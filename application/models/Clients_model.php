<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_clients() {     
        return $this->db->get_where('clients',['status' => 'create'])->result();
    }

    public function insert_client($data) {
        return $this->db->insert('clients', $data);
    }

    public function get_client_by_session($id_session) {
        return $this->db->get_where('clients', ['id_session' => $id_session])->row();
    }

    public function update_client($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('clients', $data);
    }

    public function delete_client($id_session, $data) {
      
        $this->db->where('id_session', $id_session);        
        return $this->db->update('clients', $data);
    }


    public function delete_client_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('clients');
    }
}
