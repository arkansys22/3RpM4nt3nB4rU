<?php
class Finance_project_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_projectacc($id_session) {
        $this->db->order_by('tanggal_transaksi', 'ASC');
        return $this->db->get_where('project_acc',['project_id_session' => $id_session])->result();
    }


    public function view_join_where($table,$id_session,$table2,$field1,$field2)
  {
      $this->db->select('*');
      $this->db->from($table);
      $this->db->where('project_id', $id_session);
      $this->db->join($table2, $table.'.'.$field1.'='.$table2.'.'.$field2);
      
      return $this->db->get()->result_array();
  }



    public function get_deleted_project() {
        return $this->db->get_where('project', ['status' => 'delete'])->result();
    }


    public function delete_permanent($project_id_session,$id_session) {
        $this->db->where('id_session', $id_session);
        $this->db->where('project_id_session', $project_id_session);
        return $this->db->delete('project_acc');
    }


    public function get_finance_out($project_id_session)
    {
        $this->db->select('SUM(nominal_transaksi) as total_finance_out');
        $this->db->from('project_acc');
        $this->db->where('project_id_session', $project_id_session);  // Tahun
        $query = $this->db->get();
        return $query->row();
    }

    public function get_finance_dibayarklien($project_id_session)
    {
        $this->db->select('SUM(total_paid) as total_dibayarkan');
        $this->db->from('payment');
        $this->db->where('id_session', $project_id_session);  // Tahun
        $this->db->where('status', 'Paid');
        $query = $this->db->get();
        return $query->row();
    }


 



    public function get_expense_project_bulan_ini()
    {

        $date_now = date('Y-m');
        $date_start_of_month = date('Y-m-01');
     
        $this->db->select('SUM(nominal_transaksi) as total_expense_project_bulan_ini');
        $this->db->from('project_acc');
        $this->db->where('DATE(tanggal_transaksi) >=', $date_start_of_month);
        $this->db->where('DATE(tanggal_transaksi) >=', $date_now);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_expense_operasional_bulan_ini()
    {

        $date_now = date('Y-m');
        $date_start_of_month = date('Y-m-01');
     
        $this->db->select('SUM(nominal_transaksi) as total_expense_operasional_bulan_ini');
        $this->db->from('operational_acc');
        $this->db->where('DATE(tanggal_transaksi) >=', $date_start_of_month);
        $this->db->where('DATE(tanggal_transaksi) >=', $date_now);
        $query = $this->db->get();
        return $query->row();
    }


    public function get_expense_project_bulan_lalu()
    {

        $date_start_of_last_month = date('Y-m-01', strtotime('first day of last month')); // Start of last month
        $date_end_of_last_month = date('Y-m-t', strtotime('last month')); // End of last month
     
        $this->db->select('SUM(nominal_transaksi) as total_expense_project_bulan_lalu');
        $this->db->from('project_acc');
        $this->db->where('DATE(tanggal_transaksi) >=', $date_start_of_last_month);
        $this->db->where('DATE(tanggal_transaksi) >=', $date_end_of_last_month);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_expense_operasional_bulan_lalu()
    {

        $date_start_of_last_month = date('Y-m-01', strtotime('first day of last month')); // Start of last month
        $date_end_of_last_month = date('Y-m-t', strtotime('last month')); // End of last month
     
        $this->db->select('SUM(nominal_transaksi) as total_expense_operasional_bulan_lalu');
        $this->db->from('operational_acc');
        $this->db->where('DATE(tanggal_transaksi) >=', $date_start_of_last_month);
        $this->db->where('DATE(tanggal_transaksi) >=', $date_end_of_last_month);
        $query = $this->db->get();
        return $query->row();
    }


    public function getTotalNilaiBulanLalu() {
        $bulanLalu = date('Y-m-01', strtotime('-1 month'));
        $this->db->select('SUM(nominal_transaksi)');
        $this->db->where('DATE(tanggal_transaksi) >=', $bulanLalu);
        $this->db->where('DATE(tanggal_transaksi) <', date('Y-m-01', strtotime('+1 month', strtotime($bulanLalu))));
        $query = $this->db->get('operational_acc');
        return $query->row()->select('sum(nominal_transaksi)');
    }

    public function get_transaction_by_session($id_session) {
        return $this->db->get_where('project_acc', ['id_session' => $id_session])->row();
    }

    public function get_transaction_by_ids($project_id_session, $id_session) {
        return $this->db->get_where('project_acc', [
            'project_id_session' => $project_id_session,
            'id_session' => $id_session
        ])->row();
    }

}
