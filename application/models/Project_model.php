<?php
class project_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_project() {
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

}
