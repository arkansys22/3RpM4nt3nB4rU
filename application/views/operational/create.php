<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Operational Finance</title>
    <link rel="icon" href="<?= favicon_url() ?>" type="image/x-icon">
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
              <h1 class="text-2xl font-bold mb-4">Tambah Operational Finance</h1>
              <form action="<?= site_url('finance-operational/store') ?>" method="post" class="bg-white p-6 shadow-md rounded">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <label class="block mb-2">Nama Transaksi</label>
                <input type="text" name="nama_transaksi" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Tanggal Transaksi</label>
                <input type="date" name="tgl_transaksi" class="w-full px-4 py-2 border rounded mb-4" required>
               
                <label class="block mb-2">Nominal</label>
                <input type="text" id="formattedNumber" class="w-full px-4 py-2 border rounded mb-4" oninput="formatNumber(this)" name="nominal" required>

                <label class="block mb-2">Kategori</label>
                <div id="kategoriBox" class="relative mb-4">
                <button type="button" id="kategoriTombol" class="flex w-full items-center justify-between px-4 py-2 border rounded text-left"><span id="kategoriTeks">-</span><span class="ml-2 text-xs">&#9660;</span></button>
                <div id="kategoriPanel" class="absolute left-0 right-0 z-50 mt-1 hidden rounded border bg-white shadow-lg dark:bg-boxdark">
                    <input type="text" id="kategoriCari" placeholder="Cari kategori (kode atau nama)..." autocomplete="off" class="w-full px-4 py-2 border-b outline-none bg-transparent">
                    <ul id="kategoriDaftar" class="max-h-60 overflow-y-auto"></ul>
                </div>
                <select name="kategori" id="kategoriSelect" onchange="toggleStaffGaji(this.value)" tabindex="-1" style="position:absolute;left:0;bottom:0;width:100%;height:1px;opacity:0;pointer-events:none;" required>
                        <option value="-">-</option>
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
                </div>

                <!-- Cuma muncul kalau Kategori = 6201.01 (Biaya Gaji, Lembur & THR) -- transaksi
                     pembayaran gaji perlu dikaitkan ke satu user crew supaya jelas ini gaji siapa. -->
                <div id="staffGajiField" class="hidden">
                    <label class="block mb-2">Staff</label>
                    <div id="staffBox" class="relative mb-4">
                    <button type="button" id="staffTombol" class="flex w-full items-center justify-between px-4 py-2 border rounded text-left"><span id="staffTeks">- Pilih Staff -</span><span class="ml-2 text-xs">&#9660;</span></button>
                    <div id="staffPanel" class="absolute left-0 right-0 z-50 mt-1 hidden rounded border bg-white shadow-lg dark:bg-boxdark">
                        <input type="text" id="staffCari" placeholder="Cari staff..." autocomplete="off" class="w-full px-4 py-2 border-b outline-none bg-transparent">
                        <ul id="staffDaftar" class="max-h-60 overflow-y-auto"></ul>
                    </div>
                    <select name="staff_id_session" id="staffSelect" tabindex="-1" style="position:absolute;left:0;bottom:0;width:100%;height:1px;opacity:0;pointer-events:none;">
                        <option value="">- Pilih Staff -</option>
                        <?php foreach ($crew_list as $c): ?>
                        <option value="<?= $c->id_session ?>"><?= htmlspecialchars($c->nama) ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>

                <!-- Cuma muncul kalau Kategori = 6100.02 (Biaya komisi) -- pilih user aktif penerima komisi. -->
                <div id="komisiField" class="hidden">
                    <label class="block mb-2">Penerima Komisi</label>
                    <select name="komisi_id_session" class="w-full px-4 py-2 border rounded mb-4">
                        <option value="">- Pilih User -</option>
                        <?php foreach (($komisi_list ?? []) as $u): ?>
                        <option value="<?= $u->id_session ?>"><?= htmlspecialchars($u->nama) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <label class="block mb-2">Periode</label>
                <select name="periode" class="w-full px-4 py-2 border rounded mb-4" required> 
                        <option value="-">-</option>
                        <?php foreach ($periode as $p) {
                              if (empty($periode)){
                                echo"
                                <option value=''>-</option>
                                <option value='$p[operational_acc_periode_id]'> $p[operational_acc_periode_nama]</option> ";
                              }else{
                                echo"<option value='$p[operational_acc_periode_id]'>$p[operational_acc_periode_nama]</option>";
                           }
                        } ?>
                </select>

                <div class="flex flex-col sm:flex-row justify-end">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-600 sm:w-24 mb-2 sm:mb-0 text-center">Simpan</button>
                <a href="<?= site_url('finance-operational') ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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

    // Field "Staff" cuma relevan buat kategori 6201.01 (Biaya Gaji, Lembur & THR).
    function toggleStaffGaji(kategoriValue) {
        var field = document.getElementById('staffGajiField');
        field.classList.toggle('hidden', kategoriValue !== '6201.01');
        document.getElementById('komisiField').classList.toggle('hidden', kategoriValue !== '6100.02');
    }
    // Jalankan sekali di awal, buat jaga-jaga kalau browser restore nilai select
    // (mis. tombol back) tanpa memicu event "change".
    toggleStaffGaji(document.getElementById('kategoriSelect').value);

    // Kategori dengan pencarian di dalam kotak pilihan: klik untuk membuka daftar, ketik untuk menyaring
    (function () {
        var select = document.getElementById('kategoriSelect');
        var box = document.getElementById('kategoriBox');
        var tombol = document.getElementById('kategoriTombol');
        var teks = document.getElementById('kategoriTeks');
        var panel = document.getElementById('kategoriPanel');
        var cari = document.getElementById('kategoriCari');
        var daftar = document.getElementById('kategoriDaftar');
        var semua = Array.prototype.map.call(select.options, function (o) {
            return { value: o.value, text: o.text };
        });

        function pilih(o) {
            select.value = o.value;
            teks.textContent = o.text;
            tutup();
            select.dispatchEvent(new Event('change'));
        }
        function render() {
            var q = cari.value.trim().toLowerCase();
            daftar.innerHTML = '';
            var ada = false;
            semua.forEach(function (o) {
                if (q !== '' && o.text.toLowerCase().indexOf(q) === -1) { return; }
                ada = true;
                var li = document.createElement('li');
                li.textContent = o.text;
                li.className = 'cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-meta-4' + (o.value === select.value ? ' font-semibold' : '');
                li.addEventListener('mousedown', function (e) { e.preventDefault(); pilih(o); });
                daftar.appendChild(li);
            });
            if (!ada) {
                var kosong = document.createElement('li');
                kosong.textContent = 'Kategori tidak ditemukan';
                kosong.className = 'px-4 py-2 text-gray-500';
                daftar.appendChild(kosong);
            }
        }
        function buka() { panel.classList.remove('hidden'); cari.value = ''; render(); cari.focus(); }
        function tutup() { panel.classList.add('hidden'); }

        teks.textContent = select.options[select.selectedIndex].text;
        tombol.addEventListener('click', function () { panel.classList.contains('hidden') ? buka() : tutup(); });
        cari.addEventListener('input', render);
        cari.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { tutup(); tombol.focus(); }
            if (e.key === 'Enter') {
                e.preventDefault();
                var q = cari.value.trim().toLowerCase();
                var cocok = semua.filter(function (o) { return q === '' || o.text.toLowerCase().indexOf(q) !== -1; });
                if (cocok.length) { pilih(cocok[0]); }
            }
        });
        document.addEventListener('click', function (e) { if (!box.contains(e.target)) { tutup(); } });
    })();

    // Staff dengan pencarian di dalam kotak pilihan: klik untuk membuka daftar, ketik untuk menyaring
    (function () {
        var select = document.getElementById('staffSelect');
        var box = document.getElementById('staffBox');
        var tombol = document.getElementById('staffTombol');
        var teks = document.getElementById('staffTeks');
        var panel = document.getElementById('staffPanel');
        var cari = document.getElementById('staffCari');
        var daftar = document.getElementById('staffDaftar');
        var semua = Array.prototype.map.call(select.options, function (o) {
            return { value: o.value, text: o.text };
        });

        function pilih(o) {
            select.value = o.value;
            teks.textContent = o.text;
            tutup();
            select.dispatchEvent(new Event('change'));
        }
        function render() {
            var q = cari.value.trim().toLowerCase();
            daftar.innerHTML = '';
            var ada = false;
            semua.forEach(function (o) {
                if (q !== '' && o.text.toLowerCase().indexOf(q) === -1) { return; }
                ada = true;
                var li = document.createElement('li');
                li.textContent = o.text;
                li.className = 'cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-meta-4' + (o.value === select.value ? ' font-semibold' : '');
                li.addEventListener('mousedown', function (e) { e.preventDefault(); pilih(o); });
                daftar.appendChild(li);
            });
            if (!ada) {
                var kosong = document.createElement('li');
                kosong.textContent = 'Staff tidak ditemukan';
                kosong.className = 'px-4 py-2 text-gray-500';
                daftar.appendChild(kosong);
            }
        }
        function buka() { panel.classList.remove('hidden'); cari.value = ''; render(); cari.focus(); }
        function tutup() { panel.classList.add('hidden'); }

        teks.textContent = select.options[select.selectedIndex].text;
        tombol.addEventListener('click', function () { panel.classList.contains('hidden') ? buka() : tutup(); });
        cari.addEventListener('input', render);
        cari.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { tutup(); tombol.focus(); }
            if (e.key === 'Enter') {
                e.preventDefault();
                var q = cari.value.trim().toLowerCase();
                var cocok = semua.filter(function (o) { return q === '' || o.text.toLowerCase().indexOf(q) !== -1; });
                if (cocok.length) { pilih(cocok[0]); }
            }
        });
        document.addEventListener('click', function (e) { if (!box.contains(e.target)) { tutup(); } });
    })();
  </script>
</body>
</html>
