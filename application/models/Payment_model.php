<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model {

    // Mendapatkan data payment berdasarkan id_session
    public function get_payment_by_session($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->get('payment')->row(); // Mengambil data pertama (bisa diubah jika ingin data lebih banyak)
    }
    // Mendapatkan jumlah payment berdasarkan id_session
    public function get_payment_count($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->count_all_results('payment');
    }

    public function get_payment_by_invoice($id_session, $invoice_number) {
        $this->db->where('id_session', $id_session);
        $this->db->where('invoice_' . $invoice_number . ' !=', NULL);  // Memastikan invoice_number valid
        return $this->db->get('payment')->row();  // Mengambil satu baris data pembayaran
    }

    // Menambahkan data payment baru
    public function insert_payment($data) {
        return $this->db->insert('payment', $data);
    }

    public function update_payment($id_session, $invoice_number, $data) {
        $this->db->where('id_session', $id_session);
        $this->db->where('invoice_' . $invoice_number, NULL); // Filter berdasarkan nomor invoice
        return $this->db->update('payment', $data);  // Mengupdate data payment
    }
            
    public function delete_invoice($id_session, $invoice_number) {
        // Menghapus data pembayaran berdasarkan id_session dan nomor invoice
        $this->db->where('id_session', $id_session);
        $this->db->update('payment', [
            'invoice_' . $invoice_number => NULL,
            'kwitansi_' . $invoice_number => NULL,
            'amount_' . $invoice_number => NULL,
            'dp_' . $invoice_number => NULL,
            'date_' . $invoice_number => NULL,
            'details_' . $invoice_number => NULL
        ]);
    }
    
    // Mendapatkan data project berdasarkan id_session
    public function get_project($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->get('project')->row();
    }
}
