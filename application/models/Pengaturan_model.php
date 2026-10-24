<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Pengaturan umum website -- memakai tabel lama `identitas` (satu baris, id_identitas = 1):
// nama_website, whatsapp, url, logo, favicon, mini_logo (logo sidebar).
// File gambar hasil upload disimpan di assets/uploads/pengaturan/ dan dicatat di kolom sebagai
// "pengaturan/<nama file>" (nilai lama tanpa awalan itu diabaikan, jadi tampilan bawaan tetap dipakai).
class Pengaturan_model extends CI_Model {

    const ID = 1;

    // Kolom `tiktok` ditambahkan otomatis di tabel identitas kalau belum ada (tanpa migrasi manual).
    // Kalau database tidak mengizinkan ALTER, fitur TikTok dinonaktifkan di form (tiktok_tersedia() = false).
    public function tiktok_tersedia() {
        if (!$this->db->field_exists('tiktok', 'identitas')) {
            try {
                $this->load->dbforge();
                $this->dbforge->add_column('identitas', ['tiktok' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true, 'after' => 'instagram']]);
            } catch (Throwable $e) {
                log_message('error', 'Gagal menambah kolom identitas.tiktok: ' . $e->getMessage());
            }
            $this->db->data_cache = []; // field_exists memakai cache daftar kolom
        }
        return $this->db->field_exists('tiktok', 'identitas');
    }

    // Kolom kode awal nomor transaksi (kode_invoice, kode_kwitansi + riwayat kode lama) ditambahkan otomatis
    // di tabel identitas kalau belum ada. Hasil false = database tidak mengizinkan ALTER.
    public function kode_transaksi_tersedia() {
        $kolom = [
            'kode_invoice' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'kode_kwitansi' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'kode_invoice_riwayat' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kode_kwitansi_riwayat' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        ];
        $perlu = [];
        foreach ($kolom as $nama => $def) {
            if (!$this->db->field_exists($nama, 'identitas')) {
                $perlu[$nama] = $def;
            }
        }
        if ($perlu) {
            try {
                $this->load->dbforge();
                $this->dbforge->add_column('identitas', $perlu);
            } catch (Throwable $e) {
                log_message('error', 'Gagal menambah kolom kode transaksi di identitas: ' . $e->getMessage());
            }
            $this->db->data_cache = [];
        }
        foreach (array_keys($kolom) as $nama) {
            if (!$this->db->field_exists($nama, 'identitas')) {
                return false;
            }
        }
        return true;
    }
    public function get() {
        $row = $this->db->get_where('identitas', ['id_identitas' => self::ID])->row();
        if (!$row) {
            // baris belum ada (instalasi baru): buat baris kosong
            $this->db->insert('identitas', ['id_identitas' => self::ID, 'mini_logo' => '', 'analytics' => '', 'pixel' => '', 'foto' => '']);
            $row = $this->db->get_where('identitas', ['id_identitas' => self::ID])->row();
        }
        return $row;
    }

    public function update($data) {
        return $this->db->where('id_identitas', self::ID)->update('identitas', $data);
    }

    // ===== Nomor rekening (tabel rekening_website, db/rekening_website.sql) =====

    public function rekening_tersedia() {
        return $this->db->table_exists('rekening_website');
    }

    public function get_rekening() {
        if (!$this->rekening_tersedia()) {
            return [];
        }
        return $this->db->order_by('urutan', 'asc')->order_by('id', 'asc')->get('rekening_website')->result();
    }

    public function get_rekening_by_id($id) {
        return $this->db->get_where('rekening_website', ['id' => (int) $id])->row();
    }

    public function simpan_rekening($data, $id = 0) {
        if ($id) {
            return $this->db->where('id', (int) $id)->update('rekening_website', $data);
        }
        $data['urutan'] = (int) $this->db->select_max('urutan')->get('rekening_website')->row()->urutan + 1;
        return $this->db->insert('rekening_website', $data);
    }

    public function hapus_rekening($id) {
        return $this->db->delete('rekening_website', ['id' => (int) $id]);
    }
}
