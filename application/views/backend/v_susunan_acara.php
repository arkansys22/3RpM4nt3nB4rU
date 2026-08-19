<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Susunan Acara</title>
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

              <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-6">
                <h1 class="text-2xl font-bold">Susunan Acara</h1>
                <a href="<?= site_url('susunan-acara/kategori') ?>"
                  class="w-full md:w-auto text-center px-4 py-2 bg-primary text-white rounded-md shadow hover:bg-opacity-90 font-medium">
                  Kelola Kategori
                </a>
              </div>

              <?php if (empty($grouped)): ?>
                <p class="text-sm text-body dark:text-bodydark">
                  Belum ada kategori acara. Klik "Kelola Kategori" untuk membuat kategori (mis. Akad Nikah, Resepsi) terlebih dahulu, baru kegiatan bisa diinput.
                </p>
              <?php else: ?>

              <!-- Mobile: cards -->
              <div class="sm:hidden space-y-3">
                <?php foreach ($grouped as $group): $kat = $group['kategori']; $jumlah = count($group['kegiatan']); ?>
                <div class="rounded-md border border-stroke dark:border-strokedark p-4">
                  <p class="font-semibold text-black dark:text-white"><?= htmlspecialchars($kat->nama_kategori) ?></p>
                  <p class="text-sm text-body dark:text-bodydark mb-3"><?= $jumlah ?> kegiatan</p>
                  <div class="flex flex-wrap gap-2">
                    <a href="<?= site_url('susunan-acara/lihat/' . $kat->id_session) ?>"
                      class="px-3 py-1.5 bg-primary text-white rounded-md text-sm font-medium hover:bg-opacity-90">
                      Lihat Kegiatan
                    </a>
                    <a href="<?= site_url('susunan-acara/presentasi/' . $kat->id_session) ?>" target="_blank"
                      class="px-3 py-1.5 border rounded-md text-sm font-medium hover:bg-whiter dark:hover:bg-meta-4">
                      Presentasi
                    </a>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>

              <!-- Desktop: table -->
              <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left">
                  <thead>
                    <tr class="border-b border-stroke dark:border-strokedark">
                      <th class="py-3 pr-4 font-medium text-black dark:text-white">Kategori Acara</th>
                      <th class="py-3 pr-4 font-medium text-black dark:text-white">Jumlah Kegiatan</th>
                      <th class="py-3 pr-4 font-medium text-black dark:text-white">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($grouped as $group): $kat = $group['kategori']; $jumlah = count($group['kegiatan']); ?>
                    <tr class="border-b border-stroke dark:border-strokedark">
                      <td class="py-3 pr-4 font-medium text-black dark:text-white"><?= htmlspecialchars($kat->nama_kategori) ?></td>
                      <td class="py-3 pr-4"><?= $jumlah ?> kegiatan</td>
                      <td class="py-3 pr-4">
                        <div class="flex items-center gap-2">
                          <a href="<?= site_url('susunan-acara/lihat/' . $kat->id_session) ?>"
                            class="inline-block px-3 py-1.5 bg-primary text-white rounded-md text-sm font-medium hover:bg-opacity-90">
                            Lihat Kegiatan
                          </a>
                          <a href="<?= site_url('susunan-acara/presentasi/' . $kat->id_session) ?>" target="_blank"
                            class="inline-block px-3 py-1.5 border rounded-md text-sm font-medium hover:bg-whiter dark:hover:bg-meta-4">
                            Presentasi
                          </a>
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
