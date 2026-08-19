<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Susunan Acara — <?= htmlspecialchars($kategori->nama_kategori) ?></title>
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
            <div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">

              <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm"><?= $this->session->flashdata('error') ?></div>
              <?php endif; ?>
              <?php if ($this->session->flashdata('Success')): ?>
                <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 text-sm"><?= $this->session->flashdata('Success') ?></div>
              <?php endif; ?>

              <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-2">
                <h1 class="text-2xl font-bold"><?= htmlspecialchars($kategori->nama_kategori) ?></h1>
                <a href="<?= site_url('susunan-acara') ?>"
                  class="w-full md:w-auto text-center px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">
                  &larr; Kembali ke Daftar Kategori
                </a>
              </div>

              <div class="flex flex-col sm:flex-row justify-end gap-2 mb-6">
                <a href="<?= site_url('susunan-acara/presentasi/' . $kategori->id_session) ?>" target="_blank"
                  class="w-full sm:w-auto text-center px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">
                  Presentasi
                </a>
                <a href="<?= site_url('susunan-acara/create/' . $kategori->id_session) ?>"
                  class="w-full sm:w-auto text-center px-4 py-2 bg-primary text-white rounded-md shadow hover:bg-opacity-90 font-medium">
                  + Tambah Kegiatan
                </a>
              </div>

              <?php $total = count($kegiatan_list); ?>

              <?php if (empty($kegiatan_list)): ?>
              <p class="text-sm text-body dark:text-bodydark border border-dashed border-stroke dark:border-strokedark rounded-md p-4">
                Belum ada kegiatan di kategori ini. Klik "+ Tambah Kegiatan" untuk mulai mengisi.
              </p>
              <?php else: ?>

              <!-- Mobile: cards -->
              <div class="sm:hidden space-y-3">
                <?php foreach ($kegiatan_list as $i => $item): ?>
                <div class="rounded-md border border-stroke dark:border-strokedark p-4">
                  <div class="flex gap-3">
                    <?php if (!empty($item->foto)): ?>
                    <img src="<?= base_url('assets/uploads/susunan_acara/' . $item->foto) ?>" alt="" class="w-16 h-16 object-cover rounded-md flex-shrink-0">
                    <?php endif; ?>
                    <div class="flex-1 min-w-0">
                      <p class="font-semibold text-black dark:text-white"><?= nl2br(htmlspecialchars($item->nama_kegiatan)) ?></p>
                      <p class="text-sm text-body dark:text-bodydark">Durasi: <?= substr($item->durasi, 0, 5) ?></p>
                      <p class="text-sm text-body dark:text-bodydark">Vendor/PJ: <?= htmlspecialchars($item->vendor_pj) ?></p>
                    </div>
                  </div>
                  <div class="mt-3 flex flex-wrap gap-2">
                    <a href="<?= site_url('susunan-acara/move-up/' . $item->id_session) ?>" class="px-3 py-1.5 border rounded-md text-sm <?= $i === 0 ? 'opacity-40 pointer-events-none' : '' ?>">&uarr; Naik</a>
                    <a href="<?= site_url('susunan-acara/move-down/' . $item->id_session) ?>" class="px-3 py-1.5 border rounded-md text-sm <?= $i === $total - 1 ? 'opacity-40 pointer-events-none' : '' ?>">&darr; Turun</a>
                    <a href="<?= site_url('susunan-acara/edit/' . $item->id_session) ?>" class="px-3 py-1.5 border rounded-md text-sm">Edit</a>
                    <a href="<?= site_url('susunan-acara/delete/' . $item->id_session) ?>" onclick="return confirm('Hapus kegiatan ini?')" class="px-3 py-1.5 border border-red-300 text-red-600 rounded-md text-sm">Hapus</a>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>

              <!-- Desktop: table -->
              <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left">
                  <thead>
                    <tr class="border-b border-stroke dark:border-strokedark">
                      <th class="py-3 pr-4 font-medium text-black dark:text-white">Foto</th>
                      <th class="py-3 pr-4 font-medium text-black dark:text-white">Nama Kegiatan</th>
                      <th class="py-3 pr-4 font-medium text-black dark:text-white">Durasi</th>
                      <th class="py-3 pr-4 font-medium text-black dark:text-white">Vendor/PJ</th>
                      <th class="py-3 pr-4 font-medium text-black dark:text-white">Urutan</th>
                      <th class="py-3 pr-4 font-medium text-black dark:text-white">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($kegiatan_list as $i => $item): ?>
                    <tr class="border-b border-stroke dark:border-strokedark">
                      <td class="py-3 pr-4">
                        <?php if (!empty($item->foto)): ?>
                        <img src="<?= base_url('assets/uploads/susunan_acara/' . $item->foto) ?>" alt="" class="w-14 h-14 object-cover rounded-md">
                        <?php else: ?>
                        <span class="text-xs text-body dark:text-bodydark">-</span>
                        <?php endif; ?>
                      </td>
                      <td class="py-3 pr-4 font-medium text-black dark:text-white"><?= nl2br(htmlspecialchars($item->nama_kegiatan)) ?></td>
                      <td class="py-3 pr-4"><?= substr($item->durasi, 0, 5) ?></td>
                      <td class="py-3 pr-4"><?= htmlspecialchars($item->vendor_pj) ?></td>
                      <td class="py-3 pr-4">
                        <div class="flex items-center gap-1">
                          <a href="<?= site_url('susunan-acara/move-up/' . $item->id_session) ?>" title="Naik" class="p-1 border rounded <?= $i === 0 ? 'opacity-40 pointer-events-none' : 'hover:bg-whiter dark:hover:bg-meta-4' ?>">&uarr;</a>
                          <a href="<?= site_url('susunan-acara/move-down/' . $item->id_session) ?>" title="Turun" class="p-1 border rounded <?= $i === $total - 1 ? 'opacity-40 pointer-events-none' : 'hover:bg-whiter dark:hover:bg-meta-4' ?>">&darr;</a>
                        </div>
                      </td>
                      <td class="py-3 pr-4">
                        <div class="flex items-center gap-3">
                          <a href="<?= site_url('susunan-acara/edit/' . $item->id_session) ?>" class="text-primary hover:underline">Edit</a>
                          <a href="<?= site_url('susunan-acara/delete/' . $item->id_session) ?>" onclick="return confirm('Hapus kegiatan ini?')" class="text-red-600 hover:underline">Hapus</a>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

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
