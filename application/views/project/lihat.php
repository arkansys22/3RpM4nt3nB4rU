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
                <label class="block mb-2"><strong>Nama Project : </strong><?= $project->project_name ?></label>        
                <label class="block mb-2"><strong>Agama : </strong><?= $project->religion ?></label>        
                <label class="block mb-2"><strong>Nilai Project : </strong><?= "Rp " . number_format($project->value, 0, ',', '.'); ?></label>
                <label class="block mb-2"><strong>Telah Dibayar : </strong><?= "Rp " . number_format($paid->total_paid, 0, ',', '.'); ?></label>

                <?php $unpaid = $project->value - $paid->total_paid ?>
                <label class="block mb-2"><strong>Kurang Bayar : </strong><?= "Rp " . number_format($unpaid, 0, ',', '.'); ?></label>

                <label class="block mb-2"><strong>Tanggal Pernikahan : </strong><?= hari($project->event_date) ?>, <?= tgl_indo($project->event_date) ?></label>
                <label class="block mb-2"><strong>Lokasi : </strong><?= $project->location ?></label>
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

                <h2 class="text-lg font-bold mb-2">List Crew</h2>

                <div class="border p-4 mb-4">
                    <?php if (!empty($crew_list)): ?>
                        <?php foreach ($crew_list as $crew): ?>
                            <div class="mb-2 flex items-center justify-between border-b pb-2">
                                <div>
                                    <p class='font-medium'>
                                        <strong><?= htmlspecialchars($crew->role) ?>:</strong> 
                                        <?= htmlspecialchars($crew->crew_name) ?>
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <!-- Edit Button -->
                                    <a href="<?= site_url('crewproject/editlist/' . $crew->id_session . '/' . $crew->crew_id) ?>" 
                                       class="bg-green-500 text-sm px-2 py-1 rounded-md hover:bg-green-600">
                                       Edit
                                    </a>

                                    <!-- Delete Button -->
                                    <a href="<?= site_url('crewproject/delete/' . $crew->id_session) ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus crew ini?')"
                                       class="bg-red-500 text-sm px-2 py-1 rounded-md hover:bg-red-600">
                                       Hapus
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class='text-red-500 font-semibold'>Belum ada crew. Silakan tambahkan crew.</p>
                    <?php endif; ?>

                    <!-- Add Crew Button -->
                    <a href="<?= site_url('crewproject/createlist/' . $project->id_session) ?>" 
                       class="mt-2 bg-blue-500 px-4 py-2 rounded-md hover:bg-blue-700 inline-block text-center w-auto">
                       Tambah Crew
                    </a>
                </div>

    <h2 class="text-lg font-bold mb-2">Vendor</h2>

    <div class="border p-4 mb-4">
    <?php
    // Tentukan urutan yang diinginkan
    $type_order = [
        'Venue', 'MC Akad', 'MC Resepsi', 'Wedding Organizer', 'MUA',
        'Perlengkapan Catering', 'Catering', 'Dokumentasi',
        'Dekorasi', 'Entertaiment'
    ];

    // Urutkan vendor berdasarkan tipe
    usort($vendors, function ($a, $b) use ($type_order) {
        $posA = array_search($a->type, $type_order);
        $posB = array_search($b->type, $type_order);

        // Jika tidak ditemukan dalam array, tempatkan di akhir
        $posA = ($posA === false) ? count($type_order) : $posA;
        $posB = ($posB === false) ? count($type_order) : $posB;

        return $posA - $posB;
    });
    ?>

    <?php if (!empty($vendors)): ?>
        <?php foreach ($vendors as $vendor): ?>
            <div class="mb-2 flex items-center justify-between border-b pb-2">
                <div>
                    <p class='font-medium'>
                        <strong><?= htmlspecialchars($vendor->type) ?>:</strong> 
                        <?= htmlspecialchars($vendor->vendor) ?>
                    </p>
                </div>
                <div class="flex gap-2">
                    <!-- Tombol Edit -->
                    <a href="<?= site_url('crud_vendor/edit/' . $vendor->id_session . '/' . $vendor->vendor_id) ?>" 
                       class="bg-green-500 text-sm px-2 py-1 rounded-md hover:bg-green-600">
                       Edit
                    </a>

                    <!-- Tombol Hapus -->
                    <a href="<?= site_url('crud_vendor/delete/' . $vendor->id_session . '/' . $vendor->vendor_id) ?>" 
                       onclick="return confirm('Apakah Anda yakin ingin menghapus vendor ini?')"
                       class="bg-red-500 text-sm px-2 py-1 rounded-md hover:bg-red-600">
                       Hapus
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class='text-red-500 font-semibold'>Belum ada vendor.</p>
    <?php endif; ?>

    <!-- Tombol Tambah Vendor -->
    <a href="<?= site_url('crud_vendor/create/' . $project->id_session) ?>" 
       class="mt-2 bg-blue-500 px-4 py-2 rounded-md hover:bg-blue-700 inline-block text-center w-auto">
       Tambah Vendor
    </a>
</div>

<h2 class="text-lg font-bold mb-2">Daftar Pembayaran</h2>

<div class="border p-4 mb-4">
    <?php if (empty($payment)): ?>
        <p class="text-red-500 font-semibold">Belum ada transaksi.</p>
        <a href="<?= site_url('payment/createinv/' . $project->id_session) ?>" 
           class="mt-2 bg-blue-500 px-4 py-2 rounded-md hover:bg-blue-700 inline-block text-center w-auto">
           Tambah Invoice
        </a>
    <?php else: ?>
        <?php foreach ($payment as $trans): ?>
            <div class="mb-4">
                <h3 class="text-md font-semibold">
                  Transaksi <?= $project->client_name ?> 
                  <span style="color: <?= $trans->status === 'Pending' ? 'red' : ($trans->status === 'Paid' ? 'green' : 'black') ?>;">
                  <?= $trans->status ?>
                  </span>
                </h3>
                <div class="flex items-center justify-between border-b pb-2">
                    <div>
                        <p class="font-medium">
                            <strong>Transaksi ID:</strong> <?= htmlspecialchars($trans->transactions_id) ?><br>
                            <strong>Total Tagihan:</strong> 
                            <?php if (strpos($trans->transactions_id, 'IMB') === 0): ?>
                                Rp <?= number_format($trans->total_bill, 0, ',', '.') ?><br>
                            <?php elseif (strpos($trans->transactions_id, 'MBP') === 0 || strpos($trans->transactions_id, 'MBP1') === 0): ?>
                                Rp <?= number_format($trans->total_paid, 0, ',', '.') ?><br>
                            <?php endif; ?>
                            <?php if (strpos($trans->transactions_id, 'IMB') === 0): ?>
                                <strong>DP:</strong> Rp <?= number_format($trans->DP, 0, ',', '.') ?><br>
                            <?php endif; ?>
                            <strong>Tanggal:</strong> <?= tgl_indo($trans->date) ?><br>
                            <?php if (!empty($trans->due_date)): ?>
                                <strong>Jatuh Tempo:</strong> <?= tgl_indo($trans->due_date) ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <?php if (strpos($trans->transactions_id, 'IMB') === 0): ?>
                            <a href="<?= site_url('payment/view_invoice/' . $project->id_session . '/' . $trans->transactions_id) ?>" 
                               class="bg-blue-500 text-sm px-2 py-1 rounded-md hover:bg-blue-600">
                               Lihat Invoice
                            </a>
                        <?php elseif (strpos($trans->transactions_id, 'MBP') === 0 || strpos($trans->transactions_id, 'MBP1') === 0): ?>
                            <a href="<?= site_url('payment/view_kwitansi/' . $project->id_session . '/' . $trans->transactions_id) ?>" 
                               class="bg-blue-500 text-sm px-2 py-1 rounded-md hover:bg-blue-600">
                               Lihat Kwitansi
                            </a>
                        <?php endif; ?>
                        <a href="<?= site_url('payment/' . (strpos($trans->transactions_id, 'IMB') === 0 ? 'edit' : 'edit2') . '/' . $project->id_session . '/' . $trans->transactions_id) ?>" 
                           class="bg-green-500 text-sm px-2 py-1 rounded-md hover:bg-green-600">
                           Edit
                        </a>
                        <a href="<?= site_url('payment/delete/' . $project->id_session . '/' . $trans->transactions_id) ?>" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')"
                           class="bg-red-500 text-sm px-2 py-1 rounded-md hover:bg-red-600">
                           Hapus
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($has_invoice)): ?>
        <a href="<?= site_url('payment/createkwt/' . $project->id_session . '/' . $has_invoice->transactions_id) ?>" 
           class="mt-2 bg-blue-500 px-4 py-2 rounded-md hover:bg-blue-700 inline-block text-center w-auto">
           Tambah Kwitansi
        </a>
    <?php endif; ?>
</div>

                <a href="<?= site_url('project/edit/'. $project->id_session) ?>" class="ml-2 bg-green-500 px-4 py-2 rounded hover:bg-green-600 inline-block text-center w-auto">Edit Project</a>
                <a href="<?= site_url('project') ?>" class="ml-2 bg-gray-500 px-4 py-2 rounded hover:bg-gray-600 inline-block text-center w-auto">Kembali</a>
              </form>

              <!-- ====== Table Three Start -->
              <div class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default  dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1" >
                <div class="max-w-full overflow-x-auto">
                  <table class="w-full table-auto">
                    <thead></thead>
                      <tr class="bg-gray-2 text-left dark:bg-meta-4">
                        <th
                          class="min-w-[220px] px-4 py-4 font-medium xl:pl-11"
                        >
                          Author
                        </th>
                        <th
                          class="min-w-[150px] px-4 py-4 font-medium"
                        >
                          Status
                        </th>
                        <th
                          class="min-w-[120px] px-4 py-4 font-medium"
                        >
                          Time
                        </th>
                        <th class="px-4 py-4 font-medium">
                          Device
                        </th>
                        <th class="px-4 py-4 font-medium">
                          IP
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1; foreach ($logactivity as $p): ?>
                      <tr>
                        <?php 
                        if ($p->log_activity_user_id === 'client'): ?>
                          <td class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                            <h5 class="font-medium">Client</h5>
                          </td>
                        <?php else: 
                          $company = $this->Crud_m->view_where('user', array('client_idsession' => $p->log_activity_user_id))->row();
                          if (!$company) {
                              $company = $this->Crud_m->view_where('user', array('id_session' => $p->log_activity_user_id))->row();
                          }
                          $level = $this->Crud_m->view_where('user_level', array('user_level_id' => $company->level))->row();
                        ?>
                          <td class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                            <h5 class="font-medium"><?= $company->username ?></h5>
                            <p class="text-sm"><?= $level->user_level_nama ?></p>
                          </td>
                        <?php endif; ?>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                          <p class="inline-flex rounded-full bg-success bg-opacity-10 px-3 py-1 text-sm font-medium text-success">
                            <?= $p->log_activity_status ?>
                          </p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p><?= hari($p->log_activity_waktu) ?>, <?= tgl_indo($p->log_activity_waktu)?></p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                         <p><?= $p->log_activity_platform ?></p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                         <p><?= $p->log_activity_ip ?></p>
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
