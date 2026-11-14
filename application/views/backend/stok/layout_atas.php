<?php
// Kerangka halaman Stock Barang (bagian atas). Variabel: $judul, $nav (kunci tab aktif), $tombol (html opsional di kanan judul)
$nav_item = [
    'daftar' => ['Daftar Stok', 'supplies'],
    'masuk' => ['Barang Masuk', 'supplies/masuk'],
    'keluar' => ['Barang Keluar', 'supplies/keluar'],
    'opname' => ['Koreksi Stok', 'supplies/opname'],
    'menipis' => ['Stok Menipis', 'supplies/restock'],
    'kategori' => ['Kategori', 'supplies/kategori'],
    'recycle' => ['Recycle Bin', 'supplies/recycle_bin'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lk_h($judul) ?> - Stock Barang</title>
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

              <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
                <div>
                  <h1 class="text-2xl font-bold"><?= lk_h($judul) ?></h1>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Stock Barang &middot; pencatatan barang masuk, keluar, dan stok per kategori</p>
                </div>
                <div class="flex flex-wrap gap-2"><?= $tombol ?? '' ?></div>
              </div>

              <div class="flex flex-wrap gap-1 border-b border-stroke dark:border-strokedark mb-5">
                <?php foreach ($nav_item as $kunci => $it): ?>
                  <a href="<?= site_url($it[1]) ?>" class="px-4 py-2 text-sm font-medium rounded-t-md <?= $nav === $kunci ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-neutral-700' ?>"><?= lk_h($it[0]) ?></a>
                <?php endforeach; ?>
              </div>

              <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm"><?= lk_h($this->session->flashdata('error')) ?></div>
              <?php endif; ?>
              <?php if ($this->session->flashdata('success')): ?>
                <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 text-sm"><?= lk_h($this->session->flashdata('success')) ?></div>
              <?php endif; ?>
