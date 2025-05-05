<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Diri</title>
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
              <h1 class="text-2xl font-bold mb-4">Edit Data Diri</h1>
              <form action="<?= site_url('user/update2/'.$pc->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <?php if ($pc->level == 1): ?>
                  <label class="block mb-2">Username</label>
                  <input type="text" name="username" value="<?= $pc->username ?>" class="w-full px-4 py-2 border rounded mb-4" required>
                <?php endif; ?>

                <label class="block mb-2">Nama</label>
                <input type="text" name="nama" value="<?= $pc->nama ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Email</label>
                <input type="email" name="email" value="<?= $pc->email ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Password</label>
                <input type="password" name="password" placeholder="Ganti password isi disini, jika tidak abaikan" class="w-full px-4 py-2 border rounded mb-4">

                <?php if (isset($crews) && $crews): ?>
                  <label class="block mb-2">Gender</label>
                  <select name="gender" class="w-full px-4 py-2 border rounded mb-4">
                    <option value="Male" <?= $crews->gender == 'Male' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Female" <?= $crews->gender == 'Female' ? 'selected' : '' ?>>Perempuan</option>
                  </select>

                  <label class="block mb-2">Agama</label>
                  <select name="religion" class="w-full px-4 py-2 border rounded mb-4" required>
                    <option value="Islam" <?= $crews->religion == 'Islam' ? 'selected' : '' ?>>Islam</option>
                    <option value="Kristen" <?= $crews->religion == 'Kristen' ? 'selected' : '' ?>>Kristen</option>
                    <option value="Katolik" <?= $crews->religion == 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                    <option value="Hindu" <?= $crews->religion == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                    <option value="Buddha" <?= $crews->religion == 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                    <option value="Lainnya" <?= $crews->religion == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                  </select>

                  <label class="block mb-2">No HP</label>
                  <input type="text" name="phone" value="<?= $crews->phone ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                  <label class="block mb-2">Tanggal Lahir</label>
                  <input type="date" name="birth_date" value="<?= $crews->birth_date ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                  <label class="block mb-2">Alamat</label>
                  <textarea name="address" class="w-full px-4 py-2 border rounded mb-4" required><?= $crews->address ?></textarea>
                <?php endif; ?>

                <div class="flex flex-col sm:flex-row justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Update</button>
                <a href="<?= site_url('user/lihat/'.$pc->id_session) ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
