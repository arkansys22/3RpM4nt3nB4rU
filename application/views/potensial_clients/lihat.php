<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Potensial Klien</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'projects', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
  >
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
                <h1 class="text-2xl font-bold">Lihat Potensial Klien <?= $pc->status ?></h1>
                <div
                  x-data="{openDropDown: false}"
                  class="relative inline-block"
                >
                  <a
                  href="#"
                  @click.prevent="openDropDown = !openDropDown"
                  class="inline-flex items-center gap-2.5 rounded-md bg-primary px-5.5 py-3 font-medium text-white hover:bg-opacity-95"
                  >
                  Menu
                  <svg
                    class="fill-current duration-200 ease-linear"
                    :class="openDropDown && 'rotate-180'"
                    width="12"
                    height="7"
                    viewBox="0 0 12 7"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                    d="M0.564864 0.879232C0.564864 0.808624 0.600168 0.720364 0.653125 0.667408C0.776689 0.543843 0.970861 0.543844 1.09443 0.649756L5.82517 5.09807C5.91343 5.18633 6.07229 5.18633 6.17821 5.09807L10.9089 0.649756C11.0325 0.526192 11.2267 0.543844 11.3502 0.667408C11.4738 0.790972 11.4562 0.985145 11.3326 1.10871L6.60185 5.55702C6.26647 5.85711 5.73691 5.85711 5.41917 5.55702L0.670776 1.10871C0.600168 1.0381 0.564864 0.967492 0.564864 0.879232Z"
                    fill=""
                    />
                    <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M1.4719 0.229332L6.00169 4.48868L10.5171 0.24288C10.9015 -0.133119 11.4504 -0.0312785 11.7497 0.267983C12.1344 0.652758 12.0332 1.2069 11.732 1.50812L11.7197 1.52041L6.97862 5.9781C6.43509 6.46442 5.57339 6.47872 5.03222 5.96853C5.03192 5.96825 5.03252 5.96881 5.03222 5.96853L0.271144 1.50833C0.123314 1.3605 -5.04223e-08 1.15353 -3.84322e-08 0.879226C-2.88721e-08 0.660517 0.0936127 0.428074 0.253705 0.267982C0.593641 -0.0719548 1.12269 -0.0699964 1.46204 0.220873L1.4719 0.229332ZM5.41917 5.55702C5.73691 5.85711 6.26647 5.85711 6.60185 5.55702L11.3326 1.10871C11.4562 0.985145 11.4738 0.790972 11.3502 0.667408C11.2267 0.543844 11.0325 0.526192 10.9089 0.649756L6.17821 5.09807C6.07229 5.18633 5.91343 5.18633 5.82517 5.09807L1.09443 0.649756C0.970861 0.543844 0.776689 0.543843 0.653125 0.667408C0.600168 0.720364 0.564864 0.808624 0.564864 0.879232C0.564864 0.967492 0.600168 1.0381 0.670776 1.10871L5.41917 5.55702Z"
                    fill=""
                    />
                  </svg>
                  </a>
                  <div
                  x-show="openDropDown"
                  @click.outside="openDropDown = false"
                  class="absolute left-0 top-full z-40 mt-2 w-full rounded-md border border-stroke bg-white py-3 shadow-card dark:border-strokedark dark:bg-boxdark"
                  >
                  <ul class="flex flex-col">
                    <li>
                    <a
                      href="<?= site_url('potensial-clients/edit/'. $pc->id_session) ?>"
                      class="flex px-5 py-2 font-medium hover:bg-whiter hover:text-primary dark:hover:bg-meta-4"
                    >
                      Edit
                    </a>
                    </li>
                    <li>
                    <a
                        href="<?= $pc->status === 'Tanya-tanya' ? site_url('potensial-clients') : ($pc->status === 'Deal' ? site_url('potensial-clients-bayar') : site_url('potensial-clients-'.strtolower($pc->status))) ?>"
                      class="flex px-5 py-2 font-medium hover:bg-whiter hover:text-primary dark:hover:bg-meta-4"
                    >
                      Kembali
                    </a>
                    </li>
                  </ul>
                  </div>
                </div>
                </div>
                <div class="hidden md:block">
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Nama Klien <p><h1 class="text-lg font-bold"><?= $pc->pc_name ?></h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Nomer WhatsApp <p><h1 class="text-lg font-bold"><?= $pc->pc_nowa ?></h1></p>
                  </label>
                  </div>                  
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Lokasi Acara <p><h1 class="text-lg font-bold"><?= $pc->location ?></h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Tanggal Acara<p><h1 class="text-lg font-bold"><?= hari($pc->event_date) ?>, <?= tgl_indo($pc->event_date) ?></h1></p>
                  </label>
                  </div>                  
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Pertama Chat <p><h1 class="text-lg font-bold"><?= hari($pc->chat_date) ?>, <?= tgl_indo($pc->chat_date) ?></h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Dari Nomer Admin <p><h1 class="text-lg font-bold"><?= $pc->nomeradmin ?></h1></p>
                  </label>
                  </div>                  
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <label class="block mb-2">
                  Catatan <p><h1 class="text-lg font-bold"><?= $pc->note ?></h1></p>
                  </label>
                </div>
              </div>
              <div class="block md:hidden">
              <form action="" method="post" class="bg-white dark:bg-boxdark p-6 shadow-md rounded">
                <label class="block mb-2">Nama Klien : <strong><?= $pc->pc_name ?></strong></label>        
                <label class="block mb-2">Nomer WhatsApp : <strong><a href="https://wa.me/<?= $pc->pc_nowa ?>"><?= $pc->pc_nowa ?></a></strong></label>        
                <label class="block mb-2">Tanggal Pernikahan : <strong><?= hari($pc->event_date) ?>, <?= tgl_indo($pc->event_date) ?></strong></label>
                <label class="block mb-2">Lokasi Pernikahan : <strong><?= $pc->location ?></strong></label>
                <label class="block mb-2">Pertama Chat : <strong><?= hari($pc->chat_date) ?>, <?= tgl_indo($pc->chat_date) ?></strong></label>                
                <label class="block mb-2">Dari Nomer Admin : <strong><?= $pc->nomeradmin ?></strong></label>
                <label class="block mb-2">Catatan : <strong><?= $pc->note ?></strong></label>
                <!-- <br>
                <a href="<?= site_url('potensial-clients/edit/'. $pc->id_session) ?>" class="ml-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 inline-block text-center w-auto">Edit</a>
                <a href="<?= site_url('potensial-clients') ?>" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 inline-block text-center w-auto">Kembali</a> -->
              </form>
              </div>

              <button onclick="openModal()"
                class="px-4 py-2 bg-primary text-white rounded-md shadow">
                Tambah Penawaran Produk 
              </button>
              <button onclick="openModals()"
                class="px-4 py-2 bg-primary text-white rounded-md shadow">
                Setting Diskon 
              </button>
              <br><br>

              <?php if ($pc->promo === 'default'){?>
                <span>Format Promo Diskon : Default</span>
              <?php }else if($pc->promo === 'tidak'){?>
                <span>Format Promo Diskon : Tidak Ada</span>
              <?php }else if($pc->promo === 'custom' ){ ?>
                <span>Format Promo Diskon : Custom Rp <?= number_format($pc->promo_value, 0, ',', '.'); ?></span>
            <?php }?>
              
              <br><br>
              <div class="border border-stroke dark:border-strokedark">
                  <div class="max-w-full overflow-x-auto">
                    <div class="min-w-[670px]">
                      <!-- table header start -->
                      <div
                        class="grid grid-cols-12 border-b border-stroke py-3.5 pl-5 pr-6 dark:border-strokedark"
                      >
                        <div class="col-span-4">
                          <h5 class="font-medium text-black dark:text-white">
                            Nama Produk
                          </h5>
                        </div>

                        <div class="col-span-2">
                          <h5 class="font-medium text-black dark:text-white">
                            Harga Asli
                          </h5>
                        </div>

                        <div class="col-span-2">
                          <h5 class="font-medium text-black dark:text-white">
                            Harga Promo
                          </h5>
                        </div>

                        <div class="col-span-1">
                          <h5 class="font-medium text-black dark:text-white">
                            Qty
                          </h5>
                        </div>

                        <div class="col-span-2">
                          <h5
                            class="font-medium text-black dark:text-white"
                          >
                            Total
                          </h5>
                        </div>
                        <div class="col-span-1">
                          <h5
                            class="font-medium text-black dark:text-white"
                          >
                            
                          </h5>
                        </div>
                      </div>
                      <!-- table header end -->
                      <?php $no = 1; $subTotal = 0; $diskonTotal = 0;foreach ($penawaran as $p): ?>
                      <!-- product item -->
                      <div
                        class="grid grid-cols-12 border-b border-stroke py-3.5 pl-5 pr-6 dark:border-strokedark"
                      >
                      <?php $namaproduk = $this->Crud_m->view_where('data_pricelist', array('data_pricelist_idsession'=> $p->penawaran_klien_idpricelist))->row(); ?>

                        <div class="col-span-4">
                          <p class="font-medium"><?= $namaproduk->data_pricelist_judul ?>  <p><small><?= $p->penawaran_klien_deskripsi ?></small></p></p>
                        </div>

                        <div class="col-span-2">
                          <p class="font-medium"><s><?= "Rp " . number_format($p->penawaran_klien_harga, 0, ',', '.'); ?></s></p>
                        </div>

                        <div class="col-span-2">
                          <p class="font-medium"><?= "Rp " . number_format($p->penawaran_klien_hargapromo, 0, ',', '.') ?></p>
                        </div>

                        <div class="col-span-1">
                          <p class="font-medium"><?= $p->penawaran_klien_qty ?></p>
                        </div>
                        <?php $total = $p->penawaran_klien_hargapromo * $p->penawaran_klien_qty ?>
                        <div class="col-span-2">
                          <p class="font-medium"><?= "Rp " . number_format($total, 0, ',', '.') ?></p>
                        </div>
                        <div class="col-span-1">
                          <p class="font-medium"><a href="<?= site_url('potensial-clients/permanent_delete_penawaran/'.$p->penawaran_klien_id.'/'.$pc->id_session ) ?>">Hapus</a></p>
                        </div>
                      </div>
                      <?php $diskonTotal += $p->penawaran_klien_diskon; ?>

                      <?php $subTotal += $total; ?>
                      <!-- product item -->
                      <?php endforeach; ?>     


                    </div>
                  </div>

                  <!-- total price start -->
                  <div class="flex justify-end p-6">
                    <div class="w-full max-w-65">
                      <div class="flex flex-col gap-4">
                        <p
                          class="flex justify-between font-medium text-black dark:text-white"
                        >
                          <span> Sub Total </span>
                          <span> Rp <?= number_format($subTotal, 0, ',', '.') ?></span>
                        </p>

                        <p
                          class="flex justify-between font-medium text-black dark:text-white"
                        >
                          <span> Promo Diskon (-) </span>
                          <span> Rp <?= number_format($diskonTotal, 0, ',', '.') ?> </span>
                        </p>
                      </div>

                      <p
                        class="mt-4 flex justify-between border-t border-stroke pt-5 dark:border-strokedark"
                      >
                        <span class="font-medium text-black dark:text-white">
                        <?php $total = $subTotal - $diskonTotal?>
                          Total
                        </span>
                        <span class="font-bold text-meta-3"> Rp <?= number_format($total, 0, ',', '.') ?> </span>
                      </p>

                      <button onclick="window.location.href='<?= base_url('potensial-clients/view_penawaran/'.$pc->id_session) ?>'"
                        class="float-right mt-10 inline-flex items-center gap-2.5 rounded bg-primary px-7.5 py-2.5 font-medium text-white hover:bg-opacity-90"
                      >
                        Cetak Penawaran
                        <svg
                          class="fill-current"
                          width="18"
                          height="18"
                          viewBox="0 0 18 18"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <g clip-path="url(#clip0_1878_13706)">
                            <path
                              d="M16.8754 12.375C16.5379 12.375 16.2285 12.6562 16.2285 13.0219V15.525C16.2285 15.7781 16.0316 15.975 15.7785 15.975H2.22227C1.96914 15.975 1.77227 15.7781 1.77227 15.525V13.0219C1.77227 12.6562 1.46289 12.375 1.12539 12.375C0.787891 12.375 0.478516 12.6562 0.478516 13.0219V15.525C0.478516 16.4812 1.23789 17.2406 2.19414 17.2406H15.7785C16.7348 17.2406 17.4941 16.4812 17.4941 15.525V13.0219C17.5223 12.6562 17.2129 12.375 16.8754 12.375Z"
                              fill=""
                            />
                            <path
                              d="M8.55055 13.078C8.66305 13.1905 8.8318 13.2468 9.00055 13.2468C9.1693 13.2468 9.30992 13.1905 9.45054 13.078L13.5287 9.1124C13.7818 8.85928 13.7818 8.46553 13.5287 8.2124C13.2755 7.95928 12.8818 7.95928 12.6287 8.2124L9.64742 11.1374V1.40615C9.64742 1.06865 9.36617 0.759277 9.00055 0.759277C8.66305 0.759277 8.35367 1.04053 8.35367 1.40615V11.1374L5.37242 8.2124C5.1193 7.95928 4.72555 7.9874 4.47242 8.2124C4.2193 8.46553 4.24742 8.85928 4.47242 9.1124L8.55055 13.078Z"
                              fill=""
                            />
                          </g>
                          <defs>
                            <clipPath id="clip0_1878_13706">
                              <rect width="18" height="18" fill="white" />
                            </clipPath>
                          </defs>
                        </svg>
                      </button>
                    </div>
                  </div>
                  <!-- total price end -->
                </div>


              <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-black dark:text-white">
                  Log Activity
                </h3>

                <button
                  onclick="toggleTable()"
                  class="flex items-center gap-2 text-sm font-medium text-primary hover:opacity-80">

                  <span id="toggleText">Roll Down</span>

                  <svg id="toggleIcon"
                    class="h-4 w-4 rotate-180 transition-transform duration-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              </div>

              <!-- ====== Table Three Start -->
              <div class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default  dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1" >
                <div id="tableWrapper" class="max-w-full overflow-x-auto hidden transition-all duration-300">
                  <table class="w-full table-auto">
                    <thead>
                      <tr class="bg-gray-2 text-left dark:bg-meta-4">
                        <th
                          class="min-w-[220px] px-4 py-4 font-medium xl:pl-11"
                        >
                          Author
                        </th>
                        <th
                          class="min-w-[150px] px-4 py-4 font-medium"
                        >
                          Status
                        </th>
                        <th
                          class="min-w-[120px] px-4 py-4 font-medium"
                        >
                          Time
                        </th>
                        <th class="px-4 py-4 font-medium">
                          Device
                        </th>
                        <th class="px-4 py-4 font-medium">
                          IP
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1; foreach ($logactivity as $p): ?>
                      <tr>
                        <?php $company= $this->Crud_m->view_where('user', array('id_session'=> $p->log_activity_user_id))->row(); ?>
                        <?php $level= $this->Crud_m->view_where('user_level', array('user_level_id'=> $company->level))->row(); ?>
                        <td
                          class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11"
                        >
                          <h5 class="font-medium"><?= $company->username ?></h5>
                          <p class="text-sm"><?= $level->user_level_nama ?></p>
                        </td>                        
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                          <p
                            class="inline-flex rounded-full bg-success bg-opacity-10 px-3 py-1 text-sm font-medium text-success"
                          >
                            <?= $p->log_activity_status ?>
                          </p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                          <p><?= hari($p->log_activity_waktu) ?>, <?= tgl_indo($p->log_activity_waktu)?></p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                         <p><?= $p->log_activity_platform ?></p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                         <p><?= $p->log_activity_ip ?></p>
                        </td>
                      </tr>
                      <?php endforeach; ?>                            
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- ====== Table Three End -->
              <div id="modal"
                class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

                <!-- Modal Box -->
                <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">

                  <!-- Close Button -->
                  <button onclick="closeModals()"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                    ✕
                  </button>

                  <!-- Title -->
                  <h2 class="text-lg font-semibold mb-4">Format Diskon</h2>

                  <!-- Form -->
                  <form action="<?= site_url('potensial-clients/update_promo/'.$pc->id_session) ?>" method="post">

                    <div class="mb-4">
                      <label class="block text-sm font-medium mb-1">Pilihan Promo</label>
                      <select id="promo" name="promo"
                        class="w-full px-4 py-2 border rounded" required>                        
                        <option value="default">Default</option>
                        <option value="tidak">Tidak Ada</option>
                        <option value="custom">Promo Custom</option>
                      </select>
                    </div>
                    <div class="mb-4 hidden" id="promoCustom">
                      <label class="block text-sm font-medium mb-1">Masukan Nilai Promo</label>
                      <input type="number" name="nilai_promo"
                        class="w-full rounded border px-3 py-2">                        
                    </div>       
                    <div class="flex justify-end gap-2">
                      <button type="button" onclick="closeModals()"
                        class="px-4 py-2 border rounded-md">
                        Batal
                      </button>
                      <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-md">
                        Simpan
                      </button>
                    </div>
                  </form>

                </div>
              </div>

              <!-- Overlay -->
              <div id="modal"
                class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

                <!-- Modal Box -->
                <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">

                  <!-- Close Button -->
                  <button onclick="closeModal()"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                    ✕
                  </button>

                  <!-- Title -->
                  <h2 class="text-lg font-semibold mb-4">Rencana Vendor</h2>

                  <!-- Form -->
                  <form action="<?= site_url('potensial-clients/update_penawaran/'.$pc->id_session) ?>" method="post">

                    <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Pilih Kategori</label>
                      <select id="kategori" name="kategori"
                        class="w-full px-4 py-2 border rounded" required>
                        <option value="">---</option>
                        <?php foreach ($kategori as $k): ?>
                          <option value="<?= $k->data_pricelist_kategori_nama ?>">
                            <?= $k->data_pricelist_kategori_nama ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label class="block text-sm font-medium mb-1">Pilihan Produk</label>
                      <select id="produk" name="produk_id"
                        class="w-full px-4 py-2 border rounded" required>
                        <option value="">Pilih kategori dulu</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label class="block text-sm font-medium mb-1">Harga Asli</label>
                      <input type="text" id="harga_asli" name="harga_asli"
                        class="w-full rounded border px-3 py-2" readonly>                        
                    </div>
                    <div class="mb-4">
                      <label class="block text-sm font-medium mb-1">Harga Promo</label>
                       <input type="text" id="harga_promo" name="harga_promo" class="w-full rounded border px-3 py-2" readonly>
                    </div>
                    <div class="mb-4">
                      <label class="block text-sm font-medium mb-1">Maks. Diskon</label>
                       <input type="text" id="maks_diskon" name="maks_diskon" class="w-full rounded border px-3 py-2" readonly>
                    </div>
                    <div class="mb-4">
                      <label class="block text-sm font-medium mb-1">Detail</label>
                      <input type="text" id="detail" name="detail" class="w-full rounded border px-3 py-2" readonly>
                    </div>
                    <div class="mb-4">
                      <label class="block text-sm font-medium mb-1">Quantity</label>
                       <input type="number" id="qty" name="qty" class="w-full rounded border px-3 py-2">
                    </div>
                    <div class="flex justify-end gap-2">
                      <button type="button" onclick="closeModal()"
                        class="px-4 py-2 border rounded-md">
                        Batal
                      </button>
                      <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-md">
                        Simpan
                      </button>
                    </div>
                  </form>

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
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const promoSelect = document.getElementById('promo');
        const promoCustom = document.getElementById('promoCustom');

        function togglePromo() {
          if (promoSelect.value === 'custom') {
            promoCustom.classList.remove('hidden');
          } else {
            promoCustom.classList.add('hidden');
          }
        }

        // Saat berubah
        promoSelect.addEventListener('change', togglePromo);

        // Saat halaman pertama kali load
        togglePromo();
      });
    </script>
    <script>
      function openModal() {
        document.getElementById('modal').classList.remove('hidden');
        document.getElementById('modal').classList.add('flex');
      }

      function closeModal() {
        document.getElementById('modal').classList.add('hidden');
        document.getElementById('modal').classList.remove('flex');
      }
    </script>

    <script>
      function openModals() {
        document.getElementById('modal').classList.remove('hidden');
        document.getElementById('modal').classList.add('flex');
      }

      function closeModals() {
        document.getElementById('modal').classList.add('hidden');
        document.getElementById('modal').classList.remove('flex');
      }
    </script>
    <script>
    let isOpen = false;

        function toggleTable() {
          const table = document.getElementById('tableWrapper');
          const icon = document.getElementById('toggleIcon');
          const text = document.getElementById('toggleText');

          isOpen = !isOpen;

          if (isOpen) {
            table.classList.remove('hidden');
            icon.classList.remove('rotate-180');
            text.innerText = 'Roll Up';
          } else {
            table.classList.add('hidden');
            icon.classList.add('rotate-180');
            text.innerText = 'Roll Down';
          }
        }
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const kategori = document.getElementById('kategori');
      const produk = document.getElementById('produk');

      kategori.addEventListener('change', function () {
        const kategoriId = this.value;

        if (!kategoriId) {
          produk.innerHTML = '<option value="">Pilih kategori dulu</option>';
          return;
        }

        produk.innerHTML = '<option>Loading...</option>';

        fetch(`<?= site_url('crud_potensial_clients/get_pricelist_by_kategori') ?>?kategori_id=${encodeURIComponent(kategoriId)}`)
          .then(res => res.text())
          .then(text => {
            console.log('RAW:', text);

            const data = JSON.parse(text);
            let html = '<option value="">Pilih Produk</option>';

            data.forEach(p => {
              html += `<option value="${p.data_pricelist_idsession}">
                ${p.data_pricelist_judul}
              </option>`;
            });

            produk.innerHTML = html; // ✅ FIX
          })
          .catch(err => {
            console.error(err);
            produk.innerHTML = '<option>Error load data</option>'; // ✅ FIX
          });
      });
    });
  </script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const produk = document.getElementById('produk');
    const hargaAsli = document.getElementById('harga_asli');
    const hargaPromo = document.getElementById('harga_promo');
    const maksDiskon = document.getElementById('maks_diskon');
    const detail = document.getElementById('detail');

    produk.addEventListener('change', function () {
      const produkId = this.value;

      if (!produkId) {
        hargaAsli.value = '';
        hargaPromo.value = '';
        maksDiskon.value = '';
        detail.value = '';
        return;
      }

      fetch(`<?= site_url('crud_potensial_clients/get_pricelist_detail') ?>?produk_id=${produkId}`)
        .then(res => res.json())
        .then(data => {
          console.log('DETAIL:', data); // 🔥 WAJIB ADA DI CONSOLE

          if (!data) return;

          hargaAsli.value  = data.harga_asli ?? '';
          hargaPromo.value = data.harga_promo ?? '';
          maksDiskon.value = data.maks_diskon ?? '';
          detail.value     = data.deskripsi ?? '';
        })
        .catch(err => {
          console.error('ERROR:', err);
        });
    });
  });
  </script>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
</body>
</html>
