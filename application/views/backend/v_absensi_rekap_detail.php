<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi - <?= $absensi_user->nama ?> - <?= date('F Y', strtotime($periode . '-01')) ?></title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'dashboard', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
                <h1 class="text-2xl font-bold">Rekap Absensi - <?= $absensi_user->nama ?> - <?= date('F Y', strtotime($periode . '-01')) ?></h1>
                <!-- Tombol Kembali -->
                <a href="<?= site_url('absensi-rekap/' . $periode) ?>" class="flex items-center gap-2 bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                  </svg>
                </a>
                </div>

                <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">
                  Total kehadiran bulan <?= date('F Y', strtotime($periode . '-01')) ?>: <span class="font-semibold"><?= count($riwayat_absensi) ?> hari</span>
                </p>

                <div class="overflow-x-auto bg-white dark:bg-neutral-700 rounded-lg shadow-md">
                  <table class="min-w-full text-left text-sm whitespace-nowrap bg-white dark:bg-neutral-800">
                    <thead class="uppercase tracking-wider border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
                      <tr>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Tanggal</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Hari</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Status</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Jam Masuk</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Foto/Lokasi Masuk</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Jam Keluar</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Foto/Lokasi Keluar</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($riwayat_absensi)): ?>
                        <tr class="border-b border-gray-200 dark:border-neutral-600">
                          <td colspan="8" class="px-6 py-4 text-gray-700 dark:text-gray-400 text-center">
                            Belum ada riwayat absensi pada bulan ini.
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($riwayat_absensi as $r): ?>
                          <tr class="border-b border-gray-200 dark:border-neutral-600">
                            <th scope="row" class="px-6 py-4 text-gray-900 dark:text-gray-200">
                              <?= date('d-m-Y', strtotime($r->tanggal)) ?>
                            </th>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <?= hari($r->tanggal) ?>
                            </td>
                            <td class="px-6 py-4 <?= $r->status === 'Hadir' ? 'text-gray-700 dark:text-gray-400' : 'text-yellow-600 dark:text-yellow-400 font-semibold' ?>">
                              <?= $r->status ?>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <?= !empty($r->jam_masuk) ? date('H:i', strtotime($r->jam_masuk)) : '-' ?>
                              <?php if (!empty($r->jam_masuk_ketentuan)): ?>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block">ketentuan <?= substr($r->jam_masuk_ketentuan, 0, 5) ?></span>
                              <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <?php if (!empty($r->foto_masuk)): ?>
                                <a href="<?= base_url('uploads/absensi/' . $r->foto_masuk) ?>" target="_blank">
                                  <img src="<?= base_url('uploads/absensi/' . $r->foto_masuk) ?>" alt="Foto masuk" class="w-10 h-10 object-cover rounded-md inline-block">
                                </a>
                                <?php if (!empty($r->lat_masuk) && !empty($r->lng_masuk)): ?>
                                  <a href="https://www.google.com/maps?q=<?= $r->lat_masuk ?>,<?= $r->lng_masuk ?>" target="_blank" class="text-blue-500 hover:underline text-xs block">Lihat Lokasi</a>
                                <?php endif; ?>
                                <?php if (!empty($r->alamat_masuk)): ?>
                                  <span class="text-xs text-gray-500 dark:text-gray-400 block"><?= $r->alamat_masuk ?></span>
                                <?php endif; ?>
                              <?php else: ?>
                                -
                              <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <?= !empty($r->jam_keluar) ? date('H:i', strtotime($r->jam_keluar)) : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <?php if (!empty($r->foto_keluar)): ?>
                                <a href="<?= base_url('uploads/absensi/' . $r->foto_keluar) ?>" target="_blank">
                                  <img src="<?= base_url('uploads/absensi/' . $r->foto_keluar) ?>" alt="Foto keluar" class="w-10 h-10 object-cover rounded-md inline-block">
                                </a>
                                <?php if (!empty($r->lat_keluar) && !empty($r->lng_keluar)): ?>
                                  <a href="https://www.google.com/maps?q=<?= $r->lat_keluar ?>,<?= $r->lng_keluar ?>" target="_blank" class="text-blue-500 hover:underline text-xs block">Lihat Lokasi</a>
                                <?php endif; ?>
                                <?php if (!empty($r->alamat_keluar)): ?>
                                  <span class="text-xs text-gray-500 dark:text-gray-400 block"><?= $r->alamat_keluar ?></span>
                                <?php endif; ?>
                              <?php else: ?>
                                -
                              <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 <?= !empty($r->keterangan) ? 'text-red-500 font-semibold' : 'text-gray-700 dark:text-gray-400' ?>">
                              <?= !empty($r->keterangan) ? $r->keterangan : '-' ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
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
