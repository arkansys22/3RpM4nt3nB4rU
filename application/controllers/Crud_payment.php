<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_payment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Payment_model');
        $this->load->model('project_model');
        $this->load->helper('url');
    }

    public function lihat($id_session) {
        if ($this->session->level=='1'){
            cek_session_akses_developer('payment',$this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_session($id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $this->load->view('project/lihat', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('payment',$this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_session($id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $this->load->view('project/lihat', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('payment',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('payment',$this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_session($id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $this->load->view('project/lihat', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('payment',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
    }

    public function create($id_session) {
        if ($this->session->level=='1'){
            cek_session_akses_developer('payment',$this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_session($id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $this->load->view('payment/createinv', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('payment',$this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_session($id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $this->load->view('payment/createinv', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('payment',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('payment',$this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_session($id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            $this->load->view('payment/createinv', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('payment',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
    }

    public function create2($id_session, $transaction_id) {
        if (empty($id_session) || empty($transaction_id)) {
            show_404(); // Tampilkan 404 jika parameter tidak valid
        }

        if ($this->session->level == '1') {
            cek_session_akses_developer('project', $this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_session($id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
        $this->load->view('payment/createkwt', $data);

        } else if ($this->session->level == '2') {
            cek_session_akses_administrator('project', $this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_session($id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
        $this->load->view('payment/createkwt', $data);

        } else if ($this->session->level == '3') {
            cek_session_akses_staff_accounting('project', $this->session->id_session);
            redirect(base_url());

        } else if ($this->session->level == '4') {
            cek_session_akses_staff_admin('project', $this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_session($id_session);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
        $this->load->view('payment/createkwt', $data);

        } else if ($this->session->level == '5') {
            cek_session_akses_client('project', $this->session->id_session);
            redirect(base_url());

        } else {
            redirect(base_url());
        }
    }

    public function store() {
        $id_session = $this->input->post('id_session');

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
            'id_session'      => $id_session,
            'transactions_id' => 'IMB' . date('ymd', strtotime($this->input->post('date'))) . $this->input->post('number'),
            'total_bill'      => $this->input->post('total_bill'),
            'total_paid'      => 0, // Set total_paid to 0
            'detail'          => json_encode($this->input->post('detail')),
            'date'            => $this->input->post('date'),
            'due_date'        => $this->input->post('due_date'),
            'DP'              => $this->input->post('DP'),
            'created_by'      => $this->session->id_session,
        ];
        
        $this->Payment_model->insert_payment($data);
    
        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'payment/create',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Tambah Invoice',
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->Payment_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Invoice berhasil dibuat');

        redirect('project/lihat/' . $id_session);
    }

    public function store2() {
        $id_session = $this->input->post('id_session');

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
            'id_session' => $id_session,
            'transactions_id' => 'MBP1' . date('ymd', strtotime($this->input->post('date'))) . $this->input->post('number'),
            'total_paid' => $this->input->post('total_paid'),
            'total_bill' => 0, // Set total_bill to 0
            'detail' => json_encode($this->input->post('detail')),
            'date' => $this->input->post('date'),
            'due_date' => null, // Set due_date to null
            'created_by'    => $this->session->id_session,
        ];
        
        $this->Payment_model->insert_payment($data);
    
        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'payment/create2',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Tambah Kwitansi',
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->Payment_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Kwitansi berhasil dibuat');

        redirect('project/lihat/' . $id_session);
    }

    public function edit($id_session, $transaction_id) {
        if (empty($id_session) || empty($transaction_id)) {
            show_404(); // Tampilkan 404 jika parameter tidak valid
        }

        if ($this->session->level == '1') {
            cek_session_akses_developer('payment', $this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_transaction_id($id_session, $transaction_id);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            if (!$data['payment'] || strpos($data['payment']->transactions_id, 'IMB') !== 0) {
                show_404(); // Pastikan hanya invoice yang dapat diedit
            }
            $this->load->view('payment/editinv', $data);

        } else if ($this->session->level == '2') {
            cek_session_akses_administrator('payment', $this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_transaction_id($id_session, $transaction_id);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            if (!$data['payment'] || strpos($data['payment']->transactions_id, 'IMB') !== 0) {
                show_404(); // Pastikan hanya invoice yang dapat diedit
            }
            $this->load->view('payment/editinv', $data);

        } else if ($this->session->level == '3') {
            cek_session_akses_staff_accounting('payment', $this->session->id_session);
            redirect(base_url());

        } else if ($this->session->level == '4') {
            cek_session_akses_staff_admin('payment', $this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_transaction_id($id_session, $transaction_id);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            if (!$data['payment'] || strpos($data['payment']->transactions_id, 'IMB') !== 0) {
                show_404(); // Pastikan hanya invoice yang dapat diedit
            }
            $this->load->view('payment/editinv', $data);

        } else if ($this->session->level == '5') {
            cek_session_akses_client('payment', $this->session->id_session);
            redirect(base_url());

        } else {
            redirect(base_url());
        }
    }

    public function edit2($id_session, $transaction_id) {
        if (empty($id_session) || empty($transaction_id)) {
            show_404(); // Tampilkan 404 jika parameter tidak valid
        }

        if ($this->session->level == '1') {
            cek_session_akses_developer('payment', $this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_transaction_id($id_session, $transaction_id);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            if (!$data['payment'] || strpos($data['payment']->transactions_id, 'MBP1') !== 0) {
                show_404(); // Pastikan hanya kwitansi yang dapat diedit
            }
            $this->load->view('payment/editkwt', $data);

        } else if ($this->session->level == '2') {
            cek_session_akses_administrator('payment', $this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_transaction_id($id_session, $transaction_id);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            if (!$data['payment'] || strpos($data['payment']->transactions_id, 'MBP1') !== 0) {
                show_404(); // Pastikan hanya kwitansi yang dapat diedit
            }
            $this->load->view('payment/editkwt', $data);

        } else if ($this->session->level == '3') {
            cek_session_akses_staff_accounting('payment', $this->session->id_session);
            redirect(base_url());

        } else if ($this->session->level == '4') {
            cek_session_akses_staff_admin('payment', $this->session->id_session);
            $data['payment'] = $this->Payment_model->get_payment_by_transaction_id($id_session, $transaction_id);
            $data['project'] = $this->project_model->get_project_by_session($id_session);
            if (!$data['payment'] || strpos($data['payment']->transactions_id, 'MBP1') !== 0) {
                show_404(); // Pastikan hanya kwitansi yang dapat diedit
            }
            $this->load->view('payment/editkwt', $data);

        } else if ($this->session->level == '5') {
            cek_session_akses_client('payment', $this->session->id_session);
            redirect(base_url());

        } else {
            redirect(base_url());
        }
    }

    public function update($id_session, $transaction_id) {
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
            'total_bill'    => $this->input->post('total_bill'),
            'detail'        => json_encode($this->input->post('detail')),
            'date'          => $this->input->post('date'),
            'due_date'      => $this->input->post('due_date'),
            'DP'            => $this->input->post('DP'),

        ];

        $this->Payment_model->update_payment($id_session, $transaction_id, $data);
        
        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'payment/update',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Edit Invoice',
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->Payment_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Invoice berhasil diedit');

        redirect('project/lihat/' . $id_session);
    }

    public function update2($id_session, $transaction_id) {
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
            'total_paid'    => $this->input->post('total_paid'),
            'detail'        => json_encode($this->input->post('detail')),
            'date'          => $this->input->post('date'),
        ];

        $this->Payment_model->update_payment($id_session, $transaction_id, $data);
        
        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'payment/update2',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Edit Kwitansi',
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $this->input->ip_address()
            
        );

        $this->Payment_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Kwitansi berhasil diedit');

        redirect('project/lihat/' . $id_session);
    }

    public function delete($id_session, $transaction_id) {
        $payment = $this->Payment_model->get_payment_by_transaction_id($id_session, $transaction_id);

        if (!$payment) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan.');
            redirect($_SERVER['HTTP_REFERER']); // Kembali ke halaman sebelumnya
        }

        if ($this->Payment_model->delete_payment($id_session, $transaction_id)) {
            $this->session->set_flashdata('success', 'Transaksi berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus transaksi.');
        }
        redirect('project/lihat/' . $id_session);
    }

    public function view_invoice($id_session, $transactions_id) {
        // Ambil data pembayaran berdasarkan id_session dan transactions_id
        $data['payment'] = $this->Payment_model->get_payment_by_transaction_id($id_session, $transactions_id);
    
        // Ambil data project berdasarkan id_session
        $data['project'] = $this->project_model->get_project_by_session($id_session);
    
        // Tambahkan pengecekan jika data tidak ditemukan
        if (!$data['payment'] || !$data['project']) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('project/lihat/' . $id_session);
        }
    
        // Kirim data ke view
        $this->load->view('payment/view_invoice', $data);
    }
    
    public function view_kwitansi($id_session, $transactions_id) {
        // Ambil data pembayaran berdasarkan id_session dan transactions_id
        $data['payment'] = $this->Payment_model->get_payment_by_transaction_id($id_session, $transactions_id);
    
        // Ambil data project berdasarkan id_session
        $data['project'] = $this->project_model->get_project_by_session($id_session);
    
        // Tambahkan pengecekan jika data tidak ditemukan
        if (!$data['payment'] || !$data['project']) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('project/lihat/' . $id_session);
        }
    
        // Kirim data ke view
        $this->load->view('payment/view_kwitansi', $data);
    }

}
