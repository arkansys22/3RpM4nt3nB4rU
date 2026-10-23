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
            agenda.brainstorming, agenda.brainstorming_waktu, agenda.brainstorming_lokasi, agenda.brainstorming_lokasi_url,
            agenda.fiting, agenda.fiting_waktu, agenda.fiting_lokasi, agenda.fiting_lokasi_url,
            agenda.testfood, agenda.testfood_waktu, agenda.testfood_lokasi, agenda.testfood_lokasi_url,
            agenda.final_fiting, agenda.final_fiting_waktu, agenda.final_fiting_lokasi, agenda.final_fiting_lokasi_url,
            agenda.technical_meeting, agenda.technical_meeting_waktu, agenda.technical_meeting_lokasi, agenda.technical_meeting_lokasi_url,
            agenda.final_revision,
            agenda.loading_decoration, agenda.loading_decoration_waktu,
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
    // Agenda yang punya kolom waktu (<kolom>_waktu) di tabel agenda
    // 5 agenda yang punya <kolom>_lokasi & <kolom>_lokasi_url
    private $kolom_dengan_lokasi = ['brainstorming', 'fiting', 'testfood', 'final_fiting', 'technical_meeting'];

    private $kolom_dengan_waktu = ['brainstorming', 'fiting', 'testfood', 'final_fiting', 'technical_meeting', 'loading_decoration'];

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

    // Daftar milestone agenda (kolom => label), dipakai juga oleh To Do List dashboard.
    public function daftar_kolom()
    {
        return $this->kolom_agenda;
    }

    public function get_agenda_items_by_month($month)
    {
        $items = [];

        foreach ($this->kolom_agenda as $kolom => $label) {
            $waktu = in_array($kolom, $this->kolom_dengan_waktu, true) ? "agenda.{$kolom}_waktu" : 'NULL';
            $ada_lokasi = in_array($kolom, $this->kolom_dengan_lokasi, true);
            $lokasi = $ada_lokasi ? "agenda.{$kolom}_lokasi" : 'NULL';
            $lokasi_url = $ada_lokasi ? "agenda.{$kolom}_lokasi_url" : 'NULL';
            $this->db->select("agenda.id_session, agenda.$kolom as tanggal, $waktu as waktu, $lokasi as lokasi, $lokasi_url as lokasi_url, project.client_name, project.project_name", false); // false: jangan di-escape ("NULL as waktu")
            $this->db->from('agenda');
            $this->db->join('project', 'project.id_session = agenda.id_session');
            $this->db->where("DATE_FORMAT(agenda.$kolom, '%Y-%m') =", $month);
            $this->db->where('project.status', 'create');
            $rows = $this->db->get()->result();

            foreach ($rows as $row) {
                $items[] = (object) [
                    'tanggal' => $row->tanggal,
                    'tipe' => $label,
                    'waktu' => $row->waktu,
                    'lokasi' => $row->lokasi,
                    'lokasi_url' => $row->lokasi_url,
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
