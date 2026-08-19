<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_susunan_acara extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Susunan_acara_model');
        $this->load->helper(['url', 'form']);
    }

    private function cek_akses()
    {
        if (!in_array($this->session->level, ['1', '2', '4'])) {
            redirect(base_url());
            exit;
        }
    }

    public function index()
    {
        $this->cek_akses();

        // Halaman ini cuma menampilkan daftar kategori (+ jumlah kegiatan per kategori).
        // Detail kegiatannya ada di halaman terpisah, lihat method lihat().
        $data['grouped'] = $this->Susunan_acara_model->get_all_grouped();
        $this->load->view('backend/v_susunan_acara', $data);
    }

    public function lihat($kategori_id_session)
    {
        $this->cek_akses();

        $data['kategori'] = $this->Susunan_acara_model->get_kategori_by_session($kategori_id_session);
        if (!$data['kategori']) {
            show_error('Kategori tidak ditemukan.', 404);
            return;
        }

        $data['kegiatan_list'] = $this->Susunan_acara_model->get_kegiatan_by_kategori($kategori_id_session);

        $this->load->view('backend/v_susunan_acara_detail', $data);
    }

    public function presentasi($kategori_id_session)
    {
        $this->cek_akses();

        $data['kategori'] = $this->Susunan_acara_model->get_kategori_by_session($kategori_id_session);
        if (!$data['kategori']) {
            show_error('Kategori tidak ditemukan.', 404);
            return;
        }

        $data['kegiatan_list'] = $this->Susunan_acara_model->get_kegiatan_by_kategori($kategori_id_session);

        $this->load->view('backend/v_susunan_acara_presentasi', $data);
    }

    // ================= Kategori =================

    public function kategori()
    {
        $this->cek_akses();

        $data['kategori_list'] = $this->Susunan_acara_model->get_kategori_all();
        $this->load->view('backend/v_susunan_acara_kategori', $data);
    }

    public function kategori_store()
    {
        $this->cek_akses();

        $nama = trim($this->input->post('nama_kategori'));
        if ($nama === '') {
            $this->session->set_flashdata('error', 'Nama kategori wajib diisi');
            redirect('susunan-acara/kategori');
            return;
        }

        $this->Susunan_acara_model->insert_kategori([
            'id_session'    => hash('sha256', bin2hex(random_bytes(16))),
            'nama_kategori' => $nama,
            'urutan'        => $this->Susunan_acara_model->get_kategori_next_urutan(),
            'created_by'    => $this->session->id_session,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('Success', 'Kategori berhasil ditambahkan');
        redirect('susunan-acara/kategori');
    }

    public function kategori_update($id_session)
    {
        $this->cek_akses();

        $nama = trim($this->input->post('nama_kategori'));
        if ($nama === '') {
            $this->session->set_flashdata('error', 'Nama kategori wajib diisi');
            redirect('susunan-acara/kategori');
            return;
        }

        $this->Susunan_acara_model->update_kategori_by_session($id_session, ['nama_kategori' => $nama]);

        $this->session->set_flashdata('Success', 'Kategori berhasil diperbarui');
        redirect('susunan-acara/kategori');
    }

    public function kategori_delete($id_session)
    {
        $this->cek_akses();

        // Hapus dulu file foto semua kegiatan di kategori ini sebelum baris-barisnya
        // ikut kehapus (cascade) di model.
        $this->db->where('kategori_id_session', $id_session);
        $kegiatanList = $this->db->get('susunan_acara')->result();
        foreach ($kegiatanList as $k) {
            if (!empty($k->foto)) {
                $path = FCPATH . 'assets/uploads/susunan_acara/' . $k->foto;
                if (is_file($path)) {
                    unlink($path);
                }
            }
        }

        $this->Susunan_acara_model->delete_kategori_by_session($id_session);

        $this->session->set_flashdata('Success', 'Kategori beserta kegiatan di dalamnya berhasil dihapus');
        redirect('susunan-acara/kategori');
    }

    public function kategori_move_up($id_session)
    {
        $this->cek_akses();
        $this->Susunan_acara_model->swap_kategori_urutan($id_session, 'up');
        redirect('susunan-acara/kategori');
    }

    public function kategori_move_down($id_session)
    {
        $this->cek_akses();
        $this->Susunan_acara_model->swap_kategori_urutan($id_session, 'down');
        redirect('susunan-acara/kategori');
    }

    // ================= Kegiatan =================

    public function create($kategori_id_session = null)
    {
        $this->cek_akses();

        $data['item'] = null;
        $data['kategori_list'] = $this->Susunan_acara_model->get_kategori_all();
        $data['selected_kategori'] = $kategori_id_session;

        if (empty($data['kategori_list'])) {
            $this->session->set_flashdata('error', 'Buat kategori acara dulu (mis. Akad Nikah, Resepsi) sebelum menambah kegiatan.');
            redirect('susunan-acara/kategori');
            return;
        }

        $this->load->view('backend/v_susunan_acara_form', $data);
    }

    public function store()
    {
        $this->cek_akses();

        if (!$this->validasi_post()) {
            redirect('susunan-acara/create/' . $this->input->post('kategori_id_session'));
            return;
        }

        $foto = $this->upload_foto();
        if ($foto === false) {
            redirect('susunan-acara/create/' . $this->input->post('kategori_id_session'));
            return;
        }

        $kategori_id_session = $this->input->post('kategori_id_session');
        $id_session = hash('sha256', bin2hex(random_bytes(16)));

        $this->Susunan_acara_model->insert([
            'id_session'          => $id_session,
            'kategori_id_session' => $kategori_id_session,
            'nama_kegiatan'       => $this->input->post('nama_kegiatan'),
            'durasi'              => $this->input->post('durasi'),
            'vendor_pj'           => $this->input->post('vendor_pj'),
            'foto'                => $foto,
            'urutan'              => $this->Susunan_acara_model->get_next_urutan($kategori_id_session),
            'created_by'          => $this->session->id_session,
            'created_at'          => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('Success', 'Kegiatan berhasil ditambahkan');
        redirect('susunan-acara/lihat/' . $kategori_id_session);
    }

    public function edit($id_session)
    {
        $this->cek_akses();

        $data['item'] = $this->Susunan_acara_model->get_by_session($id_session);
        if (!$data['item']) {
            show_error('Kegiatan tidak ditemukan.', 404);
            return;
        }

        $data['kategori_list'] = $this->Susunan_acara_model->get_kategori_all();
        $data['selected_kategori'] = $data['item']->kategori_id_session;

        $this->load->view('backend/v_susunan_acara_form', $data);
    }

    public function update($id_session)
    {
        $this->cek_akses();

        $item = $this->Susunan_acara_model->get_by_session($id_session);
        if (!$item) {
            show_error('Kegiatan tidak ditemukan.', 404);
            return;
        }

        if (!$this->validasi_post()) {
            redirect('susunan-acara/edit/' . $id_session);
            return;
        }

        $data = [
            'kategori_id_session' => $this->input->post('kategori_id_session'),
            'nama_kegiatan'       => $this->input->post('nama_kegiatan'),
            'durasi'              => $this->input->post('durasi'),
            'vendor_pj'           => $this->input->post('vendor_pj'),
        ];

        // Foto pada saat edit sifatnya opsional — kalau tidak diganti, foto lama tetap dipakai.
        if (!empty($_FILES['foto']['name'])) {
            $foto = $this->upload_foto();
            if ($foto === false) {
                redirect('susunan-acara/edit/' . $id_session);
                return;
            }
            $data['foto'] = $foto;

            if (!empty($item->foto)) {
                $old_path = FCPATH . 'assets/uploads/susunan_acara/' . $item->foto;
                if (is_file($old_path)) {
                    unlink($old_path);
                }
            }
        }

        $this->Susunan_acara_model->update_by_session($id_session, $data);

        $this->session->set_flashdata('Success', 'Kegiatan berhasil diperbarui');
        redirect('susunan-acara/lihat/' . $data['kategori_id_session']);
    }

    public function delete($id_session)
    {
        $this->cek_akses();

        $item = $this->Susunan_acara_model->get_by_session($id_session);
        $redirectTo = 'susunan-acara';

        if ($item) {
            $redirectTo = 'susunan-acara/lihat/' . $item->kategori_id_session;

            if (!empty($item->foto)) {
                $path = FCPATH . 'assets/uploads/susunan_acara/' . $item->foto;
                if (is_file($path)) {
                    unlink($path);
                }
            }
            $this->Susunan_acara_model->delete_by_session($id_session);
            $this->session->set_flashdata('Success', 'Kegiatan berhasil dihapus');
        }

        redirect($redirectTo);
    }

    public function move_up($id_session)
    {
        $this->cek_akses();
        $item = $this->Susunan_acara_model->get_by_session($id_session);
        $this->Susunan_acara_model->swap_urutan($id_session, 'up');
        redirect($item ? 'susunan-acara/lihat/' . $item->kategori_id_session : 'susunan-acara');
    }

    public function move_down($id_session)
    {
        $this->cek_akses();
        $item = $this->Susunan_acara_model->get_by_session($id_session);
        $this->Susunan_acara_model->swap_urutan($id_session, 'down');
        redirect($item ? 'susunan-acara/lihat/' . $item->kategori_id_session : 'susunan-acara');
    }

    private function validasi_post()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('kategori_id_session', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('nama_kegiatan', 'Nama Kegiatan', 'required|trim');
        $this->form_validation->set_rules('durasi', 'Durasi', 'required|trim');
        $this->form_validation->set_rules('vendor_pj', 'Vendor/PJ', 'required|trim');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            return false;
        }

        return true;
    }

    /**
     * Upload foto ilustrasi. Return nama file (string) kalau sukses, atau FALSE
     * kalau ada file yang dipilih tapi gagal (pesan errornya sudah di-flash).
     * Dipanggil dari store() (wajib ada file) dan update() (hanya kalau file baru dipilih).
     */
    private function upload_foto()
    {
        if (empty($_FILES['foto']['name'])) {
            $this->session->set_flashdata('error', 'Foto ilustrasi wajib diisi');
            return false;
        }

        $basePath = FCPATH . 'assets/uploads/susunan_acara';
        if (!is_dir($basePath)) {
            mkdir($basePath, 0777, true);
        }

        $config = [
            'upload_path'   => $basePath,
            'allowed_types' => 'jpg|jpeg|png|webp',
            'file_name'     => 'susunan_acara_' . md5(uniqid('', true)),
            'max_size'      => 2048,
        ];

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('foto')) {
            $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
            return false;
        }

        $file = $this->upload->data();
        return $file['file_name'];
    }
}
