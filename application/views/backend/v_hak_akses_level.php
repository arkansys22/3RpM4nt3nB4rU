<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Hak Akses <?= htmlspecialchars($nama_level) ?></title>
    <link rel="icon" href="<?= favicon_url() ?>" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'hak_akses', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
  >
  <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})" class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
    <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent"></div>
  </div>
  <div class="flex h-screen overflow-hidden">
    <?php $this->load->view('backend/sidebar')?>

    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
      <?php $this->load->view('backend/header')?>

      <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
          <div class="grid grid-cols-12 gap-4 md:gap-6 2xl:gap-9">
            <div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">

              <h1 class="text-2xl font-bold mb-1">Hak Akses: <?= htmlspecialchars($nama_level) ?></h1>
              <?php if (!empty($dasar_nama)): ?><p class="mb-2 text-xs text-gray-500">Level baru &middot; hak dasar mengikuti <strong><?= htmlspecialchars(ucwords(strtolower($dasar_nama))) ?></strong></p><?php endif; ?>
              <p class="mb-4 text-sm text-body dark:text-bodydark">
                Semua halaman ditampilkan di sini. Centang halaman yang ditampilkan dan boleh dibuka untuk semua user level ini; yang tidak dicentang disembunyikan dari sidebar dan tidak bisa dibuka.
                Halaman bertanda <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700">tambahan</span> berada di luar hak dasar level ini; kalau dicentang, level ini mendapat akses ke halaman tersebut.
                Pengaturan hanya berlaku untuk level ini dan langsung dipakai semua user di level ini. Pakai "Kembalikan ke Bawaan" di daftar level untuk membatalkannya.
              </p>

              <?php if ($this->session->flashdata('Error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($this->session->flashdata('Error')) ?></div>
              <?php endif; ?>

              <?php
              // Kelompokkan menu per group (urutan sesuai katalog)
              $perGrup = [];
              foreach ($menu_semua as $key => $def) {
                  $perGrup[$def['group']][$key] = $def;
              }
              ?>
              <form action="<?= site_url('hak-akses/simpan') ?>" method="post" class="bg-white dark:bg-boxdark p-2 sm:p-6 shadow-md rounded">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="level" value="<?= htmlspecialchars($level) ?>">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                  <?php foreach ($perGrup as $grup => $menuGrup): ?>
                    <div class="rounded-md border border-stroke p-4 dark:border-strokedark" data-grup>
                      <div class="mb-2 flex items-center justify-between">
                        <p class="font-medium"><?= htmlspecialchars($grup) ?></p>
                        <label class="text-xs text-gray-500"><input type="checkbox" class="mr-1" data-semua> Pilih semua</label>
                      </div>
                      <?php foreach ($menuGrup as $key => $def):
                        $kunci_wajib = in_array($key, $menu_wajib, true);
                        $terkunci = !in_array($key, $menu_bisa_dipilih, true); // tidak bisa diberikan ke level ini (mis. Hak Akses)
                        $tambahan = !$terkunci && !in_array($key, $menu_bawaan, true);
                        $mati = $kunci_wajib || $terkunci;
                      ?>
                        <label class="mb-1 flex items-start gap-2 text-sm <?= $terkunci ? 'opacity-60' : '' ?>">
                          <input type="checkbox" name="menu[]" value="<?= htmlspecialchars($key) ?>" class="mt-1" <?= ($kunci_wajib || (!$terkunci && in_array($key, $menu_dipilih, true))) ? 'checked' : '' ?> <?= $mati ? 'disabled' : '' ?>>
                          <span>
                            <?= htmlspecialchars($def['label']) ?>
                            <?php if ($kunci_wajib): ?><em class="text-xs text-gray-500">(selalu aktif)</em><?php endif; ?>
                            <?php if ($terkunci): ?><em class="text-xs text-gray-500">(hanya Developer &amp; Administrator)</em><?php endif; ?>
                            <?php if ($tambahan): ?><span class="ml-1 rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700">tambahan</span><?php endif; ?>
                          </span>
                        </label>
                      <?php endforeach; ?>
                    </div>
                  <?php endforeach; ?>
                </div>

                <div class="mt-6 flex flex-col justify-end sm:flex-row">
                  <button type="submit" class="mb-2 w-full rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600 sm:mb-0 sm:w-24">Simpan</button>
                  <a href="<?= site_url('hak-akses') ?>" class="w-full rounded bg-gray-500 px-4 py-2 text-center text-white hover:bg-gray-600 sm:ml-2 sm:w-24">Batal</a>
                </div>
              </form>

            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
  <script>
    // "Pilih semua" per kelompok; status awal mengikuti isi kelompok
    document.querySelectorAll('[data-grup]').forEach(function (grup) {
      var semua = grup.querySelector('[data-semua]');
      var kotak = grup.querySelectorAll('input[name="menu[]"]:not([disabled])');
      function sinkron() { semua.checked = Array.prototype.every.call(kotak, function (k) { return k.checked; }); }
      semua.addEventListener('change', function () { kotak.forEach(function (k) { k.checked = semua.checked; }); });
      kotak.forEach(function (k) { k.addEventListener('change', sinkron); });
      sinkron();
    });
  </script>
</body>
</html>
