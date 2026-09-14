<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jubir_cpp extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Naskah_model');
    }

    // Jubir Keluarga Pria (CPP) punya 2 pilihan naskah -- "Akad" (naskah
    // penyerahan, default/lama) atau "Ngunduh Mantu" (naskah sambutan) --
    // dipilih dari clients.jubir_pria_naskah (lihat clients/edit &
    // clients/c_edit, cuma relevan kalau m_spokesman diisi).
    public function view($id_session) {
        $data['client'] = $this->Naskah_model->get_by_session($id_session);

        if (!$data['client']) {
            show_404();
        }

        $view = $data['client']->jubir_pria_naskah === 'Ngunduh Mantu'
            ? 'naskah/jubir_cpp_ngunduh_mantu'
            : 'naskah/jubir_cpp';

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
        $client_name = $data['client']->client_name ? $data['client']->client_name : 'Jubir_CPP_Naskah';

        $is_ngunduh_mantu = $data['client']->jubir_pria_naskah === 'Ngunduh Mantu';

        // Format nama file sesuai keinginan
        $filename = $client_name . ($is_ngunduh_mantu
            ? ' Sambutan Ngunduh Mantu Pengantin Pria (Jubir CPP)'
            : ' Naskah Penyerahan Calon Pengantin Pria (Jubir CPP)');

        // Generate PDF dengan nama file yang sudah diformat
        $view = $is_ngunduh_mantu ? 'naskah/pdf_jubir_cpp_ngunduh_mantu' : 'naskah/pdf_jubir_cpp';
        $html = $this->load->view($view, $data, true);
        $this->pdf->createPDF_P($html, $filename, true);
    }
}    