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
