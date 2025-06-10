<?php
class Users2_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_user() {
        return $this->db->get_where('user',['user_stat' => 'Publish'])->result();
    }


    public function get_all_user_admin() {
        $this->db->where_in('level', [3,4,5,6,7,8]);
        $this->db->order_by('level', 'ASC');
        $this->db->where('user_stat', 'Publish');
        return $this->db->get('user')->result();
    }

    public function get_deleted_user() {
        return $this->db->get_where('user', ['user_stat' => 'Delete'])->result();
    }

    public function insert_users($data) {
        return $this->db->insert('user', $data);        
    }

    public function get_users_by_session($id_session) {
        return $this->db->get_where('user', ['id_session' => $id_session])->row();
    }

    public function update_users($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('user', $data);
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);

    }

    public function get_logactivity_by_session($id_session) {
        $this->db->order_by('log_activity_id', 'DESC');
        $this->db->limit(5,0);
        return $this->db->get_where('log_activity', ['log_activity_document_no' => $id_session])->result();
    }

    public function delete_users($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('user', $data);
    }

    public function restore_users($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('user', ['status' => 'create']);
    }

    public function delete_users_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('user');
    }

    public function get_crew_by_id_session($crews_idsession) {
        $this->db->select("*, TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS age");
        $this->db->from("crews");
        $this->db->where("id_session", $crews_idsession); // Match crews_idsession with id_session in crews table
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null; // Return null if no data found
    }

    public function update_crews($crews_idsession, $data) {
        $this->db->where('id_session', $crews_idsession);
        return $this->db->update('crews', $data);
    }

}
