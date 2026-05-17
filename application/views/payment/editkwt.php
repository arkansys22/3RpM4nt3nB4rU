<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kwitansi</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'payment', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
          <h1 class="text-2xl font-bold mb-4">Edit Kwitansi <?= $payment->transactions_id ?></h1>
          <form id="edit-kwitansi-form" action="<?= site_url('payment/update2/' . $payment->id_session . '/' . ($payment->payment_id_session ?? '')) ?>" method="post" class="bg-white p-6 shadow-md rounded">
            <label for="total_paid" class="block mb-2 font-medium">Total Dibayar:</label>
            <input type="text" id="total_paid" name="total_paid" value="<?= number_format($payment->total_paid, 0, ',', '.') ?>" class="w-full px-4 py-2 border rounded mb-4" required>

            <label for="date" class="block mb-2 font-medium">Tanggal:</label>
            <input type="date" name="date" value="<?= $payment->date ?>" class="w-full px-4 py-2 border rounded mb-4" required>

            <label for="detail" class="block mb-2 font-medium">Detail:</label>
            <textarea name="detail" class="w-full px-4 py-2 border rounded mb-4"><?= htmlspecialchars(json_decode($payment->detail)) ?></textarea>


            <label class="block mb-2">Pembayaran ke</label>
                <select id="metodep" name="metodep" class="w-full px-4 py-2 border rounded mb-4" required>
                    <option value="">- Pilih -</option>

                    <?php
                    $pembayaranList = [
                        'Kesatu',
                        'Kedua',
                        'Ketiga',
                        'Keempat',
                        'Kelima',
                        'Keenam',
                        'Ketujuh',
                        'Kedelapan'
                    ];

                    foreach ($pembayaranList as $item):
                        $value = "Pembayaran {$item} {$payment->client_name}";
                    ?>
                        <option value="<?= $value ?>" <?= ($payment->metodep == $value) ? 'selected' : '' ?>>
                            <?= $value ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <label for="status" class="block mb-2 font-medium">Status:</label>
            <select name="status" id="status" class="w-full px-4 py-2 border rounded mb-4" required>
                <option value="Pending" <?= $payment->status === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Paid" <?= $payment->status === 'Paid' ? 'selected' : '' ?>>Paid</option>
            </select>

            <div  id="kategori-wrapper" class="<?= ($payment->status === 'Paid') ? '' : 'hidden' ?>"  >

                <label class="block mb-2 font-medium">
                    Kategori
                </label>

                <select 
                    name="kategori"
                    id="kategori"
                    class="w-full px-4 py-2 border rounded mb-4"
                    <?= ($payment->status === 'Paid') ? 'required' : '' ?>
                >
                    <option value="">- Pilih Kategori -</option>

                    <?php foreach ($kategori as $p): ?>

                        <option
                            value="<?= $p['nomer_kategori'] ?>"
                            <?= ($payment->kategori == $p['nomer_kategori']) ? 'selected' : '' ?>
                        >
                            <?= $p['nomer_kategori'] ?> |
                            <?= $p['nama_kategori'] ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="flex flex-col sm:flex-row justify-end">
              <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Update</button>
              <a href="<?= base_url('project/lihat/' . $project->id_session) ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
            </div>
          </form>
        </div>
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
  <script>
    // Format angka dengan titik saat pengguna mengetik
    document.getElementById('total_paid').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); // Hapus karakter non-angka
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambahkan titik setiap 3 angka
        e.target.value = value;
    });

    // Hapus titik sebelum formulir dikirim
    document.getElementById('edit-kwitansi-form').addEventListener('submit', function (e) {
        const totalPaidInput = document.getElementById('total_paid');
        totalPaidInput.value = totalPaidInput.value.replace(/\./g, ''); // Hapus semua titik
    });
  </script>

  <script>
      const statusSelect = document.getElementById('status');
      const kategoriWrapper = document.getElementById('kategori-wrapper');
      const kategoriSelect = document.getElementById('kategori');

      function toggleKategori() {

          if (statusSelect.value === 'Paid') {

              kategoriWrapper.classList.remove('hidden');
              kategoriSelect.setAttribute('required', 'required');

          } else {

              kategoriWrapper.classList.add('hidden');
              kategoriSelect.removeAttribute('required');
              kategoriSelect.value = '';
          }
      }

      // saat halaman load
      toggleKategori();

      // saat status berubah
      statusSelect.addEventListener('change', toggleKategori);
  </script>
</body>
</html>
