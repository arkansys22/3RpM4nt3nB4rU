<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_supplies extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Supplies_model');
    }

    public function lihat($id_session) {

        if ($this->session->level=='1'){
            cek_session_akses_developer('supplies',$this->session->id_session);
            $data['supplies'] = $this->Supplies_model->get_supplies_by_session($id_session);
            $data['supplies_stock'] = $this->Supplies_model->get_stock($id_session);
            $this->load->view('supplies/lihat', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('supplies',$this->session->id_session);
            $data['supplies'] = $this->Supplies_model->get_supplies_by_session($id_session);
            $data['supplies_stock'] = $this->Supplies_model->get_stock($id_session);
            $this->load->view('supplies/lihat', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('supplies',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('supplies',$this->session->id_session);
            $data['supplies'] = $this->Supplies_model->get_supplies_by_session($id_session);
            $data['supplies_stock'] = $this->Supplies_model->get_stock($id_session);
            $this->load->view('supplies/lihat', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('supplies',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);
            
        }else{
            redirect(base_url());
            }
    }

    public function index() {
        if ($this->session->level=='1'){
            cek_session_akses_developer('supplies',$this->session->id_session);
            $data['supplies'] = $this->Supplies_model->get_all_supplies();
            $data['logactivity'] = $this->Supplies_model->get_logactivity_by_session();
            $this->load->view('supplies/index', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('supplies',$this->session->id_session);
            $data['supplies'] = $this->Supplies_model->get_all_supplies();
            $data['logactivity'] = $this->Supplies_model->get_logactivity_by_session();
            $this->load->view('supplies/index', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('supplies',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('supplies',$this->session->id_session);
            $data['supplies'] = $this->Supplies_model->get_all_supplies();
            $data['logactivity'] = $this->Supplies_model->get_logactivity_by_session();
            $this->load->view('supplies/index', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('supplies',$this->session->id_session);
            $data['aaa'] = '';
            $this->load->view('backend/v_home', $data);
            
        }else{
            redirect(base_url());
            }
    }

    public function create() {
        if ($this->session->level=='1'){
            cek_session_akses_developer('supplies',$this->session->id_session);
            $this->load->view('supplies/create');

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('supplies',$this->session->id_session);
            $this->load->view('supplies/create');

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('supplies',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('supplies',$this->session->id_session);
            $this->load->view('supplies/create');

        }else if($this->session->level=='5'){
            cek_session_akses_client('supplies',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }       
    }

    public function store2() {
        $id_session = $this->input->post('id_session');
        $id_sessionstock = hash('sha256', bin2hex(random_bytes(16)));
        $created_at = date('Y-m-d H:i:s');
    
        // Cek platform pengguna
        if ($this->agent->is_browser()) {
            $agent = 'Desktop ' . $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = 'Mobile ' . $this->agent->mobile() . ' ' . $this->agent->version();
        } else {
            $agent = 'Unidentified User Agent';
        }
    
        // Ambil jumlah terakhir dari stok (record terbaru)
        $supplies = $this->Supplies_model->get_stock_by_session($id_session);
        
        // Jika supplies tidak ditemukan, anggap amount = 0, jika ditemukan ambil amount terakhir
        $last_amount = isset($supplies) && $supplies ? $supplies->amount : 0;
    
        // Ambil nilai goods_in dan goods_out dari input user
        $goods_in = (int) $this->input->post('goods_in'); // Ubah menjadi integer
        $goods_out = (int) $this->input->post('goods_out'); // Ubah menjadi integer
    
        // Hitung amount baru dengan menambahkan goods_in
        $new_amount = $last_amount + $goods_in - $goods_out;
    
        // Simpan stock baru ke database (insert bukan update)
        $stock = array(
            'id_session'    => $id_session,
            'id_sessionstock' => $id_sessionstock,
            'created_by'    => $this->session->id_session,
            'amount'        => $new_amount, // Sekarang amount ditambah dari jumlah sebelumnya
            'goods_in'      => $goods_in,
            'goods_out'     => $goods_out,
            'status'        => 'Barang Masuk',
            'created_at'    => $created_at,
            'detail'          => $this->input->post('detail'),
        );
    
        // Insert data baru ke database
        $this->db->insert('supplies_stock', $stock);
        
        $product_name =  $this->input->post('product_name');
        $status = 'Barang Masuk '.$product_name;
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        // Simpan log aktivitas
        $data_log = array(
            'log_activity_user_id' => $this->session->id_session,
            'log_activity_modul'   => 'supplies/editin',
            'log_activity_document_no' => $id_session,
            'log_activity_status'  => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'      => $ip_with_location
        );
    
        // Simpan log aktivitas ke database
        $this->Supplies_model->insert_log_activity($data_log);
    
        // Berikan pesan sukses dan redirect
        $this->session->set_flashdata('Success', 'Stock berhasil ditambahkan');
        redirect('supplies');
    }
    
    public function store3() {
        $id_session = $this->input->post('id_session');
        $id_sessionstock = hash('sha256', bin2hex(random_bytes(16)));
        $created_at = date('Y-m-d H:i:s');
    
        // Cek platform pengguna
        if ($this->agent->is_browser()) {
            $agent = 'Desktop ' . $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = 'Mobile ' . $this->agent->mobile() . ' ' . $this->agent->version();
        } else {
            $agent = 'Unidentified User Agent';
        }
    
        // Ambil stok terakhir dari database berdasarkan id_session
        $supplies = $this->Supplies_model->get_stock_by_session($id_session);
    
        // Jika tidak ada stok sebelumnya, anggap amount = 0
        $last_amount = isset($supplies) && $supplies ? $supplies->amount : 0;
    
        // Ambil jumlah barang keluar dari input user
        $goods_out = (int) $this->input->post('goods_out'); // Ubah ke integer
    
        // Pastikan jumlah barang keluar tidak melebihi stok yang tersedia
        if ($goods_out > $last_amount) {
            $this->session->set_flashdata('Error', 'Stok tidak mencukupi untuk barang keluar');
            redirect('supplies/editout/' . $id_session);
            return;
        }
    
        // Hitung amount baru setelah barang keluar
        $new_amount = $last_amount - $goods_out;
    
        // Simpan data barang keluar ke database
        $stock = array(
            'id_session'    => $id_session,
            'id_sessionstock' => $id_sessionstock,
            'created_by'    => $this->session->id_session,
            'amount'        => $new_amount, // Amount dikurangi dengan barang keluar
            'goods_in'      => 0, // Tidak ada barang masuk
            'goods_out'     => -abs($goods_out), // Simpan sebagai negatif jika diinginkan
            'status'        => 'Barang Keluar',
            'created_at'    => $created_at,
            'detail'          => $this->input->post('detail'),
        );
    
        // Insert data baru ke database
        $this->db->insert('supplies_stock', $stock);

        $product_name =  $this->input->post('product_name');
        $status = 'Barang Keluar '.$product_name;
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        // Simpan log aktivitas
        $data_log = array(
            'log_activity_user_id' => $this->session->id_session,
            'log_activity_modul'   => 'supplies/editout',
            'log_activity_document_no' => $id_session,
            'log_activity_status'  => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'      => $ip_with_location
        );
    
        // Simpan log aktivitas ke database
        $this->Supplies_model->insert_log_activity($data_log);
    
        // Berikan pesan sukses dan redirect
        $this->session->set_flashdata('Success', 'Stock berhasil dikurangi');
        redirect('supplies');
    }
    
    public function store() {
        $id_session = hash('sha256', bin2hex(random_bytes(16)));
        $created_at = date('Y-m-d H:i:s');
        $created_day = date('l', strtotime($created_at));
        $id_sessionstock = hash('sha256', bin2hex(random_bytes(16)));

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
            'id_session'    => $id_session,
            'product_name'  => $this->input->post('product_name'),
            'type'          => $this->input->post('type'),
            'created_by'     => $this->session->id_session,
            'created_day'    => $created_day,
            'status'       => 'created'
        );
    
        $this->Supplies_model->insert_supplies($data);

        $stock = array(
            'id_session'   => $id_session,
            'id_sessionstock'   => $id_sessionstock,
            'created_by'    => $this->session->id_session,
            'status'       => 'created'
        );
    
        $this->Supplies_model->insert_stock($stock);

        $product_name =  $this->input->post('product_name');
        $status = 'Tambah Produk '.$product_name;
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(

            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'supplies/create',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
            
        );

        $this->Supplies_model->insert_log_activity($data_log);

        $this->session->set_flashdata('Success', 'Stock berhasil dibuat');

        redirect('supplies');
    }

    public function edit($id_sessionstock) {
        // Mengecek apakah level session termasuk 1, 2, atau 4
        if ($this->session->level == '1' || $this->session->level == '2' || $this->session->level == '4') {
            // Mengecek akses berdasarkan level session
            if ($this->session->level == '1') {
                cek_session_akses_developer('supplies', $this->session->id_session);
            } elseif ($this->session->level == '2') {
                cek_session_akses_administrator('supplies', $this->session->id_session);
            } elseif ($this->session->level == '4') {
                cek_session_akses_staff_admin('supplies', $this->session->id_session);
            }
    
            // Ambil data supplies
            $supplies = $this->Supplies_model->get_stock_by_id($id_sessionstock);
    
            if ($supplies) {
                $data['supplies'] = $supplies;
            } else {
                // Jika tidak ada data ditemukan, set error message atau redirect
                $data['error'] = 'Data supplies tidak ditemukan.';
                $this->load->view('404', $data);
                return; // Stop further execution
            }
    
            // Ambil bagian pertama dari URI (segmen pertama setelah 'supplies/')
            $uri_segment = $this->uri->segment(2);
    
            // Mengecek apakah segmen URI adalah 'editin' atau 'editout'
            if ($uri_segment == 'editin') {
                $this->load->view('supplies/editin', $data); // Tampilkan form untuk 'editin'
            } elseif ($uri_segment == 'editout') {
                $this->load->view('supplies/editout', $data); // Tampilkan form untuk 'editout'
            } else {
                // Jika URI tidak sesuai dengan 'editin' atau 'editout'
                echo "Invalid action parameter.";
            }
        } else {
            // Untuk level 3 dan level 5, lakukan redirect
            redirect(base_url());
        }
    }

    public function edit2($id_session) {
        if (in_array($this->session->level, ['1', '2', '4'])) {
            $supplies = $this->Supplies_model->get_supplies_by_session($id_session);

            if ($supplies) {
                $data['supplies'] = $supplies;
                $this->load->view('supplies/edit', $data);
            } else {
                $this->session->set_flashdata('Error', 'Data supplies tidak ditemukan.');
                redirect('supplies');
            }
        } else {
            redirect(base_url());
        }
    }

    public function update($id_session) {

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
            'product_name'  => $this->input->post('product_name'),
            'type'          => $this->input->post('type'),
        );
    
        // Update data produk
        $this->Supplies_model->update_supplies($id_session, $data);
    
        // Ambil data stock terakhir
        $supplies = $this->Supplies_model->get_stock_by_session($id_session);
    
        // Misalkan $supplies['amount'] adalah amount terakhir yang ada di database
        $last_amount = $supplies->amount;
    
        // Ambil nilai goods_in dan goods_out dari input user
        $goods_in = (int) $this->input->post('goods_in'); // Ubah menjadi integer
        $goods_out = (int) $this->input->post('goods_out'); // Ubah menjadi integer
    
        // Jika ada goods_in, kita tambah amount yang terakhir
        if ($goods_in > 0) {
            $new_amount = $last_amount + $goods_in;  // Tambah jumlah goods_in
        } elseif ($goods_out > 0) {
            // Jika ada goods_out, kita kurangi amount yang terakhir
            $new_amount = $last_amount - $goods_out; // Kurangi jumlah goods_out
        }
    
        // Simpan stock yang telah diperbarui
        $stock = array(
            'amount'        => $new_amount, // amount akhir setelah semua perhitungan
            'goods_in'      => $goods_in,
            'goods_out'     => $goods_out
        );
    
        // Update database dengan stock terbaru
        $this->db->where('id_session', $id_session);
        $this->db->update('supplies_stock', $stock);
    
        $status = 'Edit';
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        // Simpan log aktivitas
        $data_log = array(
            'log_activity_user_id'=>$this->session->id_session,
            'log_activity_modul' => 'supplies/edit',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform'=> $agent,
            'log_activity_ip'=> $ip_with_location
        );
    
        // Simpan log aktivitas ke database
        $this->Supplies_model->insert_log_activity($data_log);
    
        // Berikan pesan sukses dan redirect
        $this->session->set_flashdata('Success', 'Stock berhasil diupdate');
        redirect('supplies');
    }

    public function update2($id_session) {
        if (in_array($this->session->level, ['1', '2', '4'])) {
            $data = array(
                'product_name' => $this->input->post('product_name'),
                'type' => $this->input->post('type')
            );

            $this->Supplies_model->update_supplies($id_session, $data);

            $this->session->set_flashdata('Success', 'Produk berhasil diperbarui');
            redirect('supplies/lihat/' . $id_session);
        } else {
            redirect(base_url());
        }
    }

    public function delete($id_session) {

        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='4' OR $this->session->level=='5'){

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

            // Ambil nama produk sebelum dihapus
            $product = $this->Supplies_model->get_supplies_by_session($id_session);
            $product_name = $product ? $product->product_name : 'Unknown Product';

            $data = ['status' => 'deleted'];

            $this->Supplies_model->delete_supplies($id_session);

            $status = 'Delete ' . $product_name;
            $ip = $this->input->ip_address();
            $location = get_location_from_ip($ip);
            $ip_with_location = $ip . "<br>(" . $location . ")";

            $data_log = array(
                'log_activity_user_id' => $this->session->id_session,
                'log_activity_modul' => 'supplies/delete',
                'log_activity_document_no' => $id_session,
                'log_activity_status' => $status,
                'log_activity_waktu' => date('Y-m-d H:i:s'),
                'log_activity_platform' => $agent,
                'log_activity_ip'=> $ip_with_location
            );

            $this->Supplies_model->insert_log_activity($data_log);

            $this->session->set_flashdata('Success', 'Stock berhasil dihapus');
            redirect('supplies');
        } else {
            redirect(base_url());
        }
    }

    public function recycle_bin() {

        if ($this->session->level=='1'){
            cek_session_akses_developer('supplies',$this->session->id_session);
            $data['supplies'] = $this->Supplies_model->get_deleted_supplies();
            $this->load->view('supplies/recycle_bin', $data);

        }else if($this->session->level=='2'){
            cek_session_akses_administrator('supplies',$this->session->id_session);
            $data['supplies'] = $this->Supplies_model->get_deleted_supplies();
            $this->load->view('supplies/recycle_bin', $data);

        }else if($this->session->level=='3'){
            cek_session_akses_staff_accounting('supplies',$this->session->id_session);
            redirect(base_url());

        }else if($this->session->level=='4'){
            cek_session_akses_staff_admin('supplies',$this->session->id_session);
            $data['supplies'] = $this->Supplies_model->get_deleted_supplies();
            $this->load->view('supplies/recycle_bin', $data);

        }else if($this->session->level=='5'){
            cek_session_akses_client('supplies',$this->session->id_session);
            redirect(base_url());
            
        }else{
            redirect(base_url());
            }
    }

    public function restore($id_session) {

        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='4' OR $this->session->level=='5'){

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

            // Restore supplies and stock
            $this->Supplies_model->restore_supplies_and_stock($id_session);    

            // Get product name after restoring
            $product = $this->Supplies_model->get_supplies_by_session($id_session);
            $product_name = $product ? $product->product_name : 'Unknown Product';

            $status = 'Restore '.$product_name;
            $ip = $this->input->ip_address();
            $location = get_location_from_ip($ip);
            $ip_with_location = $ip . "<br>(" . $location . ")";

            $data_log = array(
                'log_activity_user_id' => $this->session->id_session,
                'log_activity_modul' => 'supplies/restore',
                'log_activity_document_no' => $id_session,
                'log_activity_status' => $status,
                'log_activity_waktu' => date('Y-m-d H:i:s'),
                'log_activity_platform' => $agent,
                'log_activity_ip'=> $ip_with_location
            );

            $this->Supplies_model->insert_log_activity($data_log);      

            $this->session->set_flashdata('Success', 'Stock berhasil dipulihkan');
            redirect('supplies/recycle_bin');
        }
    }

    public function permanent_delete($id_session) {

        if ($this->session->level=='1' OR $this->session->level=='2' OR $this->session->level=='3' OR $this->session->level=='4' OR $this->session->level=='5'){

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

            // Ambil nama produk sebelum dihapus
            $product = $this->Supplies_model->get_supplies_by_session($id_session);
            $product_name = $product ? $product->product_name : 'Unknown Product';

        $this->Supplies_model->permanent_delete_supplies_and_stock($id_session);
        
        $status = 'Delete Permanent ' . $product_name;
        $ip = $this->input->ip_address();
        $location = get_location_from_ip($ip);
        $ip_with_location = $ip . "<br>(" . $location . ")";

        $data_log = array(
            'log_activity_user_id' => $this->session->id_session,
            'log_activity_modul' => 'supplies/delete_permanent',
            'log_activity_document_no' => $id_session,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform' => $agent,
            'log_activity_ip'=> $ip_with_location
        );

        $this->Supplies_model->insert_log_activity($data_log);
        $this->session->set_flashdata('Success', 'Stock berhasil dihapus permanen');
        redirect('supplies/recycle_bin');
    }
    }

    public function restock() {
        if (in_array($this->session->level, ['1', '2', '4'])) {
            $data['low_stocks'] = $this->Supplies_model->get_low_stock_items();
            $this->load->view('supplies/restock', $data);
        } else {
            redirect(base_url());
        }
    }

}
