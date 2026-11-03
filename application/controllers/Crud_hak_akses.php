<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Hak Akses per level user: admin memilih halaman (menu) mana saja yang ditampilkan dan boleh dibuka untuk tiap
 * level (Administrator, Staff Accounting, Staff Admin, Staff, Staff Sales, level baru). Semua halaman ditampilkan di
 * form dan bisa dicentang untuk level mana pun. Level yang belum diatur memakai tampilan bawaan. Lihat config/hak_akses.php, Hak_akses_model dan hooks/Hak_akses_guard.php.
 */
class Crud_hak_akses extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Hak_akses_model');
    }

    private function cek_akses()
    {
        // level_asli = id level akun sebenarnya; level baru buatan Developer yang berinduk Administrator tidak boleh mengubah
        // pengaturan halaman (termasuk miliknya sendiri), hanya Developer dan Administrator asli.
        $asli = $this->session->userdata('level_asli') ?: $this->session->level;
        if (!in_array($asli, ['1', '2'], true)) {
            redirect(base_url());
            exit;
        }
        if (!$this->Hak_akses_model->pastikan_tabel()) {
            $this->session->set_flashdata('Error', 'Tabel hak akses belum bisa dibuat otomatis. Jalankan db/hak_akses.sql di database.');
        }
    }

    // Level yang valid & boleh diatur, atau redirect balik ke daftar
    private function level_valid($level)
    {
        $level = (string) $level;
        if (!in_array($level, $this->Hak_akses_model->level_boleh(), true) || !$this->Hak_akses_model->tersedia()) {
            $this->session->set_flashdata('Error', 'Level tidak dikenal atau tabel hak akses belum tersedia.');
            redirect('hak-akses');
            exit;
        }
        return $level;
    }

    public function index()
    {
        $this->cek_akses();
        $tersedia = $this->Hak_akses_model->tersedia();
        $data['tersedia'] = $tersedia;
        $data['daftar'] = $tersedia ? $this->Hak_akses_model->daftar_level() : [];
        $data['katalog'] = $this->Hak_akses_model->katalog();
        $this->load->view('backend/v_hak_akses', $data);
    }

    // Form pilih halaman untuk satu level
    public function level($level = 0)
    {
        $this->cek_akses();
        $level = $this->level_valid($level);
        $bawaan = $this->Hak_akses_model->menu_bawaan($level);
        $diatur = $this->Hak_akses_model->menu_level($level);
        $data['level'] = $level;
        $data['nama_level'] = $this->Hak_akses_model->nama_level($level);
        $kustom = $this->Hak_akses_model->level_kustom();
        $data['dasar_nama'] = isset($kustom[$level]) ? $this->Hak_akses_model->nama_level($kustom[$level]) : null;
        $data['menu_semua'] = $this->Hak_akses_model->katalog();
        $data['menu_bawaan'] = $bawaan;
        $data['menu_bisa_dipilih'] = $this->Hak_akses_model->menu_bisa_dipilih($level);
        $data['menu_dipilih'] = $diatur === null ? $bawaan : $diatur; // belum diatur = halaman bawaan level
        $data['sudah_diatur'] = $diatur !== null;
        $data['menu_wajib'] = $this->menu_wajib($level);
        $this->load->view('backend/v_hak_akses_level', $data);
    }

    // Menu yang tidak boleh dimatikan: Administrator tidak boleh mengunci dirinya dari halaman Hak Akses
    private function menu_wajib($level)
    {
        return $this->Hak_akses_model->dasar_level($level) === '2' ? ['hak_akses'] : [];
    }

    public function simpan()
    {
        $this->cek_akses();
        $level = $this->level_valid($this->input->post('level'));
        $menu = array_merge((array) $this->input->post('menu'), $this->menu_wajib($level));
        $menu = array_values(array_intersect(array_unique($menu), $this->Hak_akses_model->menu_bisa_dipilih($level)));
        if (!$menu) {
            $this->session->set_flashdata('Error', 'Pilih minimal satu halaman yang ditampilkan.');
            redirect('hak-akses/level/' . $level);
            return;
        }
        $this->Hak_akses_model->simpan_level($level, $menu);
        $this->session->set_flashdata('Success', 'Halaman untuk level ' . $this->Hak_akses_model->nama_level($level) . ' disimpan.');
        redirect('hak-akses');
    }

    // Level baru: hanya Developer. Meniru hak dasar "level dasar" lalu halamannya diatur di form Atur.
    public function level_baru()
    {
        $this->cek_akses();
        if ($this->session->level !== '1' || $this->input->method() !== 'post' || !$this->Hak_akses_model->tersedia()) {
            redirect('hak-akses');
            return;
        }
        list($id, $galat) = $this->Hak_akses_model->tambah_level($this->input->post('nama'), $this->input->post('dasar'));
        if ($id === null) {
            $this->session->set_flashdata('Error', $galat);
            redirect('hak-akses');
            return;
        }
        $this->session->set_flashdata('Success', 'Level baru dibuat. Atur halaman yang ditampilkan, lalu pilih level ini di form User.');
        redirect('hak-akses/level/' . $id);
    }

    // Hapus level baru (Developer, hanya kalau tidak dipakai user)
    public function level_hapus($level = 0)
    {
        $this->cek_akses();
        if ($this->session->level !== '1' || $this->input->method() !== 'post') {
            redirect('hak-akses');
            return;
        }
        $nama = $this->Hak_akses_model->nama_level($level);
        list($galat, $dipindah) = $this->Hak_akses_model->hapus_level($level);
        if ($galat) {
            $this->session->set_flashdata('Error', $galat);
        } else {
            $this->session->set_flashdata('Success', 'Level ' . $nama . ' dihapus.' . ($dipindah > 0
                ? ' ' . $dipindah . ' user nonaktif/terhapus yang masih memakai level ini dipindah ke level ' . $this->Hak_akses_model->nama_level(Hak_akses_model::LEVEL_PENGGANTI) . '.'
                : ''));
        }
        redirect('hak-akses');
    }
    // Kembalikan level ke tampilan bawaan (hanya lewat POST/CSRF)
    public function reset($level = 0)
    {
        $this->cek_akses();
        if ($this->input->method() !== 'post') {
            redirect('hak-akses');
            return;
        }
        $level = $this->level_valid($level);
        $this->Hak_akses_model->reset_level($level);
        $this->session->set_flashdata('Success', 'Level ' . $this->Hak_akses_model->nama_level($level) . ' dikembalikan ke tampilan bawaan.');
        redirect('hak-akses');
    }
}
