<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_clients extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Clients_model');
        $this->load->model('Vendor_model');
        $this->load->model('Agenda_model');
        $this->load->helper('url');
    }

    public function index() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_all_clients(); // Ubah pemanggilan model
            $this->load->view('clients/index', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_all_clients(); // Ubah pemanggilan model
            $this->load->view('clients/index', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('clients',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_all_clients(); // Ubah pemanggilan model
            $this->load->view('clients/index', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('clients',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);
            
        }else{
            redirect(base_url());
            }
    }

    public function create() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('clients',$this->session->id_session);
            $this->load->view('clients/create');

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('clients',$this->session->id_session);
            $this->load->view('clients/create');

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('clients',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('clients',$this->session->id_session);
            $this->load->view('clients/create');

        }else if($this->session->level=='5'){
            cek_session_akses_client('clients',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }       
    }

    public function store() {

        $id_session = hash('sha256', bin2hex(random_bytes(16)));

        $created_at = date('Y-m-d H:i:s'); // Waktu sekarang

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
            
            'client_name'           => $this->input->post('client_name'),
            'email'                 => $this->input->post('email'),
            'phone'                 => $this->input->post('phone'),
            'create_by'             => $this->session->id_session,
            'status'                => 'create',
            'f_bride_fname'         => $this->input->post('f_bride_fname'),
            'f_bride_cname'         => $this->input->post('f_bride_cname'),
            'f_bride_nchild'        => $this->input->post('f_bride_nchild'),
            'f_bride_hsibling'      => $this->input->post('f_bride_hsibling'),
            'f_bride_fathername'    => $this->input->post('f_bride_fathername'),
            'f_bride_fathercname'   => $this->input->post('f_bride_fathercname'),
            'f_bride_freplacementname' => $this->input->post('f_bride_freplacementname'),
            'f_bride_freplacementcname' => $this->input->post('f_bride_freplacementcname'),
            'f_bride_mothername'    => $this->input->post('f_bride_mothername'),
            'f_bride_mothercname'   => $this->input->post('f_bride_mothercname'),
            'f_bride_mreplacementname' => $this->input->post('f_bride_mreplacementname'),
            'f_bride_mreplacementcname' => $this->input->post('f_bride_mreplacementcname'),
            'f_bride_sibling'       => $this->input->post('f_bride_sibling'),
            'm_bride_fname'         => $this->input->post('m_bride_fname'),
            'm_bride_cname'         => $this->input->post('m_bride_cname'),
            'm_bride_nchild'        => $this->input->post('m_bride_nchild'),
            'm_bride_hsibling'      => $this->input->post('m_bride_hsibling'),
            'm_bride_fathername'    => $this->input->post('m_bride_fathername'),
            'm_bride_fathercname'   => $this->input->post('m_bride_fathercname'),
            'm_bride_freplacementname' => $this->input->post('m_bride_freplacementname'),
            'm_bride_freplacementcname' => $this->input->post('m_bride_freplacementcname'),
            'm_bride_mothername'    => $this->input->post('m_bride_mothername'),
            'm_bride_mothercname'   => $this->input->post('m_bride_mothercname'),
            'm_bride_mreplacementname' => $this->input->post('m_bride_mreplacementname'),
            'm_bride_mreplacementcname' => $this->input->post('m_bride_mreplacementcname'),
            'm_bride_sibling'       => $this->input->post('m_bride_sibling'),
            'mahr'                  => $this->input->post('mahr'),
            'handover'              => $this->input->post('handover'),
            'female_coor'           => $this->input->post('female_coor'),
            'male_coor'             => $this->input->post('male_coor'),
            'f_spokesman'           => $this->input->post('f_spokesman'),
            'm_spokesman'           => $this->input->post('m_spokesman'),
            'wedding_officiant'     => $this->input->post('wedding_officiant'),
            'guardian'              => $this->input->post('guardian'),
            'f_witness'             => $this->input->post('f_witness'),
            'm_witness'             => $this->input->post('m_witness'),
            'qori'                  => $this->input->post('qori'),
            'advice_doa'            => $this->input->post('advice_doa'),
            'clamp'                 => $this->input->post('clamp'),
            'jasmine_carrier'       => $this->input->post('jasmine_carrier'),
            'mahr_carrier'          => $this->input->post('mahr_carrier'),
            'ring_carrier'          => $this->input->post('ring_carrier'),
            'pastor'                => $this->input->post('pastor'),
            'church'                => $this->input->post('church'),
            'prayer'                => $this->input->post('prayer'),
            'wedding_speech'        => $this->input->post('wedding_speech'),
            'wedding_date'          => $this->input->post('wedding_date'),
            'location'              => $this->input->post('location'),
            'maps'                  => $this->input->post('maps'),
            'created_at'            => $created_at,
            'create_day'            => date('l') // Simpan hari otomatis
        ];

        // Clear unused fields based on radio button selection
        if ($this->input->post('fayah_status') === 'Masih Ada') {
            $data['f_bride_freplacementname'] = null;
            $data['f_bride_freplacementcname'] = null;
        } else {
            $data['f_bride_fathername'] = null;
            $data['f_bride_fathercname'] = null;
        }

        if ($this->input->post('fibu_status') === 'Masih Ada') {
            $data['f_bride_mreplacementname'] = null;
            $data['f_bride_mreplacementcname'] = null;
        } else {
            $data['f_bride_mothername'] = null;
            $data['f_bride_mothercname'] = null;
        }

        if ($this->input->post('mayah_status') === 'Masih Ada') {
            $data['m_bride_freplacementname'] = null;
            $data['m_bride_freplacementcname'] = null;
        } else {
            $data['m_bride_fathername'] = null;
            $data['m_bride_fathercname'] = null;
        }

        if ($this->input->post('mibu_status') === 'Masih Ada') {
            $data['m_bride_mreplacementname'] = null;
            $data['m_bride_mreplacementcname'] = null;
        } else {
            $data['m_bride_mothername'] = null;
            $data['m_bride_mothercname'] = null;
        }

        $this->Clients_model->insert_client($data); // Ubah pemanggilan model

        $client_id = $this->db->insert_id();  // Mendapatkan id auto-increment

        $project_data = array(
            'id'           => $client_id,  // Gunakan id yang sama di clients
            'id_session'   => $id_session,
            'client_name'  => $this->input->post('client_name'),
            'event_date'   => $this->input->post('wedding_date'),
            'location'     => $this->input->post('location'),
            'create_date'  => $created_at,
            'create_by'    => $this->session->id_session,
            'status'       => 'create'
        );

        // Insert ke tabel project
        $this->db->insert('project', $project_data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'clients/create',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Tambah Client',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
             'log_activity_ip'=> $ip_with_location
            
        );

        $this->Clients_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Client berhasil dibuat');

        redirect('clients');
    }

    public function lihat($id_session) {

        if ($this->session->level=='1'){
            cek_session_akses_developer('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_client_by_session($id_session);
            $data['logactivity'] = $this->Clients_model->get_logactivity_by_session($id_session);
            $this->load->view('clients/lihat', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_client_by_session($id_session);
            $data['logactivity'] = $this->Clients_model->get_logactivity_by_session($id_session);
            $this->load->view('clients/lihat', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('clients',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_client_by_session($id_session);
            $data['logactivity'] = $this->Clients_model->get_logactivity_by_session($id_session);
            $this->load->view('clients/lihat', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('clients',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
    }

    public function edit($id_session) {

        if ($this->session->level=='1'){
            cek_session_akses_developer('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_client_by_session($id_session);
            $this->load->view('clients/edit', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_client_by_session($id_session);
            $this->load->view('clients/edit', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('clients',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_client_by_session($id_session);
            $this->load->view('clients/edit', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('clients',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
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
            'client_name'           => $this->input->post('client_name'),
            'email'                 => $this->input->post('email'),
            'phone'                 => $this->input->post('phone'),
            'wedding_ceremony'      => $this->input->post('wedding_ceremony'),
            'reception_afterward'   => $this->input->post('reception_afterward'),
            'list_photo'            => $this->input->post('list_photo'),
            'stand_by'              => $this->input->post('stand_by'),
            'uniform'               => $this->input->post('uniform'),
            'f_bride_fname'         => $this->input->post('f_bride_fname'),
            'f_bride_cname'         => $this->input->post('f_bride_cname'),
            'f_bride_nchild'        => $this->input->post('f_bride_nchild'),
            'f_bride_hsibling'      => $this->input->post('f_bride_hsibling'),
            'f_bride_fathername'    => $this->input->post('f_bride_fathername'),
            'f_bride_fathercname'   => $this->input->post('f_bride_fathercname'),
            'f_bride_freplacementname' => $this->input->post('f_bride_freplacementname'),
            'f_bride_freplacementcname' => $this->input->post('f_bride_freplacementcname'),
            'f_bride_mothername'    => $this->input->post('f_bride_mothername'),
            'f_bride_mothercname'   => $this->input->post('f_bride_mothercname'),
            'f_bride_mreplacementname' => $this->input->post('f_bride_mreplacementname'),
            'f_bride_mreplacementcname' => $this->input->post('f_bride_mreplacementcname'),
            'f_bride_sibling'       => $this->input->post('f_bride_sibling'),
            'm_bride_fname'         => $this->input->post('m_bride_fname'),
            'm_bride_cname'         => $this->input->post('m_bride_cname'),
            'm_bride_nchild'        => $this->input->post('m_bride_nchild'),
            'm_bride_hsibling'      => $this->input->post('m_bride_hsibling'),
            'm_bride_fathername'    => $this->input->post('m_bride_fathername'),
            'm_bride_fathercname'   => $this->input->post('m_bride_fathercname'),
            'm_bride_freplacementname' => $this->input->post('m_bride_freplacementname'),
            'm_bride_freplacementcname' => $this->input->post('m_bride_freplacementcname'),
            'm_bride_mothername'    => $this->input->post('m_bride_mothername'),
            'm_bride_mothercname'   => $this->input->post('m_bride_mothercname'),
            'm_bride_mreplacementname' => $this->input->post('m_bride_mreplacementname'),
            'm_bride_mreplacementcname' => $this->input->post('m_bride_mreplacementcname'),
            'm_bride_sibling'       => $this->input->post('m_bride_sibling'),
            'mahr'                  => $this->input->post('mahr'),
            'handover'              => $this->input->post('handover'),
            'female_coor'           => $this->input->post('female_coor'),
            'male_coor'             => $this->input->post('male_coor'),
            'f_spokesman'           => $this->input->post('f_spokesman'),
            'm_spokesman'           => $this->input->post('m_spokesman'),
            'wedding_officiant'     => $this->input->post('wedding_officiant'),
            'guardian'              => $this->input->post('guardian'),
            'f_witness'             => $this->input->post('f_witness'),
            'm_witness'             => $this->input->post('m_witness'),
            'qori'                  => $this->input->post('qori'),
            'advice_doa'            => $this->input->post('advice_doa'),
            'clamp'                 => $this->input->post('clamp'),
            'jasmine_carrier'       => $this->input->post('jasmine_carrier'),
            'mahr_carrier'          => $this->input->post('mahr_carrier'),
            'ring_carrier'          => $this->input->post('ring_carrier'),
            'pastor'                => $this->input->post('pastor'),
            'church'                => $this->input->post('church'),
            'prayer'                => $this->input->post('prayer'),
            'wedding_speech'        => $this->input->post('wedding_speech'),
            'wedding_date'          => $this->input->post('wedding_date'),
            'location'              => $this->input->post('location'),
            'maps'                  => $this->input->post('maps'),
        );

        // Clear unused fields based on radio button selection
        if ($this->input->post('fayah_status') === 'Masih Ada') {
            $data['f_bride_freplacementname'] = null;
            $data['f_bride_freplacementcname'] = null;
        } else {
            $data['f_bride_fathercname'] = null;
        }

        if ($this->input->post('fibu_status') === 'Masih Ada') {
            $data['f_bride_mreplacementname'] = null;
            $data['f_bride_mreplacementcname'] = null;
        } else {
            $data['f_bride_mothercname'] = null;
        }

        if ($this->input->post('mayah_status') === 'Masih Ada') {
            $data['m_bride_freplacementname'] = null;
            $data['m_bride_freplacementcname'] = null;
        } else {
            $data['m_bride_fathercname'] = null;
        }

        if ($this->input->post('mibu_status') === 'Masih Ada') {
            $data['m_bride_mreplacementname'] = null;
            $data['m_bride_mreplacementcname'] = null;
        } else {
            $data['m_bride_mothercname'] = null;
        }

        $this->Clients_model->update_client($id_session, $data);

        // Update juga di tabel project
        $project_data = array(
            'client_name' => $this->input->post('client_name'),
            'event_date' => $this->input->post('wedding_date'),
            'location' => $this->input->post('location'),
        );

            $this->db->where('id_session', $id_session);
            $this->db->update('project', $project_data);

        $status = 'Update Data Client' ;
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'clients/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
             'log_activity_ip'=> $ip_with_location
            
        );

        $this->Clients_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Client berhasil diupdate');
        redirect('clients/lihat/'.$id_session);
    }

    public function delete($id_session) {

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

        $data = ['status' => 'delete'];
        $this->Clients_model->update_client($id_session, $data);

        // Update juga di tabel project
        $this->db->where('id_session', $id_session);
        $this->db->update('project', $data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'clients/delete',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Delete Client',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
             'log_activity_ip'=> $ip_with_location
            
        );

        $this->Clients_model->insert_log_activity($data_log);


        $this->session->set_flashdata('Success', 'Client berhasil dihapus');
        redirect('clients');
         }else{
                redirect(base_url());
            }
    }

    public function recycle_bin() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_deleted_clients();  // Get projects with status 'delete'
            $this->load->view('clients/recycle_bin', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_deleted_clients();  // Get projects with status 'delete'
            $this->load->view('clients/recycle_bin', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('clients',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_deleted_clients();  // Get projects with status 'delete'
            $this->load->view('clients/recycle_bin', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('clients',$this->session->id_session);
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
                
        $data = ['status' => 'create']; // Kembalikan status menjadi 'create'
        $this->Clients_model->update_client($id_session, $data);
    
        // Update juga di tabel project
        $this->db->where('id_session', $id_session);
        $this->db->update('project', $data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'clients/restore',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Restore Client',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
             'log_activity_ip'=> $ip_with_location
            
        );

        $this->Clients_model->insert_log_activity($data_log);      
    
        $this->session->set_flashdata('Success', 'Client berhasil dipulihkan');
        redirect('clients/recycle_bin');
    }
    
    public function permanent_delete($id_session) {
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

        $this->Clients_model->delete_client_permanent($id_session);
    
        // Hapus juga di tabel project
        $this->db->where('id_session', $id_session);
        $this->db->delete('project');

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'clients/delete_permanent',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Delete Permanent Client',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
             'log_activity_ip'=> $ip_with_location
            
        );

        $this->Clients_model->insert_log_activity($data_log);      

        $this->session->set_flashdata('Success', 'Client berhasil dihapus permanent');
        redirect('clients/recycle_bin');
    }

    public function c_lihat($id_session) {
        $data['clients'] = $this->Clients_model->get_client_by_session($id_session);
        $data['vendors'] = $this->Vendor_model->get_vendor_by_id($id_session);
        $data['agenda'] = $this->Agenda_model->get_agenda_by_session($id_session);
        $data['logactivity'] = $this->Clients_model->get_logactivity_by_session($id_session);

        if (empty($data['clients']) && empty($data['vendors']) && empty($data['agenda']) && empty($data['logactivity'])) {
            $this->load->view('404');
        } else {
            $this->load->view('clients/c_lihat', $data);
        }
    }

    public function c_concept() {
        $id_session = $this->input->get('id_session');
        $vendor_id = $this->input->get('vendor_id');
        
        $data['vendors'] = $this->Vendor_model->get_vendor_by_id_and_vendor_id($id_session, $vendor_id);
        $this->load->view('clients/c_concept', $data);
    }
    
    public function c_update($id_session) {

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
            'f_bride_fname'         => $this->input->post('f_bride_fname'),
            'f_bride_cname'         => $this->input->post('f_bride_cname'),
            'f_bride_nchild'        => $this->input->post('f_bride_nchild'),
            'f_bride_hsibling'      => $this->input->post('f_bride_hsibling'),
            'f_bride_fathername'    => $this->input->post('f_bride_fathername'),
            'f_bride_fathercname'   => $this->input->post('f_bride_fathercname'),
            'f_bride_freplacementname' => $this->input->post('f_bride_freplacementname'),
            'f_bride_freplacementcname' => $this->input->post('f_bride_freplacementcname'),
            'f_bride_mothername'    => $this->input->post('f_bride_mothername'),
            'f_bride_mothercname'   => $this->input->post('f_bride_mothercname'),
            'f_bride_mreplacementname' => $this->input->post('f_bride_mreplacementname'),
            'f_bride_mreplacementcname' => $this->input->post('f_bride_mreplacementcname'),
            'f_bride_sibling'       => $this->input->post('f_bride_sibling'),
            'm_bride_fname'         => $this->input->post('m_bride_fname'),
            'm_bride_cname'         => $this->input->post('m_bride_cname'),
            'm_bride_nchild'        => $this->input->post('m_bride_nchild'),
            'm_bride_hsibling'      => $this->input->post('m_bride_hsibling'),
            'm_bride_fathername'    => $this->input->post('m_bride_fathername'),
            'm_bride_fathercname'   => $this->input->post('m_bride_fathercname'),
            'm_bride_freplacementname' => $this->input->post('m_bride_freplacementname'),
            'm_bride_freplacementcname' => $this->input->post('m_bride_freplacementcname'),
            'm_bride_mothername'    => $this->input->post('m_bride_mothername'),
            'm_bride_mothercname'   => $this->input->post('m_bride_mothercname'),
            'm_bride_mreplacementname' => $this->input->post('m_bride_mreplacementname'),
            'm_bride_mreplacementcname' => $this->input->post('m_bride_mreplacementcname'),
            'm_bride_sibling'       => $this->input->post('m_bride_sibling'),
            'mahr'                  => $this->input->post('mahr'),
            'handover'              => $this->input->post('handover'),
            'female_coor'           => $this->input->post('female_coor'),
            'male_coor'             => $this->input->post('male_coor'),
            'f_spokesman'           => $this->input->post('f_spokesman'),
            'm_spokesman'           => $this->input->post('m_spokesman'),
            'wedding_officiant'     => $this->input->post('wedding_officiant'),
            'guardian'              => $this->input->post('guardian'),
            'f_witness'             => $this->input->post('f_witness'),
            'm_witness'             => $this->input->post('m_witness'),
            'qori'                  => $this->input->post('qori'),
            'advice_doa'            => $this->input->post('advice_doa'),
            'clamp'                 => $this->input->post('clamp'),
            'jasmine_carrier'       => $this->input->post('jasmine_carrier'),
            'mahr_carrier'          => $this->input->post('mahr_carrier'),
            'ring_carrier'          => $this->input->post('ring_carrier'),
            'pastor'                => $this->input->post('pastor'),
            'church'                => $this->input->post('church'),
            'prayer'                => $this->input->post('prayer'),
            'wedding_speech'        => $this->input->post('wedding_speech'),
            'wedding_date'          => $this->input->post('wedding_date'),
            'location'              => $this->input->post('location'),
        );

        // Clear unused fields based on radio button selection
        if ($this->input->post('fayah_status') === 'Masih Ada') {
            $data['f_bride_freplacementname'] = null;
            $data['f_bride_freplacementcname'] = null;            
        } else {
            $data['f_bride_fathercname'] = null;
        }

        if ($this->input->post('fibu_status') === 'Masih Ada') {
            $data['f_bride_mreplacementname'] = null;
            $data['f_bride_mreplacementcname'] = null;
        } else {
            
            $data['f_bride_mothercname'] = null;
        }

        if ($this->input->post('mayah_status') === 'Masih Ada') {
            $data['m_bride_freplacementname'] = null;
            $data['m_bride_freplacementcname'] = null;
        } else {
            
            $data['m_bride_fathercname'] = null;
        }

        if ($this->input->post('mibu_status') === 'Masih Ada') {
            $data['m_bride_mreplacementname'] = null;
            $data['m_bride_mreplacementcname'] = null;
        } else {
           
            $data['m_bride_mothercname'] = null;
        }

        $this->Clients_model->update_client($id_session, $data);

        // Update juga di tabel project
        $project_data = array(
            'event_date' => $this->input->post('wedding_date'),
            'location' => $this->input->post('location'),
        );

        $this->db->where('id_session', $id_session);
        $this->db->update('project', $project_data);

        $status = 'Update Data Client';
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(
            'log_activity_user_id' => $this->session->id_session,
            'log_activity_modul' => 'clients/c_edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform' => $agent,
            'log_activity_ip' => $ip_with_location
        );

        $this->Clients_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Client berhasil diupdate');
        redirect('clients/c_lihat/' . $id_session);
    }

    public function c_edit($id_session) {

        if ($this->session->level=='1'){
            cek_session_akses_developer('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_client_by_session($id_session);
            $this->load->view('clients/c_edit', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_client_by_session($id_session);
            $this->load->view('clients/c_edit', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('clients',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('clients',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='5'){
            cek_session_akses_client('clients',$this->session->id_session);
            $data['clients'] = $this->Clients_model->get_client_by_session($id_session);
            $this->load->view('clients/c_edit', $data);
            
        }else{
            redirect(base_url('client/login'));
            }
    }

}
