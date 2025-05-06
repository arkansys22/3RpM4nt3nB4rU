<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_crews extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('crews_model');
        $this->load->helper('url');
    }

    public function index() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('crews',$this->session->id_session);
            $data['crews'] = $this->crews_model->get_all_crews(); // Ubah pemanggilan model
            $this->load->view('crews/index', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('crews',$this->session->id_session);
            $data['crews'] = $this->crews_model->get_all_crews(); // Ubah pemanggilan model
            $this->load->view('crews/index', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('crews',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('crews',$this->session->id_session);
            $data['crews'] = $this->crews_model->get_all_crews(); // Ubah pemanggilan model
            $this->load->view('crews/index', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('crews',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);
            
        }else{
            redirect(base_url());
            }
    }

    public function recycle_bin() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('crews',$this->session->id_session);
            $data['crews'] = $this->crews_model->get_deleted();  // Get projects with status 'delete'
            $this->load->view('crews/recycle_bin', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('crews',$this->session->id_session);
            $data['crews'] = $this->crews_model->get_deleted();  // Get projects with status 'delete'
            $this->load->view('crews/recycle_bin', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('crews',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('crews',$this->session->id_session);
            $data['crews'] = $this->crews_model->get_deleted();  // Get projects with status 'delete'
            $this->load->view('crews/recycle_bin', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('crews',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
    }

    public function create() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('crews',$this->session->id_session);
            $this->load->view('crews/create');

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('crews',$this->session->id_session);
            $this->load->view('crews/create');

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('crews',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('crews',$this->session->id_session);
            $this->load->view('crews/create');

        }else if($this->session->level=='5'){
            cek_session_akses_client('crews',$this->session->id_session);
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
            'id_session'  => $id_session,
            'crew_name'   => $this->input->post('crew_name'),
            'gender'      => $this->input->post('gender'),
            'religion'    => $this->input->post('religion'),
            'phone'       => $this->input->post('phone'),
            'address'     => $this->input->post('address'),
            'birth_date'  => $this->input->post('birth_date'),
            'joining_date'=> $this->input->post('joining_date'),
            'created_at'  => $created_at,
            'status'      => 'active'
        ];
        $this->crews_model->insert($data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'crews/create',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Tambah Crew',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->crews_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Crew berhasil dibuat');

        redirect('crews');
    }

    public function lihat($id_session) {

        if ($this->session->level=='1'){
            cek_session_akses_developer('crews',$this->session->id_session);
            $data['crews'] = $this->crews_model->get_by_id_session($id_session);
            $data['logactivity'] = $this->crews_model->get_logactivity_by_session($id_session);
            $this->load->view('crews/lihat', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('crews',$this->session->id_session);
            $data['crews'] = $this->crews_model->get_by_id_session($id_session);
            $data['logactivity'] = $this->crews_model->get_logactivity_by_session($id_session);
            $this->load->view('crews/lihat', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('crews',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('crews',$this->session->id_session);
            $data['crews'] = $this->crews_model->get_by_id_session($id_session);
            $data['logactivity'] = $this->crews_model->get_logactivity_by_session($id_session);
            $this->load->view('crews/lihat', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('crews',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
    }

    public function edit($id_session) {

        if ($this->session->level=='1'){
            cek_session_akses_developer('crews',$this->session->id_session);
            $data['crews'] = $this->crews_model->get_by_id_session($id_session);
            $this->load->view('crews/edit', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('crews',$this->session->id_session);
            $data['crews'] = $this->crews_model->get_by_id_session($id_session);
            $this->load->view('crews/edit', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('crews',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('crews',$this->session->id_session);
            $data['crews'] = $this->crews_model->get_by_id_session($id_session);
            $this->load->view('crews/edit', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('crews',$this->session->id_session);
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
            'crew_name'   => $this->input->post('crew_name'),
            'gender'      => $this->input->post('gender'),
            'religion'    => $this->input->post('religion'),
            'phone'       => $this->input->post('phone'),
            'address'     => $this->input->post('address'),
            'birth_date'  => $this->input->post('birth_date'),
            'joining_date'=> $this->input->post('joining_date'),
        ];

        $this->crews_model->update($id_session, $data);

        $status = 'Update Data Crew' ;
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'crews/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->crews_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Crew berhasil diupdate');
        redirect('crews/lihat/'. $id_session);
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

        $data = ['status' => 'delete'];
        $this->crews_model->update($id_session, $data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'crews/delete',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Delete Crew',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->crews_model->insert_log_activity($data_log);


        $this->session->set_flashdata('Success', 'Crew berhasil dihapus');
        redirect('crews');
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

        $data = ['status' => 'active'];
        $this->crews_model->update($id_session, $data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'crews/restore',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Restore Crew',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->crews_model->insert_log_activity($data_log);      
    
        $this->session->set_flashdata('Success', 'Crew berhasil dipulihkan');
        redirect('crews/recycle_bin');
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

        $this->crews_model->delete_permanent($id_session);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'crews/delete_permanent',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Delete Permanent Crew',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->crews_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Crew berhasil dihapus permanen');
        redirect('crews/recycle_bin');
    }
}
?>
