<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
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
              <h1 class="text-2xl font-bold mb-4">Edit Invoice <?= $payment->transactions_id ?></h1>
              <form action="<?= site_url('payment/update/' . $payment->id_session . '/' . ($payment->payment_id_session ?? '')) ?>" method="post" class="bg-white p-6 shadow-md rounded">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

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

                <label class="block mb-2">Nama Invoice</label>
                <input type="text" name="nama_transaksi" value="<?= $payment->nama_transaksi ?>" class="w-full px-4 py-2 border rounded mb-4">

                <label for="date" class="block mb-2 font-medium">Tanggal Pembuatan</label>
                <input type="date" name="date" value="<?= $payment->date ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label for="due_date" class="block mb-2 font-medium">Jatuh Tempo</label>
                <input type="date" name="due_date" value="<?= $payment->due_date ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label for="total_bill" class="block mb-2 font-medium">Total Tagihan:</label>
                <input type="text" id="total_bill" name="total_bill" oninput="formatNumber(this)" value="<?= number_format($payment->total_bill, 0, ',', '.') ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Kategori Account Receivable</label>
                <select 
                    name="kategori" 
                    class="w-full px-4 py-2 border rounded mb-4" 
                    required
                >
                    <option value="">- Pilih Kategori -</option>

                    <?php foreach ($kategori as $p): ?>

                        <option 
                            value="<?= $p['nomer_kategori'] ?>"
                            <?= ($payment->kategori == $p['nomer_kategori']) ? 'selected' : '' ?>
                        >
                            <?= $p['nomer_kategori'] ?> | <?= $p['nama_kategori'] ?>
                        </option>

                    <?php endforeach; ?>

                </select>


                <label class="block mb-2">Kategori Sales</label>
                <select 
                    name="kategori2" 
                    class="w-full px-4 py-2 border rounded mb-4" 
                    required
                >
                    <option value="">- Pilih Kategori -</option>

                    <?php foreach ($kategori2 as $p): ?>

                        <option 
                            value="<?= $p['nomer_kategori'] ?>"
                            <?= ($payment->kategori2 == $p['nomer_kategori']) ? 'selected' : '' ?>
                        >
                            <?= $p['nomer_kategori'] ?> | <?= $p['nama_kategori'] ?>
                        </option>

                    <?php endforeach; ?>

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

                            if (is_array($decoded) && isset($decoded['default'])) {
                                $details = []; // teks metode Default (lihat #default-wrapper), bukan detail Custom
                            } elseif (is_array($decoded)) {
                                $details = $decoded;
                            } else {
                                $details = [$payment->detail];
                            }
                        }

                        if (empty($details)) {
                            $details[] = '';
                        }
                        ?>

                        <?php foreach ($details as $index => $detail):
                            // data lama berupa teks biasa; data baru berupa [nominal, teks]
                            $nom = is_array($detail) ? ($detail['nominal'] ?? '') : '';
                            $txt = is_array($detail) ? ($detail['teks'] ?? '') : $detail;
                            $nom = ($nom === '' ? '' : number_format((int) $nom, 0, ',', '.'));
                        ?>
                        <div class="detail-item">
                            <label class="detail-label block mb-1 text-sm font-semibold">Pembayaran Pertama</label>
                            <div class="flex flex-col sm:flex-row gap-2 items-start">
                                <input type="text" name="detail[0][teks]" value="<?= htmlspecialchars($txt) ?>"
                                    class="detail-input detail-teks w-full sm:flex-1 px-4 py-2 border rounded-lg"
                                    placeholder="Teks custom, contoh: DP maksimal H+7">
                                <div class="flex w-full sm:w-56 flex-shrink-0 items-center rounded-lg border">
                                    <span class="pl-3 text-sm">Rp</span>
                                    <input type="text" inputmode="numeric" autocomplete="off"
                                        name="detail[0][nominal]" value="<?= htmlspecialchars($nom) ?>"
                                        class="detail-input detail-nominal w-full bg-transparent px-2 py-2 outline-none"
                                        placeholder="0">
                                </div>
                                <button type="button"
                                    class="remove-detail bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg hidden">
                                    Hapus
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>

                    </div>

                    <!-- Muncul setelah pembayaran terakhir diisi -->
                    <button
                        type="button"
                        id="add-detail-btn"
                        class="hidden mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        + Tambah Pembayaran
                    </button>

                </div>

<?php
                // Metode Default: input teks pembayaran pertama sampai terakhir. Isi awal = teks
                // hitungan otomatis (atau teks yang pernah diubah); baris yang tidak diubah tetap
                // otomatis di invoice. Jumlah baris mengikuti sisa hari ke tanggal acara.
                $barisDefault = jadwal_pembayaran_default((int) $payment->total_bill, $project->event_date);
                $overrideDefault = override_pembayaran_default($payment->detail, count($barisDefault));
                $urutanDefault = ['Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam'];
                $tambahanAwal = tambahan_pembayaran_default($payment->detail);
                ?>
                <div id="default-wrapper" class="hidden">
                    <label class="block mb-2 font-medium">Detail Pembayaran (Default)</label>
                    <div class="space-y-3 mb-4">
                        <?php foreach ($barisDefault as $i => $barisItem):
                            $teksAsli = trim(html_entity_decode(strip_tags($barisItem['teks'])));
                            $nominalAsli = (int) $barisItem['nominal'];
                            $ov = $overrideDefault[$i] ?? null;
                            if (is_string($ov)) { $ov = null; } // bentuk lama (baris penuh) tidak dipakai lagi di form
                            $teksManual = is_array($ov) ? trim((string) ($ov['teks'] ?? '')) : '';
                            $nominalManual = is_array($ov) ? preg_replace('/\D/', '', (string) ($ov['nominal'] ?? '')) : '';
                            $nominalTampil = $nominalManual !== '' ? (int) $nominalManual : $nominalAsli;
                        ?>
                        <div>
                            <label class="block mb-1 text-sm font-semibold">Pembayaran <?= $urutanDefault[$i] ?? ('ke-' . ($i + 1)) ?><?= $i === count($barisDefault) - 1 ? ' (terakhir)' : '' ?></label>
                            <div class="flex flex-col sm:flex-row gap-2 items-start">
                                <input type="text" name="detail_default[]" value="<?= htmlspecialchars($teksManual !== '' ? $teksManual : $teksAsli) ?>" class="w-full sm:flex-1 px-4 py-2 border rounded-lg">
                                <div class="flex w-full sm:w-56 flex-shrink-0 items-center rounded-lg border">
                                    <span class="pl-3 text-sm">Rp</span>
                                    <input type="text" inputmode="numeric" autocomplete="off" name="detail_default_nominal[]" value="<?= number_format($nominalTampil, 0, ',', '.') ?>"
                                           oninput="this.value = this.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                           class="w-full bg-transparent px-2 py-2 outline-none" placeholder="0">
                                </div>
                            </div>
                            <input type="hidden" name="detail_default_asli[]" value="<?= htmlspecialchars($teksAsli) ?>">
                            <input type="hidden" name="detail_default_nominal_asli[]" value="<?= $nominalAsli ?>">
                        </div>
                        <?php endforeach; ?>
                        <div id="default-tambahan" class="space-y-3 mb-3" <?= isset($tambahanAwal) ? 'data-awal="' . htmlspecialchars(json_encode($tambahanAwal), ENT_QUOTES) . '"' : '' ?>></div>
                        <button type="button" id="tambah-default-btn" class="mb-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">+ Tambah Pembayaran</button>
<p class="text-xs text-gray-500">Teks bisa diubah. Baris yang tidak diubah tetap mengikuti hitungan otomatis di invoice.</p>
                    </div>
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
        const defaultWrapper = document.getElementById('default-wrapper');
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
                        encodeURIComponent(this.value) +
                        "&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>"
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
            defaultWrapper.classList.toggle('hidden', metodeSelect.value !== 'Default');

            document
                .querySelectorAll('.detail-input')
                .forEach(input => {

                    input.required = isCustom && input.classList.contains('detail-nominal');

                    if (!isCustom) {
                        input.value = '';
                    }
                });
        }

        metodeSelect.addEventListener(
            'change',
            toggleDetailSection
        );

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
        // init
        toggleDetailSection();
    });
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
