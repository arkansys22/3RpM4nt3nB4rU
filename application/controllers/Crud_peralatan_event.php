<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Kelola template global "List Peralatan Event" -- dikelompokkan per
// kategori (mis. "WO", "Fotobooth"), lalu disalin ke tiap project (lihat
// Crud_client_peralatan_event).
class Crud_peralatan_event extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Peralatan_event_model');
        $this->load->helper(['url', 'form']);
    }

    private function cek_akses()
    {
        if (!in_array($this->session->level, ['1', '2', '4'])) {
            redirect(base_url('panel'));
            exit;
        }
    }

    public function index()
    {
        $this->cek_akses();
        $data['grouped'] = $this->Peralatan_event_model->get_template_grouped();
        $this->load->view('backend/v_peralatan_event', $data);
    }

    // ================= Kategori =================

    public function kategori()
    {
        $this->cek_akses();
        $data['kategori_list'] = $this->Peralatan_event_model->get_kategori_all();
        $this->load->view('backend/v_peralatan_event_kategori', $data);
    }

    public function kategori_store()
    {
        $this->cek_akses();

        $nama = trim($this->input->post('nama_kategori'));
        if ($nama === '') {
            $this->session->set_flashdata('error', 'Nama kategori wajib diisi');
            redirect('peralatan-event/kategori');
            return;
        }

        $this->Peralatan_event_model->insert_kategori([
            'id_session' => hash('sha256', bin2hex(random_bytes(16))),
            'nama_kategori' => $nama,
            'urutan' => $this->Peralatan_event_model->get_kategori_next_urutan(),
            'created_by' => $this->session->id_session,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('Success', 'Kategori berhasil ditambahkan');
        redirect('peralatan-event/kategori');
    }

    public function kategori_update($id_session)
    {
        $this->cek_akses();

        $nama = trim($this->input->post('nama_kategori'));
        if ($nama === '') {
            $this->session->set_flashdata('error', 'Nama kategori wajib diisi');
            redirect('peralatan-event/kategori');
            return;
        }

        $this->Peralatan_event_model->update_kategori($id_session, ['nama_kategori' => $nama]);

        $this->session->set_flashdata('Success', 'Kategori berhasil diperbarui');
        redirect('peralatan-event/kategori');
    }

    public function kategori_delete($id_session)
    {
        $this->cek_akses();
        $this->Peralatan_event_model->delete_kategori($id_session);
        $this->session->set_flashdata('Success', 'Kategori beserta seluruh itemnya (termasuk yang sudah disalin ke project) berhasil dihapus');
        redirect('peralatan-event/kategori');
    }

    public function kategori_move_up($id_session)
    {
        $this->cek_akses();
        $this->Peralatan_event_model->swap_kategori_urutan($id_session, 'up');
        redirect('peralatan-event/kategori');
    }

    public function kategori_move_down($id_session)
    {
        $this->cek_akses();
        $this->Peralatan_event_model->swap_kategori_urutan($id_session, 'down');
        redirect('peralatan-event/kategori');
    }

    // ================= Item =================

    public function store()
    {
        $this->cek_akses();

        if (!$this->validasi_post()) {
            redirect('peralatan-event');
            return;
        }

        $kategori_id_session = $this->input->post('kategori_id_session');

        $this->Peralatan_event_model->insert_template([
            'id_session' => hash('sha256', bin2hex(random_bytes(16))),
            'kategori_id_session' => $kategori_id_session,
            'nama_barang' => $this->input->post('nama_barang'),
            'detail' => $this->input->post('detail'),
            'bold' => $this->input->post('bold') ? 1 : 0,
            'urutan' => $this->Peralatan_event_model->get_template_next_urutan($kategori_id_session),
            'created_by' => $this->session->id_session,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('Success', 'Item berhasil ditambahkan.');
        redirect('peralatan-event');
    }

    public function update($id_session)
    {
        $this->cek_akses();

        if (!$this->validasi_post()) {
            redirect('peralatan-event');
            return;
        }

        $this->Peralatan_event_model->update_template($id_session, [
            'nama_barang' => $this->input->post('nama_barang'),
            'detail' => $this->input->post('detail'),
            'bold' => $this->input->post('bold') ? 1 : 0,
        ]);

        $this->session->set_flashdata('Success', 'Item berhasil diperbarui.');
        redirect('peralatan-event');
    }

    public function delete($id_session)
    {
        $this->cek_akses();
        $this->Peralatan_event_model->delete_template($id_session);
        $this->session->set_flashdata('Success', 'Item berhasil dihapus.');
        redirect('peralatan-event');
    }

    public function move_up($id_session)
    {
        $this->cek_akses();
        $this->Peralatan_event_model->swap_template_urutan($id_session, 'up');
        redirect('peralatan-event');
    }

    public function move_down($id_session)
    {
        $this->cek_akses();
        $this->Peralatan_event_model->swap_template_urutan($id_session, 'down');
        redirect('peralatan-event');
    }

    private function validasi_post()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('kategori_id_session', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            return false;
        }

        return true;
    }
}
