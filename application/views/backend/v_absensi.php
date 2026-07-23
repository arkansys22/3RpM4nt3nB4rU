<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Kehadiran - <?= date('F Y', strtotime($periode . '-01')) ?></title>
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
                <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Absensi Kehadiran</h1>
                <div class="flex items-center gap-2">
                  <?php if (!empty($bisa_atur_absensi)): ?>
                    <a href="<?= site_url('absensi-rekap') ?>" class="flex items-center gap-2 bg-gray-500 text-white px-4 py-3 rounded-md hover:bg-gray-600 focus:outline-none text-sm">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008ZM10.5 11.25h.008v.008H10.5v-.008Zm2.25 0h.008v.008H12.75v-.008Zm2.25 0h.008v.008H15v-.008Zm2.25 0h.008v.008H17.25v-.008ZM4.5 4.5v15a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V4.5A1.5 1.5 0 0 0 19.5 3H6A1.5 1.5 0 0 0 4.5 4.5Zm3.75-1.5v3m9-3v3" />
                      </svg>
                      Rekap Semua User
                    </a>
                    <a href="<?= site_url('absensi-pengaturan') ?>" class="flex items-center gap-2 bg-gray-500 text-white px-4 py-3 rounded-md hover:bg-gray-600 focus:outline-none text-sm">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                      </svg>
                      Pengaturan
                    </a>
                  <?php endif; ?>
                  <!-- Tombol Kembali -->
                  <a href="<?= site_url('panel') ?>" class="flex items-center gap-2 bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                  </a>
                </div>
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                  Jam masuk ketentuan: <span class="font-semibold"><?= substr($jam_masuk_ketentuan, 0, 5) ?></span>
                </p>

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

                <!-- Absen hari ini -->
                <div class="mb-6 p-5 rounded-lg bg-gray-50 dark:bg-neutral-700 flex flex-wrap items-center justify-between gap-4">
                  <div class="flex items-center gap-4">
                    <?php if (!empty($absen_hari_ini) && !empty($absen_hari_ini->foto_masuk)): ?>
                      <img src="<?= base_url('uploads/absensi/' . $absen_hari_ini->foto_masuk) ?>" alt="Foto absen masuk" class="w-16 h-16 object-cover rounded-md border border-stroke dark:border-strokedark">
                    <?php endif; ?>
                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-300">Hari ini, <?= hari(date('Y-m-d')) ?> <?= date('d F Y') ?></p>
                      <?php if (empty($absen_hari_ini)): ?>
                        <p class="text-gray-700 dark:text-gray-200 font-medium">Anda belum melakukan absen masuk.</p>
                      <?php elseif ($absen_hari_ini->status !== 'Hadir'): ?>
                        <p class="text-yellow-600 dark:text-yellow-400 font-semibold"><?= $absen_hari_ini->status ?></p>
                        <?php if (!empty($absen_hari_ini->keterangan)): ?>
                          <p class="text-gray-700 dark:text-gray-200 text-sm"><?= $absen_hari_ini->keterangan ?></p>
                        <?php endif; ?>
                      <?php else: ?>
                        <p class="text-gray-700 dark:text-gray-200 font-medium">
                          Jam Masuk: <span class="font-semibold"><?= date('H:i', strtotime($absen_hari_ini->jam_masuk)) ?></span>
                          <?php if (!empty($absen_hari_ini->jam_masuk_ketentuan)): ?>
                            <span class="text-xs text-gray-500 dark:text-gray-400">(ketentuan <?= substr($absen_hari_ini->jam_masuk_ketentuan, 0, 5) ?>)</span>
                          <?php endif; ?>
                          <?php if (!empty($absen_hari_ini->jam_keluar)): ?>
                            &nbsp;|&nbsp; Jam Keluar: <span class="font-semibold"><?= date('H:i', strtotime($absen_hari_ini->jam_keluar)) ?></span>
                          <?php endif; ?>
                        </p>
                        <?php if (!empty($absen_hari_ini->keterangan)): ?>
                          <p class="text-red-500 text-sm font-semibold"><?= $absen_hari_ini->keterangan ?></p>
                        <?php endif; ?>
                      <?php endif; ?>
                    </div>
                  </div>

                  <div class="flex gap-3">
                    <?php if (empty($absen_hari_ini)): ?>
                      <button type="button" onclick="bukaKameraAbsensi('masuk')" class="bg-green-500 text-white px-5 py-2.5 rounded-md hover:bg-green-600 font-semibold">
                        Absen Masuk
                      </button>
                      <button type="button" onclick="bukaFormIzinAbsensi()" class="bg-yellow-500 text-white px-5 py-2.5 rounded-md hover:bg-yellow-600 font-semibold">
                        Tidak Masuk (Sakit/Izin)
                      </button>
                    <?php elseif ($absen_hari_ini->status !== 'Hadir'): ?>
                      <span class="text-sm text-gray-500 dark:text-gray-300">Absensi hari ini sudah dicatat.</span>
                    <?php elseif (empty($absen_hari_ini->jam_keluar)): ?>
                      <button type="button" onclick="bukaKameraAbsensi('keluar')" class="bg-[#ed126b] text-white px-5 py-2.5 rounded-md hover:bg-pink-800 font-semibold">
                        Absen Keluar
                      </button>
                    <?php else: ?>
                      <span class="text-sm text-gray-500 dark:text-gray-300">Absensi hari ini sudah selesai.</span>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- Form Sakit/Izin (disembunyikan sampai tombol "Tidak Masuk" diklik) -->
                <?php if (empty($absen_hari_ini)): ?>
                  <div id="form-izin-wrapper" class="hidden mb-6 p-5 rounded-lg bg-gray-50 dark:bg-neutral-700">
                    <form method="post" action="<?= site_url('absensi/izin') ?>">
                      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                      <div class="mb-4">
                        <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Status</label>
                        <select name="status" required class="w-full sm:w-60 rounded-md border border-stroke dark:border-strokedark px-3 py-2 dark:bg-boxdark dark:text-white">
                          <option value="Sakit">Sakit</option>
                          <option value="Izin">Izin</option>
                        </select>
                      </div>
                      <div class="mb-4">
                        <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Keterangan</label>
                        <textarea name="keterangan" required rows="3" placeholder="Jelaskan alasan tidak masuk..." class="w-full rounded-md border border-stroke dark:border-strokedark px-3 py-2 dark:bg-boxdark dark:text-white"></textarea>
                      </div>
                      <div class="flex gap-3">
                        <button type="submit" class="bg-yellow-500 text-white px-5 py-2.5 rounded-md hover:bg-yellow-600 font-semibold">
                          Simpan
                        </button>
                        <button type="button" onclick="tutupFormIzinAbsensi()" class="bg-gray-400 text-white px-5 py-2.5 rounded-md hover:bg-gray-500 font-semibold">
                          Batal
                        </button>
                      </div>
                    </form>
                  </div>
                <?php endif; ?>

                <!-- Navigasi bulan -->
                <div class="flex items-center justify-between mb-4 gap-3">
                  <a href="<?= site_url('absensi/' . $periode_sebelumnya) ?>" class="bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-md hover:bg-gray-300 dark:hover:bg-neutral-600">
                    &laquo; <?= date('F Y', strtotime($periode_sebelumnya . '-01')) ?>
                  </a>

                  <form method="get" onsubmit="return false;">
                    <input
                      type="month"
                      value="<?= $periode ?>"
                      class="border border-stroke dark:border-strokedark rounded-md px-3 py-2 dark:bg-boxdark dark:text-white"
                      onchange="window.location.href = '<?= site_url('absensi/') ?>' + this.value"
                    >
                  </form>

                  <a href="<?= site_url('absensi/' . $periode_berikutnya) ?>" class="bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-md hover:bg-gray-300 dark:hover:bg-neutral-600">
                    <?= date('F Y', strtotime($periode_berikutnya . '-01')) ?> &raquo;
                  </a>
                </div>

                <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">
                  Total kehadiran bulan <?= date('F Y', strtotime($periode . '-01')) ?>: <span class="font-semibold"><?= count($riwayat_absensi) ?> hari</span>
                </p>

                <div class="overflow-x-auto bg-white dark:bg-neutral-700 rounded-lg shadow-md">
                  <table class="min-w-full text-left text-sm whitespace-nowrap bg-white dark:bg-neutral-800">
                    <thead class="uppercase tracking-wider border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
                      <tr>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Tanggal</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Hari</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Status</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Jam Masuk</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Foto/Lokasi Masuk</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Jam Keluar</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Foto/Lokasi Keluar</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($riwayat_absensi)): ?>
                        <tr class="border-b border-gray-200 dark:border-neutral-600">
                          <td colspan="7" class="px-6 py-4 text-gray-700 dark:text-gray-400 text-center">
                            Belum ada riwayat absensi pada bulan ini.
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($riwayat_absensi as $r): ?>
                          <tr class="border-b border-gray-200 dark:border-neutral-600">
                            <th scope="row" class="px-6 py-4 text-gray-900 dark:text-gray-200">
                              <?= date('d-m-Y', strtotime($r->tanggal)) ?>
                            </th>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <?= hari($r->tanggal) ?>
                            </td>
                            <td class="px-6 py-4 <?= $r->status === 'Hadir' ? 'text-gray-700 dark:text-gray-400' : 'text-yellow-600 dark:text-yellow-400 font-semibold' ?>">
                              <?= $r->status ?>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <?= !empty($r->jam_masuk) ? date('H:i', strtotime($r->jam_masuk)) : '-' ?>
                              <?php if (!empty($r->jam_masuk_ketentuan)): ?>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block">ketentuan <?= substr($r->jam_masuk_ketentuan, 0, 5) ?></span>
                              <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <?php if (!empty($r->foto_masuk)): ?>
                                <a href="<?= base_url('uploads/absensi/' . $r->foto_masuk) ?>" target="_blank">
                                  <img src="<?= base_url('uploads/absensi/' . $r->foto_masuk) ?>" alt="Foto masuk" class="w-10 h-10 object-cover rounded-md inline-block">
                                </a>
                                <?php if (!empty($r->lat_masuk) && !empty($r->lng_masuk)): ?>
                                  <a href="https://www.google.com/maps?q=<?= $r->lat_masuk ?>,<?= $r->lng_masuk ?>" target="_blank" class="text-blue-500 hover:underline text-xs block">Lihat Lokasi</a>
                                <?php endif; ?>
                                <?php if (!empty($r->alamat_masuk)): ?>
                                  <span class="text-xs text-gray-500 dark:text-gray-400 block"><?= $r->alamat_masuk ?></span>
                                <?php endif; ?>
                              <?php else: ?>
                                -
                              <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <?= !empty($r->jam_keluar) ? date('H:i', strtotime($r->jam_keluar)) : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <?php if (!empty($r->foto_keluar)): ?>
                                <a href="<?= base_url('uploads/absensi/' . $r->foto_keluar) ?>" target="_blank">
                                  <img src="<?= base_url('uploads/absensi/' . $r->foto_keluar) ?>" alt="Foto keluar" class="w-10 h-10 object-cover rounded-md inline-block">
                                </a>
                                <?php if (!empty($r->lat_keluar) && !empty($r->lng_keluar)): ?>
                                  <a href="https://www.google.com/maps?q=<?= $r->lat_keluar ?>,<?= $r->lng_keluar ?>" target="_blank" class="text-blue-500 hover:underline text-xs block">Lihat Lokasi</a>
                                <?php endif; ?>
                                <?php if (!empty($r->alamat_keluar)): ?>
                                  <span class="text-xs text-gray-500 dark:text-gray-400 block"><?= $r->alamat_keluar ?></span>
                                <?php endif; ?>
                              <?php else: ?>
                                -
                              <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 <?= !empty($r->keterangan) ? 'text-red-500 font-semibold' : 'text-gray-700 dark:text-gray-400' ?>">
                              <?= !empty($r->keterangan) ? $r->keterangan : '-' ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
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
  <!-- ===== Modal Kamera Absensi Start ===== -->
  <div id="camera-modal" class="fixed inset-0 z-999999 hidden items-center justify-center bg-black/70 p-4">
    <div class="bg-white dark:bg-neutral-800 rounded-lg p-4 max-w-md w-full">
      <h2 id="camera-modal-title" class="text-lg font-semibold mb-3 text-gray-800 dark:text-white"></h2>
      <p id="camera-status" class="text-sm text-gray-500 dark:text-gray-300 mb-2"></p>

      <video id="camera-video" autoplay playsinline muted class="w-full rounded-md bg-black hidden"></video>
      <canvas id="camera-canvas" class="w-full rounded-md hidden"></canvas>

      <div class="flex gap-3 mt-4">
        <button type="button" id="btn-ambil-foto" class="hidden flex-1 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">Ambil Foto</button>
        <button type="button" id="btn-ulangi-foto" class="hidden flex-1 bg-gray-400 text-white py-2 rounded-md hover:bg-gray-500">Ambil Ulang</button>
        <button type="button" id="btn-kirim-foto" class="hidden flex-1 bg-green-500 text-white py-2 rounded-md hover:bg-green-600">Kirim</button>
        <button type="button" id="btn-batal-foto" class="flex-1 bg-red-500 text-white py-2 rounded-md hover:bg-red-600">Batal</button>
      </div>
    </div>
  </div>
  <!-- ===== Modal Kamera Absensi End ===== -->

  <!-- Form tersembunyi, disubmit oleh JS setelah foto+lokasi siap -->
  <form id="form-absensi" method="post" class="hidden">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <input type="hidden" name="foto" id="input-foto-absensi">
    <input type="hidden" name="lat" id="input-lat-absensi">
    <input type="hidden" name="lng" id="input-lng-absensi">
    <input type="hidden" name="alamat" id="input-alamat-absensi">
  </form>

  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
  <script>
    var absensiCameraStream = null;
    var absensiCurrentAction = null;
    var absensiCurrentCoords = null;
    var absensiCurrentAlamat = null;
    var absensiUrlMasuk = '<?= site_url('absensi/masuk') ?>';
    var absensiUrlKeluar = '<?= site_url('absensi/keluar') ?>';

    var absensiLokasiSiap = false;
    var absensiKameraSiap = false;

    // Cari nama kecamatan/kabupaten dari titik koordinat lewat reverse-geocode
    // gratis (OpenStreetMap Nominatim, tanpa API key). Best-effort: dikasih
    // batas waktu sendiri supaya kalau layanannya lambat/down, absen tetap
    // bisa lanjut pakai koordinat mentah saja (tanpa nama daerah).
    function absensiAmbilAlamat(lat, lng, callback) {
      var selesai = false;
      var selesaikan = function (alamat) {
        if (selesai) return;
        selesai = true;
        callback(alamat);
      };

      setTimeout(function () { selesaikan(null); }, 6000);

      fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=16&addressdetails=1&accept-language=id')
        .then(function (res) { return res.json(); })
        .then(function (data) {
          selesaikan(absensiFormatAlamat((data && data.address) ? data.address : {}));
        })
        .catch(function () {
          selesaikan(null);
        });
    }

    function absensiFormatAlamat(a) {
      var kecamatan = a.city_district || a.suburb || a.subdistrict || a.district || '';
      var kabkota = a.city || a.county || a.regency || a.town || '';
      var provinsi = a.state || '';
      var bagian = [kecamatan, kabkota, provinsi].filter(function (v) { return v; });
      return bagian.length ? bagian.join(', ') : null;
    }

    // Tombol "Ambil Foto" hanya boleh muncul kalau lokasi SUDAH didapat DAN
    // frame video kamera sudah punya dimensi valid — kalau video belum
    // sempat "loadedmetadata", videoWidth/videoHeight-nya masih 0, hasil
    // capture jadi kosong (makanya overlay lokasi kelihatan tidak muncul).
    function absensiCekSiapAmbilFoto() {
      var status = document.getElementById('camera-status');
      if (absensiLokasiSiap && absensiKameraSiap) {
        document.getElementById('btn-ambil-foto').classList.remove('hidden');
        status.textContent = 'Lokasi & kamera siap. Arahkan kamera lalu klik Ambil Foto.';
      } else if (absensiKameraSiap && !absensiLokasiSiap) {
        status.textContent = 'Kamera siap, menunggu lokasi...';
      } else if (absensiLokasiSiap && !absensiKameraSiap) {
        status.textContent = 'Lokasi didapat, menunggu kamera...';
      }
    }

    function bukaKameraAbsensi(action) {
      absensiCurrentAction = action;
      absensiCurrentCoords = null;
      absensiCurrentAlamat = null;
      absensiLokasiSiap = false;
      absensiKameraSiap = false;

      var modal = document.getElementById('camera-modal');
      var title = document.getElementById('camera-modal-title');
      var status = document.getElementById('camera-status');
      var video = document.getElementById('camera-video');
      var canvas = document.getElementById('camera-canvas');

      title.textContent = action === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
      status.textContent = 'Mengaktifkan kamera & mengambil lokasi...';
      video.classList.remove('hidden');
      canvas.classList.add('hidden');
      document.getElementById('btn-ambil-foto').classList.add('hidden');
      document.getElementById('btn-ulangi-foto').classList.add('hidden');
      document.getElementById('btn-kirim-foto').classList.add('hidden');

      modal.classList.remove('hidden');
      modal.classList.add('flex');

      if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        status.textContent = 'Perangkat/browser ini tidak mendukung akses kamera.';
        return;
      }
      if (!navigator.geolocation) {
        status.textContent = 'Perangkat/browser ini tidak mendukung akses lokasi.';
        return;
      }

      navigator.geolocation.getCurrentPosition(function (pos) {
        absensiCurrentCoords = { lat: pos.coords.latitude, lng: pos.coords.longitude };
        status.textContent = 'Lokasi didapat, mencari nama daerah...';

        absensiAmbilAlamat(pos.coords.latitude, pos.coords.longitude, function (alamat) {
          absensiCurrentAlamat = alamat;
          absensiLokasiSiap = true;
          absensiCekSiapAmbilFoto();
        });
      }, function () {
        status.textContent = 'Gagal mengambil lokasi. Izinkan akses lokasi lalu coba lagi.';
      }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });

      video.onloadedmetadata = function () {
        video.play();
        absensiKameraSiap = true;
        absensiCekSiapAmbilFoto();
      };

      navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
        .then(function (stream) {
          absensiCameraStream = stream;
          video.srcObject = stream;
        })
        .catch(function () {
          status.textContent = 'Gagal mengakses kamera. Izinkan akses kamera lalu coba lagi.';
        });
    }

    function hentikanKameraAbsensi() {
      if (absensiCameraStream) {
        absensiCameraStream.getTracks().forEach(function (t) { t.stop(); });
        absensiCameraStream = null;
      }
    }

    function tutupKameraAbsensi() {
      var modal = document.getElementById('camera-modal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      hentikanKameraAbsensi();
    }

    function ambilFotoAbsensi() {
      var status = document.getElementById('camera-status');
      if (!absensiCurrentCoords) {
        status.textContent = 'Lokasi belum didapat, tunggu sebentar lalu coba lagi.';
        return;
      }

      var video = document.getElementById('camera-video');
      var canvas = document.getElementById('camera-canvas');
      if (!video.videoWidth || !video.videoHeight) {
        status.textContent = 'Kamera belum siap, tunggu sebentar lalu coba lagi.';
        return;
      }
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      var ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      var waktu = new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'medium' });
      var baris = [waktu];
      if (absensiCurrentAlamat) {
        baris.push(absensiCurrentAlamat);
      }
      baris.push('Lat: ' + absensiCurrentCoords.lat.toFixed(6) + ', Lng: ' + absensiCurrentCoords.lng.toFixed(6));

      var fontSize = Math.max(14, Math.round(canvas.width / 32));
      var lineHeight = fontSize + 6;
      var padding = 10;
      var boxHeight = baris.length * lineHeight + padding * 2;

      ctx.fillStyle = 'rgba(0,0,0,0.55)';
      ctx.fillRect(0, canvas.height - boxHeight, canvas.width, boxHeight);

      ctx.font = fontSize + 'px sans-serif';
      ctx.fillStyle = '#ffffff';
      baris.forEach(function (line, i) {
        ctx.fillText(line, padding, canvas.height - boxHeight + padding + fontSize + i * lineHeight);
      });

      video.classList.add('hidden');
      canvas.classList.remove('hidden');
      document.getElementById('btn-ambil-foto').classList.add('hidden');
      document.getElementById('btn-ulangi-foto').classList.remove('hidden');
      document.getElementById('btn-kirim-foto').classList.remove('hidden');
      status.textContent = 'Foto siap dikirim.';

      hentikanKameraAbsensi();
    }

    function ambilUlangFotoAbsensi() {
      bukaKameraAbsensi(absensiCurrentAction);
    }

    function kirimFotoAbsensi() {
      var canvas = document.getElementById('camera-canvas');
      var dataUrl = canvas.toDataURL('image/jpeg', 0.85);

      var form = document.getElementById('form-absensi');
      form.action = absensiCurrentAction === 'masuk' ? absensiUrlMasuk : absensiUrlKeluar;
      document.getElementById('input-foto-absensi').value = dataUrl;
      document.getElementById('input-lat-absensi').value = absensiCurrentCoords.lat;
      document.getElementById('input-lng-absensi').value = absensiCurrentCoords.lng;
      document.getElementById('input-alamat-absensi').value = absensiCurrentAlamat || '';
      form.submit();
    }

    document.getElementById('btn-ambil-foto').addEventListener('click', ambilFotoAbsensi);
    document.getElementById('btn-ulangi-foto').addEventListener('click', ambilUlangFotoAbsensi);
    document.getElementById('btn-kirim-foto').addEventListener('click', kirimFotoAbsensi);
    document.getElementById('btn-batal-foto').addEventListener('click', tutupKameraAbsensi);

    function bukaFormIzinAbsensi() {
      var wrapper = document.getElementById('form-izin-wrapper');
      if (wrapper) {
        wrapper.classList.remove('hidden');
        wrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    }

    function tutupFormIzinAbsensi() {
      var wrapper = document.getElementById('form-izin-wrapper');
      if (wrapper) {
        wrapper.classList.add('hidden');
      }
    }
  </script>
</body>
</html>
