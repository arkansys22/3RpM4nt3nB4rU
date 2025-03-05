<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_vendor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vendor_model');
        $this->load->helper('url');

    }

    public function lihat($id_session) {
        $data['vendor'] = $this->Vendor_model->get_vendor_by_session($id_session);
        $data['project'] = $this->Vendor_model->get_project($id_session);
        
        // Check if vendor data is retrieved
        if (empty($data['vendor'])) {
            // Handle the case where no vendor data is found
            $data['vendor'] = [];
        }
    
        $this->load->view('project/lihat', $data);
    }

    public function create($id_session) {
        $data['project'] = $this->Vendor_model->get_project($id_session);
        $data['vendor'] = $this->Vendor_model->get_vendor_by_session($id_session);    
        $this->load->view('vendor/create', $data);
    }    

public function store($id_session) {

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
                'vendor_3' => $this->input->post('vendor_3'),
                'social_media_3' => $this->input->post('social_media_3'),
                'contact_name_3' => $this->input->post('contact_name_3'),
                'phone_3' => $this->input->post('phone_3'),
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
            redirect('project/lihat/'.$id_session);
        }

        public function edit($id_session) {
            $data['project'] = $this->Vendor_model->get_project($id_session);

            $data['vendor'] = $this->Vendor_model->get_vendor_by_session($id_session);
                        
            if (empty($data['vendor'])) {
                show_404(); // Menampilkan error jika data tidak ditemukan
            }
        
            $this->load->view('vendor/edit', $data);
        }
        

    public function update($id_session) {
        // Ambil data dari form
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
            'vendor_3' => $this->input->post('vendor_3'),
            'social_media_3' => $this->input->post('social_media_3'),
            'contact_name_3' => $this->input->post('contact_name_3'),
            'phone_3' => $this->input->post('phone_3'),
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
    
        // Update vendor data berdasarkan id_session
        $this->Vendor_model->update_vendor($id_session, $data);
        redirect('project/lihat/'.$id_session);
    }
    

}