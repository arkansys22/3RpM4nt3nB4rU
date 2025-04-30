<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Crew ke Project</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'crew', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h1 class="text-2xl font-bold mb-4">Tambah Crew ke <?= htmlspecialchars($client_name) ?></h1>
              <form action="<?= site_url('crewproject/storelist') ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <input type="hidden" name="project_id" value="<?= htmlspecialchars($project_id) ?>">

                <!-- Select Crew -->
                <div class="mb-4">
                    <label for="crew_id" class="block text-sm font-medium mb-2">Nama Crew</label>
                    <select name="crew_id" id="crew_id" class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:border-gray-600" required>
                        <option value="">-- Pilih Crew --</option>
                        <?php foreach ($crews as $crew): ?>
                            <option value="<?= htmlspecialchars($crew->id_session) ?>"><?= htmlspecialchars($crew->crew_name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Select Role -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium mb-2">Posisi</label>
                    <select name="role" id="role" class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:border-gray-600" required>
                        <option value="">-- Pilih Posisi --</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= htmlspecialchars($role->role) ?>"><?= htmlspecialchars($role->role) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row justify-end">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-600 sm:w-24 mb-2 sm:mb-0 text-center">Simpan</button>
                    <a href="<?= site_url('project/lihat/' . htmlspecialchars($project_id)) ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
