<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Website</title>
    <link rel="icon" href="<?= favicon_url() ?>" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'pengaturan', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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

              <h1 class="text-2xl font-bold mb-1">Pengaturan Website</h1>
              <p class="mb-4 text-sm text-body dark:text-bodydark">Pengaturan umum yang dipakai di seluruh aplikasi. Gambar yang belum diupload memakai tampilan bawaan.</p>

              <?php if ($this->session->flashdata('Success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($this->session->flashdata('Success')) ?></div>
              <?php endif; ?>
              <?php if ($this->session->flashdata('Error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($this->session->flashdata('Error')) ?></div>
              <?php endif; ?>

              <form action="<?= site_url('pengaturan-website/update') ?>" method="post" enctype="multipart/form-data" class="bg-white dark:bg-boxdark p-2 sm:p-6 shadow-md rounded">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div>
                    <label class="mb-2 block font-medium" for="nama_website">Nama Website</label>
                    <input type="text" id="nama_website" name="nama_website" required maxlength="100" value="<?= htmlspecialchars((string) $pengaturan->nama_website) ?>" class="w-full rounded border px-4 py-2">
                  </div>
                  <div>
                    <label class="mb-2 block font-medium">Nomor WhatsApp <span class="text-xs font-normal text-gray-500">(boleh lebih dari satu)</span></label>
                    <?php
                    // nomor yang sudah tersimpan (satu per baris); minimal satu kolom kosong ditampilkan
                    $nomorWaTersimpan = preg_split('/[\r\n,;|]+/', trim((string) $pengaturan->whatsapp), -1, PREG_SPLIT_NO_EMPTY) ?: [''];
                    ?>
                    <div id="daftar-whatsapp" class="space-y-2" data-awal="<?= htmlspecialchars(json_encode(array_values(array_map('trim', $nomorWaTersimpan))), ENT_QUOTES) ?>"></div>
                    <button type="button" id="tambah-whatsapp" class="mt-2 rounded bg-blue-500 px-3 py-1.5 text-sm text-white hover:bg-blue-600">+ Tambah Nomor</button>
                    <script>
                    (function () {
                        const box = document.getElementById('daftar-whatsapp');
                        function baris(nilai) {
                            const row = document.createElement('div');
                            row.className = 'flex gap-2';
                            row.innerHTML = '<input type="text" name="whatsapp[]" inputmode="tel" maxlength="30" placeholder="Contoh: 081234567890" class="w-full rounded border px-4 py-2">' +
                                '<button type="button" class="rounded bg-red-500 px-3 py-2 text-sm text-white hover:bg-red-600">Hapus</button>';
                            row.querySelector('input').value = nilai || '';
                            row.querySelector('button').addEventListener('click', function () {
                                if (box.children.length > 1) { row.remove(); } else { row.querySelector('input').value = ''; }
                            });
                            box.appendChild(row);
                            return row.querySelector('input');
                        }
                        try { JSON.parse(box.dataset.awal || '[""]').forEach(baris); } catch (e) { baris(''); }
                        document.getElementById('tambah-whatsapp').addEventListener('click', function () { baris('').focus(); });
                    })();
                    </script>
                  </div>
                  <div class="md:col-span-2">
                    <label class="mb-2 block font-medium" for="url">Alamat Website</label>
                    <input type="text" id="url" name="url" maxlength="100" placeholder="https://www.contoh.com" value="<?= htmlspecialchars((string) $pengaturan->url) ?>" class="w-full rounded border px-4 py-2">
                  </div>
                  <div class="md:col-span-2">
                    <label class="mb-2 block font-medium" for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" placeholder="Alamat kantor / perusahaan" class="w-full rounded border px-4 py-2"><?= htmlspecialchars((string) $pengaturan->alamat) ?></textarea>
                  </div>
                  <div>
                    <label class="mb-2 block font-medium" for="instagram">Akun Instagram</label>
                    <input type="text" id="instagram" name="instagram" maxlength="100" placeholder="@namaakun atau link profil" value="<?= htmlspecialchars((string) $pengaturan->instagram) ?>" class="w-full rounded border px-4 py-2">
                  </div>
                  <div>
                    <label class="mb-2 block font-medium" for="tiktok">Akun TikTok</label>
                    <?php if ($tiktok_ada): ?>
                    <input type="text" id="tiktok" name="tiktok" maxlength="150" placeholder="@namaakun atau link profil" value="<?= htmlspecialchars((string) ($pengaturan->tiktok ?? '')) ?>" class="w-full rounded border px-4 py-2">
                    <?php else: ?>
                    <input type="text" id="tiktok" disabled placeholder="Kolom tiktok belum bisa dibuat di database" class="w-full rounded border bg-gray-100 px-4 py-2">
                    <?php endif; ?>
                  </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                  <?php
                  $gambar = [
                      ['field' => 'logo',         'judul' => 'Logo (halaman login)',          'info' => 'Dipakai di halaman login (latar terang).',          'url' => logo_login_url(),   'kolom' => 'logo',      'latar' => 'bg-white',   'tinggi' => 'h-20'],
                      ['field' => 'logo_sidebar', 'judul' => 'Logo Sidebar (latar gelap)',    'info' => 'Dipakai di sidebar dan header (latar gelap).',      'url' => logo_sidebar_url(), 'kolom' => 'mini_logo', 'latar' => 'bg-black',   'tinggi' => 'h-20'],
                      ['field' => 'favicon',      'judul' => 'Favicon',                       'info' => 'Ikon kecil di tab browser. Disarankan persegi (PNG).', 'url' => favicon_url(),     'kolom' => 'favicon',   'latar' => 'bg-gray-100', 'tinggi' => 'h-16'],
                  ];
                  foreach ($gambar as $g):
                      $nilai = trim((string) $pengaturan->{$g['kolom']});
                      $custom = strpos($nilai, 'pengaturan/') === 0;
                  ?>
                  <div class="rounded-md border border-stroke p-4 dark:border-strokedark">
                    <p class="mb-1 font-medium"><?= $g['judul'] ?></p>
                    <p class="mb-3 text-xs text-gray-500"><?= $g['info'] ?></p>
                    <div class="mb-3 flex items-center justify-center rounded p-3 <?= $g['latar'] ?>">
                      <img src="<?= htmlspecialchars($g['url']) ?>" alt="<?= $g['judul'] ?>" class="<?= $g['tinggi'] ?> max-w-full object-contain">
                    </div>
                    <input type="file" name="<?= $g['field'] ?>" accept="image/png,image/jpeg,image/gif,image/webp,image/x-icon" class="w-full text-sm text-gray-500 file:mr-3 file:rounded file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-gray-500">PNG/JPG/GIF/WEBP/ICO, maks 2 MB.</p>
                    <?php if ($custom): ?>
                      <label class="mt-2 inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" name="hapus_<?= $g['field'] ?>" value="1"> Kembalikan ke bawaan
                      </label>
                    <?php else: ?>
                      <p class="mt-2 text-xs text-gray-500">Saat ini memakai tampilan bawaan.</p>
                    <?php endif; ?>
                  </div>
                  <?php endforeach; ?>
                </div>

                <div class="mt-6 rounded-md border border-stroke p-4 dark:border-strokedark">
                  <p class="mb-1 font-medium">Kode Awal Nomor Transaksi</p>
                  <p class="mb-3 text-xs text-gray-500">3 huruf/angka di depan nomor invoice dan kwitansi, contoh: <code>IMB260417112</code> (kode + tanggal + nomor). Hanya berlaku untuk dokumen yang dibuat sesudah diubah; dokumen lama tetap memakai kode lamanya dan tetap dikenali.</p>
                  <?php if (!empty($kode_ada)): ?>
                  <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                      <label class="mb-2 block text-sm font-medium" for="kode_invoice">Kode Invoice</label>
                      <input type="text" id="kode_invoice" name="kode_invoice" maxlength="3" required pattern="[A-Za-z0-9]{3}" style="text-transform:uppercase" value="<?= htmlspecialchars(strtoupper(trim((string) ($pengaturan->kode_invoice ?? '')) ?: 'IMB')) ?>" class="w-full rounded border px-4 py-2 font-mono">
                    </div>
                    <div>
                      <label class="mb-2 block text-sm font-medium" for="kode_kwitansi">Kode Kwitansi</label>
                      <input type="text" id="kode_kwitansi" name="kode_kwitansi" maxlength="3" required pattern="[A-Za-z0-9]{3}" style="text-transform:uppercase" value="<?= htmlspecialchars(strtoupper(trim((string) ($pengaturan->kode_kwitansi ?? '')) ?: 'MBP')) ?>" class="w-full rounded border px-4 py-2 font-mono">
                    </div>
                  </div>
                  <?php else: ?>
                  <p class="text-sm text-red-600">Kolom kode transaksi belum bisa dibuat di database (hak ALTER), jadi kode tetap IMB / MBP.</p>
                  <?php endif; ?>
                </div>

                <div class="mt-6 flex justify-end">
                  <button type="submit" class="w-full rounded bg-blue-500 px-6 py-2 text-white hover:bg-blue-600 sm:w-auto">Simpan Pengaturan</button>
                </div>
</form>

              <!-- ===== Nomor Rekening (boleh lebih dari satu) ===== -->
              <div id="rekening" class="mt-8 rounded-md border border-stroke p-4 sm:p-6 dark:border-strokedark">
                <h2 class="mb-1 text-xl font-bold">Nomor Rekening</h2>
                <p class="mb-4 text-sm text-body dark:text-bodydark">Dipakai di bagian "Informasi Pembayaran" pada invoice. Kalau belum ada rekening di sini, invoice memakai teks bawaan.</p>

                <?php if (!$rekening_ada): ?>
                  <div class="rounded border border-warning bg-warning bg-opacity-10 px-4 py-3 text-sm">
                    Tabel <code>rekening_website</code> belum dibuat. Jalankan migrasi <code>db/rekening_website.sql</code> dulu.
                  </div>
                <?php else: ?>
                  <form action="<?= site_url('pengaturan-website/rekening/simpan') ?>" method="post" class="mb-5 grid grid-cols-1 gap-3 md:grid-cols-4">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" name="id_rekening" value="<?= $edit_rekening ? (int) $edit_rekening->id : '' ?>">
                    <div>
                      <label class="mb-1 block text-sm">Nama Bank</label>
                      <input type="text" name="nama_bank" required maxlength="100" placeholder="Contoh: Bank Central Asia (BCA)" value="<?= $edit_rekening ? htmlspecialchars($edit_rekening->nama_bank) : '' ?>" class="w-full rounded border px-3 py-2">
                    </div>
                    <div>
                      <label class="mb-1 block text-sm">Nomor Rekening</label>
                      <input type="text" name="nomor_rekening" required maxlength="50" inputmode="numeric" placeholder="Contoh: 167-2468421" value="<?= $edit_rekening ? htmlspecialchars($edit_rekening->nomor_rekening) : '' ?>" class="w-full rounded border px-3 py-2">
                    </div>
                    <div>
                      <label class="mb-1 block text-sm">Atas Nama</label>
                      <input type="text" name="atas_nama" maxlength="150" placeholder="Contoh: PT Nadi Sukses Berkarya" value="<?= $edit_rekening ? htmlspecialchars($edit_rekening->atas_nama) : '' ?>" class="w-full rounded border px-3 py-2">
                    </div>
                    <div class="flex items-end gap-2">
                      <button type="submit" class="rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600"><?= $edit_rekening ? 'Simpan Perubahan' : '+ Tambah Rekening' ?></button>
                      <?php if ($edit_rekening): ?>
                        <a href="<?= site_url('pengaturan-website') ?>#rekening" class="rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">Batal</a>
                      <?php endif; ?>
                    </div>
                  </form>

                  <?php if (empty($rekening)): ?>
                    <p class="text-sm text-body dark:text-bodydark">Belum ada rekening.</p>
                  <?php else: ?>
                    <div class="max-w-full overflow-x-auto">
                      <table class="w-full table-auto">
                        <thead>
                          <tr class="bg-gray-2 text-left dark:bg-meta-4">
                            <th class="px-3 py-3 font-medium">No</th>
                            <th class="px-3 py-3 font-medium">Bank</th>
                            <th class="px-3 py-3 font-medium">Nomor Rekening</th>
                            <th class="px-3 py-3 font-medium">Atas Nama</th>
                            <th class="px-3 py-3 font-medium">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($rekening as $i => $r): ?>
                          <tr class="border-b border-[#eee] dark:border-strokedark">
                            <td class="px-3 py-2"><?= $i + 1 ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars($r->nama_bank) ?></td>
                            <td class="px-3 py-2 font-semibold"><?= htmlspecialchars($r->nomor_rekening) ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars($r->atas_nama) ?></td>
                            <td class="px-3 py-2">
                              <div class="flex gap-2">
                                <a href="<?= site_url('pengaturan-website') . '?edit_rekening=' . $r->id ?>#rekening" class="rounded bg-green-500 px-2 py-1 text-sm text-white hover:bg-green-600">Edit</a>
                                <form action="<?= site_url('pengaturan-website/rekening/hapus/' . $r->id) ?>" method="post" onsubmit="return confirm('Hapus rekening ini?')">
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
        </div>
      </main>
    </div>
  </div>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
</body>
</html>
