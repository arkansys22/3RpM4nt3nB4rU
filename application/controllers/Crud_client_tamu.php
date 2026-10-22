<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * List tamu undangan per klien/project (clients/lihat/<id> -> Menu -> List Tamu).
 * Tamu disimpan di tabel `client_tamu` (db/client_tamu.sql), dikunci ke
 * clients.id_session lewat kolom client_id_session. Isiannya sengaja ringkas:
 * honorifics, nama tamu, dan kategori.
 */
class Crud_client_tamu extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Client_tamu_model');
        $this->load->model('Clients_model');
    }

    private function cek_akses()
    {
        if (!in_array($this->session->level, ['1', '2', '4'])) {
            redirect(base_url());
            exit;
        }
    }

    private function get_client_or_404($client_id_session)
    {
        $client = $this->Clients_model->get_client_by_session($client_id_session);
        if (!$client) {
            show_error('Client tidak ditemukan.', 404);
        }
        return $client;
    }

    private function kembali($client_id_session, $jenis, $pesan)
    {
        $this->session->set_flashdata($jenis, $pesan);
        redirect('clients/tamu/' . $client_id_session);
    }

    public function index($client_id_session)
    {
        $this->cek_akses();
        $data['clients'] = $this->get_client_or_404($client_id_session);
        $data['tabel_ada'] = $this->Client_tamu_model->tabel_tersedia();
        $data['tamu'] = $this->Client_tamu_model->get_by_client($client_id_session);

        // ?edit=<id>: isi form dengan data tamu itu (hanya kalau milik klien ini)
        $data['edit'] = null;
        $edit_id = (int) $this->input->get('edit');
        if ($edit_id > 0) {
            $kandidat = $this->Client_tamu_model->get($edit_id);
            if ($kandidat && $kandidat->client_id_session === $client_id_session) {
                $data['edit'] = $kandidat;
            }
        }
        $this->load->view('clients/tamu', $data);
    }

    // Tambah satu tamu, atau simpan perubahan kalau ada id_tamu.
    public function store()
    {
        $this->cek_akses();
        $client_id_session = $this->input->post('client_id_session');
        $this->get_client_or_404($client_id_session);

        $nama = trim((string) $this->input->post('nama'));
        if ($nama === '') {
            $this->kembali($client_id_session, 'Error', 'Nama tamu wajib diisi.');
        }
        $data = [
            'nama'       => $nama,
            'honorifics' => mb_substr(trim((string) $this->input->post('honorifics')), 0, 50),
            'kategori'   => mb_substr(trim((string) $this->input->post('kategori')), 0, 255),
        ];

        $id_tamu = (int) $this->input->post('id_tamu');
        if ($id_tamu > 0) {
            $tamu = $this->Client_tamu_model->get($id_tamu);
            if (!$tamu || $tamu->client_id_session !== $client_id_session) {
                $this->kembali($client_id_session, 'Error', 'Tamu tidak ditemukan.');
            }
            $this->Client_tamu_model->update($id_tamu, $data);
            $this->kembali($client_id_session, 'Success', 'Data tamu berhasil diubah.');
        }

        $data['client_id_session'] = $client_id_session;
        $data['created_by'] = (string) $this->session->id_session;
        $this->Client_tamu_model->insert($data);
        $this->kembali($client_id_session, 'Success', 'Tamu "' . $nama . '" berhasil ditambahkan.');
    }

    public function delete($id)
    {
        $this->cek_akses();
        $tamu = $this->Client_tamu_model->get($id);
        if (!$tamu) {
            show_error('Tamu tidak ditemukan.', 404);
        }
        $this->Client_tamu_model->delete($id);
        $this->kembali($tamu->client_id_session, 'Success', 'Tamu "' . $tamu->nama . '" dihapus.');
    }

    // Cetak PDF list tamu (dibuka inline di tab baru, bisa dicetak/diunduh dari viewer).
    // Judul acara & judul daftar diambil dari query string (?judul_acara=&judul_daftar=).
    public function pdf($client_id_session)
    {
        $this->cek_akses();
        $client = $this->get_client_or_404($client_id_session);

        $judul_acara = trim((string) $this->input->get('judul_acara'));
        $judul_daftar = trim((string) $this->input->get('judul_daftar'));

        $data = [
            'clients' => $client,
            'tamu' => $this->Client_tamu_model->get_by_client($client_id_session),
            'judul_acara' => $judul_acara !== '' ? $judul_acara : 'ACARA RESEPSI NIKAH',
            'judul_daftar' => $judul_daftar !== '' ? $judul_daftar : 'List Tamu',
        ];

        $this->load->library('pdf');
        $html = $this->load->view('clients/pdf_tamu', $data, true);
        $isi_pdf = $this->pdf->createPDF_P($html, '', false, true); // true = subset font (huruf Mandarin)

        $nama_file = preg_replace('/[^A-Za-z0-9]+/', '_', $client->client_name . ' List Tamu') . '.pdf';
        $this->output
            ->set_content_type('application/pdf')
            ->set_header('Content-Disposition: inline; filename="' . $nama_file . '"')
            ->set_output($isi_pdf);
    }
}
