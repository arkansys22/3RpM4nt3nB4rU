<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_projek extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Projek_model');
        $this->load->helper('url');
    }

    public function index() {
        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='4' OR $this->session->level=='5'){
                cek_session_akses('home',$this->session->id_session);
        $data['projek'] = $this->Projek_model->get_all_projek(); // Ubah pemanggilan model
        $this->load->view('projek/index', $data);
        }else{
                redirect(base_url());
                }
    }

    public function create() {
        $this->load->view('projek/create');
    }

    public function store() {
        $data = array(
            'id_session' => sha1(uniqid()),
            'nama_projek' => $this->input->post('nama_projek'),
            'status' => 'create',
            'create_by' => $this->session->id_session,
            'wedding_date' => $this->input->post('wedding_date'),
            'location' => $this->input->post('location'),
        );
        $this->Projek_model->insert_projek($data);
        redirect('projek');
    }

    public function edit($id_session) {
        $data['projek'] = $this->Projek_model->get_projek_by_session($id_session);
        $this->load->view('projek/edit', $data);
    }

    public function update($id_session) {
        $data = array(
            'nama_projek' => $this->input->post('nama_projek'),
            'wedding_date' => $this->input->post('wedding_date'),
            'location' => $this->input->post('location'),
        );

        $this->Projek_model->update_projek($id_session, $data);
        redirect('projek');
    }

    public function delete($id_session) {
        $data = ['status' => 'delete'];
        $this->Projek_model->update_projek($id_session, $data);
        redirect('projek');
    }

    public function recycle_bin() {
        $data['projek'] = $this->Projek_model->get_deleted_projek();  // Get projects with status 'delete'
        $this->load->view('projek/recycle_bin', $data);
    }    

    public function restore($id_session) {
        $data = ['status' => 'create'];
        $this->Projek_model->update_projek($id_session, $data);
        $this->session->set_flashdata('success', 'Projek berhasil dipulihkan');
        redirect('projek/recycle_bin');
    }

    public function permanent_delete($id_session) {
        $this->Projek_model->delete_projek_permanent($id_session);
        $this->session->set_flashdata('success', 'Projek berhasil dihapus permanen');
        redirect('projek/recycle_bin');
    }
}
