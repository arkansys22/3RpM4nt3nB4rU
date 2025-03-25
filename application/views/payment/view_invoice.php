<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .background-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            opacity: 0.1;
        }
        @media print {
        .no-print {
            display: none !important; /* Menyembunyikan elemen dengan class 'no-print' */
        }
        @page {
            margin: 0; /* Menghapus margin default browser */
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .print-wrapper {
            width: 80%;
            margin: auto;
        }
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="print-wrapper">
    <div class="max-w-100% mx-auto p-8 relative"> <!-- Added relative positioning here -->
        <div class="flex justify-between mb-8">
            <!-- Company Information -->
            <div class="w-1/2">
                <img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-02.png') ?>" alt="Logo" style="width: 220px; margin-left: -12px;"> <!-- Adjust the width to match the text -->
                <p class="text-sm"><strong>MANTENBARU ORGANIZER</strong></p>
                <p class="text-xs">Teras Country Blok H No 38, Tonjong,</p>
                <p class="text-xs">Tajurhalang, Kab. Bogor</p>
                <p class="text-xs">Telp / WA : 0812-9292-9396</p>
                <p class="text-xs">Web : www.mantenbaru.com</p>
            </div>

            <!-- Invoice Info -->
            <div class="w-60 text-center"><br>
                <p class="text-sm mb-10"><strong>TAGIHAN</strong></p>
                <table class="table-auto w-full border border-black mx-auto">
                    <tr>
                        <td class="border border-black text-xs text-center"><strong>TAGIHAN</strong></td>
                        <td class="border border-black text-xs text-center"><strong>TANGGAL</strong></td>
                        <td class="border border-black text-xs text-center"><strong>JATUH TEMPO</strong></td>
                    </tr>
                    <tr>
                        <td class="border border-black text-xs text-center"><?= $payment->transactions_id; ?></td>
                        <td class="border border-black text-xs text-center"><?= date('d-M-y') ?></td>
                        <td class="border border-black text-xs text-center whitespace-nowrap"><?= date('d-M-y', strtotime($payment->{$due_date})) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="mb-6">
            <p class="text-sm"><strong>PEMBAYARAN KEPADA</strong></p>
            <p class="text-xs"><?= $project->client_name; ?></p> <!-- Menampilkan nama_client -->
            <p class="text-xs">Untuk acara pernikahan yang rencana berlokasi di</p>
            <p class="text-xs"><?= $project->location; ?></p>
            <p class="text-xs">Waktu acara :</p>
            <p class="text-xs"><?= date('d/m/Y', strtotime($project->event_date)) ?></p>
        </div>

        <!-- Payment Details -->
        <div class="mb-6">
            <table class="table-auto w-full border border-black">
                <thead>
                    <tr>
                        <th class="border border-black p-2 w-3/5 text-sm">RINCIAN</th>
                        <th class="border border-black p-2 w-1/5 text-sm">QTY</th>
                        <th class="border border-black p-2 text-sm" style="width: auto;">HARGA SATUAN</th>
                        <th class="border border-black p-2 text-sm" style="width: auto;">JUMLAH</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Ambil details sesuai dengan invoice_number
                    $details = json_decode($payment->{"details_" . $invoice_number}, true);
                    $unit_price = $payment->{"amount_" . $invoice_number}; // Ambil unit price
                    $details_count = count($details);
                    ?>
                    <tr>
                        <td class="border border-black p-2 text-justify text-xs" rowspan="<?= $details_count ?>">
                            <?php foreach ($details as $index => $detail): ?>
                                <?= $index === 0 ? '<br>' : '' ?> <!-- Add <br> before the first detail -->
                                <?= htmlspecialchars($detail) ?><br>
                                <?= $index === $details_count - 1 ? '<br>' : '' ?> <!-- Add <br> after the last detail -->
                            <?php endforeach; ?>
                        </td>
                        <td class="border border-black p-2 text-center text-xs" rowspan="<?= $details_count ?>">1 Sesi</td> <!-- Menampilkan unit price -->
                        <td class="border border-black p-2 text-center text-xs" rowspan="<?= $details_count ?>">
                            <div class="flex justify-center items-center h-full">
                                <span class="mr-1">Rp</span>
                                <span><?= number_format($unit_price, 0, ',', '.') ?></span>
                            </div>
                        </td> <!-- Menampilkan unit price -->
                        <td class="border border-black p-2 text-center text-xs" rowspan="<?= $details_count ?>">
                            <div class="flex justify-center items-center h-full">
                                <span class="mr-1">Rp</span>
                                <span><?= number_format($unit_price, 0, ',', '.') ?></span>
                            </div>
                        </td> <!-- Total berdasarkan unit price -->
                    </tr>
                    <?php for ($i = 1; $i < $details_count; $i++): ?>
                        <tr></tr>
                    <?php endfor; ?>
                    <tr>
                        <th colspan="3" class="border border-black text-right text-sm p-1">
                            <strong>GRAND TOTAL</strong>
                        </th>
                        <td class="border border-black p-2 text-center text-sm" style="width: auto;">
                            <div class="flex justify-center items-center h-full">
                                <span class="mr-1"><strong>Rp</strong></span>
                                <strong><?= number_format($unit_price, 0, ',', '.') ?></strong>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment Terms -->
        <div class="mb-6">
            <p class="text-xs">Ketentuan Pembayaran :</p>
            <p class="text-xs">Syarat Pembayaran adalah DP <?= number_format($payment->{"dp_" . $invoice_number}, 0, ',', '.') ?></p>
            <p class="text-xs">sebagai booking tanggal dan pelunasan H-7</p>
            <p class="text-xs">sebelum acara.</p>
        </div>

        <!-- Grand Total -->
        <div class="mb-6">
            <p class="text-xs">Pembayaran :</p>
            <p class="text-xs">No.rek 167-2468421</p>
            <p class="text-xs">Bank Central Asia</p>
            <p class="text-xs">a/n Nadi Sukses Berkarya PT</p>
        </div>

        <img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-03.png') ?>" alt="Background" class="background-image"> <!-- Add background image -->
        <div class="mt-6 flex justify-between no-print">
        <div class="flex">
            <button onclick="window.print()" 
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                Print
            </button>
        </div>
        <a href="javascript:history.back()" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 inline-block text-center w-auto">Kembali</a>
            </div>
    </div>
</div>

</body>
</html>
