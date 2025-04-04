<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_finance_project extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('project_model');
        $this->load->model('finance_project_model');
       
        $this->load->helper('url');
    }

    public function index() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('project-acc',$this->session->id_session);
            $data['project'] = $this->project_model->get_all_project(); // Ubah pemanggilan model
            $this->load->view('projectacc/index', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('project-acc',$this->session->id_session);
            $data['project'] = $this->project_model->get_all_project(); // Ubah pemanggilan model
            $this->load->view('projectacc/index', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('project-acc',$this->session->id_session);
            $data['project'] = $this->project_model->get_all_project(); // Ubah pemanggilan model
            $this->load->view('projectacc/index', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('project-acc',$this->session->id_session);
            $data['project'] = $this->project_model->get_all_project(); // Ubah pemanggilan model
            $this->load->view('projectacc/index', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('project-acc',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);
            
        }else{
            redirect(base_url());
            }
    }

    public function create() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('project',$this->session->id_session);
            $this->load->view('project/create');

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('project',$this->session->id_session);
            $this->load->view('project/create');

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('project',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('project',$this->session->id_session);
            $this->load->view('project/create');

        }else if($this->session->level=='5'){
            cek_session_akses_client('project',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }       
    }

    public function store() {

        $id_session = hash('sha256', bin2hex(random_bytes(16)));

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
            'id_session'    => $id_session,
            'project_name'  => $this->input->post('project_name'),
            'status'        => 'create',
            'create_by'     => $this->session->id_session,
            'event_date'    => $this->input->post('event_date'),
            'location'      => $this->input->post('location'),
            'client_name'   => $this->input->post('client_name'),
            'value'         => str_replace('.', '', $this->input->post('value')),
            'detail'        => $this->input->post('detail'),
            'religion'      => $this->input->post('religion'),
            'create_date'   => $date_create
        );
    
        // Insert ke tabel project
        $this->project_model->insert_project($data);

         // Ambil id yang baru disimpan di tabel project
        $project_id = $this->db->insert_id();  // Mendapatkan id auto-increment
    
        // Ambil nama hari dari create_date
        $create_day = date('l', strtotime($date_create));
    
        // Data untuk tabel clients
        $client_data = array(
            'id'           => $project_id,  // Gunakan id yang sama di clients
            'id_session'   => $id_session,
            'client_name'  => $this->input->post('client_name'),
            'wedding_date' => $this->input->post('event_date'),
            'created_at'   => $date_create,
            'create_day'   => $create_day,
            'location'     => $this->input->post('location'),
            'create_by'    => $this->session->id_session,
            'status'       => 'create'
        );
    
        // Insert ke tabel clients
        $this->db->insert('clients', $client_data);

        // Data untuk tabel crew_projects
        $crew_projects_data = array(
            'project_id'   => $id_session,  
            'created_at'   => $date_create,
            'status'       => 'create'
        );
    
        // Insert ke tabel crew_projects
        $this->db->insert('crew_projects', $crew_projects_data);

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'project/create',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Tambah Project',
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->project_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Project berhasil dibuat');

        redirect('project');
    }

    public function lihat($id_session) {

        if ($this->session->level=='1'){
            cek_session_akses_developer('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);         
            $data['financeacc'] = $this->finance_project_model->get_all_projectacc($id_session);
            $data['modal_ops'] = $this->finance_project_model->get_finance_out($id_session);
          
            $this->load->view('projectacc/lihat', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $data['logactivity'] = $this->project_model->get_logactivity_by_session($id_session);
            $this->load->view('projectacc/lihat', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);         
            $data['financeacc'] = $this->finance_project_model->get_all_projectacc($id_session);
            $data['modal_ops'] = $this->finance_project_model->get_finance_out($id_session);
            $this->load->view('projectacc/lihat', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $data['logactivity'] = $this->project_model->get_logactivity_by_session($id_session);
            $this->load->view('projectacc/lihat', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('project',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
    }

    public function edit($id_session) {
        if ($this->session->level=='1'){
            cek_session_akses_developer('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $this->load->view('projectacc/edit', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $this->load->view('projectacc/edit', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);           
            $this->load->view('projectacc/edit', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $data['crews_list'] = $this->Crews_model->get_all_crews();
            $data['selected_crews'] = $this->CrewProjects_model->get_crew_by_project($id_session);
            $this->load->view('project/edit', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('project',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
    }


    public function edit2($id_session) {
        if ($this->session->level=='1'){
            cek_session_akses_developer('finance-project',$this->session->id_session);  
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $data['crews'] = $this->finance_project_model->view_join_where('crew_projects', $id_session,'crews','crew_id','id_session');
            $this->load->view('projectacc/create_crew', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $this->load->view('projectacc/edit', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);           
            $this->load->view('projectacc/edit', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $data['crews_list'] = $this->Crews_model->get_all_crews();
            $data['selected_crews'] = $this->CrewProjects_model->get_crew_by_project($id_session);
            $this->load->view('project/edit', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('project',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
    }

    public function update2($id_session) {
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

        
    
        $data = array(
            'id_session'  => $id_session,
            'project_id_session'  => $this->input->post('project_id_session'),
            'nama_transaksi'  => $this->input->post('nama_transaksi'),
            'tanggal_transaksi'   => $this->input->post('event_date'),
            'nominal_transaksi'    => str_replace('.', '', $this->input->post('value')),         
            'metode_transaksi'        => $this->input->post('metode'),
            'detail'      => $this->input->post('detail'),
            'create_by'    => $this->session->id_session,            
        );
    
        $this->db->insert('project_acc', $data);
      
        $project_id_session = $this->input->post('project_id_session');
        $status = 'Tambah Transaksi' ;
        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'finance-project/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->project_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Finance Project berhasil diupdate');
        redirect('finance-project/lihat/' . $project_id_session);
    }


    public function recycle_bin() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_deleted_project();  // Get projects with status 'delete'
            $this->load->view('project/recycle_bin', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_deleted_project();  // Get projects with status 'delete'
            $this->load->view('project/recycle_bin', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('project',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('project',$this->session->id_session);
            $data['project'] = $this->project_model->get_deleted_project();  // Get projects with status 'delete'
            $this->load->view('project/recycle_bin', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('project',$this->session->id_session);
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

        $data = ['status' => 'create'];
        $this->project_model->update_project($id_session, $data);
    
        // Update juga di tabel clients
        $this->db->where('id_session', $id_session);
        $this->db->update('clients', $data);

        // Update juga di tabel crew_projects
        $this->db->where('project_id', $id_session);
        $this->db->update('crew_projects', $data);

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'project/restore',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Restore',
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->project_model->insert_log_activity($data_log);      
    
        $this->session->set_flashdata('Success', 'Project berhasil dipulihkan');
        redirect('project/recycle_bin');
    }


    public function delete($project_id_session, $id_session) {
        // Cek apakah vendor dengan id_session dan vendor_id tersebut ada
        $project_acc = $this->db->get_where('project_acc', ['id_session' => $id_session, 'project_id_session' => $project_id_session])->row();

        if (!$project_acc) {
            $this->session->set_flashdata('error', 'Project ID tidak ditemukan.');
            redirect($_SERVER['HTTP_REFERER']); // Kembali ke halaman sebelumnya
        }

        // Hapus Project Acc
        if ($this->finance_project_model->delete_permanent($project_id_session,$id_session)) {
            $this->session->set_flashdata('success', 'Project Finance berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus vendor.');
        }
        redirect('finance-project/lihat/' . $project_id_session);
    }



    public function detail_project($id_session) {
        $this->load->model('Projects_model'); 
        $this->load->model('Payment_model');  
    
        $data['project'] = $this->Projects_model->get_by_session($id_session);
        $data['payments'] = $this->Payment_model->get_by_project($id_session); 
    
        if (!$data['project']) show_404();
    
        $this->load->view('projects/detail', $data);
    }
    
}
