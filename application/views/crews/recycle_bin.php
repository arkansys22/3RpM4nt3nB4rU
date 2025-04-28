<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recycle Bin Crew</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'crews', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
>
    <!-- ===== Preloader Start ===== -->
    <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})" class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent"></div>
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
                            <div class="flex items-center justify-between mb-4">
                              <h2 class="text-2xl font-bold">Recycle Bin Crew</h2>
                              <a href="<?= site_url('crews') ?>" class="flex items-center gap-2 bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                </svg>
                              </a>
                            </div>

                            <!-- ====== Data Table Two Start --><br><br>
              <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                        <div class="data-table-common data-table-two max-w-full overflow-x-auto">

                          <table class="table w-full table-auto" id="dataTableTwo">
                            <thead>
                              <tr>
                              <th>
                                  <div class="flex items-center justify-between gap-1.5">
                                    <p>Nama</p>
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
                                    <p>Gender</p>
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
                                    <p>Agama</p>
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
                                    <p>No HP</p>
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
                                    <p>Alamat</p>
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
                                    <p>Tanggal Lahir</p>
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
                                    <p>Usia</p>
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
                                    <p>Bergabung</p>
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
                                    <?php $no = 1; foreach ($crews as $c) : ?>
                                    <tr>
                                        <td><?= $c->crew_name ?></td>
                                        <td><?= $c->gender ?></td>
                                        <td><?= $c->religion ?></td>
                                        <td><?= $c->phone ?></td>
                                        <td><?= $c->address ?></td>
                                        <td><?= tgl_indo($c->birth_date) ?></td>
                                        <td><?= $c->age ?> Tahun</td>
                                        <td><?= tgl_indo($c->joining_date) ?></td>
                                        <td>
                                        <div class="flex flex-col items-start gap-2 w-max">
                                            <a href="<?= site_url('crews/restore/'.$c->id_session) ?>" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-center w-full">Restore</a>
                                            <a href="<?= site_url('crews/delete_permanent/'.$c->id_session) ?>" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-center w-full whitespace-nowrap overflow-hidden text-ellipsis" onclick="return confirm('Hapus secara permanen?')">Delete Permanent</a>
                                        </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- ====== Data Table Two End -->  
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
