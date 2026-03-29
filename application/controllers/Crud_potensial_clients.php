<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/dompdf/autoload.inc.php';
require_once APPPATH.'third_party/dompdf/src/Options.php';

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
        } else if ($this->session->level == '9') {
            cek_session_akses_staff_sales('potensial-clients', $this->session->id_session);
            
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

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients',$this->session->id_session);
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

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients',$this->session->id_session);
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

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients',$this->session->id_session);
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


    public function index_pricelist() {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients-pricelist',$this->session->id_session);
                $data['potensial_clients_pl'] = $this->Potensial_model->get_all_pricelist(); // Ubah pemanggilan model
                $data['potensial_clients_pricelist'] = $this->Potensial_model->get_pricelist_by_status('Aktif');
                $this->load->view('potensial_clients/index_pricelist', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients-pricelist',$this->session->id_session);
                $data['potensial_clients_pl'] = $this->Potensial_model->get_all_pricelist(); // Ubah pemanggilan model
                $data['potensial_clients_pricelist'] = $this->Potensial_model->get_pricelist_by_status('Aktif');
                $this->load->view('potensial_clients/index_pricelist', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients-pricelist',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients-pricelist',$this->session->id_session);
                $data['potensial_clients_pl'] = $this->Potensial_model->get_all_pricelist(); // Ubah pemanggilan model
                $data['potensial_clients_pricelist'] = $this->Potensial_model->get_pricelist_by_status('Aktif');
                $this->load->view('potensial_clients/index_pricelist', $data);

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients-pricelist',$this->session->id_session);
                $data['potensial_clients_pl'] = $this->Potensial_model->get_all_pricelist(); // Ubah pemanggilan model
                $data['potensial_clients_pricelist'] = $this->Potensial_model->get_pricelist_by_status('Aktif');
                $this->load->view('potensial_clients/index_pricelist', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients-pricelist',$this->session->id_session);
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

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients',$this->session->id_session);
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

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients',$this->session->id_session);
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

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients',$this->session->id_session);
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
            'status'        => $this->input->post('status'),
            'note'      => $this->input->post('note'),
            'nomeradmin'      => $this->input->post('nomeradmin'),
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


    public function create_pricelist() {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients-pricelist',$this->session->id_session);
                $this->load->view('potensial_clients/create_pricelist');

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients-pricelist',$this->session->id_session);
                $this->load->view('potensial_clients/create_pricelist');

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients-pricelist',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients-pricelist',$this->session->id_session);
                $this->load->view('potensial_clients/create_pricelist');

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients-pricelist',$this->session->id_session);
                $this->load->view('potensial_clients/create_pricelist');

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients-pricelist',$this->session->id_session);
                redirect(base_url());
                
            }else{
                redirect(base_url());
                }       
    }


    public function store_pricelist() {
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
            'data_pricelist_idsession'      => $id_session2,
            'data_pricelist_judul'          => $this->input->post('judul'),
            'data_pricelist_harga'          => str_replace('.', '', $this->input->post('harga')),
            'data_pricelist_hargapromo'     => str_replace('.', '', $this->input->post('promo')), 
            'data_pricelist_diskonmax'      => str_replace('.', '', $this->input->post('diskon')),
            'data_pricelist_deskripsi'      => $this->input->post('deskripsi'),
            'data_pricelist_type'           => $this->input->post('kategori'), 
            'data_pricelist_visibilitas'    => $this->input->post('visibilitas'), 
            'data_pricelist_status'           => 'Aktif',          
            'data_pricelist_lastupdate'     => $date_create
        );
    
        // Insert ke tabel projects
        $this->Potensial_model->insert_pricelist($data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'potensial-clients-pricelist/create',
            'log_activity_document_no' => $id_session2,
            'log_activity_status' => 'Tambah Pricelist',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Potensial_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Potensial klien berhasil dibuat');
        redirect('potensial-clients-pricelist');
    }

    public function lihat($id_session) {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $data['logactivity'] = $this->Potensial_model->get_logactivity_by_session($id_session);
                $data['penawaran'] = $this->Potensial_model->get_penawaran_by_session($id_session);
                $data['kategori'] = $this->db->get('data_pricelist_kategori')->result();
                $this->load->view('potensial_clients/lihat', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $data['logactivity'] = $this->Potensial_model->get_logactivity_by_session($id_session);
                $data['penawaran'] = $this->Potensial_model->get_penawaran_by_session($id_session);
                $data['kategori'] = $this->db->get('data_pricelist_kategori')->result();
                $this->load->view('potensial_clients/lihat', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $data['logactivity'] = $this->Potensial_model->get_logactivity_by_session($id_session);
                $data['penawaran'] = $this->Potensial_model->get_penawaran_by_session($id_session);
                $data['kategori'] = $this->db->get('data_pricelist_kategori')->result();
                $this->load->view('potensial_clients/lihat', $data);

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $data['logactivity'] = $this->Potensial_model->get_logactivity_by_session($id_session);
                $data['penawaran'] = $this->Potensial_model->get_penawaran_by_session($id_session);
                $data['kategori'] = $this->db->get('data_pricelist_kategori')->result();
                $this->load->view('potensial_clients/lihat', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients',$this->session->id_session);
                redirect(base_url());
                
            }else{
                redirect(base_url());
                }
    }

    public function lihat_pricelist($id_session) {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients-pricelist',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_pricelist_by_session($id_session);
                $data['pc_img'] = $this->db
    ->where('data_pricelist_idsession', $id_session)
    ->get('data_pricelist_gambar')
    ->result();


                $data['logactivity'] = $this->Potensial_model->get_logactivity_by_session($id_session);
                $this->load->view('potensial_clients/lihat_pricelist', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients-pricelist',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_pricelist_by_session($id_session);
                $data['pc_img'] = $this->Potensial_model->get_pricelist_img_by_session($id_session);
                $data['logactivity'] = $this->Potensial_model->get_logactivity_by_session($id_session);
                $this->load->view('potensial_clients/lihat_pricelist', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients-pricelist',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients-pricelist',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_pricelist_by_session($id_session);
                $data['logactivity'] = $this->Potensial_model->get_logactivity_by_session($id_session);
                $this->load->view('potensial_clients/lihat_pricelist', $data);

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients-pricelist',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_pricelist_by_session($id_session);
                $data['logactivity'] = $this->Potensial_model->get_logactivity_by_session($id_session);
                $this->load->view('potensial_clients/lihat_pricelist', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients-pricelist',$this->session->id_session);
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

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $this->load->view('potensial_clients/edit', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients',$this->session->id_session);
                redirect(base_url());
                
            }else{
                redirect(base_url());
                }
    }


    public function edit_pricelist($id_session) {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients-pricelist',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_pricelist_by_session($id_session);
                $this->load->view('potensial_clients/edit_pricelist', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients-pricelist',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_pricelist_by_session($id_session);
                $this->load->view('potensial_clients/edit_pricelist', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients-pricelist',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients-pricelist',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_pricelist_by_session($id_session);
                $this->load->view('potensial_clients/edit_pricelist', $data);

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients-pricelist',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_pricelist_by_session($id_session);
                $this->load->view('potensial_clients/edit_pricelist', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients-pricelist',$this->session->id_session);
                redirect(base_url());
                
            }else{
                redirect(base_url());
                }
    }


    public function edit_penawaran($id_session) {

        if ($this->session->level=='1'){
                cek_session_akses_developer('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $this->load->view('potensial_clients/edit_penawaran', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $this->load->view('potensial_clients/edit_penawaran', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $this->load->view('potensial_clients/edit_penawaran', $data);

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients',$this->session->id_session);
                $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);
                $this->load->view('potensial_clients/edit_penawaran', $data);

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
            'nomeradmin'    => $this->input->post('nomeradmin'),
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



    public function update_pricelist($id_session) {

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
            'data_pricelist_judul'  => $this->input->post('judul'),
            'data_pricelist_harga'  => str_replace('.', '', $this->input->post('harga')),
            'data_pricelist_hargapromo'    => str_replace('.', '', $this->input->post('promo')),
            'data_pricelist_diskonmax'    => str_replace('.', '', $this->input->post('diskon')),
            'data_pricelist_deskripsi'    => $this->input->post('deskripsi'),
            'data_pricelist_type'      => $this->input->post('kategori'),
            'data_pricelist_visibilitas'      => $this->input->post('visibilitas'),
            'data_pricelist_lastupdate'      => date('Y-m-d H:i:s'),
        );
    
        $this->Potensial_model->update_pricelist($id_session, $data);

        $status = 'Update Pricelist';
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'potensial-clients-pricelist/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Potensial_model->insert_log_activity($data_log);
    
        $this->session->set_flashdata('Success', 'Pricelist berhasil diupdate');
        redirect('potensial-clients-pricelist/lihat/' .$id_session);
    }


    public function delete($id_session) {

        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='4' OR $this->session->level=='9'){

        

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


    public function delete_pricelist($id_session) {

        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='4' OR $this->session->level=='9'){

        

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

        $data = ['data_pricelist_status' => 'delete'];
        $this->Potensial_model->update_pricelist($id_session, $data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'potensial-clients-pricelist/delete',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Delete Pricelist',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Potensial_model->insert_log_activity($data_log);


        $this->session->set_flashdata('Success', 'Pricelist berhasil dihapus');
        redirect('potensial-clients-pricelist');
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

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('potensial-clients',$this->session->id_session);
                $data['potensial_clients'] = $this->Potensial_model->get_deleted_potensial_clients();  // Get projects with status 'delete'
                $this->load->view('potensial_clients/recycle_bin', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('potensial-clients',$this->session->id_session);
                redirect(base_url());
                
            }else{
                redirect(base_url());
                }
    }


    public function recycle_bin_pricelist() {

        if ($this->session->level=='1'){
                cek_session_akses_developer('recycle_bin_pricelist',$this->session->id_session);
                $data['pricelist_deleted'] = $this->Potensial_model->get_deleted_pricelist();  // Get projects with status 'delete'
                $this->load->view('potensial_clients/recycle_bin_pricelist', $data);

            }else if($this->session->level=='2'){
                cek_session_akses_administrator('recycle_bin_pricelist',$this->session->id_session);
                $data['pricelist_deleted'] = $this->Potensial_model->get_deleted_pricelist();  // Get projects with status 'delete'
                $this->load->view('potensial_clients/recycle_bin_pricelist', $data);

            }else if($this->session->level=='3'){
                cek_session_akses_staff_accounting('potensial-clients',$this->session->id_session);
                redirect(base_url());

            }else if($this->session->level=='4'){
                cek_session_akses_staff_admin('recycle_bin_pricelist',$this->session->id_session);
                $data['pricelist_deleted'] = $this->Potensial_model->get_deleted_pricelist();  // Get projects with status 'delete'
                $this->load->view('potensial_clients/recycle_bin_pricelist', $data);

            }else if($this->session->level=='9'){
                cek_session_akses_staff_sales('recycle_bin_pricelist',$this->session->id_session);
                $data['pricelist_deleted'] = $this->Potensial_model->get_deleted_pricelist();  // Get projects with status 'delete'
                $this->load->view('potensial_clients/recycle_bin_pricelist', $data);

            }else if($this->session->level=='5'){
                cek_session_akses_client('recycle_bin_pricelist',$this->session->id_session);
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

    public function restore_pricelist($id_session) {

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
        $data = ['data_pricelist_status' => 'Aktif'];
        $this->Potensial_model->update_pricelist($id_session, $data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'potensial-clients-pricelist/restore',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Restore Pricelist',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Potensial_model->insert_log_activity($data_log);      
    
        $this->session->set_flashdata('Success', 'Pricelist berhasil dipulihkan');
        redirect('potensial-clients-pricelist/recycle_bin');
    }

    public function permanent_delete($id_session) {
        $this->Potensial_model->delete_potensial_clients_permanent($id_session);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

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

    public function permanent_delete_penawaran($id, $id_session) {
        $this->db->where('penawaran_klien_id', $id)->delete('penawaran_klien');

        redirect('potensial-clients/lihat/'.$id_session);
    }


    public function view_penawaran($id_session) {
        // Ambil data pembayaran berdasarkan id_session dan transactions_id
       

        $data['penawaran'] = $this->Potensial_model->get_penawaran_by_session($id_session);
    
        // Ambil data project berdasarkan id_session
        $data['pc'] = $this->Potensial_model->get_potensial_clients_by_session($id_session);  
    
    
        // Kirim data ke view
        $this->load->view('potensial_clients/view_penawaran', $data);
    }



    public function download_proposal($id){

        $pc = $this->db->get_where('potensial_clients',['id_session'=>$id])->row();
        $penawaran = $this->db->get_where('penawaran_klien',['penawaran_klien_potensial_idsession'=>$id])->result();

        $data = [
            'pc' => $pc,
            'penawaran' => $penawaran
        ];

        $html = $this->load->view('potensial_clients/proposal_pdf',$data,true);

        $options = new Dompdf\Options();
        $options->set('isRemoteEnabled', TRUE);

        $dompdf = new Dompdf\Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4','portrait');
        $dompdf->render();

      

        $dompdf->stream(
        "Proposal-".$pc->pc_name."-".$pc->location.".pdf",
        array("Attachment"=>true)
        );
    }


    public function permanent_delete_pricelist($id_session) {
        $this->Potensial_model->delete_pricelist_permanent($id_session);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

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

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'potensial-clients-pricelist/delete permanent',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Delete Permanent Pricelist',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Potensial_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Pricelist berhasil dihapus permanent');
        redirect('potensial-clients-pricelist/recycle_bin');
    }


    public function permanent_delete_gambar($id_session,$idgambar) {
        $query = $this->Potensial_model->delete_gambar_permanent($idgambar);
            if($query){
                    unlink("./assets/uploads/pricelist/".$idgambar->data_pricelist_gambar_nama);
                 }



        $this->session->set_flashdata('Success', 'Pricelist berhasil dihapus permanent');
        redirect('potensial-clients-pricelist/lihat/'.$id_session);
    }


    public function update_penawaran($id_session)
    {
        $data = [
            'penawaran_klien_potensial_idsession' => $id_session,
            'penawaran_klien_pricelist_kategori'      => $this->input->post('kategori'),
            'penawaran_klien_idpricelist'     => $this->input->post('produk_id'),
            'penawaran_klien_harga'    => $this->input->post('harga_asli'),
            'penawaran_klien_hargapromo'   => $this->input->post('harga_promo'),
            'penawaran_klien_diskon'   => $this->input->post('maks_diskon'),
            'penawaran_klien_deskripsi'     => $this->input->post('detail'),
            'penawaran_klien_qty'     => $this->input->post('qty'),
            'created_at'    => date('Y-m-d H:i:s')
        ];

        $this->db->insert('penawaran_klien', $data);

        redirect('potensial-clients/lihat/'.$id_session);
    }


    public function update_promo($id_session)
    {
        $data = [
           
            'promo'      => $this->input->post('promo'),
            'promo_value'     => $this->input->post('nilai_promo')
        ];

        $this->Potensial_model->update_potensial_clients($id_session, $data);

        redirect('potensial-clients/lihat/'.$id_session);
    }



    public function get_pricelist_by_kategori()
    {
        header('Content-Type: application/json');

        $kategori_id = $this->input->get('kategori_id');

        if (!$kategori_id) {
            echo json_encode([]);
            exit;
        }

        $produk = $this->db
            ->where('data_pricelist_type', $kategori_id)
            ->where('data_pricelist_status', 'Aktif')
            ->get('data_pricelist')
            ->result_array();

        echo json_encode($produk);
        exit;
    }


    public function get_pricelist_detail()
    {
        $produk_id = $this->input->get('produk_id');

        $data = $this->db
            ->where('data_pricelist_idsession', $produk_id)
            ->get('data_pricelist')
            ->row_array();

        if ($data) {
            echo json_encode([
                'harga_asli'  => $data['data_pricelist_harga'],
                'harga_promo' => $data['data_pricelist_hargapromo'],
                'maks_diskon' => $data['data_pricelist_diskonmax'],
                'deskripsi'   => $data['data_pricelist_deskripsi']
            ]);
        } else {
            echo json_encode(null);
        }
    }


    public function upload_gambar()
{
    // Hapus semua buffer sebelumnya
    while (ob_get_level()) ob_end_clean();

    header('Content-Type: application/json; charset=utf-8');

    $sendJSON = function($data) {
        echo json_encode($data);
        exit;
    };

    // Tangkap semua warning/error
    set_error_handler(function($severity, $message, $file, $line) use ($sendJSON) {
        $sendJSON([
            'status' => 'error',
            'message' => "PHP ERROR: $message in $file:$line"
        ]);
    });

    $id = $this->input->post('id');
    if (empty($id)) $sendJSON(['status'=>'error','message'=>'ID tidak ditemukan']);

    if (empty($_FILES['gambar']['name'])) $sendJSON(['status'=>'error','message'=>'File tidak dipilih']);

    // 🔹 Absolute path tanpa trailing slash untuk CI
    $basePath = FCPATH . 'assets/uploads/pricelist';

    // 🔹 Buat folder jika belum ada
    if (!is_dir($basePath)) {
        if (!mkdir($basePath, 0777, true)) {
            $sendJSON(['status'=>'error','message'=>'Gagal membuat folder upload']);
        }
    }

    // 🔹 Pastikan folder writable
    if (!is_writable($basePath)) {
        $sendJSON(['status'=>'error','message'=>'Folder tidak writable','path'=>$basePath]);
    }

    // 🔹 Konfigurasi upload
    $config = [
        'upload_path'   => $basePath, // **tanpa trailing slash**
        'allowed_types' => 'jpg|jpeg|png|webp',
        'file_name'     => 'pricelist_' . time(),
        'max_size'      => 1024
    ];

    $this->load->library('upload', $config);

    // 🔹 Reset library untuk memastikan path valid
    $this->upload->initialize($config);

    // 🔹 Validasi path
    if (!$this->upload->validate_upload_path()) {
        $sendJSON([
            'status'=>'error',
            'message'=>'Path upload tidak valid',
            'path_config'=>$config['upload_path'],
            'is_dir'=>is_dir($config['upload_path']),
            'writable'=>is_writable($config['upload_path'])
        ]);
    }

    // 🔹 Upload file
    if (!$this->upload->do_upload('gambar')) {
        $sendJSON(['status'=>'error','message'=>strip_tags($this->upload->display_errors())]);
    }

    $file = $this->upload->data();

    // 🔹 Simpan ke database
    $this->db->insert('data_pricelist_gambar', [
        'data_pricelist_idsession' => $id,
        'data_pricelist_gambar_nama' => $file['file_name']
    ]);

    $sendJSON([
        'status'=>'success',
        'url'=>base_url('assets/uploads/pricelist/' . $file['file_name'])
    ]);
}



    
}
