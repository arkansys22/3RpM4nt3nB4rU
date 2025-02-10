<?php
class Crud_clients extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Clients_model'); // Ubah dari Client_model ke Clients_model
        $this->load->helper('url');
    }

    public function index() {
        $data['clients'] = $this->Clients_model->get_all_clients(); // Ubah pemanggilan model
        $this->load->view('clients/index', $data);
    }

    public function create() {
        $this->load->view('clients/create');
    }

    public function store() {
        $data = [
            'id_session' => sha1(uniqid()),
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'status' => 'create',
            'phone' => $this->input->post('phone'),
            'wedding_date' => $this->input->post('wedding_date'),
        ];
        $this->Clients_model->insert_client($data); // Ubah pemanggilan model
        redirect('clients');
    }

    public function edit($id_session) {
        $data['client'] = $this->Clients_model->get_client_by_session($id_session); // Ubah pemanggilan model
        $this->load->view('clients/edit', $data);
    }

    public function update($id_session) {
        $data = [
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'wedding_date' => $this->input->post('wedding_date'),
        ];
        $this->Clients_model->update_client($id_session, $data); // Ubah pemanggilan model
        redirect('clients');
    }

    public function delete($id_session) {

        $data = [
            'status' => 'delete',           
        ];
        $this->Clients_model->delete_client($id_session, $data); // Ubah pemanggilan model
        redirect('clients');
    }

    public function recycle_bin() {
        $data['clients'] = $this->Clients_model->get_deleted_clients(); // Mengambil data dengan status delete
        $this->load->view('clients/recycle_bin', $data);
    }
    
    public function restore($id_session) {
        $data = ['status' => 'create']; // Kembalikan status menjadi 'create'
        $this->Clients_model->update_client($id_session, $data);
        $this->session->set_flashdata('success', 'Client berhasil dipulihkan');
        redirect('clients/recycle_bin');
    }
    
    public function permanent_delete($id_session) {
        $this->Clients_model->delete_client_permanent($id_session);
        $this->session->set_flashdata('success', 'Client berhasil dihapus permanen');
        redirect('clients/recycle_bin');
    }
    
}
