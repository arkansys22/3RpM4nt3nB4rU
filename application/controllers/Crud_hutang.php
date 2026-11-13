<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Hutang (Fin & Acc). Akses sama dengan sub menu Fin & Acc lain: Developer, Administrator, Staff Accounting.
// Pencatatan ke COA dijelaskan di Hutang_model: akun 2000 Utang (jangka pendek) / 2300 Hutang Jangka Panjang.
class Crud_hutang extends CI_Controller
{
    const NOMINAL_MAKS = 2000000000; // kolom int(200) di MySQL = int 32-bit

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Hutang_model');
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

        $daftar = $this->Hutang_model->get_semua();
        $hari_ini = date('Y-m-d');
        $batas_7_hari = date('Y-m-d', strtotime('+7 days'));

        $ringkas = [
            'sisa_total' => 0, 'sisa_pendek' => 0, 'sisa_panjang' => 0,
            'dibayar_total' => 0, 'aktif' => 0, 'terlambat' => 0, 'segera' => 0,
        ];
        foreach ($daftar as $h) {
            $h->sisa = max(0, (int) $h->nominal_pokok - (int) $h->total_dibayar);
            $h->status = $this->status_hutang($h, $hari_ini);
            $ringkas['dibayar_total'] += (int) $h->total_dibayar;
            if ($h->sisa > 0) {
                $ringkas['aktif']++;
                $ringkas['sisa_total'] += $h->sisa;
                $ringkas[$h->jangka === 'Panjang' ? 'sisa_panjang' : 'sisa_pendek'] += $h->sisa;
                if ($h->status === 'Terlambat') {
                    $ringkas['terlambat']++;
                } elseif (!empty($h->tanggal_jatuh_tempo) && $h->tanggal_jatuh_tempo <= $batas_7_hari) {
                    $ringkas['segera']++;
                }
            }
        }

        $data['daftar'] = $daftar;
        $data['ringkas'] = $ringkas;
        $data['jenis_list'] = Hutang_model::JENIS;
        $data['old'] = $this->session->flashdata('old'); // isian form yang gagal divalidasi (kalau ada)
        $this->load->view('backend/v_hutang', $data);
    }

    public function store()
    {
        $this->cek_akses();

        list($data, $error) = $this->validasi_hutang_post();
        if ($error) {
            $this->session->set_flashdata('error', $error);
            $this->session->set_flashdata('old', $this->isian_form());
            redirect(base_url('hutang'));
            return;
        }

        $data['id_session'] = hash('sha256', bin2hex(random_bytes(16)));
        $data['create_by'] = $this->session->id_session;

        if (!$this->Hutang_model->tambah($data)) {
            $this->session->set_flashdata('error', 'Hutang gagal disimpan. Coba lagi.');
            redirect(base_url('hutang'));
            return;
        }

        $this->catat_log('hutang/store', $data['id_session'], 'Tambah Hutang');
        $this->session->set_flashdata('success', 'Hutang berhasil ditambahkan dan tercatat di akun COA ' . $data['kategori'] . '.');
        redirect(base_url('hutang/lihat/' . $data['id_session']));
    }

    public function lihat($id_session = null)
    {
        $this->cek_akses();

        $hutang = $this->Hutang_model->get_by_id_session($id_session);
        if (!$hutang) {
            $this->session->set_flashdata('error', 'Data hutang tidak ditemukan.');
            redirect(base_url('hutang'));
            return;
        }

        $hutang->sisa = max(0, (int) $hutang->nominal_pokok - (int) $hutang->total_dibayar);
        $hutang->status = $this->status_hutang($hutang, date('Y-m-d'));

        $data['hutang'] = $hutang;
        $data['pembayaran'] = $this->Hutang_model->get_pembayaran($id_session);
        $data['nama_akun'] = $this->nama_akun($hutang->kategori);
        $data['jenis_list'] = Hutang_model::JENIS;
        $this->load->view('backend/v_hutang_detail', $data);
    }

    public function update($id_session = null)
    {
        $this->cek_akses();

        $hutang = $this->Hutang_model->get_by_id_session($id_session);
        if (!$hutang) {
            redirect(base_url('hutang'));
            return;
        }

        list($data, $error) = $this->validasi_hutang_post();
        if ($error) {
            $this->session->set_flashdata('error', $error);
            $this->session->set_flashdata('old', $this->isian_form());
            redirect(base_url('hutang/lihat/' . $id_session));
            return;
        }

        // Pengecekan 'pokok >= total dibayar' dilakukan di model, di dalam transaksi dengan row lock
        $hasil = $this->Hutang_model->ubah($id_session, $data);
        if ($hasil !== true) {
            $this->session->set_flashdata('error', $hasil === 'kurang'
                ? 'Nominal hutang tidak boleh lebih kecil dari total yang sudah dibayar.'
                : 'Hutang gagal diperbarui. Coba lagi.');
            $this->session->set_flashdata('old', $this->isian_form());
            redirect(base_url('hutang/lihat/' . $id_session));
            return;
        }

        $this->catat_log('hutang/update', $id_session, 'Edit Hutang');
        $this->session->set_flashdata('success', 'Hutang berhasil diperbarui.');
        redirect(base_url('hutang/lihat/' . $id_session));
    }

    public function delete($id_session = null)
    {
        $this->cek_akses();

        if ($this->input->method() !== 'post') {
            redirect(base_url('hutang'));
            return;
        }

        $hutang = $this->Hutang_model->get_by_id_session($id_session);
        if (!$hutang) {
            redirect(base_url('hutang'));
            return;
        }

        if (!$this->Hutang_model->hapus($id_session)) {
            $this->session->set_flashdata('error', 'Hutang gagal dihapus. Coba lagi.');
            redirect(base_url('hutang/lihat/' . $id_session));
            return;
        }

        $this->catat_log('hutang/delete', $id_session, 'Hapus Hutang');
        $this->session->set_flashdata('success', 'Hutang beserta seluruh pembayarannya berhasil dihapus (termasuk dari akun COA).');
        redirect(base_url('hutang'));
    }

    public function bayar($id_session = null)
    {
        $this->cek_akses();

        if ($this->input->method() !== 'post') {
            redirect(base_url('hutang'));
            return;
        }

        $hutang = $this->Hutang_model->get_by_id_session($id_session);
        if (!$hutang) {
            redirect(base_url('hutang'));
            return;
        }

        $tujuan = base_url('hutang/lihat/' . $id_session);
        $nominal = $this->angka($this->input->post('nominal_bayar'));
        $tanggal = $this->tanggal_valid($this->input->post('tanggal_bayar'));
        $sisa = (int) $hutang->nominal_pokok - (int) $hutang->total_dibayar;

        if ($nominal === null || $nominal <= 0) {
            $error = 'Nominal pembayaran harus lebih dari 0.';
        } elseif ($tanggal === null) {
            $error = 'Tanggal pembayaran tidak valid.';
        } elseif ($nominal > $sisa) {
            $error = 'Nominal pembayaran melebihi sisa hutang (Rp ' . number_format(max(0, $sisa), 0, ',', '.') . ').';
        } else {
            $error = null;
        }
        if ($error) {
            $this->session->set_flashdata('error', $error);
            redirect($tujuan);
            return;
        }

        $data = [
            'id_session' => hash('sha256', bin2hex(random_bytes(16))),
            'hutang_id_session' => $id_session,
            'tanggal_bayar' => $tanggal,
            'nominal_bayar' => $nominal,
            'keterangan' => mb_substr(trim((string) $this->input->post('keterangan')), 0, 255, 'UTF-8'),
            'create_by' => $this->session->id_session,
        ];

        // Pengecekan 'tidak melebihi sisa' diulang di model di dalam transaksi dengan row lock (pembayaran bersamaan)
        $hasil = $this->Hutang_model->bayar($hutang, $data);
        if ($hasil !== true) {
            $this->session->set_flashdata('error', $hasil === 'sisa'
                ? 'Nominal pembayaran melebihi sisa hutang (sisa sudah berubah). Muat ulang halaman.'
                : 'Pembayaran gagal disimpan. Coba lagi.');
            redirect($tujuan);
            return;
        }

        $this->catat_log('hutang/bayar', $data['id_session'], 'Bayar Hutang');
        $this->session->set_flashdata('success', 'Pembayaran Rp ' . number_format($nominal, 0, ',', '.') . ' dicatat dan mengurangi saldo akun COA ' . $hutang->kategori . '.');
        redirect($tujuan);
    }

    public function bayar_hapus($id_session = null)
    {
        $this->cek_akses();

        if ($this->input->method() !== 'post') {
            redirect(base_url('hutang'));
            return;
        }

        $bayar = $this->Hutang_model->get_pembayaran_by_id_session($id_session);
        if (!$bayar) {
            redirect(base_url('hutang'));
            return;
        }

        $tujuan = base_url('hutang/lihat/' . $bayar->hutang_id_session);
        if (!$this->Hutang_model->hapus_pembayaran($id_session)) {
            $this->session->set_flashdata('error', 'Pembayaran gagal dihapus. Coba lagi.');
            redirect($tujuan);
            return;
        }

        $this->catat_log('hutang/bayar_hapus', $id_session, 'Hapus Pembayaran Hutang');
        $this->session->set_flashdata('success', 'Pembayaran dihapus; sisa hutang dan saldo akun COA dikembalikan.');
        redirect($tujuan);
    }

    // ---------------- helper ----------------

    // Baca & validasi isian form hutang (dipakai tambah dan edit). Return [data, error].
    private function validasi_hutang_post()
    {
        $nama = trim((string) $this->input->post('nama_hutang'));
        $kreditur = trim((string) $this->input->post('kreditur'));
        $jenis = $this->input->post('jenis');
        $jangka = $this->input->post('jangka');
        $nominal = $this->angka($this->input->post('nominal_pokok'));
        $tanggal = $this->tanggal_valid($this->input->post('tanggal_hutang'));
        $tempo_post = trim((string) $this->input->post('tanggal_jatuh_tempo'));
        $tempo = $tempo_post === '' ? null : $this->tanggal_valid($tempo_post);

        if ($nama === '') {
            return [null, 'Nama hutang wajib diisi.'];
        }
        if ($kreditur === '') {
            return [null, 'Pemberi pinjaman / kreditur wajib diisi.'];
        }
        if (!in_array($jenis, Hutang_model::JENIS, true)) {
            return [null, 'Jenis hutang tidak valid.'];
        }
        if (!isset(Hutang_model::AKUN[$jangka])) {
            return [null, 'Jangka hutang tidak valid.'];
        }
        if ($nominal === null || $nominal <= 0) {
            return [null, 'Nominal hutang harus lebih dari 0.'];
        }
        if ($nominal > self::NOMINAL_MAKS) {
            return [null, 'Nominal hutang terlalu besar (maksimal Rp ' . number_format(self::NOMINAL_MAKS, 0, ',', '.') . ').'];
        }
        if ($tanggal === null) {
            return [null, 'Tanggal hutang tidak valid.'];
        }
        if ($tempo_post !== '' && $tempo === null) {
            return [null, 'Tanggal jatuh tempo tidak valid.'];
        }
        if ($tempo !== null && $tempo < $tanggal) {
            return [null, 'Tanggal jatuh tempo tidak boleh sebelum tanggal hutang.'];
        }

        return [[
            'nama_hutang' => mb_substr($nama, 0, 255, 'UTF-8'),
            'kreditur' => mb_substr($kreditur, 0, 255, 'UTF-8'),
            'jenis' => $jenis,
            'jangka' => $jangka,
            'kategori' => Hutang_model::AKUN[$jangka],
            'nominal_pokok' => $nominal,
            'tanggal_hutang' => $tanggal,
            'tanggal_jatuh_tempo' => $tempo,
            'keterangan' => mb_substr(trim((string) $this->input->post('keterangan')), 0, 2000, 'UTF-8'),
        ], null];
    }

    // Isian form yang dikirim (tanpa token CSRF), disimpan ke flashdata supaya form terisi lagi setelah validasi gagal
    private function isian_form()
    {
        $isian = [];
        foreach (['nama_hutang', 'kreditur', 'jenis', 'jangka', 'nominal_pokok', 'tanggal_hutang', 'tanggal_jatuh_tempo', 'keterangan'] as $k) {
            $isian[$k] = (string) $this->input->post($k);
        }
        return $isian;
    }

    // "1.500.000" / "1500000" -> 1500000 ; selain digit -> null
    private function angka($mentah)
    {
        $bersih = str_replace('.', '', trim((string) $mentah));
        return ($bersih !== '' && ctype_digit($bersih) && strlen($bersih) <= 12) ? (int) $bersih : null;
    }

    private function tanggal_valid($tgl)
    {
        $tgl = trim((string) $tgl);
        $d = DateTime::createFromFormat('Y-m-d', $tgl);
        return ($d && $d->format('Y-m-d') === $tgl) ? $tgl : null;
    }

    private function status_hutang($h, $hari_ini)
    {
        if ((int) $h->nominal_pokok - (int) $h->total_dibayar <= 0) {
            return 'Lunas';
        }
        if (!empty($h->tanggal_jatuh_tempo) && $h->tanggal_jatuh_tempo < $hari_ini) {
            return 'Terlambat';
        }
        return 'Belum Lunas';
    }

    private function nama_akun($nomer)
    {
        $row = $this->db->get_where('operational_kategori', ['nomer_kategori' => $nomer])->row();
        return $row ? $row->nama_kategori : '';
    }

    private function catat_log($modul, $dokumen, $status)
    {
        if ($this->agent->is_browser()) {
            $agent = 'Desktop ' . $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = 'Mobile' . $this->agent->mobile() . '' . $this->agent->version();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $ip = $this->input->ip_address();
        $ip_with_location = $ip . "<br>(" . get_location_from_ip($ip) . ")";

        $this->Hutang_model->insert_log_activity([
            'log_activity_user_id' => $this->session->id_session,
            'log_activity_modul' => $modul,
            'log_activity_document_no' => $dokumen,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform' => $agent,
            'log_activity_ip' => $ip_with_location,
        ]);
    }
}
