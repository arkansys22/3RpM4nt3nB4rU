<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Vendor</title>
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'vendor', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
            <div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">
              <h1 class="text-2xl font-bold mb-4">Tambah Vendor untuk Project: <?= $project->project_name ?></h1>
              <form action="<?= base_url('vendor/store/'.$project->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <!-- Vendor 1 (MC) -->
                <h2 class="text-xl font-semibold mb-4">Venue</h2>
                <label class="block mb-2">Nama Venue</label>
                <input type="text" name="vendor_1" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Media Sosial</label>
                <input type="text" name="social_media_1" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Nama Kontak</label>
                <input type="text" name="contact_name_1" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">No HP</label>
                <input type="text" name="phone_1" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Detail</label>
                <textarea name="detail_1" class="w-full px-4 py-2 border rounded mb-4"></textarea>

                <!-- Vendor 2 (WO) -->
                <h2 class="text-xl font-semibold mb-4">MC</h2>
                <label class="block mb-2">Nama MC</label>
                <input type="text" name="vendor_2" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Media Sosial</label>
                <input type="text" name="social_media_2" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Nama Kontak</label>
                <input type="text" name="contact_name_2" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">No HP</label>
                <input type="text" name="phone_2" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Detail</label>
                <textarea name="detail_2" class="w-full px-4 py-2 border rounded mb-4"></textarea>

                <!-- Vendor 3 (Others) -->
                <h2 class="text-xl font-semibold mb-4">WO</h2>

                <h2 class="text-xl font-semibold mb-4">MC</h2>
                <label class="block mb-2">Nama MC</label>
                <input type="text" name="vendor_3" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Media Sosial</label>
                <input type="text" name="social_media_3" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Nama Kontak</label>
                <input type="text" name="contact_name_3" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">No HP</label>
                <input type="text" name="phone_3" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Detail</label>
                <textarea name="detail_3" class="w-full px-4 py-2 border rounded mb-4"></textarea>

                <!-- Vendor 4 -->
                <h2 class="text-xl font-semibold mb-4">Make Up</h2>
                <label class="block mb-2">Nama MUA</label>
                <input type="text" name="vendor_4" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Media Sosial</label>
                <input type="text" name="social_media_4" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Nama Kontak</label>
                <input type="text" name="contact_name_4" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">No HP</label>
                <input type="text" name="phone_4" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Detail</label>
                <textarea name="detail_4" class="w-full px-4 py-2 border rounded mb-4"></textarea>

                <!-- Vendor 5 -->
                <h2 class="text-xl font-semibold mb-4">Perlengkapan Catering</h2>
                <label class="block mb-2">Nama Penyedia</label>
                <input type="text" name="vendor_5" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Media Sosial</label>
                <input type="text" name="social_media_5" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Nama Kontak</label>
                <input type="text" name="contact_name_5" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">No HP</label>
                <input type="text" name="phone_5" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Detail</label>
                <textarea name="detail_5" class="w-full px-4 py-2 border rounded mb-4"></textarea>

                <!-- Vendor 6 -->
                <h2 class="text-xl font-semibold mb-4">Catering</h2>
                <label class="block mb-2">Nama Catering</label>
                <input type="text" name="vendor_6" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Media Sosial</label>
                <input type="text" name="social_media_6" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Nama Kontak</label>
                <input type="text" name="contact_name_6" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">No HP</label>
                <input type="text" name="phone_6" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Detail</label>
                <textarea name="detail_6" class="w-full px-4 py-2 border rounded mb-4"></textarea>

                <!-- Vendor 7 -->
                <h2 class="text-xl font-semibold mb-4">Dokumentasi</h2>
                <label class="block mb-2">Nama Dokumentasi</label>
                <input type="text" name="vendor_7" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Media Sosial</label>
                <input type="text" name="social_media_7" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Nama Kontak</label>
                <input type="text" name="contact_name_7" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">No HP</label>
                <input type="text" name="phone_7" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Detail</label>
                <textarea name="detail_7" class="w-full px-4 py-2 border rounded mb-4"></textarea>

                <!-- Vendor 8 -->
                <h2 class="text-xl font-semibold mb-4">Dekorasi</h2>
                <label class="block mb-2">Nama Dekorasi</label>
                <input type="text" name="vendor_8" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Media Sosial</label>
                <input type="text" name="social_media_8" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Nama Kontak</label>
                <input type="text" name="contact_name_8" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">No HP</label>
                <input type="text" name="phone_8" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Detail</label>
                <textarea name="detail_8" class="w-full px-4 py-2 border rounded mb-4"></textarea>

                <!-- Vendor 9 -->
                <h2 class="text-xl font-semibold mb-4">Entertainment</h2>
                <label class="block mb-2">Nama Entertainment</label>
                <input type="text" name="vendor_9" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Media Sosial</label>
                <input type="text" name="social_media_9" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Nama Kontak</label>
                <input type="text" name="contact_name_9" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">No HP</label>
                <input type="text" name="phone_9" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Detail</label>
                <textarea name="detail_9" class="w-full px-4 py-2 border rounded mb-4"></textarea>

                <div class="flex flex-col sm:flex-row justify-end">
                  <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-600 sm:w-24 mb-2 sm:mb-0 text-center">Simpan</button>
                  <a href="javascript:history.back()" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
</body>
</html>
