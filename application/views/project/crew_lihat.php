<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Project</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
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
            <div class="flex justify-between items-center mb-4">  
              <h1 class="text-2xl font-bold mb-4">Lihat project</h1>
              <a href="<?= site_url('panel/staff') ?>" class="flex items-center gap-2 bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                  </svg>
                </a>
            </div>
              <form action="<?= site_url('project/update/'.$project->id_session) ?>" method="post" class="bg-white dark:bg-boxdark p-6 shadow-md rounded">
                <label class="block mb-2"><strong>Nama Project : </strong><?= $project->project_name ?></label>        
                <label class="block mb-2"><strong>Agama : </strong><?= $project->religion ?></label>        
                <label class="block mb-2"><strong>Tanggal Pernikahan : </strong><?= hari($project->event_date) ?>, <?= tgl_indo($project->event_date) ?></label>
                <label class="block mb-2"><strong>Lokasi : </strong><?= $project->location ?></label>
                <label class="block mb-2"><strong>Maps : </strong>
                  <?php if (!empty($clients->maps)): ?>
                  <a href="<?= $clients->maps ?>" target="_blank" class="text-blue-500 underline">Lihat Maps</a>
                  <?php else: ?>
                  <span class="text-red-500">Belum tersedia</span>
                  <?php endif; ?>
                </label>
                <label class="block mb-2"><strong>Jam Stand by : </strong>
                  <?php if (!empty($clients->stand_by) && $clients->stand_by !== '00:00:00'): ?>
                  <?= date('H:i', strtotime($clients->stand_by)) ?>
                  <?php else: ?>
                  <span class="text-red-500">Belum tersedia</span>
                  <?php endif; ?>
                </label>
                <label class="block mb-2"><strong>Seragam : </strong>
                  <?php if (!empty($clients->uniform)): ?>
                  <?= $clients->uniform ?>
                  <?php else: ?>
                  <span class="text-red-500">Belum tersedia</span>
                  <?php endif; ?>
                </label>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2 mb-2">
                      <?php if (!empty($clients->wedding_ceremony)): ?>
                        <?php if ($project->religion == 'Islam'): ?>
                          <a href="<?= $clients->wedding_ceremony ?>" target="_blank" class="w-full sm:w-auto block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-2 sm:mb-0 text-center">Lihat Susunan Akad</a>
                        <?php else: ?>
                          <a href="<?= $clients->wedding_ceremony ?>" target="_blank" class="w-full sm:w-auto block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-2 sm:mb-0 text-center">Lihat Susunan Pemberkatan</a>
                        <?php endif; ?>
                      <?php endif; ?>

                      <?php if (!empty($clients->reception_afterward)): ?>
                        <a href="<?= $clients->reception_afterward ?>" target="_blank" class="w-full sm:w-auto block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-2 sm:mb-0 text-center">Lihat Susunan Resepsi</a>
                      <?php endif; ?>

                      <?php if (!empty($clients->list_photo)) : ?>
                        <a href="<?= $clients->list_photo ?>" target="_blank" class="w-full sm:w-auto block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-center">List Foto</a>
                      <?php endif; ?>
                    </div>

                <?php
                // Ensure $roles is defined
                $roles = [
                  'koor_acara'      => 'Koordinator Acara',
                  'koor_lapangan'   => 'Koordinator Lapangan',
                  'koor_catering'   => 'Koordinator Catering',
                  'koor_pengantin'  => 'Koordinator Pengantin',
                  'koor_tamu'       => 'Koordinator Tamu',
                  'koor_tambahan1'  => 'Koordinator Tambahan1',
                  'koor_tambahan2'  => 'Koordinator Tambahan2'
                ];
                ?>

                    <div x-data="{ modalOpen: false }" class="block mb-2">
                    <?php if (!empty($crew_role)): ?>
                      <p><strong>Role:</strong> <?= htmlspecialchars($crew_role->role) ?></p>
                      <button
                      type="button"
                      @click.prevent="modalOpen = true"
                      class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-full sm:w-auto"
                      >
                      Lihat Jobdesc
                      </button>
                    <?php else: ?>
                      <p class="text-red-500 font-semibold">Job description belum tersedia</p>
                    <?php endif; ?>

                    <div
                      x-show="modalOpen"
                      x-cloak
                      x-transition
                      class="fixed left-0 top-0 z-999999 flex h-full min-h-screen w-full items-center justify-center bg-black/90 px-4 py-5"
                    >
                      <div
                      @click.outside="modalOpen = false"
                      class="w-full max-w-142.5 rounded-lg bg-white px-8 py-12 text-center dark:bg-boxdark md:px-17.5 md:py-15"
                      >
                      <h3 class="pb-2 text-xl font-bold sm:text-2xl">
                        Job Description
                      </h3>
                      <span
                        class="mx-auto mb-6 inline-block h-1 w-22.5 rounded bg-blue-500 dark:bg-primary"
                      ></span>
                      <div 
                        class="whitespace-pre-line max-h-96 overflow-y-auto text-left mb-6 px-2 scrollbar-thin"
                        :class="darkMode ? 'scrollbar-thumb-white' : 'scrollbar-thumb-gray-400'"
                        style="scrollbar-width: thin; scrollbar-color: #9ca3af transparent;"
                      >
                        <?= $jobdesc ? nl2br(htmlspecialchars($jobdesc)) : "Tidak ada deskripsi pekerjaan." ?>
                      </div>
                      <style>
                        .scrollbar-thin::-webkit-scrollbar {
                        width: 4px;
                        }
                        .scrollbar-thin::-webkit-scrollbar-thumb {
                        background: #9ca3af;
                        border-radius: 2px;
                        }
                        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
                        background: #fff;
                        }
                        .scrollbar-thin::-webkit-scrollbar-track {
                        background: transparent;
                        }
                      </style>
                      <div class="-mx-3 flex flex-wrap gap-y-4 mt-6">
                        <div class="w-full px-3">
                        <button
                          type="button"
                          @click="modalOpen = false"
                          :class="darkMode 
                          ? 'block w-full rounded border border-stroke bg-gray p-3 text-center font-medium transition hover:border-meta-1 hover:bg-meta-1 dark:border-strokedark dark:bg-meta-4 dark:hover:border-meta-1 dark:hover:bg-meta-1 text-white'
                          : 'block w-full rounded border border-gray-300 bg-gray-100 p-3 text-center font-medium transition hover:border-blue-500 hover:bg-blue-500 hover:text-white text-black'"
                        >
                          Tutup
                        </button>
                        </div>
                      </div>
                      </div>
                    </div>

                <?php
                $hasCrew = false;
                foreach ($roles as $field => $label) {
                  if (!empty($crew_project->$field)) {
                  $hasCrew = true;
                  break;
                  }
                }

                if ($hasCrew): ?>
                  <h4 class="text-lg font-semibold mt-4 mb-2">List Crew</h4>
                  <?php foreach ($roles as $field => $label):
                  if (!empty($crew_project->$field)):
                    $crew = $this->Crud_m->view_where('crews', array('id_session' => $crew_project->$field))->row();
                  ?>
                  <label class="block mb-2"><strong><?= $label ?> : </strong><?= $crew->crew_name ?></label>
                  <?php 
                  endif;
                  endforeach; 
                endif; 
                ?>

                <h2 class="text-lg font-bold mb-2">List Crew</h2>

                <div class="border p-4 mb-4">
                  <?php
                  // Define the order of roles
                  $role_order = [
                  'Koordinator Acara',
                  'Koordinator Lapangan',
                  'Koordinator Catering',
                  'Koordinator Pengantin',
                  'Koordinator Tamu',
                  'Koordinator Tambahan1',
                  'Koordinator Tambahan2'
                  ];

                  // Sort the crew list based on the defined role order
                  usort($crew_list, function ($a, $b) use ($role_order) {
                  $posA = array_search($a->role, $role_order);
                  $posB = array_search($b->role, $role_order);

                  // If not found in the array, place at the end
                  $posA = ($posA === false) ? count($role_order) : $posA;
                  $posB = ($posB === false) ? count($role_order) : $posB;

                  return $posA - $posB;
                  });
                  ?>

                  <?php if (!empty($crew_list)): ?>
                  <?php foreach ($crew_list as $index => $crew): ?>
                    <div class="mb-2 flex items-center justify-between <?= $index === count($crew_list) - 1 ? '' : 'border-b pb-2' ?>">
                    <div>
                      <p class='font-medium'>
                      <strong><?= htmlspecialchars($crew->role) ?>:</strong> 
                      <?= htmlspecialchars($crew->crew_name) ?>
                      </p>
                    </div>
                    </div>
                  <?php endforeach; ?>
                  <?php else: ?>
                  <p class='text-red-500 font-semibold'>Belum ada crew.</p>
                  <?php endif; ?>
                </div>

                <h2 class="text-lg font-bold mb-2">Vendor</h2>
                  <?php
                  // Tentukan urutan yang diinginkan
                  $type_order = [
                    'Venue', 'MC Akad', 'MC Pemberkatan', 'MC Resepsi', 'Wedding Organizer', 'MUA',
                    'Perlengkapan Catering', 'Catering', 'Dokumentasi',
                    'Dekorasi', 'Entertaiment'
                  ];

                  // Urutkan vendor berdasarkan tipe
                  usort($vendors, function ($a, $b) use ($type_order) {
                    $posA = array_search($a->type, $type_order);
                    $posB = array_search($b->type, $type_order);

                    // Jika tidak ditemukan dalam array, tempatkan di akhir
                    $posA = ($posA === false) ? count($type_order) : $posA;
                    $posB = ($posB === false) ? count($type_order) : $posB;

                    return $posA - $posB;
                  });
                  ?>

                <div class="border p-4 mb-4">
                  <?php if (!empty($vendors)): ?>
                    <?php foreach ($vendors as $index => $vendor): ?>
                      <div class="mb-2 flex items-center justify-between <?= $index === count($vendors) - 1 ? '' : 'border-b pb-2' ?>">
                        <div>
                          <p class='font-medium'>
                            <strong><?= htmlspecialchars($vendor->type) ?>:</strong> 
                            <?= htmlspecialchars($vendor->vendor) ?>
                          </p>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <p class='text-red-500 font-semibold'>Belum ada vendor.</p>
                  <?php endif; ?>
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
