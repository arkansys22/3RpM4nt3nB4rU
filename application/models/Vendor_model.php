<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_model extends CI_Model {

    public function get_vendor_by_session($id_session) {
        $this->db->where('id_session', $id_session);
        $query = $this->db->get('vendor');
        return $query->result();
    }

    public function insert_vendor($data) {
        return $this->db->insert('vendor', $data);
    }

    public function update_vendor($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('vendor', $data);
    }

    public function get_project($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->get('project')->row();
    }

}
