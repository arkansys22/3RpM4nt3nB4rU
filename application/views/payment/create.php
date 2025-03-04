<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Invoice & Kwitansi</title>
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
              <h1 class="text-2xl font-bold mb-4">Tambah Invoice & Kwitansi <?= $invoice_number ?></h1>
              <form action="<?= base_url('Crud_payment/store/' . $project->id_session . '/' . $invoice_number) ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <label class="block mb-2">Invoice</label>
                <input type="text" name="invoice_<?= $invoice_number ?>" value="<?= isset($payment) ? $payment->{"invoice_$invoice_number"} : '' ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Kwitansi</label>
                <input type="text" name="kwitansi_<?= $invoice_number ?>" value="<?= isset($payment) ? $payment->{"kwitansi_$invoice_number"} : '' ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Amount</label>
                <input type="text" id="formattedNumber" oninput="formatNumber(this)" name="amount_<?= $invoice_number ?>" value="<?= isset($payment) ? $payment->{"amount_$invoice_number"} : '' ?>" step="0.01" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">DP</label>
                <input type="text" id="formattedNumber" oninput="formatNumber(this)" name="dp_<?= $invoice_number ?>" value="<?= isset($payment) ? $payment->{"dp_$invoice_number"} : '' ?>" step="0.01" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Tanggal</label>
                <input type="date" name="date_<?= $invoice_number ?>" value="<?= isset($payment) ? date('Y-m-d', strtotime($payment->{"date_$invoice_number"})) : '' ?>" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2" for="due_date_<?= $invoice_number ?>">Tanggal Jatuh Tempo</label>
                <input type="date" name="due_date_<?= $invoice_number ?>" class="w-full px-4 py-2 border rounded mb-4">

                <!-- Section for details -->
    <div id="details-section">
        <div class="mb-2">
            <label class="block mb-2" for="details">Details</label>
            <textarea name="details[]" class="w-full px-4 py-2 border rounded mb-4" required></textarea>
        </div>
    </div>

    <!-- Button to add more details -->
    <button type="button" id="add-detail-btn" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Detail</button>


                <div class="flex flex-col sm:flex-row justify-end">
                  <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-600 sm:w-24 mb-2 sm:mb-0 text-center">Simpan</button>
                  <a href="javascript:history.back()" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
      let value = input.value.replace(/\D/g, ''); // Hanya angka
      value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambah titik setiap 3 digit
      input.value = value;
  }
  </script>
  <script>
    // JavaScript to handle adding new detail fields dynamically
    document.getElementById('add-detail-btn').addEventListener('click', function() {
        const newDetailInput = document.createElement('textarea');
        newDetailInput.name = 'details[]';
        newDetailInput.required = true;

        const detailsSection = document.getElementById('details-section');
        detailsSection.appendChild(newDetailInput);
    });
</script>
<script>
    // Menyimpan tanggal jatuh tempo ke sessionStorage saat form disubmit
document.querySelector("form").addEventListener("submit", function(event) {
    let invoiceNumber = <?= $invoice_number ?>; // Ambil nomor invoice saat ini
    let dueDate = document.querySelector("input[name='due_date_" + invoiceNumber + "']").value;
    
    // Simpan tanggal jatuh tempo di sessionStorage
    sessionStorage.setItem('due_date_' + invoiceNumber, dueDate);
});

</script>
</body>
</html>
