<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Aspanel extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Potensial_model');
		$this->load->model('Clients_model');
		$this->load->model('project_model');
		$this->load->model('Payment_model');
		$this->load->model('finance_project_model');
		date_default_timezone_set('Asia/Jakarta');
	}
	public function index()
		{
		redirect(base_url('login'));
		}
		public function home($view = null)
		{
			if (!$this->session->userdata('crews_idsession')) {
				$user = $this->db->get_where('user', ['id_session' => $this->session->userdata('id_session')])->row();
				if ($user && isset($user->crews_idsession)) {
					$this->session->set_userdata('crews_idsession', $user->crews_idsession);
				}
			}

			$user = $this->db->get_where('user', ['id_session' => $this->session->userdata('id_session')])->row();
			$level = $this->session->userdata('level');
			$data['tampilkan_button_event'] = false;

			if ($user && in_array($level, ['1', '2', '3', '4'])) {
				if (!empty($user->crews_idsession) && $user->crews_idsession !== '-') {
					$data['tampilkan_button_event'] = true;
				}
			}

			if ($this->session->level == '1') {
				cek_session_akses_developer('panel', $this->session->id_session);
				$month_now = date('Y-m');
				$month_last = date('Y-m', strtotime('last month'));
				$data['client_bulan_ini'] = $this->Clients_model->get_clients_by_month(date('Y-m'));  // Client bulan ini
				$data['client_bulan_lalu'] = $this->Clients_model->get_clients_by_month(date('Y-m', strtotime('last month'))); // Client bulan lalu
				$data['client_bulan_depan'] = $this->Clients_model->get_clients_by_month(date('Y-m', strtotime('next month'))); // Client bulan depan
				$data['total_client'] = $this->Clients_model->get_total_clients(); // Total client

				
				// Revenue bulan ini
				$data['revenue_bulan_ini'] = $this->db->select_sum('total_paid')
					->where('DATE_FORMAT(date, "%Y-%m") =', $month_now)
					->get('payment')
					->row();

				// Revenue bulan lalu
				$data['revenue_bulan_lalu'] = $this->db->select_sum('total_paid')
					->where('DATE_FORMAT(date, "%Y-%m") =', $month_last)
					->get('payment')
					->row();

				// Total revenue all
				$data['total_revenue_all'] = $this->db->select_sum('total_paid')
					->get('payment')
					->row();

				// Hitung persentase perubahan revenue
				$data['percent_change'] = null;
				if ($data['revenue_bulan_ini'] && $data['revenue_bulan_lalu'] && $data['revenue_bulan_lalu']->total_paid != 0) {
					$data['percent_change'] = (($data['revenue_bulan_ini']->total_paid - $data['revenue_bulan_lalu']->total_paid) / $data['revenue_bulan_lalu']->total_paid) * 100;
				}

				// Load view based on the URI segment
				if ($view === 'staff') {
					$this->db->select('project.event_date, project.location, project.client_name, crew_projects.role, crew_projects.project_id');
					$this->db->from('user');
					$this->db->join('crew_projects', 'user.crews_idsession = crew_projects.crew_id');
					$this->db->join('project', 'crew_projects.project_id = project.id_session');
					$this->db->where('user.id_session', $this->session->id_session);
					$this->db->order_by('project.event_date', 'DESC');
					$data['events'] = $this->db->get()->result_array();
					$this->load->view('backend/v_home_staff', $data);
				} else {
					$this->load->view('backend/v_home', $data);
				}
		
			} else if ($this->session->level == '2') {
				cek_session_akses_administrator('panel', $this->session->id_session);
				$month_now = date('Y-m');
				$month_last = date('Y-m', strtotime('last month'));
				$data['client_bulan_ini'] = $this->Clients_model->get_clients_by_month(date('Y-m'));
				$data['client_bulan_lalu'] = $this->Clients_model->get_clients_by_month(date('Y-m', strtotime('last month')));
				$data['client_bulan_depan'] = $this->Clients_model->get_clients_by_month(date('Y-m', strtotime('next month'))); // Client bulan depan
				$data['total_client'] = $this->Clients_model->get_total_clients();
				
				// Revenue bulan ini
				$data['revenue_bulan_ini'] = $this->db->select_sum('total_paid')
					->where('DATE_FORMAT(date, "%Y-%m") =', $month_now)
					->get('payment')
					->row();

				// Revenue bulan lalu
				$data['revenue_bulan_lalu'] = $this->db->select_sum('total_paid')
					->where('DATE_FORMAT(date, "%Y-%m") =', $month_last)
					->get('payment')
					->row();

				// Total revenue all
				$data['total_revenue_all'] = $this->db->select_sum('total_bill')
					->get('payment')
					->row();

				// Hitung persentase perubahan revenue
				$data['percent_change'] = null;
				if ($data['revenue_bulan_ini'] && $data['revenue_bulan_lalu'] && $data['revenue_bulan_lalu']->total_paid != 0) {
					$data['percent_change'] = (($data['revenue_bulan_ini']->total_paid - $data['revenue_bulan_lalu']->total_paid) / $data['revenue_bulan_lalu']->total_paid) * 100;
				}
				if ($view === 'staff') {
					$this->db->select('project.event_date, project.location, project.client_name, crew_projects.role, crew_projects.project_id');
					$this->db->from('user');
					$this->db->join('crew_projects', 'user.crews_idsession = crew_projects.crew_id');
					$this->db->join('project', 'crew_projects.project_id = project.id_session');
					$this->db->where('user.id_session', $this->session->id_session);
					$this->db->order_by('project.event_date', 'DESC');
					$data['events'] = $this->db->get()->result_array();
					$this->load->view('backend/v_home_staff', $data);
				} else {
					$this->load->view('backend/v_home', $data);
				}
		
			} else if ($this->session->level == '3') {
				cek_session_akses_staff_accounting('panel', $this->session->id_session);
				$month_now = date('Y-m');
				$month_last = date('Y-m', strtotime('last month'));
				$data['client_bulan_ini'] = $this->Clients_model->get_clients_by_month(date('Y-m'));
				$data['client_bulan_lalu'] = $this->Clients_model->get_clients_by_month(date('Y-m', strtotime('last month')));
				$data['client_bulan_depan'] = $this->Clients_model->get_clients_by_month(date('Y-m', strtotime('next month'))); // Client bulan depan
				$data['total_client'] = $this->Clients_model->get_total_clients();
				
				// Revenue bulan ini
				$data['revenue_bulan_ini'] = $this->db->select_sum('total_paid')
					->where('DATE_FORMAT(date, "%Y-%m") =', $month_now)
					->get('payment')
					->row();

				// Revenue bulan lalu
				$data['revenue_bulan_lalu'] = $this->db->select_sum('total_paid')
					->where('DATE_FORMAT(date, "%Y-%m") =', $month_last)
					->get('payment')
					->row();

				// Total revenue all
				$data['total_revenue_all'] = $this->db->select_sum('total_bill')
					->get('payment')
					->row();

				// Hitung persentase perubahan revenue
				$data['percent_change'] = null;
				if ($data['revenue_bulan_ini'] && $data['revenue_bulan_lalu'] && $data['revenue_bulan_lalu']->total_paid != 0) {
					$data['percent_change'] = (($data['revenue_bulan_ini']->total_paid - $data['revenue_bulan_lalu']->total_paid) / $data['revenue_bulan_lalu']->total_paid) * 100;
				}
				if ($view === 'staff') {
					$this->db->select('project.event_date, project.location, project.client_name, crew_projects.role, crew_projects.project_id');
					$this->db->from('user');
					$this->db->join('crew_projects', 'user.crews_idsession = crew_projects.crew_id');
					$this->db->join('project', 'crew_projects.project_id = project.id_session');
					$this->db->where('user.id_session', $this->session->id_session);
					$this->db->order_by('project.event_date', 'DESC');
					$data['events'] = $this->db->get()->result_array();
					$this->load->view('backend/v_home_staff', $data);
				} else {
					$this->load->view('backend/v_home', $data);
				}
		
			} else if ($this->session->level == '4') {
				cek_session_akses_staff_admin('panel', $this->session->id_session);
				$month_now = date('Y-m');
				$month_last = date('Y-m', strtotime('last month'));
				$data['client_bulan_ini'] = $this->Clients_model->get_clients_by_month(date('Y-m'));
				$data['client_bulan_lalu'] = $this->Clients_model->get_clients_by_month(date('Y-m', strtotime('last month')));
				$data['client_bulan_depan'] = $this->Clients_model->get_clients_by_month(date('Y-m', strtotime('next month'))); // Client bulan depan
				$data['total_client'] = $this->Clients_model->get_total_clients();
				
				
				// Revenue bulan ini
				$data['revenue_bulan_ini'] = $this->db->select_sum('total_paid')
					->where('DATE_FORMAT(date, "%Y-%m") =', $month_now)
					->get('payment')
					->row();

				// Revenue bulan lalu
				$data['revenue_bulan_lalu'] = $this->db->select_sum('total_paid')
					->where('DATE_FORMAT(date, "%Y-%m") =', $month_last)
					->get('payment')
					->row();

				// Total revenue all
				$data['total_revenue_all'] = $this->db->select_sum('total_bill')
					->get('payment')
					->row();

				// Hitung persentase perubahan revenue
				$data['percent_change'] = null;
				if ($data['revenue_bulan_ini'] && $data['revenue_bulan_lalu'] && $data['revenue_bulan_lalu']->total_paid != 0) {
					$data['percent_change'] = (($data['revenue_bulan_ini']->total_paid - $data['revenue_bulan_lalu']->total_paid) / $data['revenue_bulan_lalu']->total_paid) * 100;
				}
				if ($view === 'staff') {
					$this->db->select('project.event_date, project.location, project.client_name, crew_projects.role, crew_projects.project_id');
					$this->db->from('user');
					$this->db->join('crew_projects', 'user.crews_idsession = crew_projects.crew_id');
					$this->db->join('project', 'crew_projects.project_id = project.id_session');
					$this->db->where('user.id_session', $this->session->id_session);
					$this->db->order_by('project.event_date', 'DESC');
					$data['events'] = $this->db->get()->result_array();
					$this->load->view('backend/v_home_staff', $data);
				} else {
					$this->load->view('backend/v_home', $data);
				}
		
			} else if ($this->session->level == '5') {
				cek_session_akses_client('panel', $this->session->id_session);
				$data['aaa'] = '';
				$this->load->view('backend/v_home_clients', $data);
		
			} else if ($this->session->level == '7') {
				cek_session_akses_staff('panel', $this->session->id_session);

					// Fetch event data
					$this->db->select('project.event_date, project.location, project.client_name, crew_projects.role, crew_projects.project_id');
					$this->db->from('user');
					$this->db->join('crew_projects', 'user.crews_idsession = crew_projects.crew_id');
					$this->db->join('project', 'crew_projects.project_id = project.id_session');
					$this->db->where('user.id_session', $this->session->id_session);
					$this->db->order_by('project.event_date', 'DESC');
					$events = $this->db->get()->result_array();

				// Pass data to the view
				$data['events'] = $events;
				$this->load->view('backend/v_home_staff', $data);

					} else if ($this->session->level == '8') {
				cek_session_akses_partner('panel', $this->session->id_session);

					// Fetch event data
					$this->db->select('project.event_date, project.location, project.client_name, vendor.type, vendor.id_session');
					$this->db->from('user');
					$this->db->join('vendor', 'user.partner_idsession = vendor.vendor_id');
					$this->db->join('project', 'vendor.id_session = project.id_session');
					$this->db->where('user.id_session', $this->session->id_session);
					$this->db->order_by('project.event_date', 'DESC');
					$events = $this->db->get()->result_array();

				// Pass data to the view
				$data['events'] = $events;
				$this->load->view('backend/v_home_partner', $data);

			} else {
				redirect(base_url());
			}
		}

	public function logout()
		{

		if ($this->agent->is_browser()) // Agent untuk fitur di log activity
                {
                      $agent = 'Desktop ' .$this->agent->browser().' '.$this->agent->version();
                }
                elseif ($this->agent->is_robot())
                {
                      $agent = $this->agent->robot();
                }
                elseif ($this->agent->is_mobile())
                {
                      $agent = 'Mobile' .$this->agent->mobile().''.$this->agent->version();
                }
                else
                {
                      $agent = 'Unidentified User Agent';
                }


		$id = array('id_session' => $this->session->id_session);
						$data = array('user_login_status'=>'offline');
						$this->db->update('user', $data, $id);

			$ip = $this->input->ip_address();
			$location = get_location_from_ip($ip);
			$ip_with_location = $ip . "<br>(" . $location . ")";
	
			$data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'Logout',            
            'log_activity_status' => 'Logout ',
			'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Potensial_model->insert_log_activity($data_log);


            // Unset user data
            $this->session->unset_userdata('logged_in');
            $this->session->unset_userdata('user_id');
            $this->session->unset_userdata('username');

            // Set message
            $this->session->set_flashdata('user_logout', 'You are now logged out');
						$this->session->sess_destroy();
            redirect(base_url());
    	}
	public function profil()
		{

		if (isset($_POST['submit'])){
			$config['upload_path'] = 'assets/frontend/user/';
			$config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
			$this->upload->initialize($config);
			$this->upload->do_upload('gambar');
			$hasil22=$this->upload->data();
			$config['image_library']='gd2';
			$config['source_image'] = './assets/frontend/user/'.$hasil22['file_name'];
			$config['create_thumb']= FALSE;
			$config['maintain_ratio']= FALSE;
			$config['quality']= '90%';
			$config['width']= 200;
			$config['height']= 200;
			$config['new_image']= './assets/frontend/user/'.$hasil22['file_name'];
			$this->load->library('image_lib', $config);
			$this->image_lib->resize();

				if ($hasil22['file_name']=='' AND $this->input->post('password')=='' ){
									          $data = array(
																'email'=>$this->db->escape_str($this->input->post('email')),
																'nama'=>$this->input->post('nama'),
																'user_update_hari'=>hari_ini(date('w')),
																'user_update_tanggal'=>date('Y-m-d'),
																'user_update_jam'=>date('H:i:s'));
																$where = array('id_user' => $this->input->post('id_user'));
						    								$this->db->update('user',$data,$where);
															}else if ($this->input->post('password')=='' ){
																$data = array(
																'user_gambar'=>$hasil22['file_name'],
																'email'=>$this->db->escape_str($this->input->post('email')),
																'nama'=>$this->input->post('nama'),
																'user_update_hari'=>hari_ini(date('w')),
																'user_update_tanggal'=>date('Y-m-d'),
																'user_update_jam'=>date('H:i:s'));
																$where = array('id_user' => $this->input->post('id_user'));
																$_image = $this->db->get_where('user',$where)->row();
																$query = $this->db->update('user',$data,$where);
																if($query){
																	unlink("assets/frontend/user/".$_image->user_gambar);
																}
															}else if ($hasil22['file_name']==''){
																$data = array(
																'email'=>$this->db->escape_str($this->input->post('email')),
																'nama'=>$this->input->post('nama'),
																'password'=>sha1($this->input->post('password')),
																'user_update_hari'=>hari_ini(date('w')),
																'user_update_tanggal'=>date('Y-m-d'),
																'user_update_jam'=>date('H:i:s'));
																$where = array('id_user' => $this->input->post('id_user'));
						    								$this->db->update('user',$data,$where);
															}else{
															$data = array(
																'user_gambar'=>$hasil22['file_name'],
																'email'=>$this->db->escape_str($this->input->post('email')),
																'nama'=>$this->input->post('nama'),
																'password'=>sha1($this->input->post('password')),
																'user_update_hari'=>hari_ini(date('w')),
																'user_update_tanggal'=>date('Y-m-d'),
																'user_update_jam'=>date('H:i:s'));
																$where = array('id_user' => $this->input->post('id_user'));
																$_image = $this->db->get_where('user',$where)->row();
																$query = $this->db->update('user',$data,$where);
																if($query){
																	unlink("assets/frontend/user/".$_image->user_gambar);
																}
															}
			redirect('aspanel/profil');
			}else{
			$proses = $this->As_m->edit('user', array('username' => $this->session->username))->row_array();
			$data = array('record' => $proses);
				$data['post'] = $this->As_m->view_ordering('user_detail','id_user','ASC');
				if ($this->session->level=='1'){
					cek_session_akses('profil',$this->session->id_session);
					$data['record_company_sub'] = $this->Crud_m->view_where_ordering('user_company',array('user_company_kategori'=>'2'),'user_company_id','ASC');
					$data['recordall'] = $this->Crud_m->view_where_ordering('user',array('user_status'=>'1'),'id_user','DESC');
					$this->load->view('backend/profil/profilall', $data);
				}elseif ($this->session->level=='2'){
					cek_session_akses_admin('profil',$this->session->id_session);
					$data['record_company_sub'] = $this->Crud_m->view_where_ordering('user_company',array('user_company_kategori'=>'2'),'user_company_id','ASC');
					$data['recordall'] = $this->Crud_m->view_where_ordering('user',array('user_status'=>'1'),'id_user','DESC');
					$this->load->view('backend/profil/profilall', $data);
				}elseif ($this->session->level=='3') {
					cek_session_akses_level_3('profil',$this->session->id_session);
					$data['record_company_sub'] = $this->Crud_m->view_where_ordering('user_company',array('user_company_kategori'=>'2'),'user_company_id','ASC');
					$data['recordall'] = $this->Crud_m->view_where_ordering('user',array('user_status'=>'1'),'id_user','DESC');
					$this->load->view('backend/profil/profilall', $data);
				}elseif ($this->session->level=='4') {
					cek_session_akses_level_4('profil',$this->session->id_session);
					$data['record_company_sub'] = $this->Crud_m->view_where_ordering('user_company',array('user_company_kategori'=>'2'),'user_company_id','ASC');
					$data['recordall'] = $this->Crud_m->view_where_ordering('user',array('user_status'=>'1'),'id_user','DESC');
					$this->load->view('backend/profil/profilall', $data);
				}elseif ($this->session->level=='5') {
					cek_session_akses_level_5('profil',$this->session->id_session);
					$data['record_company_sub'] = $this->Crud_m->view_where_ordering('user_company',array('user_company_kategori'=>'2'),'user_company_id','ASC');
					$data['recordall'] = $this->Crud_m->view_where_ordering('user',array('user_status'=>'1'),'id_user','DESC');
					$this->load->view('backend/profil/profilall', $data);
				}else{
					redirect(base_url());
				}
		}
		}
	public function user_update()
		{
				cek_session_akses($this->session->id_session);
				$id = $this->uri->segment(3);
				if (isset($_POST['submit'])){
					$config['upload_path'] = 'assets/frontend/user/';
					$config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
					$this->upload->initialize($config);
					$this->upload->do_upload('gambar');
					$hasil22=$this->upload->data();
					$config['image_library']='gd2';
					$config['source_image'] = './assets/frontend/user/'.$hasil22['file_name'];
					$config['create_thumb']= FALSE;
					$config['maintain_ratio']= FALSE;
					$config['quality']= '90%';
					$config['width']= 200;
					$config['height']= 200;
					$config['new_image']= './assets/frontend/user/'.$hasil22['file_name'];
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();

					if ($hasil22['file_name']=='' AND $this->input->post('password')=='' ){
										          $data = array(
																	'email'=>$this->db->escape_str($this->input->post('email')),
																	'nama'=>$this->input->post('nama'),
																	'level'=>$this->input->post('level'),
																	'user_status'=>$this->input->post('user_status'),
																	'user_update_hari'=>hari_ini(date('w')),
																	'user_update_tanggal'=>date('Y-m-d'),
																	'user_update_jam'=>date('H:i:s'));
																	$where = array('id_session' => $this->input->post('id_session'));
							    								$this->db->update('user',$data,$where);
																}else if ($this->input->post('password')=='' ){
																	$data = array(
																	'user_gambar'=>$hasil22['file_name'],
																	'email'=>$this->db->escape_str($this->input->post('email')),
																	'nama'=>$this->input->post('nama'),
																	'level'=>$this->input->post('level'),
																	'user_status'=>$this->input->post('user_status'),
																	'user_update_hari'=>hari_ini(date('w')),
																	'user_update_tanggal'=>date('Y-m-d'),
																	'user_update_jam'=>date('H:i:s'));
																	$where = array('id_session' => $this->input->post('id_session'));
																	$_image = $this->db->get_where('user',$where)->row();
																	$query = $this->db->update('user',$data,$where);
																	if($query){
																		unlink("assets/frontend/user/".$_image->user_gambar);
																	}
																}else if ($hasil22['file_name']==''){
																	$data = array(
																	'email'=>$this->db->escape_str($this->input->post('email')),
																	'nama'=>$this->input->post('nama'),
																	'password'=>sha1($this->input->post('password')),
																	'level'=>$this->input->post('level'),
																	'user_status'=>$this->input->post('user_status'),
																	'user_update_hari'=>hari_ini(date('w')),
																	'user_update_tanggal'=>date('Y-m-d'),
																	'user_update_jam'=>date('H:i:s'));
																	$where = array('id_session' => $this->input->post('id_session'));
							    								$this->db->update('user',$data,$where);
																}else{
																$data = array(
																	'user_gambar'=>$hasil22['file_name'],
																	'email'=>$this->db->escape_str($this->input->post('email')),
																	'nama'=>$this->input->post('nama'),
																	'password'=>sha1($this->input->post('password')),
																	'level'=>$this->input->post('level'),
																	'user_status'=>$this->input->post('user_status'),
																	'user_update_hari'=>hari_ini(date('w')),
																	'user_update_tanggal'=>date('Y-m-d'),
																	'user_update_jam'=>date('H:i:s'));
																	$where = array('id_session' => $this->input->post('id_session'));
																	$_image = $this->db->get_where('user',$where)->row();
																	$query = $this->db->update('user',$data,$where);
																	if($query){
																		unlink("assets/frontend/user/".$_image->user_gambar);
																	}
																}

					redirect('aspanel/profil');
				}else{
				if ($this->session->level=='1'){
							 $proses = $this->As_m->edit('user', array('id_session' => $id))->row_array();
					}else{
							$proses = $this->As_m->edit('user', array('id_session' => $id))->row_array();
					}
					$data = array('rows' => $proses);
					$data['post'] = $this->As_m->view_ordering('user_detail','id_user','ASC');
					if ($this->session->level=='1'){
							$data['recordall'] = $this->Crud_m->view_where_ordering('user',array('user_status'=>'active'),'id_user','DESC');
					}else{
					}
					$data['records'] = $this->Crud_m->view_ordering('user_level','user_level_id','DESC');
					$data['record_status'] = $this->Crud_m->view_ordering('user_status','user_status_id','DESC');
					$this->load->view('backend/profil/profiledit', $data);
					}
		}
	public function user_storage_bin()
		{
				cek_session_akses ($this->session->id_session);

						if ($this->session->level=='1'){
								$data['recordall'] = $this->Crud_m->view_where_ordering('user',array('user_status'=>'2'),'id_user','DESC');
						}else{
								$data['recordall'] = $this->Crud_m->view_where_ordering('user',array('user_status'=>'2'),'id_user','DESC');
						}
						$this->load->view('backend/profil/profilblock', $data);
			}
	public function user_delete()
		{
					cek_session_akses ('profil',$this->session->id_session);
					$id = $this->uri->segment(3);
					$_id = $this->db->get_where('user',['id_session' => $id])->row();
					 $query = $this->db->delete('user',['id_session'=>$id]);
				 	if($query){
									 unlink("./bahan/foto_profil/".$_id->user_gambar);
				 }
					redirect('aspanel/user_storage_bin');
		}

	function identitaswebsite()
		{

		if (isset($_POST['submit'])){
					$config['upload_path'] = 'assets/frontend/campur/';
					$config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
					$this->upload->initialize($config);
					$this->upload->do_upload('logo');
					$hasillogo=$this->upload->data();
					$config['image_library']='gd2';
					$config['source_image'] = './assets/frontend/campur/'.$hasillogo['file_name'];
					$config['create_thumb']= FALSE;
					$config['maintain_ratio']= FALSE;
					$config['quality']= '100%';
					$config['new_image']= './assets/frontend/campur/'.$hasillogo['file_name'];
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();

					$this->upload->initialize($config);
					$this->upload->do_upload('favicon');
					$hasilfav=$this->upload->data();
					$config['image_library']='gd2';
					$config['source_image'] = './assets/frontend/campur/'.$hasilfav['file_name'];
					$config['create_thumb']= FALSE;
					$config['maintain_ratio']= FALSE;
					$config['quality']= '50%';
					$config['width']= 30;
					$config['height']= 30;
					$config['new_image']= './assets/frontend/campur/'.$hasilfav['file_name'];
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();

          if ($hasilfav['file_name']=='' && $hasillogo['file_name']==''){
            	$data = array(
            	                	'nama_website'=>$this->db->escape_str($this->input->post('nama_website')),
                                'email'=>$this->db->escape_str($this->input->post('email')),
                                'url'=>$this->db->escape_str($this->input->post('url')),
                                'facebook'=>$this->input->post('facebook'),
                                'instagram'=>$this->input->post('instagram'),
                                'youtube'=>$this->input->post('youtube'),
                                'no_telp'=>$this->db->escape_str($this->input->post('no_telp')),
                                'slogan'=>$this->input->post('slogan'),
                                'alamat'=>$this->input->post('alamat'),
																'whatsapp'=>$this->input->post('whatsapp'),
                                'meta_deskripsi'=>$this->input->post('meta_deskripsi'),
																'meta_keyword'=>$this->input->post('meta_keyword'),
																'seo'=>$this->input->post('seo'),
																'analytics'=>$this->input->post('analytics'),
																'pixel'=>$this->input->post('pixel'),
                                'maps'=>$this->input->post('maps'),
															);
																$where = array('id_identitas' => $this->input->post('id_identitas'));
    														$query = $this->db->update('identitas',$data,$where);
            }else if ($hasillogo['file_name']==''){
            	$data = array(
																'nama_website'=>$this->db->escape_str($this->input->post('nama_website')),
																'email'=>$this->db->escape_str($this->input->post('email')),
																'url'=>$this->db->escape_str($this->input->post('url')),
																'facebook'=>$this->input->post('facebook'),
																'instagram'=>$this->input->post('instagram'),
																'youtube'=>$this->input->post('youtube'),
																'no_telp'=>$this->db->escape_str($this->input->post('no_telp')),
																'slogan'=>$this->input->post('slogan'),
																'alamat'=>$this->input->post('alamat'),
																'whatsapp'=>$this->input->post('whatsapp'),
																'meta_deskripsi'=>$this->input->post('meta_deskripsi'),
																'meta_keyword'=>$this->input->post('meta_keyword'),
																'seo'=>$this->input->post('seo'),
																'analytics'=>$this->input->post('analytics'),
																'pixel'=>$this->input->post('pixel'),
																'maps'=>$this->input->post('maps'),
                                'favicon'=>$hasilfav['file_name']);
																$where = array('id_identitas' => $this->input->post('id_identitas'));
						    								$_image = $this->db->get_where('identitas',$where)->row();
						    								$query = $this->db->update('identitas',$data,$where);
						    								if($query){
						    					                unlink("assets/frontend/campur/".$_image->favicon);
						    					                }
            }else if ($hasilfav['file_name']==''){
            	$data = array(
																'nama_website'=>$this->db->escape_str($this->input->post('nama_website')),
																'email'=>$this->db->escape_str($this->input->post('email')),
																'url'=>$this->db->escape_str($this->input->post('url')),
																'facebook'=>$this->input->post('facebook'),
																'instagram'=>$this->input->post('instagram'),
																'youtube'=>$this->input->post('youtube'),
																'no_telp'=>$this->db->escape_str($this->input->post('no_telp')),
																'slogan'=>$this->input->post('slogan'),
																'alamat'=>$this->input->post('alamat'),
																'whatsapp'=>$this->input->post('whatsapp'),
																'meta_deskripsi'=>$this->input->post('meta_deskripsi'),
																'meta_keyword'=>$this->input->post('meta_keyword'),
																'seo'=>$this->input->post('seo'),
																'analytics'=>$this->input->post('analytics'),
																'pixel'=>$this->input->post('pixel'),
																'maps'=>$this->input->post('maps'),
                                'logo'=>$hasillogo['file_name']);
																$where = array('id_identitas' => $this->input->post('id_identitas'));
						    								$_image = $this->db->get_where('identitas',$where)->row();
						    								$query = $this->db->update('identitas',$data,$where);
						    								if($query){
						    					                unlink("assets/frontend/campur/".$_image->logo);
						    					                }
            }else{
            	$data = array(
																'nama_website'=>$this->db->escape_str($this->input->post('nama_website')),
																'email'=>$this->db->escape_str($this->input->post('email')),
																'url'=>$this->db->escape_str($this->input->post('url')),
																'facebook'=>$this->input->post('facebook'),
																'instagram'=>$this->input->post('instagram'),
																'youtube'=>$this->input->post('youtube'),
																'no_telp'=>$this->db->escape_str($this->input->post('no_telp')),
																'slogan'=>$this->input->post('slogan'),
																'alamat'=>$this->input->post('alamat'),
																'whatsapp'=>$this->input->post('whatsapp'),
																'meta_deskripsi'=>$this->input->post('meta_deskripsi'),
																'meta_keyword'=>$this->input->post('meta_keyword'),
																'seo'=>$this->input->post('seo'),
																'analytics'=>$this->input->post('analytics'),
																'pixel'=>$this->input->post('pixel'),
																'maps'=>$this->input->post('maps'),
																'favicon'=>$hasilfav['file_name'],
                                'logo'=>$hasillogo['file_name']);
																$where = array('id_identitas' => $this->input->post('id_identitas'));
						    								$_image = $this->db->get_where('identitas',$where)->row();
						    								$query = $this->db->update('identitas',$data,$where);
						    								if($query){
						    					                unlink("assets/frontend/campur/".$_image->favicon);
																					unlink("assets/frontend/campur/".$_image->logo);
						    					                }
            }
			redirect('aspanel/identitaswebsite');
		}else{

			$proses = $this->As_m->edit('identitas', array('id_identitas' => 1))->row_array();
			$data = array('record' => $proses);
			if ($this->session->level=='1'){
				cek_session_akses('identitaswebsite',$this->session->id_session);
				$data['record_company_sub'] = $this->Crud_m->view_where_ordering('user_company',array('user_company_kategori'=>'2'),'user_company_id','ASC');
				}elseif ($this->session->level=='2'){
					cek_session_akses_admin('identitaswebsite',$this->session->id_session);
					$data['record_company_sub'] = $this->Crud_m->view_where_ordering('user_company',array('user_company_kategori'=>'2'),'user_company_id','ASC');
				}else{
					redirect('aspanel/home');
				}
			$this->load->view('backend/identitas/views', $data);
		}
		}


	public function logactivity()
		{
			if ($this->session->level=='1'){
				cek_session_akses('activities',$this->session->id_session);
				$data['record'] = $this->Crud_m->view_join_ordering2('log_activity','user','log_activity_user_id','id_user','log_activity_id','DESC');
				}elseif ($this->session->level=='2'){
					cek_session_akses_admin('activities',$this->session->id_session);
					$data['record'] = $this->Crud_m->view_join_ordering2('log_activity','user','log_activity_user_id','id_user','log_activity_id','DESC');
				}else{
					cek_session_akses_level_3('activities',$this->session->id_session);
					redirect('aspanel/profil');
				}
				$this->load->view('backend/log/v_daftar', $data);
			}

	public function get_revenue_data()
	{
	    $date_now = date('Y-m-d'); // Current date
	    $date_start_of_month = date('Y-m-01'); // Start of the current month
	    $date_start_of_last_month = date('Y-m-01', strtotime('first day of last month')); // Start of last month
	    $date_end_of_last_month = date('Y-m-t', strtotime('last month')); // End of last month

	    // Revenue bulan ini
	    $revenue_bulan_ini = $this->db->select_sum('total_paid')
	        ->where('DATE(date) >=', $date_start_of_month)
	        ->where('DATE(date) <=', $date_now)
	        ->where('status', 'Paid')
	        ->get('payment')
	        ->row();

	    // Revenue bulan lalu
	    $revenue_bulan_lalu = $this->db->select_sum('total_paid')
	        ->where('DATE(date) >=', $date_start_of_last_month)
	        ->where('DATE(date) <=', $date_end_of_last_month)
	        ->where('status', 'Paid')
	        ->get('payment')
	        ->row();

	    // Total revenue all
	    $total_revenue_all = $this->db->select_sum('total_paid')
	    	->where('status', 'Paid')
	        ->get('payment')
	        ->row();

	    $total_pending_revenue = $this->db->select_sum('total_paid')
	    	->where('status', 'Pending')
	        ->get('payment')
	        ->row();

	    $total_project_acc = $this->db->select_sum('nominal_transaksi')
	        ->get('project_acc')
	        ->row();

	    $total_net_revenue = $total_revenue_all->total_paid - $total_project_acc->nominal_transaksi;


	    $expense_bulan_ini = $this->db->select_sum('nominal_transaksi')
	    	->where('DATE(tanggal_transaksi) >=', $date_start_of_month)
	        ->where('DATE(tanggal_transaksi) <=', $date_now)
	        ->get('operational_acc')
	        ->row();

	    $expense_bulan_lalu = $this->db->select_sum('nominal_transaksi')
	    	->where('DATE(tanggal_transaksi) >=', $date_start_of_last_month)
	        ->where('DATE(tanggal_transaksi) <=', $date_end_of_last_month)
	        ->get('operational_acc')
	        ->row();

	    $total_expense_all = $this->db->select_sum('nominal_transaksi')
	        ->get('operational_acc')
	        ->row();

	    $total_other = $this->db->select_sum('nominal_transaksi')
	        ->get('other_acc')
	        ->row();

	    $total_gross_profit = ($total_revenue_all->total_paid - $total_project_acc->nominal_transaksi) - $total_expense_all->nominal_transaksi;

	    $total_net_profit = $total_revenue_all->total_paid - $total_project_acc->nominal_transaksi - $total_expense_all->nominal_transaksi - $total_other->nominal_transaksi ;

	    // Hitung persentase perubahan revenue
	    $percent_change = null;
	    if ($revenue_bulan_ini && $revenue_bulan_lalu && $revenue_bulan_lalu->total_paid != 0) {
	        $percent_change = (($revenue_bulan_ini->total_paid - $revenue_bulan_lalu->total_paid) / $revenue_bulan_lalu->total_paid) * 100;
	    }

	    echo json_encode([
	        'revenue_bulan_ini' => $revenue_bulan_ini->total_paid ?? 0,
	        'revenue_bulan_lalu' => $revenue_bulan_lalu->total_paid ?? 0,
	        'total_revenue_all' => $total_revenue_all->total_paid ?? 0,
	        'total_pending_revenue' => $total_pending_revenue->total_paid ?? 0,
	        'total_project_acc' => $total_project_acc->nominal_transaksi ?? 0,
	        'total_net_revenue' => $total_net_revenue,
	        'expense_bulan_ini' => $expense_bulan_ini->nominal_transaksi ?? 0,
	        'expense_bulan_lalu' => $expense_bulan_lalu->nominal_transaksi ?? 0,
	        'total_expense_all' => $total_expense_all->nominal_transaksi ?? 0,
	        'total_gross_profit' => $total_gross_profit,
	        'total_net_profit' => $total_net_profit,
	        'percent_change' => $percent_change
	    ]);
	}

	public function client_lebih_lengkap() {
	    $clients_per_year = $this->Clients_model->get_clients_per_year();
	    $data['clients_per_year'] = $clients_per_year;
	    $this->load->view('backend/client_lebih_lengkap', $data);
	}

	public function client_lebih_lengkap_detail($year, $month) {
	    // Format bulan dan tahun
	    $formatted_month = sprintf('%04d-%02d', $year, $month);

	    // Ambil data client berdasarkan bulan dan tahun
	    $data['clients'] = $this->Clients_model->get_clients_by_month($formatted_month);

	    // Tambahkan informasi bulan dan tahun ke data
	    $data['year'] = $year;
	    $data['month'] = $month;

	    // Load view untuk menampilkan detail client
	    $this->load->view('backend/client_lebih_lengkap_detail', $data);
	}

	public function revenue_lebih_lengkap() {
	    $this->load->model('Payment_model');
	    $data['revenue_per_year'] = $this->Payment_model->get_revenue_per_year();
	    $this->load->view('backend/revenue_lebih_lengkap', $data);
	}

	public function revenue_lebih_lengkap_detail($year, $month) {
	    $this->load->model('Payment_model');
	    $data['revenues'] = $this->Payment_model->get_paid_revenues_with_transaction_id($month, $year);
	    $data['month'] = $month;
	    $data['year'] = $year;
	    $this->load->view('backend/revenue_lebih_lengkap_detail', $data);
	}


	public function expense_lebih_lengkap() {
		if ($this->session->level == '1') {
			cek_session_akses_developer('panel', $this->session->id_session);	    
		    $data['expense_per_year'] = $this->Payment_model->get_expense_per_year();
		    $this->load->view('backend/expense_lebih_lengkap', $data);
		}else if ($this->session->level == '3') {
			cek_session_akses_staff_accounting('panel', $this->session->id_session);
			$data['expense_per_year'] = $this->Payment_model->get_expense_per_year();
		    $this->load->view('backend/expense_lebih_lengkap', $data);
		}else{
				redirect(base_url());
		}
	}

	public function expense_lebih_lengkap_detail($year, $month) {
		if ($this->session->level == '1') {
			cek_session_akses_developer('panel', $this->session->id_session);
			$data['expense'] = $this->Payment_model->get_paid_expense_with_transaction_id($month, $year);
	    	$data['month'] = $month;
	    	$data['year'] = $year;
	    	$this->load->view('backend/expense_lebih_lengkap_detail', $data);
		}else if ($this->session->level == '3') {
			cek_session_akses_staff_accounting('panel', $this->session->id_session);
			$data['expense'] = $this->Payment_model->get_paid_expense_with_transaction_id($month, $year);
	    	$data['month'] = $month;
	    	$data['year'] = $year;
	    	$this->load->view('backend/expense_lebih_lengkap_detail', $data);
		}else{
				redirect(base_url());
		}

	    
	}



	public function get_expense_data()
	{
	      
	    $total_expense = $this->db->select_sum('nominal_transaksi')
	        ->get('operational_acc')
	        ->row();

	    echo json_encode([
	        'total_expense' => $total_expense->nominal_transaksi ?? 0
	        
	    ]);
	}
	
}
