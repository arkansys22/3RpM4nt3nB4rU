<?php
class crews_model extends CI_Model {
    private $table = "crews";

    public function get_all_crews() {
        return $this->db->get_where('crews', ['status' => 'active'])->result();
    }    

    public function get_active_crews() {
        $this->db->where('status', 'active');
        return $this->db->get('crews')->result();
    }


    public function get_deleted() {
        return $this->db->get_where($this->table, ['status' => 'delete'])->result();
    }

    public function get_by_id_session($id_session) {
        return $this->db->get_where($this->table, ['id_session' => $id_session])->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update($this->table, $data);
    }

    public function soft_delete($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update($this->table, ['status' => 'delete']);
    }

    public function restore($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update($this->table, ['status' => 'active']);
    }

    public function delete_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->delete($this->table);
    }

    public function get_crews_by_role($project_id, $role) {
        $this->db->select('crews.id, crews.crew_name');
        $this->db->from('crew_projects');
        $this->db->join('crews', 'crews.id = crew_projects.crew_id');
        $this->db->where('crew_projects.project_id', $project_id);
        $this->db->where('crew_projects.role', $role);
        return $this->db->get()->result();
    }

    public function get_crews_roles_in_project($project_id) {
        $this->db->select('crews.id, crews.crew_name, crew_projects.role');
        $this->db->from('crew_projects');
        $this->db->join('crews', 'crews.id = crew_projects.crew_id');
        $this->db->where('crew_projects.project_id', $project_id);
        return $this->db->get()->result();
    }

    public function get_crews_by_role_in_project($project_id, $role) {
        $this->db->select('crews.id, crews.crew_name');
        $this->db->from('crew_projects');
        $this->db->join('crews', 'crews.id = crew_projects.crew_id');
        $this->db->where('crew_projects.project_id', $project_id);
        $this->db->where('crew_projects.role', $role);
        return $this->db->get()->row(); // Mengambil satu crews yang memiliki peran ini
    }    

}
?>
