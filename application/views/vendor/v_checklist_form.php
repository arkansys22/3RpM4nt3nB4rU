<?php
// Dipakai buat create (form == null) DAN edit (form == data lama).
$is_edit = $form !== null;
$action_url = $is_edit ? site_url('vendor-checklist/update/' . $form->id_session) : site_url('vendor-checklist/store');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceklist Vendor — <?= htmlspecialchars($vendor->vendor) ?></title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'project', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
  >
  <!-- ===== Preloader Start ===== -->
  <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})" class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
    <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent">
    </div>
  </div>
  <!-- ===== Preloader End ===== -->
  <!-- ===== Page Wrapper Start ===== -->
  <div class="flex h-screen overflow-hidden">
    <?php $this->load->view('backend/sidebar')?>

    <!-- ===== Content Area Start ===== -->
    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
      <?php $this->load->view('backend/header')?>

      <!-- ===== Main Content Start ===== -->
      <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
          <div class="grid grid-cols-12 gap-4 md:gap-6 2xl:gap-9">
            <div class="col-span-12 lg:col-span-8 lg:col-start-3 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">

              <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-2">
                <h1 class="text-2xl font-bold">Ceklist Serah Terima <?= htmlspecialchars($vendor->type) ?></h1>
                <a href="<?= site_url('project/lihat/' . $project->id_session) ?>" class="px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">
                  &larr; Kembali ke Project
                </a>
              </div>
              <p class="text-sm text-body dark:text-bodydark mb-4">
                <?= htmlspecialchars($project->project_name ?? '') ?> &middot;
                Vendor <strong><?= htmlspecialchars($vendor->type) ?></strong>: <?= htmlspecialchars($vendor->vendor) ?>
              </p>

              <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm"><?= $this->session->flashdata('error') ?></div>
              <?php endif; ?>

              <form action="<?= $action_url ?>" method="post" id="formCeklist" onsubmit="return sebelumSubmit()">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <?php if (!$is_edit): ?>
                <input type="hidden" name="project_id_session" value="<?= $project->id_session ?>">
                <input type="hidden" name="vendor_id" value="<?= $vendor->vendor_id ?>">
                <?php endif; ?>
                <input type="hidden" name="checklist_items" id="checklistItemsInput">

                <!-- Checklist -->
                <h2 class="text-lg font-bold mb-2">Ceklist</h2>
                <div id="checklistRows" class="space-y-2 mb-3">
                  <?php foreach ($checklist_items as $item): ?>
                  <div class="checklist-row flex items-center gap-2">
                    <div class="flex flex-col flex-shrink-0">
                      <button type="button" onclick="pindahAtas(this)" class="px-1 leading-none hover:text-primary" title="Naik">&uarr;</button>
                      <button type="button" onclick="pindahBawah(this)" class="px-1 leading-none hover:text-primary" title="Turun">&darr;</button>
                    </div>
                    <input type="checkbox" class="chk-checked h-7 w-7 flex-shrink-0" <?= !empty($item['checked']) ? 'checked' : '' ?>>
                    <input type="text" class="chk-label flex-1 rounded-lg border border-gray-400 bg-transparent py-2 px-3 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input" value="<?= htmlspecialchars($item['label']) ?>">
                    <button type="button" onclick="this.closest('.checklist-row').remove()" class="text-red-600 hover:underline text-sm flex-shrink-0">Hapus</button>
                  </div>
                  <?php endforeach; ?>
                </div>
                <button type="button" onclick="tambahBarisChecklist()" class="mb-6 px-3 py-1.5 border rounded-md text-sm hover:bg-whiter dark:hover:bg-meta-4">+ Tambah Item</button>

                <!-- Catatan -->
                <h2 class="text-lg font-bold mb-2">Catatan</h2>
                <textarea name="catatan" rows="3" placeholder="Catatan tambahan (opsional)"
                  class="w-full rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input mb-6"><?= $is_edit ? htmlspecialchars($form->catatan ?? '') : '' ?></textarea>

                <p class="text-xs text-body dark:text-bodydark mb-6">
                  Tanda tangan serah terima (Petugas WO, Vendor, Customer) dilakukan manual di kertas hasil cetak, bukan di sistem ini.
                </p>

                <div class="flex gap-2">
                  <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md font-medium hover:bg-opacity-90">Simpan</button>
                  <a href="<?= site_url('project/lihat/' . $project->id_session) ?>" class="px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">Batal</a>
                </div>
              </form>

            </div>
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <script src="<?php echo base_url()?>assets/backend/bundle.js"></script>
  <script>
    function tambahBarisChecklist() {
        var wrap = document.createElement('div');
        wrap.className = 'checklist-row flex items-center gap-2';
        wrap.innerHTML =
            '<div class="flex flex-col flex-shrink-0">' +
                '<button type="button" onclick="pindahAtas(this)" class="px-1 leading-none hover:text-primary" title="Naik">&uarr;</button>' +
                '<button type="button" onclick="pindahBawah(this)" class="px-1 leading-none hover:text-primary" title="Turun">&darr;</button>' +
            '</div>' +
            '<input type="checkbox" class="chk-checked h-7 w-7 flex-shrink-0">' +
            '<input type="text" class="chk-label flex-1 rounded-lg border border-gray-400 bg-transparent py-2 px-3 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input" placeholder="Item ceklist baru">' +
            '<button type="button" onclick="this.closest(\'.checklist-row\').remove()" class="text-red-600 hover:underline text-sm flex-shrink-0">Hapus</button>';
        document.getElementById('checklistRows').appendChild(wrap);
    }

    // Naik/turun cuma menukar posisi elemen DOM-nya (urutan array yang
    // dikirim ke server ikut mengikuti urutan baris ini -- lihat
    // sebelumSubmit()) -- tidak perlu urutan/id di server karena checklist
    // disimpan sebagai satu JSON array, bukan baris per baris di DB.
    function pindahAtas(btn) {
        var row = btn.closest('.checklist-row');
        var prev = row.previousElementSibling;
        if (prev) {
            row.parentNode.insertBefore(row, prev);
        }
    }

    function pindahBawah(btn) {
        var row = btn.closest('.checklist-row');
        var next = row.nextElementSibling;
        if (next) {
            row.parentNode.insertBefore(next, row);
        }
    }

    function sebelumSubmit() {
        var items = [];
        document.querySelectorAll('#checklistRows .checklist-row').forEach(function (row) {
            var label = row.querySelector('.chk-label').value.trim();
            if (label === '') { return; }
            items.push({ label: label, checked: row.querySelector('.chk-checked').checked });
        });
        document.getElementById('checklistItemsInput').value = JSON.stringify(items);
        return true;
    }
  </script>
</body>
</html>
