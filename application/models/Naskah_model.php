<?php
class Naskah_model extends CI_Model {
    public function get_by_session($id_session) {
        return $this->db->get_where('clients', ['id_session' => $id_session])->row();
    }
}
