<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_projek extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Projek_model');
        $this->load->helper('url');
    }

    public function index() {
        $data['projek'] = $this->Projek_model->get_all_projek();
        $this->load->view('projek/index', $data);
    }

    public function create() {
        $this->load->view('projek/create');
    }

    public function store() {
        $data = array(
            'id_session' => sha1(uniqid()),
            'nama_projek' => $this->input->post('nama_projek'),
            'status' => 'create',
            'author' => $this->input->post('author'),
            'author_last_update' => $this->input->post('author')
        );

        $this->Projek_model->insert_projek($data);
        redirect('Crud_projek');
    }

    public function edit($id) {
        $data['projek'] = $this->Projek_model->get_projek_by_id($id);
        $this->load->view('projek/edit', $data);
    }

    public function update($id) {
        $data = array(
            'nama_projek' => $this->input->post('nama_projek'),
            'author_last_update' => $this->input->post('author')
        );

        $this->Projek_model->update_projek($id, $data);
        redirect('projek');
    }

    public function delete($id) {
        $data = ['status' => 'delete'];
        $this->Projek_model->update_projek($id, $data);
        redirect('projek');
    }

    public function recycle_bin() {
        $data['projek'] = $this->Projek_model->get_deleted_projek();  // Get projects with status 'delete'
        $this->load->view('projek/recycle_bin', $data);
    }    

    public function restore($id) {
        $data = ['status' => 'create'];
        $this->Projek_model->update_projek($id, $data);
        $this->session->set_flashdata('success', 'Projek berhasil dipulihkan');
        redirect('Crud_projek/recycle_bin');
    }

    public function permanent_delete($id) {
        $this->Projek_model->delete_projek_permanent($id);
        $this->session->set_flashdata('success', 'Projek berhasil dihapus permanen');
        redirect('Crud_projek/recycle_bin');
    }
}
