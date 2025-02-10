<?php
class Projek_model extends CI_Model {

    public function get_all_projek() {
        return $this->db->get('projek')->result();
    }

    public function insert_projek($data) {
        return $this->db->insert('projek', $data);
    }

    public function get_projek_by_id($id) {
        return $this->db->get_where('projek', array('id' => $id))->row();
    }

    public function update_projek($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('projek', $data);
    }

    public function delete_projek($id) {
        return $this->db->delete('projek', array('id' => $id));
    }
}
