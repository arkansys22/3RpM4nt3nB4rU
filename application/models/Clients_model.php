<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_clients() {
        $this->db->order_by('wedding_date', 'DESC');     
        return $this->db->get_where('clients', ['status' => 'create'])->result();
    }

    public function get_deleted_clients() {
        return $this->db->get_where('clients', ['status' => 'delete'])->result();
    }

    public function insert_client($data) {
        // Insert data ke tabel clients
        $insert = $this->db->insert('clients', $data);

        if ($insert) {
            // Ambil id yang baru disimpan di tabel clients
            $client_id = $this->db->insert_id();  // Mendapatkan id auto-increment dari clients

            // Ambil hari dari created_at (format: Senin, Selasa, dst.)
            $create_day = date('l', strtotime($data['created_at'])); 

            // Data untuk tabel project
            $project_data = [
                'id_session'         => $data['id_session'],
                'project_name'       => $data['client_name'],  // client_name → project_name
                'event_date'         => $data['wedding_date'], // wedding_date → event_date
                'create_date'        => $data['created_at'],   // created_at → create_date
                'create_day'         => $create_day,           // Otomatis ambil hari dari created_at
                'location'           => $data['location'],     // Sama dengan clients
                'status'             => 'create'
            ];

            // Insert ke tabel project
            $this->db->insert('project', $project_data);
        }

        return $insert;
    }

    public function get_client_by_session($id_session) {
        return $this->db->get_where('clients', ['id_session' => $id_session])->row();
    }

    public function update_client($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('clients', $data);
    }

    public function delete_client($id_session, $data) {
        $this->db->where('id_session', $id_session);        
        return $this->db->update('clients', $data);
    }

    public function restore_client($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('clients', ['status' => 'create']);
    }

    public function delete_client_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('clients');
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);
    }

    public function get_logactivity_by_session($id_session) {
        $this->db->order_by('log_activity_id', 'DESC');
        $this->db->limit(5, 0);
        return $this->db->get_where('log_activity', ['log_activity_document_no' => $id_session])->result();
    }

    public function get_clients_by_year($year) {
        $this->db->like('wedding_date', $year, 'after');
        return $this->db->get('clients')->result();
    }

    public function get_total_clients()
    {
        return $this->db->count_all_results('clients');
    }

    public function get_clients_per_year() {
        $this->db->select("YEAR(wedding_date) as year, MONTH(wedding_date) as month, COUNT(*) as count");
        $this->db->from("clients");
        $this->db->group_by(["YEAR(wedding_date)", "MONTH(wedding_date)"]);
        $this->db->order_by("YEAR(wedding_date), MONTH(wedding_date)", "ASC");
        $query = $this->db->get();

        $result = $query->result_array();
        $clients_per_year = [];
        foreach ($result as $row) {
            $clients_per_year[$row['year']][$row['month']] = $row['count'];
        }
        return $clients_per_year;
    }
}
