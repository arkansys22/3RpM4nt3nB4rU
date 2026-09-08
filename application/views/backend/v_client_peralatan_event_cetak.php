<?php
$nama_pengantin = $clients->client_name ?? ($project->project_name ?? '-');
$tanggal = format_tanggal_acara($project->event_date ?? null);
$lokasi = $project->location ?? '-';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Peralatan Event — <?= htmlspecialchars($nama_pengantin) ?></title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <style>
        @page { size: A4; margin: 15mm; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #000;
            margin: 0;
            padding: 20px;
            background: #525659;
        }
        .sheet {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 24px 28px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        .toolbar { max-width: 900px; margin: 0 auto 12px auto; display: flex; justify-content: flex-end; gap: 8px; }
        .toolbar button, .toolbar a {
            display: inline-block; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500;
            text-decoration: none; border: 1px solid #ccc; background: #fff; color: #1c2434; cursor: pointer;
        }
        .toolbar .primary { background: #3C50E0; color: #fff; border-color: #3C50E0; }
        h1 { text-align: center; text-decoration: underline; font-size: 22px; margin: 0 0 20px 0; }
        .info { margin-bottom: 20px; }
        .info div { display: flex; margin-bottom: 4px; }
        .info .label { font-weight: bold; width: 130px; flex-shrink: 0; }
        .info .colon { width: 14px; flex-shrink: 0; }

        /* Tiap kategori = bagiannya sendiri-sendiri: judul + tabel + baris
           Tanda Tangan sendiri, dipisah jelas satu sama lain (bukan cuma
           satu baris judul di dalam satu tabel besar). Kategori kedua dst
           mulai di halaman baru saat dicetak. */
        .kategori-section { margin-bottom: 32px; }
        .kategori-section.new-page { page-break-before: always; }
        .kategori-title {
            font-size: 16px; font-weight: bold; text-transform: uppercase;
            background: #eee; border: 1px solid #000; border-bottom: none;
            padding: 8px 10px;
        }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; }
        th { text-align: center; font-weight: bold; background: #fff; }
        td.no, td.chk { text-align: center; }
        .col-no   { width: 6%; }
        .col-nama { width: 32%; }
        .col-detail { width: 32%; }
        .col-chk  { width: 10%; }
        .checkbox { font-size: 18px; }
        .ttd-row td { text-align: center; font-weight: bold; }

        @media print {
            body { background: #fff; padding: 0; }
            .sheet { box-shadow: none; padding: 0; max-width: none; }
            .toolbar { display: none !important; }
        }
    </style>
</head>
<body>

  <div class="toolbar">
    <a href="<?= site_url('peralatan-event-project/' . $project->id_session) ?>">&larr; Kembali</a>
    <button type="button" class="primary" onclick="window.print()">Cetak</button>
  </div>

  <div class="sheet">
    <h1>LIST PERALATAN EVENT</h1>

    <div class="info">
      <div><span class="label">Nama Pengantin</span><span class="colon">:</span><span><?= htmlspecialchars($nama_pengantin) ?></span></div>
      <div><span class="label">Tanggal</span><span class="colon">:</span><span><?= htmlspecialchars($tanggal) ?></span></div>
      <div><span class="label">Lokasi</span><span class="colon">:</span><span><?= htmlspecialchars($lokasi) ?></span></div>
    </div>

    <?php if (empty($grouped)): ?>
    <p style="text-align:center;">Belum ada item.</p>
    <?php else: foreach ($grouped as $g_index => $group): ?>
    <div class="kategori-section<?= $g_index > 0 ? ' new-page' : '' ?>">
      <div class="kategori-title"><?= htmlspecialchars($group['kategori']->nama_kategori) ?></div>
      <table>
        <thead>
          <tr>
            <th class="col-no">No</th>
            <th class="col-nama">Nama Barang</th>
            <th class="col-detail">Detail</th>
            <th class="col-chk">Check<br>dikantor</th>
            <th class="col-chk">Check<br>dilokasi</th>
            <th class="col-chk">Check<br>dikantor</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; foreach ($group['items'] as $item): ?>
          <tr>
            <td class="no"><?= $no++ ?></td>
            <td style="<?= $item->bold ? 'font-weight:bold;' : '' ?>"><?= htmlspecialchars($item->nama_barang) ?></td>
            <td style="<?= $item->bold ? 'font-weight:bold;' : '' ?>"><?= htmlspecialchars($item->detail) ?></td>
            <td class="chk"><span class="checkbox">&#9744;</span></td>
            <td class="chk"><span class="checkbox">&#9744;</span></td>
            <td class="chk"><span class="checkbox">&#9744;</span></td>
          </tr>
          <?php endforeach; ?>
          <tr class="ttd-row">
            <td colspan="3">Tanda Tangan</td>
            <td>(.............)</td>
            <td>(.............)</td>
            <td>(.............)</td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php endforeach; endif; ?>
  </div>

</body>
</html>
