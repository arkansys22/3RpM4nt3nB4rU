<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CrewRole_model extends CI_Model {
    public function get_all_roles() {
        return $this->db->get('crew_role')->result();
    }

    public function get_role_by_id($id) {
        return $this->db->get_where('crew_role', ['id' => $id])->row();
    }

    public function insert_role($data) {
        $this->db->insert('crew_role', $data);
    }

    public function update_role($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('crew_role', $data);
    }

    public function delete_role($id) {
        $this->db->where('id', $id);
        $this->db->delete('crew_role');
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);
    }

}
