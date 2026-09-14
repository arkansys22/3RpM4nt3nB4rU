<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jubir_cpw extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Naskah_model');
    }

    // Jubir Keluarga Wanita (CPW) ikut pilihan naskah yang sama dengan
    // Jubir Keluarga Pria -- SATU parameter (clients.jubir_pria_naskah,
    // lihat clients/edit & clients/c_edit) berlaku buat naskah Pria MAUPUN
    // Wanita sekaligus, tidak ada dropdown/kolom terpisah buat Wanita.
    public function view($id_session) {
        $data['client'] = $this->Naskah_model->get_by_session($id_session);

        if (!$data['client']) {
            show_404();
        }

        $view = $data['client']->jubir_pria_naskah === 'Ngunduh Mantu'
            ? 'naskah/jubir_cpw_ngunduh_mantu'
            : 'naskah/jubir_cpw';

        $this->load->view($view, $data);
    }

    public function generate_pdf($id_session) {
        $this->load->library('pdf');

        // Ambil data client
        $data['client'] = $this->Naskah_model->get_by_session($id_session);
        if (!$data['client']) {
            show_404();
        }

        // Ambil client_name sebagai nama file, jika tidak ada gunakan default
        $client_name = $data['client']->client_name ? $data['client']->client_name : 'Jubir_CPW_Naskah';

        $is_ngunduh_mantu = $data['client']->jubir_pria_naskah === 'Ngunduh Mantu';

        // Format nama file sesuai keinginan
        $filename = $client_name . ($is_ngunduh_mantu
            ? ' Sambutan Ngunduh Mantu Pengantin Wanita (Jubir CPW)'
            : ' Naskah Penerimaan Calon Pengantin Pria (Jubir CPW)');

        // Generate PDF dengan nama file yang sudah diformat
        $view = $is_ngunduh_mantu ? 'naskah/pdf_jubir_cpw_ngunduh_mantu' : 'naskah/pdf_jubir_cpw';
        $html = $this->load->view($view, $data, true);
        $this->pdf->createPDF_P($html, $filename, true);
    }
}    