<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Model buat fitur "List Peralatan Event": kategori (WO, Fotobooth, dst)
// yang SHARED antara template global (tabel peralatan_event) dan tiap
// project (tabel client_peralatan_event) -- kategori tidak disalin ulang
// per project, cuma item-nya. Sama pola-nya dengan
// Susunan_acara_model/Client_susunan_acara_model.
class Peralatan_event_model extends CI_Model {

    // ================= Kategori (shared) =================

    public function get_kategori_all()
    {
        return $this->db->order_by('urutan', 'asc')->get('peralatan_event_kategori')->result();
    }

    public function get_kategori_by_session($id_session)
    {
        return $this->db->get_where('peralatan_event_kategori', ['id_session' => $id_session])->row();
    }

    public function get_kategori_next_urutan()
    {
        $row = $this->db->select_max('urutan')->get('peralatan_event_kategori')->row();
        return ($row && $row->urutan !== null) ? ((int) $row->urutan + 1) : 1;
    }

    public function insert_kategori($data)
    {
        return $this->db->insert('peralatan_event_kategori', $data);
    }

    public function update_kategori($id_session, $data)
    {
        $this->db->where('id_session', $id_session);
        return $this->db->update('peralatan_event_kategori', $data);
    }

    // Hapus kategori -- item template & SEMUA project yang masih pakai
    // kategori ini ikut terhapus (cascade manual, sama seperti
    // Susunan_acara_model::delete_kategori_by_session()).
    public function delete_kategori($id_session)
    {
        $this->db->where('kategori_id_session', $id_session)->delete('peralatan_event');
        $this->db->where('kategori_id_session', $id_session)->delete('client_peralatan_event');
        $this->db->where('id_session', $id_session);
        return $this->db->delete('peralatan_event_kategori');
    }

    public function swap_kategori_urutan($id_session, $direction)
    {
        $current = $this->get_kategori_by_session($id_session);
        if (!$current) {
            return false;
        }

        $this->db->where($direction === 'up' ? 'urutan <' : 'urutan >', $current->urutan);
        $this->db->order_by('urutan', $direction === 'up' ? 'desc' : 'asc');
        $neighbor = $this->db->limit(1)->get('peralatan_event_kategori')->row();

        if (!$neighbor) {
            return false;
        }

        $this->db->trans_start();
        $this->db->where('id_session', $current->id_session)->update('peralatan_event_kategori', ['urutan' => $neighbor->urutan]);
        $this->db->where('id_session', $neighbor->id_session)->update('peralatan_event_kategori', ['urutan' => $current->urutan]);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    // ================= Template global =================

    // Semua item template, dikelompokkan per kategori (urutan kategori &
    // urutan item di dalamnya sudah terurut). Dipakai halaman kelola template.
    public function get_template_grouped()
    {
        $kategoriList = $this->get_kategori_all();
        $itemsAll = $this->db->order_by('urutan', 'asc')->get('peralatan_event')->result();

        $grouped = [];
        foreach ($itemsAll as $item) {
            $grouped[$item->kategori_id_session][] = $item;
        }

        $result = [];
        foreach ($kategoriList as $kat) {
            $result[] = ['kategori' => $kat, 'items' => $grouped[$kat->id_session] ?? []];
        }

        return $result;
    }

    public function get_template_all()
    {
        return $this->db->order_by('urutan', 'asc')->get('peralatan_event')->result();
    }

    public function get_template_by_session($id_session)
    {
        return $this->db->get_where('peralatan_event', ['id_session' => $id_session])->row();
    }

    // Item template cuma dari satu kategori -- dipakai "Salin dari
    // Template" supaya cuma menarik kategori yang sedang aktif (mis. cuma
    // WO), tidak ikut menarik kategori lain (mis. Fotobooth).
    public function get_template_by_kategori($kategori_id_session)
    {
        return $this->db->where('kategori_id_session', $kategori_id_session)
            ->order_by('urutan', 'asc')
            ->get('peralatan_event')->result();
    }

    public function get_template_next_urutan($kategori_id_session)
    {
        $row = $this->db->select_max('urutan')
            ->where('kategori_id_session', $kategori_id_session)
            ->get('peralatan_event')->row();
        return ($row && $row->urutan !== null) ? ((int) $row->urutan + 1) : 1;
    }

    public function insert_template($data)
    {
        return $this->db->insert('peralatan_event', $data);
    }

    public function update_template($id_session, $data)
    {
        $this->db->where('id_session', $id_session);
        return $this->db->update('peralatan_event', $data);
    }

    public function delete_template($id_session)
    {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('peralatan_event');
    }

    // Tukar urutan DI KATEGORI YANG SAMA.
    public function swap_template_urutan($id_session, $direction)
    {
        $current = $this->get_template_by_session($id_session);
        if (!$current) {
            return false;
        }

        $this->db->where('kategori_id_session', $current->kategori_id_session);
        $this->db->where($direction === 'up' ? 'urutan <' : 'urutan >', $current->urutan);
        $this->db->order_by('urutan', $direction === 'up' ? 'desc' : 'asc');
        $neighbor = $this->db->limit(1)->get('peralatan_event')->row();

        if (!$neighbor) {
            return false;
        }

        $this->db->trans_start();
        $this->db->where('id_session', $current->id_session)->update('peralatan_event', ['urutan' => $neighbor->urutan]);
        $this->db->where('id_session', $neighbor->id_session)->update('peralatan_event', ['urutan' => $current->urutan]);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    // ================= Per project =================

    // Semua item milik satu project, dikelompokkan per kategori.
    // $skip_kosong = true buat cetak (kategori tanpa item disembunyikan),
    // false buat halaman kelola (semua kategori tampil, biar bisa nambah
    // item ke kategori yang masih kosong).
    public function get_by_project_grouped($project_id_session, $skip_kosong = false)
    {
        $kategoriList = $this->get_kategori_all();
        $itemsAll = $this->db->where('project_id_session', $project_id_session)
            ->order_by('urutan', 'asc')
            ->get('client_peralatan_event')->result();

        $grouped = [];
        foreach ($itemsAll as $item) {
            $grouped[$item->kategori_id_session][] = $item;
        }

        $result = [];
        foreach ($kategoriList as $kat) {
            $items = $grouped[$kat->id_session] ?? [];
            if ($skip_kosong && empty($items)) {
                continue;
            }
            $result[] = ['kategori' => $kat, 'items' => $items];
        }

        return $result;
    }

    public function get_by_project($project_id_session)
    {
        return $this->db->where('project_id_session', $project_id_session)
            ->order_by('urutan', 'asc')
            ->get('client_peralatan_event')->result();
    }

    // Item satu project TAPI cuma dari satu kategori -- dipakai buat
    // filter tampilan "WO" / "Fotobooth" di halaman daftar & cetak.
    public function get_by_project_kategori($project_id_session, $kategori_id_session)
    {
        return $this->db->where('project_id_session', $project_id_session)
            ->where('kategori_id_session', $kategori_id_session)
            ->order_by('urutan', 'asc')
            ->get('client_peralatan_event')->result();
    }

    public function get_by_session($id_session)
    {
        return $this->db->get_where('client_peralatan_event', ['id_session' => $id_session])->row();
    }

    public function count_by_project($project_id_session)
    {
        return $this->db->where('project_id_session', $project_id_session)->count_all_results('client_peralatan_event');
    }

    public function get_next_urutan($project_id_session, $kategori_id_session)
    {
        $row = $this->db->select_max('urutan')
            ->where('project_id_session', $project_id_session)
            ->where('kategori_id_session', $kategori_id_session)
            ->get('client_peralatan_event')->row();
        return ($row && $row->urutan !== null) ? ((int) $row->urutan + 1) : 1;
    }

    public function insert($data)
    {
        return $this->db->insert('client_peralatan_event', $data);
    }

    public function update($id_session, $data)
    {
        $this->db->where('id_session', $id_session);
        return $this->db->update('client_peralatan_event', $data);
    }

    public function delete($id_session)
    {
        $this->db->where('id_session', $id_session);
        return $this->db->delete('client_peralatan_event');
    }

    public function delete_by_project($project_id_session)
    {
        $this->db->where('project_id_session', $project_id_session);
        return $this->db->delete('client_peralatan_event');
    }

    // Tukar urutan DI PROJECT & KATEGORI YANG SAMA.
    public function swap_urutan($id_session, $direction)
    {
        $current = $this->get_by_session($id_session);
        if (!$current) {
            return false;
        }

        $this->db->where('project_id_session', $current->project_id_session);
        $this->db->where('kategori_id_session', $current->kategori_id_session);
        $this->db->where($direction === 'up' ? 'urutan <' : 'urutan >', $current->urutan);
        $this->db->order_by('urutan', $direction === 'up' ? 'desc' : 'asc');
        $neighbor = $this->db->limit(1)->get('client_peralatan_event')->row();

        if (!$neighbor) {
            return false;
        }

        $this->db->trans_start();
        $this->db->where('id_session', $current->id_session)->update('client_peralatan_event', ['urutan' => $neighbor->urutan]);
        $this->db->where('id_session', $neighbor->id_session)->update('client_peralatan_event', ['urutan' => $current->urutan]);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}
