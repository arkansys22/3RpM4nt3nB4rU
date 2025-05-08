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
        $data['partners'] = $this->db->get('partner')->result(); // Fetch list of partners

        $this->load->view('vendor/create', $data);
    }    

    public function store() {

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

        $id_session = $this->input->post('id_session');
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
            'photo1'        => $this->upload_photo('photo1'),
            'photo2'        => $this->upload_photo('photo2'),
            'photo3'        => $this->upload_photo('photo3'),
            'photo4'        => $this->upload_photo('photo4'),
            'photo5'        => $this->upload_photo('photo5'),
        ];

        if ($this->input->post('vendor_status') === 'partner') {
            $partner = $this->db->get_where('partner', ['id' => $this->input->post('partner_id')])->row();
            if ($partner) {
                $data['vendor_id'] = $partner->id_session;
                $data['vendor'] = $partner->partner_name;
                $data['type'] = $partner->type;
                $data['social_media'] = $partner->social_media;
                $data['contact_name'] = $partner->contact_name;
                $data['phone'] = $partner->phone;
                $data['photo1'] = $partner->logo;
                $data['detail'] = $this->input->post('partner_detail');
                $data['photo2'] = $this->upload_photo('partner_photo2');
                $data['photo3'] = $this->upload_photo('partner_photo3');
                $data['photo4'] = $this->upload_photo('partner_photo4');
                $data['photo5'] = $this->upload_photo('partner_photo5');
            }
        }

        $this->Vendor_model->insert_vendor($data);

        $type = $this->input->post('type');

        if ($this->input->post('vendor_status') === 'partner') {
            $partner = $this->db->get_where('partner', ['id' => $this->input->post('partner_id')])->row();
            if ($partner) {
            $type = $partner->type; // Ambil type dari tabel partner
            }
        }
        $status = 'Tambah Vendor ' . $type;
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = [
            'log_activity_user_id' => $this->session->id_session,
            'log_activity_modul' => 'vendor/create',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform' => $agent,
            'log_activity_ip' => $ip_with_location,
        ];

        $this->project_model->insert_log_activity($data_log);

        $this->session->set_flashdata('message', '<p style="color:green;">Vendor berhasil disimpan!</p>');

        redirect('project/lihat/' . $id_session);
    }

    public function edit($id_session, $vendor_id) {
        $data['vendors'] = $this->Vendor_model->get_vendor_by_id_and_vendor_id($id_session, $vendor_id);
        $data['project'] = $this->project_model->get_project_by_session($id_session);
        $data['partners'] = $this->db->get('partner')->result(); // Fetch list of partners

        // Check if vendor_id exists in the partner table
        $data['is_partner'] = $this->db->where('id_session', $vendor_id)->count_all_results('partner') > 0;

        $this->load->view('vendor/edit', $data);
    }
    
    public function update($id_session, $vendor_id) {

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

        $existing_vendor = $this->Vendor_model->get_vendor_by_id_and_vendor_id($id_session, $vendor_id);

        $old_type = $existing_vendor->type; // Ambil type sebelumnya

        $data = [];

        $partner_match = $this->db->get_where('partner', ['id_session' => $vendor_id])->row();
        if ($partner_match) {
            // Data dari partner
            $data['vendor_id'] = $partner_match->id_session;
            $data['vendor'] = $partner_match->partner_name;
            $data['type'] = $partner_match->type;
            $data['social_media'] = $partner_match->social_media;
            $data['contact_name'] = $partner_match->contact_name;
            $data['phone'] = $partner_match->phone;
            $data['photo1'] = $partner_match->logo ?: $existing_vendor->photo1;
        
            // Editable fields dari form
            $data['detail'] = $this->input->post('partner_detail') ?: $existing_vendor->detail;
            $data['photo2'] = $this->upload_photo('partner_photo2') ?: $existing_vendor->photo2;
            $data['photo3'] = $this->upload_photo('partner_photo3') ?: $existing_vendor->photo3;
            $data['photo4'] = $this->upload_photo('partner_photo4') ?: $existing_vendor->photo4;
            $data['photo5'] = $this->upload_photo('partner_photo5') ?: $existing_vendor->photo5;
        } else {
            // Non-partner logic tetap sama
            $data['vendor'] = $this->input->post('vendor');
            $data['type'] = $this->input->post('type');
            $data['social_media'] = $this->input->post('social_media');
            $data['contact_name'] = $this->input->post('contact_name');
            $data['phone'] = $this->input->post('phone');
            $data['detail'] = $this->input->post('detail') ?: $existing_vendor->detail;
            $data['photo1'] = $this->upload_photo('photo1') ?: $existing_vendor->photo1;
            $data['photo2'] = $this->upload_photo('photo2') ?: $existing_vendor->photo2;
            $data['photo3'] = $this->upload_photo('photo3') ?: $existing_vendor->photo3;
            $data['photo4'] = $this->upload_photo('photo4') ?: $existing_vendor->photo4;
            $data['photo5'] = $this->upload_photo('photo5') ?: $existing_vendor->photo5;
        }

        $this->Vendor_model->update_vendor($id_session, $vendor_id, $data);

        $new_type = $this->input->post('type'); // Ambil type baru
        if ($old_type === $new_type) {
            $status = 'Update Vendor ' . $old_type;
        } else {
            $status = 'Update Vendor dari ' . $old_type . ' ke ' . $new_type;
        }
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = [
            'log_activity_user_id' => $this->session->id_session,
            'log_activity_modul' => 'vendor/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform' => $agent,
            'log_activity_ip' => $ip_with_location
        ];

        $this->project_model->insert_log_activity($data_log);

        redirect('project/lihat/' . $id_session);
    }

    public function delete($id_session, $vendor_id) {

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

        // Cek apakah vendor dengan id_session dan vendor_id tersebut ada
        $vendor = $this->db->get_where('vendor', ['id_session' => $id_session, 'vendor_id' => $vendor_id])->row();

        if (!$vendor) {
            $this->session->set_flashdata('error', 'Vendor tidak ditemukan.');
            redirect($_SERVER['HTTP_REFERER']); // Kembali ke halaman sebelumnya
        }

        // Ambil type vendor sebelum dihapus
        $type = $vendor->type;

        // Hapus vendor
        if ($this->Vendor_model->delete_vendor($id_session, $vendor_id)) {
            $this->session->set_flashdata('success', 'Vendor berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus vendor.');
        }

        $status = 'Hapus Vendor '.$type;
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'vendor/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->project_model->insert_log_activity($data_log);

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
        $config['max_size']      = 3072; // 3MB
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
