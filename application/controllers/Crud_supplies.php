<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Stock Barang (menu Stock). Akses: Developer, Administrator, Staff Admin (sama seperti sebelumnya).
// Penjelasan struktur data & aturan saldo ada di Supplies_model.
class Crud_supplies extends CI_Controller
{
    const SATUAN = ['pcs', 'pack', 'box', 'lusin', 'rim', 'roll', 'botol', 'set', 'pasang', 'meter', 'kg', 'lembar', 'buah'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Supplies_model');
        $this->load->helper('laporan'); // lk_h, lk_tgl (pemformat bersama)
    }

    private function cek_akses()
    {
        if (!in_array($this->session->level, ['1', '2', '4'], true)) {
            redirect(base_url('panel'));
            exit;
        }
    }

    private function wajib_post()
    {
        if ($this->input->method() !== 'post') {
            redirect(base_url('supplies'));
            exit;
        }
    }

    // ================================================================= daftar stok

    public function index()
    {
        $this->cek_akses();

        list($dari, $sampai, $pakai_periode) = $this->baca_periode();
        $semua = $this->Supplies_model->daftar('created', $pakai_periode ? $dari : null, $pakai_periode ? $sampai : null);

        // filter (di PHP: jumlah barang kecil)
        $q = trim((string) $this->input->get('q'));
        $kat = (string) $this->input->get('kategori');
        $st = (string) $this->input->get('status');
        $urut = (string) $this->input->get('urut');
        $filtered = [];
        foreach ($semua as $b) {
            if ($q !== '' && mb_stripos($b->product_name, $q, 0, 'UTF-8') === false) continue;
            if ($kat === 'none' && $b->kategori_id !== null) continue;
            if ($kat !== '' && $kat !== 'none' && (string) $b->kategori_id !== $kat) continue;
            if (in_array($st, ['aman', 'menipis', 'habis'], true) && $b->status_stok !== $st) continue;
            $filtered[] = $b;
        }
        $kunci = ['nama' => 'product_name', 'stok' => 'stok', 'masuk' => 'total_masuk', 'keluar' => 'total_keluar', 'kategori' => 'kategori'];
        $urut = isset($kunci[$urut]) ? $urut : 'nama';
        $f = $kunci[$urut];
        usort($filtered, function ($a, $b) use ($f, $urut) {
            if ($urut === 'nama' || $urut === 'kategori') {
                return strcasecmp((string) $a->$f, (string) $b->$f) ?: strcasecmp($a->product_name, $b->product_name);
            }
            return ($b->$f <=> $a->$f) ?: strcasecmp($a->product_name, $b->product_name); // angka: terbesar dulu
        });

        // ringkasan (dari semua barang aktif, bukan hasil filter)
        $ring = ['jenis' => count($semua), 'unit' => 0, 'masuk' => 0, 'keluar' => 0, 'menipis' => 0, 'habis' => 0, 'tanpa_kategori' => 0];
        foreach ($semua as $b) {
            $ring['unit'] += $b->stok;
            $ring['masuk'] += $b->total_masuk;
            $ring['keluar'] += $b->total_keluar;
            if ($b->status_stok === 'menipis') $ring['menipis']++;
            if ($b->status_stok === 'habis') $ring['habis']++;
            if ($b->kategori_id === null) $ring['tanpa_kategori']++;
        }

        $data = [
            'daftar' => $filtered, 'ringkas' => $ring,
            'kategori_list' => $this->Supplies_model->kategori_semua(),
            'q' => $q, 'kat' => $kat, 'st' => $st, 'urut' => $urut, 'dari' => $dari, 'sampai' => $sampai,
            'pakai_periode' => $pakai_periode,
            'dari_in' => $this->tanggal_valid($this->input->get('dari'), false) ?: '',
            'sampai_in' => $this->tanggal_valid($this->input->get('sampai'), false) ?: '',
            'terbaru' => $this->Supplies_model->riwayat_semua(date('Y-m-d', strtotime('-30 days')), date('Y-m-d'), 8),
        ];
        $data['nama_user'] = $this->Supplies_model->nama_user(array_column($data['terbaru'], 'created_by'));
        $this->load->view('backend/stok/v_stok_index', $data);
    }

    // Periode filter kolom masuk/keluar (opsional): [dari, sampai, aktif]. Cukup salah satu diisi (sisi lain terbuka). Tanpa isian: aktif = false.
    private function baca_periode()
    {
        $dari = $this->tanggal_valid($this->input->get('dari'), false);
        $sampai = $this->tanggal_valid($this->input->get('sampai'), false);
        if ($dari === null && $sampai === null) {
            return [date('Y-m-01'), date('Y-m-d'), false];
        }
        $dari = $dari ?? '2000-01-01';
        $sampai = $sampai ?? '2100-12-31';
        return $dari <= $sampai ? [$dari, $sampai, true] : [$sampai, $dari, true];
    }
    // ================================================================= barang (tambah / ubah / hapus)

    public function create()
    {
        $this->cek_akses();
        $this->load->view('backend/stok/v_stok_barang', [
            'barang' => null, 'kategori_list' => $this->Supplies_model->kategori_semua(), 'satuan_list' => self::SATUAN,
            'old' => $this->session->flashdata('old'),
        ]);
    }

    public function store()
    {
        $this->cek_akses();
        $this->wajib_post();

        list($data, $stok_awal, $tanggal, $error) = $this->validasi_barang(null);
        if ($error) {
            $this->session->set_flashdata('error', $error);
            $this->session->set_flashdata('old', $this->isian(['product_name', 'kategori_id', 'satuan', 'stok_minimum', 'stok_awal', 'tanggal']));
            redirect(base_url('supplies/create'));
            return;
        }

        $id = $this->Supplies_model->barang_tambah($data, $stok_awal, $tanggal, $this->session->id_session);
        if (!$id) {
            $this->session->set_flashdata('error', 'Barang gagal disimpan. Coba lagi.');
            redirect(base_url('supplies/create'));
            return;
        }
        $this->catat_log('supplies/create', $id, 'Tambah Produk ' . $data['product_name']);
        $this->session->set_flashdata('success', 'Barang "' . $data['product_name'] . '" ditambahkan' . ($stok_awal > 0 ? ' dengan stok awal ' . $stok_awal . ' ' . $data['satuan'] : '') . '.');
        redirect(base_url('supplies/lihat/' . $id));
    }

    public function ubah($id_session = null)
    {
        $this->cek_akses();
        $barang = $this->Supplies_model->barang_by_id_session($id_session, 'created');
        if (!$barang) {
            $this->session->set_flashdata('error', 'Barang tidak ditemukan.');
            redirect(base_url('supplies'));
            return;
        }
        $this->load->view('backend/stok/v_stok_barang', [
            'barang' => $barang, 'kategori_list' => $this->Supplies_model->kategori_semua(), 'satuan_list' => self::SATUAN,
            'old' => $this->session->flashdata('old'),
        ]);
    }

    public function update($id_session = null)
    {
        $this->cek_akses();
        $this->wajib_post();
        $barang = $this->Supplies_model->barang_by_id_session($id_session, 'created');
        if (!$barang) {
            redirect(base_url('supplies'));
            return;
        }

        list($data, , , $error) = $this->validasi_barang($id_session);
        if ($error) {
            $this->session->set_flashdata('error', $error);
            $this->session->set_flashdata('old', $this->isian(['product_name', 'kategori_id', 'satuan', 'stok_minimum']));
            redirect(base_url('supplies/ubah/' . $id_session));
            return;
        }
        $this->Supplies_model->barang_ubah($id_session, $data);
        $this->catat_log('supplies/edit', $id_session, 'Edit Produk ' . $data['product_name']);
        $this->session->set_flashdata('success', 'Data barang diperbarui.');
        redirect(base_url('supplies/lihat/' . $id_session));
    }

    public function delete($id_session = null)
    {
        $this->cek_akses();
        $this->wajib_post();
        $b = $this->Supplies_model->barang_by_id_session($id_session, 'created');
        if ($b) {
            $this->Supplies_model->barang_set_status($id_session, 'deleted');
            $this->catat_log('supplies/delete', $id_session, 'Delete ' . $b->product_name);
            $this->session->set_flashdata('success', 'Barang "' . $b->product_name . '" dipindah ke Recycle Bin. Riwayat stoknya tetap tersimpan.');
        }
        redirect(base_url('supplies'));
    }

    public function recycle_bin()
    {
        $this->cek_akses();
        $this->load->view('backend/stok/v_stok_recycle', ['daftar' => $this->Supplies_model->daftar('deleted')]);
    }

    public function restore($id_session = null)
    {
        $this->cek_akses();
        $this->wajib_post();
        $b = $this->Supplies_model->barang_by_id_session($id_session, 'deleted');
        if ($b) {
            if ($this->Supplies_model->nama_barang_ada($b->product_name, $id_session)) {
                $this->session->set_flashdata('error', 'Sudah ada barang aktif bernama "' . $b->product_name . '". Ubah nama salah satunya dulu.');
                redirect(base_url('supplies/recycle_bin'));
                return;
            }
            $this->Supplies_model->barang_set_status($id_session, 'created');
            $this->catat_log('supplies/restore', $id_session, 'Restore ' . $b->product_name);
            $this->session->set_flashdata('success', 'Barang "' . $b->product_name . '" dipulihkan.');
        }
        redirect(base_url('supplies/recycle_bin'));
    }

    public function permanent_delete($id_session = null)
    {
        $this->cek_akses();
        $this->wajib_post();
        $b = $this->Supplies_model->barang_by_id_session($id_session, 'deleted');
        if ($b) {
            if ($this->Supplies_model->barang_hapus_permanen($id_session)) {
                $this->catat_log('supplies/delete_permanent', $id_session, 'Delete Permanent ' . $b->product_name);
                $this->session->set_flashdata('success', 'Barang "' . $b->product_name . '" beserta riwayat stoknya dihapus permanen.');
            } else {
                $this->session->set_flashdata('error', 'Barang tidak dihapus: sudah dipulihkan atau tidak ditemukan.');
            }
        } else {
            $this->session->set_flashdata('error', 'Barang tidak ada di Recycle Bin (mungkin sudah dipulihkan), jadi tidak dihapus permanen.');
        }
        redirect(base_url('supplies/recycle_bin'));
    }

    // Ubah kategori banyak barang sekaligus dari halaman daftar
    public function kategorikan()
    {
        $this->cek_akses();
        $this->wajib_post();
        $ids = $this->input->post('barang');
        $ids = is_array($ids) ? array_values(array_filter($ids, 'is_string')) : [];
        $kat = (string) $this->input->post('kategori_id');
        $balik = base_url('supplies' . ($this->input->post('balik') ? '?' . $this->bersihkan_balik($this->input->post('balik')) : ''));

        if (!$ids) {
            $this->session->set_flashdata('error', 'Pilih minimal satu barang dulu.');
        } elseif ($kat !== 'none' && !$this->Supplies_model->kategori_by_id($kat)) {
            $this->session->set_flashdata('error', 'Kategori tidak valid.');
        } else {
            $n = $this->Supplies_model->kategorikan($ids, $kat === 'none' ? null : (int) $kat);
            $this->catat_log('supplies/edit', $ids[0], 'Ubah kategori ' . count($ids) . ' barang');
            $this->session->set_flashdata('success', 'Kategori ' . count($ids) . ' barang diperbarui.');
        }
        redirect($balik);
    }

    // hanya izinkan query string sederhana (bukan URL penuh) untuk kembali ke daftar yang sama
    private function bersihkan_balik($qs)
    {
        parse_str((string) $qs, $arr);
        $ok = [];
        foreach (['q', 'kategori', 'status', 'urut', 'dari', 'sampai'] as $k) {
            if (isset($arr[$k]) && is_string($arr[$k])) {
                $ok[$k] = $arr[$k];
            }
        }
        return http_build_query($ok);
    }

    // ================================================================= barang masuk / keluar / koreksi

    public function masuk()
    {
        $this->form_gerakan('masuk');
    }

    public function keluar()
    {
        $this->form_gerakan('keluar');
    }

    public function opname()
    {
        $this->form_gerakan('koreksi');
    }

    private function form_gerakan($mode)
    {
        $this->cek_akses();
        $dipilih = (string) $this->input->get('b');
        $this->load->view('backend/stok/v_stok_gerakan', [
            'mode' => $mode,
            'barang_list' => $this->Supplies_model->daftar('created'),
            'dipilih' => $dipilih,
            'project_list' => $mode === 'keluar' ? $this->Supplies_model->project_pilihan() : [],
            'old' => $this->session->flashdata('old'),
        ]);
    }

    // POST barang masuk (rute lama: supplies/store2)
    public function store2()
    {
        $this->simpan_gerakan('masuk');
    }

    // POST barang keluar (rute lama: supplies/store3)
    public function store3()
    {
        $this->simpan_gerakan('keluar');
    }

    public function opname_store()
    {
        $this->simpan_gerakan('koreksi');
    }

    private function simpan_gerakan($mode)
    {
        $this->cek_akses();
        $this->wajib_post();

        $id_session = (string) $this->input->post('id_session');
        $balik = base_url('supplies/' . ($mode === 'masuk' ? 'masuk' : ($mode === 'keluar' ? 'keluar' : 'opname')) . '?b=' . rawurlencode($id_session));
        $barang = $this->Supplies_model->barang_by_id_session($id_session, 'created');
        $tanggal = $this->tanggal_valid($this->input->post('tanggal'), true);
        $detail = mb_substr(trim((string) $this->input->post('detail')), 0, 500, 'UTF-8');
        $project = null;

        $gagal = function ($pesan) use ($balik, $mode) {
            $this->session->set_flashdata('error', $pesan);
            $this->session->set_flashdata('old', $this->isian(['id_session', 'jumlah', 'tanggal', 'detail', 'project_id_session']));
            redirect($balik);
        };
        if (!$barang) {
            return $gagal('Pilih barang yang valid.');
        }
        if ($tanggal === null) {
            return $gagal('Tanggal tidak valid (tidak boleh di masa depan).');
        }

        if ($mode === 'koreksi') {
            $mentah = trim((string) $this->input->post('jumlah'));
            $fisik = $mentah === '' ? null : $this->angka($mentah, true); // kosong BUKAN 0: harus diisi
            if ($fisik === null) {
                return $gagal('Isi jumlah stok fisik dengan bilangan bulat 0 atau lebih (tanpa desimal).');
            }
            $jumlah = $fisik; // selisih terhadap stok sistem dihitung di model, di dalam kunci
            if ($detail === '') {
                $detail = 'Koreksi stok (stock opname): stok fisik ' . $fisik;
            }
        } else {
            $jumlah = $this->angka($this->input->post('jumlah'), false);
            if ($jumlah === null || $jumlah < 1) {
                return $gagal('Jumlah harus bilangan bulat lebih dari 0, tanpa desimal (maksimal ' . number_format(Supplies_model::MAKS_JUMLAH, 0, ',', '.') . ').');
            }
            if ($mode === 'keluar') {
                $project = $this->project_valid($this->input->post('project_id_session'));
            }
        }

        $hasil = $this->Supplies_model->catat($id_session, $mode, $jumlah, $tanggal, $detail, $project, $this->session->id_session);
        if ($hasil === 'sama') {
            $this->session->set_flashdata('success', 'Stok fisik sama dengan stok di sistem; tidak ada yang dikoreksi.');
            redirect(base_url('supplies/lihat/' . $id_session));
            return;
        }
        if ($mode === 'koreksi') {
            $jumlah = $this->Supplies_model->selisih_terakhir; // selisih bertanda untuk pesan
        }
        if ($hasil === 'stok') {
            $this->session->set_flashdata('error', 'Stok tidak mencukupi: saldo barang akan menjadi minus pada ' . lk_tgl($this->Supplies_model->saldo_negatif_di) . '. Stok saat ini ' . $this->Supplies_model->stok_sekarang($id_session) . ' ' . $barang->satuan . '.');
            $this->session->set_flashdata('old', $this->isian(['id_session', 'jumlah', 'tanggal', 'detail', 'project_id_session']));
            redirect($balik);
            return;
        }
        if ($hasil !== true) {
            return $gagal('Pencatatan gagal. Coba lagi.');
        }

        $label = $mode === 'masuk' ? 'Barang Masuk ' : ($mode === 'keluar' ? 'Barang Keluar ' : 'Koreksi Stok ');
        $this->catat_log($mode === 'masuk' ? 'supplies/editin' : ($mode === 'keluar' ? 'supplies/editout' : 'supplies/opname'), $id_session, $label . $barang->product_name);
        $sisa = $this->Supplies_model->stok_sekarang($id_session);
        $this->session->set_flashdata('success', ($mode === 'masuk' ? 'Barang masuk ' : ($mode === 'keluar' ? 'Barang keluar ' : 'Koreksi stok ')) . abs($jumlah) . ' ' . $barang->satuan . ' dicatat. Stok sekarang: ' . $sisa . ' ' . $barang->satuan . '.');
        redirect(base_url('supplies/lihat/' . $id_session));
    }

    // ================================================================= riwayat per barang

    public function lihat($id_session = null)
    {
        $this->cek_akses();
        $barang = $this->Supplies_model->barang_detail($id_session);
        if (!$barang) {
            $this->session->set_flashdata('error', 'Barang tidak ditemukan.');
            redirect(base_url('supplies'));
            return;
        }
        $dari = $this->tanggal_valid($this->input->get('dari'), false);
        $sampai = $this->tanggal_valid($this->input->get('sampai'), false);
        if ($dari !== null && $sampai !== null && $dari > $sampai) {
            list($dari, $sampai) = [$sampai, $dari];
        }
        $riwayat = $this->Supplies_model->riwayat($id_session, $dari, $sampai);
        $this->load->view('backend/stok/v_stok_riwayat', [
            'barang' => $barang, 'riwayat' => $riwayat, 'dari' => $dari, 'sampai' => $sampai,
            'total_periode' => ($dari !== null || $sampai !== null) ? $this->Supplies_model->total_periode($id_session, $dari, $sampai) : null,
            'nama_user' => $this->Supplies_model->nama_user(array_column($riwayat, 'created_by')),
            'nama_project' => $this->Supplies_model->nama_project(array_column($riwayat, 'project_id_session')),
        ]);
    }

    public function gerakan($id_sessionstock = null)
    {
        $this->cek_akses();
        $g = $this->Supplies_model->gerakan_by_id($id_sessionstock);
        if (!$g || $g->status === Supplies_model::BATAL || $g->status_barang !== 'created' || strpos((string) $g->status, 'Penyesuaian') === 0) {
            $this->session->set_flashdata('error', 'Pergerakan tidak ditemukan, sudah dibatalkan, atau berupa koreksi stok (koreksi tidak diedit; batalkan lalu buat koreksi baru).');
            redirect(base_url('supplies'));
            return;
        }
        $this->load->view('backend/stok/v_stok_gerakan_edit', [
            'g' => $g, 'project_list' => (int) $g->goods_out < 0 ? $this->Supplies_model->project_pilihan() : [],
            'project_sekarang' => $g->project_id_session ? ($this->Supplies_model->nama_project([$g->project_id_session])[$g->project_id_session] ?? null) : null,
            'old' => $this->session->flashdata('old'),
        ]);
    }

    public function gerakan_update($id_sessionstock = null)
    {
        $this->cek_akses();
        $this->wajib_post();
        $g = $this->Supplies_model->gerakan_by_id($id_sessionstock);
        if (!$g) {
            redirect(base_url('supplies'));
            return;
        }
        $balik = base_url('supplies/gerakan/' . $id_sessionstock);
        $jumlah = $this->angka($this->input->post('jumlah'), false);
        $tanggal = $this->tanggal_valid($this->input->post('tanggal'), true);
        if ($jumlah === null || $jumlah < 1 || $tanggal === null) {
            $this->session->set_flashdata('error', 'Jumlah harus bilangan bulat lebih dari 0 (tanpa desimal) dan tanggal valid (tidak di masa depan).');
            redirect($balik);
            return;
        }
        $project = (int) $g->goods_out < 0 ? $this->project_valid($this->input->post('project_id_session')) : null;
        $hasil = $this->Supplies_model->gerakan_ubah($id_sessionstock, $jumlah, $tanggal, mb_substr(trim((string) $this->input->post('detail')), 0, 500, 'UTF-8'), $project);
        if ($hasil === 'stok') {
            $this->session->set_flashdata('error', 'Perubahan ditolak: saldo barang akan menjadi minus pada ' . lk_tgl($this->Supplies_model->saldo_negatif_di) . '.');
            redirect($balik);
            return;
        }
        if ($hasil !== true) {
            $this->session->set_flashdata('error', 'Perubahan gagal. Coba lagi.');
            redirect($balik);
            return;
        }
        $this->catat_log('supplies/edit', $g->id_session, 'Edit pergerakan ' . $g->product_name);
        $this->session->set_flashdata('success', 'Pergerakan diperbarui dan saldo dihitung ulang.');
        redirect(base_url('supplies/lihat/' . $g->id_session));
    }

    public function gerakan_batal($id_sessionstock = null)
    {
        $this->cek_akses();
        $this->wajib_post();
        $g = $this->Supplies_model->gerakan_by_id($id_sessionstock);
        if (!$g) {
            redirect(base_url('supplies'));
            return;
        }
        $hasil = $this->Supplies_model->gerakan_batal($id_sessionstock);
        if ($hasil === 'stok') {
            $this->session->set_flashdata('error', 'Tidak bisa dibatalkan: saldo barang akan menjadi minus pada ' . lk_tgl($this->Supplies_model->saldo_negatif_di) . '.');
        } elseif ($hasil !== true) {
            $this->session->set_flashdata('error', 'Pergerakan tidak ditemukan atau sudah dibatalkan.');
        } else {
            $this->catat_log('supplies/edit', $g->id_session, 'Batalkan pergerakan ' . $g->product_name);
            $this->session->set_flashdata('success', 'Pergerakan dibatalkan (tetap tampil di riwayat, tidak dihitung) dan saldo dihitung ulang.');
        }
        redirect(base_url('supplies/lihat/' . $g->id_session));
    }

    // ================================================================= kategori

    public function kategori()
    {
        $this->cek_akses();
        $edit = $this->input->get('ubah') ? $this->Supplies_model->kategori_by_id($this->input->get('ubah')) : null;
        $this->load->view('backend/stok/v_stok_kategori', [
            'kategori_list' => $this->Supplies_model->kategori_semua(), 'edit' => $edit, 'old' => $this->session->flashdata('old'),
        ]);
    }

    public function kategori_store()
    {
        $this->cek_akses();
        $this->wajib_post();
        list($nama, $ket, $error) = $this->validasi_kategori(0);
        if ($error) {
            $this->session->set_flashdata('error', $error);
            $this->session->set_flashdata('old', $this->isian(['nama', 'keterangan']));
        } else {
            $this->Supplies_model->kategori_tambah($nama, $ket);
            $this->catat_log('supplies/kategori', '-', 'Tambah kategori ' . $nama);
            $this->session->set_flashdata('success', 'Kategori "' . $nama . '" ditambahkan.');
        }
        redirect(base_url('supplies/kategori'));
    }

    public function kategori_update($id = null)
    {
        $this->cek_akses();
        $this->wajib_post();
        if (!$this->Supplies_model->kategori_by_id($id)) {
            redirect(base_url('supplies/kategori'));
            return;
        }
        list($nama, $ket, $error) = $this->validasi_kategori((int) $id);
        if ($error) {
            $this->session->set_flashdata('error', $error);
            redirect(base_url('supplies/kategori?ubah=' . (int) $id));
            return;
        }
        $this->Supplies_model->kategori_ubah($id, $nama, $ket);
        $this->catat_log('supplies/kategori', '-', 'Edit kategori ' . $nama);
        $this->session->set_flashdata('success', 'Kategori diperbarui.');
        redirect(base_url('supplies/kategori'));
    }

    public function kategori_delete($id = null)
    {
        $this->cek_akses();
        $this->wajib_post();
        $k = $this->Supplies_model->kategori_by_id($id);
        if ($k) {
            $this->Supplies_model->kategori_hapus($id);
            $this->catat_log('supplies/kategori', '-', 'Hapus kategori ' . $k->nama);
            $this->session->set_flashdata('success', 'Kategori "' . $k->nama . '" dihapus; barang di dalamnya menjadi belum dikategorikan.');
        }
        redirect(base_url('supplies/kategori'));
    }

    private function validasi_kategori($kecuali_id)
    {
        $nama = mb_substr(trim((string) $this->input->post('nama')), 0, 100, 'UTF-8');
        $ket = mb_substr(trim((string) $this->input->post('keterangan')), 0, 255, 'UTF-8');
        if ($nama === '') {
            return [null, null, 'Nama kategori wajib diisi.'];
        }
        if ($this->Supplies_model->kategori_nama_ada($nama, $kecuali_id)) {
            return [null, null, 'Kategori "' . $nama . '" sudah ada.'];
        }
        return [$nama, $ket, null];
    }

    // ================================================================= stok menipis & ekspor

    public function restock()
    {
        $this->cek_akses();
        $butuh = [];
        foreach ($this->Supplies_model->daftar('created') as $b) {
            if ($b->status_stok !== 'aman') {
                $butuh[] = $b;
            }
        }
        usort($butuh, function ($a, $b) {
            return [$a->status_stok === 'habis' ? 0 : 1, (string) $a->kategori, $a->product_name] <=> [$b->status_stok === 'habis' ? 0 : 1, (string) $b->kategori, $b->product_name];
        });
        $this->load->view('backend/stok/v_stok_menipis', ['butuh' => $butuh]);
    }

    // Ekspor Excel (.xls berisi tabel): daftar stok + riwayat pergerakan pada periode
    public function export()
    {
        $this->cek_akses();
        $dari = $this->tanggal_valid($this->input->get('dari'), false) ?: date('Y-m-01');
        $sampai = $this->tanggal_valid($this->input->get('sampai'), false) ?: date('Y-m-d');
        if ($dari > $sampai) {
            list($dari, $sampai) = [$sampai, $dari];
        }
        $data = [
            'daftar' => $this->Supplies_model->daftar('created', $dari, $sampai),
            'riwayat' => $this->Supplies_model->riwayat_semua($dari, $sampai),
            'dari' => $dari, 'sampai' => $sampai, 'dibuat' => date('d-m-Y H:i'),
        ];
        $data['nama_user'] = $this->Supplies_model->nama_user(array_column($data['riwayat'], 'created_by'));
        $data['nama_project'] = $this->Supplies_model->nama_project(array_column($data['riwayat'], 'project_id_session'));
        $this->output
            ->set_header('Content-Type: application/vnd.ms-excel; charset=utf-8')
            ->set_header('Content-Disposition: attachment; filename="Stock-Barang_' . $dari . '_sd_' . $sampai . '.xls"')
            ->set_header('Cache-Control: no-store');
        $this->load->view('backend/stok/v_stok_excel', $data);
    }

    // ================================================================= helper

    private function validasi_barang($id_session)
    {
        $nama = mb_substr(trim((string) $this->input->post('product_name')), 0, 255, 'UTF-8');
        $kat = (string) $this->input->post('kategori_id');
        $satuan = mb_substr(trim((string) $this->input->post('satuan')), 0, 30, 'UTF-8');
        $min = $this->angka($this->input->post('stok_minimum'), true);
        $awal = $id_session === null ? $this->angka($this->input->post('stok_awal'), true) : 0;
        $tanggal = $id_session === null ? $this->tanggal_valid($this->input->post('tanggal'), true) : date('Y-m-d');

        if ($nama === '') return [null, 0, null, 'Nama barang wajib diisi.'];
        if ($this->Supplies_model->nama_barang_ada($nama, (string) $id_session)) return [null, 0, null, 'Barang "' . $nama . '" sudah ada.'];
        if ($kat !== '' && $kat !== 'none' && !$this->Supplies_model->kategori_by_id($kat)) return [null, 0, null, 'Kategori tidak valid.'];
        if ($satuan === '') return [null, 0, null, 'Satuan wajib diisi (mis. pcs, pack, box).'];
        if ($min === null) return [null, 0, null, 'Stok minimum harus angka 0 atau lebih.'];
        if ($awal === null) return [null, 0, null, 'Stok awal harus angka 0 atau lebih.'];
        if ($tanggal === null) return [null, 0, null, 'Tanggal tidak valid (tidak boleh di masa depan).'];

        return [[
            'product_name' => $nama,
            'kategori_id' => ($kat === '' || $kat === 'none') ? null : (int) $kat,
            'satuan' => $satuan,
            'stok_minimum' => $min,
        ], $awal, $tanggal, null];
    }

    // Bilangan bulat tanpa tanda. Titik hanya boleh sebagai pemisah ribuan (1.500); "2.5" atau "0.5" DITOLAK (kolom stok bilangan bulat,
    // titik tidak boleh dibuang diam-diam karena "2.5" akan menjadi 25). $boleh_nol: 0 diperbolehkan; kosong -> 0 (atau null bila !$boleh_nol)
    private function angka($mentah, $boleh_nol)
    {
        $b = trim((string) $mentah);
        if ($b === '') {
            return $boleh_nol ? 0 : null;
        }
        if (strpos($b, '.') !== false) {
            if (!preg_match('/^\d{1,3}(\.\d{3})+$/', $b)) {
                return null;
            }
            $b = str_replace('.', '', $b);
        }
        if (!ctype_digit($b) || strlen($b) > 9) {
            return null;
        }
        $n = (int) $b;
        return $n > Supplies_model::MAKS_JUMLAH ? null : $n;
    }
    // Y-m-d valid (tahun 2000-2100). $tolak_masa_depan: tanggal setelah hari ini ditolak. Kosong -> hari ini bila $tolak_masa_depan
    private function tanggal_valid($tgl, $tolak_masa_depan)
    {
        if ($tgl === null) {
            $tgl = ''; // kolom tidak terkirim = kosong
        }
        if (!is_string($tgl)) {
            return null; // mis. ?dari[]=x
        }
        $tgl = trim($tgl);
        if ($tgl === '') {
            return $tolak_masa_depan ? date('Y-m-d') : null;
        }
        $d = DateTime::createFromFormat('Y-m-d', $tgl);
        if (!$d || $d->format('Y-m-d') !== $tgl || (int) $d->format('Y') < 2000 || (int) $d->format('Y') > 2100) {
            return null;
        }
        if ($tolak_masa_depan && $tgl > date('Y-m-d')) {
            return null;
        }
        return $tgl;
    }

    // id_session project yang benar-benar ada, atau null
    private function project_valid($id)
    {
        $id = is_string($id) ? trim($id) : '';
        if ($id === '') {
            return null;
        }
        return $this->Supplies_model->nama_project([$id]) ? $id : null;
    }

    private function isian($kunci)
    {
        $o = [];
        foreach ($kunci as $k) {
            $v = $this->input->post($k);
            $o[$k] = is_string($v) ? $v : '';
        }
        return $o;
    }

    private function catat_log($modul, $dokumen, $status)
    {
        if ($this->agent->is_browser()) {
            $agent = 'Desktop ' . $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = 'Mobile ' . $this->agent->mobile() . ' ' . $this->agent->version();
        } else {
            $agent = 'Unidentified User Agent';
        }
        $ip = $this->input->ip_address();
        $this->Supplies_model->insert_log_activity([
            'log_activity_user_id' => $this->session->id_session,
            'log_activity_modul' => $modul,
            'log_activity_document_no' => $dokumen,
            'log_activity_status' => $status,
            'log_activity_waktu' => date('Y-m-d H:i:s'),
            'log_activity_platform' => $agent,
            'log_activity_ip' => $ip . '<br>(' . get_location_from_ip($ip) . ')',
        ]);
    }
}
