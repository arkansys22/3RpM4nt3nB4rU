<?php
class CrewProjects_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_crews() {
        return $this->db->get_where('crews', ['status' => 'active'])->result();
    }    

    // Mengambil daftar crews dalam project tertentu beserta perannya
    public function get_crew_by_project($project_id) {
        return $this->db->get_where('crew_projects', ['project_id' => $project_id])->row();
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
    
}
?>
