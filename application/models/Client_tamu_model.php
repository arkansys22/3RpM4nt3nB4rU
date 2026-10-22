<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// List tamu undangan per klien (tabel client_tamu, lihat db/client_tamu.sql).
class Client_tamu_model extends CI_Model {

    public function tabel_tersedia() {
        return $this->db->table_exists('client_tamu');
    }

    public function get_by_client($client_id_session) {
        if (!$this->tabel_tersedia()) {
            return [];
        }
        return $this->db->order_by('id', 'asc')->get_where('client_tamu', ['client_id_session' => $client_id_session])->result();
    }

    public function get($id) {
        return $this->db->get_where('client_tamu', ['id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert('client_tamu', $data);
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('client_tamu', $data);
    }

    public function delete($id) {
        return $this->db->delete('client_tamu', ['id' => $id]);
    }
}
