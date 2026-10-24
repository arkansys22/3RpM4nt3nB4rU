<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pengaturan umum website: nama website, logo, logo sidebar, favicon, nomor WhatsApp, alamat website (URL).
 * Datanya di tabel `identitas` (lihat Pengaturan_model); dipakai di seluruh aplikasi lewat helper
 * pengaturan_website(), favicon_url(), logo_login_url(), logo_sidebar_url() di customs_helper.php.
 */
class Crud_pengaturan_website extends CI_Controller {

    private $folder = 'assets/uploads/pengaturan/';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pengaturan_model');
    }

    private function cek_akses()
    {
        if (!in_array($this->session->level, ['1', '2'])) {
            redirect(base_url());
            exit;
        }
    }

    public function index()
    {
        $this->cek_akses();
        $data['tiktok_ada'] = $this->Pengaturan_model->tiktok_tersedia();
        $data['kode_ada'] = $this->Pengaturan_model->kode_transaksi_tersedia();
        $data['pengaturan'] = $this->Pengaturan_model->get();
        $data['rekening_ada'] = $this->Pengaturan_model->rekening_tersedia();
        $data['rekening'] = $this->Pengaturan_model->get_rekening();
        // ?edit_rekening=<id>: isi form rekening dengan data rekening itu
        $data['edit_rekening'] = $data['rekening_ada'] && (int) $this->input->get('edit_rekening') > 0
            ? $this->Pengaturan_model->get_rekening_by_id($this->input->get('edit_rekening')) : null;
        $this->load->view('backend/v_pengaturan_website', $data);
    }

    // Upload satu gambar; hasil: nama file ("pengaturan/xxx.png"), null kalau tidak ada upload, false kalau gagal.
    private function upload_gambar($field)
    {
        if (empty($_FILES[$field]['name'])) {
            return null;
        }
        if (!is_dir(FCPATH . $this->folder)) {
            @mkdir(FCPATH . $this->folder, 0755, true);
        }
        $this->load->library('upload');
        $this->upload->initialize([
            'upload_path'   => FCPATH . $this->folder,
            'allowed_types' => 'png|jpg|jpeg|gif|webp|ico',
            'max_size'      => 2048,
            'encrypt_name'  => true,
        ], true);
        if (!$this->upload->do_upload($field)) {
            return false;
        }
        return 'pengaturan/' . $this->upload->data('file_name');
    }

    // Hapus file lama hanya kalau itu hasil upload fitur ini (awalan "pengaturan/").
    private function hapus_file_lama($nilai)
    {
        if (is_string($nilai) && strpos($nilai, 'pengaturan/') === 0 && strpos($nilai, '..') === false) {
            @unlink(FCPATH . 'assets/uploads/' . $nilai);
        }
    }

    // Akun sosial media: boleh @username, username saja, atau link lengkap -> disimpan sebagai link
    // http/https. Kembalikan [nilai, pesan_error|null].
    private function normalisasi_akun($input, $basis, $awalan_user, $maks, $lama)
    {
        $v = trim((string) $input);
        if ($v === '') {
            return ['', null];
        }
        if (preg_match('#^https?://#i', $v)) {
            $url = $v;
        } elseif (preg_match('/^@?[A-Za-z0-9._]+$/', $v)) {
            $url = $basis . $awalan_user . ltrim($v, '@');
        } elseif (preg_match('#^[a-z0-9.-]+\.[a-z]{2,}(/.*)?$#i', $v)) {
            $url = 'https://' . $v;
        } else {
            return [(string) $lama, 'Format akun tidak dikenali: ' . htmlspecialchars($v) . '.'];
        }
        if (strlen($url) > $maks) {
            return [(string) $lama, 'Link akun terlalu panjang (maks ' . $maks . ' karakter).'];
        }
        return [$url, null];
    }
    public function update()
    {
        $this->cek_akses();
        $lama = $this->Pengaturan_model->get();
        $galat = [];

        $nama = trim((string) $this->input->post('nama_website'));
        if ($nama === '') {
            $this->session->set_flashdata('Error', 'Nama website wajib diisi.');
            redirect('pengaturan-website');
            return;
        }

        // WhatsApp: hanya angka, spasi, +, -, ()
        // Nomor WhatsApp: boleh lebih dari satu (whatsapp[]); hanya angka, spasi, +, -, tanda kurung; unik; disimpan satu per baris
        $daftarWa = [];
        foreach ((array) $this->input->post('whatsapp') as $nomorWa) {
            $nomorWa = trim(preg_replace('/[^0-9+\-\s()]/', '', (string) $nomorWa));
            if ($nomorWa !== '') {
                $daftarWa[$nomorWa] = $nomorWa;
            }
        }
        $wa = implode("\n", array_slice(array_values($daftarWa), 0, 10));

        // Alamat website: tambahkan https:// kalau belum ada, hanya http/https
        $url = trim((string) $this->input->post('url'));
        if ($url !== '') {
            if (!preg_match('#^[a-z][a-z0-9+.-]*://#i', $url)) {
                $url = 'https://' . $url;
            }
            if (!preg_match('#^https?://#i', $url) || strlen($url) > 100) {
                $galat[] = 'Alamat website harus berupa URL http/https (maks 100 karakter).';
                $url = (string) $lama->url;
            }
        }

        $data = [
            'nama_website' => mb_substr($nama, 0, 100),
            'whatsapp'     => $wa,
            'url'          => $url,
            'alamat'       => trim((string) $this->input->post('alamat')),
        ];

        // Akun Instagram & TikTok
        list($data['instagram'], $e1) = $this->normalisasi_akun($this->input->post('instagram'), 'https://www.instagram.com/', '', 100, $lama->instagram);
        if ($e1) { $galat[] = 'Instagram: ' . $e1; }
        if ($this->Pengaturan_model->tiktok_tersedia()) {
            list($data['tiktok'], $e2) = $this->normalisasi_akun($this->input->post('tiktok'), 'https://www.tiktok.com/', '@', 150, $lama->tiktok ?? '');
            if ($e2) { $galat[] = 'TikTok: ' . $e2; }
        }

        // Gambar: logo (login), logo sidebar (latar gelap), favicon
        foreach (['logo' => 'logo', 'mini_logo' => 'logo_sidebar', 'favicon' => 'favicon'] as $kolom => $field) {
            if ($this->input->post('hapus_' . $field)) {
                $this->hapus_file_lama($lama->$kolom);
                $data[$kolom] = '';
                continue;
            }
            $hasil = $this->upload_gambar($field);
            if ($hasil === false) {
                $galat[] = 'Gagal upload ' . str_replace('_', ' ', $field) . ': ' . strip_tags($this->upload->display_errors('', ''));
            } elseif ($hasil !== null) {
                $this->hapus_file_lama($lama->$kolom);
                $data[$kolom] = $hasil;
            }
        }

        // Kode awal nomor invoice & kwitansi (3 karakter huruf/angka)
        if ($this->Pengaturan_model->kode_transaksi_tersedia()) {
            $lama = $this->Pengaturan_model->get(); // ambil ulang: kolom kode baru mungkin baru dibuat
            $kodeBaru = [
                'invoice' => strtoupper(trim((string) $this->input->post('kode_invoice'))),
                'kwitansi' => strtoupper(trim((string) $this->input->post('kode_kwitansi'))),
            ];
            $sekarang = [
                'invoice' => (string) ($lama->kode_invoice ?: 'IMB'),
                'kwitansi' => (string) ($lama->kode_kwitansi ?: 'MBP'),
            ];
            $kodeOk = true;
            foreach ($kodeBaru as $jenis => $k) {
                if (!preg_match('/^[A-Z0-9]{3}$/', $k)) {
                    $galat[] = 'Kode ' . $jenis . ' harus tepat 3 huruf/angka.';
                    $kodeOk = false;
                }
            }
            if ($kodeOk) {
                // semua kode yang pernah/sedang dipakai kedua jenis tidak boleh bentrok
                $pakaiInvoice = array_unique(array_merge(['IMB', $sekarang['invoice']], array_filter(explode(',', (string) $lama->kode_invoice_riwayat))));
                $pakaiKwitansi = array_unique(array_merge(['MBP', $sekarang['kwitansi']], array_filter(explode(',', (string) $lama->kode_kwitansi_riwayat))));
                if ($kodeBaru['invoice'] === $kodeBaru['kwitansi']) {
                    $galat[] = 'Kode invoice dan kwitansi tidak boleh sama.';
                    $kodeOk = false;
                } elseif (in_array($kodeBaru['invoice'], $pakaiKwitansi, true) || in_array($kodeBaru['kwitansi'], $pakaiInvoice, true)) {
                    $galat[] = 'Kode tersebut pernah dipakai oleh jenis dokumen lain, pilih kode berbeda.';
                    $kodeOk = false;
                }
            }
            if ($kodeOk) {
                foreach ($kodeBaru as $jenis => $k) {
                    $data['kode_' . $jenis] = $k;
                    // kode lama masuk riwayat supaya dokumen lama tetap dikenali sebagai invoice/kwitansi
                    $riwayat = array_filter(array_map('trim', explode(',', (string) $lama->{'kode_' . $jenis . '_riwayat'})));
                    if ($sekarang[$jenis] !== $k) {
                        $riwayat[] = $sekarang[$jenis];
                    }
                    $data['kode_' . $jenis . '_riwayat'] = implode(',', array_unique($riwayat));
                }
            }
        }

        $this->Pengaturan_model->update($data);

        if ($galat) {
            $this->session->set_flashdata('Error', implode(' ', $galat) . ' Pengaturan lain sudah disimpan.');
        } else {
            $this->session->set_flashdata('Success', 'Pengaturan website berhasil disimpan.');
        }
        redirect('pengaturan-website');
    }

    // Tambah rekening baru, atau simpan perubahan kalau ada id_rekening.
    public function rekening_simpan()
    {
        $this->cek_akses();
        if (!$this->Pengaturan_model->rekening_tersedia()) {
            $this->session->set_flashdata('Error', 'Tabel rekening_website belum dibuat. Jalankan db/rekening_website.sql dulu.');
            redirect('pengaturan-website#rekening');
            return;
        }
        $bank = mb_substr(trim((string) $this->input->post('nama_bank')), 0, 100);
        $nomor = mb_substr(trim((string) $this->input->post('nomor_rekening')), 0, 50);
        $atas = mb_substr(trim((string) $this->input->post('atas_nama')), 0, 150);
        if ($bank === '' || $nomor === '') {
            $this->session->set_flashdata('Error', 'Nama bank dan nomor rekening wajib diisi.');
            redirect('pengaturan-website#rekening');
            return;
        }
        $id = (int) $this->input->post('id_rekening');
        if ($id > 0 && !$this->Pengaturan_model->get_rekening_by_id($id)) {
            $this->session->set_flashdata('Error', 'Rekening tidak ditemukan.');
            redirect('pengaturan-website#rekening');
            return;
        }
        $this->Pengaturan_model->simpan_rekening(['nama_bank' => $bank, 'nomor_rekening' => $nomor, 'atas_nama' => $atas], $id);
        $this->session->set_flashdata('Success', $id > 0 ? 'Rekening berhasil diubah.' : 'Rekening berhasil ditambahkan.');
        redirect('pengaturan-website#rekening');
    }

    public function rekening_hapus($id)
    {
        $this->cek_akses();
        if ($this->Pengaturan_model->rekening_tersedia()) {
            $this->Pengaturan_model->hapus_rekening($id);
            $this->session->set_flashdata('Success', 'Rekening dihapus.');
        }
        redirect('pengaturan-website#rekening');
    }
}
