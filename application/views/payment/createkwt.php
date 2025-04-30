<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kwitansi</title>
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
              <h1 class="text-2xl font-bold mb-4">Tambah Kwitansi <?= $project->client_name ?></h1>
              <form action="<?= base_url('payment/store2') ?>" method="post" class="bg-white p-6 shadow-md rounded">
              <input type="hidden" name="id_session" value="<?= $project->id_session ?>">
              <input type="hidden" name="total_bill" value="0"> <!-- Ensure total_bill is always 0 -->

                <label class="block mb-2">Total</label>
                <input type="text" id="formattedNumber" oninput="formatNumber(this)" name="total_paid" step="0.01" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Tanggal Pembuatan</label>
                <input type="date" name="date" class="w-full px-4 py-2 border rounded mb-4">
                <!-- Exclude due_date -->

                <label class="block mb-2">Kode Unik</label>
                <input type="number" name="number" class="w-full px-4 py-2 border rounded mb-4">

                <!-- Section for detail -->
                        <label class="block mb-2">Detail</label>
                        <textarea name="detail" class="w-full px-4 py-2 border rounded mb-4" required></textarea>

                <div class="flex flex-col sm:flex-row justify-end">
                  <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-600 sm:w-24 mb-2 sm:mb-0 text-center">Simpan</button>
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
    function formatNumber(input) {
        let value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Add dots for formatting
        input.value = value;
    }

    document.querySelector('form').addEventListener('submit', function (e) {
        const totalPaidInput = document.querySelector('input[name="total_paid"]');
        totalPaidInput.value = totalPaidInput.value.replace(/\./g, ''); // Remove dots before submitting
    });
  </script>
  <script>
    document.getElementById('add-detail-btn').addEventListener('click', function() {
        const detailSection = document.getElementById('detail-section');
        const existingDetail = detailSection.querySelector('textarea');
        
        if (existingDetail) {
            const newDetailWrapper = document.createElement('div');
            newDetailWrapper.classList.add('mb-2');

            const newDetailLabel = document.createElement('label');
            newDetailLabel.classList.add('block', 'mb-2');
            newDetailLabel.textContent = 'Detail';

            const newDetailInput = existingDetail.cloneNode(true);
            newDetailInput.value = '';

            newDetailWrapper.appendChild(newDetailLabel);
            newDetailWrapper.appendChild(newDetailInput);
            detailSection.appendChild(newDetailWrapper);
        }
    });
</script>
</body>
</html>
