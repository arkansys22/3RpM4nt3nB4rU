<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda_model extends CI_Model {

    public function get_agenda_by_session($id_session) {
        return $this->db->get_where('agenda', ['id_session' => $id_session])->row();
    }

    public function insert_agenda($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update_agenda($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('agenda', $data);
    }

}
