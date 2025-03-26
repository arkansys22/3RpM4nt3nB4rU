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



}
