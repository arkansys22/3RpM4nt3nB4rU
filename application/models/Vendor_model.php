<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_model extends CI_Model {

    private $table = 'vendor';

    public function get_vendor_by_id($id_session) {
        return $this->db->get_where('vendor', ['id_session' => $id_session])->row();
    }

    public function insert_vendor($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update_vendor($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

}
