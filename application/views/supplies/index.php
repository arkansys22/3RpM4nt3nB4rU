<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Stock</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'supplies', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h1 class="text-2xl font-bold mb-4">Daftar Stocks</h1>
              <div class="flex justify-between mb-4">
              <div class="flex space-x-2">
                <a href="<?= site_url('supplies/create') ?>">
                  <button class="bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"></path>
                      </svg>
                  </button>
                </a>
                <a href="<?= site_url('supplies/restock') ?>">
                  <button class="bg-orange-500 text-white p-3 rounded-md hover:bg-orange-700 focus:outline-none">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                  </svg>
                  </button>
                </a>
              </div>
              <a href="<?= site_url('supplies/recycle_bin') ?>">
                <button class="bg-red-500 text-white p-3 rounded-md hover:bg-red-700 focus:outline-none">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-2 14H7L5 7M12 4v-2m4 2h-8m5 2l1-1m3 1l-1-1m0 0h6l-1 2m-7-5h2m6 5H5"></path>
                  </svg>
                </button>
              </a>
              </div>
              <!-- ====== Data Table Two Start --><br>
              <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="data-table-common data-table-two max-w-full overflow-x-auto">
                  <table class="table w-full table-auto" id="dataTableTwo">
                    <thead>
                      <tr>
                      <th>
                          <div class="flex items-center justify-between gap-1.5">
                            <p>Nama Stock</p>
                            <div class="inline-flex flex-col space-y-[2px]">
                              <span class="inline-block">
                                <svg
                                  class="fill-current"
                                  width="10"
                                  height="5"
                                  viewBox="0 0 10 5"
                                  fill="none"
                                  xmlns="http://www.w3.org/2000/svg"
                                >
                                  <path d="M5 0L0 5H10L5 0Z" fill="" />
                                </svg>
                              </span>
                              <span class="inline-block">
                                <svg
                                  class="fill-current"
                                  width="10"
                                  height="5"
                                  viewBox="0 0 10 5"
                                  fill="none"
                                  xmlns="http://www.w3.org/2000/svg"
                                >
                                  <path
                                    d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z"
                                    fill=""
                                  />
                                </svg>
                              </span>
                            </div>
                          </div>
                        </th>
                        <th>
                          <div class="flex items-center justify-between gap-1.5">
                            <p>Jenis</p>
                            <div class="inline-flex flex-col space-y-[2px]">
                              <span class="inline-block">
                                <svg
                                  class="fill-current"
                                  width="10"
                                  height="5"
                                  viewBox="0 0 10 5"
                                  fill="none"
                                  xmlns="http://www.w3.org/2000/svg"
                                >
                                  <path d="M5 0L0 5H10L5 0Z" fill="" />
                                </svg>
                              </span>
                              <span class="inline-block">
                                <svg
                                  class="fill-current"
                                  width="10"
                                  height="5"
                                  viewBox="0 0 10 5"
                                  fill="none"
                                  xmlns="http://www.w3.org/2000/svg"
                                >
                                  <path
                                    d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z"
                                    fill=""
                                  />
                                </svg>
                              </span>
                            </div>
                          </div>
                        </th>
                        <th>
                          <div class="flex items-center justify-between gap-1.5">
                            <p>Stock Tersedia</p>
                            <div class="inline-flex flex-col space-y-[2px]">
                              <span class="inline-block">
                                <svg
                                  class="fill-current"
                                  width="10"
                                  height="5"
                                  viewBox="0 0 10 5"
                                  fill="none"
                                  xmlns="http://www.w3.org/2000/svg"
                                >
                                  <path d="M5 0L0 5H10L5 0Z" fill="" />
                                </svg>
                              </span>
                              <span class="inline-block">
                                <svg
                                  class="fill-current"
                                  width="10"
                                  height="5"
                                  viewBox="0 0 10 5"
                                  fill="none"
                                  xmlns="http://www.w3.org/2000/svg"
                                >
                                  <path
                                    d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z"
                                    fill=""
                                  />
                                </svg>
                              </span>
                            </div>
                          </div>
                        </th>
                        <th>
                          <div class="flex items-center justify-between gap-1.5">
                            <p>Aksi</p>
                            <div class="inline-flex flex-col space-y-[2px]">
                              <span class="inline-block">
                                <svg
                                  class="fill-current"
                                  width="10"
                                  height="5"
                                  viewBox="0 0 10 5"
                                  fill="none"
                                  xmlns="http://www.w3.org/2000/svg"
                                >
                                  <path d="M5 0L0 5H10L5 0Z" fill="" />
                                </svg>
                              </span>
                              <span class="inline-block">
                                <svg
                                  class="fill-current"
                                  width="10"
                                  height="5"
                                  viewBox="0 0 10 5"
                                  fill="none"
                                  xmlns="http://www.w3.org/2000/svg"
                                >
                                  <path
                                    d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z"
                                    fill=""
                                  />
                                </svg>
                              </span>
                            </div>
                          </div>
                        </th>
                      </tr>
                    </thead>
                <tbody>
                  <?php foreach ($supplies as $p): ?>
                  <tr>
                    <td><?= $p->product_name ?></td>
                    <td><?= $p->type ?></td>
                    <td><?= $p->amount ?></td>
                    <td>
                    <div class="flex flex-col items-start gap-2 w-max">
                        <a href="<?= site_url('supplies/editin/'.$p->latest_stock_id) ?>" 
                            class="inline-flex justify-center bg-yellow-500 text-white px-2 py-1 rounded-md hover:bg-yellow-600 text-center w-full">
                            Barang Masuk
                        </a>
                        <a href="<?= site_url('supplies/editout/'.$p->latest_stock_id) ?>" 
                            class="inline-flex justify-center bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 text-center w-full">
                            Barang Keluar
                        </a>
                        <a href="<?= site_url('supplies/lihat/'.$p->id_session) ?>" 
                            class="inline-flex justify-center bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600 text-center w-full">
                            Lihat
                        </a>
                    </div>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- ====== Data Table Two End -->
          <!-- ====== Table Three Start -->
          <div class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
            <div class="max-w-full overflow-x-auto">
              <div class="max-h-[400px] overflow-y-auto"> <!-- Tambahkan batas tinggi & scroll -->
                <table class="w-full table-auto">
                  <thead>
                    <tr class="bg-gray-2 text-left dark:bg-meta-4">
                      <th class="min-w-[220px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11">Author</th>
                      <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Status</th>
                      <th class="min-w-[120px] px-4 py-4 font-medium text-black dark:text-white">Time</th>
                      <th class="px-4 py-4 font-medium text-black dark:text-white">Device</th>
                      <th class="px-4 py-4 font-medium text-black dark:text-white">IP</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1; foreach ($logactivity as $p): ?>
                    <tr>
                      <?php $company= $this->Crud_m->view_where('user', array('id_session'=> $p->log_activity_user_id))->row(); ?>
                      <?php $level= $this->Crud_m->view_where('user_level', array('user_level_id'=> $company->level))->row(); ?>
               
                      <td class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                        <h5 class="font-medium text-black dark:text-white"><?= $company->username ?></h5>
                        <p class="text-sm"><?= $level->user_level_nama ?></p>
                        </td>                        
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <?php if (strpos($p->log_activity_status, 'Barang Keluar') !== false): ?>
                          <p class="inline-flex rounded-full bg-danger bg-opacity-10 px-3 py-1 text-sm font-medium text-danger">
                          <?= $p->log_activity_status?> 
                          </p>
                        <?php elseif (strpos($p->log_activity_status, 'Delete') !== false): ?>
                          <p class="inline-flex rounded-full bg-gray-500 bg-opacity-10 px-3 py-1 text-sm font-medium text-gray-500">
                          <?= $p->log_activity_status ?> 
                          </p>
                        <?php else: ?>
                          <p class="inline-flex rounded-full bg-success bg-opacity-10 px-3 py-1 text-sm font-medium text-success">
                          <?= $p->log_activity_status?> 
                          </p>
                        <?php endif; ?>
                        </td>
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p class="text-black dark:text-white"><?= hari($p->log_activity_waktu) ?>, <?= tgl_indo($p->log_activity_waktu)?></p>
                      </td>
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p class="text-black dark:text-white"><?= $p->log_activity_platform ?></p>
                      </td>
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p class="text-black dark:text-white"><?= $p->log_activity_ip ?></p>
                      </td>
                    </tr>
                    <?php endforeach; ?>                            
                  </tbody>
                </table>
              </div>
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
