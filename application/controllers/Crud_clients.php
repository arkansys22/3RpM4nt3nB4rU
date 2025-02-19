<?php
class Crud_clients extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Clients_model'); // Ubah dari Client_model ke Clients_model
        $this->load->helper('url');
    }

    public function index() {
        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='4' OR $this->session->level=='5'){
                cek_session_akses('home',$this->session->id_session);
        $data['clients'] = $this->Clients_model->get_all_clients(); // Ubah pemanggilan model
        $this->load->view('clients/index', $data);
        }else{
                redirect(base_url());
                }
    }

    public function create() {
        $this->load->view('clients/create');
    }

    public function store() {
        $id_session = sha1(uniqid());
        $created_at = date('Y-m-d H:i:s'); // Waktu sekarang
        
        $data = [
            
            'client_name'           => $this->input->post('client_name'),
            'email'                 => $this->input->post('email'),
            'phone'                 => $this->input->post('phone'),
            'create_by'             => $this->session->id_session,
            'status'                => 'create',
            'f_bride_fname'         => $this->input->post('f_bride_fname'),
            'f_bride_cname'         => $this->input->post('f_bride_cname'),
            'f_bride_nchild'        => $this->input->post('f_bride_nchild'),
            'f_bride_hsibling'      => $this->input->post('f_bride_hsibling'),
            'f_bride_fathername'    => $this->input->post('f_bride_fathername'),
            'f_bride_fathercname'   => $this->input->post('f_bride_fathercname'),
            'f_bride_mothername'    => $this->input->post('f_bride_mothername'),
            'f_bride_mothercname'   => $this->input->post('f_bride_mothercname'),
            'f_bride_sibling'       => $this->input->post('f_bride_sibling'),
            'm_bride_fname'         => $this->input->post('m_bride_fname'),
            'm_bride_cname'         => $this->input->post('m_bride_cname'),
            'm_bride_nchild'        => $this->input->post('m_bride_nchild'),
            'm_bride_hsibling'      => $this->input->post('m_bride_hsibling'),
            'm_bride_fathername'    => $this->input->post('m_bride_fathername'),
            'm_bride_fathercname'   => $this->input->post('m_bride_fathercname'),
            'm_bride_mothername'    => $this->input->post('m_bride_mothername'),
            'm_bride_mothercname'   => $this->input->post('m_bride_mothercname'),
            'm_bride_sibling'       => $this->input->post('m_bride_sibling'),
            'mahr'                  => $this->input->post('mahr'),
            'handover'              => $this->input->post('handover'),
            'female_coor'           => $this->input->post('female_coor'),
            'male_coor'             => $this->input->post('male_coor'),
            'f_spokesman'           => $this->input->post('f_spokesman'),
            'm_spokesman'           => $this->input->post('m_spokesman'),
            'wedding_officiant'     => $this->input->post('wedding_officiant'),
            'guardian'              => $this->input->post('guardian'),
            'f_witness'             => $this->input->post('f_witness'),
            'm_witness'             => $this->input->post('m_witness'),
            'qori'                  => $this->input->post('qori'),
            'advice_doa'            => $this->input->post('advice_doa'),
            'clamp'                 => $this->input->post('clamp'),
            'jasmine_carrier'       => $this->input->post('jasmine_carrier'),
            'mahr_carrier'          => $this->input->post('mahr_carrier'),
            'ring_carrier'          => $this->input->post('ring_carrier'),
            'pastor'                => $this->input->post('pastor'),
            'church'                => $this->input->post('church'),
            'prayer'                => $this->input->post('prayer'),
            'wedding_speech'        => $this->input->post('wedding_speech'),
            'wedding_date'          => $this->input->post('wedding_date'),
            'location'              => $this->input->post('location'),
            'created_at'            => $created_at,
            'create_day'            => date('l') // Simpan hari otomatis
        ];

        $this->Clients_model->insert_client($data); // Ubah pemanggilan model

        $client_id = $this->db->insert_id();  // Mendapatkan id auto-increment

        $projects_data = array(
            'id'           => $client_id,  // Gunakan id yang sama di clients
            'id_session'   => $id_session,
            'client_name'  => $this->input->post('client_name'),
            'event_date'   => $this->input->post('wedding_date'),
            'location'     => $this->input->post('location'),
            'create_date'  => $created_at,
            'create_by'    => $this->session->id_session,
            'status'       => 'create'
        );

        // Insert ke tabel projects
        $this->db->insert('projects', $projects_data);
    
        $this->session->set_flashdata('Success', 'Client berhasil dibuat');
        redirect('clients');
    }

    public function edit($id_session) {
        $data['client'] = $this->Clients_model->get_client_by_session($id_session); // Ubah pemanggilan model
        $this->load->view('clients/edit', $data);
    }

    public function update($id_session){
        $data = array(
            'client_name'           => $this->input->post('client_name'),
            'email'                 => $this->input->post('email'),
            'phone'                 => $this->input->post('phone'),
            'f_bride_fname'         => $this->input->post('f_bride_fname'),
            'f_bride_cname'         => $this->input->post('f_bride_cname'),
            'f_bride_nchild'        => $this->input->post('f_bride_nchild'),
            'f_bride_hsibling'      => $this->input->post('f_bride_hsibling'),
            'f_bride_fathername'    => $this->input->post('f_bride_fathername'),
            'f_bride_fathercname'   => $this->input->post('f_bride_fathercname'),
            'f_bride_mothername'    => $this->input->post('f_bride_mothername'),
            'f_bride_mothercname'   => $this->input->post('f_bride_mothercname'),
            'f_bride_sibling'       => $this->input->post('f_bride_sibling'),
            'm_bride_fname'         => $this->input->post('m_bride_fname'),
            'm_bride_cname'         => $this->input->post('m_bride_cname'),
            'm_bride_nchild'        => $this->input->post('m_bride_nchild'),
            'm_bride_hsibling'      => $this->input->post('m_bride_hsibling'),
            'm_bride_fathername'    => $this->input->post('m_bride_fathername'),
            'm_bride_fathercname'   => $this->input->post('m_bride_fathercname'),
            'm_bride_mothername'    => $this->input->post('m_bride_mothername'),
            'm_bride_mothercname'   => $this->input->post('m_bride_mothercname'),
            'm_bride_sibling'       => $this->input->post('m_bride_sibling'),
            'mahr'                  => $this->input->post('mahr'),
            'handover'              => $this->input->post('handover'),
            'female_coor'           => $this->input->post('female_coor'),
            'male_coor'             => $this->input->post('male_coor'),
            'f_spokesman'           => $this->input->post('f_spokesman'),
            'm_spokesman'           => $this->input->post('m_spokesman'),
            'wedding_officiant'     => $this->input->post('wedding_officiant'),
            'guardian'              => $this->input->post('guardian'),
            'f_witness'             => $this->input->post('f_witness'),
            'm_witness'             => $this->input->post('m_witness'),
            'qori'                  => $this->input->post('qori'),
            'advice_doa'            => $this->input->post('advice_doa'),
            'clamp'                 => $this->input->post('clamp'),
            'jasmine_carrier'       => $this->input->post('jasmine_carrier'),
            'mahr_carrier'          => $this->input->post('mahr_carrier'),
            'ring_carrier'          => $this->input->post('ring_carrier'),
            'pastor'                => $this->input->post('pastor'),
            'church'                => $this->input->post('church'),
            'prayer'                => $this->input->post('prayer'),
            'wedding_speech'        => $this->input->post('wedding_speech'),
            'wedding_date'          => $this->input->post('wedding_date'),
            'location'              => $this->input->post('location'),
        );

        $this->Clients_model->update_client($id_session, $data);

        // Update juga di tabel projects
        $projects_data = array(
            'client_name' => $this->input->post('client_name'),
            'event_date' => $this->input->post('wedding_date'),
            'location' => $this->input->post('location'),
        );

            $this->db->where('id_session', $id_session);
            $this->db->update('projects', $projects_data);

            $this->session->set_flashdata('Success', 'Client berhasil diupdate');
            redirect('clients');
    }

    public function delete($id_session) {
        $data = ['status' => 'delete'];
        $this->Clients_model->update_client($id_session, $data);

        // Update juga di tabel projects
        $this->db->where('id_session', $id_session);
        $this->db->update('projects', $data);

        $this->session->set_flashdata('Success', 'Client berhasil dihapus');
        redirect('clients');
    }

    public function recycle_bin() {
        $data['clients'] = $this->Clients_model->get_deleted_clients(); // Mengambil data dengan status delete
        $this->load->view('clients/recycle_bin', $data);
    }
    
    public function restore($id_session) {
        $data = ['status' => 'create']; // Kembalikan status menjadi 'create'
        $this->Clients_model->update_client($id_session, $data);
    
        // Update juga di tabel projects
        $this->db->where('id_session', $id_session);
        $this->db->update('projects', $data);
    
        $this->session->set_flashdata('Success', 'Client berhasil dipulihkan');
        redirect('clients/recycle_bin');
    }
    
    public function permanent_delete($id_session) {
        $this->Clients_model->delete_client_permanent($id_session);
    
        // Hapus juga di tabel projects
        $this->db->where('id_session', $id_session);
        $this->db->delete('projects');
    
        $this->session->set_flashdata('Success', 'Client berhasil dihapus permanen');
        redirect('clients/recycle_bin');
    }
}
