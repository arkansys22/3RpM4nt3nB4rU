<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hak Akses</title>
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

              <h1 class="text-2xl font-bold mb-1">Hak Akses</h1>
              <p class="mb-4 text-sm text-body dark:text-bodydark">
                Atur sendiri halaman mana saja yang ditampilkan untuk setiap level user. Semua halaman bisa dicentang untuk level mana pun, termasuk halaman di luar hak dasar level itu
                (hanya halaman Hak Akses yang tetap khusus Developer &amp; Administrator). Level yang belum diatur memakai tampilan bawaan (halaman sesuai hak dasar levelnya).
                Level Developer tidak pernah dibatasi.
              </p>

              <?php if ($this->session->flashdata('Success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($this->session->flashdata('Success')) ?></div>
              <?php endif; ?>
              <?php if ($this->session->flashdata('Error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($this->session->flashdata('Error')) ?></div>
              <?php endif; ?>

              <?php if (!$tersedia): ?>
                <div class="rounded border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                  Tabel hak akses belum ada dan belum bisa dibuat otomatis (database tidak mengizinkan CREATE TABLE).
                  Jalankan <code>db/hak_akses.sql</code> di database, lalu muat ulang halaman ini.
                </div>
              <?php else: ?>
                <?php if ($this->session->level === '1'): ?>
                <form action="<?= site_url('hak-akses/level-baru') ?>" method="post" class="mb-6 rounded-md border border-stroke p-4 dark:border-strokedark">
                  <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                  <p class="mb-1 font-medium">Tambah Level Baru</p>
                  <p class="mb-3 text-xs text-gray-500">
                    Level baru meniru hak dasar sebuah level (level dasar), lalu halamannya bisa dibatasi sesuai kebutuhan. Setelah dibuat, atur halamannya dan pilih level ini di form User
                    (Tambah/Edit User &rarr; Level). Hanya Developer yang bisa menambah atau menghapus level.
                  </p>
                  <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                    <div>
                      <label class="mb-1 block text-sm font-medium" for="nama_level">Nama Level</label>
                      <input type="text" id="nama_level" name="nama" required maxlength="100" placeholder="Contoh: Staff Keuangan" class="w-full rounded border px-4 py-2">
                    </div>
                    <div>
                      <label class="mb-1 block text-sm font-medium" for="dasar_level">Level Dasar (hak dasar yang ditiru)</label>
                      <select id="dasar_level" name="dasar" required class="w-full rounded border px-4 py-2">
                        <?php foreach ($daftar as $l): if ($l->kustom) { continue; } ?>
                          <option value="<?= htmlspecialchars($l->level) ?>"><?= htmlspecialchars($l->nama) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="flex items-end">
                      <button type="submit" class="w-full rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 md:w-auto">+ Tambah Level</button>
                    </div>
                  </div>
                </form>
                <?php endif; ?>
                <div class="max-w-full overflow-x-auto rounded-sm border border-stroke dark:border-strokedark">
                  <table class="w-full table-auto text-sm">
                    <thead>
                      <tr class="bg-gray-2 text-left dark:bg-meta-4">
                        <th class="px-4 py-3">Level</th>
                        <th class="px-4 py-3">User Aktif</th>
                        <th class="px-4 py-3">Halaman yang Ditampilkan</th>
                        <th class="px-4 py-3">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($daftar as $l): ?>
                      <tr class="border-t border-stroke align-top dark:border-strokedark">
                        <td class="px-4 py-3 font-medium">
                          <?= htmlspecialchars($l->nama) ?>
                          <?php if ($l->kustom): ?><span class="ml-1 rounded-full bg-purple-100 px-2 py-0.5 text-xs font-normal text-purple-700">Level baru &middot; hak dasar <?= htmlspecialchars(ucwords(strtolower($l->dasar_nama))) ?></span><?php endif; ?>
                        </td>
                        <td class="px-4 py-3"><?= (int) $l->jumlah_user ?></td>
                        <td class="px-4 py-3">
                          <?php if ($l->menu === null): ?>
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">Bawaan (semua halaman sesuai hak level)</span>
                          <?php else: ?>
                            <div class="flex flex-wrap gap-1">
                              <?php foreach ($katalog as $key => $def): if (!in_array($key, $l->menu, true)) { continue; } ?>
                                <span class="rounded-full bg-blue-50 px-2 py-0.5 text-xs text-blue-700"><?= htmlspecialchars($def['label']) ?></span>
                              <?php endforeach; ?>
                            </div>
                          <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                          <div class="flex flex-wrap gap-2">
                            <a href="<?= site_url('hak-akses/level/' . $l->level) ?>" class="rounded bg-green-500 px-3 py-1 text-white hover:bg-green-600">Atur</a>
                            <?php if ($this->session->level === '1'):
                              $nonaktif = (int) $l->jumlah_semua_user - (int) $l->jumlah_user; // user nonaktif/terhapus yang masih memegang level ini
                              if (!$l->kustom) {
                                  $alasan = 'Level bawaan sistem tidak bisa dihapus';
                              } elseif ((int) $l->jumlah_user > 0) {
                                  $alasan = 'Masih punya ' . (int) $l->jumlah_user . ' user aktif';
                              } else {
                                  $alasan = '';
                              }
                              $konfirmasi = 'Hapus level ' . $l->nama . '?' . ($nonaktif > 0 ? ' ' . $nonaktif . ' user nonaktif/terhapus yang masih memakai level ini akan dipindah ke level Guest.' : '');
                            ?>
                              <form action="<?= site_url('hak-akses/level-hapus/' . $l->level) ?>" method="post" onsubmit="return confirm(<?= htmlspecialchars(json_encode($konfirmasi), ENT_QUOTES) ?>)">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                <button type="submit" <?= $alasan !== '' ? 'disabled title="' . htmlspecialchars($alasan) . '"' : '' ?> class="rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600 disabled:cursor-not-allowed disabled:opacity-40">Hapus</button>
                              </form>
                            <?php endif; ?>
                            <?php if ($l->menu !== null): ?>
                              <form action="<?= site_url('hak-akses/reset/' . $l->level) ?>" method="post" onsubmit="return confirm('Kembalikan level ini ke tampilan bawaan?')">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                <button type="submit" class="rounded bg-gray-500 px-3 py-1 text-white hover:bg-gray-600">Kembalikan ke Bawaan</button>
                              </form>
                            <?php endif; ?>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
</body>
</html>
