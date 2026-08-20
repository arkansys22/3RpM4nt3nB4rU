<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Susunan acara per klien. Baris-barisnya awalnya di-copy dari `susunan_acara` (data umum,
 * lihat Susunan_acara_model) sesuai kategori acara yang dipilih klien, tapi sejak itu berdiri
 * sendiri -- diedit/dihapus di sini tidak menyentuh data umum sama sekali.
 *
 * Satu klien bisa punya SAMPAI DUA acara (mis. Akad Nikah = Acara 1, Resepsi = Acara 2, sesuai
 * Kategori Acara 1/2 di clients/edit). Kolom `acara_ke` (1 atau 2) memisahkan kegiatan milik
 * masing-masing acara -- reorder/urutan juga di-scope per acara_ke, bukan cuma per klien.
 */
class Client_susunan_acara_model extends CI_Model {

    public function get_by_client($client_id_session, $acara_ke = 1)
    {
        return $this->db->where('client_id_session', $client_id_session)
            ->where('acara_ke', $acara_ke)
            ->order_by('urutan', 'asc')
            ->get('client_susunan_acara')->result();
    }

    public function get_by_session($id_session)
    {
        return $this->db->get_where('client_susunan_acara', ['id_session' => $id_session])->row();
    }

    public function count_by_client($client_id_session, $acara_ke = 1)
    {
        return $this->db->where('client_id_session', $client_id_session)
            ->where('acara_ke', $acara_ke)
            ->count_all_results('client_susunan_acara');
    }

    public function get_next_urutan($client_id_session, $acara_ke = 1)
    {
        $row = $this->db->select_max('urutan')
            ->where('client_id_session', $client_id_session)
            ->where('acara_ke', $acara_ke)
            ->get('client_susunan_acara')->row();
        return ($row && $row->urutan !== null) ? ((int) $row->urutan + 1) : 1;
    }

    public function insert($data)
    {
        return $this->db->insert('client_susunan_acara', $data);
    }

    public function update_by_session($id_session, $data)
    {
        $this->db->where('id_session', $id_session);
        return $this->db->update('client_susunan_acara', $data);
    }

    public function delete_by_session($id_session)
    {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('client_susunan_acara');
    }

    public function delete_by_client($client_id_session, $acara_ke = 1)
    {
        $this->db->where('client_id_session', $client_id_session);
        $this->db->where('acara_ke', $acara_ke);
        return $this->db->delete('client_susunan_acara');
    }

    /**
     * Tukar nilai `urutan` dengan tetangga terdekat MILIK KLIEN & ACARA YANG SAMA
     * (naik = tetangga dengan urutan lebih kecil, turun = lebih besar). Di-scope per
     * acara_ke juga supaya kegiatan Acara 1 tidak ketuker urutan sama kegiatan Acara 2.
     */
    public function swap_urutan($id_session, $direction)
    {
        $current = $this->get_by_session($id_session);
        if (!$current) {
            return false;
        }

        $this->db->where('client_id_session', $current->client_id_session);
        $this->db->where('acara_ke', $current->acara_ke);
        $this->db->where($direction === 'up' ? 'urutan <' : 'urutan >', $current->urutan);
        $this->db->order_by('urutan', $direction === 'up' ? 'desc' : 'asc');
        $neighbor = $this->db->limit(1)->get('client_susunan_acara')->row();

        if (!$neighbor) {
            return false; // sudah paling atas/bawah di acara ini
        }

        $this->db->trans_start();
        $this->db->where('id_session', $current->id_session)->update('client_susunan_acara', ['urutan' => $neighbor->urutan]);
        $this->db->where('id_session', $neighbor->id_session)->update('client_susunan_acara', ['urutan' => $current->urutan]);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}
