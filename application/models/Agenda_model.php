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
    
    // Semua milestone agenda (Fitting, Test Food, Final Fitting, Technical
    // Meeting, dst) dari SEMUA project yang jatuh di bulan tertentu --
    // dipakai kalender event di dashboard. wedding_day sengaja tidak
    // disertakan di sini karena tanggalnya sama dengan project.event_date,
    // yang sudah muncul tersendiri sebagai "Hari H" (lihat
    // Aspanel::get_calendar_events()) -- kalau ikut disertakan di sini akan
    // dobel di tanggal yang sama.
    private $kolom_agenda = [
        'brainstorming' => 'Brainstorming',
        'fiting' => 'Fitting',
        'testfood' => 'Test Food',
        'final_fiting' => 'Final Fitting',
        'technical_meeting' => 'Technical Meeting',
        'final_revision' => 'Final Revisi',
        'loading_decoration' => 'Loading Dekorasi',
        'honeymoon' => 'Honeymoon',
    ];

    public function get_agenda_items_by_month($month)
    {
        $items = [];

        foreach ($this->kolom_agenda as $kolom => $label) {
            $this->db->select("agenda.id_session, agenda.$kolom as tanggal, project.client_name, project.project_name");
            $this->db->from('agenda');
            $this->db->join('project', 'project.id_session = agenda.id_session');
            $this->db->where("DATE_FORMAT(agenda.$kolom, '%Y-%m') =", $month);
            $this->db->where('project.status', 'create');
            $rows = $this->db->get()->result();

            foreach ($rows as $row) {
                $items[] = (object) [
                    'tanggal' => $row->tanggal,
                    'tipe' => $label,
                    'id_session' => $row->id_session,
                    'client_name' => $row->client_name,
                    'project_name' => $row->project_name,
                ];
            }
        }

        return $items;
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
