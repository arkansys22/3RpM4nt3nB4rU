<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Modal (Fin & Acc). Akses sama dengan sub menu Fin & Acc lain: Developer, Administrator, Staff Accounting.
// Pencatatan ke COA dijelaskan di Modal_model: 3000 Modal / 3100 Opening Balance Equity / 3200.02 Deviden.
class Crud_modal extends CI_Controller
{
    const NOMINAL_MAKS = 2000000000; // kolom int(200) di MySQL = int 32-bit

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Modal_model');
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
        $this->tampilkan(null);
    }

    public function edit($id_session = null)
    {
        $this->cek_akses();

        $modal = $this->Modal_model->get_by_id_session($id_session);
        if (!$modal) {
            $this->session->set_flashdata('error', 'Data modal tidak ditemukan.');
            redirect(base_url('modal'));
            return;
        }
        $this->tampilkan($modal);
    }

    private function tampilkan($sedang_edit)
    {
        $daftar = $this->Modal_model->get_semua();

        $ringkas = ['setoran' => 0, 'saldo_awal' => 0, 'penarikan' => 0];
        foreach ($daftar as $m) {
            $kunci = $m->jenis === 'Setoran Modal' ? 'setoran' : ($m->jenis === 'Saldo Awal Modal' ? 'saldo_awal' : 'penarikan');
            $ringkas[$kunci] += (int) $m->nominal;
        }
        $ringkas['bersih'] = $ringkas['setoran'] + $ringkas['saldo_awal'] - $ringkas['penarikan'];

        $data['daftar'] = $daftar;
        $data['ringkas'] = $ringkas;
        $data['edit'] = $sedang_edit;
        // isian yang gagal divalidasi (flashdata) menimpa nilai form supaya tidak perlu mengetik ulang
        $old = $this->session->flashdata('old');
        $data['old'] = $old ? true : false;
        $data['nilai'] = $old ? (object) array_merge($sedang_edit ? (array) $sedang_edit : [], $old) : $sedang_edit;
        $data['akun'] = Modal_model::AKUN;
        $this->load->view('backend/v_modal', $data);
    }

    public function store()
    {
        $this->cek_akses();

        list($data, $error) = $this->validasi_post();
        if ($error) {
            $this->session->set_flashdata('error', $error);
            $this->session->set_flashdata('old', $this->isian_form());
            redirect(base_url('modal'));
            return;
        }

        $data['id_session'] = hash('sha256', bin2hex(random_bytes(16)));
        $data['create_by'] = $this->session->id_session;

        if (!$this->Modal_model->tambah($data)) {
            $this->session->set_flashdata('error', 'Modal gagal disimpan. Coba lagi.');
            redirect(base_url('modal'));
            return;
        }

        $this->catat_log('modal/store', $data['id_session'], 'Tambah Modal');
        $this->session->set_flashdata('success', 'Modal berhasil dicatat di akun COA ' . $data['kategori'] . '.');
        redirect(base_url('modal'));
    }

    public function update($id_session = null)
    {
        $this->cek_akses();

        if (!$this->Modal_model->get_by_id_session($id_session)) {
            redirect(base_url('modal'));
            return;
        }

        list($data, $error) = $this->validasi_post();
        if ($error) {
            $this->session->set_flashdata('error', $error);
            $this->session->set_flashdata('old', $this->isian_form());
            redirect(base_url('modal/edit/' . $id_session));
            return;
        }

        if (!$this->Modal_model->ubah($id_session, $data)) {
            $this->session->set_flashdata('error', 'Modal gagal diperbarui. Coba lagi.');
            redirect(base_url('modal/edit/' . $id_session));
            return;
        }

        $this->catat_log('modal/update', $id_session, 'Edit Modal');
        $this->session->set_flashdata('success', 'Modal berhasil diperbarui (akun COA ' . $data['kategori'] . ').');
        redirect(base_url('modal'));
    }

    public function delete($id_session = null)
    {
        $this->cek_akses();

        if ($this->input->method() !== 'post') {
            redirect(base_url('modal'));
            return;
        }

        if (!$this->Modal_model->get_by_id_session($id_session)) {
            redirect(base_url('modal'));
            return;
        }

        if (!$this->Modal_model->hapus($id_session)) {
            $this->session->set_flashdata('error', 'Modal gagal dihapus. Coba lagi.');
            redirect(base_url('modal'));
            return;
        }

        $this->catat_log('modal/delete', $id_session, 'Hapus Modal');
        $this->session->set_flashdata('success', 'Modal berhasil dihapus (termasuk dari akun COA).');
        redirect(base_url('modal'));
    }

    // ---------------- helper ----------------

    // Baca & validasi isian form modal (dipakai tambah dan edit). Return [data, error].
    private function validasi_post()
    {
        $jenis = $this->input->post('jenis');
        $penanam = trim((string) $this->input->post('penanam'));
        $nominal = $this->angka($this->input->post('nominal'));
        $tanggal = $this->tanggal_valid($this->input->post('tanggal'));

        if (!isset(Modal_model::AKUN[$jenis])) {
            return [null, 'Jenis modal tidak valid.'];
        }
        if ($penanam === '') {
            return [null, 'Nama pemilik / investor wajib diisi.'];
        }
        if ($nominal === null || $nominal <= 0) {
            return [null, 'Nominal harus lebih dari 0.'];
        }
        if ($nominal > self::NOMINAL_MAKS) {
            return [null, 'Nominal terlalu besar (maksimal Rp ' . number_format(self::NOMINAL_MAKS, 0, ',', '.') . ').'];
        }
        if ($tanggal === null) {
            return [null, 'Tanggal tidak valid.'];
        }

        return [[
            'jenis' => $jenis,
            'penanam' => mb_substr($penanam, 0, 255, 'UTF-8'),
            'kategori' => Modal_model::AKUN[$jenis],
            'nominal' => $nominal,
            'tanggal' => $tanggal,
            'keterangan' => mb_substr(trim((string) $this->input->post('keterangan')), 0, 2000, 'UTF-8'),
        ], null];
    }

    // Isian form yang dikirim (tanpa token CSRF), disimpan ke flashdata supaya form terisi lagi setelah validasi gagal
    private function isian_form()
    {
        $isian = [];
        foreach (['jenis', 'penanam', 'nominal', 'tanggal', 'keterangan'] as $k) {
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

        $this->Modal_model->insert_log_activity([
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
