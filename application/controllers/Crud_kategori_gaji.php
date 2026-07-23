<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_kategori_gaji extends CI_Controller {

	// Fitur ini ada di sub menu Fin & Acc, jadi akses levelnya sama dengan
	// menu itu di sidebar: developer, administrator, staff accounting.
	private function cek_akses()
	{
	    if (!in_array($this->session->level, ['1', '2', '3'])) {
	        redirect(base_url('panel'));
	        exit;
	    }
	}

	// Rekap gaji: semua user staff internal (bukan client/guest/partner),
	// nama kategori + nominal gajinya kalau sudah di-assign.
	public function rekap()
	{
	    $this->cek_akses();

	    $sql = "SELECT user.id_session, user.nama, user.level, user_level.user_level_nama,
	                   user.kategori_gaji_id, kategori_gaji.nama_kategori, kategori_gaji.nominal_gaji
	            FROM user
	            JOIN user_level ON user_level.user_level_id = user.level
	            LEFT JOIN kategori_gaji ON kategori_gaji.id = user.kategori_gaji_id
	            WHERE user.level IN ('1','2','3','4','7','9')
	            AND user.user_stat = 'Publish'
	            ORDER BY user.nama ASC";

	    $data['rekap_gaji'] = $this->db->query($sql)->result();
	    $data['total_gaji'] = 0;
	    foreach ($data['rekap_gaji'] as $r) {
	        $data['total_gaji'] += (float) $r->nominal_gaji;
	    }
	    $data['daftar_kategori'] = $this->db->order_by('nama_kategori', 'ASC')->get('kategori_gaji')->result();

	    $this->load->view('backend/v_rekap_gaji', $data);
	}

	// Assign / ganti kategori gaji satu user, dipanggil dari dropdown inline
	// di halaman rekap.
	public function assign($user_id_session)
	{
	    $this->cek_akses();

	    $kategori_gaji_id = $this->input->post('kategori_gaji_id');
	    $kategori_gaji_id = ($kategori_gaji_id === '' || $kategori_gaji_id === null) ? null : (int) $kategori_gaji_id;

	    $this->db->where('id_session', $user_id_session)
	        ->update('user', ['kategori_gaji_id' => $kategori_gaji_id]);

	    $this->session->set_flashdata('success', 'Kategori gaji berhasil diperbarui.');
	    redirect(base_url('rekap-gaji'));
	}

	public function kategori()
	{
	    $this->cek_akses();

	    $data['daftar_kategori'] = $this->db->order_by('nama_kategori', 'ASC')->get('kategori_gaji')->result();
	    $this->load->view('backend/v_kategori_gaji', $data);
	}

	public function kategori_store()
	{
	    $this->cek_akses();

	    list($nama_kategori, $nominal_gaji, $error) = $this->validasi_kategori_gaji_post();
	    if ($error) {
	        $this->session->set_flashdata('error', $error);
	        redirect(base_url('rekap-gaji/kategori'));
	        return;
	    }

	    $this->db->insert('kategori_gaji', [
	        'nama_kategori' => $nama_kategori,
	        'nominal_gaji' => $nominal_gaji,
	    ]);

	    $this->session->set_flashdata('success', 'Kategori gaji berhasil ditambahkan.');
	    redirect(base_url('rekap-gaji/kategori'));
	}

	public function kategori_edit($id)
	{
	    $this->cek_akses();

	    $data['kategori'] = $this->db->get_where('kategori_gaji', ['id' => $id])->row();
	    if (!$data['kategori']) {
	        redirect(base_url('rekap-gaji/kategori'));
	        return;
	    }

	    $data['daftar_kategori'] = $this->db->order_by('nama_kategori', 'ASC')->get('kategori_gaji')->result();
	    $this->load->view('backend/v_kategori_gaji', $data);
	}

	public function kategori_update($id)
	{
	    $this->cek_akses();

	    list($nama_kategori, $nominal_gaji, $error) = $this->validasi_kategori_gaji_post();
	    if ($error) {
	        $this->session->set_flashdata('error', $error);
	        redirect(base_url('rekap-gaji/kategori/edit/' . $id));
	        return;
	    }

	    $this->db->where('id', $id)->update('kategori_gaji', [
	        'nama_kategori' => $nama_kategori,
	        'nominal_gaji' => $nominal_gaji,
	    ]);

	    $this->session->set_flashdata('success', 'Kategori gaji berhasil diperbarui.');
	    redirect(base_url('rekap-gaji/kategori'));
	}

	public function kategori_delete($id)
	{
	    $this->cek_akses();

	    // Lepas assignment user yang masih pakai kategori ini dulu, supaya
	    // tidak ada kategori_gaji_id yatim nunjuk ke baris yang dihapus.
	    $this->db->where('kategori_gaji_id', $id)->update('user', ['kategori_gaji_id' => null]);
	    $this->db->where('id', $id)->delete('kategori_gaji');

	    $this->session->set_flashdata('success', 'Kategori gaji berhasil dihapus.');
	    redirect(base_url('rekap-gaji/kategori'));
	}

	private function validasi_kategori_gaji_post()
	{
	    $nama_kategori = trim((string) $this->input->post('nama_kategori'));
	    $nominal_gaji = $this->input->post('nominal_gaji');

	    if ($nama_kategori === '') {
	        return [null, null, 'Nama kategori wajib diisi.'];
	    }
	    if (!is_numeric($nominal_gaji) || (float) $nominal_gaji < 0) {
	        return [null, null, 'Nominal gaji tidak valid.'];
	    }

	    return [substr($nama_kategori, 0, 100), (float) $nominal_gaji, null];
	}
}
