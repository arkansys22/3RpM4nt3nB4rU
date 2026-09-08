<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template List Peralatan Event</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'peralatan_event', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
            <div class="col-span-12 lg:col-span-10 lg:col-start-2 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">

              <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
                <h1 class="text-2xl font-bold">Template List Peralatan Event</h1>
                <a href="<?= site_url('peralatan-event/kategori') ?>"
                  class="w-full md:w-auto text-center px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">
                  Kelola Kategori
                </a>
              </div>

              <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm"><?= $this->session->flashdata('error') ?></div>
              <?php endif; ?>
              <?php if ($this->session->flashdata('Success')): ?>
                <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 text-sm"><?= $this->session->flashdata('Success') ?></div>
              <?php endif; ?>

              <p class="text-sm text-body dark:text-bodydark mb-6">
                Daftar barang standar ini dipakai sebagai titik awal saat membuat List Peralatan Event di sebuah project
                (tombol "List Peralatan Event" di halaman detail project) -- per project tetap bisa ditambah/diedit/dihapus sendiri.
              </p>

              <?php if (empty($grouped)): ?>
                <p class="text-sm text-body dark:text-bodydark border border-dashed border-stroke dark:border-strokedark rounded-md p-4">
                  Belum ada kategori. <a href="<?= site_url('peralatan-event/kategori') ?>" class="text-primary hover:underline">Buat kategori dulu</a> (mis. "WO", "Fotobooth") sebelum menambah item.
                </p>
              <?php else: foreach ($grouped as $group): $kat = $group['kategori']; $items = $group['items']; $total = count($items); ?>

              <div class="mb-8">
                <h2 class="text-lg font-bold mb-3 pb-2 border-b border-stroke dark:border-strokedark"><?= htmlspecialchars($kat->nama_kategori) ?></h2>

                <?php if (empty($items)): ?>
                  <p class="text-sm text-body dark:text-bodydark mb-3">Belum ada item di kategori ini.</p>
                <?php else: ?>
                <div class="space-y-2 mb-3">
                  <?php foreach ($items as $i => $item): ?>
                  <div x-data="{ editing: false }" class="rounded-md border border-stroke dark:border-strokedark p-3">
                    <div x-show="!editing" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                      <span class="<?= $item->bold ? 'font-bold' : '' ?> text-black dark:text-white">
                        <?= htmlspecialchars($item->nama_barang) ?>
                        <?php if ($item->detail !== ''): ?><span class="font-normal text-body dark:text-bodydark"> — <?= htmlspecialchars($item->detail) ?></span><?php endif; ?>
                      </span>
                      <div class="flex flex-wrap gap-2">
                        <a href="<?= site_url('peralatan-event/move-up/' . $item->id_session) ?>" class="px-3 py-1.5 border rounded-md text-sm <?= $i === 0 ? 'opacity-40 pointer-events-none' : 'hover:bg-whiter dark:hover:bg-meta-4' ?>">&uarr;</a>
                        <a href="<?= site_url('peralatan-event/move-down/' . $item->id_session) ?>" class="px-3 py-1.5 border rounded-md text-sm <?= $i === $total - 1 ? 'opacity-40 pointer-events-none' : 'hover:bg-whiter dark:hover:bg-meta-4' ?>">&darr;</a>
                        <button type="button" @click="editing = true" class="px-3 py-1.5 border rounded-md text-sm hover:bg-whiter dark:hover:bg-meta-4">Edit</button>
                        <a href="<?= site_url('peralatan-event/delete/' . $item->id_session) ?>"
                          onclick="return confirm('Hapus item \'<?= htmlspecialchars(addslashes($item->nama_barang)) ?>\'?')"
                          class="px-3 py-1.5 border border-red-300 text-red-600 rounded-md text-sm">Hapus</a>
                      </div>
                    </div>
                    <form x-show="editing" action="<?= site_url('peralatan-event/update/' . $item->id_session) ?>" method="post" class="flex flex-col sm:flex-row gap-2">
                      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                      <input type="text" name="nama_barang" required value="<?= htmlspecialchars($item->nama_barang) ?>"
                        class="flex-1 rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input" />
                      <input type="text" name="detail" value="<?= htmlspecialchars($item->detail) ?>"
                        class="flex-1 rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input" />
                      <label class="flex items-center gap-2 px-2 whitespace-nowrap text-sm">
                        <input type="checkbox" name="bold" value="1" <?= $item->bold ? 'checked' : '' ?> class="h-5 w-5"> Tebal
                      </label>
                      <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md font-medium hover:bg-opacity-90">Simpan</button>
                        <button type="button" @click="editing = false" class="px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">Batal</button>
                      </div>
                    </form>
                  </div>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Form tambah item ke kategori ini -->
                <form action="<?= site_url('peralatan-event/store') ?>" method="post" class="flex flex-col sm:flex-row gap-2">
                  <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                  <input type="hidden" name="kategori_id_session" value="<?= $kat->id_session ?>">
                  <input type="text" name="nama_barang" required placeholder="Nama barang"
                    class="flex-1 rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input" />
                  <input type="text" name="detail" placeholder="Detail (mis. 3/4/5/6/7 pcs)"
                    class="flex-1 rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-gray-600 dark:bg-form-input" />
                  <label class="flex items-center gap-2 px-2 whitespace-nowrap text-sm">
                    <input type="checkbox" name="bold" value="1" class="h-5 w-5"> Tebal
                  </label>
                  <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md font-medium hover:bg-opacity-90 whitespace-nowrap">
                    + Tambah ke <?= htmlspecialchars($kat->nama_kategori) ?>
                  </button>
                </form>
              </div>

              <?php endforeach; endif; ?>

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
