<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Projects</title>
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'projects', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h1 class="text-2xl font-bold mb-4">Tambah Projects</h1>
              <form action="<?= site_url('projects/store') ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <label class="block mb-2">Nama Projects</label>
                <input type="text" name="project_name" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Nama Clients</label>
                <input type="text" name="client_name" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Tanggal Pernikahan</label>
                <input type="date" name="event_date" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Value</label>
                <input type="number" name="value" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Detail</label>
                <textarea name="detail" class="w-full px-4 py-2 border rounded mb-4" required></textarea>

                <label class="block mb-2">Agama</label>
                <select name="religion" class="w-full px-4 py-2 border rounded mb-4" required>
                    <option value="">Pilih Agama</option>
                    <option value="Islam" <?= set_select('religion', 'Islam') ?>>Islam</option>
                    <option value="Kristen" <?= set_select('religion', 'Kristen') ?>>Kristen</option>
                    <option value="Katolik" <?= set_select('religion', 'Katolik') ?>>Katolik</option>
                    <option value="Hindu" <?= set_select('religion', 'Hindu') ?>>Hindu</option>
                    <option value="Buddha" <?= set_select('religion', 'Buddha') ?>>Buddha</option>
                    <option value="Lainnya" <?= set_select('religion', 'Lainnya') ?>>Lainnya</option>
                  </select>
                
                <label class="block mb-2">Lokasi</label>
                <input type="text" name="location" class="w-full px-4 py-2 border rounded mb-4" required>

                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                <a href="<?= site_url('projects') ?>" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
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
