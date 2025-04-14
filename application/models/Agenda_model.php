<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda_model extends CI_Model {

    public function get_agenda_by_session($id_session) {
        return $this->db->get_where('agenda', ['id_session' => $id_session])->row();
    }

    public function insert_agenda($data) {
        return $this->db->insert('agenda', $data);
    }

    public function update_agenda($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('agenda', $data);
    }

    public function get_agenda_by_project()
    {
        $this->db->select('
            project.id_session, 
            project.client_name, 
            agenda.brainstorming, 
            agenda.technical_meeting, 
            agenda.final_revision, 
            agenda.loading_decoration, 
            agenda.wedding_day,
            agenda.honeymoon
        ');
        $this->db->from('project');
        $this->db->join('agenda', 'agenda.id_session = project.id_session', 'left'); // Join tabel agenda
        $this->db->where('project.status', 'create'); // Hanya ambil yang statusnya "create"
        return $this->db->get()->result();
    }
    
    public function get_agenda_by_id($id_session)
{
    $this->db->select('
        agenda.*, 
        project.client_name
    ');
    $this->db->from('agenda');
    $this->db->join('project', 'agenda.id_session = project.id_session', 'left');
    $this->db->where('agenda.id_session', $id_session);
    return $this->db->get()->row(); // Pastikan pakai row(), bukan result()
}

public function delete_permanent($id_session) {
    $this->db->where('id_session', $id_session);
    return $this->db->delete('agenda');
}

public function insert_log_activity($data_log) {
    return $this->db->insert('log_activity', $data_log);

    return $insert;
}

}
