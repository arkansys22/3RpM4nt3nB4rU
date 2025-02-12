<?php
class Projek_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_projek() {
        return $this->db->get_where('projek',['status' => 'create'])->result();
    }

    public function get_deleted_projek() {
        return $this->db->get_where('projek', ['status' => 'delete'])->result();
    }

    public function insert_projek($data) {
        return $this->db->insert('projek', $data);
    }

    public function get_projek_by_session($id_session) {
        return $this->db->get_where('projek', ['id_session' => $id_session])->row();
    }

    public function update_projek($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('projek', $data);
    }

    public function delete_projek($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('projek', $data);
    }

    public function restore_projek($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('projek', ['status' => 'create']);
    }

    public function delete_projek_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('projek');
    }

}
