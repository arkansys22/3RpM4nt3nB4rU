<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-md">
    <div class="flex justify-between mb-8">
        <!-- Company Information -->
        <div class="w-1/2">
            <h2 class="text-2xl font-bold">MANTENBARU ORGANIZER</h2>
            <p>Teras Country Blok H No 38, Tonjong, Tajurhalang, Kab. Bogor</p>
            <p>Telp / WA: 0812-9292-9396</p>
            <p>Web: www.mantenbaru.com</p>
        </div>

        <!-- Invoice Info -->
        <div class="w-1/2 text-right">
        <p><strong>TAGIHAN</strong>IMB<?= date('ymd', strtotime($payment->{"date_" . $invoice_number})) ?></p>
        <p><strong>TANGGAL</strong><?= date('d-M-y') ?></p>
        <p><strong>JATUH TEMPO</strong><?= date('d-M-y', strtotime($payment->{"due_date_" . $invoice_number})) ?></p>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="mb-6">
        <h3 class="font-bold">PEMBAYARAN KEPADA</h3>
        <p><?= $project->client_name; ?></p> <!-- Menampilkan nama_client -->
        <p>Untuk acara pernikahan yang rencana berlokasi di <?= $project->location; ?></p>
        <p>Waktu acara</p>
        <p><?= date('d/m/Y', strtotime($project->event_date)) ?></p>
    </div>

    <!-- Payment Details -->
    <div class="mb-6">
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border p-2">Description</th>
                    <th class="border p-2">Unit Price</th>
                    <th class="border p-2">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Ambil details sesuai dengan invoice_number
                $details = json_decode($payment->{"details_" . $invoice_number}, true);
                $unit_price = $payment->{"amount_" . $invoice_number}; // Ambil unit price
                foreach ($details as $detail): 
                ?>
                    <tr>
                        <td class="border p-2"><?= htmlspecialchars($detail) ?></td>
                        <td class="border p-2"><?= "Rp " . number_format($unit_price, 0, ',', '.') ?></td> <!-- Menampilkan unit price -->
                        <td class="border p-2"><?= "Rp " . number_format($unit_price, 0, ',', '.') ?></td> <!-- Total berdasarkan unit price -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Payment Terms -->
    <div class="mb-6">
        <p><strong>Payment Terms:</strong> DP Rp.1,500,000 as booking, full payment due H-7 before the event.</p>
        <p><strong>Bank Details:</strong> No. Rek 167-2468421, Bank Central Asia, a/n Nadi Sukses Berkarya PT</p>
    </div>

    <!-- Grand Total -->
    <div class="text-right">
        <h3 class="text-xl font-bold">Grand Total: Rp. <?= number_format($payment->{"amount_" . $invoice_number}, 0, ',', '.') ?></h3>
    </div>

</div>

</body>
</html>
