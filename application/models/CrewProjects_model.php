<?php
class CrewProjects_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_crews() {
        return $this->db->get_where('crews', ['status' => 'active'])->result();
    }    

    public function get_available_crews() {
        $this->db->select('id, crew_name, status');
        $this->db->from('crews');
        $this->db->where('status', 'active'); // 🔥 Hanya ambil crew yang aktif
        $query = $this->db->get();
        return $query->result();
    }

    public function delete_by_project($project_id) {
        $this->db->where('project_id', $project_id);
        $this->db->delete('crew_projects');
    }
    
    public function check_existing($project_id, $crew_id_session, $role) {
        $this->db->where('project_id', $project_id);
        $this->db->where('crew_id', $crew_id_session);
        $this->db->where('role', $role);
        $query = $this->db->get('crew_projects');
        return $query->row(); // Jika ada, berarti sudah tersimpan sebelumnya
    }
    
    public function add_crew_to_project($project_id, $crew_id_session, $role) {
        $data = [
            'project_id' => $project_id,
            'crew_id' => $crew_id_session,
            'role' => $role
        ];
        return $this->db->insert('crew_projects', $data);
    }
    

    // Mengambil daftar crews dalam project tertentu beserta perannya
    public function get_crew_by_project($project_id) {
        return $this->db->get_where('crew_projects', ['project_id' => $project_id])->row();
    } 

    // 🔥 Ambil crew berdasarkan id_session
    public function get_by_session($id_session) {
        return $this->db->get_where('crews', ['id_session' => $id_session])->row();
    }

    public function update_crew_roles($project_id, $data) {
        $exists = $this->db->get_where('crew_projects', ['project_id' => $project_id])->row();
        
        if ($exists) {
            $this->db->where('project_id', $project_id);
            $this->db->update('crew_projects', $data);
        } else {
            $data['project_id'] = $project_id;
            $this->db->insert('crew_projects', $data);
        }
    }
    
    public function get_crews_grouped_by_project($project_id) {
        $this->db->select('crew_projects.role, crews.id as crew_id, crews.crew_name');
        $this->db->from('crew_projects');
        $this->db->join('crews', 'crews.id = crew_projects.crew_id');
        $this->db->where('crew_projects.project_id', $project_id);
        
        $query = $this->db->get();
        $result = $query->result();
    
        $grouped_crews = [];
        foreach ($result as $row) {
            $grouped_crews[$row->role] = [
                'crew_id' => $row->crew_id,
                'crew_name' => $row->crew_name
            ];
        }
    
        return $grouped_crews;
    }

    // Menghapus crews dari project tertentu
    public function remove_crew_from_project($project_id, $crew_session) {
        return $this->db->delete('crew_projects', array(
            'project_id' => $project_id,
            'crew_id' => $crew_session // crew_id = id_session dari crews
        ));
    }
    
    
    public function update_crews_in_project($id_session, $crews_roles) {
        // Hapus data lama berdasarkan project_id (id_session)
        $this->db->where('project_id', $id_session);
        $this->db->delete('crew_projects');
    
        // Simpan ulang data baru
        foreach ($crews_roles as $role => $crew_session_id) {
            if ($crew_session_id != '-') { // Abaikan jika tidak dipilih
                $data = [
                    'project_id' => $id_session,
                    'crew_id'    => $crew_session_id, 
                    'role'       => $role
                ];
                $this->db->insert('crew_projects', $data);
            }
        }
    }    
    
    public function add_or_update_crew($project_id, $crew_id, $role) {
        // Cek apakah crew ini sudah ada di project dengan role yang sama
        $this->db->where('project_id', $project_id);
        $this->db->where('crew_id', $crew_id);
        $this->db->where('role', $role);
        $query = $this->db->get('crew_projects');
    
        if ($query->num_rows() == 0) {
            // 🔥 Jika belum ada, tambahkan data baru
            $data = [
                'project_id' => $project_id,
                'crew_id'    => $crew_id,
                'role'       => $role
            ];
            $this->db->insert('crew_projects', $data);
        }
    }
    
    // 🔥 Hapus crew yang tidak dipilih dalam update
    public function remove_unselected_crews($project_id, $selected_crews) {
        if (!empty($selected_crews)) {
            $crew_session_ids = array_values($selected_crews);
    
            // 🔥 Ambil ID asli dari crew berdasarkan id_session
            $this->db->select('id');
            $this->db->where_in('id_session', $crew_session_ids);
            $crew_data = $this->db->get('crews')->result_array();
    
            if (!empty($crew_data)) {
                $crew_ids = array_column($crew_data, 'id');
    
                // 🔥 Hapus crew yang tidak termasuk dalam daftar update terbaru
                $this->db->where('project_id', $project_id);
                $this->db->where_not_in('crew_id', $crew_ids);
                $this->db->delete('crew_projects');
            }
        }
    }
    
    
}
?>
