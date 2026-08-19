<?php
// Nama pasangan dipecah jadi 2 bagian dari client_name (mis. "Tiara Hafidz" -> "Tiara" & "Hafidz").
// Kalau cuma satu kata, bagian kedua dikosongkan dan tampilan menyesuaikan (tanpa tanda "&").
$namaParts = explode(' ', trim($clients->client_name), 2);
$nama1 = $namaParts[0] ?? $clients->client_name;
$nama2 = isset($namaParts[1]) ? trim($namaParts[1]) : '';

$namaSatuBaris = $nama2 !== '' ? ($nama1 . ' & ' . $nama2) : $nama1;
$tanggalAcara = trim(hari($clients->wedding_date) . ', ' . tgl_indo($clients->wedding_date));
$lokasiAcara = $clients->location;

$bukuTamuUrl = base_url('assets/backend/buku_tamu/');

$slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $clients->client_name), '-'));
if ($slug === '') { $slug = 'client'; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu | <?= htmlspecialchars($clients->client_name) ?></title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&display=swap" rel="stylesheet">
    <style>
      .buku-tamu-page { position: relative; width: 100%; }
      .buku-tamu-page svg { display: block; width: 100%; height: auto; }
      .buku-tamu-nama { font-family: 'Alex Brush', cursive; }
      .buku-tamu-info { font-family: Georgia, 'Times New Roman', serif; font-weight: 700; }
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
                <h1 class="text-2xl font-bold">Buku Tamu — <?= htmlspecialchars($clients->client_name) ?></h1>
                <a href="<?= site_url('clients/lihat/'.$clients->id_session) ?>"
                  class="w-full md:w-auto text-center px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">
                  &larr; Kembali
                </a>
              </div>

              <p class="text-sm text-body dark:text-bodydark mb-6">
                Preview desain buku tamu untuk client ini. Nama, tanggal, dan lokasi acara diambil otomatis dari data client
                — file gambar dasarnya tetap resolusi aslinya (tidak di-resize), tampilan di layar cuma diperkecil secara responsif.
              </p>

              <div class="flex flex-col gap-10">

                <!-- ============ TAMPAK DEPAN ============ -->
                <div>
                  <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-black dark:text-white">Tampak Depan</h3>
                    <button type="button" onclick="downloadBukuTamu('depan', this)"
                      class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-md shadow hover:bg-opacity-90 text-sm font-medium">
                      Download PNG
                    </button>
                  </div>
                  <div class="buku-tamu-page mx-auto max-w-3xl border border-stroke dark:border-strokedark rounded-sm overflow-hidden">
                    <svg viewBox="0 0 3508 2481" xmlns="http://www.w3.org/2000/svg">
                      <defs>
                        <linearGradient id="gold1" x1="0%" y1="0%" x2="100%" y2="100%">
                          <stop offset="0%" stop-color="#c9a15b" />
                          <stop offset="50%" stop-color="#a9823f" />
                          <stop offset="100%" stop-color="#8a6a35" />
                        </linearGradient>
                      </defs>
                      <image href="<?= $bukuTamuUrl ?>tampak_depan.png" x="0" y="0" width="3508" height="2481" />

                      <text x="1120" y="1220" text-anchor="middle" textLength="1200" lengthAdjust="spacingAndGlyphs"
                        class="buku-tamu-nama" font-size="300" fill="url(#gold1)"><?= htmlspecialchars($nama1) ?></text>
                      <?php if ($nama2 !== ''): ?>
                      <text x="1120" y="1480" text-anchor="middle" textLength="1200" lengthAdjust="spacingAndGlyphs"
                        class="buku-tamu-nama" font-size="300" fill="url(#gold1)"><tspan font-size="200" dy="-60">&amp;</tspan><tspan dy="60"> <?= htmlspecialchars($nama2) ?></tspan></text>
                      <?php endif; ?>

                      <text x="3346" y="1900" text-anchor="end" textLength="1050" lengthAdjust="spacingAndGlyphs"
                        class="buku-tamu-info" font-size="65" fill="#ed126b"><?= htmlspecialchars($tanggalAcara) ?></text>
                      <text x="3346" y="2010" text-anchor="end" textLength="1050" lengthAdjust="spacingAndGlyphs"
                        class="buku-tamu-info" font-size="65" fill="#ed126b"><?= htmlspecialchars($lokasiAcara) ?></text>
                    </svg>
                  </div>
                </div>

                <!-- ============ TAMPAK ISI ============ -->
                <div>
                  <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-black dark:text-white">Tampak Isi</h3>
                    <button type="button" onclick="downloadBukuTamu('isi', this)"
                      class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-md shadow hover:bg-opacity-90 text-sm font-medium">
                      Download PNG
                    </button>
                  </div>
                  <div class="buku-tamu-page mx-auto max-w-3xl border border-stroke dark:border-strokedark rounded-sm overflow-hidden">
                    <svg viewBox="0 0 3508 2481" xmlns="http://www.w3.org/2000/svg">
                      <image href="<?= $bukuTamuUrl ?>tampak_isi.png" x="0" y="0" width="3508" height="2481" />

                      <text x="1754" y="1330" text-anchor="middle" textLength="2200" lengthAdjust="spacingAndGlyphs"
                        class="buku-tamu-nama" font-size="240" fill="#d6d6d6"><?= htmlspecialchars($namaSatuBaris) ?></text>
                    </svg>
                  </div>
                </div>

                <!-- ============ TAMPAK BELAKANG ============ -->
                <div>
                  <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-black dark:text-white">Tampak Belakang</h3>
                    <button type="button" onclick="downloadBukuTamu('belakang', this)"
                      class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-md shadow hover:bg-opacity-90 text-sm font-medium">
                      Download PNG
                    </button>
                  </div>
                  <div class="buku-tamu-page mx-auto max-w-3xl border border-stroke dark:border-strokedark rounded-sm overflow-hidden">
                    <svg viewBox="0 0 3508 2481" xmlns="http://www.w3.org/2000/svg">
                      <defs>
                        <linearGradient id="gold2" x1="0%" y1="0%" x2="100%" y2="100%">
                          <stop offset="0%" stop-color="#c9a15b" />
                          <stop offset="50%" stop-color="#a9823f" />
                          <stop offset="100%" stop-color="#8a6a35" />
                        </linearGradient>
                      </defs>
                      <image href="<?= $bukuTamuUrl ?>tampak_belakang.png" x="0" y="0" width="3508" height="2481" />

                      <text x="1754" y="1280" text-anchor="middle" textLength="1550" lengthAdjust="spacingAndGlyphs"
                        class="buku-tamu-nama" font-size="280" fill="url(#gold2)"><?= htmlspecialchars($namaSatuBaris) ?></text>
                    </svg>
                  </div>
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
    const BUKU_TAMU = {
      depan: {
        img: <?= json_encode($bukuTamuUrl . 'tampak_depan.png') ?>,
        filename: <?= json_encode('buku-tamu-depan-' . $slug . '.png') ?>,
        texts: [
          { text: <?= json_encode($nama1) ?>, x: 1120, y: 1220, size: 300, family: "'Alex Brush'", fill: 'gold', maxWidth: 1200 },
          <?php if ($nama2 !== ''): ?>
          { segments: [{ text: '&', size: 200, dy: -60 }, { text: <?= json_encode(' ' . $nama2) ?>, size: 300 }], x: 1120, y: 1480, family: "'Alex Brush'", fill: 'gold', maxWidth: 1200 },
          <?php endif; ?>
          { text: <?= json_encode($tanggalAcara) ?>, x: 3346, y: 1900, size: 65, family: "Georgia, 'Times New Roman', serif", weight: 'bold', fill: '#ed126b', maxWidth: 1050, align: 'right' },
          { text: <?= json_encode($lokasiAcara) ?>, x: 3346, y: 2010, size: 65, family: "Georgia, 'Times New Roman', serif", weight: 'bold', fill: '#ed126b', maxWidth: 1050, align: 'right' }
        ]
      },
      isi: {
        img: <?= json_encode($bukuTamuUrl . 'tampak_isi.png') ?>,
        filename: <?= json_encode('buku-tamu-isi-' . $slug . '.png') ?>,
        texts: [
          { text: <?= json_encode($namaSatuBaris) ?>, x: 1754, y: 1330, size: 240, family: "'Alex Brush'", fill: '#d6d6d6', maxWidth: 2200 }
        ]
      },
      belakang: {
        img: <?= json_encode($bukuTamuUrl . 'tampak_belakang.png') ?>,
        filename: <?= json_encode('buku-tamu-belakang-' . $slug . '.png') ?>,
        texts: [
          { text: <?= json_encode($namaSatuBaris) ?>, x: 1754, y: 1280, size: 280, family: "'Alex Brush'", fill: 'gold', maxWidth: 1550 }
        ]
      }
    };

    async function downloadBukuTamu(kind, btn) {
      const cfg = BUKU_TAMU[kind];
      if (!cfg) return;

      const originalLabel = btn ? btn.textContent : null;
      if (btn) { btn.disabled = true; btn.textContent = 'Menyiapkan...'; }

      try {
        // Pastikan font web (Alex Brush) sudah termuat sebelum digambar ke canvas.
        await Promise.all(cfg.texts.flatMap(t => {
          if (t.segments) {
            return t.segments.map(seg => document.fonts.load(`${t.weight || ''} ${seg.size}px ${t.family}`.trim(), seg.text || 'A'));
          }
          return [document.fonts.load(`${t.weight || ''} ${t.size}px ${t.family}`.trim(), t.text || 'A')];
        }));
        await document.fonts.ready;

        const img = new Image();
        await new Promise((resolve, reject) => {
          img.onload = resolve;
          img.onerror = () => reject(new Error('Gagal memuat gambar dasar: ' + cfg.img));
          img.src = cfg.img;
        });

        const canvas = document.createElement('canvas');
        canvas.width = 3508;
        canvas.height = 2481;
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
            // baris dengan campuran ukuran font (mis. "&" dibikin lebih besar dari nama di sebelahnya)
            const widths = t.segments.map(seg => {
              ctx.font = `${t.weight || ''} ${seg.size}px ${t.family}`.trim();
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
              ctx.font = `${t.weight || ''} ${seg.size}px ${t.family}`.trim();
              ctx.fillStyle = (t.fill === 'gold') ? goldFillFor(cursor, cursor + widths[i], seg.size, seg.dy) : t.fill;
              ctx.fillText(seg.text, cursor, seg.dy || 0);
              cursor += widths[i];
            });
            ctx.restore();
            return;
          }

          if (!t.text) return;
          const align = t.align || 'center';
          ctx.font = `${t.weight || ''} ${t.size}px ${t.family}`.trim();
          ctx.textAlign = align;

          const measured = ctx.measureText(t.text).width;
          const scaleX = measured > 0 ? Math.min(1, t.maxWidth / measured) : 1;
          const bounds = align === 'right' ? [-measured, 0] : align === 'left' ? [0, measured] : [-measured / 2, measured / 2];

          ctx.save();
          ctx.translate(t.x, t.y);
          ctx.scale(scaleX, 1);
          ctx.fillStyle = (t.fill === 'gold') ? goldFillFor(bounds[0], bounds[1], t.size) : t.fill;
          ctx.fillText(t.text, 0, 0);
          ctx.restore();
        });

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
