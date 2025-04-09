<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terima_kasih2 extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Naskah_model');
    }

    public function view($id_session) {
        $data['client'] = $this->Naskah_model->get_by_session($id_session);

        if (!$data['client']) {
            show_404();
        }

        $this->load->view('naskah/terima_kasih2', $data);
    }

    public function generate_pdf($id_session) {
        $this->load->library('pdf');
    
        // Ambil data client
        $data['client'] = $this->Naskah_model->get_by_session($id_session);
        if (!$data['client']) {
            show_404();
        }
    
        // Ambil client_name sebagai nama file, jika tidak ada gunakan default
        $client_name = $data['client']->client_name ? $data['client']->client_name : 'Terimakasih_Naskah';
        
        // Format nama file sesuai keinginan
        $filename = $client_name . ' Naskah Ucapan Terimakasih oleh Pengantin Pria';
    
        // Generate PDF dengan nama file yang sudah diformat
        $html = $this->load->view('naskah/pdf_terima_kasih2', $data, true);
        $this->pdf->createPDF_P($html, $filename, true);
    }
}    