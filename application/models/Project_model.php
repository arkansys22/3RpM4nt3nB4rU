<?php
class project_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('project_model');
        $this->load->model('finance_project_model');
    }

    public function get_all_project() {
        $this->db->order_by('event_date', 'DESC');
        return $this->db->get_where('project',['status' => 'create'])->result();
    }

    public function get_deleted_project() {
        return $this->db->get_where('project', ['status' => 'delete'])->result();
    }

    public function insert_project($data) {
        return $this->db->insert('project', $data);

        if ($insert) {

            // Ambil id yang baru disimpan di tabel project
            $project_id = $this->db->insert_id();  // Mendapatkan id auto-increment dari project

            // Ambil hari dari create_at (format: Senin, Selasa, dst.)
            $create_day = date('l', strtotime($data['date_create'])); 
    
            // Data untuk tabel clients
            $client_data = [
                'id_session'         => $data['id_session'],
                'client_name'        => $data['project_name'],  // project_name → client_name
                'wedding_date'       => $data['event_date'],   // event_date → wedding_date
                'created_at'         => $data['create_date'],  // create_date → create_at
                'create_day'         => $create_day,           // Otomatis ambil hari dari create_at
                'location'           => $data['location'],     // Sama dengan project
                'status'             => 'create'
            ];
            
            // Insert ke tabel clients
            $this->db->insert('clients', $client_data);
        }
    
        return $insert;
    }

    public function get_project_by_session($id_session) {
        return $this->db->get_where('project', ['id_session' => $id_session])->row();
    }

    public function update_project($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('project', $data);
    }

    public function delete_project($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('project', $data);
    }

    public function restore_project($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('project', ['status' => 'create']);
    }

    public function delete_project_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('project');
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);
    }

    public function get_logactivity_by_session($id_session) {
        $this->db->order_by('log_activity_id', 'DESC');
        $this->db->limit(5, 0);
        return $this->db->get_where('log_activity', ['log_activity_document_no' => $id_session])->result();
    }

    public function get_project()
    {
        return $this->db->get('project')->row(); // Mengambil satu baris data dari tabel project
    }

    public function get_revenue_data() {
        $this->db->select("YEAR(event_date) as year, MONTH(event_date) as month, SUM(value) as total_revenue");
        $this->db->from("project");
        $this->db->where("event_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE()");
        $this->db->group_by("YEAR(event_date), MONTH(event_date)");
        $this->db->order_by("year DESC, month DESC");
        $query = $this->db->get();
        
        return $query->result();
    }

    public function get_revenue_by_month($month)
    {
        $this->db->select('SUM(value) as total_revenue');
        $this->db->from('project');
        // Gunakan YEAR() dan MONTH() untuk mengambil bulan dan tahun dari event_date
        $this->db->where('YEAR(event_date)', date('Y', strtotime($month)));  // Tahun
        $this->db->where('MONTH(event_date)', date('m', strtotime($month)));  // Bulan
        $query = $this->db->get();
        return $query->row();
    }

    public function get_total_revenue_all()
    {
        $this->db->select('SUM(value) as total_revenue');
        $this->db->from('project');
        $query = $this->db->get();
        return $query->row()->total_revenue;
    }


    public function view_ordering($table,$order,$ordering)
    {
          $this->db->select('*');
          $this->db->from($table);
          $this->db->order_by($order,$ordering);
          return $this->db->get()->result_array();
    }


    public function insert_accounting($data_accounting) {

        // cek data yang sama (sesuaikan field uniknya)
        $this->db->where('accounting_id_session', $data_accounting['accounting_id_session']);
        $this->db->where('accounting_nomer_kategori', $data_accounting['accounting_nomer_kategori']);
        $this->db->where('accounting_tanggal', $data_accounting['accounting_tanggal']);

        $cek = $this->db->get('accounting');

        if ($cek->num_rows() > 0) {
            // UPDATE jika sudah ada
            $this->db->where('accounting_id_session', $data_accounting['accounting_id_session']);
            $this->db->where('accounting_nomer_kategori', $data_accounting['accounting_nomer_kategori']);
            $this->db->where('accounting_tanggal', $data_accounting['accounting_tanggal']);

            return $this->db->update('accounting', $data_accounting);

        } else {
            // INSERT jika belum ada
            return $this->db->insert('accounting', $data_accounting);
        }
    }


}
