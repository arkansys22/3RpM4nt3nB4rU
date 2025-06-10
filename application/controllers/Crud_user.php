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
            $data['users'] = $this->Users2_model->get_all_user();
            $this->load->view('user/index', $data);

        }else if ($this->session->level=='4'){
            cek_session_akses_staff_admin('user',$this->session->id_session);
            $data['users'] = $this->Users2_model->get_all_user_admin();
            $this->load->view('user/index', $data);

        }else{
                redirect(base_url());
                }
    }


    public function create() {
        if ($this->session->level=='1'){
            cek_session_akses_developer('user',$this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering('user_level','user_level_id','asc');
            $this->load->view('user/create', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('user',$this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering_admin('user_level','user_level_id','asc');
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

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(
            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/create',
            'log_activity_document_no' => $id_session2,
            'log_activity_status' => 'Tambah Pengguna',
            'log_activity_platform'=> $agent,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_ip'=> $ip_with_location            
        );
        $this->Users2_model->insert_log_activity($data_log);   
    
        $this->session->set_flashdata('Success', 'Pengguna berhasil dibuat');
        redirect('user');
    }

    public function lihat($id_session) {
        if ($this->session->level=='1'){
            cek_session_akses_developer('user',$this->session->id_session);
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $data['logactivity'] = $this->Users2_model->get_logactivity_by_session($id_session);
            $this->load->view('user/lihat', $data);


        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('user',$this->session->id_session);
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $data['logactivity'] = $this->Users2_model->get_logactivity_by_session($id_session);
            $this->load->view('user/lihat', $data);

        }else if($this->session->level=='7'){
            cek_session_akses_staff('user',$this->session->id_session);
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $data['logactivity'] = $this->Users2_model->get_logactivity_by_session($id_session);
            $this->load->view('user/lihat', $data);

        }else{
                    redirect(base_url());
                }
    }

    public function edit($id_session) {
        if ($this->session->level == '1') {
            cek_session_akses_developer('user', $this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering('user_level', 'user_level_id', 'asc');
            $data['clients'] = $this->Crud_m->view_ordering('clients', 'id', 'asc');
            $data['partner'] = $this->Crud_m->view_ordering('partner', 'id', 'asc'); // Fetch partner
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);

            $view_type = $this->input->get('view_type'); // Get 'view_type' from query string
            if ($view_type === 'edit_staff') {
                // Fetch specific crew based on crews_idsession
                $crews_idsession = $data['pc']->crews_idsession;
                $data['crews'] = $this->Users2_model->get_crew_by_id_session($crews_idsession);
                $this->load->view('user/edit_staff', $data);
            } else {
                // Fetch all crews for user/edit
                $data['crews'] = $this->Crud_m->view_ordering('crews', 'id', 'asc');
                $this->load->view('user/edit', $data);
            }
        } else if ($this->session->level == '4') {
            cek_session_akses_staff_admin('user', $this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering_admin('user_level', 'user_level_id', 'asc');
            $data['clients'] = $this->Crud_m->view_ordering('clients', 'id', 'asc');
            $data['partner'] = $this->Crud_m->view_ordering('partner', 'id', 'asc'); // Fetch partner
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);

            $view_type = $this->input->get('view_type');
            if ($view_type === 'edit_staff') {
                // Fetch specific crew based on crews_idsession
                $crews_idsession = $data['pc']->crews_idsession;
                $data['crews'] = $this->Users2_model->get_crew_by_id_session($crews_idsession);
                $this->load->view('user/edit_staff', $data);
            } else {
                // Fetch all crews for user/edit
                $data['crews'] = $this->Crud_m->view_ordering('crews', 'id', 'asc');
                $this->load->view('user/edit', $data);
            }
        }else if($this->session->level=='7'){
            cek_session_akses_staff('user',$this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering('user_level','user_level_id','asc');
            $data['clients'] = $this->Crud_m->view_ordering('clients','id','asc');
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);

            // Fetch crews_idsession from user table
            $crews_idsession = $data['pc']->crews_idsession;

            // Fetch crews data using crews_idsession
            $data['crews'] = $this->Users2_model->get_crew_by_id_session($crews_idsession);

            $this->load->view('user/edit_staff', $data); // Load edit_staff view

        } else {
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
            'partner_idsession'        => $this->input->post('partnerid'),
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
            'partner_idsession'        => $this->input->post('partnerid'),
            'password'    => sha1($this->input->post('password'))       
            );

        }
    
        $this->Users2_model->update_users($id_session, $data);

        $status = 'Update Data Pengguna';
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_platform'=> $agent,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Users2_model->insert_log_activity($data_log);
    
        $this->session->set_flashdata('Success', 'Pengguna berhasil diupdate');
        redirect('user/lihat/' . $id_session);
    }

    public function update2($id_session) {

        if ($this->agent->is_browser()) {
            $agent = 'Desktop ' . $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = 'Mobile ' . $this->agent->mobile() . ' ' . $this->agent->version();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $user_lama = $this->Users2_model->get_users_by_session($id_session);

        $user_data = array(
            'username' => $this->input->post('username') !== null ? $this->input->post('username') : $user_lama->username,
            'nama' => $this->input->post('nama') !== null ? $this->input->post('nama') : $user_lama->nama,
            'email' => $this->input->post('email') !== null ? $this->input->post('email') : $user_lama->email,
        );

        if ($this->input->post('password') != '') {
            $user_data['password'] = sha1($this->input->post('password'));
        }

        $this->Users2_model->update_users($id_session, $user_data);

        // Fetch crews_idsession from user table
        $user = $this->Users2_model->get_users_by_session($id_session);
        $crews_idsession = $user->crews_idsession;

        if ($crews_idsession) {
            // Ambil data crews lama
            $crews_lama = $this->Users2_model->get_crew_by_id_session($crews_idsession);

            // Update crews table, gunakan data lama jika input kosong
            $crews_data = array(
                'gender' => $this->input->post('gender') !== null ? $this->input->post('gender') : $crews_lama->gender,
                'religion' => $this->input->post('religion') !== null ? $this->input->post('religion') : $crews_lama->religion,
                'phone' => $this->input->post('phone') !== null ? $this->input->post('phone') : $crews_lama->phone,
                'birth_date' => $this->input->post('birth_date') !== null ? $this->input->post('birth_date') : $crews_lama->birth_date,
                'address' => $this->input->post('address') !== null ? $this->input->post('address') : $crews_lama->address,
            );

            $this->Users2_model->update_crews($crews_idsession, $crews_data);
        }

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        // Log activity
        $data_log = array(
            'log_activity_user_id' => $this->session->id_session,
            'log_activity_modul' => 'user/edit_staff',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Update Data Pengguna',
            'log_activity_platform' => $agent,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_ip'=> $ip_with_location,
        );

        $this->Users2_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Data pengguna berhasil diupdate');
        redirect('user/lihat/' . $id_session);
    }

    public function delete($id_session) {
        if ($this->session->level=='1' OR $this->session->level=='4'){
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

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/delete',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Hapus Pengguna',
            'log_activity_platform'=> $agent,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Users2_model->insert_log_activity($data_log);


        $this->session->set_flashdata('Success', 'Pengguna berhasil dihapus');
        redirect('user');
        }else{
                redirect(base_url());
            }
    }

    public function recycle_bin() {
        if ($this->session->level=='1' OR $this->session->level=='4'){
            cek_session_akses_developer('user',$this->session->id_session);

        $data['users'] = $this->Users2_model->get_deleted_user();  // Get projects with status 'delete'
        $this->load->view('user/recycle_bin', $data);
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
        $data = ['user_stat' => 'Publish'];
        $this->Users2_model->update_Users($id_session, $data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/restore',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Restore',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Users2_model->insert_log_activity($data_log);      

        $this->session->set_flashdata('Success', 'Pengguna berhasil dipulihkan');
        redirect('user');
    }

    public function permanent_delete($id_session) {

        if ($this->session->level=='1'OR $this->session->level=='4'){
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

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/permanent',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Hapus Permanent Pengguna',
            'log_activity_platform'=> $agent,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Users2_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Pengguna berhasil dihapus permanen');
        redirect('user/recycle_bin');
        }
    }
    
}
