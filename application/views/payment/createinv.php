<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Invoice</title>
    <link rel="icon" href="<?= favicon_url() ?>" type="image/x-icon">
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
                                <input type="text" name="detail[0][teks]"
                                    class="detail-input detail-teks w-full sm:flex-1 px-4 py-2 border rounded-lg"
                                    placeholder="Teks custom, contoh: DP maksimal H+7">
                                <div class="flex w-full sm:w-56 flex-shrink-0 items-center rounded-lg border">
                                    <span class="pl-3 text-sm">Rp</span>
                                    <input type="text" inputmode="numeric" autocomplete="off"
                                        name="detail[0][nominal]"
                                        class="detail-input detail-nominal w-full bg-transparent px-2 py-2 outline-none"
                                        placeholder="0">
                                </div>
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


                <!-- Metode Default: input teks pembayaran pertama sampai terakhir (diisi otomatis dari total + tanggal acara) -->
                <div id="default-wrapper" class="hidden">
                    <label class="block mb-2 font-medium">Detail Pembayaran (Default)</label>
                    <div id="default-lines" class="space-y-3 mb-2"></div>
                    <div id="default-tambahan" class="space-y-3 mb-3" <?= isset($tambahanAwal) ? 'data-awal="' . htmlspecialchars(json_encode($tambahanAwal), ENT_QUOTES) . '"' : '' ?>></div>
                        <button type="button" id="tambah-default-btn" class="mb-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">+ Tambah Pembayaran</button>

                    <p class="text-xs text-gray-500 mb-4">Teks terisi otomatis dari total &amp; tanggal acara dan bisa diubah. Baris yang tidak diubah tetap mengikuti hitungan otomatis di invoice.</p>
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
                    <input type="text"
                        class="detail-input detail-teks w-full sm:flex-1 px-4 py-2 border rounded-lg"
                        placeholder="Teks custom, contoh: DP maksimal H+7">
                    <div class="flex w-full sm:w-56 flex-shrink-0 items-center rounded-lg border">
                        <span class="pl-3 text-sm">Rp</span>
                        <input type="text" inputmode="numeric" autocomplete="off"
                            class="detail-input detail-nominal w-full bg-transparent px-2 py-2 outline-none"
                            placeholder="0" required>
                    </div>
                    <button type="button"
                        class="remove-detail bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                        Hapus
                    </button>
                </div>
            `;
            detailSection.appendChild(wrapper);
            refreshDetail();
            wrapper.querySelector('.detail-teks').focus();
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
            totalBillInput.dispatchEvent(new Event('input')); // perbarui jadwal pembayaran Default

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
<script>
// Jadwal pembayaran metode Default: ambil teks dari server (total + tanggal acara project),
// tampilkan sebagai input yang bisa diedit. Input yang belum diubah ikut diperbarui saat total berubah.
(function () {
    const metodeSelect = document.getElementById('metodep');
    const totalInput = document.getElementById('total_bill');
    const wrapper = document.getElementById('default-wrapper');
    const lines = document.getElementById('default-lines');
    const urutan = ['Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam'];
    let timer = null;

    function ribuan(n) {
        return String(n).replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // baris = [{teks, nominal}, ...]; input yang belum diubah user ikut hitungan baru
    function render(baris) {
        // jumlah baris tetap selama tanggal acara sama; kalau berbeda, bangun ulang
        if (lines.children.length !== baris.length) {
            lines.innerHTML = '';
            baris.forEach(function (b, i) {
                const box = document.createElement('div');
                const label = document.createElement('label');
                label.className = 'block mb-1 text-sm font-semibold';
                label.textContent = 'Pembayaran ' + (urutan[i] || ('ke-' + (i + 1))) + (i === baris.length - 1 ? ' (terakhir)' : '');

                const row = document.createElement('div');
                row.className = 'flex flex-col sm:flex-row gap-2 items-start';

                const teks = document.createElement('input');
                teks.type = 'text';
                teks.name = 'detail_default[]';
                teks.className = 'w-full sm:flex-1 px-4 py-2 border rounded-lg';

                const nomWrap = document.createElement('div');
                nomWrap.className = 'flex w-full sm:w-56 flex-shrink-0 items-center rounded-lg border';
                const rp = document.createElement('span');
                rp.className = 'pl-3 text-sm';
                rp.textContent = 'Rp';
                const nom = document.createElement('input');
                nom.type = 'text';
                nom.inputMode = 'numeric';
                nom.autocomplete = 'off';
                nom.name = 'detail_default_nominal[]';
                nom.placeholder = '0';
                nom.className = 'w-full bg-transparent px-2 py-2 outline-none';
                nom.addEventListener('input', function () { nom.value = ribuan(nom.value); });
                nomWrap.appendChild(rp); nomWrap.appendChild(nom);

                const asliTeks = document.createElement('input');
                asliTeks.type = 'hidden'; asliTeks.name = 'detail_default_asli[]';
                const asliNom = document.createElement('input');
                asliNom.type = 'hidden'; asliNom.name = 'detail_default_nominal_asli[]';

                row.appendChild(teks); row.appendChild(nomWrap);
                box.appendChild(label); box.appendChild(row); box.appendChild(asliTeks); box.appendChild(asliNom);
                lines.appendChild(box);

                teks.value = b.teks; teks.dataset.asli = b.teks; asliTeks.value = b.teks;
                nom.value = ribuan(b.nominal); nom.dataset.asli = String(b.nominal); asliNom.value = b.nominal;
            });
            return;
        }
        baris.forEach(function (b, i) {
            const box = lines.children[i];
            const teks = box.querySelector('input[name="detail_default[]"]');
            const nom = box.querySelector('input[name="detail_default_nominal[]"]');
            const asliTeks = box.querySelector('input[name="detail_default_asli[]"]');
            const asliNom = box.querySelector('input[name="detail_default_nominal_asli[]"]');
            if (teks.value === teks.dataset.asli) { teks.value = b.teks; }
            if (nom.value.replace(/\D/g, '') === nom.dataset.asli) { nom.value = ribuan(b.nominal); }
            teks.dataset.asli = b.teks; asliTeks.value = b.teks;
            nom.dataset.asli = String(b.nominal); asliNom.value = b.nominal;
        });
    }
    function refresh() {
        const isDefault = metodeSelect.value === 'Default';
        wrapper.classList.toggle('hidden', !isDefault);
        if (!isDefault) { return; }
        const body = 'id_session=<?= $project->id_session ?>'
            + '&total=' + encodeURIComponent(totalInput.value.replace(/\D/g, ''))
            + '&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>';
        fetch('<?= base_url('Crud_payment/jadwal_default') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body
        })
        .then(function (r) { return r.json(); })
        .then(function (d) { render(d.baris || []); if (window.nomoriTambahanDefault) { window.nomoriTambahanDefault(); } })
        .catch(function (e) { console.error('Jadwal default:', e); });
    }

    function tunda() { clearTimeout(timer); timer = setTimeout(refresh, 300); }

    metodeSelect.addEventListener('change', refresh);
    totalInput.addEventListener('input', tunda);
    refresh();
})();
</script>
<script>
// "+ Tambah Pembayaran" pada metode Default: baris tambahan (teks + nominal) setelah jadwal otomatis.
(function () {
    const box = document.getElementById('default-tambahan');
    const btn = document.getElementById('tambah-default-btn');
    if (!box || !btn) { return; }
    const kata = ['Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam', 'Ketujuh', 'Kedelapan', 'Kesembilan', 'Kesepuluh'];
    const ribuan = function (n) { return String(n).replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); };

    function jumlahOtomatis() {
        return document.querySelectorAll('input[name="detail_default[]"]').length;
    }
    function nomori() {
        const mulai = jumlahOtomatis();
        Array.prototype.forEach.call(box.children, function (row, i) {
            const n = mulai + i;
            row.querySelector('label').textContent = 'Pembayaran ' + (kata[n] || ('ke-' + (n + 1))) + ' (tambahan)';
        });
    }
    window.nomoriTambahanDefault = nomori;

    function tambah(teks, nominal) {
        const row = document.createElement('div');
        row.innerHTML =
            '<label class="block mb-1 text-sm font-semibold"></label>' +
            '<div class="flex flex-col sm:flex-row gap-2 items-start">' +
              '<input type="text" name="tambahan_teks[]" placeholder="Keterangan pembayaran, contoh: Pembayaran tambahan dekorasi" class="w-full sm:flex-1 px-4 py-2 border rounded-lg">' +
              '<div class="flex w-full sm:w-56 flex-shrink-0 items-center rounded-lg border"><span class="pl-3 text-sm">Rp</span>' +
                '<input type="text" inputmode="numeric" autocomplete="off" name="tambahan_nominal[]" placeholder="0" class="w-full bg-transparent px-2 py-2 outline-none"></div>' +
              '<button type="button" class="hapus-tambahan bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">Hapus</button>' +
            '</div>';
        const t = row.querySelector('input[name="tambahan_teks[]"]');
        const n = row.querySelector('input[name="tambahan_nominal[]"]');
        t.value = teks || '';
        n.value = nominal ? ribuan(nominal) : '';
        n.addEventListener('input', function () { n.value = ribuan(n.value); });
        row.querySelector('.hapus-tambahan').addEventListener('click', function () { row.remove(); nomori(); });
        box.appendChild(row);
        nomori();
        return t;
    }

    btn.addEventListener('click', function () { tambah('', '').focus(); });

    try {
        JSON.parse(box.dataset.awal || '[]').forEach(function (t) { tambah(t.teks, t.nominal); });
    } catch (e) { /* data awal rusak: abaikan */ }
})();
</script>
</body>
</html>
