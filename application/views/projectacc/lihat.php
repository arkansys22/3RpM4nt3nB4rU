<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Project</title>
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
              <h1 class="text-2xl font-bold mb-4">Finance project</h1>
              <form action="<?= site_url('project/update/'.$project->id_session) ?>" method="post" class="bg-white dark:bg-boxdark p-6 shadow-md rounded">
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Project : </strong><?= $project->project_name ?></label>        
                <label class="block mb-2 text-black dark:text-white"><strong>Agama : </strong><?= $project->religion ?></label> 
                <label class="block mb-2 text-black dark:text-white"><strong>Tanggal Pernikahan : </strong><?= hari($project->event_date) ?>, <?= tgl_indo($project->event_date) ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Lokasi : </strong><?= $project->location ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Nilai Project : </strong><?= "Rp " . number_format($project->value, 0, ',', '.'); ?></label>

                <label class="block mb-2 text-black dark:text-white"><strong>Biaya Pokok : </strong>
                <?= "Rp " . number_format($modal_ops->total_finance_out, 0, ',', '.'); ?>
                </label>

                <label class="block mb-2 text-black dark:text-white"><strong>Gross Profit : </strong>

                <?php $profit = $project->value - $modal_ops->total_finance_out ?>
                <?= "Rp " . number_format($profit, 0, ',', '.'); ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Detail : </strong><?= $project->detail ?></label>

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

   


                <a href="<?= site_url('finance-project/edit/'. $project->id_session) ?>" class="ml-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 inline-block text-center w-auto">Tambah Transaksi</a>
                <a href="<?= site_url('finance-project/edit2/'. $project->id_session) ?>" class="ml-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 inline-block text-center w-auto">Tambah Crew</a>
                <a href="javascript:history.back()" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 inline-block text-center w-auto">Kembali</a>
              </form>

              <!-- ====== Table Three Start -->
              <div class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default  dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1" >
                <div class="max-w-full overflow-x-auto">
                  <table class="w-full table-auto">
                    <thead>
                      <tr class="bg-gray-2 text-left dark:bg-meta-4">
                        <th class="px-4 py-4 font-medium text-black dark:text-white">
                          No
                        </th>
                        <th class="px-4 py-4 font-medium text-black dark:text-white">
                          Date
                        </th>
                        <th class="px-4 py-4 font-medium text-black dark:text-white">
                          Nama Transaksi
                        </th>
                        <th class="px-4 py-4 font-medium text-black dark:text-white">
                          Nominal
                        </th>
                        <th class="px-4 py-4 font-medium text-black dark:text-white">
                           
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1; foreach ($financeacc as $p): ?>
                      <tr>                        
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                          <p class="text-black dark:text-white"><?=$no++?></p>
                        </td>                        
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                          <p class="inline-flex rounded-full bg-success bg-opacity-10 px-3 py-1 text-sm font-medium text-success">
                            <?= tgl_indo($p->tanggal_transaksi) ?>
                          </p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p class="text-black dark:text-white"><?= $p->nama_transaksi ?></p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                         <p class="text-black dark:text-white" style="text-align: right;">Rp <?= number_format($p->nominal_transaksi, 0, ',', '.'); ?></p>
                        </td>

                        <?php 
                          $nilai1 = $p->nominal_transaksi;
                          $nilai2 = $p->nominal_transaksi;
                          $total = $nilai1++; 
                          ?>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                         <a href="<?= site_url('crud_finance_project/delete/' . $p->project_id_session . '/' . $p->id_session) ?>" class="inline-flex justify-center bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 min-w-full text-center" onclick="return confirm('Yakin ingin menghapus transaksi <?= $p->nama_transaksi ?> ?')">Hapus</a>
                        </td>
                      </tr>
                      <?php endforeach; ?>                            
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- ====== Table Three End -->
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
