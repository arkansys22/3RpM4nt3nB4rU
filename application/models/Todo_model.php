<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Sumber data widget "To Do List" di dashboard. Semua query hanya mencakup
// project aktif (status 'create') yang tanggal acaranya belum lewat.
class Todo_model extends CI_Model {

    // Isian inti data pengantin: kolom => label. Nilai kosong / NULL (dan
    // untuk 'anak ke', 0 -- default form) dianggap belum diisi. Nama
    // panggilan orang tua & pengganti sengaja tidak dihitung karena memang
    // boleh kosong.
    private $kolom_pengantin = [
        'f_bride_fname'      => 'Nama CPW',
        'f_bride_cname'      => 'Panggilan CPW',
        'f_bride_nchild'     => 'Anak ke CPW',
        'f_bride_fathername' => 'Ayah CPW',
        'f_bride_mothername' => 'Ibu CPW',
        'm_bride_fname'      => 'Nama CPP',
        'm_bride_cname'      => 'Panggilan CPP',
        'm_bride_nchild'     => 'Anak ke CPP',
        'm_bride_fathername' => 'Ayah CPP',
        'm_bride_mothername' => 'Ibu CPP',
    ];

    private function project_mendatang($select) {
        $this->db->select($select);
        $this->db->from('project p');
        $this->db->where('p.status', 'create');
        $this->db->where('p.event_date >=', date('Y-m-d'));
        $this->db->order_by('p.event_date', 'asc');
    }

    // Vendor per project yang perlu ditindaklanjuti: project yang belum punya
    // vendor sama sekali (->total = 0), atau punya vendor berstatus "Belum
    // Diketahui" (nama kosong, dihitung di ->jumlah).
    public function vendor_belum_diketahui() {
        $this->project_mendatang("p.id_session, p.project_name, p.client_name, p.event_date, COUNT(v.id) AS total, COALESCE(SUM(v.vendor = ''), 0) AS jumlah");
        $this->db->join('vendor v', 'v.id_session = p.id_session', 'left');
        $this->db->group_by('p.id_session, p.project_name, p.client_name, p.event_date');
        $this->db->having('total = 0 OR jumlah > 0', null, false);
        return $this->db->get()->result();
    }

    // Project yang data pengantinnya belum lengkap; tiap baris membawa
    // daftar label isian yang masih kosong di ->kurang.
    public function pengantin_belum_lengkap() {
        $select = 'p.id_session, p.project_name, p.client_name, p.event_date';
        foreach (array_keys($this->kolom_pengantin) as $k) {
            $select .= ', c.' . $k;
        }
        $this->project_mendatang($select);
        $this->db->join('clients c', 'c.id_session = p.id_session', 'left');

        $hasil = [];
        foreach ($this->db->get()->result() as $row) {
            $kurang = [];
            foreach ($this->kolom_pengantin as $k => $label) {
                $v = trim((string) $row->$k);
                if ($v === '' || (substr($k, -6) === '_nchild' && (int) $v === 0)) {
                    $kurang[] = $label;
                }
            }
            if ($kurang) {
                $row->kurang = $kurang;
                $hasil[] = $row;
            }
        }
        return $hasil;
    }

    // Project yang belum punya satu pun crew di crew_projects.
    public function project_tanpa_crew() {
        $this->project_mendatang('p.id_session, p.project_name, p.client_name, p.event_date');
        $this->db->where('NOT EXISTS (SELECT 1 FROM crew_projects cp WHERE cp.project_id = p.id_session)', null, false);
        return $this->db->get()->result();
    }

    // Project yang milestone agendanya belum semua terisi (tanggal kosong /
    // 0000-00-00, atau belum punya baris agenda sama sekali). Tiap baris
    // membawa label milestone yang kosong di ->kurang.
    public function agenda_belum_terisi() {
        $this->load->model("Agenda_model");
        $kolom = $this->Agenda_model->daftar_kolom();

        $select = "p.id_session, p.project_name, p.client_name, p.event_date";
        foreach (array_keys($kolom) as $k) {
            $select .= ", a.$k";
        }
        $this->project_mendatang($select);
        $this->db->join("agenda a", "a.id_session = p.id_session", "left");

        $hasil = [];
        foreach ($this->db->get()->result() as $row) {
            $kurang = [];
            foreach ($kolom as $k => $label) {
                $v = trim((string) $row->$k);
                if ($v === "" || strpos($v, "0000-00-00") === 0) {
                    $kurang[] = $label;
                }
            }
            if ($kurang) {
                $row->kurang = $kurang;
                $hasil[] = $row;
            }
        }
        return $hasil;
    }
}
