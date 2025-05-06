<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }
  public function logout(){
    $this->session->sess_destroy();
    redirect(base_url('login'));
  }
  public function login()	{
    $data['title'] = 'Sign In';
    $data['identitas']= $this->Crud_m->get_by_id_identitas($id='1');
    $this->form_validation->set_rules('username','','trim|required', array('trim' => '', 'required' => '**Fill Your Username '));
    $this->form_validation->set_rules('password','','trim|required', array('trim' => '', 'required' => '**Fill Your Password'));
    if($this->form_validation->run() === FALSE){
        $this->load->view('backend/v_login', $data);
    } else {
      if ($this->agent->is_browser())
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

        $modul = 'Login';
        $username = $this->input->post('username');
        $password = sha1($this->input->post('password'));
        $cek = $this->As_m->cek_login($username,$password,'user');
          $row = $cek->row_array();
          $total = $cek->num_rows();
          if ($total > 0){
            $this->session->set_userdata(
              array(
                'username'=>$row['username'],
                'level'=>$row['level'],
                'id_user'=>$row['id_user'],
                'id_session'=>$row['id_session']));

            $this->session->set_flashdata('user_loggedin','Selamat Anda Berhasil Login');
            $id = array('id_session' => $this->session->id_session);
            $data = array('user_login_status'=>'online');
            $this->db->update('user', $data, $id);

            $ip = $this->input->ip_address();
            $location = get_location_from_ip($ip);
            $ip_with_location = $ip . "<br>(" . $location . ")";
    
            $data2 = array (
              'log_activity_user_id'=>$row['id_session'],
              'log_activity_modul' => 'Login',
              'log_activity_status' => 'Login',
              'log_activity_platform'=> $agent,
              'log_activity_ip'=> $ip_with_location
            );
            $this->db->insert('log_activity', $data2);
            redirect('panel');
          }else {
            // Set message
            $this->session->set_flashdata('login_failed', 'username and password you entered is unregisted');

            redirect(base_url('login'));
        }
    }
		}
  public function datapengantinmoeslem(){
        $this->form_validation->set_rules('cpp_namaleng','','trim|required', array('trim' => '', 'required' => 'nama lengkap pengantin pria belum diisi'));


        $this->form_validation->set_rules('email','','trim|required|valid_email|is_unique[user.email]', array('trim' => '', 'required' => 'Email belum diisi','is_unique' => 'Email telah digunakan, silahkan gunakan email lain.'));
        $this->form_validation->set_rules('password','','trim|required', array('trim' => '', 'required' => 'Password belum diisi'));
        $this->form_validation->set_rules('konfirmpassword','','trim|required|matches[password]', array('trim' => '', 'required' => 'Konfirmasi Password belum diisi','matches'=>'Password tidak sama! Cek kembali password Anda'));
       
        if($this->form_validation->run() != false){
          if (isset($_POST['submit']))
          {
            $username = $this->input->post('username');
            $email = $this->input->post('email');            
            $password = sha1($this->input->post('password'));
            $cek = $this->Crud_m->cek_register($username,$email,'user');
              $total = $cek->num_rows();
            if ($total > 0)
              {
              $data['title'] = 'Periksa kembali email dan password Anda!';
              redirect(site_url('daftar'));
              }else{
                $saltid   = md5($email);
                $aktivasi   = 0;
                $data = array('username'=>$this->db->escape_str($this->input->post('username')),
                                'password'=>sha1($this->input->post('password')),
                                'email'=>$this->db->escape_str($this->input->post('email')),
                                'user_status'=> $aktivasi,
                                'user_post_hari'=>hari_ini(date('w')),
                                'user_post_tanggal'=>date('Y-m-d'),
                                'user_post_jam'=>date('H:i:s'),
                                'level'=>'5',
                                'user_stat'=>'verified',
                                'id_session'=>md5($this->input->post('email')).'-'.date('YmdHis'));
                $id_user_detail = $this->Crud_m->tambah_user($data);
                $data_user_detail = array('id_user' => $id_user_detail);
                if($this->Crud_m->tambah_user_detail($data_user_detail))
                {
                  if($this->sendemail($email,$saltid,$username))
                                {
                                    $this->session->set_flashdata('msg','<div class="alert bg-5 text-center">Segera lakukan aktivasi akun mantenbaru dari email anda. Harap merefresh pesan masuk di email Anda.</div>');
                                    redirect(base_url('daftar')
                                    );
                            }else
                                {
                                  $this->session->set_flashdata('msg','<div class="alert bg-5 text-center">Email Verifikasi tidak terkirim</div>');
                                  redirect(base_url('daftar'));
                                }
                }
                $data['title'] = 'Sukses mendaftar';
                $data['identitas']= $this->Crud_m->get_by_id_identitas($id='1');
                $this->load->view('fronts/user/v_datapengantinmoeslem',$data);

                }
            }
            else
            {
              $data['identitas']= $this->Crud_m->get_by_id_identitas($id='1');
              $this->load->view('fronts/user/v_datapengantinmoeslem',$data);
            }

          } else{
          $data['title'] = 'Ops.. Masih ada yang kurang. Silahkan dicek kembali.';
          $data['identitas']= $this->Crud_m->get_by_id_identitas($id='1');
          $this->load->view('fronts/user/v_datapengantinmoeslem',$data);
        }
  }
  public function sendemail($email,$saltid,$username){
        // configure the email setting

            $config = array (
              'protocol'    => 'smtp',
              'smtp_host'   => 'mail.mantenbaru.com',
              'smtp_port'   => '465',
              'smtp_user'   => 'activation@mantenbaru.com',
              'smtp_pass'   => 'dh4wy3p1c',
              'mailtype'    => 'html',
              'charset'     => 'iso-8859-1',
              'wordwrap'    => TRUE
              );           
            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");
            $url = base_url()."user/confirmation/".$saltid;
            $this->email->from('activation@mantenbaru.com', 'Mantenbaru');
            $this->email->to($email);
            $this->email->subject('Verifikasi Email - Mantenbaru');
            $message = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'></head><body><p><strong>Hallo, $username</strong></p><p>Hanya tinggal 1 langkah lagi untuk bisa bergabung dengan Mantenbaru.</p><p>Silahkan mengklik link di bawah ini</p>".$url."<br/><p>Salam Hangat</p><p>Mantenbaru Team</p></body></html>";
            $this->email->message($message);
            return $this->email->send();
      }
  public function confirmation($key){
          if($this->Crud_m->verifyemail($key))
          {
            $this->session->set_flashdata('msg','<div class="alert bg-3 text-center">Selamat Anda telah Resmi Bergabung! Silahkan Login.</div>');
            redirect(base_url('login'));
          }	else {
            $this->session->set_flashdata('msg','<div class="alert bg-3 text-center">Ops. Anda gagal, silahkan coba lagi.</div>');
            redirect(base_url('login'));
          }
      }




}
