<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_vendor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vendor_model');
        $this->load->model('project_model');
        $this->load->model('Naskah_model');
        $this->load->library('upload');
    }

    public function create($id_session) {
        $data['project'] = $this->project_model->get_project_by_session($id_session);
        $data['vendors'] = $this->Vendor_model->get_vendor_by_id($id_session);
    
        $this->load->view('vendor/create', $data);
    }    

    public function store() {
        $id_session = $this->input->post('id_session'); // Ambil ID session dari form
        $vendor_id = hash('sha256', bin2hex(random_bytes(16)));
        $data = [
            'id_session'    => $id_session,
            'vendor_id'     => $vendor_id,
            'vendor'        => $this->input->post('vendor'),
            'type'          => $this->input->post('type'),
            'social_media'  => $this->input->post('social_media'),
            'contact_name'  => $this->input->post('contact_name'),
            'phone'         => $this->input->post('phone'),
            'detail'        => $this->input->post('detail'),
        ];
    
        // Konfigurasi upload
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 10024; // 2MB
        $config['encrypt_name']  = TRUE;
    
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }
    
        // Upload foto 1-5
        for ($i = 1; $i <= 5; $i++) {
            $input_name = 'photo' . $i;
            if (!empty($_FILES[$input_name]['name'])) {
                $_FILES['file']['name']     = $_FILES[$input_name]['name'];
                $_FILES['file']['type']     = $_FILES[$input_name]['type'];
                $_FILES['file']['tmp_name'] = $_FILES[$input_name]['tmp_name'];
                $_FILES['file']['error']    = $_FILES[$input_name]['error'];
                $_FILES['file']['size']     = $_FILES[$input_name]['size'];
    
                $this->upload->initialize($config);
    
                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    $data['photo' . $i] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('message', '<p style="color:red;">Gagal upload ' . $input_name . ': ' . $this->upload->display_errors() . '</p>');
                    redirect('crud_vendor/create');
                }
            }
        }
    
        // Simpan ke database
        $this->Vendor_model->insert_vendor($data);
        $this->session->set_flashdata('message', '<p style="color:green;">Vendor berhasil disimpan!</p>');
    
        // Redirect ke halaman project/lihat/{id_session}
        redirect('project/lihat/' . $id_session);
    }
    

    public function edit($id_session, $vendor_id) {
        $data['vendor'] = $this->Vendor_model->get_vendor_by_id_and_vendor_id($id_session, $vendor_id);
        $data['project'] = $this->project_model->get_project_by_session($id_session);
    
        $this->load->view('vendor/edit', $data);
    }
    
    public function update($id_session, $vendor_id) {
        $existing_vendor = $this->Vendor_model->get_vendor_by_id_and_vendor_id($id_session, $vendor_id);

        $data = array(
            'vendor' => $this->input->post('vendor'),
            'type' => $this->input->post('type'),
            'social_media' => $this->input->post('social_media'),
            'contact_name' => $this->input->post('contact_name'),
            'phone' => $this->input->post('phone'),
            'detail' => $this->input->post('detail'),
            'photo1' => $this->upload_photo('photo1') ?: $existing_vendor->photo1,
            'photo2' => $this->upload_photo('photo2') ?: $existing_vendor->photo2,
            'photo3' => $this->upload_photo('photo3') ?: $existing_vendor->photo3,
            'photo4' => $this->upload_photo('photo4') ?: $existing_vendor->photo4,
            'photo5' => $this->upload_photo('photo5') ?: $existing_vendor->photo5
        );

        $this->Vendor_model->update_vendor($id_session, $vendor_id, $data);
        redirect('project/lihat/' . $id_session);
    }

    public function delete($id_session, $vendor_id) {
        // Cek apakah vendor dengan id_session dan vendor_id tersebut ada
        $vendor = $this->db->get_where('vendor', ['id_session' => $id_session, 'vendor_id' => $vendor_id])->row();

        if (!$vendor) {
            $this->session->set_flashdata('error', 'Vendor tidak ditemukan.');
            redirect($_SERVER['HTTP_REFERER']); // Kembali ke halaman sebelumnya
        }

        // Hapus vendor
        if ($this->Vendor_model->delete_vendor($id_session, $vendor_id)) {
            $this->session->set_flashdata('success', 'Vendor berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus vendor.');
        }
        redirect('project/lihat/' . $id_session);
    }

    public function view($id_session) {
        $data['client'] = $this->Naskah_model->get_by_session($id_session);
        $data['vendors'] = $this->Vendor_model->get_vendor_by_id($id_session);

        if (!$data['client']) {
            show_404();
        }

        $this->load->view('naskah/list_vendor', $data);
    }

    public function generate_pdf($id_session) {
        $this->load->library('pdf');
    
        // Ambil data client
        $data['client'] = $this->Naskah_model->get_by_session($id_session);
        $data['vendors'] = $this->Vendor_model->get_vendor_by_id($id_session);
        if (!$data['client']) {
            show_404();
        }
    
        // Ambil client_name sebagai nama file, jika tidak ada gunakan default
        $client_name = $data['client']->client_name ? $data['client']->client_name : 'List_Vendor';
        
        // Format nama file sesuai keinginan
        $filename = $client_name . ' List Vendor';
    
        // Generate PDF dengan nama file yang sudah diformat
        $html = $this->load->view('naskah/pdf_list_vendor', $data, true);
        $this->pdf->createPDF_P($html, $filename, true);
    }

    private function upload_photo($input_name) {
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 10024; // 2MB
        $config['encrypt_name']  = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->upload->initialize($config);

        if ($this->upload->do_upload($input_name)) {
            $upload_data = $this->upload->data();
            return $upload_data['file_name'];
        } else {
            return null;
        }
    }
}
