<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Invoice</title>
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
              <h1 class="text-2xl font-bold mb-4">Tambah Invoice <?= $project->client_name ?></h1>
              <form action="<?= base_url('payment/store') ?>" method="post" class="bg-white p-6 shadow-md rounded">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="id_session" value="<?= $project->id_session ?>">
              <input type="hidden" name="total_paid" value="0"> <!-- Ensure total_paid is always 0 -->

                <label class="block mb-2">Jenis Invoice</label>
                <select id="typeinvoice" name="typeinvoice" class="w-full px-4 py-2 border rounded mb-4" required>
    
                    <option value="">- Pilih Asal Invoice -</option>

                    <option value="<?= $project->potensial_clients_id_session ?>"
                        <?= set_select('typeinvoice', $project->potensial_clients_id_session) ?>>
                        Dari Proposal
                    </option>

                    <option value="Penambahan"
                        <?= set_select('typeinvoice', 'Penambahan') ?>>
                        Penambahan
                    </option>

                </select>

                <label class="block mb-2">Nama Invoice</label>
                <?php
                // Nama invoice otomatis: "Invoice" + nama di proposal penawaran project ini
                $pc_nama = get_instance()->db->select("pc_name")->get_where("potensial_clients", ["id_session" => $project->potensial_clients_id_session])->row();
                $nama_invoice_otomatis = trim("Invoice " . ($pc_nama->pc_name ?? $project->client_name));
                ?>
                <input type="text" id="nama_transaksi" name="nama_transaksi" value="<?= set_value("nama_transaksi", $nama_invoice_otomatis) ?>" class="w-full px-4 py-2 border rounded mb-4">
                <p class="text-xs text-gray-500 -mt-3 mb-4">Terisi otomatis dari nama di proposal penawaran, bisa diubah.</p>

                <label class="block mb-2">Tanggal Pembuatan</label>
                <input type="date" name="date" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Jatuh Tempo</label>
                <input type="date" name="due_date" class="w-full px-4 py-2 border rounded mb-4"> <!-- Include due_date -->

                <?php
                // Generate angka random 3 digit (100 - 999)
                $kode_unik = rand(100, 999);
                ?>

                <input type="hidden" name="number" value="<?= $kode_unik ?>">

                <label class="block mb-2">Total</label>
                <input type="text" id="total_bill" oninput="formatNumber(this)" name="total_bill" step="0.01" class="w-full px-4 py-2 border rounded mb-4" required>
                
                <label class="block mb-2">Kategori Account Receivable</label>
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

                <label class="block mb-2">Kategori Sales</label>
                <select name="kategori2" class="w-full px-4 py-2 border rounded mb-4" required> 
                        <option value="-">- Pilih Kategori -</option>
                        <?php foreach ($kategori2 as $p) {
                              if (empty($kategori2)){
                                echo"
                                <option value=''>-</option>
                                <option value='$p[nomer_kategori]'>$p[nomer_kategori] - $p[nama_kategori]</option> ";
                              }else{
                                echo"<option value='$p[nomer_kategori]'>$p[nomer_kategori] | $p[nama_kategori]</option>";
                           }
                        } ?>
                </select>

                <label class="block mb-2">Metode Pembayaran</label>
                <select id= "metodep" name="metodep" class="w-full px-4 py-2 border rounded mb-4" required>
                    <option value="">- Pilih -</option>
                    <option value="Default" <?= set_select('metodep', 'Default') ?>>Default</option>
                    <option value="Custom" <?= set_select('metodep', 'Custom') ?>>Custom</option>
                  </select>                

               <!-- Section for detail -->
                <!-- Detail Pembayaran -->
                <div id="detail-wrapper" class="hidden">

                    <label class="block mb-2 font-medium">
                        Detail Pembayaran
                    </label>

                    <div id="detail-section" class="space-y-3">

                        <!-- Pembayaran pertama (item berikutnya ditambah lewat tombol di bawah) -->
                        <div class="detail-item">
                            <label class="detail-label block mb-1 text-sm font-semibold">Pembayaran Pertama</label>
                            <div class="flex flex-col sm:flex-row gap-2 items-start">
                                <div class="flex w-full sm:w-56 flex-shrink-0 items-center rounded-lg border">
                                    <span class="pl-3 text-sm">Rp</span>
                                    <input type="text" inputmode="numeric" autocomplete="off"
                                        name="detail[0][nominal]"
                                        class="detail-input detail-nominal w-full bg-transparent px-2 py-2 outline-none"
                                        placeholder="0">
                                </div>
                                <input type="text" name="detail[0][teks]"
                                    class="detail-input detail-teks w-full flex-1 rounded-lg border px-4 py-2"
                                    placeholder="Teks custom, contoh: DP maksimal H+7">
                                <button type="button"
                                    class="remove-detail bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg hidden">
                                    Hapus
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Muncul setelah pembayaran terakhir diisi -->
                    <button
                        type="button"
                        id="add-detail-btn"
                        class="hidden mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        + Tambah Pembayaran
                    </button>

                </div>


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
        const totalBillInput = document.querySelector('input[name="total_bill"]');
        totalBillInput.value = totalBillInput.value.replace(/\./g, ''); // Remove dots before submitting

        const dpInput = document.querySelector('input[name="DP"]');
        dpInput.value = dpInput.value.replace(/\./g, ''); // Remove dots before submitting DP
    });
  </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const detailSection = document.getElementById('detail-section');
        const addButton = document.getElementById('add-detail-btn');
        const metodeSelect = document.getElementById('metodep');

        // ===== Detail pembayaran custom: tiap pembayaran = nominal (titik ribuan) + teks custom =====
        const urutan = ['Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam', 'Ketujuh', 'Kedelapan', 'Kesembilan', 'Kesepuluh'];

        function detailItems() {
            return detailSection.querySelectorAll('.detail-item');
        }

        // Format angka dengan titik ribuan (1.000.000)
        function formatRibuan(input) {
            const digits = input.value.replace(/\D/g, '');
            input.value = digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Nomori ulang label & nama field, tombol Hapus hanya di item terakhir (selain yang pertama),
        // dan tombol tambah baru muncul kalau nominal pembayaran terakhir sudah diisi.
        function refreshDetail() {
            const list = detailItems();
            list.forEach(function (item, i) {
                item.querySelector('.detail-label').textContent =
                    'Pembayaran ' + (urutan[i] || ('ke-' + (i + 1)));
                item.querySelector('.detail-nominal').name = 'detail[' + i + '][nominal]';
                item.querySelector('.detail-teks').name = 'detail[' + i + '][teks]';
                item.querySelector('.remove-detail')
                    .classList.toggle('hidden', !(i > 0 && i === list.length - 1));
            });
            const terakhir = list[list.length - 1].querySelector('.detail-nominal');
            addButton.classList.toggle('hidden', terakhir.value.trim() === '');
        }

        addButton.addEventListener('click', function () {
            const wrapper = document.createElement('div');
            wrapper.className = 'detail-item';
            wrapper.innerHTML = `
                <label class="detail-label block mb-1 text-sm font-semibold"></label>
                <div class="flex flex-col sm:flex-row gap-2 items-start">
                    <div class="flex w-full sm:w-56 flex-shrink-0 items-center rounded-lg border">
                        <span class="pl-3 text-sm">Rp</span>
                        <input type="text" inputmode="numeric" autocomplete="off"
                            class="detail-input detail-nominal w-full bg-transparent px-2 py-2 outline-none"
                            placeholder="0" required>
                    </div>
                    <input type="text"
                        class="detail-input detail-teks w-full flex-1 rounded-lg border px-4 py-2"
                        placeholder="Teks custom, contoh: DP maksimal H+7">
                    <button type="button"
                        class="remove-detail bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                        Hapus
                    </button>
                </div>
            `;
            detailSection.appendChild(wrapper);
            refreshDetail();
            wrapper.querySelector('.detail-nominal').focus();
        });

        detailSection.addEventListener('input', function (e) {
            if (e.target.classList.contains('detail-nominal')) {
                formatRibuan(e.target);
            }
            refreshDetail();
        });

        detailSection.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-detail')) {
                e.target.closest('.detail-item').remove();
                refreshDetail();
            }
        });

        // Ganti metode: sisakan satu item (nilainya sudah dikosongkan kalau bukan Custom)
        metodeSelect.addEventListener('change', function () {
            const list = detailItems();
            for (let i = list.length - 1; i > 0; i--) { list[i].remove(); }
            refreshDetail();
        });
        refreshDetail();
    });
</script>

<script>
document.getElementById('typeinvoice').addEventListener('change', function () {

    const typeinvoice = this.value;
    const totalBillInput = document.getElementById('total_bill');

    // value proposal
    const proposalValue = '<?= $project->potensial_clients_id_session ?>';

    // Jika pilih Dari Proposal
    if (typeinvoice === proposalValue) {

        fetch("<?= base_url('Crud_payment/get_total_penawaran') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "id_session=" + encodeURIComponent(typeinvoice) + "&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>"
        })
        .then(response => response.json())
        .then(data => {

            console.log(data);

            let total = parseInt(data.total) || 0;

            // format ribuan Indonesia
            totalBillInput.value = total.toLocaleString('id-ID');

        })
        .catch(error => {
            console.error('Error:', error);
        });

    } else {

        // kosongkan jika penambahan
        totalBillInput.value = '';

    }
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const metodeSelect = document.getElementById('metodep');
    const detailWrapper = document.getElementById('detail-wrapper');

    function toggleDetailSection() {

        const isCustom = metodeSelect.value === 'Custom';

        detailWrapper.classList.toggle('hidden', !isCustom);

        document.querySelectorAll('.detail-input')
            .forEach(input => {
                input.required = isCustom && input.classList.contains('detail-nominal');

                if (!isCustom) {
                    input.value = '';
                }
            });
    }

    metodeSelect.addEventListener('change', toggleDetailSection);

    toggleDetailSection();
});
</script>
</body>
</html>
