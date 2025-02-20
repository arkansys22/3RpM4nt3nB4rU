<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class crud_projects extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Projects_model');
        $this->load->helper('url');
    }

    public function index() {
        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='4' OR $this->session->level=='5'){
                cek_session_akses('home',$this->session->id_session);
        $data['projects'] = $this->Projects_model->get_all_projects(); // Ubah pemanggilan model
        $this->load->view('projects/index', $data);
        }else{
                redirect(base_url());
                }
    }

    public function create() {
        $this->load->view('projects/create');
    }

    public function store() {
        $id_session = hash('sha256', bin2hex(random_bytes(16)));

        $date_create = date('Y-m-d H:i:s');  // tanggal dan waktu
    
        $data = array(
            'id_session'    => $id_session,
            'project_name'  => $this->input->post('project_name'),
            'status'        => 'create',
            'create_by'     => $this->session->id_session,
            'event_date'    => $this->input->post('event_date'),
            'location'      => $this->input->post('location'),
            'client_name'   => $this->input->post('client_name'),
            'value'         => $this->input->post('value'),
            'detail'        => $this->input->post('detail'),
            'religion'      => $this->input->post('religion'),
            'create_date'   => $date_create
        );
    
        // Insert ke tabel projects
        $this->Projects_model->insert_projects($data);

         // Ambil id yang baru disimpan di tabel projects
        $project_id = $this->db->insert_id();  // Mendapatkan id auto-increment
    
        // Ambil nama hari dari create_date
        $create_day = date('l', strtotime($date_create));
    
        // Data untuk tabel clients
        $client_data = array(
            'id'           => $project_id,  // Gunakan id yang sama di clients
            'id_session'   => $id_session,
            'client_name'  => $this->input->post('client_name'),
            'wedding_date' => $this->input->post('event_date'),
            'created_at'   => $date_create,
            'create_day'   => $create_day,
            'location'     => $this->input->post('location'),
            'create_by'    => $this->session->id_session,
            'status'       => 'create'
        );
    
        // Insert ke tabel clients
        $this->db->insert('clients', $client_data);
    
        $this->session->set_flashdata('Success', 'Projects berhasil dibuat');
        redirect('projects');
    }

    public function edit($id_session) {
        $data['projects'] = $this->Projects_model->get_projects_by_session($id_session);
        $this->load->view('projects/edit', $data);
    }

    public function update($id_session) {
        $data = array(
            'project_name'  => $this->input->post('project_name'),
            'client_name'   => $this->input->post('client_name'),
            'event_date'    => $this->input->post('event_date'),
            'value'         => $this->input->post('value'),
            'detail'        => $this->input->post('detail'),
            'religion'      => $this->input->post('religion'),
            'location'      => $this->input->post('location'),
        );
    
        $this->Projects_model->update_projects($id_session, $data);
    
        // Update juga di tabel clients
        $client_data = array(
            'client_name' => $this->input->post('project_name'),
            'wedding_date' => $this->input->post('event_date'),
            'location' => $this->input->post('location'),
        );
    
        $this->db->where('id_session', $id_session);
        $this->db->update('clients', $client_data);
    
        $this->session->set_flashdata('Success', 'Projects berhasil diupdate');
        redirect('projects');
    }

    public function delete($id_session) {
        $data = ['status' => 'delete'];
        $this->Projects_model->update_projects($id_session, $data);
    
        // Update juga di tabel clients
        $this->db->where('id_session', $id_session);
        $this->db->update('clients', $data);
    
        $this->session->set_flashdata('Success', 'Projects berhasil dihapus');
        redirect('projects');
    }

    public function recycle_bin() {
        $data['projects'] = $this->Projects_model->get_deleted_projects();  // Get projects with status 'delete'
        $this->load->view('projects/recycle_bin', $data);
    }    

    public function restore($id_session) {
        $data = ['status' => 'create'];
        $this->Projects_model->update_projects($id_session, $data);
    
        // Update juga di tabel clients
        $this->db->where('id_session', $id_session);
        $this->db->update('clients', $data);
    
        $this->session->set_flashdata('Success', 'Projects berhasil dipulihkan');
        redirect('projects/recycle_bin');
    }

    public function permanent_delete($id_session) {
        $this->Projects_model->delete_projects_permanent($id_session);
    
        // Hapus juga di tabel clients
        $this->db->where('id_session', $id_session);
        $this->db->delete('clients');
    
        $this->session->set_flashdata('Success', 'Projects berhasil dihapus permanen');
        redirect('projects/recycle_bin');
    }
    
}
