<?php
class Operational_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('operational_acc')->result();
    }

    public function get_all_operational_acc() {
        $this->db->order_by('tanggal_transaksi', 'DESC');
        return $this->db->get('operational_acc')->result();
    }
    
    public function insert($data) {
        return $this->db->insert('operational_acc', $data);        
    }

    public function get_operational_by_session($id_session) {
        return $this->db->get_where('operational_acc', ['id_session' => $id_session])->row();
    }

    public function update_operational($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('operational_acc', $data);
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);

    }

    var $table = 'operational_acc';
    var $column_order = array(null, 'nama_transaksi','tanggal_transaksi','nominal_transaksi'); // kolom yg bisa diurutkan
    var $column_search = array('nama_transaksi','tanggal_transaksi','kategori');      // kolom yg bisa dicari
    var $order = array('tanggal_transaksi' => 'DESC'); // default order 
    private function _get_datatables_query() {
        $this->db->from($this->table);

        $i = 0;
        foreach ($this->column_search as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if(isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']],
                                $_POST['order']['0']['dir']);
        } else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables() {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }



    public function get_periode_by_session($id_session) {
        $this->db->order_by('tanggal_transaksi', 'DESC');
        return $this->db->get_where('operational_acc', ['periode' => $id_session])->result();
    }

    public function get_logactivity_by_session($id_session) {
        $this->db->order_by('log_activity_id', 'DESC');
        $this->db->limit(5,0);
        return $this->db->get_where('log_activity', ['log_activity_document_no' => $id_session])->result();
    }

    public function delete_users($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('user', $data);
    }

    public function restore_users($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('user', ['status' => 'create']);
    }

    public function delete_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('operational_acc');
    }


    public function view_ordering($table,$order,$ordering)
    {
          $this->db->select('*');
          $this->db->from($table);
          $this->db->order_by($order,$ordering);
          return $this->db->get()->result_array();
    }

   
    
    
    
}
