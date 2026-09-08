<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceklist &amp; TTD — <?= htmlspecialchars($form->vendor_nama) ?></title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { size: A4; margin: 15mm; }
            .no-print { display: none !important; }
            /* Layout sidebar/header buat tampilan layar pakai height:100vh +
               overflow:hidden -- kalau dibawa ke print, tingginya kepatok ke
               ukuran viewport screen (bukan halaman kertas), bikin konten
               bisa terpotong. Lepas semua itu khusus pas print. */
            .print-reset { height: auto !important; overflow: visible !important; }
            /* Jarak tetap ke bagian Tanda Tangan -- sebelumnya dipaksa nempel
               ke paling bawah kertas (margin-top:auto), tapi itu bikin
               jaraknya kelewat jauh kalau ceklistnya pendek. Jarak tetap
               lebih wajar & konsisten. */
            .print-signature { margin-top: 48px; }
            /* Lebar area cetak A4 (210mm - margin kiri+kanan) ada di bawah
               breakpoint md: Tailwind, jadi grid tanda tangannya bisa
               kepatok jadi 1 kolom (tersusun ke bawah) kalau dibiarkan pakai
               breakpoint responsif itu -- dipaksa 3 kolom di sini biar
               selalu berjejer pas dicetak, terlepas dari breakpoint layar. */
            .print-signature-grid { grid-template-columns: repeat(3, 1fr) !important; }
        }
    </style>
</head>
<body
    x-data="{ page: 'project', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
  >
  <!-- ===== Preloader Start ===== -->
  <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})" class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black no-print">
    <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent">
    </div>
  </div>
  <!-- ===== Preloader End ===== -->
  <!-- ===== Page Wrapper Start ===== -->
  <div class="flex h-screen overflow-hidden print-reset">
    <div class="no-print"><?php $this->load->view('backend/sidebar')?></div>

    <!-- ===== Content Area Start ===== -->
    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden print-reset">
      <div class="no-print"><?php $this->load->view('backend/header')?></div>

      <!-- ===== Main Content Start ===== -->
      <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
          <div class="grid grid-cols-12 gap-4 md:gap-6 2xl:gap-9">
            <div class="col-span-12 lg:col-span-10 lg:col-start-2 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">

              <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-2">
                <h1 class="text-4xl font-bold">Ceklist Serah Terima <?= htmlspecialchars($form->vendor_type) ?></h1>
                <div class="flex flex-wrap gap-2 no-print">
                  <button type="button" onclick="window.print()" class="px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">Cetak</button>
                  <a href="<?= site_url('vendor-checklist/edit/' . $form->id_session) ?>" class="px-4 py-2 bg-primary text-white rounded-md font-medium hover:bg-opacity-90">
                    Edit
                  </a>
                  <a href="<?= site_url('project/lihat/' . $form->project_id_session) ?>" class="px-4 py-2 border rounded-md font-medium hover:bg-whiter dark:hover:bg-meta-4">
                    &larr; Kembali ke Project
                  </a>
                </div>
              </div>

              <?php if ($this->session->flashdata('Success')): ?>
                <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 text-sm no-print"><?= $this->session->flashdata('Success') ?></div>
              <?php endif; ?>

              <p class="text-sm text-body dark:text-bodydark mb-1">
                <?= htmlspecialchars($project->project_name ?? '') ?><br>
                <?= format_tanggal_acara($project->event_date ?? null) ?>
                <?php if (!empty($project->location)): ?>
                &middot; <?= htmlspecialchars($project->location) ?>
                <?php endif; ?>
              </p>
              <p class="mb-4">
                Vendor <strong><?= htmlspecialchars($form->vendor_type) ?></strong>: <?= htmlspecialchars($form->vendor_nama) ?>
                <span class="ml-2 inline-flex rounded-full px-3 py-1 text-xs font-medium <?= $form->status === 'Selesai' ? 'bg-success bg-opacity-10 text-success' : 'bg-yellow-500 bg-opacity-10 text-yellow-600' ?>">
                  <?= $form->status ?>
                </span>
              </p>

              <h2 class="text-lg font-bold mb-2">Ceklist</h2>
              <?php if (empty($checklist_items)): ?>
              <p class="text-sm text-body dark:text-bodydark mb-4">Tidak ada item ceklist.</p>
              <?php else: ?>
              <ul class="mb-6 space-y-2">
                <?php foreach ($checklist_items as $item): ?>
                <li class="flex items-center gap-3">
                  <span class="text-2xl leading-none flex-shrink-0"><?= !empty($item['checked']) ? '✅' : '⬜' ?></span>
                  <span class="text-base <?= !empty($item['checked']) ? '' : 'text-body dark:text-bodydark' ?>"><?= htmlspecialchars($item['label']) ?></span>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php endif; ?>

              <?php if (!empty($form->catatan)): ?>
              <h2 class="text-lg font-bold mb-2">Catatan</h2>
              <p class="whitespace-pre-line text-body dark:text-bodydark border border-stroke dark:border-strokedark rounded-md p-3 mb-6"><?= nl2br(htmlspecialchars($form->catatan)) ?></p>
              <?php endif; ?>

              <div class="print-signature">
                <h2 class="text-lg font-bold mb-3">Tanda Tangan Serah Terima</h2>
                <p class="text-xs text-body dark:text-bodydark mb-4 no-print">
                  Diisi manual di kertas hasil cetak (klik tombol "Cetak" di atas), bukan di sistem.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 print-signature-grid">
                  <?php foreach (['Petugas WO', 'Vendor', 'Customer'] as $label): ?>
                  <div class="text-center">
                    <div class="h-20 border-b border-black dark:border-white"></div>
                    <p class="mt-2 text-sm font-medium"><?= $label ?></p>
                    <p class="text-xs text-body dark:text-bodydark">(Nama &amp; Tanda Tangan)</p>
                  </div>
                  <?php endforeach; ?>
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
</body>
</html>
