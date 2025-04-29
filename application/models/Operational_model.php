<?php
class Operational_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('operational_acc')->result();
    }

    
    public function insert($data) {
        return $this->db->insert('operational_acc', $data);        
    }

    public function get_operational_by_session($id_session) {
        return $this->db->get_where('operational_acc', ['id_session' => $id_session])->row();
    }

    public function update_operational($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('operational_acc', $data);
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

    public function delete_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('operational_acc');
    }


    public function view_ordering($table,$order,$ordering)
    {
          $this->db->select('*');
          $this->db->from($table);
          $this->db->order_by($order,$ordering);
          return $this->db->get()->result_array();
    }

   
    
    
    
}
