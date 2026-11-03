<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Penjaga Hak Akses per Level: kalau level user punya pengaturan halaman (menu Hak Akses), user hanya boleh membuka halaman
// milik menu yang dicentang untuk levelnya (lihat config/hak_akses.php & Hak_akses_model). Kalau halaman itu di luar hak
// dasar levelnya, request dijalankan dengan level efektif halaman tsb (MY_Session) supaya pengecekan level di controller
// lolos. Dipanggil di post_controller_constructor, jadi berlaku untuk SEMUA controller tanpa mengubah satu per satu.
// Level tanpa pengaturan & Developer: tidak ada efek.
class Hak_akses_guard
{
    public function periksa()
    {
        $CI =& get_instance();
        $id_session = $CI->session->userdata('id_session');
        if (empty($id_session)) {
            return; // belum login (halaman publik)
        }

        $debug = $CI->db->db_debug;
        $CI->db->db_debug = false; // jangan sampai tabel belum ada membuat halaman error
        $CI->load->model('Hak_akses_model');
        // Sesi dari level baru yang sudah dihapus: hentikan sesi (fail-closed), jangan dibiarkan tanpa pembatasan halaman
        if (!$CI->Hak_akses_model->sesi_sah($CI->session->userdata('level'), $CI->session->userdata('level_asli'))) {
            $CI->db->db_debug = $debug;
            $CI->session->sess_destroy();
            if ($CI->input->is_ajax_request()) {
                $CI->output->set_status_header(401)->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Sesi berakhir. Silakan login ulang.']))->_display();
                exit;
            }
            redirect(base_url('login'));
            exit;
        }
        $menu = $CI->Hak_akses_model->menu_level($CI->session->userdata('level_asli') ?: $CI->session->userdata('level'));
        $CI->db->db_debug = $debug;
        if ($menu === null) {
            return; // tidak dibatasi
        }

        $dasar = method_exists($CI->session, 'level_nyata') ? $CI->session->level_nyata() : $CI->session->userdata('level');
        $cocok = $CI->Hak_akses_model->cocok($CI->router->class, $CI->router->method, $menu, $dasar);
        if ($cocok !== null) {
            // Halaman di luar hak dasar level: selama request ini controller membaca level efektif halaman itu
            if ($cocok['grant'] !== null && method_exists($CI->session, 'set_level_efektif')) {
                $CI->session->set_level_efektif($cocok['grant']);
            }
            return;
        }

        if ($CI->input->is_ajax_request()) {
            $CI->output->set_status_header(403)->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Tidak punya akses ke halaman ini.']))->_display();
            exit;
        }

        $tujuan = $CI->Hak_akses_model->url_awal($menu, $CI->session->userdata('level'));
        $sekarang = trim($CI->uri->uri_string(), '/');

        // Pemutus redirect berputar (halaman tujuan sendiri mengalihkan balik): lebih dari 3 pengalihan dalam 10 detik -> berhenti
        $jejak = $CI->session->userdata('ha_jejak');
        $n = (is_array($jejak) && time() - (int) $jejak['t'] < 10) ? (int) $jejak['n'] + 1 : 1;
        $CI->session->set_userdata('ha_jejak', ['n' => $n, 't' => time()]);

        if ($n <= 3 && $tujuan !== null && trim($tujuan, '/') !== $sekarang) {
            $CI->session->set_flashdata('Error', 'Anda tidak memiliki akses ke halaman tersebut.');
            redirect($tujuan);
            exit;
        }
        // Tidak ada halaman lain yang bisa dituju (hindari loop redirect)
        show_error('Akun Anda belum diberi akses ke halaman ini. Hubungi administrator. <a href="' . site_url('logout') . '">Keluar</a>', 403, 'Akses ditolak');
    }
}
