<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Susunan_acara_model extends CI_Model {

    // ================= Kategori =================

    public function get_kategori_all()
    {
        return $this->db->order_by('urutan', 'asc')->get('susunan_acara_kategori')->result();
    }

    public function get_kategori_by_session($id_session)
    {
        return $this->db->get_where('susunan_acara_kategori', ['id_session' => $id_session])->row();
    }

    public function get_kategori_next_urutan()
    {
        $row = $this->db->select_max('urutan')->get('susunan_acara_kategori')->row();
        return ($row && $row->urutan !== null) ? ((int) $row->urutan + 1) : 1;
    }

    public function insert_kategori($data)
    {
        return $this->db->insert('susunan_acara_kategori', $data);
    }

    public function update_kategori_by_session($id_session, $data)
    {
        $this->db->where('id_session', $id_session);
        return $this->db->update('susunan_acara_kategori', $data);
    }

    public function delete_kategori_by_session($id_session)
    {
        // Kegiatan di dalam kategori ini ikut terhapus (foto-fotonya dibersihkan
        // oleh controller sebelum baris ini dipanggil).
        $this->db->where('kategori_id_session', $id_session)->delete('susunan_acara');
        $this->db->where('id_session', $id_session);
        return $this->db->delete('susunan_acara_kategori');
    }

    public function kategori_has_kegiatan($id_session)
    {
        return $this->db->where('kategori_id_session', $id_session)->count_all_results('susunan_acara') > 0;
    }

    public function swap_kategori_urutan($id_session, $direction)
    {
        $current = $this->get_kategori_by_session($id_session);
        if (!$current) {
            return false;
        }

        $this->db->where($direction === 'up' ? 'urutan <' : 'urutan >', $current->urutan);
        $this->db->order_by('urutan', $direction === 'up' ? 'desc' : 'asc');
        $neighbor = $this->db->limit(1)->get('susunan_acara_kategori')->row();

        if (!$neighbor) {
            return false; // sudah paling atas/bawah
        }

        $this->db->trans_start();
        $this->db->where('id_session', $current->id_session)->update('susunan_acara_kategori', ['urutan' => $neighbor->urutan]);
        $this->db->where('id_session', $neighbor->id_session)->update('susunan_acara_kategori', ['urutan' => $current->urutan]);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    // ================= Kegiatan =================

    /**
     * Semua kegiatan, dikelompokkan per kategori (urutan kategori & urutan
     * kegiatan di dalamnya sudah terurut). Dipakai halaman index.
     */
    public function get_all_grouped()
    {
        $kategoriList = $this->get_kategori_all();
        $kegiatanAll = $this->db->order_by('urutan', 'asc')->get('susunan_acara')->result();

        $grouped = [];
        foreach ($kegiatanAll as $k) {
            $grouped[$k->kategori_id_session][] = $k;
        }

        $result = [];
        foreach ($kategoriList as $kat) {
            $result[] = [
                'kategori'  => $kat,
                'kegiatan'  => $grouped[$kat->id_session] ?? [],
            ];
        }

        return $result;
    }

    public function get_by_session($id_session)
    {
        return $this->db->get_where('susunan_acara', ['id_session' => $id_session])->row();
    }

    /**
     * Semua kegiatan milik SATU kategori saja, sudah terurut. Dipakai halaman detail
     * kategori (susunan-acara/lihat/<kategori_id_session>).
     */
    public function get_kegiatan_by_kategori($kategori_id_session)
    {
        return $this->db->where('kategori_id_session', $kategori_id_session)
            ->order_by('urutan', 'asc')
            ->get('susunan_acara')->result();
    }

    public function get_next_urutan($kategori_id_session)
    {
        $row = $this->db->select_max('urutan')
            ->where('kategori_id_session', $kategori_id_session)
            ->get('susunan_acara')->row();
        return ($row && $row->urutan !== null) ? ((int) $row->urutan + 1) : 1;
    }

    public function insert($data)
    {
        return $this->db->insert('susunan_acara', $data);
    }

    public function update_by_session($id_session, $data)
    {
        $this->db->where('id_session', $id_session);
        return $this->db->update('susunan_acara', $data);
    }

    public function delete_by_session($id_session)
    {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('susunan_acara');
    }

    /**
     * Tukar nilai `urutan` dengan tetangga terdekat DI KATEGORI YANG SAMA
     * (naik = tetangga dengan urutan lebih kecil, turun = lebih besar).
     */
    public function swap_urutan($id_session, $direction)
    {
        $current = $this->get_by_session($id_session);
        if (!$current) {
            return false;
        }

        $this->db->where('kategori_id_session', $current->kategori_id_session);
        $this->db->where($direction === 'up' ? 'urutan <' : 'urutan >', $current->urutan);
        $this->db->order_by('urutan', $direction === 'up' ? 'desc' : 'asc');
        $neighbor = $this->db->limit(1)->get('susunan_acara')->row();

        if (!$neighbor) {
            return false; // sudah paling atas/bawah di kategori ini
        }

        $this->db->trans_start();
        $this->db->where('id_session', $current->id_session)->update('susunan_acara', ['urutan' => $neighbor->urutan]);
        $this->db->where('id_session', $neighbor->id_session)->update('susunan_acara', ['urutan' => $current->urutan]);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}
