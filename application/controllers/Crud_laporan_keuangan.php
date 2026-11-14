<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Laporan Keuangan (Fin & Acc): Laba Rugi, Neraca, Arus Kas, Perubahan Ekuitas, Ringkasan, Catatan Kualitas Data.
// Hanya MEMBACA data COA (tabel accounting); lihat penjelasan perhitungan di Laporan_keuangan_model.
// Akses sama dengan sub menu Fin & Acc lain: Developer, Administrator, Staff Accounting.
class Crud_laporan_keuangan extends CI_Controller
{
    const TAB = ['ringkasan', 'laba-rugi', 'neraca', 'arus-kas', 'ekuitas', 'catatan', 'semua'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Laporan_keuangan_model');
        $this->load->helper('laporan');
    }

    private function cek_akses()
    {
        if (!in_array($this->session->level, ['1', '2', '3'])) {
            redirect(base_url('panel'));
            exit;
        }
    }

    public function index()
    {
        $this->cek_akses();

        $tab = $this->input->get('tab');
        $data = $this->bangun();
        $data['d'] = $data; // partial laporan memakai satu array $d
        $data['tab'] = in_array($tab, self::TAB, true) ? $tab : 'ringkasan';
        $data['preset'] = $this->preset_link();
        $this->load->view('backend/v_laporan_keuangan', $data);
    }

    public function export()
    {
        $this->cek_akses();

        $data = $this->bangun();
        $data['d'] = $data;
        $nama = 'Laporan-Keuangan_' . $data['dari'] . '_sd_' . $data['sampai'] . '.xls';
        $this->output
            ->set_header('Content-Type: application/vnd.ms-excel; charset=utf-8')
            ->set_header('Content-Disposition: attachment; filename="' . $nama . '"')
            ->set_header('Cache-Control: no-store');
        $this->load->view('backend/v_laporan_keuangan_excel', $data);
    }

    // ---------------------------------------------------------------- rincian sumber angka (dimuat lewat AJAX ke jendela "Lihat detail")

    const RINCIAN = ['pendapatan', 'laba_kotor', 'laba_bersih', 'uang_masuk', 'kas', 'piutang', 'liabilitas', 'ekuitas'];

    // Potongan HTML (tanpa kerangka halaman) yang menjelaskan dari mana sebuah angka di Ringkasan berasal
    public function detail($kunci = null)
    {
        $this->cek_akses();
        if (!in_array($kunci, self::RINCIAN, true)) {
            $this->output->set_status_header(404)->set_output('Rincian tidak dikenal.');
            return;
        }
        $m = $this->Laporan_keuangan_model;
        list($dari, $sampai) = $this->baca_periode();
        $data = ['kunci' => $kunci, 'dari' => $dari, 'sampai' => $sampai];

        $agg_p = $m->agregat($m->saldo_akun($dari, $sampai));       // hanya periode
        $agg_k = $m->agregat($m->saldo_akun(null, $sampai));        // kumulatif sampai tanggal akhir

        if ($kunci === 'pendapatan') {
            $trx = $m->transaksi_rinci($dari, $sampai, function ($k, $j) { return $j === 'pendapatan'; });
            $data['akun'] = $m->per_akun($trx);
            $data['project'] = $m->per_project($trx);
            $data['n'] = count($trx);
            $data['total'] = array_sum(array_column($data['akun'], 'jumlah'));
        } elseif ($kunci === 'laba_kotor' || $kunci === 'laba_bersih') {
            $data['lr'] = $m->laba_rugi($agg_p);
        } elseif ($kunci === 'uang_masuk') {
            $trx = $m->transaksi_rinci($dari, $sampai, function ($k, $j) { return $j === 'kas' || $j === 'pendapatan_lain'; });
            $data['akun'] = $m->per_akun($trx);
            $data['project'] = $m->per_project($trx);
            $data['n'] = count($trx);
            $data['total'] = array_sum(array_column($data['akun'], 'jumlah'));
            $data['pending'] = $m->kwitansi_pending($dari, $sampai);
        } elseif ($kunci === 'kas') {
            $lr = $m->laba_rugi($agg_k);
            $j = function ($k) use ($agg_k) { return $agg_k['jenis'][$k] ?? 0; };
            // [label, tanda, jumlah (positif), kunci jenis untuk "lihat transaksi"]
            $data['baris'] = [
                ['Uang masuk dari klien (kwitansi Paid, akun Kas & Bank 1000.xx)', 1, $j('kas'), 'kas'],
                ['Pendapatan di luar usaha (7100)', 1, $j('pendapatan_lain'), 'pendapatan_lain'],
                ['Setoran modal (3000)', 1, $j('modal'), 'modal'],
                ['Saldo awal modal (3100)', 1, $j('saldo_awal'), 'saldo_awal'],
                ['Pinjaman diterima dikurangi cicilan (akun 2000 / 2300)', 1, $j('liabilitas'), 'liabilitas'],
                ['Harga pokok pendapatan (5xxx)', -1, $lr['hpp']['total'], 'hpp'],
                ['Biaya operasional event (6205 / 6207)', -1, $lr['event']['total'], 'beban:event'],
                ['Beban pemasaran (6100)', -1, $lr['pemasaran']['total'], 'beban:pemasaran'],
                ['Gaji & tunjangan (6201)', -1, $lr['gaji']['total'], 'beban:gaji'],
                ['Beban umum & administrasi (6200 / 6203)', -1, $lr['umum']['total'], 'beban:umum'],
                ['Beban di luar usaha (7200)', -1, $lr['lain_beban']['total'], 'beban_lain'],
                ['Uang muka & piutang lain (1100.xx)', -1, $j('piutang_lain'), 'piutang_lain'],
                ['Biaya dibayar dimuka (1300 - 1600)', -1, $j('dimuka'), 'dimuka'],
                ['Pembelian aset tetap (17xx)', -1, $j('aset_tetap'), 'aset_tetap'],
                ['Penarikan modal / deviden (3200)', -1, $j('deviden'), 'deviden'],
            ];
            $data['total'] = $m->kas_dari($agg_k);
        } elseif ($kunci === 'piutang') {
            $data['piutang'] = $m->piutang_rinci($sampai);
            $data['piutang_coa'] = $agg_k['jenis']['piutang_usaha'] ?? 0;
        } elseif ($kunci === 'liabilitas') {
            $akun = [];
            foreach ($agg_k['akun']['liabilitas'] ?? [] as $kode => $a) {
                $akun[$kode] = ['kode' => $kode, 'nama' => $a['nama'], 'jumlah' => $a['jumlah'], 'n' => null];
            }
            $data['akun'] = $akun;
            $data['total'] = array_sum(array_column($akun, 'jumlah'));
            $data['hutang'] = $m->hutang_rinci();
        } elseif ($kunci === 'ekuitas') {
            $lr = $m->laba_rugi($agg_k);
            $j = function ($k) use ($agg_k) { return $agg_k['jenis'][$k] ?? 0; };
            $data['modal'] = $j('modal');
            $data['saldo_awal'] = $j('saldo_awal');
            $data['deviden'] = $j('deviden');
            $data['lr'] = $lr;
            $data['total'] = $j('modal') + $j('saldo_awal') + $lr['laba_bersih'] - $j('deviden');
        }

        $this->output->set_content_type('text/html', 'UTF-8');
        $this->load->view('backend/laporan/v_lk_detail', $data);
    }

    // Daftar transaksi COA asal sebuah angka: satu akun (?kode=1000.02) atau satu jenis (?jenis=beban:event); ?kum=1 = dari awal sampai tanggal akhir
    public function transaksi()
    {
        $this->cek_akses();
        list($dari, $sampai) = $this->baca_periode();
        $kum = $this->input->get('kum') === '1';
        $kode = $this->input->get('kode');
        $jenis_req = $this->input->get('jenis');
        $kode = is_string($kode) && preg_match('/^[0-9A-Za-z.]{1,20}$/', $kode) ? $kode : '';
        $jenis_req = is_string($jenis_req) && preg_match('/^[a-z_]{1,30}(:[a-z]{1,20})?$/', $jenis_req) ? $jenis_req : '';
        if ($kode === '' && $jenis_req === '') {
            $this->output->set_status_header(400)->set_output('Permintaan tidak lengkap.');
            return;
        }

        $cocok = function ($k, $j, $s) use ($kode, $jenis_req) {
            if ($kode !== '') {
                return $k === $kode;
            }
            $bagian = explode(':', $jenis_req, 2);
            return $j === $bagian[0] && (!isset($bagian[1]) || $s === $bagian[1]);
        };
        $trx = $this->Laporan_keuangan_model->transaksi_rinci($kum ? null : $dari, $sampai, $cocok);
        $this->output->set_content_type('text/html', 'UTF-8');
        $this->load->view('backend/laporan/v_lk_transaksi', [
            'trx' => $trx, 'kum' => $kum, 'dari' => $dari, 'sampai' => $sampai, 'maks' => 500,
        ]);
    }
    // ---------------------------------------------------------------- periode

    private function tanggal_valid($tgl)
    {
        if (!is_string($tgl)) {
            return null; // mis. ?dari[]=x
        }
        $tgl = trim($tgl);
        $d = DateTime::createFromFormat('Y-m-d', $tgl);
        // tahun dibatasi 2000-2100 supaya rentang absurd (mis. tahun 0001 s/d 9999) tidak membebani server
        return ($d && $d->format('Y-m-d') === $tgl && (int) $d->format('Y') >= 2000 && (int) $d->format('Y') <= 2100) ? $tgl : null;
    }

    private function geser($tgl, $modifier)
    {
        $d = new DateTime($tgl);
        $d->modify($modifier);
        return $d->format('Y-m-d');
    }

    // Geser N tahun; 29 Feb dipetakan ke 28 Feb kalau tahun tujuan bukan kabisat (modify('-1 year') akan melompat ke 1 Mar)
    private function geser_tahun($tgl, $n)
    {
        $d = new DateTime($tgl);
        $y = (int) $d->format('Y') + $n;
        $m = (int) $d->format('n');
        $hari = min((int) $d->format('j'), (int) (new DateTime(sprintf('%04d-%02d-01', $y, $m)))->format('t'));
        return sprintf('%04d-%02d-%02d', $y, $m, $hari);
    }

    // Default: 1 Januari tahun ini s/d hari ini
    private function baca_periode()
    {
        $dari = $this->tanggal_valid($this->input->get('dari'));
        $sampai = $this->tanggal_valid($this->input->get('sampai'));
        if ($dari === null) {
            $dari = date('Y-01-01');
        }
        if ($sampai === null) {
            $sampai = date('Y-m-d');
        }
        if ($dari > $sampai) {
            list($dari, $sampai) = [$sampai, $dari];
        }
        $banding = $this->input->get('banding');
        if (!in_array($banding, ['none', 'sebelumnya', 'tahun_lalu'], true)) {
            $banding = 'sebelumnya';
        }
        return [$dari, $sampai, $banding];
    }

    // Periode pembanding: 'sebelumnya' = rentang yang sama panjangnya tepat sebelum periode ini; 'tahun_lalu' = digeser 1 tahun
    private function periode_banding($dari, $sampai, $banding)
    {
        if ($banding === 'tahun_lalu') {
            return [$this->geser_tahun($dari, -1), $this->geser_tahun($sampai, -1)];
        }
        $hari = (new DateTime($dari))->diff(new DateTime($sampai))->days + 1;
        $sampai2 = $this->geser($dari, '-1 day');
        $dari2 = $this->geser($sampai2, '-' . ($hari - 1) . ' days');
        return [$dari2, $sampai2];
    }

    private function preset_link()
    {
        $hari_ini = date('Y-m-d');
        $bulan = date('Y-m-01');
        $kuartal_awal = date('Y') . '-' . str_pad((string) ((int) (floor((date('n') - 1) / 3) * 3) + 1), 2, '0', STR_PAD_LEFT) . '-01';
        $bulan_lalu_awal = $this->geser($bulan, '-1 month');
        $tahun_lalu = (int) date('Y') - 1;
        return [
            'Bulan ini' => [$bulan, $hari_ini],
            'Bulan lalu' => [$bulan_lalu_awal, $this->geser($bulan, '-1 day')],
            'Kuartal ini' => [$kuartal_awal, $hari_ini],
            'Tahun ini' => [date('Y-01-01'), $hari_ini],
            'Tahun lalu' => [$tahun_lalu . '-01-01', $tahun_lalu . '-12-31'],
            'Semua data' => [$this->Laporan_keuangan_model->tanggal_awal(), $hari_ini],
        ];
    }

    // ---------------------------------------------------------------- penyusunan data laporan

    private function bangun()
    {
        $m = $this->Laporan_keuangan_model;
        list($dari, $sampai, $banding) = $this->baca_periode();
        $sebelum_dari = $this->geser($dari, '-1 day');

        // periode berjalan
        $agg = $m->agregat($m->saldo_akun($dari, $sampai));
        $lr = $m->laba_rugi($agg);

        // posisi kumulatif (awal & akhir periode)
        $agg_awal = $m->agregat($m->saldo_akun(null, $sebelum_dari));
        $agg_akhir = $m->agregat($m->saldo_akun(null, $sampai));
        $neraca_awal = $m->neraca($agg_awal, $sebelum_dari);
        $neraca_akhir = $m->neraca($agg_akhir, $sampai);

        $arus = $m->arus_kas($agg, $neraca_awal['kas']);
        $ekuitas = $m->perubahan_ekuitas($neraca_awal, $neraca_akhir, $agg, $lr);

        // pembanding (laba rugi & arus kas)
        $pembanding = null;
        if ($banding !== 'none') {
            list($dari2, $sampai2) = $this->periode_banding($dari, $sampai, $banding);
            $agg2 = $m->agregat($m->saldo_akun($dari2, $sampai2));
            $kas_awal2 = $m->kas_dari($m->agregat($m->saldo_akun(null, $this->geser($dari2, '-1 day'))));
            $cakupan = $m->cakupan_invoice($dari2, $sampai2);
            $pembanding = [
                'dari' => $dari2, 'sampai' => $sampai2,
                'lr' => $m->laba_rugi($agg2), 'arus' => $m->arus_kas($agg2, $kas_awal2),
                // data COA periode pembanding belum lengkap -> perbandingan & pertumbuhan tidak bermakna
                'cakupan' => $cakupan, 'cakupan_rendah' => ($cakupan !== null && $cakupan < 90),
            ];
        }

        $tren = $m->tren_bulanan($dari, $sampai);
        $tren_terpotong = $m->tren_terpotong;
        list($burn, $burn_bulan) = $m->burn_rata($sampai);
        $kualitas = $m->kualitas($dari, $sampai);
        $operasional = $m->info_operasional($dari, $sampai);

        return [
            'dari' => $dari, 'sampai' => $sampai, 'banding' => $banding, 'sebelum_dari' => $sebelum_dari,
            'lr' => $lr, 'arus' => $arus, 'neraca_awal' => $neraca_awal, 'neraca_akhir' => $neraca_akhir,
            'ekuitas' => $ekuitas, 'pembanding' => $pembanding, 'tren' => $tren, 'tren_terpotong' => $tren_terpotong,
            'piutang_coa' => $agg_akhir['jenis']['piutang_usaha'] ?? 0,
            'kualitas' => $kualitas, 'operasional' => $operasional,
            'kpi' => $this->hitung_kpi($lr, $arus, $neraca_akhir, $pembanding, $burn, $burn_bulan, $dari, $sampai),
            'dibuat' => date('d-m-Y H:i'),
        ];
    }

    private function hitung_kpi($lr, $arus, $neraca, $pembanding, $burn, $burn_bulan, $dari, $sampai)
    {
        $pend = $lr['total_pendapatan'];
        $hari = (new DateTime($dari))->diff(new DateTime($sampai))->days + 1;

        $pertumbuhan = null;
        if ($pembanding !== null && !$pembanding['cakupan_rendah'] && $pembanding['lr']['total_pendapatan'] > 0) {
            $pertumbuhan = ($pend - $pembanding['lr']['total_pendapatan']) / $pembanding['lr']['total_pendapatan'] * 100;
        }

        $liab = $neraca['liab_akun']; // liabilitas dari akun COA (tanpa selisih rekonsiliasi)
        $piutang = $neraca['aset']['piutang_usaha']['jumlah'];

        return [
            'pendapatan' => $pend,
            'laba_kotor' => $lr['laba_kotor'],
            'margin_kotor' => $pend > 0 ? $lr['laba_kotor'] / $pend * 100 : null,
            'laba_bersih' => $lr['laba_bersih'],
            'margin_bersih' => $pend > 0 ? $lr['laba_bersih'] / $pend * 100 : null,
            'penerimaan' => $arus['total_masuk_operasi'],
            'kas_operasi' => $arus['kas_operasi'],
            'kas_akhir' => $neraca['kas'],
            'piutang' => $piutang,
            'liabilitas' => $liab,
            'selisih' => $neraca['selisih'],
            'ekuitas' => $neraca['total_ekuitas'],
            'rasio_kas_liabilitas' => $liab > 0 ? $neraca['kas'] / $liab : null,
            'rasio_hutang_ekuitas' => $neraca['total_ekuitas'] > 0 ? $liab / $neraca['total_ekuitas'] : null,
            // hari penjualan yang belum tertagih (DSO) = piutang akhir / pendapatan periode x jumlah hari periode
            'dso' => $pend > 0 ? $piutang / $pend * $hari : null,
            'burn' => $burn,
            'burn_bulan' => $burn_bulan,
            'runway' => ($burn > 0 && $neraca['kas'] > 0) ? $neraca['kas'] / $burn : null,
            'pertumbuhan' => $pertumbuhan,
        ];
    }
}
