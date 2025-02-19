<?php
class Projects_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_projects() {
        return $this->db->get_where('projects',['status' => 'create'])->result();
    }

    public function get_deleted_projects() {
        return $this->db->get_where('projects', ['status' => 'delete'])->result();
    }

    public function insert_projects($data) {
        return $this->db->insert('projects', $data);

        if ($insert) {

            // Ambil id yang baru disimpan di tabel projects
            $project_id = $this->db->insert_id();  // Mendapatkan id auto-increment dari projects

            // Ambil hari dari create_at (format: Senin, Selasa, dst.)
            $create_day = date('l', strtotime($data['date_create'])); 
    
            // Data untuk tabel clients
            $client_data = [
                'id_session'         => $data['id_session'],
                'client_name'        => $data['projects_name'],  // project_name → client_name
                'wedding_date'       => $data['event_date'],   // event_date → wedding_date
                'created_at'         => $data['create_date'],  // create_date → create_at
                'create_day'         => $create_day,           // Otomatis ambil hari dari create_at
                'location'           => $data['location'],     // Sama dengan projects
                'status'             => 'create'
            ];
            
            // Insert ke tabel clients
            $this->db->insert('clients', $client_data);
        }
    
        return $insert;
    }

    public function get_projects_by_session($id_session) {
        return $this->db->get_where('projects', ['id_session' => $id_session])->row();
    }

    public function update_projects($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('projects', $data);
    }

    public function delete_projects($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('projects', $data);
    }

    public function restore_projects($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('projects', ['status' => 'create']);
    }

    public function delete_projects_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('projects');
    }

}
