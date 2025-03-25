<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model {

    public function get_payment_by_session($id_session) {
        $this->db->select('*');
        $this->db->where('id_session', $id_session);
        return $this->db->get('payment')->result(); // Kembalikan semua transaksi
    }

    public function get_payment_by_transaction_id($id_session, $transactions_id) {
        $this->db->where('id_session', $id_session);
        $this->db->where('transactions_id', $transactions_id);
        $result = $this->db->get('payment')->row();

        if (!$result) {
            log_message('error', "Payment not found for id_session: $id_session and transactions_id: $transactions_id");
        }

        return $result;
    }

    public function insert_payment($data) {
        return $this->db->insert('payment', $data);
    }

    public function update_payment($id_session, $transactions_id, $data) {
        $this->db->where('id_session', $id_session);
        $this->db->where('transactions_id', $transactions_id);
        return $this->db->update('payment', $data);
    }

    public function delete_payment($id_session, $transactions_id) {
        $this->db->where('id_session', $id_session);
        $this->db->where('transactions_id', $transactions_id);
        return $this->db->delete('payment');
    }

    public function has_invoice($id_session) {
        $this->db->select('transactions_id');
        $this->db->where('id_session', $id_session);
        $this->db->like('transactions_id', 'IMB', 'after'); // Cari transactions_id yang diawali dengan 'IMB'
        return $this->db->get('payment')->row(); // Kembalikan satu baris jika ada
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);
    }

}
