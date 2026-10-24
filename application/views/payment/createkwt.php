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
              <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="id_session" value="<?= $project->id_session ?>">
              <input type="hidden" name="total_bill" value="0"> <!-- Ensure total_bill is always 0 -->


                <label class="block mb-2 font-medium">Dari Invoice</label>

                <select 
                    name="dariinvoice"
                    id="dariinvoice"
                    class="w-full px-4 py-2 border rounded mb-4"
                >
                    <option value="">- Pilih Invoice -</option>

                    <?php foreach ($invoice as $p): ?>

                        <option
                            value="<?= $p['payment_id_session'] ?>"
                        >
                            <?= $p['nama_transaksi'] ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <div id="pilihan-pembayaran-wrapper" class="hidden">
                    <label class="block mb-2 font-medium">Pilih Pembayaran (dari invoice)</label>
                    <select id="pilihan-pembayaran" class="w-full px-4 py-2 border rounded mb-1">
                        <option value="">- Pilih pembayaran -</option>
                    </select>
                    <p class="text-xs text-gray-500 mb-4">Memilih pembayaran mengisi otomatis Total Dibayar dan "Pembayaran ke". Detail diisi manual.</p>
                </div>

<label class="block mb-2">Total Dibayar</label>
                <input type="text" id="formattedNumber" oninput="formatNumber(this)" name="total_paid" step="0.01" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Tanggal</label>
                <input type="date" name="date" class="w-full px-4 py-2 border rounded mb-4">
                <!-- Exclude due_date -->

                <?php
                // Generate angka random 3 digit (100 - 999)
                $kode_unik = rand(100, 999);
                ?>

                <input type="hidden" name="number" value="<?= $kode_unik ?>">

                <!-- Section for detail -->
                <label class="block mb-2">Detail</label>
                <textarea name="detail" class="w-full px-4 py-2 border rounded mb-4" required></textarea>

                <!-- Pembayaran ke -->
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

                    // Pembayaran yang sudah dibayar (kwitansi Paid) tidak ditampilkan lagi
                    $sudahDibayar = urutan_pembayaran_dibayar($project->id_session);

                    foreach ($pembayaranList as $idx => $item):
                        if (in_array($idx + 1, $sudahDibayar, true)) { continue; }
                        $value = "Pembayaran {$item} {$project->client_name}";
                    ?>
                        <option value="<?= $value ?>">
                            <?= $value ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Status -->
                <label for="status" class="block mb-2 font-medium">Status:</label>
                <select name="status" id="status" class="w-full px-4 py-2 border rounded mb-4" required>
                    <option value="Pending" selected>Pending</option>
                    <option value="Paid">Paid</option>
                </select>

                <!-- Kategori -->
                <div id="kategori-wrapper" class="hidden">

                    <label class="block mb-2 font-medium">
                        Kategori
                    </label>

                    <select
                        name="kategori"
                        id="kategori"
                        class="w-full px-4 py-2 border rounded mb-4"
                    >
                        <option value="">- Pilih Kategori -</option>

                        <?php foreach ($kategori as $p): ?>
                            <option value="<?= $p['nomer_kategori'] ?>">
                                <?= $p['nomer_kategori'] ?> |
                                <?= $p['nama_kategori'] ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
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
    document.addEventListener('DOMContentLoaded', function () {

        // Format angka ribuan
        window.formatNumber = function(input) {
            let value = input.value.replace(/\D/g, '');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = value;
        };

        // Hapus titik sebelum submit
        const form = document.querySelector('form');

        if (form) {
            form.addEventListener('submit', function () {
                const totalPaidInput = document.querySelector('input[name="total_paid"]');

                if (totalPaidInput) {
                    totalPaidInput.value = totalPaidInput.value.replace(/\./g, '');
                }
            });
        }

        // Show / Hide kategori berdasarkan status
        const statusSelect = document.getElementById('status');
        const kategoriWrapper = document.getElementById('kategori-wrapper');
        const kategoriSelect = document.getElementById('kategori');

        function toggleKategori() {

            // Cegah error jika element tidak ditemukan
            if (!statusSelect || !kategoriWrapper || !kategoriSelect) return;

            if (statusSelect.value === 'Paid') {
                kategoriWrapper.classList.remove('hidden');
                kategoriSelect.setAttribute('required', 'required');
            } else {
                kategoriWrapper.classList.add('hidden');
                kategoriSelect.removeAttribute('required');
                kategoriSelect.value = '';
            }
        }

        // Event change status
        if (statusSelect) {
            statusSelect.addEventListener('change', toggleKategori);
        }

        // Jalankan saat halaman load
        toggleKategori();
    });
  </script>
<script>
// Pilih pembayaran dari invoice: isi otomatis Total Dibayar dan "Pembayaran ke" (Detail diisi manual).
(function () {
    const invoiceSelect = document.getElementById('dariinvoice');
    const wrapper = document.getElementById('pilihan-pembayaran-wrapper');
    const pilih = document.getElementById('pilihan-pembayaran');
    const total = document.getElementById('formattedNumber');
    const metodep = document.getElementById('metodep');
    const ordinal = ['Kesatu', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam', 'Ketujuh', 'Kedelapan'];
    let data = [];

    function ribuan(n) {
        return String(n).replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    function rupiah(n) { return 'Rp ' + ribuan(n); }

    function muat() {
        data = [];
        pilih.innerHTML = '<option value="">- Pilih pembayaran -</option>';
        Array.prototype.forEach.call(metodep.options, function (o) { o.hidden = false; o.disabled = false; });
        if (!invoiceSelect.value) { wrapper.classList.add('hidden'); return; }
        fetch('<?= base_url('Crud_payment/pilihan_pembayaran') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'payment_id_session=' + encodeURIComponent(invoiceSelect.value)
                + '&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>'
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            data = d.pilihan || [];
            // pembayaran yang sudah dibayar tidak ditampilkan (di dropdown ini maupun di "Pembayaran ke")
            const dibayar = data.filter(function (p) { return p.dibayar; }).map(function (p) { return p.urutan; });
            Array.prototype.forEach.call(metodep.options, function (o) {
                const m = /^Pembayaran (\S+) /.exec(o.value);
                const n = m ? ordinal.indexOf(m[1]) + 1 : 0;
                const sembunyi = n > 0 && dibayar.indexOf(n) !== -1;
                o.hidden = sembunyi; o.disabled = sembunyi;
                if (sembunyi && metodep.value === o.value) { metodep.value = ''; }
            });
            data.forEach(function (p, i) {
                if (p.dibayar) { return; }
                const opt = document.createElement('option');
                opt.value = i;
                opt.textContent = p.label + (p.nominal ? ' — ' + rupiah(p.nominal) : '');
                pilih.appendChild(opt);
            });
            wrapper.classList.toggle('hidden', pilih.options.length <= 1);
        })
        .catch(function (e) { console.error('Pilihan pembayaran:', e); });
    }

    pilih.addEventListener('change', function () {
        const p = data[pilih.value];
        if (!p) { return; }
        // Total Dibayar
        total.value = ribuan(p.nominal);
        // Pembayaran ke
        const nama = ordinal[p.urutan - 1];
        if (nama) {
            Array.prototype.forEach.call(metodep.options, function (o) {
                if (o.value.indexOf('Pembayaran ' + nama + ' ') === 0) { metodep.value = o.value; }
            });
        }
        // Detail sengaja TIDAK diisi otomatis -- diisi manual oleh user
    });

    invoiceSelect.addEventListener('change', muat);
    muat();
})();
</script>
</body>
</html>
