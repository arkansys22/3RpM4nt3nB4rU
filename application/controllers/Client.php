<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  public function logout()
  {
    $this->session->sess_destroy();
    redirect(base_url('client/login'));
  }

  public function login()
  {
    $data['title'] = 'Sign In';
    $data['identitas'] = $this->Crud_m->get_by_id_identitas($id = '1');
    $this->form_validation->set_rules('username', '', 'trim|required', array('trim' => '', 'required' => '**Fill Your Username '));
    $this->form_validation->set_rules('password', '', 'trim|required', array('trim' => '', 'required' => '**Fill Your Password'));
    if ($this->form_validation->run() === FALSE) {
      $id_session = $this->session->userdata('id_session');
      $data['id_session'] = $id_session;
      $this->load->view('backend/v_login_client', $data);
    } else {
      if ($this->agent->is_browser()) {
        $agent = 'Desktop ' . $this->agent->browser() . ' ' . $this->agent->version();
      } elseif ($this->agent->is_robot()) {
        $agent = $this->agent->robot();
      } elseif ($this->agent->is_mobile()) {
        $agent = 'Mobile' . $this->agent->mobile() . '' . $this->agent->version();
      } else {
        $agent = 'Unidentified User Agent';
      }

      $modul = 'Login';
      $username = $this->input->post('username');
      $input_password = $this->input->post('password');
      $cek = $this->As_m->get_user_by_username($username, 'user'); // Change table to 'user'
      $row = ($cek->num_rows() > 0) ? $cek->row_array() : null;

      $password_ok = $row && (
          password_verify($input_password, $row['password'])
          || $row['password'] === sha1($input_password)
      );

      if ($password_ok) {

          // Legacy sha1 hash matched — transparently upgrade it to bcrypt.
          if (strlen($row['password']) !== 60) {
              $this->db->where('id_user', $row['id_user'])
                       ->update('user', ['password' => password_hash($input_password, PASSWORD_DEFAULT)]);
          }
        $this->session->set_userdata(
          array(
            'username' => $row['username'],
            'level' => $row['level'],
            'id_user' => $row['id_user'],
            'id_session' => $row['client_idsession'] // Use client_idsession
          )
        );

        $this->session->set_flashdata('user_loggedin', 'Selamat Anda Berhasil Login');
        $id = array('id_session' => $this->session->userdata('id_session'));
        $data = array('user_login_status' => 'online');
        $this->db->update('user', $data, $id); // Change table to 'user'

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data2 = array(
          'log_activity_user_id' => $row['client_idsession'], // Use client_idsession
          'log_activity_modul' => 'Login',
          'log_activity_status' => 'Login',
          'log_activity_platform' => $agent,
          'log_activity_waktu' => date('Y-m-d H:i:s'),
          'log_activity_ip'=> $ip_with_location
        );
        $this->db->insert('log_activity', $data2);
        $id_session = $this->session->userdata('id_session'); // Ensure id_session is set
        redirect('clients/c_lihat/'.$id_session);
      } else {
        // Set message
        $this->session->set_flashdata('login_failed', 'username and password you entered is unregisted');
        redirect(base_url('client/login'));
      }
    }
  }

}
