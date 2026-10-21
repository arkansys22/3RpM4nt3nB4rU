<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partner_model extends CI_Model {

    public function get_all_partner() {
        return $this->db->get_where('partner', ['status' => 'created'])->result();
    }

    public function insert($data) {
        $this->db->insert('partner', $data);
    }

    public function get_partner_by_id($id_session) {
        return $this->db->get_where('partner', ['id_session' => $id_session])->row();
    }

    public function update($id_session, $data) {
        $this->db->where('id_session', $id_session);
        $this->db->update('partner', $data);
    }

    public function get_deleted_partner() {
        return $this->db->get_where('partner', ['status' => 'deleted'])->result();
    }

    public function soft_delete($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('partner', ['status' => 'deleted']);
    }

    public function restore($id_session) {
        $this->db->where('id_session', $id_session);
        return $this->db->update('partner', ['status' => 'created']);
    }

    public function delete_permanent($id_session) {
        $this->db->where('id_session', $id_session);
        $this->db->delete('partner');
    }

    public function insert_log_activity($data_log) {
        return $this->db->insert('log_activity', $data_log);
    }

    public function get_logactivity_by_session($id_session) {
        $this->db->order_by('log_activity_id', 'DESC');
        $this->db->limit(5, 0);
        return $this->db->get_where('log_activity', ['log_activity_document_no' => $id_session])->result();
    }

    public function get_partner_login_details($project_id_session, $user_id_session) {
        $user = $this->db->get_where('user', ['id_session' => $user_id_session])->row();
        $partner_idsession = $user->partner_idsession ?? null;

        $partner_login = null;
        $partner_detail = null;
        $detail = null;

        if ($partner_idsession) {
            $partner_login = $this->db->get_where('vendor', [
                'id_session' => $project_id_session,
                'vendor_id' => $partner_idsession
            ])->row();

            if ($partner_login) {
                $partner_detail = $this->db->get_where('vendor', [
                    'detail' => $partner_login->detail
                ])->row();

                if ($partner_detail) {
                    $detail = $partner_detail->detail;
                }
            }
        }

        return [
            'partner_login' => $partner_login,
            'partner_detail' => $partner_detail,
            'detail' => $detail,
            'user' => $user
        ];
    }

    // Jumlah project aktif yang memakai tiap partner (vendor.vendor_id =
    // partner.id_session). Satu project dihitung sekali walau memakai
    // partner yang sama lebih dari satu kali. Hasil: [id_session partner => jumlah].
    public function get_jumlah_project_per_partner() {
        $rows = $this->db
            ->select('v.vendor_id, COUNT(DISTINCT p.id_session) AS jumlah', false)
            ->from('vendor v')
            ->join('project p', 'p.id_session = v.id_session')
            ->join('partner pt', 'pt.id_session = v.vendor_id')
            ->where('p.status', 'create')
            ->group_by('v.vendor_id')
            ->get()->result();

        $hasil = [];
        foreach ($rows as $r) {
            $hasil[$r->vendor_id] = (int) $r->jumlah;
        }
        return $hasil;
    }

    // Daftar project aktif yang memakai satu partner, terbaru dulu. ->detail
    // adalah isian Detail vendor (pekerjaan partner di project itu), bukan
    // detail project.
    public function get_project_pengguna($partner_id_session) {
        return $this->db
            ->select('p.id_session, p.project_name, p.client_name, p.event_date, p.location, MAX(v.detail) AS detail', false)
            ->from('vendor v')
            ->join('project p', 'p.id_session = v.id_session')
            ->where('v.vendor_id', $partner_id_session)
            ->where('p.status', 'create')
            ->group_by('p.id_session, p.project_name, p.client_name, p.event_date, p.location')
            ->order_by('p.event_date', 'desc')
            ->get()->result();
    }
}
