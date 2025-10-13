<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Project</title>
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
                  <h1 class="text-2xl font-bold">Finance Project</h1>
                  <div
                    x-data="{openDropDown: false}"
                    class="relative inline-block"
                  >
                    <a
                      href="#"
                      @click.prevent="openDropDown = !openDropDown"
                      class="inline-flex items-center gap-2.5 rounded-md bg-primary px-5.5 py-3 font-medium text-white hover:bg-opacity-95"
                    >
                      Menu
                      <svg
                        class="fill-current duration-200 ease-linear"
                        :class="openDropDown && 'rotate-180'"
                        width="12"
                        height="7"
                        viewBox="0 0 12 7"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M0.564864 0.879232C0.564864 0.808624 0.600168 0.720364 0.653125 0.667408C0.776689 0.543843 0.970861 0.543844 1.09443 0.649756L5.82517 5.09807C5.91343 5.18633 6.07229 5.18633 6.17821 5.09807L10.9089 0.649756C11.0325 0.526192 11.2267 0.543844 11.3502 0.667408C11.4738 0.790972 11.4562 0.985145 11.3326 1.10871L6.60185 5.55702C6.26647 5.85711 5.73691 5.85711 5.41917 5.55702L0.670776 1.10871C0.600168 1.0381 0.564864 0.967492 0.564864 0.879232Z"
                          fill=""
                        />
                        <path
                          fill-rule="evenodd"
                          clip-rule="evenodd"
                          d="M1.4719 0.229332L6.00169 4.48868L10.5171 0.24288C10.9015 -0.133119 11.4504 -0.0312785 11.7497 0.267983C12.1344 0.652758 12.0332 1.2069 11.732 1.50812L11.7197 1.52041L6.97862 5.9781C6.43509 6.46442 5.57339 6.47872 5.03222 5.96853C5.03192 5.96825 5.03252 5.96881 5.03222 5.96853L0.271144 1.50833C0.123314 1.3605 -5.04223e-08 1.15353 -3.84322e-08 0.879226C-2.88721e-08 0.660517 0.0936127 0.428074 0.253705 0.267982C0.593641 -0.0719548 1.12269 -0.0699964 1.46204 0.220873L1.4719 0.229332ZM5.41917 5.55702C5.73691 5.85711 6.26647 5.85711 6.60185 5.55702L11.3326 1.10871C11.4562 0.985145 11.4738 0.790972 11.3502 0.667408C11.2267 0.543844 11.0325 0.526192 10.9089 0.649756L6.17821 5.09807C6.07229 5.18633 5.91343 5.18633 5.82517 5.09807L1.09443 0.649756C0.970861 0.543844 0.776689 0.543843 0.653125 0.667408C0.600168 0.720364 0.564864 0.808624 0.564864 0.879232C0.564864 0.967492 0.600168 1.0381 0.670776 1.10871L5.41917 5.55702Z"
                          fill=""
                        />
                      </svg>
                    </a>
                    <div
                      x-show="openDropDown"
                      @click.outside="openDropDown = false"
                      class="absolute left-0 top-full z-40 mt-2 w-full rounded-md border border-stroke bg-white py-3 shadow-card dark:border-strokedark dark:bg-boxdark"
                    >
                      <ul class="flex flex-col">
                        <li>
                          <a
                            href="<?= site_url('finance-project/create/' . $project->id_session) ?>"
                            class="flex px-5 py-2 font-medium hover:bg-whiter hover:text-primary dark:hover:bg-meta-4"
                          >
                            Tambah Transaksi
                          </a>
                        </li>
                        <li>
                          <a
                            href="<?= site_url('finance-project/create2/' . $project->id_session) ?>"
                            class="flex px-5 py-2 font-medium hover:bg-whiter hover:text-primary dark:hover:bg-meta-4"
                          >
                            Tambah Crew
                          </a>
                        </li>
                        <li>
                          <a
                            href="<?= site_url('finance-project') ?>"
                            class="flex px-5 py-2 font-medium hover:bg-whiter hover:text-primary dark:hover:bg-meta-4"
                          >
                            Kembali
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              <form action="<?= site_url('project/update/'.$project->id_session) ?>" method="post" class="bg-white dark:bg-boxdark p-6 shadow-md rounded">
                <label class="block mb-2"><strong>Nama Project : </strong><?= $project->project_name ?></label>        
                <label class="block mb-2"><strong>Agama : </strong><?= $project->religion ?></label> 
                <label class="block mb-2"><strong>Tanggal Pernikahan : </strong><?= hari($project->event_date) ?>, <?= tgl_indo($project->event_date) ?></label>
                <label class="block mb-2"><strong>Lokasi : </strong><?= $project->location ?></label>
                <label class="block mb-2"><strong>Nilai Project : </strong><?= "Rp " . number_format($project->value, 0, ',', '.'); ?></label>

                <label class="block mb-2"><strong>Sudah Dibayar : </strong>
                <?= "Rp " . number_format($terbayar_ops->total_dibayarkan, 0, ',', '.'); ?> 

                <strong>Kekurangan</strong> <?php $kurang = $project->value - $terbayar_ops->total_dibayarkan  ?>
                <?= "Rp " . number_format($kurang, 0, ',', '.'); ?>
                </label>

                <label class="block mb-2"><strong>Biaya Pokok : </strong>
                <?= "Rp " . number_format($modal_ops->total_finance_out, 0, ',', '.'); ?>
                </label>

                <label class="block mb-2"><strong>Gross Profit : </strong>
                <?php $profit = $project->value - $modal_ops->total_finance_out ?>
                <?= "Rp " . number_format($profit, 0, ',', '.'); ?></label>

                <?php $presentase = ($profit / $project->value) * '100' ?>
                <label class="block mb-2"><strong>Presentase Profit : </strong>
                <?= round($presentase) ?> %
                </label>

                <label class="block mb-2"><strong>Detail : </strong><?= $project->detail ?></label>

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
                  <label class="block mb-2"><strong><?= $label ?> : </strong><?= $crew->crew_name ?></label>
                  <?php 
                  endif;
                  endforeach; 
                endif; 
                ?>

   


                <!-- <div class="flex flex-wrap gap-2 mt-4">
                  <a href="<?= site_url('finance-project/edit/' . $project->id_session) ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-center">Tambah Transaksi</a>
                  <a href="<?= site_url('finance-project/edit2/' . $project->id_session) ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-center">Tambah Crew</a>
                  <a href="javascript:history.back()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-center">Kembali</a>
                </div> -->
              </form>

              <!-- ====== Table Three Start -->
              <div class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default  dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1" >
                <div class="max-w-full overflow-x-auto">
                  <table class="w-full table-auto">
                    <thead>
                      <tr class="bg-gray-2 text-left dark:bg-meta-4">
                        <th class="px-4 py-4 font-medium">
                          No
                        </th>
                        <th class="px-4 py-4 font-medium">
                          Date
                        </th>
                        <th class="px-4 py-4 font-medium">
                          Nama Transaksi
                        </th>
                        <th class="px-4 py-4 font-medium">
                          Nominal
                        </th>
                        <th class="px-4 py-4 font-medium">
                           
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1; foreach ($financeacc as $p): ?>
                      <tr>                        
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                          <p><?=$no++?></p>
                        </td>                        
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                          <p class="inline-flex rounded-full bg-success bg-opacity-10 px-3 py-1 text-sm font-medium text-success">
                            <?= tgl_indo($p->tanggal_transaksi) ?>
                          </p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p><?= $p->nama_transaksi ?></p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                         <p style="text-align: right;">Rp <?= number_format($p->nominal_transaksi, 0, ',', '.'); ?></p>
                        </td>

                        <?php 
                          $nilai1 = $p->nominal_transaksi;
                          $nilai2 = $p->nominal_transaksi;
                          $total = $nilai1++; 
                          ?>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                          <div class="flex flex-col gap-2">
                          <?php if (stripos($p->nama_transaksi, 'crew') !== false): ?>
                            <a href="<?= site_url('finance-project/edit2/' . $p->project_id_session . '/' . $p->id_session) ?>" 
                             class="inline-flex justify-center bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-center">
                            Edit Crew
                            </a>
                          <?php else: ?>
                            <a href="<?= site_url('finance-project/edit/' . $p->project_id_session . '/' . $p->id_session) ?>" 
                             class="inline-flex justify-center bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-center">
                            Edit
                            </a>
                          <?php endif; ?>
                          <a href="<?= site_url('crud_finance_project/delete/' . $p->project_id_session . '/' . $p->id_session) ?>" 
                             class="inline-flex justify-center bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-center" 
                             onclick="return confirm('Yakin ingin menghapus transaksi <?= $p->nama_transaksi ?> ?')">
                            Hapus
                          </a>
                          </div>
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
