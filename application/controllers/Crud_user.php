<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class crud_user extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Users2_model');
        $this->load->helper('url');
    }

    // Level yang diposting harus ada di user_level (form lama / level yang baru dihapus tidak boleh dipasang ke user)
    private function level_ada($level)
    {
        return $level !== null && $level !== '' && $this->db->query('SELECT 1 FROM user_level WHERE user_level_id = ?', [$level])->num_rows() > 0;
    }

    // Level akun yang sebenarnya dari sesi yang sedang login (level baru buatan Developer: id level barunya)
    private function level_akun_sekarang()
    {
        return (string) ($this->session->userdata('level_asli') ?: $this->session->level);
    }

    // Apakah user yang sedang login boleh mengubah data user target?
    //  - Developer      : semua user
    //  - Administrator  : semua user kecuali Developer
    //  - Staff Admin    : semua user kecuali Developer, Administrator, dan level baru yang berinduk keduanya
    private function target_boleh($id_session)
    {
        $row = $this->Users2_model->get_users_by_session($id_session);
        if (!$row) {
            return false;
        }
        $lv = (string) $this->session->level;
        if ($lv === '1') {
            return true;
        }
        if ($lv === '2') {
            return (string) $row->level !== '1';
        }
        if ($lv === '4') {
            $this->load->model('Hak_akses_model');
            return !in_array($this->Hak_akses_model->dasar_level($row->level), ['1', '2'], true);
        }
        return false;
    }

    // Level terendah yang boleh dipilih akun yang sedang login saat mengubah level user: null = semua level (Developer),
    // 2 = Administrator (level 2-12), 3 = Staff Admin (level 3-12, bukan Developer/Administrator). false = tidak boleh ubah level.
    // Level baru buatan Developer (id >= 13) hanya bisa diberikan Developer. Yang dihitung level ASLI akun (bukan level efektif
    // sementara dari Hak Akses, dan bukan level baru yang berinduk Administrator/Staff Admin).
    private function level_min_pilihan()
    {
        switch ($this->level_akun_sekarang()) {
            case '1': return null;
            case '2': return 2;
            case '4': return 3;
            default: return false;
        }
    }

    // Apakah level user target boleh diubah oleh akun yang sedang login? Tidak pernah untuk akun sendiri. Developer: semua. Administrator & Staff Admin: hanya
    // kalau level target sekarang berada di rentang yang boleh mereka pilih (target Developer/Administrator/level baru: tidak).
    private function level_bisa_diubah($row)
    {
        if (!$row || ($this->session->id_session && (string) $row->id_session === (string) $this->session->id_session)) {
            return false; // akun sendiri: level tidak bisa diubah
        }
        $min = $this->level_min_pilihan();
        if ($min === null) {
            return true;
        }
        return $min !== false && (int) $row->level >= $min && (int) $row->level <= 12;
    }

    // Pilihan level untuk form edit: Developer semua level, Administrator level 2-12, Staff Admin level 3-12
    private function level_opsi()
    {
        $min = $this->level_min_pilihan();
        if ($min === null) {
            return $this->Crud_m->view_ordering('user_level', 'user_level_id', 'asc');
        }
        return $this->db->query('SELECT * FROM user_level WHERE user_level_id BETWEEN ? AND 12 ORDER BY user_level_id ASC', [(int) $min])->result_array();
    }

    public function index() {
        if ($this->session->level=='1'){
            cek_session_akses_developer('user',$this->session->id_session);
            $data['users'] = $this->urutkan_status_online($this->Users2_model->get_all_user());
            $this->load->view('user/index', $data);

        }else if ($this->session->level=='2'){
            cek_session_akses_administrator('user',$this->session->id_session);
            $data['users'] = $this->urutkan_status_online($this->Users2_model->get_all_user_non_developer());
            $this->load->view('user/index', $data);

        }else if ($this->session->level=='4'){
            cek_session_akses_staff_admin('user',$this->session->id_session);
            $data['users'] = $this->urutkan_status_online($this->Users2_model->get_all_user_admin());
            $this->load->view('user/index', $data);

        }else{
                redirect(base_url());
                }
    }

    // Urutkan daftar user berdasarkan status online-nya: Online dulu, lalu
    // yang "Terakhir Online" (dari aktivitas paling baru), baru yang "Belum
    // Pernah Login" paling akhir. Pakai status_online() yang sama dipakai
    // buat nampilin badge di view (customs_helper.php), biar urutan & label
    // yang ditampilkan selalu konsisten.
    private function urutkan_status_online($users) {
        usort($users, function ($a, $b) {
            $priority = function ($u) {
                $status = status_online($u->user_login_status, $u->last_activity);
                if ($status['is_online']) return 0;      // Online
                if (!empty($u->last_activity)) return 1; // Terakhir Online
                return 2;                                 // Belum Pernah Login
            };

            $pa = $priority($a);
            $pb = $priority($b);
            if ($pa !== $pb) {
                return $pa <=> $pb;
            }

            // Dalam grup yang sama, aktivitas paling baru duluan.
            $ta = !empty($a->last_activity) ? strtotime($a->last_activity) : 0;
            $tb = !empty($b->last_activity) ? strtotime($b->last_activity) : 0;
            return $tb <=> $ta;
        });

        return $users;
    }


    public function create() {
        if ($this->session->level=='1'){
            cek_session_akses_developer('user',$this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering('user_level','user_level_id','asc');
            $this->load->view('user/create', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('user',$this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering_admin('user_level','user_level_id','asc');
            $this->load->view('user/create', $data);

        }else{
                redirect(base_url());
                }
    }

    public function store() {
        // Hanya Developer & Staff Admin; Staff Admin hanya boleh membuat level 3-8 (sama dengan pilihan di form)
        if (!in_array($this->session->level, ['1', '4'], true)
            || (!in_array($this->input->post('level'), ['3', '4', '5', '6', '7', '8'], true) && $this->session->level !== '1')) {
            $this->session->set_flashdata('Error', 'Anda tidak berhak membuat pengguna dengan level tersebut.');
            redirect('user');
            return;
        }
        if (!$this->level_ada($this->input->post('level'))) {
            $this->session->set_flashdata('Error', 'Level tidak ditemukan (mungkin sudah dihapus). Muat ulang halaman lalu pilih level lagi.');
            redirect('user');
            return;
        }
        $id_session2 = sha1(uniqid());       
        
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

        $data = array(
            'id_session'    => $id_session2,
            'username'  => $this->input->post('username'),
            'nama'  => $this->input->post('nama'),
            'email'        => $this->input->post('email'),            
            'password'    => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'level'    => $this->input->post('level'),
            'user_stat' => 'Publish',
            'user_login_status' => 'Offline',
            'create_by'     => $this->session->id_session,
            'user_post_hari'=>hari_ini(date('w')),
            'user_post_tanggal'=>date('Y-m-d'),
            'user_post_jam'=>date('H:i:s')
        );
    
        // Insert ke tabel projects
        $this->Users2_model->insert_users($data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(
            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/create',
            'log_activity_document_no' => $id_session2,
            'log_activity_status' => 'Tambah Pengguna',
            'log_activity_platform'=> $agent,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_ip'=> $ip_with_location            
        );
        $this->Users2_model->insert_log_activity($data_log);   
    
        $this->session->set_flashdata('Success', 'Pengguna berhasil dibuat');
        redirect('user');
    }

    public function lihat($id_session) {
        if ($this->session->level=='1'){
            cek_session_akses_developer('user',$this->session->id_session);
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $data['logactivity'] = $this->Users2_model->get_logactivity_by_session($id_session);
            $this->load->view('user/lihat', $data);


        }else if($this->session->level=='2'){
            cek_session_akses_administrator('user',$this->session->id_session);
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $data['logactivity'] = $this->Users2_model->get_logactivity_by_session($id_session);
            $this->load->view('user/lihat', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('user',$this->session->id_session);
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $data['logactivity'] = $this->Users2_model->get_logactivity_by_session($id_session);
            $this->load->view('user/lihat', $data);

        }else if($this->session->level=='7'){
            cek_session_akses_staff('user',$this->session->id_session);
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $data['logactivity'] = $this->Users2_model->get_logactivity_by_session($id_session);
            $this->load->view('user/lihat', $data);

        }else{
                    redirect(base_url());
                }
    }

    // user/edit/<id>: form edit user -- kolom Level TIDAK bisa diubah (dipakai untuk mengedit akun sendiri)
    public function edit($id_session) {
        $this->form_edit($id_session, false);
    }

    // user/editother/<id>: form edit user LAIN -- kolom Level bisa diubah sesuai role (Developer / Administrator / Staff Admin,
    // lihat level_bisa_diubah()). Untuk akun sendiri dialihkan ke user/edit.
    public function editother($id_session) {
        if ($this->session->id_session && (string) $id_session === (string) $this->session->id_session) {
            redirect('user/edit/' . $id_session . '?view_type=edit');
            return;
        }
        $this->form_edit($id_session, true);
    }

    private function form_edit($id_session, $boleh_ubah_level) {
        if ($this->session->level == '1') {
            cek_session_akses_developer('user', $this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering('user_level', 'user_level_id', 'asc');
            $data['clients'] = $this->Crud_m->view_ordering('clients', 'id', 'asc');
            $data['partner'] = $this->Crud_m->view_ordering('partner', 'id', 'asc'); // Fetch partner
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            $data['level_bisa_diubah'] = $boleh_ubah_level && $this->level_bisa_diubah($data['pc']);

            $view_type = $this->input->get('view_type'); // Get 'view_type' from query string
            if ($view_type === 'edit_staff') {
                // Fetch specific crew based on crews_idsession
                $crews_idsession = $data['pc']->crews_idsession;
                $data['crews'] = $this->Users2_model->get_crew_by_id_session($crews_idsession);
                $this->load->view('user/edit_staff', $data);
            } else {
                // Fetch all crews for user/edit
                $data['crews'] = $this->Crud_m->view_ordering('crews', 'id', 'asc');
                $this->load->view('user/edit', $data);
            }
        } else if ($this->session->level == '2' || $this->session->level == '4') {
            if ($this->session->level == '2') {
                cek_session_akses_administrator('user', $this->session->id_session);
            } else {
                cek_session_akses_staff_admin('user', $this->session->id_session);
            }
            if (!$this->target_boleh($id_session)) {
                $this->session->set_flashdata('Error', 'Anda tidak berhak mengubah data pengguna ini.');
                redirect('user');
                return;
            }
            $data['clients'] = $this->Crud_m->view_ordering('clients', 'id', 'asc');
            $data['partner'] = $this->Crud_m->view_ordering('partner', 'id', 'asc'); // Fetch partner
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);
            // Level bisa diubah Developer/Administrator/Staff Admin dalam rentang masing-masing; selain itu kolom level hanya tampilan (butuh daftar lengkap untuk nama level)
            $data['level_bisa_diubah'] = $boleh_ubah_level && $this->level_bisa_diubah($data['pc']);
            $data['level'] = $data['level_bisa_diubah'] ? $this->level_opsi() : $this->Crud_m->view_ordering('user_level', 'user_level_id', 'asc');

            $view_type = $this->input->get('view_type');
            if ($view_type === 'edit_staff') {
                // Fetch specific crew based on crews_idsession
                $crews_idsession = $data['pc']->crews_idsession;
                $data['crews'] = $this->Users2_model->get_crew_by_id_session($crews_idsession);
                $this->load->view('user/edit_staff', $data);
            } else {
                // Fetch all crews for user/edit
                $data['crews'] = $this->Crud_m->view_ordering('crews', 'id', 'asc');
                $this->load->view('user/edit', $data);
            }
        }else if($this->session->level=='7'){
            cek_session_akses_staff('user',$this->session->id_session);
            $data['level'] = $this->Crud_m->view_ordering('user_level','user_level_id','asc');
            $data['clients'] = $this->Crud_m->view_ordering('clients','id','asc');
            $data['pc'] = $this->Users2_model->get_users_by_session($id_session);

            // Fetch crews_idsession from user table
            $crews_idsession = $data['pc']->crews_idsession;

            // Fetch crews data using crews_idsession
            $data['crews'] = $this->Users2_model->get_crew_by_id_session($crews_idsession);

            $this->load->view('user/edit_staff', $data); // Load edit_staff view

        } else {
            redirect(base_url());
        }
    }

    public function update($id_session) {
        // Form lengkap untuk Developer, Administrator & Staff Admin (sama dengan halaman edit). Administrator tidak boleh mengubah
        // Developer; Staff Admin tidak boleh mengubah Developer/Administrator. LEVEL user hanya bisa diubah Developer (semua level),
        // Administrator (level 2-12) dan Staff Admin (level 3-12); nilai level dari POST diabaikan kalau tidak boleh.
        if (!in_array($this->session->level, ['1', '2', '4'], true)
            || ($this->session->level !== '1' && !$this->target_boleh($id_session))) {
            $this->session->set_flashdata('Error', 'Anda tidak berhak mengubah data pengguna ini.');
            redirect('user');
            return;
        }
        $level_baru = null;
        if ($this->level_bisa_diubah($this->Users2_model->get_users_by_session($id_session))) {
            $kiriman = $this->input->post('level');
            if ($kiriman !== null && $kiriman !== '') {
                $level_sah = $this->level_ada($kiriman)
                    && ($this->level_min_pilihan() === null || ((int) $kiriman >= $this->level_min_pilihan() && (int) $kiriman <= 12));
                if (!$level_sah) {
                    $this->session->set_flashdata('Error', 'Level tidak valid atau tidak boleh dipilih.');
                    redirect('user/editother/' . $id_session);
                    return;
                }
                $level_baru = (string) $kiriman;
            }
        }

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
        if ($this->input->post('password')=='' ){
        $data = array(
            'username'  => $this->input->post('username'),
            'nama'  => $this->input->post('nama'),
            'crews_idsession'        => $this->input->post('crewid'),
            'client_idsession'        => $this->input->post('clientid'),
            'partner_idsession'        => $this->input->post('partnerid'),
            'email'        => $this->input->post('email')
            );
        }else{
            $data = array(
            'username'  => $this->input->post('username'),
            'nama'  => $this->input->post('nama'),
            'email'        => $this->input->post('email'),
            'crews_idsession'        => $this->input->post('crewid'),
            'client_idsession'        => $this->input->post('clientid'),
            'partner_idsession'        => $this->input->post('partnerid'),
            'password'    => password_hash($this->input->post('password'), PASSWORD_DEFAULT)       
            );

        }
    
        if ($level_baru !== null) {
            $data['level'] = $level_baru;
        }
        $this->Users2_model->update_users($id_session, $data);

        $status = 'Update Data Pengguna';
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_platform'=> $agent,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Users2_model->insert_log_activity($data_log);
    
        $this->session->set_flashdata('Success', 'Pengguna berhasil diupdate');
        redirect('user/lihat/' . $id_session);
    }

    public function update2($id_session) {
        // Harus login; ubah akun sendiri (staff) atau, untuk Developer/Administrator/Staff Admin, akun lain (lihat target_boleh())
        $sendiri = $this->session->id_session && $id_session === $this->session->id_session;
        $boleh = $sendiri
            || $this->session->level === '1'
            || (in_array($this->session->level, ['2', '4'], true) && $this->target_boleh($id_session));
        if (!$boleh) {
            $this->session->set_flashdata('Error', 'Anda tidak berhak mengubah data pengguna ini.');
            redirect('user');
            return;
        }

        if ($this->agent->is_browser()) {
            $agent = 'Desktop ' . $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = 'Mobile ' . $this->agent->mobile() . ' ' . $this->agent->version();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $user_lama = $this->Users2_model->get_users_by_session($id_session);

        $user_data = array(
            'username' => $this->input->post('username') !== null ? $this->input->post('username') : $user_lama->username,
            'nama' => $this->input->post('nama') !== null ? $this->input->post('nama') : $user_lama->nama,
            'email' => $this->input->post('email') !== null ? $this->input->post('email') : $user_lama->email,
        );

        if ($this->input->post('password') != '') {
            $user_data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        }

        $this->Users2_model->update_users($id_session, $user_data);

        // Fetch crews_idsession from user table
        $user = $this->Users2_model->get_users_by_session($id_session);
        $crews_idsession = $user->crews_idsession;

        if ($crews_idsession) {
            // Ambil data crews lama
            $crews_lama = $this->Users2_model->get_crew_by_id_session($crews_idsession);

            // Update crews table, gunakan data lama jika input kosong
            $crews_data = array(
                'gender' => $this->input->post('gender') !== null ? $this->input->post('gender') : $crews_lama->gender,
                'religion' => $this->input->post('religion') !== null ? $this->input->post('religion') : $crews_lama->religion,
                'phone' => $this->input->post('phone') !== null ? $this->input->post('phone') : $crews_lama->phone,
                'birth_date' => $this->input->post('birth_date') !== null ? $this->input->post('birth_date') : $crews_lama->birth_date,
                'address' => $this->input->post('address') !== null ? $this->input->post('address') : $crews_lama->address,
            );

            $this->Users2_model->update_crews($crews_idsession, $crews_data);
        }

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        // Log activity
        $data_log = array(
            'log_activity_user_id' => $this->session->id_session,
            'log_activity_modul' => 'user/edit_staff',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Update Data Pengguna',
            'log_activity_platform' => $agent,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_ip'=> $ip_with_location,
        );

        $this->Users2_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Data pengguna berhasil diupdate');
        redirect('user/lihat/' . $id_session);
    }

    public function delete($id_session) {
        if ($this->session->level=='1' OR $this->session->level=='4'){
            cek_session_akses_developer('user',$this->session->id_session);
        

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

        $data = ['user_stat' => 'Delete'];
        $this->Users2_model->update_users($id_session, $data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/delete',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Hapus Pengguna',
            'log_activity_platform'=> $agent,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Users2_model->insert_log_activity($data_log);


        $this->session->set_flashdata('Success', 'Pengguna berhasil dihapus');
        redirect('user');
        }else{
                redirect(base_url());
            }
    }

    public function recycle_bin() {
        if ($this->session->level=='1' OR $this->session->level=='4'){
            cek_session_akses_developer('user',$this->session->id_session);

        $data['users'] = $this->Users2_model->get_deleted_user();  // Get projects with status 'delete'
        $this->load->view('user/recycle_bin', $data);
        }    
    }

    public function restore($id_session) {

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
        $data = ['user_stat' => 'Publish'];
        $this->Users2_model->update_Users($id_session, $data);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/restore',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Restore',
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Users2_model->insert_log_activity($data_log);      

        $this->session->set_flashdata('Success', 'Pengguna berhasil dipulihkan');
        redirect('user');
    }

    public function permanent_delete($id_session) {

        if ($this->session->level=='1'OR $this->session->level=='4'){
            cek_session_akses_developer('user',$this->session->id_session);

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


        $this->Users2_model->delete_Users_permanent($id_session);

        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'user/permanent',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => 'Hapus Permanent Pengguna',
            'log_activity_platform'=> $agent,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Users2_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Pengguna berhasil dihapus permanen');
        redirect('user/recycle_bin');
        }
    }
    
}
