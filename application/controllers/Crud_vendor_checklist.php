<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Form Ceklist serah terima vendor Dekorasi/Catering, per project. Satu
// form per (project, vendor). Bagian tanda tangan SENGAJA tidak ditangani
// di sistem -- form ini dicetak (lihat tombol Cetak di v_checklist_lihat)
// dan ditandatangani basah di kertas oleh Petugas WO, Vendor, dan Customer.
class Crud_vendor_checklist extends CI_Controller {

    // Cuma 2 tipe vendor ini yang punya fitur ceklist -- diminta eksplisit
    // sesuai permintaan fitur ini.
    const TIPE_DIDUKUNG = ['Dekorasi', 'Catering'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Vendor_checklist_model');
        $this->load->model('Vendor_model');
        $this->load->model('project_model');
    }

    // Akses sama seperti halaman project/lihat (tempat tombol fitur ini
    // muncul) -- Developer, Administrator, Staff Admin.
    private function cek_akses()
    {
        if (!in_array($this->session->level, ['1', '2', '4'])) {
            redirect(base_url('panel'));
            exit;
        }
    }

    private function get_vendor_or_404($project_id_session, $vendor_id)
    {
        $vendor = $this->Vendor_model->get_vendor_by_id_and_vendor_id($project_id_session, $vendor_id);
        if (!$vendor || !in_array($vendor->type, self::TIPE_DIDUKUNG)) {
            show_error('Vendor tidak ditemukan atau bukan tipe Dekorasi/Catering.', 404);
            return null;
        }
        return $vendor;
    }

    // "Pintu masuk" dari tombol di project/lihat -- kalau form-nya sudah
    // pernah dibuat, langsung ke halaman lihat/lanjut isi; kalau belum, ke
    // halaman buat baru. Jadi tombolnya di project/lihat cuma satu link
    // tanpa perlu tahu dulu form-nya sudah ada atau belum.
    public function buka($project_id_session, $vendor_id)
    {
        $this->cek_akses();

        $vendor = $this->get_vendor_or_404($project_id_session, $vendor_id);
        if (!$vendor) {
            return;
        }

        $existing = $this->Vendor_checklist_model->get_by_project_vendor($project_id_session, $vendor_id);
        if ($existing) {
            redirect('vendor-checklist/lihat/' . $existing->id_session);
            return;
        }

        redirect('vendor-checklist/create/' . $project_id_session . '/' . $vendor_id);
    }

    public function create($project_id_session, $vendor_id)
    {
        $this->cek_akses();

        $vendor = $this->get_vendor_or_404($project_id_session, $vendor_id);
        if (!$vendor) {
            return;
        }

        if ($this->Vendor_checklist_model->get_by_project_vendor($project_id_session, $vendor_id)) {
            $this->session->set_flashdata('error', 'Form ceklist untuk vendor ini sudah pernah dibuat.');
            redirect('vendor-checklist/buka/' . $project_id_session . '/' . $vendor_id);
            return;
        }

        $data['form'] = null;
        $data['project'] = $this->project_model->get_project_by_session($project_id_session);
        $data['vendor'] = $vendor;
        $data['checklist_items'] = array_map(function ($label) {
            return ['label' => $label, 'checked' => false];
        }, default_checklist_vendor($vendor->type));

        $this->load->view('vendor/v_checklist_form', $data);
    }

    public function edit($id_session)
    {
        $this->cek_akses();

        $form = $this->Vendor_checklist_model->get_by_session($id_session);
        if (!$form) {
            show_error('Form ceklist tidak ditemukan.', 404);
            return;
        }

        $data['form'] = $form;
        $data['project'] = $this->project_model->get_project_by_session($form->project_id_session);
        $data['vendor'] = $this->Vendor_model->get_vendor_by_id_and_vendor_id($form->project_id_session, $form->vendor_id);
        $data['checklist_items'] = json_decode($form->checklist_items, true) ?: [];

        $this->load->view('vendor/v_checklist_form', $data);
    }

    public function lihat($id_session)
    {
        $this->cek_akses();

        $data['form'] = $this->Vendor_checklist_model->get_by_session($id_session);
        if (!$data['form']) {
            show_error('Form ceklist tidak ditemukan.', 404);
            return;
        }

        $data['project'] = $this->project_model->get_project_by_session($data['form']->project_id_session);
        $data['checklist_items'] = json_decode($data['form']->checklist_items, true) ?: [];

        $this->load->view('vendor/v_checklist_lihat', $data);
    }

    public function store()
    {
        $this->cek_akses();

        $project_id_session = $this->input->post('project_id_session');
        $vendor_id = $this->input->post('vendor_id');

        $vendor = $this->get_vendor_or_404($project_id_session, $vendor_id);
        if (!$vendor) {
            return;
        }

        if ($this->Vendor_checklist_model->get_by_project_vendor($project_id_session, $vendor_id)) {
            $this->session->set_flashdata('error', 'Form ceklist untuk vendor ini sudah pernah dibuat.');
            redirect('vendor-checklist/buka/' . $project_id_session . '/' . $vendor_id);
            return;
        }

        $id_session = hash('sha256', bin2hex(random_bytes(16)));

        $data = $this->kumpulkan_data_form();
        $data['id_session'] = $id_session;
        $data['project_id_session'] = $project_id_session;
        $data['vendor_id'] = $vendor_id;
        $data['vendor_type'] = $vendor->type;
        $data['vendor_nama'] = $vendor->vendor;
        $data['created_by'] = $this->session->id_session;
        $data['created_at'] = date('Y-m-d H:i:s');

        $this->Vendor_checklist_model->insert($data);

        $this->session->set_flashdata('Success', 'Form ceklist berhasil disimpan.');
        redirect('vendor-checklist/lihat/' . $id_session);
    }

    public function update($id_session)
    {
        $this->cek_akses();

        if (!$this->Vendor_checklist_model->get_by_session($id_session)) {
            show_error('Form ceklist tidak ditemukan.', 404);
            return;
        }

        $this->Vendor_checklist_model->update($id_session, $this->kumpulkan_data_form());

        $this->session->set_flashdata('Success', 'Form ceklist berhasil diperbarui.');
        redirect('vendor-checklist/lihat/' . $id_session);
    }

    // Logika bareng buat store() & update(): parse checklist JSON dari
    // form, catatan, dan status (Draft/Selesai di-set manual oleh staff --
    // tidak ada tanda tangan digital yang menentukan status otomatis).
    private function kumpulkan_data_form()
    {
        $checklist_raw = json_decode($this->input->post('checklist_items'), true);
        $checklist_items = [];
        if (is_array($checklist_raw)) {
            foreach ($checklist_raw as $item) {
                $label = trim($item['label'] ?? '');
                if ($label === '') {
                    continue;
                }
                $checklist_items[] = ['label' => $label, 'checked' => !empty($item['checked'])];
            }
        }

        return [
            'checklist_items' => json_encode($checklist_items),
            'catatan' => trim((string) $this->input->post('catatan')),
            'status' => $this->input->post('status') === 'Selesai' ? 'Selesai' : 'Draft',
        ];
    }
}
