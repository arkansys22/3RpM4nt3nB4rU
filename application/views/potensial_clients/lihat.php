<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Potensial Klien</title>
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'projects', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h1 class="text-2xl font-bold mb-4">Lihat Potensial Klien <?= $pc->status ?></h1>
              <form action="<?= site_url('potensial-clients/update/'.$pc->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <label class="block mb-2">Nama Klien : <?= $pc->pc_name ?></label>        
                <label class="block mb-2">Nomer WhatsApp : <?= $pc->pc_nowa ?></label>        
                <label class="block mb-2">Tanggal Pernikahan : <?= hari($pc->event_date) ?>, <?= tgl_indo($pc->event_date) ?></label>
                <label class="block mb-2">Lokasi Pernikahan : <?= $pc->location ?></label>
                <label class="block mb-2">Pertama Chat : <?= hari($pc->chat_date) ?>, <?= tgl_indo($pc->chat_date) ?></label>
                <label class="block mb-2">Catatan : <?= $pc->note ?></label>
                <br>
                <a href="<?= site_url('potensial-clients/edit/'. $pc->id_session) ?>" class="ml-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 inline-block text-center w-auto">Edit</a>
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
                        <td
                          class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11"
                        >
                          <h5 class="font-medium text-black dark:text-white"><?= $company->username ?></h5>
                          <p class="text-sm"><?= $level->user_level_nama ?></p>
                        </td>                        
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                          <p
                            class="inline-flex rounded-full bg-success bg-opacity-10 px-3 py-1 text-sm font-medium text-success"
                          >
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
