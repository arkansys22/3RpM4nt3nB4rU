<?php
class Naskah_model extends CI_Model {
    public function get_by_session($id_session) {
        return $this->db->get_where('clients', ['id_session' => $id_session])->row();
    }

    public function get_vendors_by_session($id_session) {
        return $this->db->get_where('vendor', ['id_session' => $id_session])->row();
    }
    
}
