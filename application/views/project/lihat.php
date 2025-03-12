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
                <label class="block mb-2 text-black dark:text-white"><strong>Value : </strong><?= "Rp " . number_format($project->value, 0, ',', '.'); ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Tanggal Pernikahan : </strong><?= hari($project->event_date) ?>, <?= tgl_indo($project->event_date) ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Lokasi : </strong><?= $project->location ?></label>
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
                  <label class="block mb-2 text-black dark:text-white"><?= $label ?> : <?= $crew->crew_name ?></label>
                  <?php 
                  endif;
                  endforeach; 
                endif; 
                ?>

    <h2 class="text-xl font-bold mb-4">Daftar Pembayaran</h2>

    <?php if (empty($payment)): ?>
        <!-- Jika belum ada pembayaran, tampilkan tombol Add Payment untuk invoice pertama -->
        <div class="border p-4 mb-4 text-black dark:text-white">
            <p class="text-red-500 font-semibold">Belum ada data pembayaran.</p>
            <a href="<?= base_url('Crud_payment/create/' . $project->id_session . '/1') ?>" class="btn btn-primary">Tambah Invoice & Kwitansi 1</a>
        </div>
    <?php else: ?>
        <!-- Loop untuk menampilkan data pembayaran untuk setiap invoice -->
        <?php for ($i = 1; $i <= 7; $i++): ?>
            <?php 
                $invoice = "invoice_{$i}"; 
                $kwitansi = "kwitansi_{$i}"; 
                $amount = "amount_{$i}";
                $dp = "dp_{$i}";
                $date = "date_{$i}";
                $details = "details_{$i}";
                $due_date = "due_date_{$i}";
            ?>

            <?php if (!empty($payment->$invoice) || ($i == 1) || !empty($payment->{"invoice_" . ($i - 1)})): ?>
                <div class="border p-4 mb-4 text-black dark:text-white">
                    <h3 class="font-bold">Invoice & Kwitansi <?= $i ?></h3>

                    <?php if (!empty($payment->$invoice)): ?>
                        <!-- Menampilkan data jika invoice sudah ada -->
                        <p><strong>Invoice:</strong> <?= htmlspecialchars($payment->$invoice) ?></p>
                        <p><strong>Kwitansi:</strong> <?= htmlspecialchars($payment->$kwitansi) ?></p>
                        <p><strong>Jumlah:</strong> <?= "Rp " . number_format($payment->$amount, 0, ',', '.') ?></p>
                        <p><strong>DP:</strong> <?= "Rp " . number_format($payment->$dp, 0, ',', '.') ?></p>
                        <p><strong>Tanggal:</strong> <?= date('d-m-Y', strtotime($payment->$date)) ?></p>
                        <p><strong>Jatuh Tempo:</strong> <?= date('d-m-Y', strtotime($payment->$due_date)) ?></p>
                        <a href="<?= base_url('payment/view_invoice/' . $payment->id_session . '/' . $i) ?>" class="btn btn-success">Lihat Invoice</a>
                        <a href="<?= base_url('payment/view_kwitansi/' . $payment->id_session . '/' . $i) ?>" class="btn btn-success">Lihat Kwitansi</a>
                        <a href="<?= base_url('Crud_payment/edit/' . $payment->id_session . '/' . $i) ?>" class="btn btn-warning">Edit</a>
                        <!-- Tombol Hapus untuk setiap invoice -->
                        <a href="<?= base_url('Crud_payment/delete/' . $payment->id_session . '/' . $i) ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus invoice ini?')">Hapus</a>
                    <?php else: ?>
                        <!-- Menampilkan tombol Add Payment jika invoice kosong -->
                        <a href="<?= base_url('Crud_payment/create/' . $payment->id_session . '/' . $i) ?>" class="btn btn-primary">Tambah Invoice & Kwitansi <?= $i ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endfor; ?>
    <?php endif; ?>

    <h2 class="text-lg font-bold mb-2">Vendor</h2>

<div class="border p-4 mb-4 text-black dark:text-white">
    <?php if (empty($vendors)): ?>
        <p class='text-red-500 font-semibold'>Belum ada vendor.</p>
        <a href="<?= site_url('crud_vendor/create/' . $project->id_session) ?>" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 inline-block text-center w-auto">Add Vendor</a>
    <?php else: ?>
        <?php foreach ($vendors as $vendor): ?>
            <div class="mb-4">
                <p class='text-white-700 font-medium'><strong>Type:</strong> <?= $vendor->type ?></p>
                <p class='text-white-700 font-medium'><strong>Social Media:</strong> <?= $vendor->social_media ?></p>
                <p class='text-white-700 font-medium'><strong>Contact Name:</strong> <?= $vendor->contact_name ?></p>
                <p class='text-white-700 font-medium'><strong>Phone:</strong> <?= $vendor->phone ?></p>
                <p class='text-white-700 font-medium'><strong>Detail:</strong> <?= $vendor->detail ?></p>
                <p class='text-white-700 font-medium'><strong>Photo 1:</strong> <img src="<?= base_url('uploads/' . $vendor->photo1) ?>" alt="Photo 1" /></p>
                <p class='text-white-700 font-medium'><strong>Photo 2:</strong> <img src="<?= base_url('uploads/' . $vendor->photo2) ?>" alt="Photo 2" /></p>
                <p class='text-white-700 font-medium'><strong>Photo 3:</strong> <img src="<?= base_url('uploads/' . $vendor->photo3) ?>" alt="Photo 3" /></p>
                <p class='text-white-700 font-medium'><strong>Photo 4:</strong> <img src="<?= base_url('uploads/' . $vendor->photo4) ?>" alt="Photo 4" /></p>
                <p class='text-white-700 font-medium'><strong>Photo 5:</strong> <img src="<?= base_url('uploads/' . $vendor->photo5) ?>" alt="Photo 5" /></p>
                <a href="<?= site_url('crud_vendor/edit/' . $vendor->id) ?>" class="ml-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 inline-block text-center w-auto">Edit Vendor</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

                <a href="<?= site_url('project/edit/'. $project->id_session) ?>" class="ml-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 inline-block text-center w-auto">Edit</a>
                <a href="javascript:history.back()" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 inline-block text-center w-auto">Kembali</a>
              </form>

              <!-- ====== Table Three Start -->
              <div class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default  dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1" >
                <div class="max-w-full overflow-x-auto">
                  <table class="w-full table-auto">
                    <thead>
                      <tr class="bg-gray-2 text-left dark:bg-meta-4">
                        <th
                          class="min-w-[220px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11"
                        >
                          Author
                        </th>
                        <th
                          class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white"
                        >
                          Status
                        </th>
                        <th
                          class="min-w-[120px] px-4 py-4 font-medium text-black dark:text-white"
                        >
                          Time
                        </th>
                        <th class="px-4 py-4 font-medium text-black dark:text-white">
                          Device
                        </th>
                        <th class="px-4 py-4 font-medium text-black dark:text-white">
                          IP
                        </th>
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
                          <p class="inline-flex rounded-full bg-success bg-opacity-10 px-3 py-1 text-sm font-medium text-success">
                            <?= $p->log_activity_status ?>
                          </p>
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
