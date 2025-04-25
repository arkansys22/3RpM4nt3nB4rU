<?php
class crews_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_crews() {
        $this->db->select("*, TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS age");
        $this->db->from("crews");
        $this->db->where("status", "active"); // Hanya ambil data crew yang aktif
        return $this->db->get()->result();
    }

    public function get_deleted() {
        $this->db->select("*, TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS age");
        $this->db->from("crews");
        $this->db->where("status", "delete"); // Ambil data yang dihapus
        return $this->db->get()->result();
    }

    public function get_by_id_session($id_session) {
        $this->db->select("*, TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS age");
        $this->db->from("crews");
        $this->db->where("id_session", $id_session);
        return $this->db->get()->row();
    }

    public function insert($data) {
        return $this->db->insert('crews', $data);
    }

    public function update($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('crews', $data);
    }

    public function soft_delete($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('crews', ['status' => 'delete']);
    }

    public function restore($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('crews', ['status' => 'active']);
    }

    public function delete_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('crews');
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);
    }

    public function get_logactivity_by_session($id_session) {
        $this->db->order_by('log_activity_id', 'DESC');
        $this->db->limit(5, 0);
        return $this->db->get_where('log_activity', ['log_activity_document_no' => $id_session])->result();
    }

    public function get_all_roles() {
        $this->db->select('role');
        $this->db->from('crew_role');
        return $this->db->get()->result();
    }
}
?>
