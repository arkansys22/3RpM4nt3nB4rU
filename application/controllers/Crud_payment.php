<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_payment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Payment_model');
        $this->load->helper('url');
    }

    public function lihat($id_session) {
        $data['payment'] = $this->Payment_model->get_payment_by_session($id_session);
        $data['project'] = $this->Payment_model->get_project($id_session);
    
        $this->load->view('project/lihat', $data);
    }

    public function create($id_session, $invoice_number = 1) {
        $data['project'] = $this->Payment_model->get_project($id_session);
        $data['invoice_number'] = $invoice_number;
        $data['payment'] = $this->Payment_model->get_payment_by_invoice($id_session, $invoice_number);
        $due_date = $this->input->post('due_date_' . $invoice_number);
    
        $this->load->view('payment/create', $data);
    }    

    public function store($id_session, $invoice_number) {

    $details = $this->input->post('details');
    $due_date = $this->input->post('due_date_' . $invoice_number);

    $details_json = json_encode($details);

    $formatted_due_date = date('Y-m-d', strtotime($due_date));

        $data = [
            'id_session' => $id_session,
            'invoice_' . $invoice_number => $this->input->post('invoice_' . $invoice_number),
            'kwitansi_' . $invoice_number => $this->input->post('kwitansi_' . $invoice_number),
            'amount_' . $invoice_number => str_replace('.', '', $this->input->post('amount_' . $invoice_number)),
            'dp_' . $invoice_number => str_replace('.', '', $this->input->post('dp_' . $invoice_number)),
            'date_' . $invoice_number => $this->input->post('date_' . $invoice_number),
            'details_' . $invoice_number => $details_json,
            'due_date_' . $invoice_number => $formatted_due_date,
        ];
    
        // Jika data sudah ada, lakukan update, jika belum, lakukan insert
        if ($this->Payment_model->get_payment_by_session($id_session)) {
            $this->Payment_model->update_payment($id_session, $invoice_number, $data);
        } else {
            $this->Payment_model->insert_payment($data);
        }
    
        // Redirect kembali ke halaman project setelah penyimpanan
        redirect('project/lihat/' . $id_session);
    }
    

    // Menampilkan form edit untuk invoice/kwitansi tertentu
    public function edit($id_session, $invoice_number) {
        // Mengambil data pembayaran berdasarkan id_session dan invoice_number
        $data['payment'] = $this->Payment_model->get_payment_by_invoice($id_session, $invoice_number);
        $data['project'] = $this->Payment_model->get_project($id_session);
        $data['invoice_number'] = $invoice_number;
    
        // Load view untuk mengedit pembayaran
        $this->load->view('payment/edit', $data);
    }
    

    // Menyimpan perubahan untuk edit
    public function update($id_session, $invoice_number) {
        $details = $this->input->post('details');
        $due_date = $this->input->post('due_date_' . $invoice_number); // Ambil tanggal jatuh tempo dari form

        $details_json = json_encode($details);

        $data = [
            'invoice_' . $invoice_number => $this->input->post('invoice_' . $invoice_number),
            'kwitansi_' . $invoice_number => $this->input->post('kwitansi_' . $invoice_number),
            'amount_' . $invoice_number => str_replace('.', '', $this->input->post('amount_' . $invoice_number)),
            'dp_' . $invoice_number => str_replace('.', '', $this->input->post('dp_' . $invoice_number)),
            'date_' . $invoice_number => $this->input->post('date_' . $invoice_number), // Mengupdate tanggal
            'details_' . $invoice_number => $details_json,
            'due_date_' . $invoice_number => $due_date,
        ];

        // Update data pembayaran
        $this->db->where('id_session', $id_session);
        $this->db->update('payment', $data);

        // Redirect ke halaman lihat untuk menampilkan data
        redirect('project/lihat/' . $id_session);
    }


    public function delete($id_session, $invoice_number) {
        // Panggil model untuk menghapus invoice yang dipilih berdasarkan id_session dan invoice_number
        $this->Payment_model->delete_invoice($id_session, $invoice_number);
    
        // Redirect kembali ke halaman lihat project setelah penghapusan
        redirect('project/lihat/' . $id_session);
    }
    
    public function view_invoice($id_session, $invoice_number) {
        // Ambil data pembayaran berdasarkan id_session dan invoice_number
        $data['payment'] = $this->Payment_model->get_payment_by_invoice($id_session, $invoice_number);
    
        // Ambil data project berdasarkan id_session
        $data['project'] = $this->Payment_model->get_project($id_session); 
    
        $data['invoice_number'] = $invoice_number;
        // Kirim data ke view
        $this->load->view('payment/view_invoice', $data);
    }
    
    public function view_kwitansi($id_session, $invoice_number) {
        // Ambil data pembayaran berdasarkan id_session dan invoice_number
        $data['payment'] = $this->Payment_model->get_payment_by_invoice($id_session, $invoice_number);
    
        // Ambil data project berdasarkan id_session
        $data['project'] = $this->Payment_model->get_project($id_session); 
    
        $data['invoice_number'] = $invoice_number;
        // Kirim data ke view
        $this->load->view('payment/view_kwitansi', $data);
    }

}
