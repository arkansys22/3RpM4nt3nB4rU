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
        $this->db->where('id_session', $id_session);
        $this->db->where('vendor_id', $vendor_id);
        return $this->db->delete('vendor');
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);
    }

}
