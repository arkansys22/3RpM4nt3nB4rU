<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
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
        <div class="grid md:grid-cols-2 grid-cols-1 gap-8 mt-8 relative z-10">

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


        </div>

        <!-- Table -->
        <div class="mt-10 relative z-10 overflow-hidden rounded-xl border border-slate-200">

            <table class="w-full">

                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="text-left px-6 py-4 text-sm font-medium">
                            Rincian
                        </th>

                        <th class="text-right px-6 py-4 text-sm font-medium w-52">
                            Harga
                        </th>

                        <th class="text-center px-6 py-4 text-sm font-medium w-28">
                            Qty
                        </th>

                        <th class="text-right px-6 py-4 text-sm font-medium w-52">
                            Total
                        </th>
                    </tr>
                </thead>
                    <tbody class="bg-white">
                            <?php 
                            $subTotal = 0;
                            $diskonTotal = 0;

                            $penawaran = $this->db
                                ->get_where(
                                    'penawaran_klien',
                                    ['penawaran_klien_potensial_idsession' => $payment->potensial_clients_id_session]
                                )
                                ->result();
                            ?>

                            <?php foreach ($penawaran as $p): ?>

                            <?php

                            $namaproduk = $this->Crud_m
                                ->view_where(
                                    'data_pricelist',
                                    [
                                        'data_pricelist_idsession' => $p->penawaran_klien_idpricelist
                                    ]
                                )
                                ->row();




                           // bersihkan format angka
                            $harga = (float) str_replace('.', '', $p->penawaran_klien_harga);
                            $hargaPromo = (float) str_replace('.', '', $p->penawaran_klien_hargapromo);
                            $qty = (float) $p->penawaran_klien_qty;
                            $diskon = (float) str_replace('.', '', $p->penawaran_klien_diskon);

                            // gunakan harga promo jika ada
                            $hargaFinal = ($hargaPromo > 0) ? $hargaPromo : $harga;

                            // total sebelum diskon
                            $totalHarga = $hargaFinal * $qty;

                            // total setelah diskon
                            $totalAkhir = $totalHarga - $diskon;

                            // hitung subtotal
                            $subTotal += $totalHarga;

                            // simpan total diskon
                            $diskonTotal += $diskon;

                            $grandTotal = $subTotal - $diskonTotal;

                            ?>

                            <tr class="border-b border-slate-200 align-top hover:bg-slate-50">

                                <td class="px-6 py-5 text-sm text-slate-700 leading-7">

                                    <div class="font-medium text-slate-800">
                                        <?= $namaproduk->data_pricelist_judul ?? 'Produk Tidak Ditemukan' ?>
                                    </div>

                                    <?php if (!empty($p->penawaran_klien_deskripsi)): ?>
                                        <small class="text-slate-500">
                                            <?= $p->penawaran_klien_deskripsi ?>
                                        </small>
                                    <?php endif; ?>

                                </td>

                                <td class="text-right px-6 py-5 text-sm text-slate-700">

                                    <?php if ($harga > 0 && $hargaPromo > 0): ?>
                                        <div class="text-slate-400 text-xs">
                                            <s>Rp <?= number_format($harga,0,',','.') ?></s>
                                        </div>
                                    <?php endif; ?>

                                    <div class="font-medium">
                                        Rp <?= number_format($hargaFinal,0,',','.') ?>
                                    </div>

                                </td>

                                <td class="text-center px-6 py-5 text-sm text-slate-700">
                                    <?= $qty ?>
                                </td>

                                <td class="text-right px-6 py-5 font-semibold text-slate-800">

                                    <div>
                                        Rp <?= number_format($totalHarga,0,',','.') ?>
                                    </div>

                                </td>

                            </tr>

                            <?php endforeach; ?>
                    </tbody>
            </table>

        </div>

        <!-- Total -->
        <div class="w-full max-w-md bg-slate-50 rounded-2xl border border-slate-200 p-6">

            <?php

                $potensial = $this->Crud_m
                    ->view_where(
                        'potensial_clients',
                        [
                            'id_session' => $payment->potensial_clients_id_session
                        ]
                    )
                    ->row();

                // default
                $promoDiskon = 0;
                $labelPromo = '';

                // kondisi promo
                if ($potensial->promo == 'custom') {

                    // ambil promo custom
                    $promoDiskon = (float) str_replace('.', '', $potensial->promo_value);
                    $labelPromo = 'Custom';

                } elseif ($potensial->promo == 'default') {

                    // ambil total diskon item
                    $promoDiskon = $diskonTotal;
                    $labelPromo = 'Default';

                } elseif ($potensial->promo == 'tidak') {

                    // tidak ada promo
                    $promoDiskon = 0;
                }

                // hitung grand total
                $grandTotal = $subTotal - $promoDiskon;

            ?>

            <div class="space-y-3">

                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">
                        Sub Total 
                    </span>

                    <span class="font-medium">
                        Rp <?= number_format($subTotal,0,',','.') ?>
                    </span>
                </div>

                <?php if ($promoDiskon > 0): ?>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">
                        Promo Diskon
                    </span>

                    <span class="text-red-500 font-medium">
                        - Rp <?= number_format($promoDiskon,0,',','.') ?>
                    </span>
                </div>
                <?php endif; ?>

                <div class="border-t pt-4 flex justify-between items-center">
                    <span class="font-semibold text-slate-700">
                        Grand Total
                    </span>

                    <span class="font-bold text-2xl text-slate-800">
                        Rp <?= number_format($grandTotal,0,',','.') ?>
                    </span>
                </div>

            </div>

        </div>

        <!-- Terms & Payment -->
        <div class="grid md:grid-cols-2 grid-cols-1 gap-8 mt-10 relative z-10">

            <div>
                <h4 class="font-semibold text-slate-800 mb-3">
                    Ketentuan Pembayaran
                </h4>

                <?php
                $details = json_decode($payment->detail, true);
                ?>

                <?php if (!empty($details)): ?>
                    <ul class="list-disc ml-5 text-sm text-slate-500 leading-7 mb-4">
                        <?php foreach ($details as $item): ?>
                            <li><?= $item ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div class="text-sm text-slate-500 leading-7">
                    <p>
                        Pembayaran DP sebesar
                        <strong>
                            Rp <?= number_format($payment->DP, 0, ',', '.') ?>
                        </strong>
                        diperlukan untuk booking tanggal acara.
                    </p>

                    <p>
                        Pelunasan maksimal
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
</html>
