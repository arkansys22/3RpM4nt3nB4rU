<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'project', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h1 class="text-2xl font-bold mb-4">Edit Project</h1>
              <form action="<?= site_url('project/update/'.$project->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <label class="block mb-2">Nama Project</label>
                <input type="text" name="project_name" value="<?= $project->project_name ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Nama Client</label>
                <input type="text" name="client_name" value="<?= $project->client_name ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Tanggal Pernikahan</label>
                <input type="date" name="event_date" value="<?= $project->event_date ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Value</label>
                <input type="text" id="formattedNumber" value="<?= $project->value ?>" class="w-full px-4 py-2 border rounded mb-4" oninput="formatNumber(this)" name="value" required>

                <label class="block mb-2">Detail</label>
                <textarea name="detail" class="w-full px-4 py-2 border rounded mb-4" required><?= $project->detail ?></textarea>
                
                <label class="block mb-2">Agama</label>
                <select name="religion" class="w-full px-4 py-2 border rounded mb-4" required>
                    <option value="Islam" <?= $project->religion == 'Islam' ? 'selected' : '' ?>>Islam</option>
                    <option value="Kristen" <?= $project->religion == 'Kristen' ? 'selected' : '' ?>>Kristen</option>
                    <option value="Katolik" <?= $project->religion == 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                    <option value="Hindu" <?= $project->religion == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                    <option value="Buddha" <?= $project->religion == 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                    <option value="Lainnya" <?= $project->religion == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>

                <label class="block mb-2">Lokasi</label>
                <input type="text" name="location" value="<?= $project->location ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <h4 class="text-lg font-semibold mt-4 mb-2">Pilih Koordinator</h4>

                <?php
                $roles = [
                    'koor_acara'      => 'Koordinator Acara',
                    'koor_lapangan'   => 'Koordinator Lapangan',
                    'koor_catering'   => 'Koordinator Catering',
                    'koor_pengantin'  => 'Koordinator Pengantin',
                    'koor_tamu'       => 'Koordinator Tamu',
                    'koor_tambahan1'  => 'Koordinator Tambahan1',
                    'koor_tambahan2'  => 'Koordinator Tambahan2'
                ];

                foreach ($roles as $field => $label):
                ?>
                    <label class="block mb-2"><?= $label ?></label>
                    <select name="<?= $field ?>" class="w-full px-4 py-2 border rounded mb-4">
                        <option value="-">-</option> <!-- Pilihan kosong -->
                        <?php foreach ($crews_list as $crew): ?>
                            <option value="<?= $crew->id_session ?>" 
                                <?= (isset($selected_crews->$field) && $selected_crews->$field == $crew->id_session) ? 'selected' : '' ?>>
                                <?= $crew->crew_name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endforeach; ?>

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
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js">
  </script>
  <script>
    function formatNumber(input) {
        let value = input.value.replace(/\D/g, ''); // Hanya angka
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambah titik setiap 3 digit
        input.value = value;
    }
  </script>

</body>
</html>
