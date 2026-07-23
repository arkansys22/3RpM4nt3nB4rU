<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setting Salary</title>
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
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h1 class="text-xl sm:text-2xl font-bold">Setting Salary</h1>
                <div class="flex items-center gap-2">
                  <a href="<?= site_url('rekap-gaji/kategori') ?>" class="flex flex-1 sm:flex-none items-center justify-center gap-2 bg-gray-500 text-white px-3 sm:px-4 py-2.5 sm:py-3 rounded-md hover:bg-gray-600 focus:outline-none text-xs sm:text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 shrink-0">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 3.75v3.75m-16.5-3.75v3.75m16.5 3.75c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    <span class="whitespace-nowrap">Kelola Kategori</span>
                  </a>
                  <a href="<?= site_url('panel') ?>" class="flex items-center gap-2 bg-blue-500 text-white p-2.5 sm:p-3 rounded-md hover:bg-blue-700 focus:outline-none shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                  </a>
                </div>
                </div>

                <?php if ($this->session->flashdata('error')): ?>
                  <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm">
                    <?= $this->session->flashdata('error') ?>
                  </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('success')): ?>
                  <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 text-sm">
                    <?= $this->session->flashdata('success') ?>
                  </div>
                <?php endif; ?>

                <div class="mb-5">
                  <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Pilih Nama</label>
                  <select
                    onchange="if (this.value) { window.location.href = '<?= site_url('rekap-gaji/') ?>' + this.value; }"
                    class="w-full sm:w-96 rounded-md border border-stroke dark:border-strokedark px-3 py-2 dark:bg-boxdark dark:text-white"
                  >
                    <option value="">- Pilih nama user -</option>
                    <?php foreach ($daftar_user as $u): ?>
                      <option value="<?= $u->id_session ?>" <?= (!empty($user_terpilih) && $user_terpilih->id_session === $u->id_session) ? 'selected' : '' ?>>
                        <?= $u->nama ?> (<?= ucwords(strtolower($u->user_level_nama)) ?>)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <?php if (empty($user_terpilih)): ?>
                  <div class="p-6 text-center text-sm text-gray-700 dark:text-gray-400 bg-white dark:bg-neutral-700 rounded-lg shadow-md">
                    Pilih nama dulu di atas untuk melihat & atur kategori salary-nya.
                  </div>
                <?php else: ?>
                  <?php $id_terpilih = array_map(function ($k) { return (int) $k->id; }, $user_terpilih->kategori_list); ?>
                  <div class="p-4 sm:p-5 rounded-lg bg-white dark:bg-neutral-800 shadow-md">
                    <div class="flex items-center justify-between mb-3">
                      <span class="font-semibold text-lg text-gray-900 dark:text-gray-200"><?= $user_terpilih->nama ?></span>
                      <span class="text-xs px-2 py-1 rounded-full bg-gray-100 dark:bg-neutral-700 text-gray-600 dark:text-gray-300"><?= ucwords(strtolower($user_terpilih->user_level_nama)) ?></span>
                    </div>

                    <div class="mb-4">
                      <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Kategori Salary Saat Ini</p>
                      <?php if (empty($user_terpilih->kategori_list)): ?>
                        <p class="text-sm text-gray-400">- Belum ada kategori -</p>
                      <?php else: ?>
                        <?php foreach ($user_terpilih->kategori_list as $k): ?>
                          <p class="text-sm text-gray-700 dark:text-gray-300"><?= $k->nama_kategori ?>: <span class="font-medium"><?= format_nominal_salary($k->nominal_gaji, $k->satuan_gaji) ?></span></p>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </div>

                    <form method="post" action="<?= site_url('rekap-gaji/assign/' . $user_terpilih->id_session) ?>">
                      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                      <select name="kategori_gaji_id[]" multiple size="6" class="w-full sm:w-96 border border-stroke dark:border-strokedark rounded-md px-3 py-2 dark:bg-boxdark dark:text-white text-sm mb-1">
                        <?php foreach ($daftar_kategori as $k): ?>
                          <option value="<?= $k->id ?>" <?= in_array((int) $k->id, $id_terpilih, true) ? 'selected' : '' ?>>
                            <?= $k->nama_kategori ?> (<?= $k->satuan_gaji ?>)
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <p class="text-xs text-gray-400 mb-2">Ctrl/Cmd+klik (atau tap-tahan di HP) untuk pilih lebih dari satu</p>
                      <button type="submit" class="w-full sm:w-auto bg-blue-500 text-white px-6 py-2.5 rounded-md hover:bg-blue-600 text-sm font-semibold">
                        Simpan
                      </button>
                    </form>
                  </div>
                <?php endif; ?>
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
