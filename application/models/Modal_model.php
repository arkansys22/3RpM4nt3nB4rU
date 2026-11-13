<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Modal (Fin & Acc): setoran modal pemilik/investor, saldo awal modal, dan penarikan modal/deviden.
|
| Pencatatan ke COA (tabel accounting, single-entry seperti fitur lain; semua nominal positif di akunnya masing-masing,
| seperti akun lain di sistem ini):
|   Setoran Modal            -> akun 3000    Modal
|   Saldo Awal Modal         -> akun 3100    Opening Balance Equity
|   Penarikan Modal / Deviden -> akun 3200.02 Deviden (pengurang modal)
| accounting_id_session = id_session baris modal, jadi edit & hapus menyinkronkan barisnya.
*/
class Modal_model extends CI_Model
{
    // jenis => nomor akun COA
    const AKUN = [
        'Setoran Modal' => '3000',
        'Saldo Awal Modal' => '3100',
        'Penarikan Modal / Deviden' => '3200.02',
    ];
    const PENGURANG = 'Penarikan Modal / Deviden';

    public function get_semua()
    {
        return $this->db->order_by('tanggal', 'DESC')->order_by('id', 'DESC')->get('modal')->result();
    }

    public function get_by_id_session($id_session)
    {
        return $this->db->get_where('modal', ['id_session' => $id_session])->row();
    }

    public function tambah($data)
    {
        $this->db->trans_start();
        $this->db->insert('modal', $data);
        $this->simpan_accounting($data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function ubah($id_session, $data)
    {
        $this->db->trans_start();
        $this->db->where('id_session', $id_session)->update('modal', $data);
        $data['id_session'] = $id_session;
        $this->simpan_accounting($data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function hapus($id_session)
    {
        $this->db->trans_start();
        $this->db->where('accounting_id_session', $id_session)->delete('accounting');
        $this->db->where('id_session', $id_session)->delete('modal');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Satu baris accounting per id_session: insert kalau belum ada, update kalau sudah
    private function simpan_accounting($m)
    {
        $baris = [
            'accounting_nomer_kategori' => $m['kategori'],
            'accounting_nominal' => (int) $m['nominal'],
            'accounting_tanggal' => $m['tanggal'],
            'accounting_nama_transaksi' => $m['jenis'] . ' - ' . $m['penanam'],
        ];
        $ada = $this->db->get_where('accounting', ['accounting_id_session' => $m['id_session']])->row();
        if ($ada) {
            $this->db->where('accounting_id_session', $m['id_session'])->update('accounting', $baris);
        } else {
            $baris['accounting_id_session'] = $m['id_session'];
            $baris['accounting_metode_transaksi'] = '';
            $this->db->insert('accounting', $baris);
        }
    }

    public function insert_log_activity($data_log)
    {
        return $this->db->insert('log_activity', $data_log);
    }
}
