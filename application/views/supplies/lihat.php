<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Stock</title>
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'supplies', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h1 class="text-2xl font-bold mb-4">Stock <?= $supplies->product_name ?></h1>
              <form method="post" class="bg-white dark:bg-boxdark p-6 shadow-md rounded">
                <label class="block mb-2"><strong>Nama Produk : </strong><?= $supplies->product_name ?></label>        
                <label class="block mb-2"><strong>Jenis : </strong><?= $supplies->type ?></label>        
                <label class="block mb-2"><strong>Stock Tersedia : </strong><?= $supplies->amount; ?></label>

                <a href="<?= site_url('supplies/delete/'.$supplies->id_session) ?>" class="ml-2 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 inline-block text-center w-auto" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                <a href="javascript:history.back()" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 inline-block text-center w-auto">Kembali</a>
              </form>
            <!-- ====== Table Three Start -->
            <div class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
              <div class="max-w-full max-h-[400px] overflow-y-auto overflow-x-auto">
                <table class="w-full table-auto">
                  <thead>
                    <tr class="bg-gray-2 text-left dark:bg-meta-4">
                      <th class="min-w-[220px] px-4 py-4 font-medium text-center xl:pl-11">
                        Product Name
                      </th>
                      <th class="min-w-[150px] px-4 py-4 font-medium text-center">
                        Type
                      </th>
                      <th class="min-w-[120px] px-4 py-4 font-medium text-center">
                        Status
                      </th>
                      <th class="px-4 py-4 font-medium text-center">
                        Amount
                      </th>
                      <th class="px-4 py-4 font-medium text-center">
                        Detail
                      </th>
                      <th class="px-4 py-4 font-medium text-center">
                        Time
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1; foreach ($supplies_stock as $p): ?>
                    <?php $supplies= $this->Crud_m->view_where('supplies', array('id_session'=> $p->id_session))->row(); ?>
                    <tr>
                      <td class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                        <h5 class="font-medium text-center"><?= $supplies->product_name ?></h5>
                      </td>
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p class="text-center"><?= $supplies->type ?></p>
                      </td>
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <?php if ($p->status == 'Barang Keluar'): ?>
                        <p class="inline-flex rounded-full bg-danger bg-opacity-10 px-3 py-1 text-center text-sm font-medium text-danger">
                          <?= $p->status ?>
                        </p>
                        <?php else: ?>
                        <p class="inline-flex rounded-full bg-success bg-opacity-10 px-3 py-1 text-center text-sm font-medium text-success">
                          <?= $p->status ?>
                        </p>
                        <?php endif; ?>
                      </td>
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <?php if ($p->status == 'Barang Masuk'): ?>
                        <p class="text-center"><?= $p->goods_in ?></p>
                        <?php else: ?>
                        <p class="text-center"><?= $p->goods_out ?></p>
                        <?php endif; ?>
                      </td>
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p class="text-center"><?= $p->detail ?></p>
                      </td>
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p class="text-center"><?= hari($p->created_at) ?>, <?= tgl_indo($p->created_at)?></p>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- ====== Table Three End -->
            </div>
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
</body>
</html>
