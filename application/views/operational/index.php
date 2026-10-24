<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operational Finance</title>
    <link rel="icon" href="<?= favicon_url() ?>" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
</head>
<body
    x-data="{ page: 'operational', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h1 class="text-2xl font-bold mb-4">Operational Finance</h1>

              <div class="col-span-12 flex flex-wrap items-center justify-between gap-3">

                <div class="relative">
                  <!-- Tombol tambah potensial clients-->
                  <a href="<?= site_url('finance-operational/create') ?>">
                    <button class="bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">                    
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"></path>
                        </svg>                    
                    </button>
                  </a>
                 
                </div>

              </div>


              <?php
              // Sortir bulanan: ?periode=YYYY-MM (default bulan ini), ?periode=semua untuk seluruh transaksi
              $periode = (string) $this->input->get('periode');
              $semua_bulan = ($periode === 'semua');
              if (!$semua_bulan && !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $periode)) {
                  $periode = date('Y-m');
              }
              $total_bulan = 0;
              if (!$semua_bulan) {
                  $ops = array_values(array_filter($ops, function ($o) use ($periode) {
                      return substr((string) $o->tanggal_transaksi, 0, 7) === $periode;
                  }));
              }
              foreach ($ops as $o) { $total_bulan += (float) $o->nominal_transaksi; }
              $nama_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
              $url_op = site_url('finance-operational');
              if (!$semua_bulan) {
                  $prev = date('Y-m', strtotime($periode . '-01 -1 month'));
                  $next = date('Y-m', strtotime($periode . '-01 +1 month'));
                  $label_periode = $nama_bulan[(int) substr($periode, 5, 2)] . ' ' . substr($periode, 0, 4);
              } else {
                  $label_periode = 'Semua Bulan';
              }
              ?>
              <div class="mt-6 flex flex-wrap items-center gap-2">
                <?php if (!$semua_bulan): ?>
                  <a href="<?= $url_op . '?periode=' . $prev ?>" class="rounded-md border border-stroke px-3 py-2 text-sm hover:bg-whiter dark:border-strokedark dark:hover:bg-meta-4">&laquo; Sebelumnya</a>
                <?php endif; ?>
                <span class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-white"><?= $label_periode ?></span>
                <?php if (!$semua_bulan): ?>
                  <a href="<?= $url_op . '?periode=' . $next ?>" class="rounded-md border border-stroke px-3 py-2 text-sm hover:bg-whiter dark:border-strokedark dark:hover:bg-meta-4">Berikutnya &raquo;</a>
                <?php endif; ?>
                <input type="month" value="<?= $semua_bulan ? '' : $periode ?>" onchange="if(this.value){location.href='<?= $url_op ?>?periode='+this.value}" class="rounded-md border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-boxdark">
                <a href="<?= $url_op . '?periode=' . date('Y-m') ?>" class="rounded-md border border-stroke px-3 py-2 text-sm hover:bg-whiter dark:border-strokedark dark:hover:bg-meta-4">Bulan Ini</a>
                <a href="<?= $url_op . '?periode=semua' ?>" class="rounded-md border px-3 py-2 text-sm <?= $semua_bulan ? 'border-primary bg-primary text-white' : 'border-stroke hover:bg-whiter dark:border-strokedark dark:hover:bg-meta-4' ?>">Semua Bulan</a>
              </div>
              <p class="mt-3 text-sm">Total <?= $label_periode ?>: <strong>Rp <?= number_format($total_bulan, 0, ',', '.') ?></strong> (<?= count($ops) ?> transaksi)</p>
              <!-- ====== Data Table Two Start --><br><br>
              <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="data-table-common data-table-two max-w-full overflow-x-auto">
                  <table class="table w-full table-auto" id="operationalTable">
                    <thead>
                      <tr>
                      <th>
                          <div class="flex items-center justify-between gap-1.5">
                            <p>Date</p>
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
                            <p>Kategori</p>
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
                            <p>Nama Transaksi</p>
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
                            <p>Nominal</p>
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
                    <?php $no = 1; foreach ($ops as $p): ?>
                      <tr>
                        <td><?= $p->tanggal_transaksi ?></td>
                        <?php 
                        $kat = $this->Crud_m
                            ->view_where('operational_kategori', array('nomer_kategori' => $p->kategori))
                            ->row(); 
                        ?>

                        <td>
                            <?= !empty($kat) && !empty($kat->nama_kategori) 
                                ? $kat->nama_kategori 
                                : 'Belum di input' ?>
                        </td>
                        
                        <td><?= $p->nama_transaksi ?></td>
                        <td><?= "Rp " . number_format($p->nominal_transaksi, 0, ',', '.'); ?></td>                
                        <td>
                        <div class="flex flex-col items-start gap-2 w-max">
                          <a href="<?= site_url('finance-operational/edit/'. $p->id_session) ?>" class="inline-flex justify-center px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 min-w-full text-center">
                            Edit
                          </a>
                          <a href="<?= site_url('finance-operational/permanent_delete/'.$p->id_session) ?>" class="inline-flex justify-center bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 min-w-full text-center" onclick="return confirm('Yakin ingin menghapus <?= $p->nama_transaksi ?> ?')">Hapus</a>
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
  <script type="text/javascript">
  $(function () {
      $("#operationalTable").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
        // Kolom Date (index 0) diurutkan turun (data terbaru dulu) saat halaman
        // dibuka -- tanpa ini DataTables default-nya urut naik dari kolom
        // pertama, jadi kelihatan ASC walau query PHP-nya sudah DESC.
        "order": [[0, "desc"]],
        // Mode mobile: kolom yang disembunyikan responsive diurutkan dari prioritas terendah;
        // Nama Transaksi (kolom 2) paling akhir disembunyikan supaya selalu langsung terbaca.
        "columnDefs": [
          { "responsivePriority": 1, "targets": 2 },
          { "responsivePriority": 2, "targets": 3 },
          { "responsivePriority": 3, "targets": 4 },
          { "responsivePriority": 4, "targets": 0 },
          { "responsivePriority": 5, "targets": 1 }
        ],

      });
  });
</script>
</body>
</html>
