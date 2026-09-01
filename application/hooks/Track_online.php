<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Nge-update `user.last_activity` (+ pastikan `user_login_status` = 'online')
// tiap kali user yang sudah login membuka halaman apa pun -- supaya status
// "Online" di halaman daftar user (lihat status_online() di
// application/helpers/customs_helper.php) benar-benar mencerminkan aktivitas
// nyata, bukan cuma flag basi yang di-set sekali waktu login pertama kali
// dan tidak pernah berubah lagi kalau user cuma nutup browser tanpa Logout.
// Lihat db/user_add_last_activity.sql buat konteks lengkapnya.
class Track_online
{
    public function touch_last_activity()
    {
        $CI =& get_instance();

        $id_session = $CI->session->userdata('id_session');
        if (empty($id_session)) {
            return; // Belum login -- tidak ada yang perlu diupdate.
        }

        $CI->db->where('id_session', $id_session);
        $CI->db->update('user', [
            'user_login_status' => 'online',
            'last_activity' => date('Y-m-d H:i:s'),
        ]);
    }
}
