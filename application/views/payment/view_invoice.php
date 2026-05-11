<body class="bg-slate-100 print:bg-white">

<style>
    @media print {
        .no-print {
            display: none !important;
        }

        body {
            background: white !important;
        }

        .invoice-container {
            box-shadow: none !important;
            margin: 0 !important;
            border-radius: 0 !important;
        }

        @page {
            size: A4;
            margin: 12mm;
        }
    }
</style>

<div class="max-w-5xl mx-auto py-8 px-4">

    <!-- Action Button -->
    <div class="flex justify-end gap-3 mb-5 no-print">
        <button onclick="window.print()"
            class="bg-slate-800 hover:bg-black text-white px-5 py-2 rounded-lg transition">
            Print Invoice
        </button>

        <a href="<?= base_url('project/lihat/' . $project->id_session) ?>"
            class="bg-slate-500 hover:bg-slate-600 text-white px-5 py-2 rounded-lg transition">
            Kembali
        </a>
    </div>

    <!-- Invoice -->
    <div class="invoice-container bg-white rounded-2xl shadow-xl p-10 relative overflow-hidden">

        <!-- Watermark -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03]">
            <img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-03.png') ?>"
                class="w-[420px]">
        </div>

        <!-- Header -->
        <div class="flex justify-between items-start border-b border-slate-200 pb-8 relative z-10">

            <div>
                <img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-02.png') ?>"
                    class="w-56 mb-3">

                <h2 class="font-semibold text-slate-800 text-sm tracking-wide">
                    MANTENBARU ORGANIZER
                </h2>

                <div class="text-sm text-slate-500 leading-6 mt-2">
                    <p>Teras Country Blok H No 38, Tonjong</p>
                    <p>Tajurhalang, Kabupaten Bogor</p>
                    <p>0812-9292-9396</p>
                    <p>www.mantenbaru.com</p>
                </div>
            </div>

            <div class="text-right">

                <h1 class="text-4xl font-bold text-slate-800 tracking-wide">
                    INVOICE
                </h1>

                <div class="mt-6 bg-slate-50 rounded-xl p-5 min-w-[260px] border border-slate-200">
                    <div class="flex justify-between text-sm py-1">
                        <span class="text-slate-500">Invoice No</span>
                        <span class="font-semibold">
                            <?= $payment->transactions_id; ?>
                        </span>
                    </div>

                    <div class="flex justify-between text-sm py-1">
                        <span class="text-slate-500">Tanggal</span>
                        <span>
                            <?= date('d M Y', strtotime($payment->date)); ?>
                        </span>
                    </div>

                    <div class="flex justify-between text-sm py-1">
                        <span class="text-slate-500">Jatuh Tempo</span>
                        <span>
                            <?= date('d M Y', strtotime($payment->due_date)); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Info -->
        <div class="grid grid-cols-2 gap-8 mt-8 relative z-10">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">
                    Ditagihkan Kepada
                </p>

                <h3 class="text-xl font-semibold text-slate-800">
                    <?= $project->client_name; ?>
                </h3>

                <div class="text-sm text-slate-500 mt-2 leading-7">
                    <p>Lokasi Acara:</p>
                    <p class="text-slate-700 font-medium">
                        <?= $project->location; ?>
                    </p>

                    <p class="mt-2">Tanggal Acara:</p>
                    <p class="text-slate-700 font-medium">
                        <?= date('d F Y', strtotime($project->event_date)) ?>
                    </p>
                </div>
            </div>

            <div class="flex justify-end">
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-6 py-5 text-right">
                    <p class="text-sm text-slate-500 mb-2">
                        Total Tagihan
                    </p>

                    <h2 class="text-3xl font-bold text-emerald-600">
                        Rp <?= number_format($payment->total_bill, 0, ',', '.') ?>
                    </h2>
                </div>
            </div>

        </div>

        <!-- Table -->
        <div class="mt-10 relative z-10 overflow-hidden rounded-xl border border-slate-200">

            <table class="w-full">

                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="text-left px-6 py-4 text-sm font-medium">
                            Rincian
                        </th>

                        <th class="text-center px-6 py-4 text-sm font-medium w-28">
                            Qty
                        </th>

                        <th class="text-right px-6 py-4 text-sm font-medium w-52">
                            Harga
                        </th>

                        <th class="text-right px-6 py-4 text-sm font-medium w-52">
                            Jumlah
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white">

                    <tr class="border-b border-slate-200 align-top">

                        <td class="px-6 py-5 text-sm text-slate-700 leading-7">

                            <?php
                            $details = str_replace(['[', ']', '"'], '', $payment->detail);
                            $detailsArray = explode(',', $details);

                            foreach ($detailsArray as $item):
                            ?>

                            <div class="mb-2 flex items-start">
                                <span class="mr-2 text-emerald-600">•</span>
                                <span><?= trim($item); ?></span>
                            </div>

                            <?php endforeach; ?>

                        </td>

                        <td class="text-center px-6 py-5 text-sm text-slate-700">
                            1 Sesi
                        </td>

                        <td class="text-right px-6 py-5 text-sm text-slate-700">
                            Rp <?= number_format($payment->total_bill, 0, ',', '.') ?>
                        </td>

                        <td class="text-right px-6 py-5 font-semibold text-slate-800">
                            Rp <?= number_format($payment->total_bill, 0, ',', '.') ?>
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>

        <!-- Total -->
        <div class="flex justify-end mt-8 relative z-10">

            <div class="w-full max-w-md bg-slate-50 rounded-2xl border border-slate-200 p-6">

                <div class="flex justify-between items-center text-lg">
                    <span class="font-medium text-slate-600">
                        Grand Total
                    </span>

                    <span class="font-bold text-2xl text-slate-800">
                        Rp <?= number_format($payment->total_bill, 0, ',', '.') ?>
                    </span>
                </div>

            </div>

        </div>

        <!-- Terms & Payment -->
        <div class="grid grid-cols-2 gap-8 mt-10 relative z-10">

            <div>
                <h4 class="font-semibold text-slate-800 mb-3">
                    Ketentuan Pembayaran
                </h4>

                <div class="text-sm text-slate-500 leading-7">
                    <p>
                        Pembayaran DP sebesar
                        <strong>
                            Rp <?= number_format($payment->DP, 0, ',', '.') ?>
                        </strong>
                        diperlukan untuk booking tanggal acara.
                    </p>

                    <p>
                        Pelunasan dilakukan maksimal
                        <strong>H-7</strong>
                        sebelum acara berlangsung.
                    </p>
                </div>
            </div>

            <div>
                <h4 class="font-semibold text-slate-800 mb-3">
                    Informasi Pembayaran
                </h4>

                <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-sm leading-7">
                    <p class="font-semibold text-slate-700">
                        PT Nadi Sukses Berkarya
                    </p>

                    <p>Bank Central Asia (BCA)</p>
                    <p>No. Rek: <strong>167-2468421</strong></p>
                </div>
            </div>

        </div>

    </div>

</div>

</body>