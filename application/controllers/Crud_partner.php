<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_partner extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Partner_model');
    }

    public function index() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('partner',$this->session->id_session);
            $data['partner'] = $this->Partner_model->get_all_partner();
            $this->load->view('partner/index', $data);
    
        }else if($this->session->level=='2'){
            cek_session_akses_administrator('partner',$this->session->id_session);
            $data['partner'] = $this->Partner_model->get_all_partner();
            $this->load->view('partner/index', $data);
    
        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('partner',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('partner',$this->session->id_session);
            $data['partner'] = $this->Partner_model->get_all_partner();
            $this->load->view('partner/index', $data);
    
        }else if($this->session->level=='5'){
            cek_session_akses_client('partner',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);
            
        }else{
            redirect(base_url());
            }
    }

    public function create() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('partner',$this->session->id_session);
            $this->load->view('partner/create');

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('partner',$this->session->id_session);
            $this->load->view('partner/create');

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('partner',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('partner',$this->session->id_session);
            $this->load->view('partner/create');

        }else if($this->session->level=='5'){
            cek_session_akses_client('partner',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }       
    }

    public function store() {

        $id_session = hash('sha256', bin2hex(random_bytes(16)));

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
            'id_session'     => $id_session,
            'partner_name'   => $this->input->post('partner_name'),
            'type'           => $this->input->post('type'),
            'social_media'   => $this->input->post('social_media'),
            'contact_name'   => $this->input->post('contact_name'),
            'phone'          => $this->input->post('phone'),
            'status'         => 'created'
        ];
        $this->Partner_model->insert($data);

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'partner/create',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Tambah Partner',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->Partner_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Partner berhasil dibuat');

        redirect('partner');
    }

    public function lihat($id_session) {

        if ($this->session->level=='1'){
            cek_session_akses_developer('partner',$this->session->id_session);
            $data['partner'] = $this->Partner_model->get_partner_by_id($id_session);
            $data['logactivity'] = $this->Partner_model->get_logactivity_by_session($id_session);
            $this->load->view('partner/lihat', $data);
    
        }else if($this->session->level=='2'){
            cek_session_akses_administrator('partner',$this->session->id_session);
            $data['partner'] = $this->Partner_model->get_partner_by_id($id_session);
            $data['logactivity'] = $this->Partner_model->get_logactivity_by_session($id_session);
            $this->load->view('partner/lihat', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('partner',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('partner',$this->session->id_session);
            $data['partner'] = $this->Partner_model->get_partner_by_id($id_session);
            $data['logactivity'] = $this->Partner_model->get_logactivity_by_session($id_session);
            $this->load->view('partner/lihat', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('partner',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
    }

    public function edit($id_session) {

        if ($this->session->level=='1'){
            cek_session_akses_developer('partner',$this->session->id_session);
            $data['partner'] = $this->Partner_model->get_partner_by_id($id_session);
            $this->load->view('partner/edit', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('partner',$this->session->id_session);
            $data['partner'] = $this->Partner_model->get_partner_by_id($id_session);
            $this->load->view('partner/edit', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('partner',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('partner',$this->session->id_session);
            $data['partner'] = $this->Partner_model->get_partner_by_id($id_session);
            $this->load->view('partner/edit', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('partner',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
    }

    public function update($id_session) {

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
            'partner_name'   => $this->input->post('partner_name'),
            'type'           => $this->input->post('type'),
            'social_media'   => $this->input->post('social_media'),
            'contact_name'   => $this->input->post('contact_name'),
            'phone'          => $this->input->post('phone'),
        ];

        $this->Partner_model->update($id_session, $data);
        $status = 'Update Data Partner' ;


        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'patner/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->Partner_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Partner berhasil diupdate');
        redirect('partner/lihat/'. $id_session);
    }

    public function soft_delete($id_session) {

        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='4' OR $this->session->level=='5'){
        

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
    
            $data = ['status' => 'deleted'];
            $this->Partner_model->update($id_session, $data);
    
            $data_log = array(
    
                'log_activity_user_id'=>$this->session->id_session,
                'log_activity_modul' => 'partner/delete',
                'log_activity_document_no' => $id_session,
                'log_activity_status' => 'Delete Partner',
                'log_activity_waktu' => date('Y-m-d H:i:s'),
                'log_activity_platform'=> $agent,
                'log_activity_ip'=> $this->input->ip_address()
                
            );
    
            $this->Partner_model->insert_log_activity($data_log);
    
    
            $this->session->set_flashdata('Success', 'Partner berhasil dihapus');
            redirect('partner');
             }else{
                    redirect(base_url());
                }
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

        $this->Partner_model->delete_permanent($id_session);

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'partner/delete_permanent',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Delete Permanent Partner',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->Partner_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Partner berhasil dihapus permanen');
        redirect('partner/recycle_bin');
    }

    public function recycle_bin() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('partner',$this->session->id_session);
            $data['deleted_partner'] = $this->Partner_model->get_deleted_partner();
            $this->load->view('partner/recycle_bin', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('partner',$this->session->id_session);
            $data['deleted_partner'] = $this->Partner_model->get_deleted_partner();
            $this->load->view('partner/recycle_bin', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('partner',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('partner',$this->session->id_session);
            $data['deleted_partner'] = $this->Partner_model->get_deleted_partner();
            $this->load->view('partner/recycle_bin', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('partner',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
    }

    public function restore($id_session) {

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

        $data = ['status' => 'created'];
        $this->Partner_model->update($id_session, $data);

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'partner/restore',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Restore Partner',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->Partner_model->insert_log_activity($data_log);      
    
        $this->session->set_flashdata('Success', 'Partner berhasil dipulihkan');
        redirect('partner/recycle_bin');
    }
}
