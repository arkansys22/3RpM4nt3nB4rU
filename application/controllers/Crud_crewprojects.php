<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_crewprojects extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('CrewProjects_model');
        $this->load->model('project_model');
    }

    public function createlist($project_id) {
        if ($this->session->level == '1') {
            cek_session_akses_developer('crews', $this->session->id_session);
        } else if ($this->session->level == '2') {
            cek_session_akses_administrator('crews', $this->session->id_session);
        } else if ($this->session->level == '3') {
            cek_session_akses_staff_accounting('crews', $this->session->id_session);
            redirect(base_url());
            return;
        } else if ($this->session->level == '4') {
            cek_session_akses_staff_admin('crews', $this->session->id_session);
        } else if ($this->session->level == '5') {
            cek_session_akses_client('crews', $this->session->id_session);
            redirect(base_url());
            return;
        } else {
            redirect(base_url());
            return;
        }

        $data['project_id'] = $project_id;
        $data['existing_crews'] = $this->CrewProjects_model->get_crew_by_project($project_id); // Fetch existing crews
        $data['crews'] = $this->CrewProjects_model->get_all_crews();
        $data['roles'] = $this->CrewProjects_model->get_all_roles(); // Fetch roles from crew_role table
        $data['client_name'] = $this->CrewProjects_model->get_client_name_by_project_id($project_id); // Fetch client name
        $data['project'] = $this->project_model->get_project_by_session($project_id); // Corrected reference

        $this->load->view('crews/createlist', $data);
    }

    public function storelist() {
        $id_session = hash('sha256', bin2hex(random_bytes(16)));
        $project_id = $this->input->post('project_id'); // ID session of the project
        $crew_id = $this->input->post('crew_id');       // ID session of the selected crew
        $role = $this->input->post('role');            // Role from the dropdown

        if ($this->agent->is_browser()) {
            $agent = 'Desktop ' . $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = 'Mobile ' . $this->agent->mobile() . ' ' . $this->agent->version();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $data = [
            'id_session'    => $id_session,
            'project_id'    => $project_id,
            'crew_id'       => $crew_id,
            'role'          => $role, // Save the role directly
            'created_by'    => $this->session->id_session,
        ];

        $this->CrewProjects_model->add_crew_to_project($data); // Store the data in the database

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = [
            'log_activity_user_id' => $this->session->id_session,
            'log_activity_modul'   => 'crewproject/createlist',
            'log_activity_document_no' => $project_id,
            'log_activity_status'  => 'Tambah Crew ke Project',
            'log_activity_waktu'   => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'      => $ip_with_location
        ];

        $this->CrewProjects_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Crew berhasil ditambahkan ke project'); 

        redirect('project/lihat/' . $project_id); // Redirect back to the project view
    }

    public function editlist($id_session, $crew_id) {
        if ($this->session->level == '1') {
            cek_session_akses_developer('crews', $this->session->id_session);
        } else if ($this->session->level == '2') {
            cek_session_akses_administrator('crews', $this->session->id_session);
        } else if ($this->session->level == '3') {
            cek_session_akses_staff_accounting('crews', $this->session->id_session);
            redirect(base_url());
            return;
        } else if ($this->session->level == '4') {
            cek_session_akses_staff_admin('crews', $this->session->id_session);
        } else if ($this->session->level == '5') {
            cek_session_akses_client('crews', $this->session->id_session);
            redirect(base_url());
            return;
        } else {
            redirect(base_url());
            return;
        }

        // Retrieve the project_id using the id_session
        $project_id = $this->CrewProjects_model->get_project_id_by_crew($id_session);
        if (!$project_id) {
            show_404(); // Show 404 if project_id is not found
        }

        $data['project_id'] = $project_id;
        $data['selected_crew'] = $this->CrewProjects_model->get_crew_by_id_and_crew_id($id_session, $crew_id); // Fetch the specific crew details
        if (!$data['selected_crew']) {
            show_404(); // Show 404 if crew not found
        }

        $data['client_name'] = $this->CrewProjects_model->get_client_name_by_project_id($project_id); // Fetch client name
        $data['project'] = $this->project_model->get_project_by_session($project_id); // Corrected reference
        $data['crews'] = $this->CrewProjects_model->get_all_crews(); // Fetch all available crews
        $data['roles'] = $this->CrewProjects_model->get_all_roles(); // Fetch roles from crew_role table

        $this->load->view('crews/editlist', $data);
    }

    public function updatelist() {
        $project_id = $this->input->post('project_id');
        $crew_id = $this->input->post('crew_id');
        $role = $this->input->post('role');
        $id_session = $this->input->post('id_session');

        if ($this->agent->is_browser()) {
            $agent = 'Desktop ' . $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = 'Mobile ' . $this->agent->mobile() . ' ' . $this->agent->version();
        } else {
            $agent = 'Unidentified User Agent';
        }

        if (!$project_id || !$crew_id || !$role || !$id_session) {
            show_error('Invalid input', 400);
        }

        $data = [
            'crew_id' => $crew_id,
            'role'    => $role // Update the role directly
        ];

        $this->CrewProjects_model->update_crew_role($id_session, $data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = [
            'log_activity_user_id' => $this->session->id_session,
            'log_activity_modul'   => 'crewproject/updatelist',
            'log_activity_document_no' => $project_id,
            'log_activity_status'  => 'Update Crew di Project',
            'log_activity_waktu'   => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'      => $ip_with_location
        ];

        $this->CrewProjects_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Crew berhasil diupdate di project'); 

        redirect('project/lihat/' . $project_id);
    }

    public function delete($crew_id) {

        if ($this->agent->is_browser()) // Agent untuk fitur di log activity
        {
            $agent = 'Desktop ' .$this->agent->browser().' '.$this->agent->version();
        }
        elseif ($this->agent->is_robot())
        {
            $agent = $this->agent->robot();
        }
        elseif ($this->agent->is_mobile())
        {
            $agent = 'Mobile' .$this->agent->mobile().''.$this->agent->version();
        }
        else
        {
            $agent = 'Unidentified User Agent';
        }

        $project_id = $this->CrewProjects_model->get_project_id_by_crew($crew_id); // Fetch the associated project ID
        if (!$project_id) {
            show_404(); // Show 404 if project not found
        }

        $this->CrewProjects_model->delete_crew_from_project($crew_id); // Delete the crew

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'crewproject/delete',
            'log_activity_document_no' => $project_id,
            'log_activity_status' => 'Delete Crew dari Project',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
             'log_activity_ip'=> $ip_with_location
            
        );

        $this->CrewProjects_model->insert_log_activity($data_log);

        redirect('project/lihat/' . $project_id); // Redirect back to the project view
    }

}
?>
