<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_agenda extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Agenda_model');
        $this->load->model('project_model');
    }

    public function index() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('agenda',$this->session->id_session);
            $data['project'] = $this->project_model->get_project();
            $data['agenda'] = $this->Agenda_model->get_agenda_by_project();
                $this->load->view('agenda/index', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('agenda',$this->session->id_session);
            $data['project'] = $this->project_model->get_project();
            $data['agenda'] = $this->Agenda_model->get_agenda_by_project();
                $this->load->view('agenda/index', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('agenda',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('agenda',$this->session->id_session);
            $data['project'] = $this->project_model->get_project();
            $data['agenda'] = $this->Agenda_model->get_agenda_by_project();
                $this->load->view('agenda/index', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('agenda',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);
            
        }else{
            redirect(base_url());
            }
    }

    public function create($id_session) {
        $data['project'] = $this->project_model->get_project_by_session($id_session);
        $data['agenda'] = $this->Agenda_model->get_agenda_by_session($id_session);
    
        $this->load->view('agenda/create', $data);
    }    

    public function store(){
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
    $id_session = $this->input->post('id_session');
    
    $data = array(
        'id_session' => $id_session,
        'brainstorming' => $this->input->post('brainstorming'),
        'technical_meeting' => $this->input->post('technical_meeting'),
        'final_revision' => $this->input->post('final_revision'),
        'loading_decoration' => $this->input->post('loading_decoration'),
        'wedding_day' => $this->input->post('wedding_day'),
        'honeymoon' => $this->input->post('honeymoon'),
    );

    $this->Agenda_model->insert_agenda($data);
    $status = 'Tambah Agenda' .$this->input->post('status');

    $ip = $this->input->ip_address();
    $location = get_location_from_ip($ip);
    $ip_with_location = $ip . "<br>(" . $location . ")";

    $data_log = array(

        'log_activity_user_id'=>$this->session->id_session,
        'log_activity_modul' => 'agenda/create',
        'log_activity_document_no' => $id_session,
        'log_activity_status' => $status,
        'log_activity_waktu' => date('Y-m-d H:i:s'),
        'log_activity_platform'=> $agent,
         'log_activity_ip'=> $ip_with_location
        
    );
    $this->Agenda_model->insert_log_activity($data_log);
    redirect('agenda');
}

public function edit($id_session) {
    $data['agenda'] = $this->Agenda_model->get_agenda_by_id($id_session);
    $this->load->view('agenda/edit', $data);
}

public function update($id_session){
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
    $data = array(
        'brainstorming' => $this->input->post('brainstorming'),
        'technical_meeting' => $this->input->post('technical_meeting'),
        'final_revision' => $this->input->post('final_revision'),
        'loading_decoration' => $this->input->post('loading_decoration'),
        'wedding_day' => $this->input->post('wedding_day'),
        'honeymoon' => $this->input->post('honeymoon'),
    );

    $this->Agenda_model->update_agenda($id_session, $data);
    $status = 'Update Agenda' .$this->input->post('status');

    $ip = $this->input->ip_address();
    $location = get_location_from_ip($ip);
    $ip_with_location = $ip . "<br>(" . $location . ")";

    $data_log = array(

        'log_activity_user_id'=>$this->session->id_session,
        'log_activity_modul' => 'agenda/edit',
        'log_activity_document_no' => $id_session,
        'log_activity_status' => $status,
        'log_activity_waktu' => date('Y-m-d H:i:s'),
        'log_activity_platform'=> $agent,
         'log_activity_ip'=> $ip_with_location
        
    );

    $this->Agenda_model->insert_log_activity($data_log);
        redirect('agenda');
    }

    public function delete_permanent($id_session) {
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
        $this->Agenda_model->delete_permanent($id_session);
        $status = 'Hapus Agenda' .$this->input->post('status');

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(
    
            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'agenda/delete',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
             'log_activity_ip'=> $ip_with_location
            
        );
    
        $this->Agenda_model->insert_log_activity($data_log);
        $this->session->set_flashdata('Success', 'Agenda berhasil dihapus');
        redirect('agenda');
    }

}
