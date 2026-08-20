<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Susunan acara khusus per klien (clients/lihat/<id> -> Menu -> Susunan Acara).
 *
 * Datanya AWALNYA di-copy dari fitur Susunan Acara umum (Crud_susunan_acara / tabel
 * `susunan_acara`) sesuai kategori acara yang dipilih klien di clients/edit, tapi
 * sesudah itu disimpan terpisah di tabel `client_susunan_acara` -- edit/hapus di sini
 * tidak pernah menyentuh data umum, dan sebaliknya perubahan di data umum tidak
 * otomatis ikut berubah di sini (kecuali klik "Salin Ulang dari Data Umum").
 *
 * Satu klien bisa punya SAMPAI DUA acara (Kategori Acara 1 & 2, mis. Akad Nikah + Resepsi).
 * Parameter $acara_ke (1 atau 2) menentukan set kegiatan mana yang dipakai -- kolom
 * `kategori_acara_id_session`/`waktu_acara_mulai` di tabel clients dipakai untuk Acara 1,
 * `kategori_acara_2_id_session`/`waktu_acara_2_mulai` untuk Acara 2. Baris kegiatan di
 * `client_susunan_acara` dibedakan lewat kolom `acara_ke`.
 */
class Crud_client_susunan_acara extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Client_susunan_acara_model');
        $this->load->model('Susunan_acara_model');
        $this->load->model('Clients_model');
        $this->load->helper(['url', 'form']);
    }

    private function cek_akses()
    {
        if (!in_array($this->session->level, ['1', '2', '4'])) {
            redirect(base_url());
            exit;
        }
    }

    private function get_client_or_404($client_id_session)
    {
        $client = $this->Clients_model->get_client_by_session($client_id_session);
        if (!$client) {
            show_error('Client tidak ditemukan.', 404);
            return null;
        }
        return $client;
    }

    /**
     * Normalisasi $acara_ke jadi 1 atau 2 (default 1 kalau nilai lain/kosong).
     */
    private function norm_acara_ke($acara_ke)
    {
        return ((int) $acara_ke === 2) ? 2 : 1;
    }

    private function kategori_field($acara_ke)
    {
        return $this->norm_acara_ke($acara_ke) === 2 ? 'kategori_acara_2_id_session' : 'kategori_acara_id_session';
    }

    private function waktu_field($acara_ke)
    {
        return $this->norm_acara_ke($acara_ke) === 2 ? 'waktu_acara_2_mulai' : 'waktu_acara_mulai';
    }

    /**
     * Bangun URL clients/susunan-acara/... yang benar untuk $acara_ke (1 atau 2).
     * $action kosong = halaman daftar/index.
     */
    private function route($action, $client_id_session, $acara_ke = 1)
    {
        $acara_ke = $this->norm_acara_ke($acara_ke);
        $prefix = 'clients/susunan-acara/';
        if ($acara_ke === 2) {
            $prefix .= ($action === '' ? 'acara2/' : $action . '/acara2/');
        } else {
            $prefix .= ($action === '' ? '' : $action . '/');
        }
        return $prefix . $client_id_session;
    }

    public function index($client_id_session, $acara_ke = 1)
    {
        $this->cek_akses();
        $acara_ke = $this->norm_acara_ke($acara_ke);

        $data['clients'] = $this->get_client_or_404($client_id_session);
        if (!$data['clients']) {
            return;
        }

        $kategoriField = $this->kategori_field($acara_ke);

        // Baru pertama kali dibuka & klien sudah punya kategori acara tapi belum
        // punya susunan acara sendiri sama sekali -> auto-salin dari data umum.
        if ($this->Client_susunan_acara_model->count_by_client($client_id_session, $acara_ke) == 0
            && !empty($data['clients']->$kategoriField)) {
            $this->copy_from_global($client_id_session, $data['clients']->$kategoriField, $acara_ke);
        }

        $data['acara_ke'] = $acara_ke;
        $data['kegiatan_list'] = $this->apply_computed_waktu(
            $data['clients'],
            $this->Client_susunan_acara_model->get_by_client($client_id_session, $acara_ke),
            $acara_ke
        );
        $this->apply_variabel_text($data['clients'], $data['kegiatan_list']);
        $data['kategori_acara'] = !empty($data['clients']->$kategoriField)
            ? $this->Susunan_acara_model->get_kategori_by_session($data['clients']->$kategoriField)
            : null;

        $this->load->view('backend/v_client_susunan_acara', $data);
    }

    /**
     * Susunan data (clients, kegiatan_list, kategori_acara) yang dibutuhkan buat merender
     * template PDF -- dipakai bareng oleh preview_pdf_page() dan preview_pdf().
     */
    private function build_pdf_data($client_id_session, $acara_ke)
    {
        $clients = $this->get_client_or_404($client_id_session);
        if (!$clients) {
            return null;
        }

        $kategoriField = $this->kategori_field($acara_ke);

        $data['clients'] = $clients;
        $data['kegiatan_list'] = $this->apply_computed_waktu(
            $clients,
            $this->Client_susunan_acara_model->get_by_client($client_id_session, $acara_ke),
            $acara_ke
        );
        $this->apply_variabel_text($clients, $data['kegiatan_list']);
        $data['kategori_acara'] = !empty($clients->$kategoriField)
            ? $this->Susunan_acara_model->get_kategori_by_session($clients->$kategoriField)
            : null;

        return $data;
    }

    /**
     * Nama file PDF yang stabil & aman buat nama file (tanpa karakter aneh) -- SATU nama
     * per klien+acara, jadi tiap preview ditimpa ulang (bukan numpuk file baru tiap klik).
     */
    private function pdf_filename($client_id_session, $acara_ke)
    {
        return 'susunan_acara_' . $this->norm_acara_ke($acara_ke) . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $client_id_session);
    }

    /**
     * Path folder + file fisik PDF milik satu klien+acara di assets/backend/susunan_acara/.
     */
    private function pdf_file_path($client_id_session, $acara_ke)
    {
        return FCPATH . 'assets/backend/susunan_acara/' . $this->pdf_filename($client_id_session, $acara_ke) . '.pdf';
    }

    /**
     * Tombol "Update PDF" -- generate PDF dari data TERBARU lalu SIMPAN sebagai file fisik
     * di assets/backend/susunan_acara/ (menimpa file lama punya klien+acara ini). Tidak
     * menampilkan apa-apa, cuma balik ke halaman daftar dengan notif -- buat lihat hasilnya
     * pakai tombol "Preview" di sebelahnya.
     */
    public function update_pdf($client_id_session, $acara_ke = 1)
    {
        $this->cek_akses();
        $acara_ke = $this->norm_acara_ke($acara_ke);

        $data = $this->build_pdf_data($client_id_session, $acara_ke);
        if (!$data) {
            return;
        }

        $this->load->library('pdf');
        $html = $this->load->view('backend/v_client_susunan_acara_pdf', $data, true);
        $pdfContent = $this->pdf->createPDF_P($html, 'preview', false);

        $filePath = $this->pdf_file_path($client_id_session, $acara_ke);
        $destDir = dirname($filePath);
        if (!is_dir($destDir)) {
            mkdir($destDir, 0777, true);
        }
        file_put_contents($filePath, $pdfContent);

        $this->session->set_flashdata('Success', 'PDF berhasil diperbarui. Klik "Preview" untuk melihat hasilnya.');
        redirect($this->route('', $client_id_session, $acara_ke));
    }

    /**
     * Tombol "Preview" -- TIDAK generate ulang, cuma ambil file PDF yang sudah tersimpan dari
     * tombol "Update PDF" dan menampilkannya di halaman ini pakai PDF.js (digambar ke
     * <canvas>, halaman per halaman). Sengaja BUKAN <iframe>/<a href> langsung ke file
     * .pdf-nya, karena kalau browser punya setting "selalu unduh PDF" aktif, setting itu
     * tetap ke-trigger untuk navigasi/embed langsung ke resource PDF -- termasuk Blob URL
     * sekalipun. Rendering manual ke canvas bikin browser tidak pernah "melihat" ini
     * sebagai resource PDF sama sekali, jadi tidak ada keputusan download vs tampilkan
     * yang bisa diambil browser -- selalu tampil sebagai halaman biasa.
     */
    public function preview_pdf_page($client_id_session, $acara_ke = 1)
    {
        $this->cek_akses();
        $acara_ke = $this->norm_acara_ke($acara_ke);

        $clients = $this->get_client_or_404($client_id_session);
        if (!$clients) {
            return;
        }

        $filePath = $this->pdf_file_path($client_id_session, $acara_ke);
        if (!is_file($filePath)) {
            $this->session->set_flashdata('error', 'PDF belum pernah dibuat untuk acara ini. Klik "Update PDF" dulu.');
            redirect($this->route('', $client_id_session, $acara_ke));
            return;
        }

        $filename = $this->pdf_filename($client_id_session, $acara_ke);

        $data['clients'] = $clients;
        $data['acara_ke'] = $acara_ke;
        // Cache-bust dengan waktu modifikasi file, supaya PDF.js tidak nampilin file lama
        // yang sempat ke-cache, tapi tetap stabil selama filenya belum di-"Update PDF" lagi.
        $data['pdf_url'] = base_url('assets/backend/susunan_acara/' . $filename . '.pdf') . '?t=' . filemtime($filePath);

        $this->load->view('backend/v_client_susunan_acara_pdf_preview', $data);
    }

    /**
     * Konten PDF mentah (A4, potrait) susunan acara satu klien -- tabel No/Jam/Durasi/
     * Kegiatan/Vendor-PJ, tanpa foto ilustrasi. Dipakai untuk akses langsung/lama; tombol
     * "Preview" di halaman daftar sekarang pakai preview_pdf_page() (viewer PDF.js),
     * bukan endpoint ini.
     */
    public function preview_pdf($client_id_session, $acara_ke = 1)
    {
        $this->cek_akses();
        $acara_ke = $this->norm_acara_ke($acara_ke);

        $data = $this->build_pdf_data($client_id_session, $acara_ke);
        if (!$data) {
            return;
        }

        $this->load->library('pdf');
        $html = $this->load->view('backend/v_client_susunan_acara_pdf', $data, true);

        // Nama file dibersihkan dari karakter yang bisa merusak header Content-Disposition
        // (mis. tanda kutip) kalau nama klien kebetulan mengandungnya.
        $rawFilename = 'Susunan Acara' . ($acara_ke === 2 ? ' 2' : '') . ' - ' . $data['clients']->client_name;
        $filename = trim(preg_replace('/[\r\n"]+/', '', $rawFilename));

        $pdfContent = $this->pdf->createPDF_P($html, $filename, false);

        // Attachment (unduh) hanya kalau diminta eksplisit lewat ?download=1 -- selain itu
        // selalu inline, supaya benar-benar "preview" bukan langsung ke-download.
        $isDownload = $this->input->get('download') == '1';

        // no-cache -- kalau tidak, browser bisa nyimpen versi PDF lama (mis. dari sebelum
        // kegiatan diedit) dan menampilkannya lagi tanpa benar-benar minta ulang ke server.
        $this->output
            ->set_content_type('application/pdf')
            ->set_header('Content-Disposition: ' . ($isDownload ? 'attachment' : 'inline') . '; filename="' . $filename . '.pdf"')
            ->set_header('Cache-Control: no-cache, no-store, must-revalidate')
            ->set_header('Pragma: no-cache')
            ->set_header('Expires: 0')
            ->set_output($pdfContent);
    }

    /**
     * Isi properti `nama_kegiatan_display` tiap kegiatan: nama_kegiatan mentah (dengan
     * token {{...}}) yang sudah diganti jadi nilai aktual klien ini, lihat
     * render_susunan_acara_text() di customs_helper.php. `nama_kegiatan` aslinya TIDAK
     * diubah supaya tetap konsisten dengan yang tersimpan di DB.
     */
    private function apply_variabel_text($clients, array $kegiatan_list)
    {
        foreach ($kegiatan_list as $item) {
            $item->nama_kegiatan_display = render_susunan_acara_text($item->nama_kegiatan, $clients);
        }

        return $kegiatan_list;
    }

    /**
     * Hitung jam mulai & jam selesai TIAP kegiatan (properti tambahan `waktu_mulai` /
     * `waktu_selesai`, bukan kolom di DB -- selalu dihitung ulang saat ditampilkan).
     * Patokannya jam mulai acara ({acara_ke}=1 -> clients.waktu_acara_mulai, {acara_ke}=2 ->
     * clients.waktu_acara_2_mulai) sebagai jam mulai kegiatan urutan pertama, lalu kegiatan
     * berikutnya = jam mulai kegiatan sebelumnya + durasi kegiatan sebelumnya.
     */
    private function apply_computed_waktu($clients, array $kegiatan_list, $acara_ke = 1)
    {
        $waktuField = $this->waktu_field($acara_ke);
        $baseWaktu = $clients->$waktuField ?? null;
        $cursor = !empty($baseWaktu) ? strtotime('1970-01-01 ' . $baseWaktu) : null;

        foreach ($kegiatan_list as $item) {
            if ($cursor === null) {
                $item->waktu_mulai = null;
                $item->waktu_selesai = null;
                continue;
            }

            $item->waktu_mulai = date('H:i', $cursor);

            $durasiParts = array_map('intval', explode(':', $item->durasi));
            $detik = ($durasiParts[0] ?? 0) * 3600 + ($durasiParts[1] ?? 0) * 60 + ($durasiParts[2] ?? 0);
            $cursor += $detik;

            $item->waktu_selesai = date('H:i', $cursor);
        }

        return $kegiatan_list;
    }

    public function import($client_id_session, $acara_ke = 1)
    {
        $this->cek_akses();
        $acara_ke = $this->norm_acara_ke($acara_ke);

        $client = $this->get_client_or_404($client_id_session);
        if (!$client) {
            return;
        }

        $kategoriField = $this->kategori_field($acara_ke);

        if (empty($client->$kategoriField)) {
            $label = $acara_ke === 2 ? 'Kategori Acara 2' : 'Kategori Acara 1';
            $this->session->set_flashdata('error', "Klien ini belum memilih $label. Atur dulu di halaman Edit Klien.");
            redirect($this->route('', $client_id_session, $acara_ke));
            return;
        }

        // Hapus dulu susunan acara klien yang lama (beserta foto hasil salinannya),
        // baru salin ulang dari data umum -- supaya tombol ini hasilnya selalu predictable.
        $old = $this->Client_susunan_acara_model->get_by_client($client_id_session, $acara_ke);
        foreach ($old as $item) {
            if (!empty($item->foto)) {
                $path = FCPATH . 'assets/uploads/client_susunan_acara/' . $item->foto;
                if (is_file($path)) {
                    unlink($path);
                }
            }
        }
        $this->Client_susunan_acara_model->delete_by_client($client_id_session, $acara_ke);

        $count = $this->copy_from_global($client_id_session, $client->$kategoriField, $acara_ke);

        $this->session->set_flashdata('Success', $count > 0
            ? 'Susunan acara berhasil disalin ulang dari data umum (' . $count . ' kegiatan).'
            : 'Kategori acara ini belum punya kegiatan di data umum.');
        redirect($this->route('', $client_id_session, $acara_ke));
    }

    /**
     * Copy semua kegiatan milik $kategori_id_session (tabel umum `susunan_acara`) jadi
     * baris-baris baru di `client_susunan_acara` milik $client_id_session, ditandai
     * `acara_ke`. Foto ilustrasi ikut di-duplikasi filenya (bukan sekadar disebut nama
     * filenya) supaya foto klien ini aman dihapus/diganti kapan saja tanpa merusak foto
     * di data umum.
     */
    private function copy_from_global($client_id_session, $kategori_id_session, $acara_ke = 1)
    {
        $acara_ke = $this->norm_acara_ke($acara_ke);
        $globalList = $this->Susunan_acara_model->get_kegiatan_by_kategori($kategori_id_session);

        $urutan = 1;
        foreach ($globalList as $g) {
            $fotoBaru = null;
            if (!empty($g->foto)) {
                $srcPath = FCPATH . 'assets/uploads/susunan_acara/' . $g->foto;
                if (is_file($srcPath)) {
                    $destDir = FCPATH . 'assets/uploads/client_susunan_acara';
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0777, true);
                    }
                    $ext = pathinfo($g->foto, PATHINFO_EXTENSION);
                    $fotoBaru = 'client_susunan_acara_' . md5(uniqid('', true)) . ($ext ? '.' . $ext : '');
                    copy($srcPath, $destDir . '/' . $fotoBaru);
                }
            }

            $this->Client_susunan_acara_model->insert([
                'id_session'        => hash('sha256', bin2hex(random_bytes(16))),
                'client_id_session' => $client_id_session,
                'acara_ke'          => $acara_ke,
                'nama_kegiatan'     => $g->nama_kegiatan,
                'durasi'            => $g->durasi,
                'vendor_pj'         => $g->vendor_pj,
                'foto'              => $fotoBaru,
                'urutan'            => $urutan++,
                'created_by'        => $this->session->id_session,
                'created_at'        => date('Y-m-d H:i:s'),
            ]);
        }

        return count($globalList);
    }

    public function presentasi($client_id_session, $acara_ke = 1)
    {
        $this->cek_akses();
        $acara_ke = $this->norm_acara_ke($acara_ke);

        $data['clients'] = $this->get_client_or_404($client_id_session);
        if (!$data['clients']) {
            return;
        }

        $data['acara_ke'] = $acara_ke;
        $data['kegiatan_list'] = $this->apply_computed_waktu(
            $data['clients'],
            $this->Client_susunan_acara_model->get_by_client($client_id_session, $acara_ke),
            $acara_ke
        );
        $this->apply_variabel_text($data['clients'], $data['kegiatan_list']);

        $this->load->view('backend/v_client_susunan_acara_presentasi', $data);
    }

    // ================= Kegiatan =================

    public function create($client_id_session, $acara_ke = 1)
    {
        $this->cek_akses();
        $acara_ke = $this->norm_acara_ke($acara_ke);

        $data['item'] = null;
        $data['acara_ke'] = $acara_ke;
        $data['clients'] = $this->get_client_or_404($client_id_session);
        if (!$data['clients']) {
            return;
        }

        $data['variabel_list'] = susunan_acara_variabel_list();
        $data['variabel_values'] = susunan_acara_variabel_values($data['clients']);

        $this->load->view('backend/v_client_susunan_acara_form', $data);
    }

    public function store()
    {
        $this->cek_akses();

        $client_id_session = $this->input->post('client_id_session');
        $acara_ke = $this->norm_acara_ke($this->input->post('acara_ke'));

        if (!$this->validasi_post()) {
            redirect($this->route('create', $client_id_session, $acara_ke));
            return;
        }

        $foto = $this->upload_foto();
        if ($foto === false) {
            redirect($this->route('create', $client_id_session, $acara_ke));
            return;
        }

        $this->Client_susunan_acara_model->insert([
            'id_session'        => hash('sha256', bin2hex(random_bytes(16))),
            'client_id_session' => $client_id_session,
            'acara_ke'          => $acara_ke,
            'nama_kegiatan'     => $this->input->post('nama_kegiatan'),
            'durasi'            => $this->input->post('durasi'),
            'vendor_pj'         => $this->input->post('vendor_pj'),
            'foto'              => $foto,
            'urutan'            => $this->Client_susunan_acara_model->get_next_urutan($client_id_session, $acara_ke),
            'created_by'        => $this->session->id_session,
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('Success', 'Kegiatan berhasil ditambahkan');
        redirect($this->route('', $client_id_session, $acara_ke));
    }

    public function edit($id_session)
    {
        $this->cek_akses();

        $data['item'] = $this->Client_susunan_acara_model->get_by_session($id_session);
        if (!$data['item']) {
            show_error('Kegiatan tidak ditemukan.', 404);
            return;
        }

        $data['acara_ke'] = $this->norm_acara_ke($data['item']->acara_ke);
        $data['clients'] = $this->get_client_or_404($data['item']->client_id_session);
        if (!$data['clients']) {
            return;
        }

        $data['variabel_list'] = susunan_acara_variabel_list();
        $data['variabel_values'] = susunan_acara_variabel_values($data['clients']);

        $this->load->view('backend/v_client_susunan_acara_form', $data);
    }

    public function update($id_session)
    {
        $this->cek_akses();

        $item = $this->Client_susunan_acara_model->get_by_session($id_session);
        if (!$item) {
            show_error('Kegiatan tidak ditemukan.', 404);
            return;
        }

        if (!$this->validasi_post()) {
            redirect('clients/susunan-acara/edit/' . $id_session);
            return;
        }

        $data = [
            'nama_kegiatan' => $this->input->post('nama_kegiatan'),
            'durasi'        => $this->input->post('durasi'),
            'vendor_pj'     => $this->input->post('vendor_pj'),
        ];

        // Foto pada saat edit sifatnya opsional -- kalau tidak diganti, foto lama tetap dipakai.
        if (!empty($_FILES['foto']['name'])) {
            $foto = $this->upload_foto();
            if ($foto === false) {
                redirect('clients/susunan-acara/edit/' . $id_session);
                return;
            }
            $data['foto'] = $foto;

            if (!empty($item->foto)) {
                $old_path = FCPATH . 'assets/uploads/client_susunan_acara/' . $item->foto;
                if (is_file($old_path)) {
                    unlink($old_path);
                }
            }
        }

        $this->Client_susunan_acara_model->update_by_session($id_session, $data);

        $this->session->set_flashdata('Success', 'Kegiatan berhasil diperbarui');
        redirect($this->route('', $item->client_id_session, $item->acara_ke));
    }

    public function delete($id_session)
    {
        $this->cek_akses();

        $item = $this->Client_susunan_acara_model->get_by_session($id_session);
        $redirectTo = 'clients';

        if ($item) {
            $redirectTo = $this->route('', $item->client_id_session, $item->acara_ke);

            if (!empty($item->foto)) {
                $path = FCPATH . 'assets/uploads/client_susunan_acara/' . $item->foto;
                if (is_file($path)) {
                    unlink($path);
                }
            }
            $this->Client_susunan_acara_model->delete_by_session($id_session);
            $this->session->set_flashdata('Success', 'Kegiatan berhasil dihapus');
        }

        redirect($redirectTo);
    }

    public function move_up($id_session)
    {
        $this->cek_akses();
        $item = $this->Client_susunan_acara_model->get_by_session($id_session);
        $this->Client_susunan_acara_model->swap_urutan($id_session, 'up');
        redirect($item ? $this->route('', $item->client_id_session, $item->acara_ke) : 'clients');
    }

    public function move_down($id_session)
    {
        $this->cek_akses();
        $item = $this->Client_susunan_acara_model->get_by_session($id_session);
        $this->Client_susunan_acara_model->swap_urutan($id_session, 'down');
        redirect($item ? $this->route('', $item->client_id_session, $item->acara_ke) : 'clients');
    }

    private function validasi_post()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama_kegiatan', 'Nama Kegiatan', 'required|trim');
        $this->form_validation->set_rules('durasi', 'Durasi', 'required|trim');
        $this->form_validation->set_rules('vendor_pj', 'Vendor/PJ', 'required|trim');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            return false;
        }

        return true;
    }

    /**
     * Upload foto ilustrasi. Return nama file (string) kalau sukses, atau FALSE
     * kalau ada file yang dipilih tapi gagal (pesan errornya sudah di-flash).
     */
    private function upload_foto()
    {
        if (empty($_FILES['foto']['name'])) {
            $this->session->set_flashdata('error', 'Foto ilustrasi wajib diisi');
            return false;
        }

        $basePath = FCPATH . 'assets/uploads/client_susunan_acara';
        if (!is_dir($basePath)) {
            mkdir($basePath, 0777, true);
        }

        $config = [
            'upload_path'   => $basePath,
            'allowed_types' => 'jpg|jpeg|png|webp',
            'file_name'     => 'client_susunan_acara_' . md5(uniqid('', true)),
            'max_size'      => 2048,
        ];

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('foto')) {
            $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
            return false;
        }

        $file = $this->upload->data();
        return $file['file_name'];
    }
}
