<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_model extends CI_Model {

    public function get_vendor_by_id($id_session) {
        return $this->db->get_where('vendor', ['id_session' => $id_session])->result();
    }

    public function insert_vendor($data) {
        unset($data['vendor_status'], $data['partner_id']);
        return $this->db->insert('vendor', $data);
    }
    
    public function get_vendor_by_id_and_vendor_id($id_session, $vendor_id) {
        return $this->db->get_where('vendor', ['id_session' => $id_session, 'vendor_id' => $vendor_id])->row();
    }

    public function update_vendor($id_session, $vendor_id, $data) {
        $this->db->where('id_session', $id_session);
        $this->db->where('vendor_id', $vendor_id);
        return $this->db->update('vendor', $data);
    }

    public function delete_vendor($id_session, $vendor_id) {
        $this->delete_photos_by_vendor($id_session, $vendor_id); // cascade galeri foto
        $this->db->where('id_session', $id_session);
        $this->db->where('vendor_id', $vendor_id);
        return $this->db->delete('vendor');
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);
    }

    // ================= Galeri foto vendor (jumlah bebas) =================
    // photo1 di tabel `vendor` tetap kolom tersendiri (cover/logo) -- ini
    // cuma buat foto/konsep tambahan, lihat db/vendor_photos.sql.

    public function get_photos($id_session, $vendor_id) {
        return $this->db->where('id_session', $id_session)
            ->where('vendor_id', $vendor_id)
            ->order_by('urutan', 'asc')
            ->get('vendor_photos')->result();
    }

    public function get_photo_by_id($id) {
        return $this->db->get_where('vendor_photos', ['id' => $id])->row();
    }

    public function get_photos_next_urutan($id_session, $vendor_id) {
        $row = $this->db->select_max('urutan')
            ->where('id_session', $id_session)
            ->where('vendor_id', $vendor_id)
            ->get('vendor_photos')->row();
        return ($row && $row->urutan !== null) ? ((int) $row->urutan + 1) : 1;
    }

    public function insert_photo($data) {
        return $this->db->insert('vendor_photos', $data);
    }

    public function delete_photo($id) {
        $this->db->where('id', $id);
        return $this->db->delete('vendor_photos');
    }

    public function delete_photos_by_vendor($id_session, $vendor_id) {
        $this->db->where('id_session', $id_session);
        $this->db->where('vendor_id', $vendor_id);
        return $this->db->delete('vendor_photos');
    }

    // Dipakai waktu satu baris vendor pindah identitas (mis. diganti jadi
    // partner lain sehingga vendor_id-nya berubah) -- galeri foto lama ikut
    // di-rekey, bukan ditinggal jadi yatim di bawah vendor_id yang sudah
    // tidak dipakai lagi.
    public function pindahkan_vendor_id_photos($id_session, $vendor_id_lama, $vendor_id_baru) {
        $this->db->where('id_session', $id_session);
        $this->db->where('vendor_id', $vendor_id_lama);
        return $this->db->update('vendor_photos', ['vendor_id' => $vendor_id_baru]);
    }

}
