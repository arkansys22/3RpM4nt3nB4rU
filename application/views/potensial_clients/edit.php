<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Potensial Klien</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
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
              <h1 class="text-2xl font-bold mb-4">Edit Potensial Klien</h1>
              <form action="<?= site_url('potensial-clients/update/'.$pc->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <label class="block mb-2">Nama Klien</label>
                <input type="text" name="pc_name" value="<?= $pc->pc_name ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Nomer WhatsApp</label>
                <input type="text" name="pc_nowa" value="<?= $pc->pc_nowa ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Tanggal Pernikahan</label>
                <input type="date" name="event_date" value="<?= $pc->event_date ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Lokasi Pernikahan</label>
                <input type="text" name="location" value="<?= $pc->location ?>" class="w-full px-4 py-2 border rounded mb-4w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Pertama Chat</label>
                <input type="date" name="chat_date" value="<?= $pc->chat_date ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border rounded mb-4" required>
                    <option value="Tanya-tanya" <?= $pc->status == 'Tanya-tanya' ? 'selected' : '' ?>>Tanya-tanya</option>
                    <option value="Hot" <?= $pc->status == 'Hot' ? 'selected' : '' ?>>Hot</option>
                    <option value="Konsul" <?= $pc->status == 'Konsul' ? 'selected' : '' ?>>Konsul Offline</option>
                    <option value="Deal" <?= $pc->status == 'Deal' ? 'selected' : '' ?>>Deal</option>
                    <option value="Ghosting" <?= $pc->status == 'Ghosting' ? 'selected' : '' ?>>Ghosting</option>
                    <option value="Batal" <?= $pc->status == 'Batal' ? 'selected' : '' ?>>Batal</option>
                </select>

                <label class="block mb-2">Catatan</label>
                <textarea name="note" class="w-full px-4 py-2 border rounded mb-4" required><?= $pc->note ?></textarea>

                <div class="flex flex-col sm:flex-row justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Update</button>
                <a href="<?= site_url('potensial-clients') ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
                </div>
              </form>
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
