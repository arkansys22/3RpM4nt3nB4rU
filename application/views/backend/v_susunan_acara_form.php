<?php $isEdit = !empty($item); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit Kegiatan' : 'Tambah Kegiatan' ?> | Susunan Acara</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'susunan_acara', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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

              <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
                <h1 class="text-2xl font-bold"><?= $isEdit ? 'Edit Kegiatan' : 'Tambah Kegiatan' ?></h1>
                <a href="<?= site_url('susunan-acara') ?>"
                  class="w-full md:w-auto text-center px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">
                  &larr; Kembali
                </a>
              </div>

              <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm"><?= $this->session->flashdata('error') ?></div>
              <?php endif; ?>

              <form action="<?= $isEdit ? site_url('susunan-acara/update/' . $item->id_session) : site_url('susunan-acara/store') ?>"
                method="post" enctype="multipart/form-data">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                <div class="mb-4">
                  <label class="block mb-2 font-medium">Kategori</label>
                  <select name="kategori_id_session" required
                    class="w-full rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori_list as $kat): ?>
                    <option value="<?= $kat->id_session ?>" <?= ($selected_kategori == $kat->id_session) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($kat->nama_kategori) ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="mb-4">
                  <label class="block mb-2 font-medium">Nama Kegiatan</label>
                  <textarea name="nama_kegiatan" required rows="4"
                    class="w-full rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input"><?= $isEdit ? htmlspecialchars($item->nama_kegiatan) : set_value('nama_kegiatan') ?></textarea>
                </div>

                <div class="mb-4">
                  <label class="block mb-2 font-medium">Durasi (jam:menit)</label>
                  <input type="time" name="durasi" required
                    value="<?= $isEdit ? substr($item->durasi, 0, 5) : set_value('durasi') ?>"
                    class="w-full rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input" />
                  <span class="text-xs text-body dark:text-bodydark">Lama kegiatan berlangsung, misalnya 00:05 (5 menit) atau 02:25 (2 jam 25 menit).</span>
                </div>

                <div class="mb-4">
                  <label class="block mb-2 font-medium">Vendor/PJ</label>
                  <input type="text" name="vendor_pj" required
                    value="<?= $isEdit ? htmlspecialchars($item->vendor_pj) : set_value('vendor_pj') ?>"
                    class="w-full rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input" />
                </div>

                <div class="mb-4">
                  <label class="block mb-2 font-medium">Foto Ilustrasi</label>
                  <?php if ($isEdit && !empty($item->foto)): ?>
                  <div class="mb-2">
                    <img src="<?= base_url('assets/uploads/susunan_acara/' . $item->foto) ?>" alt="" class="w-32 h-32 object-cover rounded-md border border-stroke dark:border-strokedark">
                  </div>
                  <?php endif; ?>
                  <input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp" <?= $isEdit ? '' : 'required' ?>
                    class="w-full rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input" />
                  <span class="text-xs text-body dark:text-bodydark">Format JPG/PNG/WEBP, maksimal 2MB.<?= $isEdit ? ' Kosongkan kalau tidak mau ganti foto.' : '' ?></span>
                </div>

                <div class="flex gap-2 mt-6">
                  <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md font-medium hover:bg-opacity-90">
                    Simpan
                  </button>
                  <a href="<?= site_url('susunan-acara') ?>" class="px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">
                    Batal
                  </a>
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
</body>
</html>
