<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class crud_user extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Users2_model');
        $this->load->helper('url');
    }

    public function index() {
        if ($this->session->level=='1'){
        cek_session_akses_developer('user',$this->session->id_session);
        $data['users'] = $this->Users2_model->get_all_user(); // Ubah pemanggilan model
        $this->load->view('user/index', $data);
        }else if ($this->session->level=='4'){
        cek_session_akses_staff_admin('user',$this->session->id_session);
        $data['users'] = $this->Users2_model->get_all_user(); // Ubah pemanggilan model
        $this->load->view('user/index', $data);

        }else{
                redirect(base_url());
                }
    }


    public function create() {
        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='5'){
            cek_session_akses_developer('user',$this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering('user_level','user_level_id','asc');
            $this->load->view('user/create', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('user',$this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering('user_level','user_level_id','asc');
            $this->load->view('user/create', $data);

        }else{
                redirect(base_url());
                }
    }

    public function store() {
        $id_session2 = sha1(uniqid());       
        
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
            'id_session'    => $id_session2,
            'username'  => $this->input->post('username'),
            'nama'  => $this->input->post('nama'),
            'email'        => $this->input->post('email'),            
            'password'    => sha1($this->input->post('password')),
            'level'    => $this->input->post('level'),
            'user_stat' => 'Publish',
            'user_login_status' => 'Offline',
            'create_by'     => $this->session->id_session,
            'user_post_hari'=>hari_ini(date('w')),
            'user_post_tanggal'=>date('Y-m-d'),
            'user_post_jam'=>date('H:i:s')
        );
    
        // Insert ke tabel projects
        $this->Users2_model->insert_users($data);

        $data_log = array(
            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/create',
            'log_activity_document_no' => $id_session2,
            'log_activity_status' => 'Tambah Pengguna',
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()            
        );
        $this->Users2_model->insert_log_activity($data_log);   
    
        $this->session->set_flashdata('Success', 'Pengguna berhasil dibuat');
        redirect('user');
    }

    public function lihat($id_session) {
        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='5'){
            cek_session_akses_developer('user',$this->session->id_session);
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $data['logactivity'] = $this->Users2_model->get_logactivity_by_session($id_session);
            $this->load->view('user/lihat', $data);


        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('user',$this->session->id_session);
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $data['logactivity'] = $this->Users2_model->get_logactivity_by_session($id_session);
            $this->load->view('user/lihat', $data);

        }else{
                    redirect(base_url());
                }
    }

    public function edit($id_session) {
        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='5'){
            cek_session_akses_developer('user',$this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering('user_level','user_level_id','asc');
            $data['crews'] = $this->Crud_m->view_ordering('crews','id','asc');
            $data['clients'] = $this->Crud_m->view_ordering('clients','id','asc');
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $this->load->view('user/edit', $data);
            
        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('user',$this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering('user_level','user_level_id','asc');
            $data['crews'] = $this->Crud_m->view_ordering('crews','id','asc');
            $data['clients'] = $this->Crud_m->view_ordering('clients','id','asc');
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $this->load->view('user/edit', $data);

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
        if ($this->input->post('password')=='' ){
        $data = array(
            'username'  => $this->input->post('username'),
            'nama'  => $this->input->post('nama'),
            'crews_idsession'        => $this->input->post('crewid'),
            'client_idsession'        => $this->input->post('clientid'),
            'email'        => $this->input->post('email'),
            'level'    => $this->input->post('level')       
            );
        }else{
            $data = array(
            'username'  => $this->input->post('username'),
            'nama'  => $this->input->post('nama'),
            'email'        => $this->input->post('email'),
            'level'    => $this->input->post('level'),
            'crews_idsession'        => $this->input->post('crewid'),
            'client_idsession'        => $this->input->post('clientid'),
            'password'    => sha1($this->input->post('password'))       
            );

        }
    
        $this->Users2_model->update_users($id_session, $data);
        $status = 'Edit Pengguna';


        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->Users2_model->insert_log_activity($data_log);
    
        $this->session->set_flashdata('Success', 'Pengguna berhasil diupdate');
        redirect('user/lihat/' . $id_session);
    }

    public function delete($id_session) {
        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='4' OR $this->session->level=='5'){
            cek_session_akses_developer('user',$this->session->id_session);
        

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

        $data = ['user_stat' => 'Delete'];
        $this->Users2_model->update_users($id_session, $data);
        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/delete',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Hapus',
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->Users2_model->insert_log_activity($data_log);


        $this->session->set_flashdata('Success', 'Pengguna berhasil dihapus');
        redirect('user');
         }else{
                redirect(base_url());
            }
    }

    public function recycle_bin() {
        $data['users'] = $this->Users2_model->get_deleted_user();  // Get projects with status 'delete'
        $this->load->view('user/recycle_bin', $data);
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
        $data = ['user_stat' => 'Publish'];
        $this->Users2_model->update_Users($id_session, $data);

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/restore',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Restore',
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->Users2_model->insert_log_activity($data_log);      
    
        $this->session->set_flashdata('Success', 'Pengguna berhasil dipulihkan');
        redirect('user');
    }

    public function permanent_delete($id_session) {

        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='4' OR $this->session->level=='5'){
            cek_session_akses_developer('user',$this->session->id_session);

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


                $this->Users2_model->delete_Users_permanent($id_session);

                $data_log = array(

                    'log_activity_user_id'=>$this->session->id_session,
                    'log_activity_modul' => 'user/permanent',
                    'log_activity_document_no' => $id_session,
                    'log_activity_status' => 'Hapus Permanent',
                    'log_activity_platform'=> $agent,
                    'log_activity_ip'=> $this->input->ip_address()
                    
                );

                $this->Users2_model->insert_log_activity($data_log);
    
        
        $this->session->set_flashdata('Success', 'Pengguna berhasil dihapus permanen');
        redirect('user/recycle_bin');
        }
    }
    
}
