<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_vendor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vendor_model');
        $this->load->model('project_model');
        $this->load->model('Naskah_model');
    }

    public function lihat($id_session) {
        $data['vendor'] = $this->Vendor_model->get_vendor_by_id($id_session);
        $data['project'] = $this->project_model->get_project_by_session($id_session);
    
        $this->load->view('project/lihat', $data);
    }

    public function create($id_session) {
        $data['project'] = $this->project_model->get_project_by_session($id_session);
        $data['vendor'] = $this->Vendor_model->get_vendor_by_id($id_session);
    
        $this->load->view('vendor/create', $data);
    }    

    public function store() {
        $data = array(
            'id_session' => $this->input->post('id_session'),
            'type' => $this->input->post('type'),
            'social_media' => $this->input->post('social_media'),
            'contact_name' => $this->input->post('contact_name'),
            'phone' => $this->input->post('phone'),
            'detail' => $this->input->post('detail'),
            'photo1' => $this->upload_photo('photo1'),
            'photo2' => $this->upload_photo('photo2'),
            'photo3' => $this->upload_photo('photo3'),
            'photo4' => $this->upload_photo('photo4'),
            'photo5' => $this->upload_photo('photo5')
        );
    
        $this->Vendor_model->insert_vendor($data);
        redirect('project/lihat/' . $this->input->post('id_session'));
    }

    public function edit($id_session) {
        $data['vendor'] = $this->Vendor_model->get_vendor_by_id($id_session);
        $data['project'] = $this->project_model->get_project_by_session($id_session);
    
        $this->load->view('vendor/edit', $data);
    }
    
    public function update($id) {
        $data = array(
            'type' => $this->input->post('type'),
            'social_media' => $this->input->post('social_media'),
            'contact_name' => $this->input->post('contact_name'),
            'phone' => $this->input->post('phone'),
            'detail' => $this->input->post('detail'),
            'photo1' => $this->upload_photo('photo1'),
            'photo2' => $this->upload_photo('photo2'),
            'photo3' => $this->upload_photo('photo3'),
            'photo4' => $this->upload_photo('photo4'),
            'photo5' => $this->upload_photo('photo5')
        );
    
        $this->Vendor_model->update_vendor($id, $data);
        redirect('project/lihat/' . $this->input->post('id_session'));
    }
    
    private function upload_photo($field_name) {
        if (!empty($_FILES[$field_name]['name'])) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['file_name'] = time() . '_' . $_FILES[$field_name]['name'];
    
            $this->load->library('upload', $config);
    
            if ($this->upload->do_upload($field_name)) {
                return $this->upload->data('file_name');
            }
        }
        return null;
    }

    public function view($id_session) {
        $data['client'] = $this->Naskah_model->get_by_session($id_session);
        $data['vendor'] = $this->Naskah_model->get_vendors_by_session($id_session);

        if (!$data['client']) {
            show_404();
        }

        $this->load->view('naskah/list_vendor', $data);
    }

    public function generate_pdf($id_session) {
        $this->load->library('pdf');
    
        // Ambil data client
        $data['client'] = $this->Naskah_model->get_by_session($id_session);
        $data['vendor'] = $this->Naskah_model->get_vendors_by_session($id_session);
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
}
