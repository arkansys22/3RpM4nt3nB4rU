<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
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
              <h1 class="text-2xl font-bold mb-4">Edit Invoice <?= $payment->transactions_id ?></h1>
              <form action="<?= site_url('payment/update/' . $payment->id_session . '/' . ($payment->transactions_id ?? '')) ?>" method="post" class="bg-white p-6 shadow-md rounded">

                <label class="block mb-2">Jenis Invoice</label>
                <select 
                    id="typeinvoice" 
                    name="typeinvoice" 
                    class="w-full px-4 py-2 border rounded mb-4" 
                    required
                >

                    <option value="">- Pilih Asal Invoice -</option>

                    <option 
                        value="<?= $project->potensial_clients_id_session ?>"
                        <?= ($payment->potensial_clients_id_session == $project->potensial_clients_id_session) ? 'selected' : '' ?>>
                        Dari Proposal
                    </option>

                    <option 
                        value="Penambahan"
                        <?= ($payment->potensial_clients_id_session == 'Penambahan') ? 'selected' : '' ?>>
                        Penambahan
                    </option>

                </select>

                <label for="date" class="block mb-2 font-medium">Tanggal Pembuatan</label>
                <input type="date" name="date" value="<?= $payment->date ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label for="due_date" class="block mb-2 font-medium">Jatuh Tempo</label>
                <input type="date" name="due_date" value="<?= $payment->due_date ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label for="total_bill" class="block mb-2 font-medium">Total Tagihan:</label>
                <input type="text" id="total_bill" name="total_bill" oninput="formatNumber(this)" value="<?= number_format($payment->total_bill, 0, ',', '.') ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Kategori</label>
                <select name="kategori" class="w-full px-4 py-2 border rounded mb-4" required> 
                        <option value="-">- Pilih Kategori -</option>
                        <?php foreach ($kategori as $p) {
                              if (empty($kategori)){
                                echo"
                                <option value=''>-</option>
                                <option value='$p[nomer_kategori]'>$p[nomer_kategori] - $p[nama_kategori]</option> ";
                              }else{
                                echo"<option value='$p[nomer_kategori]'>$p[nomer_kategori] | $p[nama_kategori]</option>";
                           }
                        } ?>
                </select>



                <label class="block mb-2">Metode Pembayaran</label>
                <select id="metodep" name="metodep" class="w-full px-4 py-2 border rounded mb-4" required>
                    <option value="">- Pilih -</option>

                    <option value="Default"
                        <?= ($payment->metodep == 'Default') ? 'selected' : '' ?>>
                        Default
                    </option>

                    <option value="Custom"
                        <?= ($payment->metodep == 'Custom') ? 'selected' : '' ?>>
                        Custom
                    </option>
                </select>


                <!-- Detail Pembayaran -->
                <div id="detail-wrapper" class="hidden">

                    <label class="block mb-2 font-medium">
                        Detail Pembayaran
                    </label>

                    <div id="detail-section" class="space-y-3">

                        <?php
                        $details = [];

                        if (!empty($payment->detail)) {
                            $decoded = json_decode($payment->detail, true);

                            if (is_array($decoded)) {
                                $details = $decoded;
                            } else {
                                $details = [$payment->detail];
                            }
                        }

                        if (empty($details)) {
                            $details[] = '';
                        }
                        ?>

                        <?php foreach ($details as $index => $detail): ?>
                        <div class="detail-item flex gap-2 items-start">

                            <input
                                type="text"
                                name="detail[]"
                                value="<?= htmlspecialchars($detail) ?>"
                                class="detail-input flex-1 px-4 py-2 border rounded-lg"
                                placeholder="Contoh: Pembayaran pertama DP 20% maksimal H+7"
                            >

                            <button
                                type="button"
                                class="remove-detail bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg <?= $index == 0 ? 'hidden' : '' ?>">
                                Hapus
                            </button>

                        </div>
                        <?php endforeach; ?>

                    </div>

                    <button
                        type="button"
                        id="add-detail-btn"
                        class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        + Tambah Detail
                    </button>

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
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.querySelector('form');
        const totalBillInput = document.getElementById('total_bill');
        const typeinvoice = document.getElementById('typeinvoice');
        const metodeSelect = document.getElementById('metodep');
        const detailWrapper = document.getElementById('detail-wrapper');
        const detailSection = document.getElementById('detail-section');
        const addButton = document.getElementById('add-detail-btn');

        // format angka
        function formatNumber(input) {
            let value = input.value.replace(/\D/g, '');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = value;
        }

        window.formatNumber = formatNumber;

        // submit form
        form.addEventListener('submit', function () {
            totalBillInput.value =
                totalBillInput.value.replace(/\./g, '');
        });

        // jenis invoice
       // jenis invoice
        typeinvoice.addEventListener('change', function () {

            const proposalValue =
                '<?= $project->potensial_clients_id_session ?>';

            // Jika pilih Dari Proposal
            if (this.value === proposalValue) {

                fetch("<?= base_url('Crud_payment/get_total_penawaran') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type":
                            "application/x-www-form-urlencoded"
                    },
                    body:
                        "id_session=" +
                        encodeURIComponent(this.value)
                })
                .then(response => response.json())
                .then(data => {

                    let total =
                        parseInt(data.total) || 0;

                    totalBillInput.value =
                        total.toLocaleString('id-ID');
                })
                .catch(error => {
                    console.error(error);
                });

            } 
            // Jika pilih Penambahan
            else if (this.value === 'Penambahan') {

                // kosongkan total bill
                totalBillInput.value = '';

            } 
            // Jika kembali ke pilih asal invoice
            else {

                totalBillInput.value = '';
            }
        });

        // simpan nilai awal saat page load
        const initialInvoiceType = typeinvoice.value;

        // saat pertama load jangan kosongkan,
        // hanya trigger logic sesuai pilihan
        if (initialInvoiceType === '<?= $project->potensial_clients_id_session ?>') {
            typeinvoice.dispatchEvent(new Event('change'));
        }

        // tampil hide detail custom
        function toggleDetailSection() {

            const isCustom =
                metodeSelect.value === 'Custom';

            detailWrapper.classList.toggle(
                'hidden',
                !isCustom
            );

            document
                .querySelectorAll('.detail-input')
                .forEach(input => {

                    input.required = isCustom;

                    if (!isCustom) {
                        input.value = '';
                    }
                });
        }

        metodeSelect.addEventListener(
            'change',
            toggleDetailSection
        );

        // tambah detail
        addButton.addEventListener(
            'click',
            function () {

                const wrapper =
                    document.createElement('div');

                wrapper.className =
                    'detail-item flex gap-2 items-start';

                wrapper.innerHTML = `
                    <input
                        type="text"
                        name="detail[]"
                        class="detail-input flex-1 px-4 py-2 border rounded-lg"
                        placeholder="Masukkan detail pembayaran"
                        required
                    >

                    <button
                        type="button"
                        class="remove-detail bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                        Hapus
                    </button>
                `;

                detailSection.appendChild(wrapper);
            }
        );

        // hapus detail
        document.addEventListener(
            'click',
            function (e) {

                if (
                    e.target.classList.contains(
                        'remove-detail'
                    )
                ) {
                    e.target
                        .closest('.detail-item')
                        .remove();
                }
            }
        );

        // init
        toggleDetailSection();
    });
    </script>
</body>
</html>
