<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Hak akses per LEVEL user: tiap level (Administrator, Staff Accounting, Staff Admin, Staff, Staff Sales, dan level baru
// buatan Developer) bisa dipilih halaman/menu mana saja yang ditampilkan dan boleh dibuka. Katalog menu ada di
// config/hak_akses.php.
//
// Tabel hak_akses_level (level, menu_key) dibuat otomatis kalau belum ada (db/hak_akses.sql berisi DDL yang sama):
//   - level TANPA baris di tabel  = tampilan bawaan (semua halaman sesuai hak dasar level itu, seperti sebelumnya)
//   - level DENGAN baris          = hanya menu_key tersebut yang tampil / boleh dibuka -- boleh juga halaman di luar hak
//                                   dasar level; request ke halaman itu memakai "level efektif" (lihat cocok() dan
//                                   libraries/Session/MY_Session.php)
class Hak_akses_model extends CI_Model {

    // Cache per request: [level => array menu_key | null]
    private static $cache_level = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->config('hak_akses', true);
    }

    public function katalog()
    {
        return $this->config->item('hak_akses_menu', 'hak_akses');
    }

    // Level bawaan yang bisa diatur (config) + level baru buatan Developer. Developer tidak pernah dibatasi;
    // Client/Partner/Affiliate punya tampilan sendiri.
    public function level_boleh()
    {
        // array_keys() mengubah kunci numerik menjadi int -> paksa string supaya in_array(..., true) cocok
        return array_merge($this->config->item('hak_akses_level_boleh', 'hak_akses'), array_map('strval', array_keys($this->level_kustom())));
    }

    // Level dasar yang boleh dijadikan induk level baru (hak dasarnya ditiru)
    public function level_dasar_boleh()
    {
        return $this->config->item('hak_akses_level_boleh', 'hak_akses');
    }

    // Level baru buatan Developer: [id_level (string) => level_dasar (string)]. Level baru meniru hak dasar level_dasar
    // (saat login session->level diisi level dasar, session->level_asli diisi id level barunya).
    public function level_kustom()
    {
        static $cache = null;
        if ($cache === null) {
            $cache = [];
            $debug = $this->db->db_debug;
            $this->db->db_debug = false;
            if ($this->db->table_exists('hak_akses_level_kustom')) {
                $q = $this->db->query('SELECT level_id, level_dasar FROM hak_akses_level_kustom');
                if ($q) {
                    foreach ($q->result() as $r) {
                        $cache[(string) $r->level_id] = (string) $r->level_dasar;
                    }
                }
            }
            $this->db->db_debug = $debug;
        }
        return $cache;
    }

    // Apakah sesi dengan level (dasar) & level_asli ini masih sah? level_asli yang berbeda dari level hanya sah kalau itu level
    // baru yang masih ada; sesi yang membawa level baru yang sudah dihapus harus dihentikan (bukan dibiarkan tanpa pembatasan).
    public function sesi_sah($level, $level_asli)
    {
        $level_asli = (string) $level_asli;
        return $level_asli === '' || $level_asli === (string) $level || isset($this->level_kustom()[$level_asli]);
    }

    // Level dasar dari sebuah level (level bawaan = dirinya sendiri)
    public function dasar_level($level)
    {
        $kustom = $this->level_kustom();
        return isset($kustom[(string) $level]) ? $kustom[(string) $level] : (string) $level;
    }

    // Tambahkan ke daftar level semua level baru yang berinduk ke salah satu level di daftar (untuk query "level IN (...)")
    public function perluas_level(array $levels)
    {
        $hasil = array_map('strval', $levels);
        foreach ($this->level_kustom() as $id => $dasar) {
            if (in_array($dasar, $hasil, true)) {
                $hasil[] = (string) $id;
            }
        }
        return $hasil;
    }

    // Buat level baru: baris di user_level (supaya muncul di form user) + induknya. Kembalikan [id|null, pesan_error|null].
    public function tambah_level($nama, $dasar)
    {
        $nama = mb_substr(trim((string) $nama), 0, 100);
        if ($nama === '') {
            return [null, 'Nama level wajib diisi.'];
        }
        if (!in_array((string) $dasar, $this->level_dasar_boleh(), true)) {
            return [null, 'Level dasar tidak valid.'];
        }
        if ($this->db->query('SELECT 1 FROM user_level WHERE UPPER(user_level_nama) = UPPER(?)', [$nama])->num_rows() > 0) {
            return [null, 'Nama level sudah dipakai.'];
        }
        $this->pastikan_tabel();
        $id = max(12, (int) $this->db->query('SELECT MAX(user_level_id) AS m FROM user_level')->row()->m) + 1;
        $this->db->trans_start();
        $this->db->insert('user_level', ['user_level_id' => $id, 'user_level_nama' => strtoupper($nama)]);
        $this->db->insert('hak_akses_level_kustom', ['level_id' => $id, 'level_dasar' => (string) $dasar]);
        $this->db->trans_complete();
        return [$this->db->trans_status() ? (string) $id : null, $this->db->trans_status() ? null : 'Gagal menyimpan level.'];
    }

    // Level tujuan untuk user nonaktif (user_stat bukan Publish, mis. di tempat sampah) yang masih memegang level yang
    // dihapus: Guest, hak paling kecil, supaya user itu tidak mendapat hak lebih besar kalau dipulihkan nanti.
    const LEVEL_PENGGANTI = '6';

    // Hapus level baru (hanya level buatan Developer) kalau TIDAK ADA user aktif (user_stat = Publish) yang memakainya.
    // User nonaktif yang masih memegang level ini dipindah ke LEVEL_PENGGANTI. Kembalikan [pesan_error|null, jumlah_dipindah].
    public function hapus_level($id)
    {
        $id = (string) $id;
        if (!isset($this->level_kustom()[$id])) {
            return ['Hanya level baru buatan Developer yang bisa dihapus.', 0];
        }
        $this->db->trans_begin();
        // Hitung user aktif di dalam transaksi (dikunci) supaya tidak ada user yang dipasang ke level ini saat dihapus
        $aktif = (int) $this->db->query('SELECT COUNT(*) AS c FROM `user` WHERE level = ? AND user_stat = ? FOR UPDATE', [$id, 'Publish'])->row()->c;
        if ($aktif > 0) {
            $this->db->trans_rollback();
            return ['Level masih punya ' . $aktif . ' user aktif. Pindahkan user-nya ke level lain dulu.', 0];
        }
        $dipindah = (int) $this->db->query('SELECT COUNT(*) AS c FROM `user` WHERE level = ?', [$id])->row()->c;
        $this->db->query('UPDATE `user` SET level = ? WHERE level = ?', [self::LEVEL_PENGGANTI, $id]);
        $this->db->delete('hak_akses_level', ['level' => $id]);
        $this->db->delete('hak_akses_level_kustom', ['level_id' => (int) $id]);
        $this->db->delete('user_level', ['user_level_id' => (int) $id]);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return ['Gagal menghapus level.', 0];
        }
        $this->db->trans_commit();
        unset(self::$cache_level[$id]);
        return [null, $dipindah];
    }
    public function tersedia()
    {
        return $this->db->table_exists('hak_akses_level') && $this->db->table_exists('hak_akses_level_kustom');
    }

    // Buat tabel kalau belum ada (butuh hak CREATE). Kembalikan tersedia().
    public function pastikan_tabel()
    {
        if ($this->tersedia()) {
            return true;
        }
        $debug = $this->db->db_debug;
        $this->db->db_debug = false;
        $this->db->query("CREATE TABLE IF NOT EXISTS `hak_akses_level` (
            `level` varchar(5) NOT NULL,
            `menu_key` varchar(60) NOT NULL,
            PRIMARY KEY (`level`,`menu_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $this->db->query("CREATE TABLE IF NOT EXISTS `hak_akses_level_kustom` (
            `level_id` int(11) NOT NULL,
            `level_dasar` varchar(5) NOT NULL,
            PRIMARY KEY (`level_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $this->db->db_debug = $debug;
        $this->db->data_cache = [];
        return $this->tersedia();
    }

    // Daftar level yang bisa diatur + nama dari tabel user_level + jumlah user + jumlah menu yang diatur (null = bawaan)
    public function daftar_level()
    {
        $hasil = [];
        foreach ($this->level_boleh() as $lv) {
            $row = $this->db->query('SELECT user_level_nama FROM user_level WHERE user_level_id = ?', [(int) $lv])->row();
            $jml = (int) $this->db->query('SELECT COUNT(*) AS c FROM `user` WHERE level = ? AND user_stat = ?', [(string) $lv, 'Publish'])->row()->c;
            $kustom = $this->level_kustom();
            $hasil[] = (object) [
                'level' => (string) $lv,
                'nama' => $row ? $row->user_level_nama : 'Level ' . $lv,
                'jumlah_user' => $jml,
                'menu' => $this->menu_level($lv),
                'kustom' => isset($kustom[(string) $lv]),
                'dasar_nama' => isset($kustom[(string) $lv]) ? $this->nama_level($kustom[(string) $lv]) : null,
                'jumlah_semua_user' => (int) $this->db->query('SELECT COUNT(*) AS c FROM `user` WHERE level = ?', [(string) $lv])->row()->c,
            ];
        }
        return $hasil;
    }

    public function nama_level($level)
    {
        $row = $this->db->query('SELECT user_level_nama FROM user_level WHERE user_level_id = ?', [(int) $level])->row();
        return $row ? $row->user_level_nama : 'Level ' . $level;
    }

    // Menu yang diatur untuk level: null = bawaan (tidak dibatasi); array = hanya menu ini.
    public function menu_level($level)
    {
        $level = (string) $level;
        if (!in_array($level, $this->level_boleh(), true)) {
            return null; // Developer/Client/Partner/dll tidak pernah dibatasi
        }
        if (array_key_exists($level, self::$cache_level)) {
            return self::$cache_level[$level];
        }
        $hasil = null;
        if ($this->tersedia()) {
            $rows = $this->db->query('SELECT menu_key FROM hak_akses_level WHERE level = ?', [$level])->result();
            if ($rows) {
                $hasil = array_map(function ($r) { return $r->menu_key; }, $rows);
            }
        }
        return self::$cache_level[$level] = $hasil;
    }

    // Menu bawaan level ini (hak dasar): menu tanpa kunci 'levels' berlaku untuk semua level. Dipakai sebagai centang awal
    // di form dan untuk menandai halaman yang di luar hak dasar.
    public function menu_bawaan($level)
    {
        $dasar = $this->dasar_level($level);
        $hasil = [];
        foreach ($this->katalog() as $key => $def) {
            if (!isset($def['levels']) || in_array($dasar, $def['levels'], true)) {
                $hasil[] = $key;
            }
        }
        return $hasil;
    }

    // Menu yang boleh dicentang untuk level ini: semua menu katalog, kecuali yang 'hanya_bawaan' (mis. Hak Akses) dan
    // bukan hak dasar level ini.
    public function menu_bisa_dipilih($level)
    {
        $bawaan = $this->menu_bawaan($level);
        $hasil = [];
        foreach ($this->katalog() as $key => $def) {
            if (empty($def['hanya_bawaan']) || in_array($key, $bawaan, true)) {
                $hasil[] = $key;
            }
        }
        return $hasil;
    }

    // Simpan menu satu level (selalu sebagai aturan khusus: hanya menu ini yang dimiliki level). Menu di luar daftar
    // yang bisa dipilih diabaikan.
    public function simpan_level($level, array $menu)
    {
        $level = (string) $level;
        $menu = array_values(array_intersect(array_unique($menu), $this->menu_bisa_dipilih($level)));
        $this->db->trans_start();
        $this->db->delete('hak_akses_level', ['level' => $level]);
        foreach ($menu as $m) {
            $this->db->insert('hak_akses_level', ['level' => $level, 'menu_key' => $m]);
        }
        $this->db->trans_complete();
        unset(self::$cache_level[$level]);
    }

    public function reset_level($level)
    {
        $this->db->delete('hak_akses_level', ['level' => (string) $level]);
        unset(self::$cache_level[(string) $level]);
    }

    // Apakah menu ini bisa dibuka oleh level dasar tsb? Lewat hak dasar ('levels') atau lewat level efektif ('grant').
    // Kembalikan [bisa, grant]: grant = null kalau cukup dengan hak dasar, selain itu level efektif yang dipakai.
    private function akses_menu(array $def, $dasar)
    {
        if (!isset($def['levels']) || in_array((string) $dasar, $def['levels'], true)) {
            return [true, null];
        }
        if (!empty($def['grant']) && empty($def['hanya_bawaan'])) {
            return [true, (string) $def['grant']];
        }
        return [false, null];
    }

    // Cari menu (dari daftar yang diizinkan level) yang memuat controller::method.
    // Kembalikan null kalau tidak boleh dibuka; selain itu ['key' => menu|null, 'grant' => level efektif|null].
    public function cocok($class, $method, array $menu_diizinkan, $level_dasar)
    {
        $kunci = strtolower($class) . '::' . strtolower($method);
        foreach ((array) $this->config->item('hak_akses_selalu', 'hak_akses') as $pola) {
            if (fnmatch($pola, $kunci)) {
                return ['key' => null, 'grant' => null];
            }
        }
        $katalog = $this->katalog();
        $kandidat = null;
        foreach ($menu_diizinkan as $key) {
            if (!isset($katalog[$key])) {
                continue;
            }
            $def = $katalog[$key];
            $cocok = false;
            foreach ($def['rules'] as $pola) {
                if (fnmatch($pola, $kunci)) { $cocok = true; break; }
            }
            if ($cocok && !empty($def['exclude'])) {
                foreach ($def['exclude'] as $pola) {
                    if (fnmatch($pola, $kunci)) { $cocok = false; break; }
                }
            }
            if (!$cocok) {
                continue;
            }
            list($bisa, $grant) = $this->akses_menu($def, $level_dasar);
            if (!$bisa) {
                continue;
            }
            if ($grant === null) {
                return ['key' => $key, 'grant' => null]; // hak dasar sudah cukup: tidak perlu level efektif
            }
            if ($kandidat === null) {
                $kandidat = ['key' => $key, 'grant' => $grant];
            }
        }
        return $kandidat;
    }

    // URL tujuan pertama (urutan katalog) dari daftar menu yang diizinkan, untuk mengalihkan user yang membuka halaman
    // yang tidak diizinkan. Menu yang tidak bisa dibuka sama sekali oleh level user dilewati. null kalau tidak ada.
    public function url_awal(array $menu_diizinkan, $level = null)
    {
        $dasar = $level === null ? null : $this->dasar_level($level);
        foreach ($this->katalog() as $key => $def) {
            if (!in_array($key, $menu_diizinkan, true)) {
                continue;
            }
            if ($dasar !== null && !$this->akses_menu($def, $dasar)[0]) {
                continue;
            }
            return $def['url'];
        }
        return null;
    }
}
