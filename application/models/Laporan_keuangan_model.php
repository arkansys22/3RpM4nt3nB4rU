<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Laporan Keuangan (Fin & Acc): Laba Rugi, Neraca, Arus Kas, Perubahan Ekuitas, Ringkasan/KPI dan Catatan Kualitas Data.
| SEMUA angka berasal dari tabel `accounting` (COA) -- model ini hanya MEMBACA, tidak pernah menulis.
|
| Cara membaca data COA di sistem ini (single-entry, hampir semua nominal positif):
|   - Invoice          -> baris +total di Piutang (110302.xx) dan +total di Pendapatan (4000.xx). Nominal baris Piutang SEHARUSNYA
|                         dikurangi saat kwitansi Paid, tetapi di data nyata sebagian baris tidak ikut berkurang. Karena itu Piutang
|                         di laporan TIDAK dibaca dari akun 110302 melainkan dihitung: total invoice yang sudah berakun COA dikurangi
|                         kwitansi Paid proyek yang sama sampai tanggal laporan (piutang_hitung()).
|   - Kwitansi Paid    -> baris +jumlah di Kas/Bank (1000.xx) = uang masuk dari klien.
|   - Biaya dibayar    -> baris +jumlah di akun beban (5xxx/6xxx/7200) = uang keluar. Tidak ada baris pengurang di Kas.
|                         Edit transaksi Finance Project dulu menambah baris baru (bukan mengubah baris lama), sehingga ada baris biaya
|                         ganda dalam satu transaksi; baris yang bukan terbaru diabaikan laporan (kondisi_dasar()).
|   - Utang ke vendor per event (210102.xx) hanya komitmen (belum biaya, belum uang keluar); biayanya baru tercatat di
|     6207.xx saat dibayar. Karena itu 210102.xx TIDAK masuk Neraca, hanya dicatat sebagai komitmen.
|   - Hutang (menu Hutang): +pokok / -cicilan di 2000 atau 2300.  Modal (menu Modal): 3000 / 3100 (+), Deviden 320002 (kode lama 3200.02 juga dikenali; +, pengurang).
|
| Karena Kas hanya mencatat uang masuk, Saldo Kas DIHITUNG dari seluruh arus transaksi (lihat kas_dari()). Dengan aturan ini
| Neraca selalu seimbang kecuali ada selisih data, yang ditampilkan apa adanya sebagai "selisih rekonsiliasi".
*/
class Laporan_keuangan_model extends CI_Model
{
    private $cache_nama = null;

    // ------------------------------------------------------------------ COA

    public function nama_akun($kode)
    {
        if ($this->cache_nama === null) {
            $this->cache_nama = [];
            foreach ($this->db->get('operational_kategori')->result() as $r) {
                $this->cache_nama[(string) $r->nomer_kategori] = $r->nama_kategori;
            }
        }
        $kode = (string) $kode;
        // kode Deviden lama (3200.02) dan baru (320002) dianggap akun yang sama untuk nama
        if (!isset($this->cache_nama[$kode]) && $kode === '3200.02') {
            $kode = '320002';
        }
        return $this->cache_nama[$kode] ?? null;
    }

    // [jenis, sub] untuk sebuah kode akun. jenis menentukan perlakuan di laporan (lihat komentar atas).
    public static function klasifikasi($kode)
    {
        $kode = (string) $kode;
        $dasar = explode('.', $kode)[0];
        $dasar4 = substr($dasar, 0, 4);

        if ($kode === '320001') {
            return ['sistem', null]; // RETAINED EARNING: akun sistem, dihitung dari laba, bukan input
        }
        if (strpos($kode, '210102') === 0) {
            return ['komitmen_vendor', null];
        }

        switch (substr($kode, 0, 1)) {
            case '1':
                if (strpos($kode, '1000') === 0) return ['kas', null];
                if (strpos($kode, '110302') === 0) return ['piutang_usaha', null];
                if (strpos($kode, '11') === 0) return ['piutang_lain', null];
                if (strpos($kode, '171') === 0) return ['akumulasi', null];
                if (strpos($kode, '17') === 0) return ['aset_tetap', null];
                return ['dimuka', null];
            case '2':
                return ['liabilitas', $dasar4 === '2000' ? 'pendek' : ($dasar4 === '2300' ? 'panjang' : 'lain')];
            case '3':
                if (strpos($kode, '3000') === 0) return ['modal', null];
                if ($dasar4 === '3100') return ['saldo_awal', null];
                if (strpos($kode, '3200.') === 0 || $kode === '320002') return ['deviden', null];
                return ['lainnya', null];
            case '4':
                return ['pendapatan', null];
            case '5':
                return ['hpp', null];
            case '6':
                if ($dasar4 === '6205' || $dasar4 === '6207') return ['beban', 'event'];
                if ($dasar4 === '6100') return ['beban', 'pemasaran'];
                if ($dasar4 === '6201') return ['beban', 'gaji'];
                return ['beban', 'umum'];
            case '7':
                if ($dasar4 === '7100') return ['pendapatan_lain', null];
                if ($dasar4 === '7200') return ['beban_lain', null];
                return ['lainnya', null];
        }
        return ['lainnya', null];
    }

    // ------------------------------------------------------------------ data mentah

    // Syarat SQL dasar untuk tabel accounting (alias a): hanya baris ber-akun, dan untuk akun biaya/pendapatan lain (5xxx,6xxx,7xxx)
    // baris lama yang sudah digantikan baris lebih baru pada transaksi yang sama (hasil edit lama) diabaikan.
    private function kondisi_dasar($a = 'a')
    {
        return "{$a}.accounting_nomer_kategori <> '' AND NOT ({$a}.accounting_nomer_kategori REGEXP '^[567]' AND EXISTS (
                    SELECT 1 FROM accounting b WHERE b.accounting_id_session = {$a}.accounting_id_session
                    AND b.accounting_id > {$a}.accounting_id AND b.accounting_nomer_kategori REGEXP '^[567]'))";
    }

    // Total per akun dalam rentang tanggal (null = tanpa batas). Baris tanpa kategori diabaikan (dilaporkan di Catatan).
    // return [kode => ['total' =>, 'pos' => (jumlah baris positif), 'neg' => (jumlah baris negatif)]]
    public function saldo_akun($dari, $sampai)
    {
        $sql = "SELECT a.accounting_nomer_kategori AS kode,
                       SUM(a.accounting_nominal) AS total,
                       SUM(CASE WHEN a.accounting_nominal > 0 THEN a.accounting_nominal ELSE 0 END) AS pos,
                       SUM(CASE WHEN a.accounting_nominal < 0 THEN a.accounting_nominal ELSE 0 END) AS neg
                FROM accounting a
                WHERE " . $this->kondisi_dasar('a');
        $bind = [];
        if ($dari !== null) {
            $sql .= ' AND a.accounting_tanggal >= ?';
            $bind[] = $dari;
        }
        if ($sampai !== null) {
            $sql .= ' AND a.accounting_tanggal <= ?';
            $bind[] = $sampai;
        }
        $sql .= ' GROUP BY a.accounting_nomer_kategori';

        $hasil = [];
        foreach ($this->db->query($sql, $bind)->result() as $r) {
            $hasil[(string) $r->kode] = ['total' => (float) $r->total, 'pos' => (float) $r->pos, 'neg' => (float) $r->neg];
        }
        return $hasil;
    }

    // Kelompokkan saldo per jenis. return ['jenis' => [jenis => total], 'akun' => [jenis => [kode => info]]]
    public function agregat($saldo)
    {
        $jenis = [];
        $akun = [];
        foreach ($saldo as $kode => $s) {
            list($j, $sub) = self::klasifikasi($kode);
            $jenis[$j] = ($jenis[$j] ?? 0) + $s['total'];
            $akun[$j][$kode] = [
                'kode' => $kode, 'nama' => $this->nama_akun($kode) ?: 'Akun ' . $kode, 'sub' => $sub,
                'jumlah' => $s['total'], 'pos' => $s['pos'], 'neg' => $s['neg'],
            ];
        }
        foreach ($akun as $j => $daftar) {
            ksort($akun[$j], SORT_NATURAL);
        }
        return ['jenis' => $jenis, 'akun' => $akun];
    }

    private static function j($agg, $jenis)
    {
        return $agg['jenis'][$jenis] ?? 0;
    }

    // ------------------------------------------------------------------ Laba Rugi

    private function seksi($agg, $jenis, $sub, $label)
    {
        $akun = [];
        $total = 0;
        foreach ($agg['akun'][$jenis] ?? [] as $kode => $a) {
            if ($sub !== null && $a['sub'] !== $sub) {
                continue;
            }
            $akun[$kode] = $a;
            $total += $a['jumlah'];
        }
        return ['label' => $label, 'akun' => $akun, 'total' => $total];
    }

    public function laba_rugi($agg)
    {
        $lr = [
            'pendapatan' => $this->seksi($agg, 'pendapatan', null, 'Pendapatan Usaha'),
            'hpp' => $this->seksi($agg, 'hpp', null, 'Harga Pokok Pendapatan'),
            'event' => $this->seksi($agg, 'beban', 'event', 'Beban Operasional Event'),
            'pemasaran' => $this->seksi($agg, 'beban', 'pemasaran', 'Beban Pemasaran'),
            'gaji' => $this->seksi($agg, 'beban', 'gaji', 'Gaji & Tunjangan Karyawan'),
            'umum' => $this->seksi($agg, 'beban', 'umum', 'Beban Umum & Administrasi'),
            'lain_pendapatan' => $this->seksi($agg, 'pendapatan_lain', null, 'Pendapatan Di Luar Usaha'),
            'lain_beban' => $this->seksi($agg, 'beban_lain', null, 'Beban Di Luar Usaha'),
        ];
        $lr['total_pendapatan'] = $lr['pendapatan']['total'];
        $lr['total_beban_pokok'] = $lr['hpp']['total'] + $lr['event']['total'];
        $lr['laba_kotor'] = $lr['total_pendapatan'] - $lr['total_beban_pokok'];
        $lr['total_beban_usaha'] = $lr['pemasaran']['total'] + $lr['gaji']['total'] + $lr['umum']['total'];
        $lr['laba_usaha'] = $lr['laba_kotor'] - $lr['total_beban_usaha'];
        $lr['total_lain'] = $lr['lain_pendapatan']['total'] - $lr['lain_beban']['total'];
        $lr['laba_bersih'] = $lr['laba_usaha'] + $lr['total_lain'];
        $lr['total_beban'] = $lr['total_beban_pokok'] + $lr['total_beban_usaha'] + $lr['lain_beban']['total'];
        return $lr;
    }

    // ------------------------------------------------------------------ Kas (diturunkan dari arus)

    // Saldo kas dari agregat KUMULATIF (atau arus bersih periode bila agregat periode):
    //   uang masuk (Kas 1000.xx, pendapatan lain, modal, saldo awal modal, pinjaman)
    // - uang keluar (HPP, beban, beban lain, piutang lain & uang muka, biaya dimuka, aset tetap, deviden)
    public function kas_dari($agg)
    {
        return self::j($agg, 'kas') + self::j($agg, 'pendapatan_lain')
            + self::j($agg, 'modal') + self::j($agg, 'saldo_awal') + self::j($agg, 'liabilitas')
            - self::j($agg, 'hpp') - self::j($agg, 'beban') - self::j($agg, 'beban_lain')
            - self::j($agg, 'piutang_lain') - self::j($agg, 'dimuka') - self::j($agg, 'aset_tetap')
            - self::j($agg, 'deviden');
    }

    // ------------------------------------------------------------------ Arus Kas (metode langsung)

    public function arus_kas($agg, $kas_awal)
    {
        $lr = $this->laba_rugi($agg);
        $j = function ($k) use ($agg) { return self::j($agg, $k); };

        // Pinjaman: baris positif = diterima, baris negatif = dibayar (menu Hutang). Data lama 2000/2300 semuanya positif.
        $pinjaman_masuk = 0;
        $pinjaman_keluar = 0;
        foreach ($agg['akun']['liabilitas'] ?? [] as $a) {
            $pinjaman_masuk += $a['pos'];
            $pinjaman_keluar += -$a['neg'];
        }

        $op_masuk = [
            ['label' => 'Penerimaan dari pelanggan (Kas & Bank)', 'jumlah' => $j('kas')],
            ['label' => 'Pendapatan di luar usaha', 'jumlah' => $j('pendapatan_lain')],
        ];
        $op_keluar = [
            ['label' => 'Pembayaran biaya operasional event', 'jumlah' => $lr['event']['total']],
            ['label' => 'Pembayaran harga pokok pendapatan', 'jumlah' => $lr['hpp']['total']],
            ['label' => 'Pembayaran gaji & tunjangan', 'jumlah' => $lr['gaji']['total']],
            ['label' => 'Pembayaran beban pemasaran', 'jumlah' => $lr['pemasaran']['total']],
            ['label' => 'Pembayaran beban umum & administrasi', 'jumlah' => $lr['umum']['total']],
            ['label' => 'Pembayaran beban di luar usaha', 'jumlah' => $lr['lain_beban']['total']],
            ['label' => 'Pembayaran uang muka, biaya dibayar dimuka & piutang lain', 'jumlah' => $j('piutang_lain') + $j('dimuka')],
        ];
        $kas_operasi = array_sum(array_column($op_masuk, 'jumlah')) - array_sum(array_column($op_keluar, 'jumlah'));

        $inv_keluar = [['label' => 'Pembelian aset tetap', 'jumlah' => $j('aset_tetap')]];
        $kas_investasi = -array_sum(array_column($inv_keluar, 'jumlah'));

        $fin_masuk = [
            ['label' => 'Setoran modal', 'jumlah' => $j('modal')],
            ['label' => 'Saldo awal modal', 'jumlah' => $j('saldo_awal')],
            ['label' => 'Penerimaan pinjaman / hutang', 'jumlah' => $pinjaman_masuk],
        ];
        $fin_keluar = [
            ['label' => 'Pembayaran pinjaman / hutang', 'jumlah' => $pinjaman_keluar],
            ['label' => 'Penarikan modal / deviden', 'jumlah' => $j('deviden')],
        ];
        $kas_pendanaan = array_sum(array_column($fin_masuk, 'jumlah')) - array_sum(array_column($fin_keluar, 'jumlah'));

        $perubahan = $kas_operasi + $kas_investasi + $kas_pendanaan;
        return [
            'op_masuk' => $op_masuk, 'op_keluar' => $op_keluar, 'kas_operasi' => $kas_operasi,
            'inv_keluar' => $inv_keluar, 'kas_investasi' => $kas_investasi,
            'fin_masuk' => $fin_masuk, 'fin_keluar' => $fin_keluar, 'kas_pendanaan' => $kas_pendanaan,
            'perubahan' => $perubahan, 'kas_awal' => $kas_awal, 'kas_akhir' => $kas_awal + $perubahan,
            'total_masuk_operasi' => array_sum(array_column($op_masuk, 'jumlah')),
            'total_keluar_operasi' => array_sum(array_column($op_keluar, 'jumlah')),
        ];
    }

    // ------------------------------------------------------------------ Piutang (dihitung)

    // Sisa piutang per tanggal: invoice yang sudah diberi akun COA (sampai tanggal itu) dikurangi kwitansi Paid proyek yang sama
    // (sampai tanggal itu), per proyek, tidak pernah negatif. Dipakai karena nominal baris Piutang di COA tidak selalu ikut berkurang.
    public function piutang_hitung($tgl)
    {
        $r = $this->db->query(
            "SELECT COALESCE(SUM(GREATEST(t.tagihan - COALESCE(k.dibayar, 0), 0)), 0) AS sisa
             FROM (SELECT id_session, SUM(total_bill) AS tagihan FROM payment
                   WHERE total_bill > 0 AND kategori <> '' AND `date` <= ? GROUP BY id_session) t
             LEFT JOIN (SELECT id_session, SUM(total_paid) AS dibayar FROM payment
                        WHERE total_bill = 0 AND status = 'Paid' AND `date` <= ? GROUP BY id_session) k
                    ON k.id_session = t.id_session",
            [$tgl, $tgl]
        )->row();
        return (float) $r->sisa;
    }

    // ------------------------------------------------------------------ Neraca

    // $agg_kumulatif = agregat seluruh baris sampai tanggal neraca; $lr_kumulatif = laba rugi kumulatif sampai tanggal itu
    public function neraca($agg, $tgl)
    {
        $j = function ($k) use ($agg) { return self::j($agg, $k); };
        $lr = $this->laba_rugi($agg);

        $kas = $this->kas_dari($agg);
        $aset = [
            'kas' => ['label' => 'Kas & Bank (hasil perhitungan arus transaksi)', 'jumlah' => $kas],
            'piutang_usaha' => ['label' => 'Piutang Usaha (sisa tagihan, dihitung dari invoice & kwitansi)', 'jumlah' => $this->piutang_hitung($tgl)],
            'piutang_lain' => ['label' => 'Piutang & Uang Muka Lainnya', 'jumlah' => $j('piutang_lain')],
            'dimuka' => ['label' => 'Biaya Dibayar Dimuka & Aset Lancar Lainnya', 'jumlah' => $j('dimuka')],
            'aset_tetap' => ['label' => 'Aset Tetap (nilai perolehan)', 'jumlah' => $j('aset_tetap')],
            'akumulasi' => ['label' => 'Akumulasi Penyusutan', 'jumlah' => -$j('akumulasi')],
        ];
        $total_aset = array_sum(array_column($aset, 'jumlah'));

        $liab = [];
        foreach ($agg['akun']['liabilitas'] ?? [] as $kode => $a) {
            $liab[$kode] = ['label' => $a['nama'] . ' (' . $kode . ')', 'jumlah' => $a['jumlah'], 'sub' => $a['sub']];
        }
        $total_liab_akun = array_sum(array_column($liab, 'jumlah'));

        $laba_kumulatif = $lr['laba_bersih'];
        $ekuitas = [
            'modal' => ['label' => 'Modal Disetor', 'jumlah' => $j('modal')],
            'saldo_awal' => ['label' => 'Saldo Awal Modal (Opening Balance Equity)', 'jumlah' => $j('saldo_awal')],
            'laba' => ['label' => 'Laba (Rugi) Ditahan & Berjalan', 'jumlah' => $laba_kumulatif],
            'deviden' => ['label' => 'Penarikan Modal / Deviden', 'jumlah' => -$j('deviden')],
        ];
        $total_ekuitas = array_sum(array_column($ekuitas, 'jumlah'));

        // Selisih = Aset - (Liabilitas + Ekuitas). Secara aljabar = Kas (akun COA 1000) + Piutang (dihitung) - Pendapatan COA:
        // uang masuk di akun Kas yang tidak terhubung ke invoice berakun COA (mis. kwitansi untuk invoice lama yang belum
        // dikategorikan) atau sebaliknya. Ditampilkan terbuka, tidak disembunyikan.
        $selisih = $total_aset - ($total_liab_akun + $total_ekuitas);
        if (abs($selisih) < 1) {
            $selisih = 0;
        }
        $selisih_liab = $selisih > 0 ? $selisih : 0;
        $selisih_aset = $selisih < 0 ? -$selisih : 0;

        return [
            'aset' => $aset, 'selisih_aset' => $selisih_aset,
            'total_aset' => $total_aset + $selisih_aset,
            'liabilitas' => $liab, 'selisih_liab' => $selisih_liab, 'subtotal_liab_akun' => $total_liab_akun,
            'total_liabilitas' => $total_liab_akun + $selisih_liab,
            'ekuitas' => $ekuitas, 'total_ekuitas' => $total_ekuitas,
            'total_pasiva' => $total_liab_akun + $selisih_liab + $total_ekuitas,
            'selisih' => $selisih, 'kas' => $kas, 'liab_akun' => $total_liab_akun,
            'liab_pendek' => $this->jumlah_liab($agg, 'pendek') + $selisih_liab,
            'liab_panjang' => $this->jumlah_liab($agg, 'panjang'),
            'liab_lain' => $this->jumlah_liab($agg, 'lain'),
            'laba_kumulatif' => $laba_kumulatif,
        ];
    }

    private function jumlah_liab($agg, $sub)
    {
        $t = 0;
        foreach ($agg['akun']['liabilitas'] ?? [] as $a) {
            if ($a['sub'] === $sub) {
                $t += $a['jumlah'];
            }
        }
        return $t;
    }

    // ------------------------------------------------------------------ Perubahan Ekuitas

    public function perubahan_ekuitas($neraca_awal, $neraca_akhir, $agg_periode, $lr_periode)
    {
        $setoran = self::j($agg_periode, 'modal') + self::j($agg_periode, 'saldo_awal');
        $deviden = self::j($agg_periode, 'deviden');
        $baris = [
            ['label' => 'Ekuitas awal periode', 'jumlah' => $neraca_awal['total_ekuitas'], 'tebal' => true],
            ['label' => 'Setoran modal & saldo awal modal', 'jumlah' => $setoran],
            ['label' => 'Laba (rugi) bersih periode berjalan', 'jumlah' => $lr_periode['laba_bersih']],
            ['label' => 'Penarikan modal / deviden', 'jumlah' => -$deviden],
        ];
        $akhir_hitung = $neraca_awal['total_ekuitas'] + $setoran + $lr_periode['laba_bersih'] - $deviden;
        return ['baris' => $baris, 'akhir' => $akhir_hitung, 'akhir_neraca' => $neraca_akhir['total_ekuitas']];
    }

    // ------------------------------------------------------------------ Tren bulanan

    public $tren_terpotong = false;
    const TREN_MAKS_BULAN = 36;

    // Per bulan dalam rentang (maks 36 bulan terakhir; $tren_terpotong = true kalau dipotong):
    // pendapatan (4xxx), pendapatan lain (7100), total beban, laba, uang masuk & keluar operasi (sama definisinya dengan Arus Kas)
    public function tren_bulanan($dari, $sampai)
    {
        $d = new DateTime(substr($dari, 0, 7) . '-01');
        $akhir = new DateTime(substr($sampai, 0, 7) . '-01');
        $bulan = [];
        while ($d <= $akhir) {
            $bulan[$d->format('Y-m')] = ['pendapatan' => 0, 'lain' => 0, 'beban' => 0, 'kas_masuk' => 0, 'kas_keluar' => 0];
            $d->modify('+1 month');
        }
        $this->tren_terpotong = false;
        if (count($bulan) > self::TREN_MAKS_BULAN) {
            $bulan = array_slice($bulan, -self::TREN_MAKS_BULAN, self::TREN_MAKS_BULAN, true);
            $dari = array_keys($bulan)[0] . '-01';
            $this->tren_terpotong = true;
        }

        $sql = "SELECT DATE_FORMAT(a.accounting_tanggal, '%Y-%m') AS ym, a.accounting_nomer_kategori AS kode, SUM(a.accounting_nominal) AS total
                FROM accounting a
                WHERE " . $this->kondisi_dasar('a') . " AND a.accounting_tanggal >= ? AND a.accounting_tanggal <= ?
                GROUP BY ym, a.accounting_nomer_kategori";
        foreach ($this->db->query($sql, [$dari, $sampai])->result() as $r) {
            if (!isset($bulan[$r->ym])) {
                continue;
            }
            list($jenis) = self::klasifikasi($r->kode);
            $t = (float) $r->total;
            if ($jenis === 'pendapatan') {
                $bulan[$r->ym]['pendapatan'] += $t;
            } elseif ($jenis === 'pendapatan_lain') {
                $bulan[$r->ym]['lain'] += $t;
                $bulan[$r->ym]['kas_masuk'] += $t;
            } elseif ($jenis === 'kas') {
                $bulan[$r->ym]['kas_masuk'] += $t;
            } elseif (in_array($jenis, ['hpp', 'beban', 'beban_lain'], true)) {
                $bulan[$r->ym]['beban'] += $t;
                $bulan[$r->ym]['kas_keluar'] += $t;
            } elseif (in_array($jenis, ['piutang_lain', 'dimuka'], true)) {
                $bulan[$r->ym]['kas_keluar'] += $t; // uang muka / biaya dibayar dimuka: uang keluar, bukan beban
            }
        }
        foreach ($bulan as $ym => $b) {
            $bulan[$ym]['laba'] = $b['pendapatan'] + $b['lain'] - $b['beban'];
        }
        return $bulan;
    }

    // Rata-rata uang keluar operasional per bulan dari bulan-bulan PENUH terakhir (maks 6) sampai $sampai; bulan sebelum ada
    // pencatatan biaya pertama tidak dihitung. return [rata-rata, jumlah bulan]
    public function burn_rata($sampai)
    {
        $sampai = min($sampai, date('Y-m-d')); // tanggal akhir di masa depan tidak boleh mengencerkan rata-rata dengan bulan yang belum terjadi
        $akhir = ($sampai === date('Y-m-t', strtotime($sampai))) ? substr($sampai, 0, 7) : date('Y-m', strtotime(substr($sampai, 0, 7) . '-01 -1 month'));
        $awal = date('Y-m', strtotime($akhir . '-01 -5 months'));
        $pertama = $this->db->query(
            "SELECT DATE_FORMAT(MIN(a.accounting_tanggal), '%Y-%m') AS ym FROM accounting a
             WHERE a.accounting_nomer_kategori REGEXP '^[567]'"
        )->row();
        if ($pertama && $pertama->ym && $pertama->ym > $awal) {
            $awal = $pertama->ym;
        }
        if ($awal > $akhir) {
            return [0, 0];
        }
        $terpotong_utama = $this->tren_terpotong; // jangan menimpa penanda tren utama
        $bulan = $this->tren_bulanan($awal . '-01', date('Y-m-t', strtotime($akhir . '-01')));
        $this->tren_terpotong = $terpotong_utama;
        $n = count($bulan);
        return [$n ? array_sum(array_column($bulan, 'kas_keluar')) / $n : 0, $n];
    }

    // ------------------------------------------------------------------ Informasi operasional (di luar COA)

    public function info_operasional($dari, $sampai)
    {
        $dalam = $this->db->query(
            "SELECT COUNT(*) AS n, COALESCE(SUM(`value`), 0) AS nilai FROM project
             WHERE (status = 'create' OR status IS NULL) AND event_date >= ? AND event_date <= ?",
            [$dari, $sampai]
        )->row();
        $mendatang = $this->db->query(
            "SELECT COUNT(*) AS n, COALESCE(SUM(`value`), 0) AS nilai FROM project
             WHERE (status = 'create' OR status IS NULL) AND event_date > ?",
            [$sampai]
        )->row();
        return [
            'event_n' => (int) $dalam->n, 'event_nilai' => (float) $dalam->nilai,
            'mendatang_n' => (int) $mendatang->n, 'mendatang_nilai' => (float) $mendatang->nilai,
        ];
    }

    // ------------------------------------------------------------------ Kualitas & kelengkapan data

    public function kualitas($dari, $sampai)
    {
        $q = [];

        // cakupan baris COA
        $c = $this->db->query("SELECT COUNT(*) AS n, MIN(accounting_tanggal) AS mn, MAX(accounting_tanggal) AS mx FROM accounting WHERE accounting_nomer_kategori <> ''")->row();
        $q['coa'] = ['baris' => (int) $c->n, 'awal' => $c->mn, 'akhir' => $c->mx];

        // baris COA tanpa kategori akun (tidak ikut laporan)
        $q['tanpa_kategori'] = $this->db->query(
            "SELECT accounting_id, accounting_tanggal, accounting_nama_transaksi, accounting_nominal
             FROM accounting WHERE accounting_nomer_kategori = '' OR accounting_nomer_kategori IS NULL
             ORDER BY accounting_tanggal DESC LIMIT 50"
        )->result();
        $tk = $this->db->query(
            "SELECT COUNT(*) AS n, COALESCE(SUM(accounting_nominal), 0) AS t FROM accounting WHERE accounting_nomer_kategori = '' OR accounting_nomer_kategori IS NULL"
        )->row();
        $q['tanpa_kategori_total'] = (float) $tk->t;
        $q['tanpa_kategori_n'] = (int) $tk->n;

        // baris biaya lama hasil edit (sudah digantikan baris terbaru pada transaksi yang sama) -> diabaikan laporan
        $dp = $this->db->query(
            "SELECT COUNT(*) AS n, COALESCE(SUM(a.accounting_nominal), 0) AS t FROM accounting a
             WHERE a.accounting_nomer_kategori REGEXP '^[567]' AND EXISTS (
                SELECT 1 FROM accounting b WHERE b.accounting_id_session = a.accounting_id_session
                AND b.accounting_id > a.accounting_id AND b.accounting_nomer_kategori REGEXP '^[567]')"
        )->row();
        $q['duplikat_biaya'] = ['n' => (int) $dp->n, 'total' => (float) $dp->t];

        // Transaksi Finance Project & Operasional (biaya, uang muka, pinjaman, dll) yang sudah / belum punya baris di COA
        $ada = "EXISTS (SELECT 1 FROM accounting a WHERE a.accounting_id_session COLLATE utf8mb4_unicode_ci = %s.id_session COLLATE utf8mb4_unicode_ci)";
        $sql_biaya = "SELECT ym, SUM(n) AS n, SUM(nilai) AS nilai, SUM(n_coa) AS n_coa, SUM(nilai_coa) AS nilai_coa FROM (
                SELECT DATE_FORMAT(p.tanggal_transaksi, '%Y-%m') AS ym, COUNT(*) AS n, SUM(p.nominal_transaksi) AS nilai,
                       SUM(CASE WHEN " . sprintf($ada, 'p') . " THEN 1 ELSE 0 END) AS n_coa,
                       SUM(CASE WHEN " . sprintf($ada, 'p') . " THEN p.nominal_transaksi ELSE 0 END) AS nilai_coa
                FROM project_acc p WHERE p.tanggal_transaksi >= ? AND p.tanggal_transaksi <= ? GROUP BY ym
                UNION ALL
                SELECT DATE_FORMAT(o.tanggal_transaksi, '%Y-%m'), COUNT(*), SUM(o.nominal_transaksi),
                       SUM(CASE WHEN " . sprintf($ada, 'o') . " THEN 1 ELSE 0 END),
                       SUM(CASE WHEN " . sprintf($ada, 'o') . " THEN o.nominal_transaksi ELSE 0 END)
                FROM operational_acc o WHERE o.tanggal_transaksi >= ? AND o.tanggal_transaksi <= ? GROUP BY 1
            ) x GROUP BY ym ORDER BY ym";
        $rb = $this->db->query($sql_biaya, [$dari, $sampai, $dari, $sampai])->result();
        $tb = ['n' => 0, 'nilai' => 0, 'n_coa' => 0, 'nilai_coa' => 0];
        foreach ($rb as $r) {
            foreach ($tb as $k2 => $v2) {
                $tb[$k2] += (float) $r->$k2;
            }
        }
        $q['biaya_bulanan'] = $rb;
        $q['biaya_total'] = $tb;

        // Pendapatan COA yang diakui di periode ini tetapi event-nya baru berlangsung SETELAH tanggal akhir periode
        $pm = $this->db->query(
            "SELECT COUNT(DISTINCT p.id_session) AS n, COALESCE(SUM(a.accounting_nominal), 0) AS nilai
             FROM accounting a
             JOIN payment p ON p.payment_id_session COLLATE utf8mb4_unicode_ci = a.accounting_id_session COLLATE utf8mb4_unicode_ci
             JOIN project pr ON pr.id_session COLLATE utf8mb4_unicode_ci = p.id_session COLLATE utf8mb4_unicode_ci
             WHERE a.accounting_nomer_kategori LIKE '4%' AND a.accounting_tanggal >= ? AND a.accounting_tanggal <= ?
               AND pr.event_date > ? AND (pr.status = 'create' OR pr.status IS NULL)",
            [$dari, $sampai, $sampai]
        )->row();
        $q['pendapatan_dimuka'] = ['n' => (int) $pm->n, 'nilai' => (float) $pm->nilai];

        // kode akun yang dipakai transaksi tapi tidak ada di daftar COA
        $q['akun_tak_dikenal'] = [];
        foreach ($this->db->query("SELECT DISTINCT accounting_nomer_kategori AS kode FROM accounting WHERE accounting_nomer_kategori <> ''")->result() as $r) {
            if ($this->nama_akun($r->kode) === null) {
                $q['akun_tak_dikenal'][] = $r->kode;
            }
        }

        // baris di akun yang perlakuannya tidak dikenal laporan ('lainnya'/'sistem')
        $q['akun_diabaikan'] = [];
        foreach ($this->saldo_akun(null, null) as $kode => $s) {
            list($jenis) = self::klasifikasi($kode);
            if ($jenis === 'lainnya' || $jenis === 'sistem') {
                $q['akun_diabaikan'][$kode] = $s['total'];
            }
        }

        // Invoice & kwitansi (tabel payment) yang belum dikategorikan ke COA -> tidak masuk laporan.
        // Pembagi per bulan supaya jelas bulan mana yang datanya lengkap.
        $rows = $this->db->query(
            "SELECT DATE_FORMAT(`date`, '%Y-%m') AS ym,
                    SUM(CASE WHEN total_bill > 0 THEN 1 ELSE 0 END) AS inv_n,
                    SUM(CASE WHEN total_bill > 0 THEN total_bill ELSE 0 END) AS inv_nilai,
                    SUM(CASE WHEN total_bill > 0 AND kategori <> '' THEN 1 ELSE 0 END) AS inv_coa_n,
                    SUM(CASE WHEN total_bill > 0 AND kategori <> '' THEN total_bill ELSE 0 END) AS inv_coa_nilai,
                    SUM(CASE WHEN total_bill = 0 AND status = 'Paid' THEN 1 ELSE 0 END) AS kw_n,
                    SUM(CASE WHEN total_bill = 0 AND status = 'Paid' THEN total_paid ELSE 0 END) AS kw_nilai,
                    SUM(CASE WHEN total_bill = 0 AND status = 'Paid' AND kategori <> '' THEN 1 ELSE 0 END) AS kw_coa_n,
                    SUM(CASE WHEN total_bill = 0 AND status = 'Paid' AND kategori <> '' THEN total_paid ELSE 0 END) AS kw_coa_nilai,
                    SUM(CASE WHEN total_bill = 0 AND status = 'Pending' THEN 1 ELSE 0 END) AS pend_n,
                    SUM(CASE WHEN total_bill = 0 AND status = 'Pending' THEN total_paid ELSE 0 END) AS pend_nilai
             FROM payment
             WHERE `date` >= ? AND `date` <= ?
             GROUP BY ym ORDER BY ym",
            [$dari, $sampai]
        )->result();
        $tot = ['inv_n' => 0, 'inv_nilai' => 0, 'inv_coa_n' => 0, 'inv_coa_nilai' => 0, 'kw_n' => 0, 'kw_nilai' => 0,
                'kw_coa_n' => 0, 'kw_coa_nilai' => 0, 'pend_n' => 0, 'pend_nilai' => 0];
        foreach ($rows as $r) {
            foreach ($tot as $k => $v) {
                $tot[$k] += (float) $r->$k;
            }
        }
        $q['kelengkapan_bulanan'] = $rows;
        $q['kelengkapan_total'] = $tot;

        // Komitmen biaya vendor per event yang belum dibayar (tidak ada di Neraca / Laba Rugi)
        $k = $this->db->query(
            "SELECT COALESCE(SUM(u.total), 0) AS komitmen,
                    COALESCE(SUM(GREATEST(u.total - COALESCE(p.dibayar, 0), 0)), 0) AS sisa,
                    COUNT(*) AS n_project
             FROM (SELECT project_id_session, SUM(nominal_transaksi) AS total FROM project_acc_utang GROUP BY project_id_session) u
             LEFT JOIN (SELECT project_id_session, SUM(nominal_transaksi) AS dibayar FROM project_acc GROUP BY project_id_session) p
                    ON p.project_id_session COLLATE utf8mb4_unicode_ci = u.project_id_session COLLATE utf8mb4_unicode_ci"
        )->row();
        $q['komitmen'] = ['total' => (float) $k->komitmen, 'sisa' => (float) $k->sisa, 'project' => (int) $k->n_project];

        // baris COA di masa depan (setelah hari ini) -> ikut laporan kalau periodenya mencakup tanggal itu
        $q['masa_depan'] = $this->db->query(
            "SELECT COUNT(*) AS n, COALESCE(SUM(accounting_nominal), 0) AS t FROM accounting WHERE accounting_nomer_kategori <> '' AND accounting_tanggal > CURDATE()"
        )->row();

        return $q;
    }

    // Persentase nilai invoice (tabel payment) pada rentang yang sudah dikategorikan ke COA; null kalau tidak ada invoice.
    // Dipakai untuk menilai apakah periode pembanding cukup lengkap.
    public function cakupan_invoice($dari, $sampai)
    {
        $r = $this->db->query(
            "SELECT COALESCE(SUM(total_bill), 0) AS semua,
                    COALESCE(SUM(CASE WHEN kategori <> '' THEN total_bill ELSE 0 END), 0) AS coa
             FROM payment WHERE total_bill > 0 AND `date` >= ? AND `date` <= ?",
            [$dari, $sampai]
        )->row();
        return (float) $r->semua > 0 ? (float) $r->coa / (float) $r->semua * 100 : null;
    }

    // Tanggal transaksi COA paling awal (untuk preset "Semua data")
    public function tanggal_awal()
    {
        $r = $this->db->query("SELECT MIN(accounting_tanggal) AS mn FROM accounting WHERE accounting_nomer_kategori <> ''")->row();
        return $r && $r->mn ? $r->mn : date('Y-m-d');
    }
}
