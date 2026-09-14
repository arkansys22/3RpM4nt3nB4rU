<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Vendor — <?= htmlspecialchars($vendors->vendor) ?></title>
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
            <div class="col-span-12 lg:col-span-8 lg:col-start-3 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">

              <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
                <h1 class="text-2xl font-bold">Detail Vendor</h1>
                <div class="flex flex-wrap gap-2">
                  <a href="<?= site_url('vendor/edit/' . $vendors->id_session . '/' . $vendors->vendor_id) ?>"
                    class="px-4 py-2 bg-green-500 text-white rounded-md font-medium hover:bg-green-600">
                    Edit
                  </a>
                  <a href="<?= site_url('project/lihat/' . $vendors->id_session) ?>"
                    class="px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">
                    &larr; Kembali ke Project
                  </a>
                </div>
              </div>

              <div class="space-y-2 mb-6">
                <label class="block"><strong>Tipe : </strong><?= htmlspecialchars($vendors->type) ?></label>
                <label class="block"><strong>Nama Vendor : </strong><?= htmlspecialchars($vendors->vendor) ?></label>
                <label class="block"><strong>Kontak : </strong><?= $vendors->contact_name ? htmlspecialchars($vendors->contact_name) : '-' ?></label>
                <label class="block">
                  <strong>No HP : </strong>
                  <?php if (!empty($vendors->phone)): ?>
                    <a href="https://wa.me/<?= htmlspecialchars($vendors->phone) ?>" class="text-primary hover:underline"><?= htmlspecialchars($vendors->phone) ?></a>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </label>
                <label class="block">
                  <strong>Sosial Media : </strong>
                  <?= $vendors->social_media ? htmlspecialchars($vendors->social_media) : '-' ?>
                </label>
                <label class="block"><strong>Detail / Catatan : </strong></label>
                <p class="whitespace-pre-line text-body dark:text-bodydark border border-stroke dark:border-strokedark rounded-md p-3">
                  <?= $vendors->detail ? nl2br(htmlspecialchars($vendors->detail)) : '-' ?>
                </p>
              </div>

              <?php
                // photo1 = cover/logo (kolom tersendiri), sisanya galeri
                // dengan jumlah bebas dari tabel vendor_photos.
                $semua_foto = $vendors->photo1 ? [$vendors->photo1] : [];
                foreach ($photos as $photo) { $semua_foto[] = $photo->file_name; }
              ?>
              <?php if (!empty($semua_foto)): ?>
              <h2 class="text-lg font-bold mb-3">Foto / Konsep</h2>
              <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                <?php foreach ($semua_foto as $i => $foto): ?>
                <a href="<?= base_url('uploads/' . $foto) ?>" target="_blank">
                  <img src="<?= base_url('uploads/' . $foto) ?>" alt="Foto <?= $i + 1 ?>" class="w-full h-32 object-cover rounded-md border border-stroke dark:border-strokedark">
                </a>
                <?php endforeach; ?>
              </div>
              <?php else: ?>
              <p class="text-sm text-body dark:text-bodydark">Belum ada foto/konsep yang diunggah untuk vendor ini.</p>
              <?php endif; ?>

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
