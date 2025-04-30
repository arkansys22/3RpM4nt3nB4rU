<?php
class Supplies_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_supplies() {
        $this->db->select('supplies.product_name, supplies.type, 
                           supplies_stock.amount, supplies_stock.goods_in, supplies_stock.goods_out, 
                           supplies_stock.created_at, supplies.id_session, 
                           supplies_stock.id_sessionstock AS latest_stock_id, 
                           supplies.status'); // Tambahkan status dari supplies
        $this->db->from('supplies');
        $this->db->join('supplies_stock', 'supplies.id_session = supplies_stock.id_session', 'left');
        
        // Tambahkan kondisi hanya mengambil yang statusnya 'created'
        $this->db->where('supplies.status', 'created');
    
        // Ambil hanya record supplies_stock dengan created_at terbaru untuk setiap supplies.id_session
        $this->db->where('supplies_stock.created_at = (SELECT MAX(created_at) 
                                                       FROM supplies_stock 
                                                       WHERE supplies_stock.id_session = supplies.id_session)');
    
        // Urutkan berdasarkan created_at agar data terbaru berada di atas
        $this->db->order_by('supplies_stock.created_at', 'DESC');
    
        // Eksekusi query
        $query = $this->db->get();
    
        // Mengembalikan hasil query
        return $query->result();
    }
                
        
        
    public function insert_supplies($data) {
        return $this->db->insert('supplies', $data);
    }

    public function insert_stock($data) {
        return $this->db->insert('supplies_stock', $data);
    }

    public function insert_supplies_stock($data) {
        $this->db->where('id_session', $data['id_session']);
        $query = $this->db->get('supplies_stock');
        if ($query->num_rows() > 0) {
            // If the session exists, update the record
            return $this->db->update('supplies_stock', $data, array('id_session' => $data['id_session']));
        } else {
            // Otherwise, insert a new record
            return $this->db->insert('supplies_stock', $data);
        }
    }
    
    public function get_supplies_by_session($id_session) {
        $this->db->select('supplies.*, supplies_stock.amount'); // Pilih kolom yang dibutuhkan
        $this->db->from('supplies'); // Tabel utama
        $this->db->join('supplies_stock', 'supplies.id_session = supplies_stock.id_session', 'left'); // JOIN dengan tabel stock berdasarkan id_session
        $this->db->where('supplies.id_session', $id_session); // Filter berdasarkan id_session
    
        // Urutkan berdasarkan waktu (misalnya created_at atau waktu lainnya)
        $this->db->order_by('supplies_stock.created_at', 'DESC'); // Mengurutkan berdasarkan waktu, DESC untuk yang terakhir
        $this->db->limit(1); // Ambil hanya satu record terakhir
    
        $query = $this->db->get(); // Eksekusi query
    
        if ($query->num_rows() > 0) {
            return $query->row(); // Mengembalikan data sebagai objek
        } else {
            return null; // Jika da
        }
    }    
    
        
    public function get_supplies_with_stock($id_session) {
        // Select data from both tables (supplies and supplies_stock)
        $this->db->select('supplies.*, supplies_stock.amount, supplies_stock.goods_in, supplies_stock.goods_out, supplies.status');
        $this->db->from('supplies');
        $this->db->join('supplies_stock', 'supplies.id_session = supplies_stock.id_session', 'left');
        $this->db->where('supplies.id_session', $id_session);
        $query = $this->db->get();
    
        return $query->row(); // Returns an object (or null if no result is found)
    }
    

    public function get_stock_by_session($id_session) {
        $this->db->select('*');
        $this->db->from('supplies_stock');
        $this->db->where('id_session', $id_session);
        $this->db->order_by('created_at', 'DESC'); // Ambil data terbaru
        $this->db->limit(1); // Batasi hanya 1 record
        $query = $this->db->get();
        
        return $query->row(); // Kembalikan sebagai objek
    }

    public function get_stock($id_session) {

        $this->db->order_by('created_at', 'DESC');        
        return $this->db->get_where('supplies_stock', ['id_session' => $id_session])->result();

    }
    

    public function update_supplies($id_session, $data) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('supplies', $data);
    }

    public function delete_supplies($id_session) {
        $this->db->where('id_session', $id_session);
        $this->db->update('supplies', ['status' => 'deleted']); // Mark the supply as deleted
    
        $this->db->where('id_session', $id_session);
        $this->db->update('supplies_stock', ['status' => 'deleted']); // Mark the stock as deleted
    }
    
    public function get_deleted_supplies() {
        $this->db->select('supplies.product_name, supplies.type, supplies.id_session, MAX(supplies_stock.amount) as amount, MAX(supplies_stock.goods_in) as goods_in, MAX(supplies_stock.goods_out) as goods_out');
        $this->db->from('supplies');
        $this->db->join('supplies_stock', 'supplies.id_session = supplies_stock.id_session', 'left');
        $this->db->where('supplies.status', 'deleted'); // Hanya yang dihapus
        $this->db->where('supplies_stock.status', 'deleted'); // Hanya stock yang dihapus
        $this->db->group_by('supplies.id_session'); // Menampilkan hanya satu per id_session
        $query = $this->db->get();
    
        return $query->result(); // Mengembalikan array objek
    }
    
    public function restore_supplies_and_stock($id_session) {
        $this->db->where('id_session', $id_session);
        $this->db->update('supplies', ['status' => 'created']); // Restore the status in 'supplies'

        $this->db->where('id_session', $id_session);
        $this->db->update('supplies_stock', ['status' => 'created']); // Restore the status in 'supplies_stock'
    }

    public function permanent_delete_supplies_and_stock($id_session) {
        $this->db->where('id_session', $id_session);
        $this->db->delete('supplies'); // Permanently delete from 'supplies'

        $this->db->where('id_session', $id_session);
        $this->db->delete('supplies_stock'); // Permanently delete from 'supplies_stock'
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);
    }

    public function get_logactivity_by_session() {
        $this->db->order_by('log_activity_id', 'DESC');
        $this->db->where_in('log_activity_modul', [
            'supplies/create', 
            'supplies/editin', 
            'supplies/editout', 
            'supplies/delete', 
            'supplies/restore',
            'supplies/delete_permanent'
        ]);
        $query = $this->db->get('log_activity');
        return $query->result();
    }

    public function get_stock_by_id($id_sessionstock) {
        $this->db->select('supplies_stock.*, supplies.product_name'); // Ambil product_name dari tabel supplies
        $this->db->from('supplies_stock');
        $this->db->join('supplies', 'supplies.id_session = supplies_stock.id_session', 'left'); // Join ke supplies
        $this->db->where('supplies_stock.id_sessionstock', $id_sessionstock);
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            return $query->row(); // Kembalikan 1 record sebagai objek
        } else {
            return false;
        }
    }
    
    public function get_logactivity_with_details($id_session) {
        $sql = "
            SELECT 
                log_activity.log_activity_status, 
                log_activity.log_activity_waktu, 
                supplies.type, 
                supplies.product_name, 
                supplies_stock.goods_in, 
                supplies_stock.goods_out, 
                supplies_stock.created_at, 
                supplies_stock.detail, 
                CASE 
                    WHEN log_activity.log_activity_status = 'Barang Masuk' THEN COALESCE(supplies_stock.goods_in, 0) 
                    WHEN log_activity.log_activity_status = 'Barang Keluar' THEN COALESCE(supplies_stock.goods_out, 0) 
                    ELSE 0 
                END as amount 
            FROM log_activity 
            LEFT JOIN supplies ON log_activity.log_activity_document_no = supplies.id_session 
            LEFT JOIN supplies_stock ON supplies.id_session = supplies_stock.id_session 
            WHERE log_activity.log_activity_document_no = ? 
            AND supplies_stock.created_at = (
                SELECT MIN(created_at) 
                FROM supplies_stock 
                WHERE supplies_stock.id_session = supplies.id_session
                AND supplies_stock.created_at >= log_activity.log_activity_waktu
            ) 
            ORDER BY log_activity.log_activity_waktu DESC
        ";
    
        $query = $this->db->query($sql, array($id_session));
    
        return $query->result();
    }
    
    // Fungsi untuk mengambil jumlah berdasarkan status dan document_no
    public function get_amount_by_status_and_document($status, $document_no) {
        $this->db->select('sum(amount) as total_amount');
        $this->db->from('supplies_stock');
        $this->db->where('log_activity_status', $status);
        $this->db->where('id_sessionstock', $document_no);  // Atau gunakan filter yang sesuai untuk status dan document_no
    
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->total_amount; // Mengembalikan jumlah total
        } else {
            return 0; // Jika tidak ada jumlah
        }
    }

    public function get_low_stock_items() {
        $this->db->select('supplies_stock.id_session, supplies_stock.amount, supplies.product_name, supplies.type');
        $this->db->from('supplies_stock');
        $this->db->join('supplies', 'supplies.id_session = supplies_stock.id_session', 'left');
        $this->db->where('supplies_stock.amount <=', 10); // Only fetch items with amount <= 10
        $this->db->where('supplies.status', 'created'); // Ensure the supply is active
        $this->db->where('supplies_stock.created_at = (SELECT MAX(created_at) FROM supplies_stock WHERE supplies_stock.id_session = supplies.id_session)'); // Fetch the latest record
        $this->db->order_by('supplies_stock.amount', 'ASC');
        return $this->db->get()->result();
    }
    
}
