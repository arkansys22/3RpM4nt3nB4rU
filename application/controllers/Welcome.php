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

		$data['paketwo'] = $this->Potensial_model->get_pricelist_type('WP dan WO');
		$this->load->view('pricelist', $data);
	}

}
