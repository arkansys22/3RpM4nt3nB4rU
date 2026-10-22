<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Tamu | <?= htmlspecialchars($clients->client_name) ?></title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'clients', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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

              <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                <h1 class="text-2xl font-bold">List Tamu <?= htmlspecialchars($clients->client_name) ?></h1>
                <a href="<?= site_url('clients/lihat/' . $clients->id_session) ?>" class="rounded-md bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">Kembali</a>
              </div>

              <?php if ($this->session->flashdata('Success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($this->session->flashdata('Success')) ?></div>
              <?php endif; ?>
              <?php if ($this->session->flashdata('Error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($this->session->flashdata('Error')) ?></div>
              <?php endif; ?>

              <?php if (!$tabel_ada): ?>
                <div class="rounded border border-warning bg-warning bg-opacity-10 px-4 py-3 text-sm">
                  Tabel <code>client_tamu</code> belum dibuat. Jalankan migrasi <code>db/client_tamu.sql</code> dulu.
                </div>
              <?php else: ?>

              <!-- Form tambah / ubah satu tamu -->
              <form id="form-tamu" action="<?= site_url('clients/tamu/store') ?>" method="post" class="mb-6 rounded-md border border-stroke p-4 dark:border-strokedark">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="client_id_session" value="<?= $clients->id_session ?>">
                <input type="hidden" name="id_tamu" value="<?= $edit ? (int) $edit->id : '' ?>">
                <h2 class="mb-3 text-lg font-semibold"><?= $edit ? 'Ubah Tamu' : 'Tambah Tamu' ?></h2>
                <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                  <div>
                    <label class="mb-1 block text-sm">Honorifics</label>
                    <input type="text" name="honorifics" list="daftarHonorifics" maxlength="50" autocomplete="off" placeholder="Pilih / ketik, contoh: Bapak"
                           value="<?= $edit ? htmlspecialchars($edit->honorifics) : '' ?>" class="w-full rounded border px-3 py-2">
                    <datalist id="daftarHonorifics">
                      <?php foreach (['Bapak', 'Ibu', 'Bapak/Ibu', 'Saudara', 'Saudari', 'Saudara/i', 'Kakak', 'Adik', 'Dr.', 'Prof.', 'H.', 'Hj.'] as $h): ?>
                        <option value="<?= $h ?>"></option>
                      <?php endforeach; ?>
                    </datalist>
                  </div>
                  <div>
                    <label class="mb-1 block text-sm">Nama Tamu</label>
                    <input type="text" name="nama" required maxlength="255" value="<?= $edit ? htmlspecialchars($edit->nama) : '' ?>" class="w-full rounded border px-3 py-2">
                  </div>
                  <div>
                    <label class="mb-1 block text-sm">Kategori</label>
                    <input type="text" name="kategori" maxlength="255" placeholder="Contoh: nama perusahaan / grup" value="<?= $edit ? htmlspecialchars($edit->kategori) : '' ?>" class="w-full rounded border px-3 py-2">
                  </div>
                </div>
                <div class="mt-3 flex gap-2">
                  <button type="submit" class="rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600"><?= $edit ? 'Simpan Perubahan' : '+ Tambah Tamu' ?></button>
                  <?php if ($edit): ?>
                    <a href="<?= site_url('clients/tamu/' . $clients->id_session) ?>" class="rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">Batal</a>
                  <?php endif; ?>
                </div>
              </form>

              <!-- Cetak PDF -->
              <form action="<?= site_url('clients/tamu/pdf/' . $clients->id_session) ?>" method="get" target="_blank" class="mb-6 rounded-md border border-stroke p-4 dark:border-strokedark">
                <h2 class="mb-3 text-lg font-semibold">Cetak PDF</h2>
                <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                  <div>
                    <label class="mb-1 block text-sm">Judul Acara</label>
                    <input type="text" name="judul_acara" value="ACARA RESEPSI NIKAH" class="w-full rounded border px-3 py-2">
                  </div>
                  <div>
                    <label class="mb-1 block text-sm">Judul Daftar</label>
                    <input type="text" name="judul_daftar" value="List Tamu" class="w-full rounded border px-3 py-2">
                  </div>
                  <div class="flex items-end">
                    <button type="submit" class="w-full rounded bg-primary px-4 py-2 text-white hover:bg-opacity-90 md:w-auto">Cetak PDF</button>
                  </div>
                </div>
              </form>

              <!-- Daftar tamu -->
              <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-semibold">Daftar Tamu (<?= count($tamu) ?>)</h2>
                <input type="search" id="cariTamu" placeholder="Cari nama / kategori…" class="w-full rounded border px-3 py-2 sm:w-72">
              </div>
              <?php if (empty($tamu)): ?>
                <p class="text-sm text-body dark:text-bodydark">Belum ada tamu.</p>
              <?php else: ?>
              <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto" id="tabelTamu">
                  <thead>
                    <tr class="bg-gray-2 text-left dark:bg-meta-4">
                      <th class="px-3 py-3 font-medium">No</th>
                      <th class="px-3 py-3 font-medium">Honorifics</th>
                      <th class="min-w-[220px] px-3 py-3 font-medium">Nama</th>
                      <th class="min-w-[180px] px-3 py-3 font-medium">Kategori</th>
                      <th class="px-3 py-3 font-medium">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($tamu as $i => $t): ?>
                    <tr class="border-b border-[#eee] dark:border-strokedark">
                      <td class="px-3 py-2"><?= $i + 1 ?></td>
                      <td class="px-3 py-2"><?= htmlspecialchars($t->honorifics) ?></td>
                      <td class="px-3 py-2"><?= htmlspecialchars($t->nama) ?></td>
                      <td class="px-3 py-2"><?= htmlspecialchars($t->kategori) ?></td>
                      <td class="px-3 py-2">
                        <div class="flex gap-2">
                          <a href="<?= site_url('clients/tamu/' . $clients->id_session) . '?edit=' . $t->id ?>#form-tamu" class="rounded bg-green-500 px-2 py-1 text-sm text-white hover:bg-green-600">Edit</a>
                          <form action="<?= site_url('clients/tamu/delete/' . $t->id) ?>" method="post" onsubmit="return confirm('Hapus tamu ini?')">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                            <button type="submit" class="rounded bg-red-500 px-2 py-1 text-sm text-white hover:bg-red-600">Hapus</button>
                          </form>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <?php endif; ?>

              <?php endif; ?>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
  <script>
    (function () {
      const cari = document.getElementById('cariTamu');
      if (!cari) { return; }
      cari.addEventListener('input', function () {
        const kata = cari.value.trim().toLowerCase();
        document.querySelectorAll('#tabelTamu tbody tr').forEach(function (tr) {
          tr.style.display = tr.textContent.toLowerCase().indexOf(kata) === -1 ? 'none' : '';
        });
      });
    })();
  </script>
</body>
</html>
