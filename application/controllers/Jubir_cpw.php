<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jubir_cpp extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Naskah_model');
    }

    public function view($id_session) {
        $data['client'] = $this->Naskah_model->get_by_session($id_session);

        if (!$data['client']) {
            show_404();
        }

        $this->load->view('naskah/jubir_cpw', $data);
    }

    public function generate_pdf($id_session) {
        $this->load->library('pdf');

        $data['client'] = $this->Naskah_model->get_by_session($id_session);

        if (!$data['client']) {
            show_404();
        }

        $html = $this->load->view('naskah/pdf_jubir_cpw', $data, true);
        $this->pdf->createPDF($html, 'Jubir_CPW_Naskah', true);
    }
}
