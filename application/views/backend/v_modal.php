<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal</title>
    <link rel="icon" href="<?= favicon_url() ?>" type="image/x-icon">
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

              <?php
                $rp = function ($n) { return 'Rp ' . number_format((float) $n, 0, ',', '.'); };
                $sedang_edit = !empty($edit);
                $form_terbuka = $sedang_edit || $old;
                $v = function ($f, $default = '') use ($nilai) { return $nilai ? (string) $nilai->$f : $default; };
                $input = 'w-full rounded-md border border-stroke dark:border-strokedark px-3 py-2 dark:bg-boxdark dark:text-white';
                $csrf_nama = $this->security->get_csrf_token_name();
                $csrf_hash = $this->security->get_csrf_hash();
                $jenis_sekarang = $v('jenis', 'Setoran Modal');
              ?>

              <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Modal</h1>
                <button type="button" id="btn-toggle-form-modal" onclick="toggleFormModal()" class="bg-green-500 text-white px-5 py-2.5 rounded-md hover:bg-green-600 font-semibold <?= $form_terbuka ? 'hidden' : '' ?>">
                  + Tambah Modal
                </button>
              </div>

              <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm"><?= htmlspecialchars($this->session->flashdata('error')) ?></div>
              <?php endif; ?>
              <?php if ($this->session->flashdata('success')): ?>
                <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 text-sm"><?= htmlspecialchars($this->session->flashdata('success')) ?></div>
              <?php endif; ?>

              <!-- Ringkasan -->
              <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                <div class="rounded-lg border border-stroke dark:border-strokedark p-4">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Modal Bersih</p>
                  <p class="text-xl font-bold text-blue-600"><?= $rp($ringkas['bersih']) ?></p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Setoran + saldo awal &minus; penarikan</p>
                </div>
                <div class="rounded-lg border border-stroke dark:border-strokedark p-4">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Setoran Modal <span class="text-xs">(COA 3000)</span></p>
                  <p class="text-xl font-bold"><?= $rp($ringkas['setoran']) ?></p>
                </div>
                <div class="rounded-lg border border-stroke dark:border-strokedark p-4">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Saldo Awal Modal <span class="text-xs">(COA 3100)</span></p>
                  <p class="text-xl font-bold"><?= $rp($ringkas['saldo_awal']) ?></p>
                </div>
                <div class="rounded-lg border border-stroke dark:border-strokedark p-4">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Penarikan / Deviden <span class="text-xs">(COA <?= htmlspecialchars($akun[Modal_model::PENGURANG]) ?>)</span></p>
                  <p class="text-xl font-bold text-red-600"><?= $rp($ringkas['penarikan']) ?></p>
                </div>
              </div>

              <!-- Form tambah / edit -->
              <div id="form-modal-wrapper" class="<?= $form_terbuka ? '' : 'hidden' ?> mb-6 p-5 rounded-lg bg-gray-50 dark:bg-neutral-700">
                <h2 class="font-semibold text-gray-800 dark:text-white mb-4"><?= $sedang_edit ? 'Edit Modal' : 'Tambah Modal Baru' ?></h2>
                <form method="post" action="<?= $sedang_edit ? site_url('modal/update/' . $edit->id_session) : site_url('modal/store') ?>">
                  <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Jenis</label>
                      <select name="jenis" id="modal-jenis" required class="<?= $input ?>">
                        <?php foreach ($akun as $nama_jenis => $nomer): ?>
                          <option value="<?= htmlspecialchars($nama_jenis) ?>" <?= $jenis_sekarang === $nama_jenis ? 'selected' : '' ?>><?= htmlspecialchars($nama_jenis) ?></option>
                        <?php endforeach; ?>
                      </select>
                      <p class="mt-1 text-xs text-blue-600 dark:text-blue-400" id="modal-akun-hint"></p>
                    </div>
                    <div>
                      <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Pemilik / Investor</label>
                      <input type="text" name="penanam" required maxlength="255" value="<?= htmlspecialchars($v('penanam')) ?>" placeholder="Contoh: Ibu Rina (pemilik)" class="<?= $input ?>">
                    </div>
                    <div>
                      <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Nominal</label>
                      <input type="text" inputmode="numeric" name="nominal" id="modal-nominal" required value="<?= htmlspecialchars(($nilai && ctype_digit((string) $nilai->nominal)) ? number_format($nilai->nominal, 0, ',', '.') : ($nilai ? (string) $nilai->nominal : '')) ?>" placeholder="Contoh: 10.000.000" class="<?= $input ?>">
                    </div>
                    <div>
                      <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Tanggal</label>
                      <input type="date" name="tanggal" required value="<?= htmlspecialchars($v('tanggal', date('Y-m-d'))) ?>" class="<?= $input ?>">
                    </div>
                    <div class="md:col-span-2">
                      <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Keterangan <span class="text-xs">(opsional)</span></label>
                      <textarea name="keterangan" rows="2" maxlength="2000" class="<?= $input ?>"><?= htmlspecialchars($v('keterangan')) ?></textarea>
                    </div>
                  </div>
                  <div class="flex gap-3 mt-4">
                    <button type="submit" class="bg-green-500 text-white px-5 py-2.5 rounded-md hover:bg-green-600 font-semibold">Simpan</button>
                    <?php if ($sedang_edit): ?>
                      <a href="<?= site_url('modal') ?>" class="bg-gray-400 text-white px-5 py-2.5 rounded-md hover:bg-gray-500 font-semibold">Batal</a>
                    <?php else: ?>
                      <button type="button" onclick="toggleFormModal()" class="bg-gray-400 text-white px-5 py-2.5 rounded-md hover:bg-gray-500 font-semibold">Batal</button>
                    <?php endif; ?>
                  </div>
                </form>
              </div>

              <!-- Tabel -->
              <div class="overflow-x-auto bg-white dark:bg-neutral-700 rounded-lg shadow-md">
                <table class="min-w-full text-left text-sm whitespace-nowrap bg-white dark:bg-neutral-800">
                  <thead class="uppercase tracking-wider border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
                    <tr>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300">Tanggal</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300">Jenis</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300">Pemilik / Investor</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300">Akun COA</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300 text-right">Nominal</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300">Keterangan</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($daftar)): ?>
                      <tr><td colspan="7" class="px-4 py-6 text-gray-700 dark:text-gray-400 text-center">Belum ada data modal.</td></tr>
                    <?php else: foreach ($daftar as $m): ?>
                      <tr class="border-b border-gray-200 dark:border-neutral-600">
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= date('d-m-Y', strtotime($m->tanggal)) ?></td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-200"><?= htmlspecialchars($m->jenis) ?></td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($m->penanam) ?></td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400"><a href="<?= site_url('coa/detail/' . $m->kategori) ?>" class="text-blue-600 hover:underline"><?= htmlspecialchars($m->kategori) ?></a></td>
                        <td class="px-4 py-3 text-right font-semibold <?= $m->jenis === Modal_model::PENGURANG ? 'text-red-600' : 'text-gray-900 dark:text-gray-200' ?>"><?= $rp($m->nominal) ?></td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400"><?= htmlspecialchars((string) $m->keterangan) !== '' ? htmlspecialchars(mb_strimwidth((string) $m->keterangan, 0, 50, '...')) : '-' ?></td>
                        <td class="px-4 py-3">
                          <div class="flex gap-2">
                            <a href="<?= site_url('modal/edit/' . $m->id_session) ?>" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600">Edit</a>
                            <form method="post" action="<?= site_url('modal/delete/' . $m->id_session) ?>" onsubmit="return confirm('Hapus data modal ini? Data juga dihapus dari akun COA dan tidak bisa dikembalikan.')">
                              <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
                              <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600">Hapus</button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; endif; ?>
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
  <script>
    function toggleFormModal() {
      document.getElementById('form-modal-wrapper').classList.toggle('hidden');
      document.getElementById('btn-toggle-form-modal').classList.toggle('hidden');
    }

    // Input nominal: tampil dengan titik ribuan; server membuang titiknya lagi.
    document.getElementById('modal-nominal').addEventListener('input', function () {
      this.value = this.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    });

    // Petunjuk akun COA mengikuti pilihan jenis
    var AKUN_MODAL = <?= json_encode($akun, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    var NAMA_AKUN = { '3000': 'Modal', '3100': 'Opening Balance Equity', '3200.02': 'Deviden', '320002': 'Deviden' };
    var selJenis = document.getElementById('modal-jenis');
    function perbaruiAkunModal() {
      var no = AKUN_MODAL[selJenis.value];
      document.getElementById('modal-akun-hint').innerHTML = 'Dicatat di COA: <b>' + no + ' &ndash; ' + (NAMA_AKUN[no] || '') + '</b>';
    }
    selJenis.addEventListener('change', perbaruiAkunModal);
    perbaruiAkunModal();
  </script>
</body>
</html>
