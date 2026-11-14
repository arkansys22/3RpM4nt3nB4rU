<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Stock Barang (menu Stock).
|
| Tabel:
|   supplies          : satu baris per barang (nama, kategori_id, satuan, stok_minimum, status 'created'/'deleted')
|   supplies_kategori : daftar kategori barang
|   supplies_stock    : BUKU BESAR pergerakan stok, satu baris per pergerakan
|       goods_in  > 0  = barang masuk,   goods_out < 0 = barang keluar (disimpan negatif, sudah begitu sejak awal)
|       status        = 'Barang Masuk' | 'Barang Keluar' | 'Penyesuaian Masuk' | 'Penyesuaian Keluar' (koreksi/stock opname)
|                       | 'Dibatalkan' (pergerakan dibatalkan; tidak dihitung) | 'created' (baris awal kosong data lama)
|       tanggal       = tanggal kejadian (boleh mundur); created_at = waktu pencatatan
|       amount        = SALDO setelah pergerakan itu (saldo berjalan). Selalu dihitung ulang dari seluruh buku besar
|                       (hitung_ulang()), jadi mengedit/membatalkan pergerakan lama tidak membuat saldo berikutnya salah.
|
| Stok sekarang = jumlah (goods_in + goods_out) seluruh pergerakan yang tidak dibatalkan.
| Total masuk / total keluar dipisah dari penyesuaian (koreksi) supaya laporan "berapa masuk, berapa keluar" jelas.
| Pergerakan diklasifikasi dengan tanda angka (bukan status teks) kecuali Penyesuaian & Dibatalkan.
*/
class Supplies_model extends CI_Model
{
    const BATAL = 'Dibatalkan';
    const MAKS_JUMLAH = 1000000;

    public function __construct()
    {
        parent::__construct();
    }

    // ------------------------------------------------------------------ kategori

    public function kategori_semua()
    {
        return $this->db->query(
            "SELECT k.*, (SELECT COUNT(*) FROM supplies s WHERE s.kategori_id = k.id AND s.status = 'created') AS jumlah_barang
             FROM supplies_kategori k ORDER BY k.nama ASC"
        )->result();
    }

    public function kategori_by_id($id)
    {
        return $this->db->get_where('supplies_kategori', ['id' => (int) $id])->row();
    }

    public function kategori_nama_ada($nama, $kecuali_id = 0)
    {
        $this->db->where('nama', $nama)->where('id <>', (int) $kecuali_id);
        return $this->db->count_all_results('supplies_kategori') > 0;
    }

    public function kategori_tambah($nama, $keterangan)
    {
        return $this->db->insert('supplies_kategori', ['nama' => $nama, 'keterangan' => $keterangan]);
    }

    public function kategori_ubah($id, $nama, $keterangan)
    {
        return $this->db->where('id', (int) $id)->update('supplies_kategori', ['nama' => $nama, 'keterangan' => $keterangan]);
    }

    // Hapus kategori: barang di dalamnya jadi "belum dikategorikan"
    public function kategori_hapus($id)
    {
        $this->db->trans_start();
        $this->db->where('kategori_id', (int) $id)->update('supplies', ['kategori_id' => null]);
        $this->db->where('id', (int) $id)->delete('supplies_kategori');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Ubah kategori banyak barang sekaligus. $kategori_id null = belum dikategorikan
    public function kategorikan($id_sessions, $kategori_id)
    {
        if (empty($id_sessions)) {
            return 0;
        }
        $this->db->where_in('id_session', $id_sessions)->where('status', 'created')
            ->update('supplies', ['kategori_id' => $kategori_id === null ? null : (int) $kategori_id]);
        return $this->db->affected_rows();
    }

    // ------------------------------------------------------------------ barang

    // Semua barang aktif beserta total masuk/keluar/penyesuaian, stok sekarang, dan (opsional) masuk/keluar pada rentang tanggal.
    // $status: 'created' (aktif) atau 'deleted' (recycle bin)
    public function daftar($status = 'created', $dari = null, $sampai = null)
    {
        $bind = [];
        $periode_masuk = '0';
        $periode_keluar = '0';
        if ($dari !== null && $sampai !== null) {
            $periode_masuk = "SUM(CASE WHEN {$this->kelas_masuk('t')} AND t.tanggal BETWEEN ? AND ? THEN t.goods_in ELSE 0 END)";
            $periode_keluar = "-SUM(CASE WHEN {$this->kelas_keluar('t')} AND t.tanggal BETWEEN ? AND ? THEN t.goods_out ELSE 0 END)";
            $bind = [$dari, $sampai, $dari, $sampai];
        }

        $sql = "SELECT s.id, s.id_session, s.product_name, s.satuan, s.stok_minimum, s.kategori_id, s.status, s.created_at AS dibuat,
                       k.nama AS kategori,
                       COALESCE(SUM(CASE WHEN {$this->kelas_masuk('t')} THEN t.goods_in ELSE 0 END), 0) AS total_masuk,
                       COALESCE(-SUM(CASE WHEN {$this->kelas_keluar('t')} THEN t.goods_out ELSE 0 END), 0) AS total_keluar,
                       COALESCE(SUM(CASE WHEN {$this->kelas_penyesuaian('t')} THEN t.goods_in + t.goods_out ELSE 0 END), 0) AS total_penyesuaian,
                       COALESCE(SUM(CASE WHEN t.status <> '" . self::BATAL . "' THEN t.goods_in + t.goods_out ELSE 0 END), 0) AS stok,
                       COALESCE({$periode_masuk}, 0) AS masuk_periode,
                       COALESCE({$periode_keluar}, 0) AS keluar_periode,
                       MAX(CASE WHEN t.status <> '" . self::BATAL . "' AND (t.goods_in <> 0 OR t.goods_out <> 0) THEN t.tanggal END) AS terakhir
                FROM supplies s
                LEFT JOIN supplies_kategori k ON k.id = s.kategori_id
                LEFT JOIN supplies_stock t ON t.id_session = s.id_session
                WHERE s.status = ?
                GROUP BY s.id, s.id_session, s.product_name, s.satuan, s.stok_minimum, s.kategori_id, s.status, s.created_at, k.nama
                ORDER BY s.product_name ASC";
        $bind[] = $status;
        $rows = $this->db->query($sql, $bind)->result();
        foreach ($rows as $r) {
            $r->stok = (int) $r->stok;
            $r->total_masuk = (int) $r->total_masuk;
            $r->total_keluar = (int) $r->total_keluar;
            $r->total_penyesuaian = (int) $r->total_penyesuaian;
            $r->masuk_periode = (int) $r->masuk_periode;
            $r->keluar_periode = (int) $r->keluar_periode;
            $r->status_stok = self::status_stok($r->stok, (int) $r->stok_minimum);
        }
        return $rows;
    }

    // 'habis' (<= 0) | 'menipis' (<= minimum, minimum > 0) | 'aman'
    public static function status_stok($stok, $minimum)
    {
        if ($stok <= 0) {
            return 'habis';
        }
        return ($minimum > 0 && $stok <= $minimum) ? 'menipis' : 'aman';
    }

    private function kelas_masuk($a)
    {
        return "({$a}.goods_in > 0 AND {$a}.status <> '" . self::BATAL . "' AND {$a}.status NOT LIKE 'Penyesuaian%')";
    }

    private function kelas_keluar($a)
    {
        return "({$a}.goods_out < 0 AND {$a}.status <> '" . self::BATAL . "' AND {$a}.status NOT LIKE 'Penyesuaian%')";
    }

    private function kelas_penyesuaian($a)
    {
        return "({$a}.status LIKE 'Penyesuaian%')";
    }

    public function barang_by_id_session($id_session, $status = null)
    {
        $this->db->where('id_session', $id_session);
        if ($status !== null) {
            $this->db->where('status', $status);
        }
        return $this->db->get('supplies')->row();
    }

    // Satu barang lengkap (dengan total & stok) atau null
    public function barang_detail($id_session)
    {
        foreach (array_merge($this->daftar('created'), $this->daftar('deleted')) as $b) {
            if ($b->id_session === $id_session) {
                return $b;
            }
        }
        return null;
    }

    public function nama_barang_ada($nama, $kecuali_id_session = '')
    {
        // query terikat (bound), bukan where(..., false): nama berasal dari input pengguna
        $r = $this->db->query(
            "SELECT COUNT(*) AS n FROM supplies WHERE LOWER(TRIM(product_name)) = ? AND status = 'created' AND id_session <> ?",
            [mb_strtolower(trim($nama), 'UTF-8'), (string) $kecuali_id_session]
        )->row();
        return (int) $r->n > 0;
    }

    // Barang baru (+ stok awal sebagai pergerakan "Barang Masuk" kalau > 0). return id_session | false | 'stok'
    public function barang_tambah($data, $stok_awal, $tanggal, $user)
    {
        $id_session = hash('sha256', bin2hex(random_bytes(16)));
        $this->db->trans_begin();
        $this->db->insert('supplies', [
            'id_session' => $id_session,
            'product_name' => $data['product_name'],
            'type' => 'Persediaan', // kolom lama, tidak dipakai lagi (kategori ada di kategori_id)
            'kategori_id' => $data['kategori_id'],
            'satuan' => $data['satuan'],
            'stok_minimum' => $data['stok_minimum'],
            'created_by' => $user,
            'created_day' => date('l'),
            'status' => 'created',
        ]);
        if ($stok_awal > 0) {
            $this->sisip_gerakan($id_session, 'Barang Masuk', $stok_awal, 0, $tanggal, 'Stok awal', null, $user);
            $h = $this->hitung_ulang($id_session);
            if ($h === false) {
                $this->db->trans_rollback();
                return false;
            }
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $id_session;
    }

    public function barang_ubah($id_session, $data)
    {
        return $this->db->where('id_session', $id_session)->update('supplies', [
            'product_name' => $data['product_name'],
            'kategori_id' => $data['kategori_id'],
            'satuan' => $data['satuan'],
            'stok_minimum' => $data['stok_minimum'],
        ]);
    }

    // Hapus/pulihkan hanya mengubah status barang; buku besar stok TIDAK disentuh (jenis pergerakan tetap utuh)
    public function barang_set_status($id_session, $status)
    {
        return $this->db->where('id_session', $id_session)->update('supplies', ['status' => $status]);
    }

    // Hanya menghapus barang yang MASIH di recycle bin. Hapus baris barang dulu (sekaligus mengunci & memeriksa status), baru buku besarnya,
    // supaya pemulihan yang bersamaan tidak membuat barang aktif kehilangan riwayat. return true | false
    public function barang_hapus_permanen($id_session)
    {
        $this->db->trans_begin();
        $this->db->where('id_session', $id_session)->where('status', 'deleted')->delete('supplies');
        if ($this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->where('id_session', $id_session)->delete('supplies_stock');
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    // ------------------------------------------------------------------ buku besar

    public function riwayat($id_session, $dari = null, $sampai = null)
    {
        $this->db->where('id_session', $id_session);
        if ($dari !== null) {
            $this->db->where('tanggal >=', $dari);
        }
        if ($sampai !== null) {
            $this->db->where('tanggal <=', $sampai);
        }
        $this->db->where('(goods_in <> 0 OR goods_out <> 0)', null, false);
        $this->db->order_by('tanggal', 'DESC')->order_by('id', 'DESC');
        return $this->db->get('supplies_stock')->result();
    }

    // Jumlah masuk/keluar/koreksi pada rentang tanggal (bbulan..) untuk satu barang; null = tanpa batas
    public function total_periode($id_session, $dari, $sampai)
    {
        $sql = "SELECT COALESCE(SUM(CASE WHEN {$this->kelas_masuk('t')} THEN t.goods_in ELSE 0 END), 0) AS masuk,
                       COALESCE(-SUM(CASE WHEN {$this->kelas_keluar('t')} THEN t.goods_out ELSE 0 END), 0) AS keluar,
                       COALESCE(SUM(CASE WHEN {$this->kelas_penyesuaian('t')} THEN t.goods_in + t.goods_out ELSE 0 END), 0) AS koreksi
                FROM supplies_stock t WHERE t.id_session = ? AND (t.goods_in <> 0 OR t.goods_out <> 0)";
        $bind = [$id_session];
        if ($dari !== null) { $sql .= ' AND t.tanggal >= ?'; $bind[] = $dari; }
        if ($sampai !== null) { $sql .= ' AND t.tanggal <= ?'; $bind[] = $sampai; }
        $r = $this->db->query($sql, $bind)->row();
        return ['masuk' => (int) $r->masuk, 'keluar' => (int) $r->keluar, 'koreksi' => (int) $r->koreksi];
    }

    // Semua pergerakan semua barang dalam rentang (untuk ekspor & aktivitas terbaru)
    public function riwayat_semua($dari, $sampai, $limit = null)
    {
        $sql = "SELECT t.*, s.product_name, s.satuan, k.nama AS kategori
                FROM supplies_stock t
                JOIN supplies s ON s.id_session = t.id_session
                LEFT JOIN supplies_kategori k ON k.id = s.kategori_id
                WHERE s.status = 'created' AND (t.goods_in <> 0 OR t.goods_out <> 0) AND t.tanggal BETWEEN ? AND ?
                ORDER BY t.tanggal DESC, t.id DESC" . ($limit ? ' LIMIT ' . (int) $limit : '');
        return $this->db->query($sql, [$dari, $sampai])->result();
    }

    public function gerakan_by_id($id_sessionstock)
    {
        return $this->db->query(
            "SELECT t.*, s.product_name, s.satuan, s.status AS status_barang FROM supplies_stock t
             JOIN supplies s ON s.id_session = t.id_session WHERE t.id_sessionstock = ?",
            [$id_sessionstock]
        )->row();
    }

    // Peta id_session user => nama
    public function nama_user($ids)
    {
        $ids = array_values(array_unique(array_filter($ids)));
        if (!$ids) {
            return [];
        }
        $peta = [];
        foreach ($this->db->select('id_session, nama')->where_in('id_session', $ids)->get('user')->result() as $u) {
            $peta[$u->id_session] = $u->nama;
        }
        return $peta;
    }

    // Peta id_session project => nama project (untuk tujuan barang keluar)
    public function nama_project($ids = null)
    {
        if ($ids !== null) {
            $ids = array_values(array_unique(array_filter($ids)));
            if (!$ids) {
                return []; // keluar SEBELUM menyentuh query builder (status select/where tidak boleh bocor ke query lain)
            }
        }
        $this->db->select('id_session, project_name, event_date');
        if ($ids !== null) {
            $this->db->where_in('id_session', $ids);
        }
        $peta = [];
        foreach ($this->db->get('project')->result() as $p) {
            $peta[$p->id_session] = $p->project_name;
        }
        return $peta;
    }

    // Project untuk dropdown tujuan barang keluar: event 60 hari ke belakang s/d 180 hari ke depan, terbaru dulu
    public function project_pilihan()
    {
        return $this->db->query(
            "SELECT id_session, project_name, event_date FROM project
             WHERE (status = 'create' OR status IS NULL) AND event_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 60 DAY) AND DATE_ADD(CURDATE(), INTERVAL 180 DAY)
             ORDER BY event_date DESC"
        )->result();
    }

    private function sisip_gerakan($id_session, $status, $masuk, $keluar, $tanggal, $detail, $project, $user)
    {
        return $this->db->insert('supplies_stock', [
            'id_session' => $id_session,
            'id_sessionstock' => hash('sha256', bin2hex(random_bytes(16))),
            'created_by' => $user,
            'amount' => 0, // diisi hitung_ulang()
            'goods_in' => (int) $masuk,
            'goods_out' => -abs((int) $keluar),
            'detail' => (string) $detail,
            'tanggal' => $tanggal,
            'project_id_session' => $project,
            'status' => $status,
        ]);
    }

    // Hitung ulang kolom amount (saldo berjalan) seluruh buku besar satu barang, urut tanggal lalu id.
    // return saldo akhir (int) atau FALSE kalau pada titik mana pun saldo menjadi negatif ($this->saldo_negatif_di diisi).
    public $saldo_negatif_di = null;

    public function hitung_ulang($id_session)
    {
        $this->saldo_negatif_di = null;
        $rows = $this->db->query(
            "SELECT id, goods_in, goods_out, status, amount, COALESCE(tanggal, DATE(created_at)) AS tgl
             FROM supplies_stock WHERE id_session = ? ORDER BY COALESCE(tanggal, DATE(created_at)) ASC, id ASC",
            [$id_session]
        )->result();
        $saldo = 0;
        foreach ($rows as $r) {
            if ($r->status !== self::BATAL) {
                $saldo += (int) $r->goods_in + (int) $r->goods_out;
            }
            if ($saldo < 0 && $this->saldo_negatif_di === null) {
                $this->saldo_negatif_di = $r->tgl;
            }
            if ((int) $r->amount !== $saldo) {
                $this->db->where('id', $r->id)->update('supplies_stock', ['amount' => $saldo]);
            }
        }
        return $this->saldo_negatif_di === null ? $saldo : false;
    }

    // Stok sekarang suatu barang (jumlah pergerakan yang tidak dibatalkan)
    public function stok_sekarang($id_session)
    {
        $r = $this->db->query(
            "SELECT COALESCE(SUM(goods_in + goods_out), 0) AS s FROM supplies_stock WHERE id_session = ? AND status <> ?",
            [$id_session, self::BATAL]
        )->row();
        return (int) $r->s;
    }

    // Kunci baris barang supaya dua pencatatan bersamaan pada barang yang sama dieksekusi bergantian
    private function kunci_barang($id_session)
    {
        $q = $this->db->query("SELECT id FROM supplies WHERE id_session = ? AND status = 'created' FOR UPDATE", [$id_session]);
        return $q ? $q->row() : false;
    }

    // Selisih koreksi terakhir (dihitung di dalam transaksi terkunci), untuk pesan di controller
    public $selisih_terakhir = 0;

    // Catat pergerakan baru. $jenis: masuk | keluar | koreksi. Untuk 'koreksi', $jumlah = STOK FISIK hasil hitung; selisihnya terhadap
    // stok sistem dihitung DI DALAM transaksi setelah barang dikunci (bukan di controller), supaya tidak basi kalau ada pencatatan bersamaan.
    // return true | 'tidak_ada' | 'stok' (saldo jadi negatif) | 'sama' (koreksi: stok fisik = stok sistem) | false
    public function catat($id_session, $jenis, $jumlah, $tanggal, $detail, $project, $user)
    {
        $this->db->trans_begin();
        if (!$this->kunci_barang($id_session)) {
            $this->db->trans_rollback();
            return 'tidak_ada';
        }
        if ($jenis === 'masuk') {
            $this->sisip_gerakan($id_session, 'Barang Masuk', $jumlah, 0, $tanggal, $detail, null, $user);
        } elseif ($jenis === 'keluar') {
            $this->sisip_gerakan($id_session, 'Barang Keluar', 0, $jumlah, $tanggal, $detail, $project, $user);
        } else { // koreksi: $jumlah = stok fisik
            $selisih = (int) $jumlah - $this->stok_sekarang($id_session);
            $this->selisih_terakhir = $selisih;
            if ($selisih === 0) {
                $this->db->trans_rollback();
                return 'sama';
            }
            if ($selisih > 0) {
                $this->sisip_gerakan($id_session, 'Penyesuaian Masuk', $selisih, 0, $tanggal, $detail, null, $user);
            } else {
                $this->sisip_gerakan($id_session, 'Penyesuaian Keluar', 0, abs($selisih), $tanggal, $detail, null, $user);
            }
        }
        return $this->selesai_transaksi($id_session);
    }

    // Pergerakan yang boleh diubah: bukan dibatalkan, bukan koreksi (koreksi dibatalkan lalu dibuat ulang), bukan baris kosong data lama
    private function boleh_diubah($g)
    {
        return $g && $g->status !== self::BATAL && $g->status_barang === 'created'
            && strpos((string) $g->status, 'Penyesuaian') !== 0 && ((int) $g->goods_in !== 0 || (int) $g->goods_out !== 0);
    }

    private function boleh_dibatalkan($g)
    {
        return $g && $g->status !== self::BATAL && $g->status_barang === 'created' && ((int) $g->goods_in !== 0 || (int) $g->goods_out !== 0);
    }

    // Ubah jumlah/tanggal/keterangan/tujuan suatu pergerakan (jenisnya tetap). return true | 'tidak_ada' | 'stok' | false
    public function gerakan_ubah($id_sessionstock, $jumlah, $tanggal, $detail, $project)
    {
        $awal = $this->gerakan_by_id($id_sessionstock);
        if (!$awal) {
            return 'tidak_ada';
        }
        $this->db->trans_begin();
        if (!$this->kunci_barang($awal->id_session)) {
            $this->db->trans_rollback();
            return 'tidak_ada';
        }
        $g = $this->gerakan_by_id($id_sessionstock); // baca ulang setelah terkunci
        if (!$this->boleh_diubah($g)) {
            $this->db->trans_rollback();
            return 'tidak_ada';
        }
        $data = ['tanggal' => $tanggal, 'detail' => (string) $detail];
        if ((int) $g->goods_in > 0) {
            $data['goods_in'] = abs((int) $jumlah);
        } else {
            $data['goods_out'] = -abs((int) $jumlah);
            $data['project_id_session'] = $project;
        }
        $this->db->where('id_sessionstock', $id_sessionstock)->update('supplies_stock', $data);
        return $this->selesai_transaksi($g->id_session);
    }

    // Batalkan pergerakan (tidak dihapus dari riwayat, hanya tidak dihitung). return true | 'tidak_ada' | 'stok' | false
    public function gerakan_batal($id_sessionstock)
    {
        $awal = $this->gerakan_by_id($id_sessionstock);
        if (!$awal) {
            return 'tidak_ada';
        }
        $this->db->trans_begin();
        if (!$this->kunci_barang($awal->id_session)) {
            $this->db->trans_rollback();
            return 'tidak_ada';
        }
        $g = $this->gerakan_by_id($id_sessionstock);
        if (!$this->boleh_dibatalkan($g)) {
            $this->db->trans_rollback();
            return 'tidak_ada';
        }
        $this->db->where('id_sessionstock', $id_sessionstock)->update('supplies_stock', ['status' => self::BATAL]);
        return $this->selesai_transaksi($g->id_session);
    }
    // Hitung ulang saldo; kalau ada saldo negatif batalkan seluruh perubahan
    private function selesai_transaksi($id_session)
    {
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        if ($this->hitung_ulang($id_session) === false) {
            $this->db->trans_rollback();
            return 'stok';
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    // ------------------------------------------------------------------ log

    public function insert_log_activity($data_log)
    {
        return $this->db->insert('log_activity', $data_log);
    }
}
