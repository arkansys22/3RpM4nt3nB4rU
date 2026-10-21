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

    // ===== Jenis / kategori partner (tabel partner_jenis) =====

    // Daftar bawaan dipakai kalau tabel partner_jenis belum dimigrasi
    // (db/partner_jenis.sql), supaya form partner tidak error di server.
    private $jenis_bawaan = [
        'Venue', 'MC Akad', 'MC Pemberkatan', 'MC Resepsi', 'MC Adat Sunda', 'MC Adat Jawa',
        'Wedding Organizer', 'MUA', 'Attire', 'Perlengkapan Catering', 'Penyewaan Peralatan',
        'Catering', 'Dokumentasi', 'Dekorasi', 'Entertainment', 'Pendukung Pernikahan Lainnya',
    ];

    public function jenis_tersedia() {
        return $this->db->table_exists('partner_jenis');
    }

    // Semua jenis + jumlah partner (belum dihapus) yang memakainya.
    public function get_jenis_list() {
        if (!$this->jenis_tersedia()) {
            $hasil = [];
            foreach ($this->jenis_bawaan as $nama) {
                $hasil[] = (object) ['id' => null, 'nama' => $nama, 'jumlah' => 0];
            }
            return $hasil;
        }
        return $this->db
            ->select('j.id, j.nama, COUNT(p.id) AS jumlah', false)
            ->from('partner_jenis j')
            ->join('partner p', "p.type = j.nama AND p.status <> 'deleted'", 'left', false)
            ->group_by('j.id, j.nama')
            ->order_by('j.nama', 'asc')
            ->get()->result();
    }

    // Kembalikan string pesan error, atau true kalau berhasil.
    public function tambah_jenis($nama) {
        $nama = trim(preg_replace('/\s+/', ' ', (string) $nama));
        if ($nama === '') {
            return 'Nama jenis tidak boleh kosong.';
        }
        if (!$this->jenis_tersedia()) {
            return 'Tabel partner_jenis belum dibuat. Jalankan db/partner_jenis.sql dulu.';
        }
        if ($this->db->where('nama', $nama)->count_all_results('partner_jenis') > 0) {
            return 'Jenis "' . $nama . '" sudah ada.';
        }
        $this->db->insert('partner_jenis', ['nama' => $nama]);
        return true;
    }

    // Ganti nama jenis; partner yang memakai jenis lama ikut berubah, begitu
    // juga vendor.type di project yang menarik data dari partner itu.
    public function ubah_jenis($id, $nama_baru) {
        $nama_baru = trim(preg_replace('/\s+/', ' ', (string) $nama_baru));
        $jenis = $this->db->get_where('partner_jenis', ['id' => $id])->row();
        if (!$jenis) {
            return 'Jenis tidak ditemukan.';
        }
        if ($nama_baru === '') {
            return 'Nama jenis tidak boleh kosong.';
        }
        if ($nama_baru === $jenis->nama) {
            return true;
        }
        $dobel = $this->db->where('nama', $nama_baru)->where('id <>', $id)->count_all_results('partner_jenis');
        if ($dobel > 0) {
            return 'Jenis "' . $nama_baru . '" sudah ada.';
        }

        $this->db->trans_start();
        $this->db->where('id', $id)->update('partner_jenis', ['nama' => $nama_baru]);
        $this->db->where('type', $jenis->nama)->update('partner', ['type' => $nama_baru]);
        $this->db->query(
            'UPDATE vendor SET type = ? WHERE type = ? AND vendor_id IN (SELECT id_session FROM partner WHERE type = ?)',
            [$nama_baru, $jenis->nama, $nama_baru]
        );
        $this->db->trans_complete();
        return $this->db->trans_status() ? true : 'Gagal mengubah jenis.';
    }

    // Hanya boleh kalau tidak ada partner (termasuk yang di recycle bin) yang memakainya.
    public function hapus_jenis($id) {
        $jenis = $this->db->get_where('partner_jenis', ['id' => $id])->row();
        if (!$jenis) {
            return 'Jenis tidak ditemukan.';
        }
        if ($this->db->where('type', $jenis->nama)->count_all_results('partner') > 0) {
            return 'Jenis "' . $jenis->nama . '" masih dipakai partner, tidak bisa dihapus.';
        }
        $this->db->delete('partner_jenis', ['id' => $id]);
        return true;
    }
}
