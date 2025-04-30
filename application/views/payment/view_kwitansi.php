<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Kwitansi Pembayaran</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important; /* Menyembunyikan elemen dengan class 'no-print' */
            }
            body {
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                width: 100%;
            }
            .container {
                width: 100%;
                max-width: 800px;
                margin: auto;
                background-color: #fae2d5 !important; /* Pastikan warna background tetap terlihat */
            }
            /* Pastikan background ikut tercetak */
            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Tambahkan padding untuk memastikan kompatibilitas dengan perangkat mobile */
        body {
            padding: 1rem;
        }
    </style>
</head>
<body class="p-6">

    <div class="container max-w-4xl mx-auto bg-[#fae2d5] p-6">
        <div class="flex justify-between items-start">
            <img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-02.png') ?>" alt="Logo" style="width: 220px; margin-left: -12px;">
            <div class="text-right">
                <table class="table-auto mx-auto">
                    <tr>
                        <td class="border-b border-black text-xs text-left" style="width: 100px;"><strong>KWITANSI</strong></td>
                    </tr>
                    <tr>
                        <td class="text-xs text-left"><strong><em>RECIEPT</em></strong></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row justify-between">
            <div class="flex flex-col items-baseline mb-2 sm:mb-0">
                <div class="flex items-baseline">
                    <p class="text-xs p-0.5 mr-6">Teras Country Blok H No 38, Tonjong,</p>
                </div>
                <div class="flex items-baseline">
                    <p class="text-xs p-0.5">Tajurhalang, Kab. Bogor</p>
                </div>
            </div>
            
            <div class="flex items-baseline">
                <table class="table-auto">
                    <tr>
                        <td class="border-b border-black text-xs text-left" style="width: 80px;">Nomor</td>
                        <td class="text-xs text-center" rowspan="2">&nbsp;&nbsp;:&nbsp;<strong><?= $payment->transactions_id; ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-xs text-left"><em>Number</em></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="flex items-baseline">
            <p class="text-xs p-0.5 mr-6">Telp/WA</p>
            <p class="text-xs p-0.5">: 0812-9292-9396</p>
        </div>
        <div class="flex items-baseline">
            <p class="text-xs p-0.5 mr-11">Web</p>
            <p class="text-xs p-0.5">: www.mantenbaru.com</p>
        </div>

        <div class="mt-6 flex justify-start">
            <table class="table-auto">
            <tr>
                <td class="border-b border-black text-xs text-left" style="width: 120px;">Telah terima dari</td>
                <td class="text-xs text-center" rowspan="2">&nbsp;&nbsp;:&nbsp;<?= $project->client_name; ?></td>
            </tr>
            <tr>
                <td class="text-xs text-left"><em>Recieved from</em></td>
            </tr>
            </table>
        </div>

        <div class="mt-2 flex justify-start">
            <table class="table-auto">
            <?php 
                    $unit_price = $payment->total_paid; // Menggunakan total paid langsung dari tabel payment
                    ?>
            <tr>
                <td class="border-b border-black text-xs text-left" style="width: 120px;">Sejumlah uang</td>
                <td class="text-xs text-center" rowspan="2">&nbsp;&nbsp;:&nbsp;<?= ucfirst(terbilang($unit_price)) ?>
                </td>
            </tr>
            <tr>
                <td class="text-xs text-left"><em>Amount recieved</em></td>
            </tr>
            </table>
        </div>

        <div class="mt-2">
            <table class="table-auto">
                <tr>
                    <td class="border-b border-black text-xs text-left" style="width: 120px;">Untuk pembayaran</td>
                    <td class="text-xs text-justify" rowspan="2">&nbsp;&nbsp;:&nbsp;<?= str_replace('"', '', $payment->detail); ?> <?= $project->client_name; ?>
                    <div class="flex justify-between mt-1">
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;Waktu acara <?= date('d M y', strtotime($project->event_date)) ?></span>
                        <span class="ml-2">Lokasi acara <?= $project->location; ?></span>
                    </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-xs text-left"><em>In payment of</em></td>
                </tr>
            </table>

            <div class="flex justify-end mt-2">
                <?php
                $bulan = [
                    1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                    7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                ];

                $date = strtotime($payment->date);
                ?>
                <p class="text-xs p-0.5" style="padding-right: 40px;">Bogor, <?= date('d', $date) . ' ' . $bulan[date('n', $date)] . ' ' . date('Y', $date) ?></p>
            </div>

            <div class="mt-2 flex justify-start" style="padding-left: 55px;">
                <table class="table-auto bg-[#f6c6ac]">
                    <tr>
                        <td class="text-lg text-left" style="width: 350px; height: 35px; padding-left: 51px;">
                            <strong>Rp <?= number_format($unit_price, 0, ',', '.') ?></strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-6 flex justify-between no-print">
            <div class="flex">
                <button onclick="window.print()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                    Print
                </button>
            </div>
            <a href="<?= base_url('project/lihat/' . $project->id_session) ?>" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 inline-block text-center w-auto">Kembali</a>
        </div>
    </div>

</body>
</html>
