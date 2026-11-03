<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Perluasan CI_Session untuk Hak Akses per Level (lihat hooks/Hak_akses_guard.php).
 *
 * Halaman yang diberikan lewat menu Hak Akses (di luar hak dasar level user) tetap melewati pengecekan level di tiap
 * controller yang membaca $this->session->level. Untuk itu, selama SATU request, `level` yang dibaca kode aplikasi bisa
 * diganti sementara dengan "level efektif" milik halaman tersebut. Nilai di $_SESSION tidak diubah, jadi tidak pernah
 * tersimpan ke sesi dan request berikutnya kembali memakai level akun yang sebenarnya.
 */
class MY_Session extends CI_Session {

    protected $level_efektif = null;

    public function set_level_efektif($level)
    {
        $this->level_efektif = ($level === null) ? null : (string) $level;
    }

    // Level akun yang sebenarnya (tanpa penggantian sementara)
    public function level_nyata()
    {
        return isset($_SESSION['level']) ? (string) $_SESSION['level'] : null;
    }

    public function __get($key)
    {
        if ($key === 'level' && $this->level_efektif !== null) {
            return $this->level_efektif;
        }
        return parent::__get($key);
    }

    public function userdata($key = NULL)
    {
        if ($key === 'level' && $this->level_efektif !== null) {
            return $this->level_efektif;
        }
        return parent::userdata($key);
    }
}
