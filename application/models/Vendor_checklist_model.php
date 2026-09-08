<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Model buat fitur Ceklist & Tanda Tangan serah terima vendor (Dekorasi &
// Catering), satu form per (project, vendor). Dipakai oleh
// Crud_vendor_checklist.
class Vendor_checklist_model extends CI_Model {

    public function get_by_session($id_session)
    {
        return $this->db->get_where('vendor_checklist_form', ['id_session' => $id_session])->row();
    }

    public function get_by_project_vendor($project_id_session, $vendor_id)
    {
        return $this->db->get_where('vendor_checklist_form', [
            'project_id_session' => $project_id_session,
            'vendor_id' => $vendor_id,
        ])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('vendor_checklist_form', $data);
    }

    public function update($id_session, $data)
    {
        $this->db->where('id_session', $id_session);
        return $this->db->update('vendor_checklist_form', $data);
    }
}
