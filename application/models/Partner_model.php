<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partner_model extends CI_Model {

    public function get_all_partner() {
        return $this->db->get_where('partner', ['status' => 'created'])->result();
    }

    public function insert($data) {
        $this->db->insert('partner', $data);
    }

    public function get_partner_by_id($id_session) {
        return $this->db->get_where('partner', ['id_session' => $id_session])->row();
    }

    public function update($id_session, $data) {
        $this->db->where('id_session', $id_session);
        $this->db->update('partner', $data);
    }

    public function get_deleted_partner() {
        return $this->db->get_where('partner', ['status' => 'deleted'])->result();
    }

    public function soft_delete($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('partner', ['status' => 'deleted']);
    }

    public function restore($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('partner', ['status' => 'created']);
    }

    public function delete_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        $this->db->delete('partner');
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);
    }

    public function get_logactivity_by_session($id_session) {
        $this->db->order_by('log_activity_id', 'DESC');
        $this->db->limit(5, 0);
        return $this->db->get_where('log_activity', ['log_activity_document_no' => $id_session])->result();
    }

}
