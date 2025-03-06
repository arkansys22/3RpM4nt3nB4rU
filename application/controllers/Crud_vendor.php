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

    public function store(){
    $id_session = $this->input->post('id_session');

    $data = array(
        'id_session' => $id_session,
        'vendor_1' => $this->input->post('vendor_1'),
        'social_media_1' => $this->input->post('social_media_1'),
        'contact_name_1' => $this->input->post('contact_name_1'),
        'phone_1' => $this->input->post('phone_1'),
        'detail_1' => $this->input->post('detail_1'),
        'vendor_2' => $this->input->post('vendor_2'),
        'social_media_2' => $this->input->post('social_media_2'),
        'contact_name_2' => $this->input->post('contact_name_2'),
        'phone_2' => $this->input->post('phone_2'),
        'detail_2' => $this->input->post('detail_2'),
        'vendor_3' => 'Mantenbaru Organizer',
        'social_media_3' => 'Mantenbaru_organizer',
        'contact_name_3' => 'Icha',
        'phone_3' => '0812-1012-6196',
        'detail_3' => $this->input->post('detail_3'),
        'vendor_4' => $this->input->post('vendor_4'),
        'social_media_4' => $this->input->post('social_media_4'),
        'contact_name_4' => $this->input->post('contact_name_4'),
        'phone_4' => $this->input->post('phone_4'),
        'detail_4' => $this->input->post('detail_4'),
        'vendor_5' => $this->input->post('vendor_5'),
        'social_media_5' => $this->input->post('social_media_5'),
        'contact_name_5' => $this->input->post('contact_name_5'),
        'phone_5' => $this->input->post('phone_5'),
        'detail_5' => $this->input->post('detail_5'),
        'vendor_6' => $this->input->post('vendor_6'),
        'social_media_6' => $this->input->post('social_media_6'),
        'contact_name_6' => $this->input->post('contact_name_6'),
        'phone_6' => $this->input->post('phone_6'),
        'detail_6' => $this->input->post('detail_6'),
        'vendor_7' => $this->input->post('vendor_7'),
        'social_media_7' => $this->input->post('social_media_7'),
        'contact_name_7' => $this->input->post('contact_name_7'),
        'phone_7' => $this->input->post('phone_7'),
        'detail_7' => $this->input->post('detail_7'),
        'vendor_8' => $this->input->post('vendor_8'),
        'social_media_8' => $this->input->post('social_media_8'),
        'contact_name_8' => $this->input->post('contact_name_8'),
        'phone_8' => $this->input->post('phone_8'),
        'detail_8' => $this->input->post('detail_8'),
        'vendor_9' => $this->input->post('vendor_9'),
        'social_media_9' => $this->input->post('social_media_9'),
        'contact_name_9' => $this->input->post('contact_name_9'),
        'phone_9' => $this->input->post('phone_9'),
        'detail_9' => $this->input->post('detail_9'),
    );

    $this->Vendor_model->insert_vendor($data);

    redirect('project/lihat/' . $id_session);
}

public function edit($id_session) {
    $data['vendor'] = $this->Vendor_model->get_vendor_by_id($id_session);
    $data['project'] = $this->project_model->get_project_by_session($id_session);

    $this->load->view('vendor/edit', $data);
}

public function update($id_session){

    $data = array(
        'vendor_1' => $this->input->post('vendor_1'),
        'social_media_1' => $this->input->post('social_media_1'),
        'contact_name_1' => $this->input->post('contact_name_1'),
        'phone_1' => $this->input->post('phone_1'),
        'detail_1' => $this->input->post('detail_1'),
        'vendor_2' => $this->input->post('vendor_2'),
        'social_media_2' => $this->input->post('social_media_2'),
        'contact_name_2' => $this->input->post('contact_name_2'),
        'phone_2' => $this->input->post('phone_2'),
        'detail_2' => $this->input->post('detail_2'),
        'vendor_3' => 'Mantenbaru Organizer',
        'social_media_3' => 'Mantenbaru_organizer',
        'contact_name_3' => 'Icha',
        'phone_3' => '0812-1012-6196',
        'detail_3' => $this->input->post('detail_3'),
        'vendor_4' => $this->input->post('vendor_4'),
        'social_media_4' => $this->input->post('social_media_4'),
        'contact_name_4' => $this->input->post('contact_name_4'),
        'phone_4' => $this->input->post('phone_4'),
        'detail_4' => $this->input->post('detail_4'),
        'vendor_5' => $this->input->post('vendor_5'),
        'social_media_5' => $this->input->post('social_media_5'),
        'contact_name_5' => $this->input->post('contact_name_5'),
        'phone_5' => $this->input->post('phone_5'),
        'detail_5' => $this->input->post('detail_5'),
        'vendor_6' => $this->input->post('vendor_6'),
        'social_media_6' => $this->input->post('social_media_6'),
        'contact_name_6' => $this->input->post('contact_name_6'),
        'phone_6' => $this->input->post('phone_6'),
        'detail_6' => $this->input->post('detail_6'),
        'vendor_7' => $this->input->post('vendor_7'),
        'social_media_7' => $this->input->post('social_media_7'),
        'contact_name_7' => $this->input->post('contact_name_7'),
        'phone_7' => $this->input->post('phone_7'),
        'detail_7' => $this->input->post('detail_7'),
        'vendor_8' => $this->input->post('vendor_8'),
        'social_media_8' => $this->input->post('social_media_8'),
        'contact_name_8' => $this->input->post('contact_name_8'),
        'phone_8' => $this->input->post('phone_8'),
        'detail_8' => $this->input->post('detail_8'),
        'vendor_9' => $this->input->post('vendor_9'),
        'social_media_9' => $this->input->post('social_media_9'),
        'contact_name_9' => $this->input->post('contact_name_9'),
        'phone_9' => $this->input->post('phone_9'),
        'detail_9' => $this->input->post('detail_9'),
    );

    $this->Vendor_model->update_vendor($id_session, $data);

        redirect('project/lihat/' . $id_session);
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
