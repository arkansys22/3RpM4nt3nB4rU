<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// List Peralatan Event per project -- diakses dari tombol di halaman
// project/lihat. Dikelompokkan per kategori (WO, Fotobooth, dst -- shared
// dengan template global, lihat Crud_peralatan_event), bisa disalin dari
// template, lalu diedit bebas per project, dan dicetak jadi dokumen A4.
class Crud_client_peralatan_event extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Peralatan_event_model');
        $this->load->model('project_model');
        $this->load->model('Clients_model');
        $this->load->helper(['url', 'form']);
    }

    private function cek_akses()
    {
        if (!in_array($this->session->level, ['1', '2', '4'])) {
            redirect(base_url('panel'));
            exit;
        }
    }

    private function get_project_or_404($project_id_session)
    {
        $project = $this->project_model->get_project_by_session($project_id_session);
        if (!$project) {
            show_error('Project tidak ditemukan.', 404);
            return null;
        }
        return $project;
    }

    // Tidak ada lagi tampilan "Semua" -- kalau kategori tidak disebutkan di
    // URL, otomatis diarahkan ke kategori pertama (mis. WO). Jadi halaman
    // ini SELALU menampilkan cuma satu kategori dalam satu waktu.
    public function index($project_id_session, $kategori_id_session = null)
    {
        $this->cek_akses();

        $project = $this->get_project_or_404($project_id_session);
        if (!$project) {
            return;
        }

        $kategori_list = $this->Peralatan_event_model->get_kategori_all();

        if (!$kategori_id_session && !empty($kategori_list)) {
            redirect('peralatan-event-project/' . $project_id_session . '/' . $kategori_list[0]->id_session);
            return;
        }

        $data['project'] = $project;
        $data['kategori_list'] = $kategori_list;
        $data['kategori_aktif'] = $kategori_id_session;

        $kat = $kategori_id_session ? $this->Peralatan_event_model->get_kategori_by_session($kategori_id_session) : null;
        $items = $kat ? $this->Peralatan_event_model->get_by_project_kategori($project_id_session, $kategori_id_session) : [];
        $data['grouped'] = $kat ? [['kategori' => $kat, 'items' => $items]] : [];

        // "ada_item" di-scope ke KATEGORI YANG SEDANG DIBUKA saja -- jadi
        // kalau WO sudah ada isinya tapi Fotobooth masih kosong, tab
        // Fotobooth tetap menawarkan "Salin dari Template" sendiri.
        $data['ada_item'] = !empty($items);
        $data['template_tersedia'] = $kategori_list;

        $this->load->view('backend/v_client_peralatan_event', $data);
    }

    // Salin item template ke project ini. $kategori_id_session WAJIB diisi
    // (dikirim dari tombol "Salin dari Template" sesuai tab/kategori yang
    // lagi aktif) -- cuma menarik item dari kategori itu saja, kategori
    // lain (mis. Fotobooth waktu lagi buka tab WO) TIDAK ikut tertarik.
    // Ditambahkan di urutan paling akhir kategori itu -- kalau dipanggil
    // berkali-kali, item template akan ke-duplikat, jadi tombolnya cuma
    // perlu diklik sekali per kategori per project.
    public function salin($project_id_session, $kategori_id_session)
    {
        $this->cek_akses();

        if (!$this->get_project_or_404($project_id_session)) {
            return;
        }

        $kat = $this->Peralatan_event_model->get_kategori_by_session($kategori_id_session);
        if (!$kat) {
            show_error('Kategori tidak ditemukan.', 404);
            return;
        }

        $template = $this->Peralatan_event_model->get_template_by_kategori($kategori_id_session);
        $urutan = $this->Peralatan_event_model->get_next_urutan($project_id_session, $kategori_id_session);

        foreach ($template as $item) {
            $this->Peralatan_event_model->insert([
                'id_session' => hash('sha256', bin2hex(random_bytes(16))),
                'project_id_session' => $project_id_session,
                'kategori_id_session' => $kategori_id_session,
                'nama_barang' => $item->nama_barang,
                'detail' => $item->detail,
                'bold' => $item->bold,
                'urutan' => $urutan++,
                'created_by' => $this->session->id_session,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $this->session->set_flashdata('Success', count($template) . ' item ' . $kat->nama_kategori . ' berhasil disalin dari template.');
        redirect('peralatan-event-project/' . $project_id_session . '/' . $kategori_id_session);
    }

    public function store()
    {
        $this->cek_akses();

        $project_id_session = $this->input->post('project_id_session');
        if (!$this->validasi_post(true)) {
            redirect('peralatan-event-project/' . $project_id_session);
            return;
        }

        $kategori_id_session = $this->input->post('kategori_id_session');

        $this->Peralatan_event_model->insert([
            'id_session' => hash('sha256', bin2hex(random_bytes(16))),
            'project_id_session' => $project_id_session,
            'kategori_id_session' => $kategori_id_session,
            'nama_barang' => $this->input->post('nama_barang'),
            'detail' => $this->input->post('detail'),
            'bold' => $this->input->post('bold') ? 1 : 0,
            'urutan' => $this->Peralatan_event_model->get_next_urutan($project_id_session, $kategori_id_session),
            'created_by' => $this->session->id_session,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('Success', 'Item berhasil ditambahkan.');
        redirect('peralatan-event-project/' . $project_id_session);
    }

    public function update($id_session)
    {
        $this->cek_akses();

        $item = $this->Peralatan_event_model->get_by_session($id_session);
        if (!$item) {
            show_error('Item tidak ditemukan.', 404);
            return;
        }

        if (!$this->validasi_post(false)) {
            redirect('peralatan-event-project/' . $item->project_id_session);
            return;
        }

        $this->Peralatan_event_model->update($id_session, [
            'nama_barang' => $this->input->post('nama_barang'),
            'detail' => $this->input->post('detail'),
            'bold' => $this->input->post('bold') ? 1 : 0,
        ]);

        $this->session->set_flashdata('Success', 'Item berhasil diperbarui.');
        redirect('peralatan-event-project/' . $item->project_id_session);
    }

    public function delete($id_session)
    {
        $this->cek_akses();

        $item = $this->Peralatan_event_model->get_by_session($id_session);
        $redirect_to = 'panel';
        if ($item) {
            $redirect_to = 'peralatan-event-project/' . $item->project_id_session;
            $this->Peralatan_event_model->delete($id_session);
            $this->session->set_flashdata('Success', 'Item berhasil dihapus.');
        }

        redirect($redirect_to);
    }

    public function move_up($id_session)
    {
        $this->cek_akses();
        $item = $this->Peralatan_event_model->get_by_session($id_session);
        $this->Peralatan_event_model->swap_urutan($id_session, 'up');
        redirect($item ? 'peralatan-event-project/' . $item->project_id_session : 'panel');
    }

    public function move_down($id_session)
    {
        $this->cek_akses();
        $item = $this->Peralatan_event_model->get_by_session($id_session);
        $this->Peralatan_event_model->swap_urutan($id_session, 'down');
        redirect($item ? 'peralatan-event-project/' . $item->project_id_session : 'panel');
    }

    // Tidak ada lagi cetak "Semua" -- kalau kategori tidak disebutkan,
    // otomatis diarahkan ke kategori pertama.
    public function cetak($project_id_session, $kategori_id_session = null)
    {
        $this->cek_akses();

        $project = $this->get_project_or_404($project_id_session);
        if (!$project) {
            return;
        }

        if (!$kategori_id_session) {
            $kategori_list = $this->Peralatan_event_model->get_kategori_all();
            if (!empty($kategori_list)) {
                redirect('peralatan-event-project/cetak/' . $project_id_session . '/' . $kategori_list[0]->id_session);
                return;
            }
        }

        $data['project'] = $project;
        $data['clients'] = $this->Clients_model->get_client_by_session($project_id_session);

        $kat = $kategori_id_session ? $this->Peralatan_event_model->get_kategori_by_session($kategori_id_session) : null;
        $items = $kat ? $this->Peralatan_event_model->get_by_project_kategori($project_id_session, $kategori_id_session) : [];
        $data['grouped'] = $kat ? [['kategori' => $kat, 'items' => $items]] : [];

        $this->load->view('backend/v_client_peralatan_event_cetak', $data);
    }

    // $wajib_kategori = true buat store() (kategori dipilih waktu bikin
    // baru), false buat update() (kategori item sudah tetap, tidak
    // ditampilkan lagi di form edit).
    private function validasi_post($wajib_kategori)
    {
        $this->load->library('form_validation');
        if ($wajib_kategori) {
            $this->form_validation->set_rules('kategori_id_session', 'Kategori', 'required|trim');
        }
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            return false;
        }

        return true;
    }
}
