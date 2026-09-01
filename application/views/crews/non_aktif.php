<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crew Non Aktif</title>
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
              <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
                <h1 class="text-2xl font-bold">Crew Non Aktif</h1>
                <a href="<?= site_url('crews') ?>"
                  class="w-full md:w-auto text-center px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">
                  &larr; Kembali ke Daftar Crew
                </a>
              </div>

              <p class="text-sm text-body dark:text-bodydark mb-4">
                Crew di bawah ini statusnya "Non Aktif" (bukan dihapus) -- jadi tidak muncul di Daftar Crew utama.
                Untuk mengaktifkan lagi, buka Lihat &rarr; Edit lalu ubah Status Keaktifan-nya ke "Aktif".
              </p>

              <!-- ====== Data Table Two Start -->
              <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="data-table-common data-table-two max-w-full overflow-x-auto">
                  <table class="table w-full table-auto" id="dataTableTwo">
                    <thead>
                      <tr>
                        <th><p>Nama</p></th>
                        <th><p>Gender</p></th>
                        <th><p>Agama</p></th>
                        <th><p>No HP</p></th>
                        <th><p>Bergabung</p></th>
                        <th><p>Aksi</p></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($crews)): ?>
                      <tr>
                        <td colspan="6" class="text-center py-6 text-sm text-body dark:text-bodydark">Tidak ada crew non aktif.</td>
                      </tr>
                    <?php else: foreach ($crews as $c) : ?>
                      <tr>
                        <td><?= $c->crew_name ?></td>
                        <td><?= $c->gender === 'Male' ? 'Laki-laki' : ($c->gender === 'Female' ? 'Perempuan' : $c->gender) ?></td>
                        <td><?= $c->religion ?></td>
                        <td><a href="https://wa.me/<?= $c->phone?>"><?= $c->phone ?></a></td>
                        <td><?= tgl_indo($c->joining_date) ?></td>
                        <td>
                            <a href="<?= site_url('crews/lihat/'.$c->id_session) ?>" class="inline-flex justify-center bg-yellow-500 text-white px-2 py-1 rounded-md hover:bg-yellow-600 text-center w-full">Lihat</a>
                        </td>
                      </tr>
                      <?php endforeach; endif; ?>
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
