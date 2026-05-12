<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_coa extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('coa_model');
        $this->load->helper('url');
    }

    public function index() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('coa',$this->session->id_session);
            $data['p'] = $this->coa_model->get_all_coa(); // Ubah pemanggilan model
            $this->load->view('coa/index', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('coa',$this->session->id_session);
            $data['p'] = $this->coa_model->get_all_coa(); // Ubah pemanggilan model
            $this->load->view('coa/index', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('coa',$this->session->id_session);
            $data['p'] = $this->coa_model->get_all_coa(); // Ubah pemanggilan model
            $this->load->view('coa/index', $data);
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
            cek_session_akses_developer('coa',$this->session->id_session);
            $this->load->view('coa/create');

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('coa',$this->session->id_session);
            $this->load->view('coa/create');

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('coa',$this->session->id_session);
            $this->load->view('coa/create');
        }else{
            redirect(base_url());
            }       
    }

      // AJAX ambil data berdasarkan prefix
    public function get_account_by_prefix()
    {
        $prefix = $this->input->post('prefix');
       

        $this->db->select('nomer_kategori , nama_kategori');
        $this->db->from('operational_kategori');
        $this->db->where("LEFT(nomer_kategori, 2) =", $prefix);

        $query = $this->db->get()->result();


        echo json_encode($query);
    }

    public function store() {

        $nama_kategori = $this->input->post('name');

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
        
            'nomer_kategori'   => $this->input->post('account_code'),
            'nama_kategori'      => $nama_kategori,
            'detail_kategori'    => $this->input->post('account_type')
        ];
        $this->coa_model->insert($data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'coa/create',
            'log_activity_document_no' => $nama_kategori,
            'log_activity_status' => 'Tambah account coa',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->coa_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'account coa berhasil dibuat');

        redirect('coa');
    }

    public function get_detail($id)
    {
        $data = $this->db
            ->where('accounting_nomer_kategori', $id)
            ->get('accounting')
            ->result();

        echo json_encode([
            'total' => count($data),
            'data' => $data
        ]);
    }
   public function detail($id)
{
    // akun coa
    $data['account'] = $this->db
        ->where('nomer_kategori', $id)
        ->get('operational_kategori')
        ->row();

    $data['transaksi'] = $this->db
        ->select('
            a.*,
            pay.total_paid,
            pay.transactions_id,
            pay.kategori as payment_kategori,

            MAX(
                COALESCE(
                    p.project_name,
                    pu_proj.project_name,
                    pay_proj.project_name
                )
            ) as project_name
        ')
        ->from('accounting a')

        // ==========================
        // PROJECT NORMAL
        // ==========================
        ->join(
            'project_acc pa',
            'pa.id_session = a.accounting_id_session',
            'left'
        )
        ->join(
            'project p',
            'p.id_session = pa.project_id_session',
            'left'
        )

        // ==========================
        // PROJECT UTANG
        // ==========================
        ->join(
            'project_acc_utang pu',
            'pu.id_session = a.accounting_id_session',
            'left'
        )
        ->join(
            'project pu_proj',
            'pu_proj.id_session = pu.project_id_session',
            'left'
        )

        // ==========================
        // PAYMENT
        // ==========================
        ->join(
            'payment pay',
            'pay.id_session = a.accounting_id_session',
            'left'
        )

        // PAYMENT -> POTENSIAL CLIENT
        ->join(
            'potensial_clients pc',
            'pc.id_session = pay.potensial_clients_id_session',
            'left'
        )

        // POTENSIAL -> PROJECT
        ->join(
            'project pay_proj',
            'pay_proj.id_session = pc.project_id_session',
            'left'
        )

        ->like('a.accounting_nomer_kategori', $id, 'after')

        // hindari duplicate
        ->group_by('a.accounting_id_session')

        ->order_by('a.accounting_tanggal', 'DESC')
        ->get()
        ->result();

    // total saldo
    $data['total'] = $this->db
        ->select_sum('accounting_nominal')
        ->like('accounting_nomer_kategori', $id, 'after')
        ->get('accounting')
        ->row()
        ->accounting_nominal ?? 0;

    if (!$data['account']) {
        show_404();
    }

    $this->load->view('coa/accounting_detail', $data);
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
            cek_session_akses_developer('coa',$this->session->id_session);
            $data['coa'] = $this->coa_model->get_by_id_session($id_session);
            $this->load->view('coa/edit', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('coa',$this->session->id_session);
            $data['coa'] = $this->coa_model->get_by_id_session($id_session);
            $this->load->view('coa/edit', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('coa',$this->session->id_session);
            $data['coa'] = $this->coa_model->get_by_id_session($id_session);
            $this->load->view('coa/edit', $data);

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
            'nomer_kategori'   => $this->input->post('account_code'),
            'nama_kategori'      => $this->input->post('name'),
            'detail_kategori'    => $this->input->post('account_type'),
        ];

        $this->coa_model->update($id_session, $data);

        $status = 'Update COA' ;
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'coa/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->coa_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'COA diupdate');
        redirect('coa');
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

    public function delete_permanent($id_session)
    {
        // 🔴 CEK DULU APAKAH ADA DATA DI ACCOUNTING
        $total = $this->db
            ->where('accounting_nomer_kategori', $id_session)
            ->count_all_results('accounting');

        if ($total > 0) {
            // ❌ JANGAN HAPUS
            $this->session->set_flashdata('error', 'Tidak dapat dihapus karena masih digunakan oleh '.$total.' transaksi accounting.');
            redirect('coa/edit/'.$id_session);
            return;
        }

        // ================== AGENT ==================
        if ($this->agent->is_browser()) {
            $agent = 'Desktop '.$this->agent->browser().' '.$this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = 'Mobile '.$this->agent->mobile().' '.$this->agent->version();
        } else {
            $agent = 'Unidentified User Agent';
        }

        // ✅ HAPUS DATA
        $this->coa_model->delete_permanent($id_session);

        // ================== LOG ==================
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(
            'log_activity_user_id'   => $this->session->id_session,
            'log_activity_modul'     => 'coa/delete_permanent',
            'log_activity_document_no'=> $id_session,
            'log_activity_status'    => 'Delete Permanent Account',
            'log_activity_waktu'     => date('Y-m-d H:i:s'),
            'log_activity_platform'  => $agent,
            'log_activity_ip'        => $ip_with_location
        );

        $this->coa_model->insert_log_activity($data_log);

        $this->session->set_flashdata('success', 'Account berhasil dihapus permanen');
        redirect('coa');
    }
}
?>
