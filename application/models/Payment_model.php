
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


 
    public function get_payment_by_payment_id_session($id_session, $payment_id_session) {
        $this->db->where('id_session', $id_session);
        $this->db->where('payment_id_session', $payment_id_session);
        $result = $this->db->get('payment')->row();

        if (!$result) {
            log_message('error', "Payment not found for id_session: $id_session and payment_id_session: $payment_id_session");
        }

        return $result;
    }

    public function get_by_payment_id_session($id_session)
    {
        return $this->db
            ->like('payment_id_session', $id_session . 'IMB', 'after')
            ->get('payment')
            ->result_array();
    }

    public function insert_payment($data) {
        return $this->db->insert('payment', $data);
    }

    public function update_payment($id_session, $transactions_id, $data) {
        $this->db->where('id_session', $id_session);
        $this->db->where('transactions_id', $transactions_id);
        return $this->db->update('payment', $data);
    }

    public function update_payment2($id_session, $payment_id_session, $data) {
        $this->db->where('id_session', $id_session);
        $this->db->where('payment_id_session', $payment_id_session);
        return $this->db->update('payment', $data);
    }

    public function delete_payment($id_session, $payment_id_session) {
        $this->db->where('id_session', $id_session);
        $this->db->where('payment_id_session', $payment_id_session);
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
        $this->db->select("YEAR(date) as year, MONTH(date) as month, SUM(CASE WHEN status = 'Pending' THEN total_paid ELSE 0 END) as total_unpaid, SUM(CASE WHEN status = 'Paid' THEN total_paid ELSE 0 END) as total_paid");
        $this->db->from("payment");
        $this->db->group_by(["YEAR(date)", "MONTH(date)"]);
        $this->db->order_by("YEAR(date) DESC, MONTH(date) DESC");
        $query = $this->db->get();

        $result = $query->result_array();
        $revenue_per_year = [];
        foreach ($result as $row) {
            $revenue_per_year[$row['year']][$row['month']] = [
                'total_unpaid' => $row['total_unpaid'],
                'total_paid' => $row['total_paid']
            ];
        }
        return $revenue_per_year;
    }

    public function get_paid_revenues_with_transaction_id($month, $year) {
        $this->db->select('id_session, transactions_id, status, total_bill, total_paid, date as transaction_date');
        $this->db->from('payment');
        $this->db->where('MONTH(date)', $month);
        $this->db->where('YEAR(date)', $year);
        $this->db->where('transactions_id IS NOT NULL'); // Ensure transaction_id exists
        $this->db->group_start(); // Start grouping conditions
        $this->db->where('total_paid IS NOT NULL'); // Include if total_bill exists
        $this->db->where('status', 'Pending'); // Only include if status is Paid
        $this->db->or_group_start(); // Start sub-group for total_paid condition
        $this->db->where('total_paid IS NOT NULL'); // Include if total_paid exists
        $this->db->where('status', 'Paid'); // Only include if status is Paid
        $this->db->group_end(); // End sub-group
        $this->db->group_end(); // End grouping conditions
        return $this->db->get()->result();
    }

    public function get_paid_expense_with_transaction_id($month, $year) {
        $this->db->select('id_session, nama_transaksi, kategori, nominal_transaksi, periode, tanggal_transaksi as transaction_date');
        $this->db->from('operational_acc');
        $this->db->where('MONTH(tanggal_transaksi)', $month);
        $this->db->where('YEAR(tanggal_transaksi)', $year);
        $this->db->group_start(); // Start grouping conditions
        $this->db->where('nominal_transaksi IS NOT NULL'); // Include if total_bill exists        
        $this->db->group_end(); // End grouping conditions
        $this->db->order_by('tanggal_transaksi','DESC');
        return $this->db->get()->result();
    }


    public function get_expense_per_year() {
        $this->db->select("YEAR(tanggal_transaksi) as year, MONTH(tanggal_transaksi) as month, SUM(nominal_transaksi) as total");
        $this->db->from("operational_acc");
        $this->db->group_by(["YEAR(tanggal_transaksi)", "MONTH(tanggal_transaksi)"]);
        $this->db->order_by("YEAR(tanggal_transaksi) DESC, MONTH(tanggal_transaksi) DESC");
        $query = $this->db->get();

        $result = $query->result_array();
        $espense_per_year = [];
        foreach ($result as $row) {
            $espense_per_year[$row['year']][$row['month']] = [
                'total' => $row['total']
            ];
        }
        return $espense_per_year;
    }


    public function delete_accounting($payment_id_session) {
        $this->db->where('accounting_id_session ', $payment_id_session);
        return $this->db->delete('accounting');
    }


    public function insert_accounting($payment_id_session, $data_accounting)
    {
        $sql = "
            INSERT INTO accounting
            (
                accounting_id_session,
                accounting_nomer_kategori,
                accounting_nominal,
                accounting_tanggal,
                accounting_nama_transaksi
            )
            VALUES (?, ?, ?, ?, ?)
        ";

        return $this->db->query($sql, [
            $payment_id_session,
            $data_accounting['accounting_nomer_kategori'],
            $data_accounting['accounting_nominal'],
            $data_accounting['accounting_tanggal'],
            $data_accounting['accounting_nama_transaksi']
        ]);
    }


    public function update_accounting($payment_id_session, $data_accounting)
    {
        // Hapus accounting lama berdasarkan payment session
        $this->db->where('accounting_id_session', $payment_id_session);
        $this->db->delete('accounting');

        // Insert ulang accounting baru
        foreach ($data_accounting as $row) {

            $insertData = [
                'accounting_id_session'      => $payment_id_session,
                'accounting_nomer_kategori'  => $row['accounting_nomer_kategori'],
                'accounting_nominal'         => $row['accounting_nominal'],
                'accounting_tanggal'         => $row['accounting_tanggal'],
                'accounting_nama_transaksi'  => $row['accounting_nama_transaksi']
            ];

            $this->db->insert('accounting', $insertData);
        }

        return true;
    }

    public function update_accounting_kwitansi($payment_id_session, $data_accounting)
{
    // Cek apakah accounting_id_session sudah ada
    $cek = $this->db
        ->where('accounting_id_session', $payment_id_session)
        ->get('accounting')
        ->row();

    $insertData = [
        'accounting_id_session'      => $payment_id_session,
        'accounting_nomer_kategori'  => $data_accounting['accounting_nomer_kategori'],
        'accounting_nominal'         => $data_accounting['accounting_nominal'],
        'accounting_tanggal'         => $data_accounting['accounting_tanggal'],
        'accounting_nama_transaksi'  => $data_accounting['accounting_nama_transaksi']
    ];

    // Jika belum ada → insert
    if (!$cek) {

        return $this->db->insert('accounting', $insertData);

    } else {

        // Jika sudah ada → update
        $this->db->where(
            'accounting_id_session',
            $payment_id_session
        );

        return $this->db->update(
            'accounting',
            $insertData
        );
    }
}


public function update_sisa_invoice($id_session)
{
    // Ambil total invoice IMB kategori 4000
    $invoice = $this->db
        ->select('id, accounting_nominal')
        ->like('accounting_id_session', $id_session . 'IMB', 'after')
        ->like('accounting_nomer_kategori', '4000', 'after')
        ->get('accounting')
        ->row();

    if (!$invoice) {
        return false;
    }

    $total_invoice = (float)$invoice->accounting_nominal;

    // Total pembayaran MBP
    $mbp = $this->db
        ->select_sum('accounting_nominal')
        ->like('accounting_id_session', $id_session . 'MBP', 'after')
        ->get('accounting')
        ->row();

    $total_mbp = !empty($mbp->accounting_nominal)
        ? (float)$mbp->accounting_nominal
        : 0;

    // Hitung sisa invoice
    $sisa_invoice = $total_invoice - $total_mbp;

    // Hindari minus
    $sisa_invoice = max(0, $sisa_invoice);

    // Update nominal IMB
    $this->db->where('id', $invoice->id);

    return $this->db->update('accounting', [
        'accounting_nominal' => $sisa_invoice
    ]);
}

}
