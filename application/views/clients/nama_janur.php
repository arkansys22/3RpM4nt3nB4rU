<?php
// Nama pasangan dipecah jadi 2 bagian dari client_name (mis. "Yohanes Rachel" -> "Yohanes" & "Rachel").
$namaParts = explode(' ', trim($clients->client_name), 2);
$nama1 = $namaParts[0] ?? $clients->client_name;
$nama2 = isset($namaParts[1]) ? trim($namaParts[1]) : '';

$lokasiAcara = $clients->location;
$janurUrl = base_url('assets/backend/janur/');

// Lebar badge lokasi menyesuaikan panjang teksnya (perkiraan lebar per karakter di PHP;
// versi download PNG pakai ukuran asli lewat ctx.measureText, lebih presisi).
$badgeMinWidth = 1200;
$badgeMaxWidth = 4200;
$badgeWidth = max($badgeMinWidth, min($badgeMaxWidth, mb_strlen($lokasiAcara) * 95 + 360));
$badgeX = 2481 - ($badgeWidth / 2);
$badgeTextMaxWidth = $badgeWidth - 300;

$slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $clients->client_name), '-'));
if ($slug === '') { $slug = 'client'; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nama Janur | <?= htmlspecialchars($clients->client_name) ?></title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&display=swap" rel="stylesheet">
    <style>
      .janur-page { position: relative; width: 100%; }
      .janur-page svg { display: block; width: 100%; height: auto; }
      .janur-nama { font-family: 'Alex Brush', cursive; }
      .janur-info { font-family: Georgia, 'Times New Roman', serif; font-weight: 700; }
    </style>
</head>
<body
    x-data="{ page: 'clients', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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

              <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
                <h1 class="text-2xl font-bold">Nama Janur — <?= htmlspecialchars($clients->client_name) ?></h1>
                <a href="<?= site_url('clients/lihat/'.$clients->id_session) ?>"
                  class="w-full md:w-auto text-center px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">
                  &larr; Kembali
                </a>
              </div>

              <p class="text-sm text-body dark:text-bodydark mb-6">
                Preview desain nama janur untuk client ini. Nama dan lokasi acara diambil otomatis dari data client
                — file gambar dasarnya tetap resolusi aslinya (tidak di-resize), tampilan di layar cuma diperkecil secara responsif.
              </p>

              <div>
                <div class="flex items-center justify-between mb-3">
                  <h3 class="text-lg font-semibold text-black dark:text-white">Nama Janur</h3>
                  <button type="button" onclick="downloadNamaJanur(this)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-md shadow hover:bg-opacity-90 text-sm font-medium">
                    Download PNG
                  </button>
                </div>
                <div class="janur-page mx-auto max-w-3xl border border-stroke dark:border-strokedark rounded-sm overflow-hidden">
                  <svg viewBox="0 0 4961 3508" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                      <linearGradient id="janurGold" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#c9a15b" />
                        <stop offset="50%" stop-color="#a9823f" />
                        <stop offset="100%" stop-color="#8a6a35" />
                      </linearGradient>
                    </defs>
                    <image href="<?= $janurUrl ?>nama_janur-01.png" x="0" y="0" width="4961" height="3508" />

                    <!-- tutup nama & badge lokasi contoh -->
                    <rect x="500" y="0" width="3900" height="2280" fill="#ffffff" />
                    <rect x="331" y="2280" width="4300" height="400" fill="#ffffff" />

                    <text x="2481" y="900" text-anchor="middle" textLength="3600" lengthAdjust="spacingAndGlyphs"
                      class="janur-nama" font-size="1150" fill="url(#janurGold)"><?= htmlspecialchars($nama1) ?></text>
                    <?php if ($nama2 !== ''): ?>
                    <text x="2481" y="1800" text-anchor="middle" textLength="3600" lengthAdjust="spacingAndGlyphs"
                      class="janur-nama" font-size="1150" fill="url(#janurGold)"><tspan font-size="730" dx="80" dy="-120">&amp;</tspan><tspan dx="-80" dy="120"> <?= htmlspecialchars($nama2) ?></tspan></text>
                    <?php endif; ?>

                    <rect x="<?= $badgeX ?>" y="2338" width="<?= $badgeWidth ?>" height="282" rx="60" fill="#f16e8d" />
                    <text x="2481" y="2515" text-anchor="middle" textLength="<?= $badgeTextMaxWidth ?>" lengthAdjust="spacingAndGlyphs"
                      class="janur-info" font-size="140" fill="#ffffff"><?= htmlspecialchars($lokasiAcara) ?></text>
                  </svg>
                </div>
              </div>

            </div>
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <script src="<?php echo base_url()?>assets/backend/bundle.js"></script>
  <script>
    const NAMA_JANUR = {
      img: <?= json_encode($janurUrl . 'nama_janur-01.png') ?>,
      filename: <?= json_encode('nama-janur-' . $slug . '.png') ?>,
      texts: [
        { text: <?= json_encode($nama1) ?>, x: 2481, y: 900, size: 1150, family: "'Alex Brush'", fill: 'gold', maxWidth: 3600 },
        <?php if ($nama2 !== ''): ?>
        { segments: [{ text: '&', size: 730, dy: -120, dx: 80 }, { text: <?= json_encode(' ' . $nama2) ?>, size: 1150 }], x: 2481, y: 1800, family: "'Alex Brush'", fill: 'gold', maxWidth: 3600 },
        <?php endif; ?>
      ],
      badge: { y: 2338, height: 282, rx: 60, fill: '#f16e8d', centerX: 2481, minWidth: 1200, maxWidth: 4200, padding: 300 },
      badgeText: { text: <?= json_encode($lokasiAcara) ?>, y: 2515, size: 140, family: "Georgia, 'Times New Roman', serif", weight: 'bold', fill: '#ffffff' }
    };

    async function downloadNamaJanur(btn) {
      const cfg = NAMA_JANUR;
      const originalLabel = btn ? btn.textContent : null;
      if (btn) { btn.disabled = true; btn.textContent = 'Menyiapkan...'; }

      try {
        await Promise.all(cfg.texts.flatMap(t => {
          if (t.segments) {
            return t.segments.map(seg => document.fonts.load(`${seg.size}px ${t.family}`.trim(), seg.text || 'A'));
          }
          return [document.fonts.load(`${t.size}px ${t.family}`.trim(), t.text || 'A')];
        }).concat([document.fonts.load(`${cfg.badgeText.weight || ''} ${cfg.badgeText.size}px ${cfg.badgeText.family}`.trim(), cfg.badgeText.text || 'A')]));
        await document.fonts.ready;

        const img = new Image();
        await new Promise((resolve, reject) => {
          img.onload = resolve;
          img.onerror = () => reject(new Error('Gagal memuat gambar dasar: ' + cfg.img));
          img.src = cfg.img;
        });

        const canvas = document.createElement('canvas');
        canvas.width = 4961;
        canvas.height = 3508;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

        const goldFillFor = (x0, x1, size, dy) => {
          dy = dy || 0;
          const grad = ctx.createLinearGradient(x0, dy - size, x1, dy);
          grad.addColorStop(0, '#c9a15b');
          grad.addColorStop(0.5, '#a9823f');
          grad.addColorStop(1, '#8a6a35');
          return grad;
        };

        cfg.texts.forEach(t => {
          ctx.textBaseline = 'alphabetic';

          if (t.segments) {
            const widths = t.segments.map(seg => {
              ctx.font = `${seg.size}px ${t.family}`.trim();
              return ctx.measureText(seg.text).width;
            });
            const totalWidth = widths.reduce((a, b) => a + b, 0);
            const scaleX = totalWidth > 0 ? Math.min(1, t.maxWidth / totalWidth) : 1;

            ctx.save();
            ctx.translate(t.x, t.y);
            ctx.scale(scaleX, 1);
            ctx.textAlign = 'left';

            let cursor = -totalWidth / 2;
            t.segments.forEach((seg, i) => {
              const drawX = cursor + (seg.dx || 0);
              ctx.font = `${seg.size}px ${t.family}`.trim();
              ctx.fillStyle = (t.fill === 'gold') ? goldFillFor(drawX, drawX + widths[i], seg.size, seg.dy) : t.fill;
              ctx.fillText(seg.text, drawX, seg.dy || 0);
              cursor += widths[i];
            });
            ctx.restore();
            return;
          }

          if (!t.text) return;
          ctx.font = `${t.size}px ${t.family}`.trim();
          ctx.textAlign = 'center';

          const measured = ctx.measureText(t.text).width;
          const scaleX = measured > 0 ? Math.min(1, t.maxWidth / measured) : 1;

          ctx.save();
          ctx.translate(t.x, t.y);
          ctx.scale(scaleX, 1);
          ctx.fillStyle = (t.fill === 'gold') ? goldFillFor(-measured / 2, measured / 2, t.size) : t.fill;
          ctx.fillText(t.text, 0, 0);
          ctx.restore();
        });

        // badge lokasi (kotak pink rounded, lebar menyesuaikan panjang teks lokasi + teks putih)
        const b = cfg.badge;
        const bt = cfg.badgeText;
        ctx.font = `${bt.weight || ''} ${bt.size}px ${bt.family}`.trim();
        const measured = bt.text ? ctx.measureText(bt.text).width : 0;
        const badgeWidth = Math.max(b.minWidth, Math.min(b.maxWidth, measured + b.padding));
        const badgeX = b.centerX - badgeWidth / 2;

        ctx.beginPath();
        ctx.moveTo(badgeX + b.rx, b.y);
        ctx.arcTo(badgeX + badgeWidth, b.y, badgeX + badgeWidth, b.y + b.height, b.rx);
        ctx.arcTo(badgeX + badgeWidth, b.y + b.height, badgeX, b.y + b.height, b.rx);
        ctx.arcTo(badgeX, b.y + b.height, badgeX, b.y, b.rx);
        ctx.arcTo(badgeX, b.y, badgeX + badgeWidth, b.y, b.rx);
        ctx.closePath();
        ctx.fillStyle = b.fill;
        ctx.fill();

        if (bt.text) {
          ctx.textAlign = 'center';
          ctx.textBaseline = 'alphabetic';
          const textMaxWidth = badgeWidth - b.padding;
          const scaleX = measured > 0 ? Math.min(1, textMaxWidth / measured) : 1;
          ctx.save();
          ctx.translate(b.centerX, bt.y);
          ctx.scale(scaleX, 1);
          ctx.fillStyle = bt.fill;
          ctx.fillText(bt.text, 0, 0);
          ctx.restore();
        }

        await new Promise(resolve => {
          canvas.toBlob(blob => {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = cfg.filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
            resolve();
          }, 'image/png');
        });
      } catch (err) {
        console.error(err);
        alert('Gagal membuat file PNG. Coba lagi.');
      } finally {
        if (btn) { btn.disabled = false; btn.textContent = originalLabel; }
      }
    }
  </script>
</body>
</html>
