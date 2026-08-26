<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Acara | Susunan Acara</title>
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
                <h1 class="text-2xl font-bold">Kategori Acara</h1>
                <a href="<?= site_url('susunan-acara') ?>"
                  class="w-full md:w-auto text-center px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">
                  &larr; Kembali ke Susunan Acara
                </a>
              </div>

              <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm"><?= $this->session->flashdata('error') ?></div>
              <?php endif; ?>
              <?php if ($this->session->flashdata('Success')): ?>
                <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 text-sm"><?= $this->session->flashdata('Success') ?></div>
              <?php endif; ?>

              <p class="text-sm text-body dark:text-bodydark mb-4">
                Buat kategori acara dulu (misalnya <em>Akad Nikah</em>, <em>Resepsi</em>), baru kegiatan-kegiatan bisa diinput satu-satu ke dalam tiap kategori dari halaman Susunan Acara.
              </p>

              <!-- Form tambah kategori -->
              <form action="<?= site_url('susunan-acara/kategori/store') ?>" method="post" class="flex flex-col sm:flex-row gap-2 mb-6">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="text" name="nama_kategori" required placeholder="Nama kategori, misalnya: Akad Nikah"
                  class="flex-1 rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input" />
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md font-medium hover:bg-opacity-90 whitespace-nowrap">
                  + Tambah Kategori
                </button>
              </form>

              <?php if (empty($kategori_list)): ?>
                <p class="text-sm text-body dark:text-bodydark">Belum ada kategori.</p>
              <?php else: ?>
              <div class="space-y-3">
                <?php $total = count($kategori_list); foreach ($kategori_list as $i => $kat): ?>
                <div x-data="{ editing: false }" class="rounded-md border border-stroke dark:border-strokedark p-4">
                  <div x-show="!editing" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <span class="font-semibold text-black dark:text-white"><?= htmlspecialchars($kat->nama_kategori) ?></span>
                    <div class="flex flex-wrap gap-2">
                      <a href="<?= site_url('susunan-acara/create/' . $kat->id_session) ?>" class="px-3 py-1.5 border rounded-md text-sm hover:bg-whiter dark:hover:bg-meta-4">+ Kegiatan</a>
                      <a href="<?= site_url('susunan-acara/kategori/move-up/' . $kat->id_session) ?>" class="px-3 py-1.5 border rounded-md text-sm <?= $i === 0 ? 'opacity-40 pointer-events-none' : 'hover:bg-whiter dark:hover:bg-meta-4' ?>">&uarr;</a>
                      <a href="<?= site_url('susunan-acara/kategori/move-down/' . $kat->id_session) ?>" class="px-3 py-1.5 border rounded-md text-sm <?= $i === $total - 1 ? 'opacity-40 pointer-events-none' : 'hover:bg-whiter dark:hover:bg-meta-4' ?>">&darr;</a>
                      <button type="button" @click="editing = true" class="px-3 py-1.5 border rounded-md text-sm hover:bg-whiter dark:hover:bg-meta-4">Edit</button>
                      <a href="<?= site_url('susunan-acara/kategori/duplicate/' . $kat->id_session) ?>"
                        onclick="return confirm('Duplikat kategori \'<?= htmlspecialchars(addslashes($kat->nama_kategori)) ?>\' beserta semua kegiatan di dalamnya jadi kategori baru?')"
                        class="px-3 py-1.5 border rounded-md text-sm hover:bg-whiter dark:hover:bg-meta-4">Duplikat</a>
                      <a href="<?= site_url('susunan-acara/kategori/delete/' . $kat->id_session) ?>"
                        onclick="return confirm('Hapus kategori ini beserta SEMUA kegiatan di dalamnya?')"
                        class="px-3 py-1.5 border border-red-300 text-red-600 rounded-md text-sm">Hapus</a>
                    </div>
                  </div>
                  <form x-show="editing" action="<?= site_url('susunan-acara/kategori/update/' . $kat->id_session) ?>" method="post" class="flex flex-col sm:flex-row gap-2">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="text" name="nama_kategori" required value="<?= htmlspecialchars($kat->nama_kategori) ?>"
                      class="flex-1 rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input" />
                    <div class="flex gap-2">
                      <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md font-medium hover:bg-opacity-90">Simpan</button>
                      <button type="button" @click="editing = false" class="px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">Batal</button>
                    </div>
                  </form>
                </div>
                <?php endforeach; ?>
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
