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
        'brainstorming_waktu' => $this->waktu_atau_null($this->input->post('brainstorming_waktu')),
        'brainstorming_lokasi' => $this->teks_atau_null($this->input->post('brainstorming_lokasi'), 255),
        'brainstorming_lokasi_url' => $this->url_atau_null($this->input->post('brainstorming_lokasi_url')),
        'fiting' => $this->input->post('fiting'),
        'fiting_waktu' => $this->waktu_atau_null($this->input->post('fiting_waktu')),
        'fiting_lokasi' => $this->teks_atau_null($this->input->post('fiting_lokasi'), 255),
        'fiting_lokasi_url' => $this->url_atau_null($this->input->post('fiting_lokasi_url')),
        'testfood' => $this->input->post('testfood'),
        'testfood_waktu' => $this->waktu_atau_null($this->input->post('testfood_waktu')),
        'testfood_lokasi' => $this->teks_atau_null($this->input->post('testfood_lokasi'), 255),
        'testfood_lokasi_url' => $this->url_atau_null($this->input->post('testfood_lokasi_url')),
        'final_fiting' => $this->input->post('final_fiting'),
        'final_fiting_waktu' => $this->waktu_atau_null($this->input->post('final_fiting_waktu')),
        'final_fiting_lokasi' => $this->teks_atau_null($this->input->post('final_fiting_lokasi'), 255),
        'final_fiting_lokasi_url' => $this->url_atau_null($this->input->post('final_fiting_lokasi_url')),
        'technical_meeting' => $this->input->post('technical_meeting'),
        'technical_meeting_waktu' => $this->waktu_atau_null($this->input->post('technical_meeting_waktu')),
        'technical_meeting_lokasi' => $this->teks_atau_null($this->input->post('technical_meeting_lokasi'), 255),
        'technical_meeting_lokasi_url' => $this->url_atau_null($this->input->post('technical_meeting_lokasi_url')),
        'final_revision' => $this->input->post('final_revision'),
        'loading_decoration' => $this->input->post('loading_decoration'),
        'loading_decoration_waktu' => $this->waktu_atau_null($this->input->post('loading_decoration_waktu')),
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
    $this->session->set_flashdata('Success', 'Agenda berhasil dibuat');

    redirect('project/lihat/'.$id_session);
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
        'brainstorming_waktu' => $this->waktu_atau_null($this->input->post('brainstorming_waktu')),
        'brainstorming_lokasi' => $this->teks_atau_null($this->input->post('brainstorming_lokasi'), 255),
        'brainstorming_lokasi_url' => $this->url_atau_null($this->input->post('brainstorming_lokasi_url')),
        'technical_meeting' => $this->input->post('technical_meeting'),
        'technical_meeting_waktu' => $this->waktu_atau_null($this->input->post('technical_meeting_waktu')),
        'technical_meeting_lokasi' => $this->teks_atau_null($this->input->post('technical_meeting_lokasi'), 255),
        'technical_meeting_lokasi_url' => $this->url_atau_null($this->input->post('technical_meeting_lokasi_url')),
        'final_revision' => $this->input->post('final_revision'),
        'fiting' => $this->input->post('fiting'),
        'fiting_waktu' => $this->waktu_atau_null($this->input->post('fiting_waktu')),
        'fiting_lokasi' => $this->teks_atau_null($this->input->post('fiting_lokasi'), 255),
        'fiting_lokasi_url' => $this->url_atau_null($this->input->post('fiting_lokasi_url')),
        'testfood' => $this->input->post('testfood'),
        'testfood_waktu' => $this->waktu_atau_null($this->input->post('testfood_waktu')),
        'testfood_lokasi' => $this->teks_atau_null($this->input->post('testfood_lokasi'), 255),
        'testfood_lokasi_url' => $this->url_atau_null($this->input->post('testfood_lokasi_url')),
        'final_fiting' => $this->input->post('final_fiting'),
        'final_fiting_waktu' => $this->waktu_atau_null($this->input->post('final_fiting_waktu')),
        'final_fiting_lokasi' => $this->teks_atau_null($this->input->post('final_fiting_lokasi'), 255),
        'final_fiting_lokasi_url' => $this->url_atau_null($this->input->post('final_fiting_lokasi_url')),
        'loading_decoration' => $this->input->post('loading_decoration'),
        'loading_decoration_waktu' => $this->waktu_atau_null($this->input->post('loading_decoration_waktu')),
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
            redirect('project/lihat/'.$id_session);

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


    // Waktu dari <input type="time"> ("HH:MM"); kosong / tidak valid -> NULL.
    private function waktu_atau_null($nilai)
    {
        $nilai = trim((string) $nilai);
        return preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $nilai) ? $nilai : null;
    }

    private function teks_atau_null($nilai, $maks)
    {
        $nilai = mb_substr(trim((string) $nilai), 0, $maks);
        return $nilai === '' ? null : $nilai;
    }

    // URL lokasi: tanpa skema ditambah https://; hanya http/https yang diterima.
    private function url_atau_null($nilai)
    {
        $nilai = trim((string) $nilai);
        if ($nilai === '') {
            return null;
        }
        if (preg_match('#^(javascript|data|vbscript|file):#i', $nilai)) {
            return null;
        }
        if (!preg_match('#^[a-z][a-z0-9+.-]*://#i', $nilai)) {
            $nilai = 'https://' . $nilai;
        }
        return preg_match('#^https?://#i', $nilai) ? mb_substr($nilai, 0, 500) : null;
    }
}
