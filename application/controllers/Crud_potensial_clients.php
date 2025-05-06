<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class crud_potensial_clients extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Potensial_model');
        $this->load->helper('url');
    }

    public function index() {
        if ($this->session->level == '1') {
            cek_session_akses_developer('potensial-clients', $this->session->id_session);
            
            // Ambil semua data potensial clients
            $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients();
    
            // Ambil data berdasarkan status tertentu
            $data['potensial_clients_tanya'] = $this->Potensial_model->get_clients_by_status('Tanya-tanya');
            $data['potensial_clients_hot'] = $this->Potensial_model->get_clients_by_status('Hot');
            $data['potensial_clients_konsul'] = $this->Potensial_model->get_clients_by_status('Konsul');
            $data['potensial_clients_deal'] = $this->Potensial_model->get_clients_by_status('Deal');
            $data['potensial_clients_ghosting'] = $this->Potensial_model->get_clients_by_status('Ghosting');
            $data['potensial_clients_batal'] = $this->Potensial_model->get_clients_by_status('Batal');
    
            $this->load->view('potensial_clients/index', $data);
        } else if ($this->session->level == '2') {
            cek_session_akses_administrator('potensial-clients', $this->session->id_session);
            
            // Ambil semua data potensial clients
            $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients();
    
            // Ambil data berdasarkan status tertentu
            $data['potensial_clients_tanya'] = $this->Potensial_model->get_clients_by_status('Tanya-tanya');
            $data['potensial_clients_hot'] = $this->Potensial_model->get_clients_by_status('Hot');
            $data['potensial_clients_konsul'] = $this->Potensial_model->get_clients_by_status('Konsul');
            $data['potensial_clients_deal'] = $this->Potensial_model->get_clients_by_status('Deal');
            $data['potensial_clients_ghosting'] = $this->Potensial_model->get_clients_by_status('Ghosting');
            $data['potensial_clients_batal'] = $this->Potensial_model->get_clients_by_status('Batal');
    
            $this->load->view('potensial_clients/index', $data);
        } else if ($this->session->level == '3') {
            cek_session_akses_staff_accounting('potensial-clients', $this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);
        } else if ($this->session->level == '4') {
            cek_session_akses_staff_admin('potensial-clients', $this->session->id_session);
            
            // Ambil semua data potensial clients
            $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients();
    
            // Ambil data berdasarkan status tertentu
            $data['potensial_clients_tanya'] = $this->Potensial_model->get_clients_by_status('Tanya-tanya');
            $data['potensial_clients_hot'] = $this->Potensial_model->get_clients_by_status('Hot');
            $data['potensial_clients_konsul'] = $this->Potensial_model->get_clients_by_status('Konsul');
            $data['potensial_clients_deal'] = $this->Potensial_model->get_clients_by_status('Deal');
            $data['potensial_clients_ghosting'] = $this->Potensial_model->get_clients_by_status('Ghosting');
            $data['potensial_clients_batal'] = $this->Potensial_model->get_clients_by_status('Batal');
    
            $this->load->view('potensial_clients/index', $data);
        } else if ($this->session->level == '5') {
            cek_session_akses_client('potensial-clients', $this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);
        } else {
            redirect(base_url());
        }
    }

    public function index_hot() {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_hot(); // Ubah pemanggilan model
                $data['potensial_clients_hot'] = $this->Potensial_model->get_clients_by_status('Hot');
                $this->load->view('potensial_clients/index_hot', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_hot(); // Ubah pemanggilan model
                $data['potensial_clients_hot'] = $this->Potensial_model->get_clients_by_status('Hot');
                $this->load->view('potensial_clients/index_hot', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_hot(); // Ubah pemanggilan model
                $data['potensial_clients_hot'] = $this->Potensial_model->get_clients_by_status('Hot');
                $this->load->view('potensial_clients/index_hot', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients',$this->session->id_session);
                redirect(base_url());
                
            }else{
                redirect(base_url());
                }

    }

    public function index_konsul() {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_konsul(); // Ubah pemanggilan model
                $data['potensial_clients_konsul'] = $this->Potensial_model->get_clients_by_status('Konsul');
                $this->load->view('potensial_clients/index_konsul', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_konsul(); // Ubah pemanggilan model
                $data['potensial_clients_konsul'] = $this->Potensial_model->get_clients_by_status('Konsul');
                $this->load->view('potensial_clients/index_konsul', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_konsul(); // Ubah pemanggilan model
                $data['potensial_clients_konsul'] = $this->Potensial_model->get_clients_by_status('Konsul');
                $this->load->view('potensial_clients/index_konsul', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients',$this->session->id_session);
                redirect(base_url());
                
            }else{
                redirect(base_url());
                }
    }

    public function index_deal() {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_bayar(); // Ubah pemanggilan model
                $data['potensial_clients_deal'] = $this->Potensial_model->get_clients_by_status('Deal');
                $this->load->view('potensial_clients/index_bayar', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_bayar(); // Ubah pemanggilan model
                $data['potensial_clients_deal'] = $this->Potensial_model->get_clients_by_status('Deal');
                $this->load->view('potensial_clients/index_bayar', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_bayar(); // Ubah pemanggilan model
                $data['potensial_clients_deal'] = $this->Potensial_model->get_clients_by_status('Deal');
                $this->load->view('potensial_clients/index_bayar', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients',$this->session->id_session);
                redirect(base_url());
                
            }else{
                redirect(base_url());
                }
        
    }

    public function index_ghosting() {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_ghosting(); // Ubah pemanggilan model
                $data['potensial_clients_ghosting'] = $this->Potensial_model->get_clients_by_status('Ghosting');
                $this->load->view('potensial_clients/index_ghosting', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_ghosting(); // Ubah pemanggilan model
                $data['potensial_clients_ghosting'] = $this->Potensial_model->get_clients_by_status('Ghosting');
                $this->load->view('potensial_clients/index_ghosting', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_ghosting(); // Ubah pemanggilan model
                $data['potensial_clients_ghosting'] = $this->Potensial_model->get_clients_by_status('Ghosting');
                $this->load->view('potensial_clients/index_ghosting', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients',$this->session->id_session);
                redirect(base_url());
                
            }else{
                redirect(base_url());
                }
    }

    public function index_batal() {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_batal(); // Ubah pemanggilan model
                $data['potensial_clients_batal'] = $this->Potensial_model->get_clients_by_status('Batal');
                $this->load->view('potensial_clients/index_batal', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_batal(); // Ubah pemanggilan model
                $data['potensial_clients_batal'] = $this->Potensial_model->get_clients_by_status('Batal');
                $this->load->view('potensial_clients/index_batal', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_all_potensial_clients_batal(); // Ubah pemanggilan model
                $data['potensial_clients_batal'] = $this->Potensial_model->get_clients_by_status('Batal');
                $this->load->view('potensial_clients/index_batal', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients',$this->session->id_session);
                redirect(base_url());
                
            }else{
                redirect(base_url());
                }
    }

    public function create() {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients',$this->session->id_session);
                $this->load->view('potensial_clients/create');

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients',$this->session->id_session);
                $this->load->view('potensial_clients/create');

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients',$this->session->id_session);
                $this->load->view('potensial_clients/create');

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients',$this->session->id_session);
                redirect(base_url());
                
            }else{
                redirect(base_url());
                }       
    }

    public function store() {
        $id_session2 = hash('sha256', bin2hex(random_bytes(16)));
        $date_create = date('Y-m-d H:i:s');  // tanggal dan waktu
        
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
            'pc_name'  => $this->input->post('pc_name'),
            'pc_nowa'  => $this->input->post('pc_nowa'),
            'status'        => 'Tanya-tanya',
            'create_by'     => $this->session->id_session,
            'event_date'    => $this->input->post('event_date'),
            'chat_date'    => $this->input->post('chat_date'),
            'location'      => $this->input->post('location'),
            'create_date'   => $date_create
        );
    
        // Insert ke tabel projects
        $this->Potensial_model->insert_potensial_clients($data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'potensial-clients/create',
            'log_activity_document_no' => $id_session2,
            'log_activity_status' => 'Tambah Potensial Klien',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Potensial_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Potensial klien berhasil dibuat');
        redirect('potensial-clients');
    }

    public function lihat($id_session) {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $data['logactivity'] = $this->Potensial_model->get_logactivity_by_session($id_session);
                $this->load->view('potensial_clients/lihat', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $data['logactivity'] = $this->Potensial_model->get_logactivity_by_session($id_session);
                $this->load->view('potensial_clients/lihat', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $data['logactivity'] = $this->Potensial_model->get_logactivity_by_session($id_session);
                $this->load->view('potensial_clients/lihat', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients',$this->session->id_session);
                redirect(base_url());
                
            }else{
                redirect(base_url());
                }
    }

    public function edit($id_session) {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $this->load->view('potensial_clients/edit', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $this->load->view('potensial_clients/edit', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $this->load->view('potensial_clients/edit', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients',$this->session->id_session);
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
        $data = array(
            'pc_name'  => $this->input->post('pc_name'),
            'pc_nowa'  => $this->input->post('pc_nowa'),
            'event_date'    => $this->input->post('event_date'),
            'chat_date'    => $this->input->post('chat_date'),
            'location'      => $this->input->post('location'),
            'status'      => $this->input->post('status'),
            'note'      => $this->input->post('note'),
        );
    
        $this->Potensial_model->update_potensial_clients($id_session, $data);

        $status = 'Update Potensial Client ' . $this->input->post('status');
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'potensial-clients/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Potensial_model->insert_log_activity($data_log);
    
        $this->session->set_flashdata('Success', 'Projects berhasil diupdate');
        redirect('potensial-clients/lihat/' .$id_session);
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
        $this->Potensial_model->update_potensial_clients($id_session, $data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'potensial-clients/delete',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Delete Potensial Client',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Potensial_model->insert_log_activity($data_log);


        $this->session->set_flashdata('Success', 'Potensial klien berhasil dihapus');
        redirect('potensial-clients');
         }else{
                redirect(base_url());
            }
    }

    public function recycle_bin() {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_deleted_potensial_clients();  // Get projects with status 'delete'
                $this->load->view('potensial_clients/recycle_bin', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_deleted_potensial_clients();  // Get projects with status 'delete'
                $this->load->view('potensial_clients/recycle_bin', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_deleted_potensial_clients();  // Get projects with status 'delete'
                $this->load->view('potensial_clients/recycle_bin', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients',$this->session->id_session);
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
        $data = ['status' => 'Tanya-tanya'];
        $this->Potensial_model->update_potensial_clients($id_session, $data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'potensial-clients/restore',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Restore Potensial Client',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Potensial_model->insert_log_activity($data_log);      
    
        $this->session->set_flashdata('Success', 'Potensial klien berhasil dipulihkan');
        redirect('potensial-clients');
    }

    public function permanent_delete($id_session) {
        $this->Potensial_model->delete_potensial_clients_permanent($id_session);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'potensial-clients/delete permanent',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Delete Permanent Potensial Client',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Potensial_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Potensial klien berhasil dihapus permanent');
        redirect('potensial-clients/recycle_bin');
    }
    
}
