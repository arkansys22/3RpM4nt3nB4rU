<?php
class CrewProjects_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_crews() {
        return $this->db->get_where('crews', ['status' => 'active'])->result();
    }    

    public function add_crew_to_project($data) {
        // Insert the project_id, crew_id, and role into the crew_projects table
        $this->db->insert('crew_projects', $data);
    }

    // Mengambil daftar crews dalam project tertentu beserta perannya
    public function get_crew_by_project($project_id) {
        $this->db->select('crew_projects.id_session, crew_projects.project_id, crew_projects.crew_id, crew_projects.role, crews.crew_name');
        $this->db->from('crew_projects');
        $this->db->join('crews', 'crew_projects.crew_id = crews.id_session', 'left');
        $this->db->where('crew_projects.project_id', $project_id);
        $this->db->where('crews.status', 'active'); // Ensure only active crews are fetched
        return $this->db->get()->result();
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

    public function update_crew_role($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('crew_projects', $data);
    }

    public function delete_crew_from_project($crew_id) {
        $this->db->where('id_session', $crew_id);
        $this->db->delete('crew_projects');
    }
    
    public function get_crew_by_id($crew_id) {
        $this->db->select('crew_projects.id_session, crew_projects.role, crews.crew_name');
        $this->db->from('crew_projects');
        $this->db->join('crews', 'crew_projects.crew_id = crews.id_session', 'left');
        $this->db->where('crew_projects.id_session', $crew_id);
        return $this->db->get()->row();
    }

    public function get_project_id_by_crew($id_session) {
        $this->db->select('project_id');
        $this->db->from('crew_projects');
        $this->db->where('id_session', $id_session);
        $result = $this->db->get()->row();
        return $result ? $result->project_id : null;
    }

    public function get_crew_by_id_and_crew_id($id_session, $crew_id) {
        $this->db->select('crew_projects.id_session, crew_projects.crew_id, crew_projects.role, crews.crew_name');
        $this->db->from('crew_projects');
        $this->db->join('crews', 'crew_projects.crew_id = crews.id_session', 'left');
        $this->db->where('crew_projects.id_session', $id_session);
        $this->db->where('crew_projects.crew_id', $crew_id);
        return $this->db->get()->row();
    }

    public function update_crew_role_by_id_and_crew_id($id_session, $crew_id, $data) {
        $this->db->where('id_session', $id_session);
        $this->db->where('crew_id', $crew_id);
        $this->db->update('crew_projects', $data);
    }

    public function get_crew_by_id_and_project($project_id, $crew_id) {
        $this->db->select('crew_projects.id_session, crew_projects.crew_id, crew_projects.role, crews.crew_name');
        $this->db->from('crew_projects');
        $this->db->join('crews', 'crew_projects.crew_id = crews.id_session', 'left');
        $this->db->where('crew_projects.project_id', $project_id);
        $this->db->where('crew_projects.crew_id', $crew_id);
        return $this->db->get()->row();
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);
    }

}
?>
