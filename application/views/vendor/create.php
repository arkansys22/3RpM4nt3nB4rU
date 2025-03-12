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
              <h2 class="text-2xl font-bold mb-4">Tambah Vendor</h2>
              <form action="<?= base_url('crud_vendor/store') ?>" method="POST" enctype="multipart/form-data" class="bg-white p-6 shadow-md rounded">
                <input type="hidden" name="id_session" value="<?= $project->id_session ?>">
                <label class="block mb-2 text-black dark:text-white"><strong>Type:</strong></label>
                <select name="type" class="block w-full mb-2">
                    <option value="Venue">Venue</option>
                    <option value="MC">MC</option>
                    <option value="WO">WO</option>
                    <option value="MUA">MUA</option>
                    <option value="Perlengkapan Catering">Perlengkapan Catering</option>
                    <option value="Catering">Catering</option>
                    <option value="Dokumentasi">Dokumentasi</option>
                    <option value="Dekorasi">Dekorasi</option>
                    <option value="Entertainment">Entertainment</option>
                </select>
                <label class="block mb-2 text-black dark:text-white"><strong>Social Media:</strong></label>
                <input type="text" name="social_media" class="block w-full mb-2">
                <label class="block mb-2 text-black dark:text-white"><strong>Contact Name:</strong></label>
                <input type="text" name="contact_name" class="block w-full mb-2">
                <label class="block mb-2 text-black dark:text-white"><strong>Phone:</strong></label>
                <input type="text" name="phone" class="block w-full mb-2">
                <label class="block mb-2 text-black dark:text-white"><strong>Detail:</strong></label>
                <textarea name="detail" class="block w-full mb-2"></textarea>
                <label class="block mb-2 text-black dark:text-white"><strong>Photo 1:</strong></label>
                <input type="file" name="photo1" class="block w-full mb-2">
                <label class="block mb-2 text-black dark:text-white"><strong>Photo 2:</strong></label>
                <input type="file" name="photo2" class="block w-full mb-2">
                <label class="block mb-2 text-black dark:text-white"><strong>Photo 3:</strong></label>
                <input type="file" name="photo3" class="block w-full mb-2">
                <label class="block mb-2 text-black dark:text-white"><strong>Photo 4:</strong></label>
                <input type="file" name="photo4" class="block w-full mb-2">
                <label class="block mb-2 text-black dark:text-white"><strong>Photo 5:</strong></label>
                <input type="file" name="photo5" class="block w-full mb-2">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-600 sm:w-24 mb-2 sm:mb-0 text-center">Simpan</button>
                <a href="<?= site_url('project') ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
