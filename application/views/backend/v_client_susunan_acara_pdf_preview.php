<?php
$acaraKe = $acara_ke ?? 1;
$acaraUrlPrefix = $acaraKe == 2 ? 'acara2/' : '';
$listUrl = site_url('clients/susunan-acara/' . $acaraUrlPrefix . $clients->id_session);
$downloadName = 'Susunan Acara' . ($acaraKe == 2 ? ' 2' : '') . ' - ' . $clients->client_name . '.pdf';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview PDF — <?= htmlspecialchars($clients->client_name) ?></title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <style>
        html, body { margin: 0; font-family: Arial, sans-serif; background: #525659; }
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 16px;
            background: #323639;
            color: #fff;
        }
        .toolbar a {
            color: #fff;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
        }
        .toolbar a.back { background: rgba(255,255,255,0.12); }
        .toolbar a.back:hover { background: rgba(255,255,255,0.2); }
        .toolbar a.download { background: #3C50E0; }
        .toolbar a.download:hover { opacity: 0.9; }
        .toolbar .title { font-size: 14px; opacity: 0.85; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        #pdfContainer {
            padding: 20px;
            min-height: calc(100vh - 52px);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }
        #pdfContainer canvas {
            max-width: 100%;
            height: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
            background: #fff;
        }
        .status { color: #fff; text-align: center; padding: 60px 20px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="<?= $listUrl ?>" class="back">&larr; Kembali</a>
        <span class="title">Susunan Acara <?= $acaraKe ?> — <?= htmlspecialchars($clients->client_name) ?></span>
        <a href="<?= $pdf_url ?>" download="<?= htmlspecialchars($downloadName) ?>" class="download">&darr; Unduh PDF</a>
    </div>

    <div id="pdfContainer">
        <p id="pdfStatus" class="status">Memuat PDF&hellip;</p>
    </div>

    <!--
      PDF-nya dirender manual ke <canvas> pakai PDF.js, BUKAN <iframe>/<a href> langsung ke
      file .pdf-nya (walaupun via Blob URL sekalipun). Alasannya: kalau browser punya
      setting "selalu unduh file PDF" aktif, setting itu tetap ke-trigger untuk SEGALA
      bentuk navigasi/embed ke resource yang dikenali sebagai PDF -- termasuk Blob URL --
      dan hasilnya tab preview jadi kosong/blank karena browser diam-diam mendownload di
      belakang layar alih-alih menampilkan. Rendering manual ke canvas membuat browser
      tidak pernah "melihat" ini sebagai resource PDF sama sekali (cuma gambar biasa di
      halaman HTML), jadi tidak ada keputusan download-vs-tampilkan yang bisa diambil
      browser -- selalu konsisten tampil di semua kondisi setting.
    -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        var pdfUrl = <?= json_encode($pdf_url, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        var container = document.getElementById('pdfContainer');
        var statusEl = document.getElementById('pdfStatus');

        function renderPage(pdf, pageNum) {
            return pdf.getPage(pageNum).then(function (page) {
                var viewport = page.getViewport({ scale: 1.4 });
                var canvas = document.createElement('canvas');
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                container.appendChild(canvas);
                return page.render({ canvasContext: canvas.getContext('2d'), viewport: viewport }).promise;
            });
        }

        // Ambil byte PDF-nya sendiri lewat fetch() biasa (bukan diserahkan ke jaringan
        // internal PDF.js -- PDF.js kadang melakukan request Range/streaming buat file
        // besar yang ternyata tidak selalu cocok dengan server statis, walau request GET
        // polos sendiri normal). PDF.js dipakai murni buat MERENDER byte yang sudah
        // ditangan, tanpa dia bikin request jaringan sendiri sama sekali.
        fetch(pdfUrl, { credentials: 'same-origin' })
            .then(function (res) {
                if (!res.ok) { throw new Error('Server merespons status ' + res.status); }
                return res.arrayBuffer();
            })
            .then(function (buffer) {
                return pdfjsLib.getDocument({ data: buffer }).promise;
            })
            .then(function (pdf) {
                statusEl.remove();
                var chain = Promise.resolve();
                for (var i = 1; i <= pdf.numPages; i++) {
                    (function (pageNum) {
                        chain = chain.then(function () { return renderPage(pdf, pageNum); });
                    })(i);
                }
                return chain;
            })
            .catch(function (err) {
                statusEl.textContent = 'Gagal memuat PDF: ' + err.message + '. Coba tombol "Unduh PDF" di atas.';
            });
    </script>
</body>
</html>
