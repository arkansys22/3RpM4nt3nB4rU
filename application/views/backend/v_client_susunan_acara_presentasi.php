<?php
// Data kegiatan disiapkan sebagai array asosiatif sederhana buat dikirim ke Alpine (x-data),
// supaya slide-nya jalan murni di client tanpa reload halaman.
$acaraKe = $acara_ke ?? 1;
$acaraUrlPrefix = $acaraKe == 2 ? 'acara2/' : '';

$slides = [];
foreach ($kegiatan_list as $item) {
    $slides[] = [
        'nama_kegiatan' => $item->nama_kegiatan_display,
        'durasi'        => substr($item->durasi, 0, 5),
        'vendor_pj'     => $item->vendor_pj,
        'waktu_mulai'   => $item->waktu_mulai,
        'waktu_selesai' => $item->waktu_selesai,
        'foto'          => !empty($item->foto) ? base_url('assets/uploads/client_susunan_acara/' . $item->foto) : null,
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presentasi — <?= htmlspecialchars($clients->client_name) ?></title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      body { background: #0b0b12 !important; }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden text-white"
  x-data="{
    kategori: <?= htmlspecialchars(json_encode($clients->client_name), ENT_QUOTES, 'UTF-8') ?>,
    slides: <?= htmlspecialchars(json_encode($slides), ENT_QUOTES, 'UTF-8') ?>,
    i: -1, /* -1 = slide judul */
    get total() { return this.slides.length },
    next() { if (this.i < this.total - 1) this.i++ },
    prev() { if (this.i > -1) this.i-- },
  }"
  x-init="
    window.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowRight' || e.key === ' ') next();
      if (e.key === 'ArrowLeft') prev();
      if (e.key === 'Escape') window.location.href = '<?= site_url('clients/susunan-acara/' . $acaraUrlPrefix . $clients->id_session) ?>';
    });
  "
>

  <!-- Tombol keluar -->
  <a href="<?= site_url('clients/susunan-acara/' . $acaraUrlPrefix . $clients->id_session) ?>"
    class="fixed top-4 right-4 z-50 px-4 py-2 rounded-md bg-white/10 hover:bg-white/20 text-sm font-medium backdrop-blur">
    &times; Keluar (Esc)
  </a>

  <!-- Penghitung slide -->
  <div class="fixed top-4 left-4 z-50 px-4 py-2 rounded-md bg-white/10 text-sm font-medium backdrop-blur">
    <span x-text="i + 2"></span> / <span x-text="total + 1"></span>
  </div>

  <div class="relative h-full w-full flex items-center justify-center px-6 sm:px-16">

    <!-- Slide 0: judul -->
    <div x-show="i === -1" x-transition.opacity.duration.500ms class="text-center">
      <p class="uppercase tracking-[0.3em] text-pink-500 text-sm sm:text-base mb-4">Susunan Acara <?= $acaraKe ?></p>
      <h1 class="text-4xl sm:text-6xl font-bold" x-text="kategori"></h1>
      <p class="mt-8 text-white/50 text-sm">Tekan &rarr; atau spasi untuk mulai</p>
    </div>

    <!-- Slide per kegiatan -->
    <template x-for="(s, idx) in slides" :key="idx">
      <div x-show="i === idx" x-transition.opacity.duration.500ms
        class="w-full max-w-5xl grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-14 items-center">

        <div class="order-2 md:order-1">
          <p class="text-pink-500 font-semibold text-sm sm:text-base mb-2" x-text="'Kegiatan ' + (idx + 1) + ' dari ' + slides.length"></p>
          <template x-if="s.waktu_mulai">
            <p class="text-lg sm:text-xl font-medium text-white mb-2" x-text="'Pukul ' + s.waktu_mulai + ' – ' + s.waktu_selesai"></p>
          </template>
          <h2 class="text-xl sm:text-3xl font-bold leading-tight whitespace-pre-line" x-text="s.nama_kegiatan"></h2>

          <div class="mt-8 flex flex-col gap-4">
            <div class="flex items-center gap-3">
              <span class="px-3 py-1 rounded-full bg-white/10 text-xs uppercase tracking-wide">Durasi</span>
              <span class="text-lg sm:text-xl font-medium" x-text="s.durasi"></span>
            </div>
            <div class="flex items-center gap-3">
              <span class="px-3 py-1 rounded-full bg-white/10 text-xs uppercase tracking-wide">Vendor / PJ</span>
              <span class="text-lg sm:text-xl font-medium" x-text="s.vendor_pj"></span>
            </div>
          </div>
        </div>

        <div class="order-1 md:order-2">
          <template x-if="s.foto">
            <img :src="s.foto" alt="" class="w-full max-h-[60vh] object-cover rounded-2xl shadow-2xl">
          </template>
          <template x-if="!s.foto">
            <div class="w-full aspect-video rounded-2xl border border-dashed border-white/20 flex items-center justify-center text-white/30 text-sm">
              Tidak ada foto ilustrasi
            </div>
          </template>
        </div>
      </div>
    </template>

    <?php if (empty($slides)): ?>
    <div x-show="i > -1" class="text-center text-white/50">Belum ada kegiatan untuk klien ini.</div>
    <?php endif; ?>

  </div>

  <!-- Navigasi bawah -->
  <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3">
    <button @click="prev()" :disabled="i === -1"
      class="px-4 py-2 rounded-md bg-white/10 hover:bg-white/20 disabled:opacity-30 disabled:pointer-events-none text-sm font-medium">
      &larr; Sebelumnya
    </button>
    <button @click="next()" :disabled="i >= total - 1"
      class="px-4 py-2 rounded-md bg-primary hover:bg-opacity-90 disabled:opacity-30 disabled:pointer-events-none text-sm font-medium">
      Selanjutnya &rarr;
    </button>
  </div>

  <script src="<?php echo base_url()?>assets/backend/bundle.js"></script>
</body>
</html>
