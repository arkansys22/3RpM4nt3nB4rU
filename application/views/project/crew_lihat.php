<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Project</title>
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
              <h1 class="text-2xl font-bold mb-4">Lihat project</h1>
              <form action="<?= site_url('project/update/'.$project->id_session) ?>" method="post" class="bg-white dark:bg-boxdark p-6 shadow-md rounded">
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Project : </strong><?= $project->project_name ?></label>        
                <label class="block mb-2 text-black dark:text-white"><strong>Agama : </strong><?= $project->religion ?></label>        
                <label class="block mb-2 text-black dark:text-white"><strong>Tanggal Pernikahan : </strong><?= hari($project->event_date) ?>, <?= tgl_indo($project->event_date) ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Lokasi : </strong><?= $project->location ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Detail : </strong><?= $project->detail ?></label>

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

                <h2 class="text-lg font-bold mb-2">Job Description</h2>
                <div class="border p-4 mb-4 text-black dark:text-white">
                    <?php 
                    $user_role = null;
                    foreach ($roles as $field => $label) {
                        // Match crew_id from crew_projects with crews_idsession in the user table
                        if (!empty($crew_project->$field) && $crew_project->$field === $user->crews_idsession) {
                            $user_role = $label;
                            break;
                        }
                    }
                    ?>
                    <?php if ($user_role): ?>
                        <p><strong>Role:</strong> <?= htmlspecialchars($user_role) ?></p>
                        <button 
                            type="button" 
                            class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                            onclick="document.getElementById('jobdescModal').classList.remove('hidden')">
                            Lihat Jobdesc
                        </button>
                    <?php else: ?>
                        <p class="text-red-500 font-semibold">Anda tidak terdaftar sebagai crew dalam proyek ini.</p>
                    <?php endif; ?>
                </div>

                <!-- Jobdesc Modal -->
                <div id="jobdescModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white dark:bg-boxdark p-6 rounded shadow-lg max-w-lg w-full">
                        <h3 class="text-lg font-bold mb-4">Job Description</h3>
                        <p class="text-black dark:text-white">
                            <?php 
                            // Replace this with the actual job description content based on the role
                            echo $user_role ? "Deskripsi pekerjaan untuk $user_role." : "Tidak ada deskripsi pekerjaan.";
                            ?>
                        </p>
                        <button 
                            type="button" 
                            class="mt-4 bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-700"
                            onclick="document.getElementById('jobdescModal').classList.add('hidden')">
                            Tutup
                        </button>
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
                  <label class="block mb-2 text-black dark:text-white"><strong><?= $label ?> : </strong><?= $crew->crew_name ?></label>
                  <?php 
                  endif;
                  endforeach; 
                endif; 
                ?>

                <h2 class="text-lg font-bold mb-2">List Crew</h2>

                <div class="border p-4 mb-4 text-black dark:text-white">
                    <?php if (!empty($crew_list)): ?>
                        <?php foreach ($crew_list as $crew): ?>
                            <div class="mb-2 flex items-center justify-between border-b pb-2">
                                <div>
                                    <p class='text-black dark:text-white font-medium'>
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

                <div class="border p-4 mb-4 text-black dark:text-white">
                    <?php if (!empty($vendors)): ?>
                        <?php foreach ($vendors as $vendor): ?>
                            <div class="mb-2 flex items-center justify-between border-b pb-2">
                                <div>
                                    <p class='text-black dark:text-white font-medium'>
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
