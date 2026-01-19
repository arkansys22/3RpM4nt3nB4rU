<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class crud_sales_marketing extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Salesmarketing_model');
        $this->load->model('Users2_model');
        $this->load->helper('url');
    }

    public function index() {
        if ($this->session->level=='1'){
        cek_session_akses_developer('sales-setting-target',$this->session->id_session);
        $data['ops'] = $this->Salesmarketing_model->get_all_salesmarketing(); // Ubah pemanggilan model
        $this->load->view('sales_marketing/index', $data);

        }else if ($this->session->level=='9'){
        cek_session_akses_staff_sales('sales-setting-target',$this->session->id_session);
        $data['ops'] = $this->Salesmarketing_model->get_all_salesmarketing(); // Ubah pemanggilan model
        $this->load->view('sales_marketing/index', $data);

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
        if ($this->session->level=='1' OR $this->session->level=='2'){
            cek_session_akses_developer('sales-setting-target',$this->session->id_session);
            $data['user'] = $this->Crud_m->view_where_user_orderingss('user','id_user', 'asc');
            $year = date('Y');
            $periode = [];
            for ($m = 1; $m <= 12; $m++) {
                $periode[] = [
                    'bulan' => str_pad($m, 2, '0', STR_PAD_LEFT),
                    'label'      => strftime('%B %Y', mktime(0, 0, 0, $m, 1, $year)), // Januari 2026
                    'periode'    => $year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT), // YYYY-MM
                    'tahun' => $year
                ];
            }
            $data['periode'] = $periode;
         
            $this->load->view('sales_marketing/create', $data);

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
            'targetsales_idsession'    => $id_session2,
            'user_id_session'  => $this->input->post('nama'),
            'targetsales_periode'  => $this->input->post('periode'),
            'targetsales_nominal'        => str_replace('.', '', $this->input->post('nominal')),               
            'create_by'     => $this->session->id_session,
            'create_date'   => $date_create
        );
    
        // Insert ke tabel projects
        $this->Salesmarketing_model->insert($data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(
            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'sales-setting-target/create',
            'log_activity_document_no' => $id_session2,
            'log_activity_status' => 'Tambah Target Sales',
            'log_activity_platform'=> $agent,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_ip'=> $ip_with_location            
        );
        $this->Users2_model->insert_log_activity($data_log);   
    
        $this->session->set_flashdata('Success', 'Berhasil dibuat');
        redirect('sales-setting-target');
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
        if ($this->session->level=='1' OR $this->session->level=='2'){
            cek_session_akses_developer('sales-setting-target',$this->session->id_session);
            $data['user'] = $this->Crud_m->view_where_user_orderingss('user','id_user', 'asc');
            $year = date('Y');
            $periode = [];
            for ($m = 1; $m <= 12; $m++) {
                $periode[] = [
                    'bulan' => $m,
                    'label' => date('F', mktime(0, 0, 0, $m, 1)),
                    'tahun' => $year
                ];
            }
            $data['periode'] = $periode;

            $data['pc'] = $this->Salesmarketing_model->get_salesmarketing_by_session($id_session);
            $this->load->view('sales_marketing/edit', $data);
            
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
            'user_id_session'  => $this->input->post('nama'),
            'targetsales_nominal'        => str_replace('.', '', $this->input->post('nominal')), 
            'targetsales_periode'    => $this->input->post('periode')                 
            );
         
        $this->Salesmarketing_model->update_salesmarketing($id_session, $data);

        $status = 'Edit Target Session';
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'sales-setting-target/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Salesmarketing_model->insert_log_activity($data_log);
    
        $this->session->set_flashdata('Success', 'Target sales berhasil diupdate');
        redirect('sales-setting-target');
    }

    public function permanent_delete($id_session) {

        if ($this->session->level=='1' OR $this->session->level=='2'){
            cek_session_akses_developer('sales-setting-target',$this->session->id_session);

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


            $this->Salesmarketing_model->delete_permanent($id_session);

            $ip = $this->input->ip_address();
            $location = get_location_from_ip($ip);
            $ip_with_location = $ip . "<br>(" . $location . ")";

            $data_log = array(

                'log_activity_user_id'=>$this->session->id_session,
                'log_activity_modul' => 'sales-setting-target/permanent',
                'log_activity_document_no' => $id_session,
                'log_activity_status' => 'Hapus Permanent',
                'log_activity_waktu' => date('Y-m-d H:i:s'),
                'log_activity_platform'=> $agent,
                'log_activity_ip'=> $ip_with_location
                
            );

            $this->Users2_model->insert_log_activity($data_log);

        
        $this->session->set_flashdata('Success', 'Berhasil dihapus permanen');
        redirect('sales-setting-target');
        }
    }
    
}
