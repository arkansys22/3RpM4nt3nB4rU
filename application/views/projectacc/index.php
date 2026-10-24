<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Project</title>
    <link rel="icon" href="<?= favicon_url() ?>" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'project-acc', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
  >

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
              <h1 class="text-2xl font-bold mb-4">Finance Project</h1>

              <?php
              // Kategori berdasarkan tanggal acara: akan datang (hari ini termasuk) vs sudah lewat
              $hari_ini = date('Y-m-d');
              $jumlah_lewat = 0;
              foreach ($project as $p) {
                  if ($p->event_date < $hari_ini) { $jumlah_lewat++; }
              }
              $jumlah_semua = count($project);
              $jumlah_mendatang = $jumlah_semua - $jumlah_lewat;

              $kategori = $this->input->get('kategori');
              // Default: Akan Datang
              if (!in_array($kategori, ['akan_datang', 'sudah_lewat', 'semua'], true)) {
                  $kategori = 'akan_datang';
              }
              if ($kategori !== 'semua') {
                  $project = array_values(array_filter($project, function ($p) use ($kategori, $hari_ini) {
                      return $kategori === 'sudah_lewat' ? $p->event_date < $hari_ini : $p->event_date >= $hari_ini;
                  }));
              }
              // Akan Datang: tanggal pernikahan terdekat di paling atas
              if ($kategori === 'akan_datang') {
                  usort($project, function ($a, $b) {
                      return strcmp($a->event_date, $b->event_date);
                  });
              }
              $tab_kategori = [
                  'akan_datang' => ['Akan Datang', $jumlah_mendatang],
                  'sudah_lewat' => ['Sudah Lewat', $jumlah_lewat],
                  'semua'       => ['Semua Project', $jumlah_semua],
              ];
              ?>
              <div class="flex flex-wrap gap-2 mb-4">
                <?php foreach ($tab_kategori as $kunci => $tab): ?>
                  <a href="<?= site_url('finance-project') . '?kategori=' . $kunci ?>"
                     class="inline-flex items-center gap-2 rounded-md border px-4 py-2 text-sm font-medium <?= $kategori === $kunci ? 'border-primary bg-primary text-white' : 'border-stroke hover:bg-whiter dark:border-strokedark dark:hover:bg-meta-4' ?>">
                    <?= $tab[0] ?>
                    <span class="rounded-full px-2 py-0.5 text-xs <?= $kategori === $kunci ? 'bg-white bg-opacity-20' : 'bg-gray-200 text-gray-600' ?>"><?= $tab[1] ?></span>
                  </a>
                <?php endforeach; ?>
              </div>
              <!-- ====== Data Table Two Start --><br>
              <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="data-table-common data-table-two max-w-full overflow-x-auto">
                  <table class="table w-full table-auto" id="dataTableTwo">
                    <thead>
                      <tr>
                      <th>
                          <div class="flex items-center justify-between gap-1.5">
                            <p>Project</p>
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
                            <p>Tanggal</p>
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
                            <p>Lokasi</p>
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
                            <p>Nilai Project</p>
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
                            <p>Net Profit (%)</p>
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
                  <?php foreach ($project as $p): ?>
                  <tr>
                    <td><?= $p->project_name ?></td>
                    <td><?= tgl_indo($p->event_date) ?></td>
                    <td><?= $p->location ?></td>
                    <td><?= "Rp " . number_format($p->value, 0, ',', '.'); ?></td>


                    <?php 
                      $data['terbayar_ops'] = $this->finance_project_model->get_finance_dibayarklien($p->id_session);
                      $data['modal_ops'] = $this->finance_project_model->get_finance_out($p->id_session);

                      $total_dibayar = $data['terbayar_ops']->total_dibayarkan ?? 0;
                      $total_modal   = $data['modal_ops']->total_finance_out ?? 0;

                      $profit = $total_dibayar - $total_modal;

                      // hindari division by zero
                      $persentase = ($total_dibayar > 0) 
                          ? ($profit / $total_dibayar) * 100 
                          : 0;

                      // tentukan warna
                      $color = $profit > 0 ? 'text-green-600' : ($profit < 0 ? 'text-red-600' : 'text-gray-500');

                      // progress bar (max 100%)
                      $progress = min(abs($persentase), 100);
                    ?>

                    <td>
                        <?php if($total_dibayar > 0){ ?>
                            
                            <div class="flex flex-col gap-1">
                                <!-- Nominal -->
                                <span class="font-semibold <?= $color ?>">
                                    <?= "Rp " . number_format($profit, 0, ',', '.'); ?>
                                </span>

                                <!-- Persentase -->
                                <span class="text-xs <?= $color ?>">
                                    <?= round($persentase) ?> %
                                </span>

                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div 
                                        class="h-2 rounded-full <?= $profit > 0 ? 'bg-green-500' : ($profit < 0 ? 'bg-red-500' : 'bg-gray-400') ?>"
                                        style="width: <?= $progress ?>%">
                                    </div>
                                </div>
                            </div>

                        <?php } else { ?>
                            <span class="text-gray-400 italic">Belum Ada</span>
                        <?php } ?>
                    </td>

                    
                    <td><?= $p->religion ?></td>                    
                    <td>
                    <div class="flex flex-col items-start gap-2 w-max">
                      <a href="<?= site_url('finance-project/lihat/'.$p->id_session) ?>" class="inline-flex justify-center bg-yellow-500 text-white px-2 py-1 rounded-md hover:bg-yellow-600 text-center w-full">Lihat</a>
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
