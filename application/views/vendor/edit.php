<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vendor</title>
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
              <h1 class="text-2xl font-bold mb-4">Edit Vendor</h1>
              <form action="<?= base_url('crud_vendor/update/' . $vendor->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <input type="hidden" name="id_session" value="<?= $vendor->id_session ?>">

                <?php 
                // Daftar jenis vendor yang akan digunakan sebagai label
                $vendor_labels = [
                    'Venue',
                    'MC',
                    'WO',
                    'MUA',
                    'Perlengkapan Catering',
                    'Catering',
                    'Dokumentasi',
                    'Dekorasi',
                    'Entertainment'
                ];

                for ($i = 1; $i <= 9; $i++): ?>
                        <h3 class="font-semibold mb-2"><?= $vendor_labels[$i - 1] ?></h3> <!-- Ubah label sesuai daftar -->

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label class="block mb-2">Nama Vendor</label>
                                <input type="text" name="vendor_<?= $i ?>" value="<?= $i == 3 ? 'Mantenbaru Organizer' : $vendor->{'vendor_'.$i} ?>" class="w-full px-4 py-2 border rounded" <?= $i == 3 ? 'readonly' : '' ?>>
                            </div>
                            <div>
                                <label class="block mb-2">Social Media</label>
                                <input type="text" name="social_media_<?= $i ?>" value="<?= $i == 3 ? 'Mantenbaru_organizer' : $vendor->{'social_media_'.$i} ?>" class="w-full px-4 py-2 border rounded" <?= $i == 3 ? 'readonly' : '' ?>>
                            </div>
                            <div>
                                <label class="block mb-2">Nama Kontak</label>
                                <input type="text" name="contact_name_<?= $i ?>" value="<?= $i == 3 ? 'Icha' : $vendor->{'contact_name_'.$i} ?>" class="w-full px-4 py-2 border rounded" <?= $i == 3 ? 'readonly' : '' ?>>
                            </div>
                            <div>
                                <label class="block mb-2">Nomor Telepon</label>
                                <input type="text" name="phone_<?= $i ?>" value="<?= $i == 3 ? '0812-1012-6196' : $vendor->{'phone_'.$i} ?>" class="w-full px-4 py-2 border rounded" <?= $i == 3 ? 'readonly' : '' ?>>
                            </div>
                        </div>

                        <label class="block mb-2">Detail</label>
                        <textarea name="detail_<?= $i ?>" class="w-full px-4 py-2 border rounded mb-4"><?= $vendor->{'detail_'.$i} ?></textarea>
                  <?php endfor; ?>

                <div class="flex flex-col sm:flex-row justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Update</button>
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
