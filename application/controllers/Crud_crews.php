<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_crews extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('crews_model');
    }

    public function index() {
        $data['crews'] = $this->crews_model->get_all_crews();
        $this->load->view('crews/index', $data);
    }

    public function recycle_bin() {
        $data['crews'] = $this->crews_model->get_deleted();
        $this->load->view('crews/recycle_bin', $data);
    }

    public function create() {
        $this->load->view('crews/create');
    }

    public function store() {
        $id_session = hash('sha256', bin2hex(random_bytes(16)));

        $data = [
            'id_session'  => $id_session,
            'crew_name'   => $this->input->post('crew_name'),
            'gender'      => $this->input->post('gender'),
            'religion'    => $this->input->post('religion'),
            'phone'       => $this->input->post('phone'),
            'address'     => $this->input->post('address'),
            'age'         => $this->input->post('age'),
            'joining_date'=> $this->input->post('joining_date'),
            'status'      => 'active'
        ];
        $this->crews_model->insert($data);
        redirect('crews');
    }

    public function edit($id_session) {
        $data['crews'] = $this->crews_model->get_by_id_session($id_session);
        $this->load->view('crews/edit', $data);
    }

    public function update($id_session) {
        $data = [
            'crew_name'   => $this->input->post('crew_name'),
            'gender'      => $this->input->post('gender'),
            'religion'    => $this->input->post('religion'),
            'phone'       => $this->input->post('phone'),
            'address'     => $this->input->post('address'),
            'age'         => $this->input->post('age'),
            'joining_date'=> $this->input->post('joining_date'),
        ];
        $this->crews_model->update($id_session, $data);
        redirect('crews');
    }

    public function soft_delete($id_session) {
        $this->crews_model->soft_delete($id_session);
        redirect('crews');
    }

    public function restore($id_session) {
        $this->crews_model->restore($id_session);
        redirect('crews/recycle_bin');
    }

    public function delete_permanent($id_session) {
        $this->crews_model->delete_permanent($id_session);
        redirect('crews/recycle_bin');
    }
}
?>
