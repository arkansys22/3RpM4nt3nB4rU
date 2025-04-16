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

    public function get_revenue_per_year() {
        $this->db->select("YEAR(date) as year, MONTH(date) as month, SUM(total_bill) as total_bill, SUM(CASE WHEN status = 'Paid' THEN total_paid ELSE 0 END) as total_paid");
        $this->db->from("payment");
        $this->db->group_by(["YEAR(date)", "MONTH(date)"]);
        $this->db->order_by("YEAR(date) DESC, MONTH(date) DESC");
        $query = $this->db->get();

        $result = $query->result_array();
        $revenue_per_year = [];
        foreach ($result as $row) {
            $revenue_per_year[$row['year']][$row['month']] = [
                'total_bill' => $row['total_bill'],
                'total_paid' => $row['total_paid']
            ];
        }
        return $revenue_per_year;
    }

    public function get_paid_revenues_with_transaction_id($month, $year) {
        $this->db->select('id_session, transactions_id, total_bill, total_paid, date as transaction_date');
        $this->db->from('payment');
        $this->db->where('MONTH(date)', $month);
        $this->db->where('YEAR(date)', $year);
        $this->db->where('transactions_id IS NOT NULL'); // Ensure transaction_id exists
        $this->db->group_start(); // Start grouping conditions
        $this->db->where('total_bill IS NOT NULL'); // Include if total_bill exists
        $this->db->or_group_start(); // Start sub-group for total_paid condition
        $this->db->where('total_paid IS NOT NULL'); // Include if total_paid exists
        $this->db->where('status', 'Paid'); // Only include if status is Paid
        $this->db->group_end(); // End sub-group
        $this->db->group_end(); // End grouping conditions
        return $this->db->get()->result();
    }

}
