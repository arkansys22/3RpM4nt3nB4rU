<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Client - <?= date('F', mktime(0, 0, 0, $month, 10)) ?> <?= $year ?></title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'clients', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
            <h1 class="text-2xl font-bold mb-4">Detail Client - <?= date('F', mktime(0, 0, 0, $month, 10)) ?> <?= $year ?></h1>
              <!-- Tombol Kembali -->
                <a href="<?= site_url('client/lebih_lengkap') ?>" class="flex items-center gap-2 bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                  </svg>
                </a>
              </div>

              <!-- ====== Data Table Start ===== -->
              <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="data-table-common data-table-two max-w-full overflow-x-auto">
                  <table class="table w-full table-auto" id="dataTableTwo">
                    <thead>
                      <tr>
                        <th>
                          <div class="flex items-center justify-between gap-1.5">
                            <p>Nama Client</p>
                            <div class="inline-flex flex-col space-y-[2px]">
                              <span class="inline-block">
                                <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5 0L0 5H10L5 0Z" fill="" />
                                </svg>
                              </span>
                              <span class="inline-block">
                                <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z" fill="" />
                                </svg>
                              </span>
                            </div>
                          </div>
                        </th>
                        <th>
                          <div class="flex items-center justify-between gap-1.5">
                            <p>Agama</p>
                            <div class="inline-flex flex-col space-y-[2px]">
                              <span class="inline-block">
                                <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5 0L0 5H10L5 0Z" fill="" />
                                </svg>
                              </span>
                              <span class="inline-block">
                                <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z" fill="" />
                                </svg>
                              </span>
                            </div>
                          </div>
                        </th>
                        <th>
                          <div class="flex items-center justify-between gap-1.5">
                            <p>Tanggal Pernikahan</p>
                            <div class="inline-flex flex-col space-y-[2px]">
                              <span class="inline-block">
                                <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5 0L0 5H10L5 0Z" fill="" />
                                </svg>
                              </span>
                              <span class="inline-block">
                                <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z" fill="" />
                                </svg>
                              </span>
                            </div>
                          </div>
                        </th>
                        <th>
                          <div class="flex items-center justify-between gap-1.5">
                            <p>Lokasi</p>
                            <div class="inline-flex flex-col space-y-[2px]">
                              <span class="inline-block">
                                <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5 0L0 5H10L5 0Z" fill="" />
                                </svg>
                              </span>
                              <span class="inline-block">
                                <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z" fill="" />
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
                                <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5 0L0 5H10L5 0Z" fill="" />
                                </svg>
                              </span>
                              <span class="inline-block">
                                <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z" fill="" />
                                </svg>
                              </span>
                            </div>
                          </div>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($clients)): ?>
                        <?php foreach ($clients as $client): ?>
                          <tr>
                            <td><?= $client->client_name ?></td>
                            <td>
                              <?php 
                                $project = $this->db->get_where('project', ['id_session' => $client->id_session])->row();
                                echo $project ? $project->religion : 'N/A';
                              ?>
                            </td>
                            <td><?= tgl_indo($client->wedding_date) ?></td>
                            <td><?= $client->location ?></td>
                            <td>
                              <a href="<?= site_url('project/lihat/' . $client->id_session) ?>" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600">
                                Lihat
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- ====== Data Table End ===== -->
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
