<?php
class potensial_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_potensial_clients() {
        return $this->db->get_where('potensial_clients',['status' => 'Tanya-tanya'])->result();
    }

    public function get_all_potensial_clients_hot() {
        return $this->db->get_where('potensial_clients',['status' => 'Hot'])->result();
    }

    public function get_all_potensial_clients_konsul() {
        return $this->db->get_where('potensial_clients',['status' => 'Konsul'])->result();
    }

    public function get_all_potensial_clients_bayar() {
        return $this->db->get_where('potensial_clients',['status' => 'Deal'])->result();
    }

    public function get_all_potensial_clients_ghosting() {
        return $this->db->get_where('potensial_clients',['status' => 'Ghosting'])->result();
    }

    public function get_all_potensial_clients_batal() {
        return $this->db->get_where('potensial_clients',['status' => 'Batal'])->result();
    }

    public function get_deleted_potensial_clients() {
        return $this->db->get_where('potensial_clients', ['status' => 'Delete'])->result();
    }

    public function insert_potensial_clients($data) {
        return $this->db->insert('potensial_clients', $data);
    
        return $insert;
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);
    
        return $insert;
    }

    public function get_potensial_clients_by_session($id_session) {
        return $this->db->get_where('potensial_clients', ['id_session' => $id_session])->row();
    }

    public function get_logactivity_by_session($id_session) {
        $this->db->order_by('log_activity_id', 'DESC');
        $this->db->limit(5,0);
        return $this->db->get_where('log_activity', ['log_activity_document_no' => $id_session])->result();
    }

    public function update_potensial_clients($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('potensial_clients', $data);
    }

    public function delete_potensial_clients($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('potensial_clients', $data);
    }

    public function restore_potensial_clients($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('potensial_clients', ['status' => 'create']);
    }

    public function delete_potensial_clients_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('potensial_clients');
    }

}
