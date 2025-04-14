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

    redirect('agenda');
}

public function edit($id_session) {
    $data['agenda'] = $this->Agenda_model->get_agenda_by_id($id_session);
    $this->load->view('agenda/edit', $data);
}

public function update($id_session){

    $data = array(
        'brainstorming' => $this->input->post('brainstorming'),
        'technical_meeting' => $this->input->post('technical_meeting'),
        'final_revision' => $this->input->post('final_revision'),
        'loading_decoration' => $this->input->post('loading_decoration'),
        'wedding_day' => $this->input->post('wedding_day'),
        'honeymoon' => $this->input->post('honeymoon'),
    );

    $this->Agenda_model->update_agenda($id_session, $data);

        redirect('agenda');
    }

    public function delete_permanent($id_session) {

        $this->Agenda_model->delete_permanent($id_session);

        $this->session->set_flashdata('Success', 'Agenda berhasil dihapus');
        redirect('agenda');
    }

}
