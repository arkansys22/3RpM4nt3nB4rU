<?php
class project_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_project() {
        return $this->db->get_where('project',['status' => 'create'])->result();
    }

    public function get_deleted_project() {
        return $this->db->get_where('project', ['status' => 'delete'])->result();
    }

    public function insert_project($data) {
        return $this->db->insert('project', $data);

        if ($insert) {

            // Ambil id yang baru disimpan di tabel project
            $project_id = $this->db->insert_id();  // Mendapatkan id auto-increment dari project

            // Ambil hari dari create_at (format: Senin, Selasa, dst.)
            $create_day = date('l', strtotime($data['date_create'])); 
    
            // Data untuk tabel clients
            $client_data = [
                'id_session'         => $data['id_session'],
                'client_name'        => $data['project_name'],  // project_name → client_name
                'wedding_date'       => $data['event_date'],   // event_date → wedding_date
                'created_at'         => $data['create_date'],  // create_date → create_at
                'create_day'         => $create_day,           // Otomatis ambil hari dari create_at
                'location'           => $data['location'],     // Sama dengan project
                'status'             => 'create'
            ];
            
            // Insert ke tabel clients
            $this->db->insert('clients', $client_data);
        }
    
        return $insert;
    }

    public function get_project_by_session($id_session) {
        return $this->db->get_where('project', ['id_session' => $id_session])->row();
    }

    public function update_project($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('project', $data);
    }

    public function delete_project($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('project', $data);
    }

    public function restore_project($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('project', ['status' => 'create']);
    }

    public function delete_project_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('project');
    }

    public function get_crews_by_project_grouped($project_id) {
        $this->db->select('crew_projects.role, crews.id as crew_id, crews.crew_name');
        $this->db->from('crew_projects');
        $this->db->join('crews', 'crews.id = crew_projects.crew_id');
        $this->db->where('crew_projects.project_id', $project_id);
        
        $result = $this->db->get()->result();
    
        // Kelompokkan berdasarkan peran (tanpa array statis, agar fleksibel)
        $grouped = [];
    
        foreach ($result as $row) {
            $grouped[$row->role][] = [
                'crew_id'   => $row->crew_id,
                'crew_name' => $row->crew_name
            ];
        }
    
        return $grouped;
    }    
    
    public function update_crews_in_project($project_id, $crews_roles) {
        // Hapus semua crews lama dalam proyek ini
        $this->db->where('project_id', $project_id);
        $this->db->delete('crew_projects');
    
        // Simpan crews yang baru dipilih
        foreach ($crews_roles as $role => $crew_id) {
            if ($crew_id != "-") { // Jika tidak dipilih, lewati
                $data = [
                    'project_id' => $project_id,
                    'crew_id'    => $crew_id,
                    'role'       => $role
                ];
                $this->db->insert('crew_projects', $data);
            }
        }
    }
    
    
    
}
