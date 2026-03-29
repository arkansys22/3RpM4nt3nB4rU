<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Potensial_model');
        $this->load->helper('url');
    }


	public function index()
	{
		$this->load->view('welcome_message');
	}


	public function pricelist()
	{
		$types = ['MC', 'WP dan WO', 'Dokumentasi', 'Catering', 'Dekorasi','Entertainment','Makeup & Busana']; 
		$data['paketwo'] = $this->Potensial_model->get_pricelist_type('Paket Wedding Organizer');
		$data['paketlamaran'] = $this->Potensial_model->get_pricelist_type('Paket Lamaran');
		$data['paketgedung'] = $this->Potensial_model->get_pricelist_type('Paket Wedding Gedung');
		$data['paketrumah'] = $this->Potensial_model->get_pricelist_type('Paket Wedding Rumah');
		$data['paketvendor'] = $this->Potensial_model->get_pricelist_types($types);
		$this->load->view('pricelist', $data);
	}

}
