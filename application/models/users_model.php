<?php
class users_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_user() {
        return $this->db->get_where('user',['user_stat' => 'Publish'])->result();
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


   
    
    
    
}
