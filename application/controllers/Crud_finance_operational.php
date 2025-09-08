<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class crud_finance_operational extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Operational_model');
        $this->load->model('Users2_model');
        $this->load->helper('url');
    }

    public function index() {
        if ($this->session->level=='1'){
        cek_session_akses_developer('finance-operational',$this->session->id_session);
        $data['ops'] = $this->Operational_model->get_all_operational_acc(); // Ubah pemanggilan model
        $this->load->view('operational/index', $data);

        }else if ($this->session->level=='3'){
        cek_session_akses_staff_accounting('finance-operational',$this->session->id_session);
        $data['ops'] = $this->Operational_model->get_all_operational_acc(); // Ubah pemanggilan model
        $this->load->view('operational/index', $data);

        }else{
                redirect(base_url());
                }
    }


    public function get_data() {
        $list = $this->Operational_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $p) {
            $no++;
            $row = array();
            $row[] = $p->tanggal_transaksi;
            $row[] = $p->kategori;
            $row[] = $p->nama_transaksi;
            $row[] = $p->nominal_transaksi;
            $row[] = $p->nominal_transaksi;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Operational_model->count_all(),
            "recordsFiltered" => $this->Operational_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }


    public function periode($id_session) {
        if ($this->session->level=='1' OR $this->session->level=='2'){
            cek_session_akses_developer('finance-operational',$this->session->id_session);
            $data['periode'] = $this->Operational_model->get_periode_by_session($id_session);
            $this->load->view('operational/index_periode', $data);


        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('finance-operational',$this->session->id_session);
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $data['logactivity'] = $this->Users2_model->get_logactivity_by_session($id_session);
            $this->load->view('user/lihat', $data);

        }else{
                    redirect(base_url());
                }
    }




    public function create() {
        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='5'){
            cek_session_akses_developer('user',$this->session->id_session);
            $data['kategori'] = $this->Operational_model->view_ordering('operational_kategori','nomer_kategori','asc');
            $data['periode'] = $this->Operational_model->view_ordering('operational_acc_periode','operational_acc_periode_id','asc');
            $this->load->view('operational/create', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('user',$this->session->id_session);
            $data['kategori'] = $this->Operational_model->view_ordering('operational_kategori','nomer_kategori','asc');
            $this->load->view('operational/create', $data);

        }else{
                redirect(base_url());
                }
    }

    public function store() {
        $id_session2 = hash('sha256', bin2hex(random_bytes(16)));
        $date_create = date('Y-m-d H:i:s');      
        
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
            'nama_transaksi'  => $this->input->post('nama_transaksi'),
            'tanggal_transaksi'  => $this->input->post('tgl_transaksi'),
            'nominal_transaksi'        => str_replace('.', '', $this->input->post('nominal')),
            'kategori'    => $this->input->post('kategori'),     
            'periode'    => $this->input->post('periode'),         
            'create_by'     => $this->session->id_session,
            'create_date'   => $date_create
        );
    
        // Insert ke tabel projects
        $this->Operational_model->insert($data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(
            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'finance-operational/create',
            'log_activity_document_no' => $id_session2,
            'log_activity_status' => 'Tambah Transaksi Operational',
            'log_activity_platform'=> $agent,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_ip'=> $ip_with_location            
        );
        $this->Users2_model->insert_log_activity($data_log);   
    
        $this->session->set_flashdata('Success', 'Berhasil dibuat');
        redirect('finance-operational');
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
            $data['kategori'] = $this->Crud_m->view_ordering('operational_kategori','nomer_kategori','asc');
            $data['periode'] = $this->Crud_m->view_ordering('operational_acc_periode','operational_acc_periode_id','asc');
            $data['pc'] = $this->Operational_model->get_operational_by_session($id_session);
            $this->load->view('operational/edit', $data);
            
        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('user',$this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering('user_level','user_level_id','asc');
            $data['crews'] = $this->Crud_m->view_ordering('crews','id','asc');
            $data['clients'] = $this->Crud_m->view_ordering('clients','id','asc');
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $this->load->view('operational/edit', $data);

        }else{
                redirect(base_url());
            }
    }


    public function edit2($id_session) {
        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='5'){
            cek_session_akses_developer('user',$this->session->id_session);
            $data['kategori'] = $this->Crud_m->view_ordering('operational_kategori','nomer_kategori','asc');
            $data['periode'] = $this->Crud_m->view_ordering('operational_acc_periode','operational_acc_periode_id','asc');
            $data['pc'] = $this->Operational_model->get_operational_by_session($id_session);
            $this->load->view('operational/edit2', $data);
            
        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('user',$this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering('user_level','user_level_id','asc');
            $data['crews'] = $this->Crud_m->view_ordering('crews','id','asc');
            $data['clients'] = $this->Crud_m->view_ordering('clients','id','asc');
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $this->load->view('operational/edit2', $data);

        }else{
                redirect(base_url());
            }
    }

    public function update2($id_session) {

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
            'nama_transaksi'  => $this->input->post('nama_transaksi'),
            'tanggal_transaksi'  => $this->input->post('tanggal_transaksi'),
            'nominal_transaksi'        => str_replace('.', '', $this->input->post('nominal_transaksi')), 
            'kategori'    => $this->input->post('kategori'), 
            'periode'    => $this->input->post('periode')                        
            );
         
        $this->Operational_model->update_operational($id_session, $data);

        $status = 'Edit Operational';
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'Operational/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Operational_model->insert_log_activity($data_log);
        $url_back = $this->input->post('url_back');
        $this->session->set_flashdata('Success', 'Operational berhasil diupdate');
        redirect($url_back);
       
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
            'nama_transaksi'  => $this->input->post('nama_transaksi'),
            'tanggal_transaksi'  => $this->input->post('tanggal_transaksi'),
            'nominal_transaksi'        => str_replace('.', '', $this->input->post('nominal_transaksi')), 
            'kategori'    => $this->input->post('kategori'), 
            'periode'    => $this->input->post('periode')                        
            );
         
        $this->Operational_model->update_operational($id_session, $data);

        $status = 'Edit Operational';
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'Operational/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Operational_model->insert_log_activity($data_log);
    
        $this->session->set_flashdata('Success', 'Operational berhasil diupdate');
        redirect('finance-operational');
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

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/delete',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Hapus',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
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


            $this->Operational_model->delete_permanent($id_session);

            $ip = $this->input->ip_address();
            $location = get_location_from_ip($ip);
            $ip_with_location = $ip . "<br>(" . $location . ")";

            $data_log = array(

                'log_activity_user_id'=>$this->session->id_session,
                'log_activity_modul' => 'finance-operational/permanent',
                'log_activity_document_no' => $id_session,
                'log_activity_status' => 'Hapus Permanent',
                'log_activity_waktu' => date('Y-m-d H:i:s'),
                'log_activity_platform'=> $agent,
                'log_activity_ip'=> $ip_with_location
                
            );

            $this->Users2_model->insert_log_activity($data_log);

        
        $this->session->set_flashdata('Success', 'Berhasil dihapus permanen');
        redirect('finance-operational');
        }
    }
    
}
