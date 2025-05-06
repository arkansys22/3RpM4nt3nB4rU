<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_crewrole extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('CrewRole_model');
    }

    public function index() {
        if ($this->session->level == '1') {
            cek_session_akses_developer('crew-role', $this->session->id_session);
            $data['roles'] = $this->CrewRole_model->get_all_roles();
            $this->load->view('crews/indexrole', $data);

        } else if ($this->session->level == '2') {
            cek_session_akses_administrator('crew-role', $this->session->id_session);
            $data['roles'] = $this->CrewRole_model->get_all_roles();
            $this->load->view('crews/indexrole', $data);

        } else if ($this->session->level == '3') {
            cek_session_akses_staff_accounting('crew-role', $this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        } else if ($this->session->level == '4') {
            cek_session_akses_staff_admin('crew-role', $this->session->id_session);
            $data['roles'] = $this->CrewRole_model->get_all_roles();
            $this->load->view('crews/indexrole', $data);

        } else if ($this->session->level == '5') {
            cek_session_akses_client('crew-role', $this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        } else {
            redirect(base_url());
        }
    }

    public function create() {
        if ($this->session->level == '1') {
            cek_session_akses_developer('crew-role', $this->session->id_session);
            $this->load->view('crews/createrole');

        } else if ($this->session->level == '2') {
            cek_session_akses_administrator('crew-role', $this->session->id_session);
            $this->load->view('crews/createrole');

        } else if ($this->session->level == '3') {
            cek_session_akses_staff_accounting('crew-role', $this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        } else if ($this->session->level == '4') {
            cek_session_akses_staff_admin('crew-role', $this->session->id_session);
            $this->load->view('crews/createrole');

        } else if ($this->session->level == '5') {
            cek_session_akses_client('crew-role', $this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        } else {
            redirect(base_url());
        }
    }

    public function store() {

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

        $data = [
            'role' => $this->input->post('role'),
            'detail' => $this->input->post('detail')
        ];
        $this->CrewRole_model->insert_role($data);

        // Retrieve the last inserted ID
        $id = $this->db->insert_id();
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'crew-role/create',
            'log_activity_document_no' => $id,
            'log_activity_status' => 'Tambah Crew Role',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
             'log_activity_ip'=> $ip_with_location
            
        );

        $this->CrewRole_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Role berhasil dibuat');

        redirect('crew-role');
    }

    public function edit($id) {
        if ($this->session->level == '1') {
            cek_session_akses_developer('crew-role', $this->session->id_session);
            $data['role'] = $this->CrewRole_model->get_role_by_id($id);
            $this->load->view('crews/editrole', $data);

        } else if ($this->session->level == '2') {
            cek_session_akses_administrator('crew-role', $this->session->id_session);
            $data['role'] = $this->CrewRole_model->get_role_by_id($id);
            $this->load->view('crews/editrole', $data);

        } else if ($this->session->level == '3') {
            cek_session_akses_staff_accounting('crew-role', $this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        } else if ($this->session->level == '4') {
            cek_session_akses_staff_admin('crew-role', $this->session->id_session);
            $data['role'] = $this->CrewRole_model->get_role_by_id($id);
            $this->load->view('crews/editrole', $data);

        } else if ($this->session->level == '5') {
            cek_session_akses_client('crew-role', $this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        } else {
            redirect(base_url());
        }
    }

    public function update($id) {

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

        $data = [
            'role' => $this->input->post('role'),
            'detail' => $this->input->post('detail')
        ];
        $this->CrewRole_model->update_role($id, $data);

        $status = 'Update Role' ;
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'crew-role/edit',
            'log_activity_document_no' => $id,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
             'log_activity_ip'=> $ip_with_location
            
        );

        $this->CrewRole_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Role berhasil diupdate');

        redirect('crew-role');
    }

    public function delete($id) {

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

        $this->CrewRole_model->delete_role($id);

        $status = 'Delete Role' ;
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'crew-role/edit',
            'log_activity_document_no' => $id,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
             'log_activity_ip'=> $ip_with_location
            
        );

        $this->CrewRole_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Role berhasil dihapus');

        redirect('crew-role');
    }

    public function view($id) {
        if ($this->session->level == '1') {
            cek_session_akses_developer('crew-role', $this->session->id_session);
            $data['role'] = $this->CrewRole_model->get_role_by_id($id);
            $this->load->view('crews/lihatrole', $data);

        } else if ($this->session->level == '2') {
            cek_session_akses_administrator('crew-role', $this->session->id_session);
            $data['role'] = $this->CrewRole_model->get_role_by_id($id);
            $this->load->view('crews/lihatrole', $data);

        } else if ($this->session->level == '3') {
            cek_session_akses_staff_accounting('crew-role', $this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        } else if ($this->session->level == '4') {
            cek_session_akses_staff_admin('crew-role', $this->session->id_session);
            $data['role'] = $this->CrewRole_model->get_role_by_id($id);
            $this->load->view('crews/lihatrole', $data);

        } else if ($this->session->level == '5') {
            cek_session_akses_client('crew-role', $this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        } else {
            redirect(base_url());
        }
    }
}
