<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vendor</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
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
              <form action="<?= base_url('crud_vendor/update/' . $vendor->id_session . '/' . $vendor->vendor_id) ?>" method="post" enctype="multipart/form-data" class="bg-white p-6 shadow-md rounded">
                <input type="hidden" name="id_session" value="<?= $vendor->id_session ?>">
                <label class="block mb-2"><strong>Vendor:</strong></label>
                <input type="text" name="vendor" value="<?= $vendor->vendor ?>" class="w-full px-4 py-2 border rounded mb-4">
                <label class="block mb-2"><strong>Type:</strong></label>
                <select name="type" class="w-full px-4 py-2 border rounded mb-4">
                    <option value="Venue" <?= $vendor->type == 'Venue' ? 'selected' : '' ?>>Venue</option>
                    <option value="MC Akad" <?= $vendor->type == 'MC Akad' ? 'selected' : '' ?>>MC Akad</option>
                    <option value="MC Pemberkatan" <?= $vendor->type == 'MC Pemberkatan' ? 'selected' : '' ?>>MC Pemberkatan</option>
                    <option value="MC Resepsi" <?= $vendor->type == 'MC Resepsi' ? 'selected' : '' ?>>MC Resepsi</option>
                    <option value="Wedding Organizer" <?= $vendor->type == 'Wedding Organizer' ? 'selected' : '' ?>>WO</option>
                    <option value="MUA" <?= $vendor->type == 'MUA' ? 'selected' : '' ?>>MUA</option>
                    <option value="Perlengkapan Catering" <?= $vendor->type == 'Perlengkapan Catering' ? 'selected' : '' ?>>Perlengkapan Catering</option>
                    <option value="Catering" <?= $vendor->type == 'Catering' ? 'selected' : '' ?>>Catering</option>
                    <option value="Dokumentasi" <?= $vendor->type == 'Dokumentasi' ? 'selected' : '' ?>>Dokumentasi</option>
                    <option value="Dekorasi" <?= $vendor->type == 'Dekorasi' ? 'selected' : '' ?>>Dekorasi</option>
                    <option value="Entertainment" <?= $vendor->type == 'Entertainment' ? 'selected' : '' ?>>Entertainment</option>
                </select>
                <label class="block mb-2"><strong>Social Media:</strong></label>
                <input type="text" name="social_media" value="<?= $vendor->social_media ?>" class="w-full px-4 py-2 border rounded mb-4">
                <label class="block mb-2"><strong>Contact Name:</strong></label>
                <input type="text" name="contact_name" value="<?= $vendor->contact_name ?>" class="w-full px-4 py-2 border rounded mb-4">
                <label class="block mb-2"><strong>Phone:</strong></label>
                <input type="text" name="phone" value="<?= $vendor->phone ?>" class="w-full px-4 py-2 border rounded mb-4">
                <label class="block mb-2"><strong>Detail:</strong></label>
                <textarea name="detail" class="w-full px-4 py-2 border rounded mb-4"><?= $vendor->detail ?></textarea>
                <label class="block mb-2"><strong>Photo 1: (Photo yang akan di showing pada wedding concept)</strong></label>
                <?php if ($vendor->photo1): ?>
                  <img src="<?= base_url('uploads/' . $vendor->photo1) ?>" alt="Photo 1" class="block w-full mb-4">
                <?php endif; ?>
                <input type="file" name="photo1" class="w-full px-4 py-2 border rounded mb-4">
                <label class="block mb-2"><strong>Photo 2:</strong></label>
                <?php if ($vendor->photo2): ?>
                  <img src="<?= base_url('uploads/' . $vendor->photo2) ?>" alt="Photo 2" class="block w-full mb-4">
                <?php endif; ?>
                <input type="file" name="photo2" class="w-full px-4 py-2 border rounded mb-4">
                <label class="block mb-2"><strong>Photo 3:</strong></label>
                <?php if ($vendor->photo3): ?>
                  <img src="<?= base_url('uploads/' . $vendor->photo3) ?>" alt="Photo 3" class="block w-full mb-4">
                <?php endif; ?>
                <input type="file" name="photo3" class="w-full px-4 py-2 border rounded mb-4">
                <label class="block mb-2"><strong>Photo 4:</strong></label>
                <?php if ($vendor->photo4): ?>
                  <img src="<?= base_url('uploads/' . $vendor->photo4) ?>" alt="Photo 4" class="block w-full mb-4">
                <?php endif; ?>
                <input type="file" name="photo4" class="w-full px-4 py-2 border rounded mb-4">
                <label class="block mb-2"><strong>Photo 5:</strong></label>
                <?php if ($vendor->photo5): ?>
                  <img src="<?= base_url('uploads/' . $vendor->photo5) ?>" alt="Photo 5" class="block w-full mb-4">
                <?php endif; ?>
                <input type="file" name="photo5" class="w-full px-4 py-2 border rounded mb-4">
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
