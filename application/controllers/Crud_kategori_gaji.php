<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_kategori_gaji extends CI_Controller {

	function __construct()
	{
	    parent::__construct();
	    $this->load->model('Gaji_model');
	}

	// Fitur ini ada di sub menu Fin & Acc, jadi akses levelnya sama dengan
	// menu itu di sidebar: developer, administrator, staff accounting.
	private function cek_akses()
	{
	    if (!in_array($this->session->level, ['1', '2', '3'])) {
	        redirect(base_url('panel'));
	        exit;
	    }
	}

	// Setting Salary: nama harus dipilih dulu dari dropdown baru data user itu
	// muncul (bukan langsung nampilin semua user sekaligus), bisa browse per
	// periode bulan juga. Tiap user bisa punya LEBIH DARI SATU kategori
	// salary sekaligus (mis. gaji pokok bulanan + komisi persentase) lewat
	// tabel penghubung user_kategori_gaji. Kalau user sudah dipilih, tiap
	// kategorinya dihitung jadi nominal aktual bulan itu:
	// - Bulanan: nominalnya sendiri, flat.
	// - Harian: nominal x jumlah hari Hadir di absensi bulan itu.
	// - Project: nominal x jumlah project yang event_date-nya di bulan itu
	//   (dari jadwal crew_projects, sama seperti dashboard staff).
	// - Persentase: persen x total pencapaian sales (closing) bulan itu,
	//   pakai aturan "achieved" yang sama dengan Aspanel::getEstimasiRevenue().
	public function rekap($user_id_session = null, $periode = null)
	{
	    $this->cek_akses();

	    if ($periode === null) {
	        $periode = date('Y-m');
	    }

	    $sql = "SELECT user.id_session, user.nama, user.level, user_level.user_level_nama
	            FROM user
	            JOIN user_level ON user_level.user_level_id = user.level
	            WHERE user.level IN ('1','2','3','4','7','9')
	            AND user.user_stat = 'Publish'
	            ORDER BY user.nama ASC";
	    $users = $this->db->query($sql)->result();

	    $sql_assign = "SELECT ukg.user_id_session, kategori_gaji.id, kategori_gaji.nama_kategori,
	                          kategori_gaji.satuan_gaji, kategori_gaji.nominal_gaji
	                   FROM user_kategori_gaji ukg
	                   JOIN kategori_gaji ON kategori_gaji.id = ukg.kategori_gaji_id";
	    $assignments = $this->db->query($sql_assign)->result();

	    $kategori_per_user = [];
	    foreach ($assignments as $a) {
	        $kategori_per_user[$a->user_id_session][] = $a;
	    }

	    $user_terpilih = null;
	    foreach ($users as $u) {
	        $u->kategori_list = $kategori_per_user[$u->id_session] ?? [];
	        if ($user_id_session !== null && $u->id_session === $user_id_session) {
	            $user_terpilih = $u;
	        }
	    }

	    $detail_gaji = [];
	    $total_gaji_periode = 0;
	    if ($user_terpilih !== null) {
	        foreach ($user_terpilih->kategori_list as $k) {
	            $detail = $this->Gaji_model->hitung_detail_gaji($user_terpilih->id_session, $k, $periode);
	            $detail_gaji[] = $detail;
	            $total_gaji_periode += $detail['jumlah'];
	        }
	    }

	    $data['daftar_user'] = $users;
	    $data['user_terpilih'] = $user_terpilih;
	    $data['periode'] = $periode;
	    $data['periode_sebelumnya'] = date('Y-m', strtotime($periode . '-01 -1 month'));
	    $data['periode_berikutnya'] = date('Y-m', strtotime($periode . '-01 +1 month'));
	    $data['detail_gaji'] = $detail_gaji;
	    $data['total_gaji_periode'] = $total_gaji_periode;
	    $data['daftar_kategori'] = $this->db->order_by('nama_kategori', 'ASC')->get('kategori_gaji')->result();

	    $this->load->view('backend/v_rekap_gaji', $data);
	}

	// Assign kategori salary satu user — bisa pilih lebih dari satu lewat
	// multi-select, dipanggil dari halaman rekap. Strategi: hapus semua
	// assignment lama user ini, lalu insert ulang sesuai pilihan terbaru
	// (lebih simpel & aman daripada diff manual).
	public function assign($user_id_session)
	{
	    $this->cek_akses();

	    $kategori_ids = $this->input->post('kategori_gaji_id');
	    $kategori_ids = is_array($kategori_ids) ? array_unique(array_map('intval', $kategori_ids)) : [];
	    $periode = $this->input->post('periode');

	    $this->db->where('user_id_session', $user_id_session)->delete('user_kategori_gaji');

	    if (!empty($kategori_ids)) {
	        $rows = [];
	        foreach ($kategori_ids as $kategori_id) {
	            $rows[] = ['user_id_session' => $user_id_session, 'kategori_gaji_id' => $kategori_id];
	        }
	        $this->db->insert_batch('user_kategori_gaji', $rows);
	    }

	    $this->session->set_flashdata('success', 'Kategori salary berhasil diperbarui.');
	    $tujuan = $user_id_session . (!empty($periode) ? '/' . $periode : '');
	    redirect(base_url('rekap-gaji/' . $tujuan));
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

	    list($nama_kategori, $satuan_gaji, $nominal_gaji, $error) = $this->validasi_kategori_gaji_post();
	    if ($error) {
	        $this->session->set_flashdata('error', $error);
	        redirect(base_url('rekap-gaji/kategori'));
	        return;
	    }

	    $this->db->insert('kategori_gaji', [
	        'nama_kategori' => $nama_kategori,
	        'satuan_gaji' => $satuan_gaji,
	        'nominal_gaji' => $nominal_gaji,
	    ]);

	    $this->session->set_flashdata('success', 'Kategori salary berhasil ditambahkan.');
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

	    list($nama_kategori, $satuan_gaji, $nominal_gaji, $error) = $this->validasi_kategori_gaji_post();
	    if ($error) {
	        $this->session->set_flashdata('error', $error);
	        redirect(base_url('rekap-gaji/kategori/edit/' . $id));
	        return;
	    }

	    $this->db->where('id', $id)->update('kategori_gaji', [
	        'nama_kategori' => $nama_kategori,
	        'satuan_gaji' => $satuan_gaji,
	        'nominal_gaji' => $nominal_gaji,
	    ]);

	    $this->session->set_flashdata('success', 'Kategori salary berhasil diperbarui.');
	    redirect(base_url('rekap-gaji/kategori'));
	}

	public function kategori_delete($id)
	{
	    $this->cek_akses();

	    // Lepas semua assignment user yang masih pakai kategori ini dulu,
	    // supaya tidak ada baris user_kategori_gaji yatim nunjuk ke
	    // kategori yang dihapus.
	    $this->db->where('kategori_gaji_id', $id)->delete('user_kategori_gaji');
	    $this->db->where('id', $id)->delete('kategori_gaji');

	    $this->session->set_flashdata('success', 'Kategori salary berhasil dihapus.');
	    redirect(base_url('rekap-gaji/kategori'));
	}

	private function validasi_kategori_gaji_post()
	{
	    $nama_kategori = trim((string) $this->input->post('nama_kategori'));
	    $satuan_gaji = $this->input->post('satuan_gaji');
	    $nominal_gaji = $this->input->post('nominal_gaji');

	    if ($nama_kategori === '') {
	        return [null, null, null, 'Nama kategori wajib diisi.'];
	    }
	    if (!in_array($satuan_gaji, ['Harian', 'Bulanan', 'Project', 'Persentase'], true)) {
	        return [null, null, null, 'Satuan salary tidak valid.'];
	    }
	    if (!is_numeric($nominal_gaji) || (float) $nominal_gaji < 0) {
	        return [null, null, null, 'Nominal salary tidak valid.'];
	    }
	    if ($satuan_gaji === 'Persentase' && (float) $nominal_gaji > 100) {
	        return [null, null, null, 'Nominal persentase tidak boleh lebih dari 100.'];
	    }

	    return [substr($nama_kategori, 0, 100), $satuan_gaji, (float) $nominal_gaji, null];
	}
}
