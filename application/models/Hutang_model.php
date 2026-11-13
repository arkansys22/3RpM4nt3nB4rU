<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Hutang (Fin & Acc): pinjaman/utang umum perusahaan yang BUKAN utang ke vendor per project (itu ada di Finance Project,
| tabel project_acc_utang, akun 210102.xx).
|
| Pencatatan ke COA (tabel accounting, single-entry seperti fitur lain) -- saldo akun = sisa hutang:
|   - Hutang baru  -> baris accounting (+pokok)   di akun 2000 (jangka pendek) atau 2300 (jangka panjang)
|   - Pembayaran   -> baris accounting (-nominal) di akun yang sama
| accounting_id_session = id_session hutang / id_session pembayaran, jadi edit & hapus bisa menyinkronkan barisnya.
*/
class Hutang_model extends CI_Model
{
    // jangka => nomor akun COA
    const AKUN = ['Pendek' => '2000', 'Panjang' => '2300'];
    const JENIS = ['Pinjaman', 'Paylater', 'Kartu Kredit', 'Utang Usaha', 'Lainnya'];

    public function akun_untuk_jangka($jangka)
    {
        return self::AKUN[$jangka] ?? null;
    }

    // Semua hutang + total dibayar + sisa (sisa tidak pernah negatif di tampilan; pembayaran berlebih ditolak di controller)
    public function get_semua()
    {
        return $this->db->query(
            "SELECT h.*,
                    COALESCE((SELECT SUM(p.nominal_bayar) FROM hutang_pembayaran p WHERE p.hutang_id_session = h.id_session), 0) AS total_dibayar
             FROM hutang h
             ORDER BY h.tanggal_hutang DESC, h.id DESC"
        )->result();
    }

    public function get_by_id_session($id_session)
    {
        return $this->db->query(
            "SELECT h.*,
                    COALESCE((SELECT SUM(p.nominal_bayar) FROM hutang_pembayaran p WHERE p.hutang_id_session = h.id_session), 0) AS total_dibayar
             FROM hutang h
             WHERE h.id_session = ?",
            [$id_session]
        )->row();
    }

    public function get_pembayaran($hutang_id_session)
    {
        return $this->db->order_by('tanggal_bayar', 'DESC')->order_by('id', 'DESC')
            ->get_where('hutang_pembayaran', ['hutang_id_session' => $hutang_id_session])->result();
    }

    public function get_pembayaran_by_id_session($id_session)
    {
        return $this->db->get_where('hutang_pembayaran', ['id_session' => $id_session])->row();
    }

    // ---- tulis (hutang + accounting selalu satu transaksi DB) ----

    public function tambah($data)
    {
        $this->db->trans_start();
        $this->db->insert('hutang', $data);
        $this->simpan_accounting(
            $data['id_session'], $data['kategori'], (int) $data['nominal_pokok'],
            $data['tanggal_hutang'], 'Hutang - ' . $data['nama_hutang'] . ' (' . $data['kreditur'] . ')'
        );
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // return true | 'kurang' (pokok baru < total dibayar) | false (gagal DB)
    public function ubah($id_session, $data)
    {
        $this->db->trans_begin();

        // Kunci baris hutang supaya tidak bentrok dengan pembayaran yang masuk bersamaan, lalu cek ulang total dibayar
        $baris = $this->query_kunci(
            "SELECT h.id_session,
                    COALESCE((SELECT SUM(p.nominal_bayar) FROM hutang_pembayaran p WHERE p.hutang_id_session = h.id_session), 0) AS total_dibayar
             FROM hutang h WHERE h.id_session = ? FOR UPDATE",
            $id_session
        );
        if (!$baris) {
            $this->db->trans_rollback();
            return false;
        }
        if ((int) $data['nominal_pokok'] < (int) $baris->total_dibayar) {
            $this->db->trans_rollback();
            return 'kurang';
        }

        $this->db->where('id_session', $id_session)->update('hutang', $data);
        $this->simpan_accounting(
            $id_session, $data['kategori'], (int) $data['nominal_pokok'],
            $data['tanggal_hutang'], 'Hutang - ' . $data['nama_hutang'] . ' (' . $data['kreditur'] . ')'
        );
        // Pembayaran ikut pindah akun kalau jangka (akun COA) hutangnya diubah, supaya saldo kedua akun tetap benar
        foreach ($this->get_pembayaran($id_session) as $p) {
            $this->db->where('accounting_id_session', $p->id_session)
                ->update('accounting', [
                    'accounting_nomer_kategori' => $data['kategori'],
                    'accounting_nama_transaksi' => 'Bayar hutang - ' . $data['nama_hutang'] . ' (' . $data['kreditur'] . ')',
                ]);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    // Hapus hutang beserta seluruh pembayarannya dan baris accounting-nya
    public function hapus($id_session)
    {
        $this->db->trans_begin();

        // Kunci baris hutang DULU (sebelum membaca daftar pembayaran) supaya pembayaran yang sedang masuk selesai lebih
        // dulu dan ikut terhapus; kalau tidak, baris accounting-nya bisa tertinggal yatim di COA.
        if (!$this->query_kunci('SELECT h.id_session FROM hutang h WHERE h.id_session = ? FOR UPDATE', $id_session)) {
            $this->db->trans_rollback();
            return false;
        }

        foreach ($this->get_pembayaran($id_session) as $p) {
            $this->db->where('accounting_id_session', $p->id_session)->delete('accounting');
        }
        $this->db->where('hutang_id_session', $id_session)->delete('hutang_pembayaran');
        $this->db->where('accounting_id_session', $id_session)->delete('accounting');
        $this->db->where('id_session', $id_session)->delete('hutang');

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    // SELECT ... FOR UPDATE yang aman: kalau query gagal (deadlock/lock timeout) CI mengembalikan FALSE saat db_debug mati
    private function query_kunci($sql, $id_session)
    {
        $q = $this->db->query($sql, [$id_session]);
        return $q ? $q->row() : false;
    }

    // return true | 'sisa' (nominal melebihi sisa saat dicek ulang di bawah row lock) | false (gagal DB)
    public function bayar($hutang, $data)
    {
        $this->db->trans_begin();

        $baris = $this->query_kunci(
            "SELECT h.kategori, h.nominal_pokok,
                    COALESCE((SELECT SUM(p.nominal_bayar) FROM hutang_pembayaran p WHERE p.hutang_id_session = h.id_session), 0) AS total_dibayar
             FROM hutang h WHERE h.id_session = ? FOR UPDATE",
            $hutang->id_session
        );
        if (!$baris) {
            $this->db->trans_rollback();
            return false;
        }
        if ((int) $data['nominal_bayar'] > (int) $baris->nominal_pokok - (int) $baris->total_dibayar) {
            $this->db->trans_rollback();
            return 'sisa';
        }

        $this->db->insert('hutang_pembayaran', $data);
        // pakai akun terbaru dari baris yang terkunci (jangka bisa saja baru diubah)
        $this->simpan_accounting(
            $data['id_session'], $baris->kategori, -((int) $data['nominal_bayar']),
            $data['tanggal_bayar'], 'Bayar hutang - ' . $hutang->nama_hutang . ' (' . $hutang->kreditur . ')'
        );

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function hapus_pembayaran($id_session)
    {
        $this->db->trans_start();
        $this->db->where('accounting_id_session', $id_session)->delete('accounting');
        $this->db->where('id_session', $id_session)->delete('hutang_pembayaran');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Satu baris accounting per id_session: insert kalau belum ada, update kalau sudah
    private function simpan_accounting($id_session, $kategori, $nominal, $tanggal, $nama)
    {
        $baris = [
            'accounting_nomer_kategori' => $kategori,
            'accounting_nominal' => $nominal,
            'accounting_tanggal' => $tanggal,
            'accounting_nama_transaksi' => $nama,
        ];
        $ada = $this->db->get_where('accounting', ['accounting_id_session' => $id_session])->row();
        if ($ada) {
            $this->db->where('accounting_id_session', $id_session)->update('accounting', $baris);
        } else {
            $baris['accounting_id_session'] = $id_session;
            $baris['accounting_metode_transaksi'] = '';
            $this->db->insert('accounting', $baris);
        }
    }

    public function insert_log_activity($data_log)
    {
        return $this->db->insert('log_activity', $data_log);
    }
}
