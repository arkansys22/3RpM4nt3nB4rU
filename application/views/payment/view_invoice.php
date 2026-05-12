<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $fileName ?></title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .background-image {
            position: absolute;
            inset: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0.03;
            pointer-events: none;
            z-index: 0;
        }

        @page {
            size: A4;
            margin: 12mm;
        }

        @media print {

            /* sembunyikan tombol */
            .no-print {
                display: none !important;
            }

            html,
            body {
                width: 100%;
                height: auto !important;
                background: #fff !important;
                margin: 0;
                padding: 0;
            }

            body {
                display: block !important;
            }

            /* container invoice */
            .invoice-container {
                width: 100% !important;
                max-width: 100% !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: visible !important;
                page-break-inside: auto;
            }

            /* wrapper full lebar */
            .max-w-5xl {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* table tidak kepotong */
            table {
                width: 100%;
                border-collapse: collapse;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            tr,
            td,
            th {
                page-break-inside: avoid;
            }

            /* section jangan terpotong aneh */
            .avoid-break {
                page-break-inside: avoid;
            }

            /* paksa pindah halaman jika perlu */
            .page-break {
                page-break-before: always;
            }
        }
    </style>
    <?php
        $clientName = preg_replace(
            '/[^A-Za-z0-9\-]/',
            '_',
            $project->client_name
        );

        $invoiceNo = preg_replace(
            '/[^A-Za-z0-9\-]/',
            '_',
            $payment->transactions_id
        );

        $fileName = 'Invoice_' . $clientName . '_' . $invoiceNo;
        ?>
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
        <button onclick="printInvoice()"
            class="bg-slate-800 hover:bg-black text-white px-5 py-2 rounded-lg transition">
            Print Invoice
        </button>

        <a href="<?= base_url('project/lihat/' . $project->id_session) ?>"
            class="bg-slate-500 hover:bg-slate-600 text-white px-5 py-2 rounded-lg transition">
            Kembali
        </a>
    </div>

    <!-- Invoice -->
    <div class="invoice-container bg-white rounded-2xl shadow-xl p-10 relative">

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
                        <?= hari($project->event_date) ?>, <?= date('d F Y', strtotime($project->event_date)) ?>
                    </p>
                </div>
            </div>


        </div>

        <!-- Table -->
        <div class="mt-10 relative z-10 overflow-hidden rounded-xl border border-slate-200">

            <table class="w-full table-fixed">

                <!-- Atur lebar kolom -->
                <colgroup>
                    <col style="width: 52%;">
                    <col style="width: 18%;">
                    <col style="width: 10%;">
                    <col style="width: 20%;">
                </colgroup>

                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="text-left px-5 py-4 text-sm font-medium">
                            Rincian
                        </th>

                        <th class="text-right px-4 py-4 text-sm font-medium whitespace-nowrap">
                            Harga
                        </th>

                        <th class="text-center px-3 py-4 text-sm font-medium whitespace-nowrap">
                            Qty
                        </th>

                        <th class="text-right px-5 py-4 text-sm font-medium whitespace-nowrap">
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

                                <td class="px-5 py-4 text-sm text-slate-700 break-words">

                                    <div class="font-medium text-slate-800 leading-6">
                                        <?= $namaproduk->data_pricelist_judul ?? 'Produk Tidak Ditemukan' ?>
                                    </div>

                                    <?php if (!empty($p->penawaran_klien_deskripsi)): ?>
                                        <div class="text-slate-500 text-xs leading-5 mt-1">
                                            <?= $p->penawaran_klien_deskripsi ?>
                                        </div>
                                    <?php endif; ?>

                                </td>

                                <td class="text-right px-4 py-4 text-sm text-slate-700 whitespace-nowrap">

                                    <?php if ($harga > 0 && $hargaPromo > 0): ?>
                                        <div class="text-slate-400 text-xs">
                                            <s>Rp <?= number_format($harga,0,',','.') ?></s>
                                        </div>
                                    <?php endif; ?>

                                    <div class="font-medium">
                                        Rp <?= number_format($hargaFinal,0,',','.') ?>
                                    </div>

                                </td>

                                <td class="text-center px-3 py-4 text-sm text-slate-700 whitespace-nowrap">
                                    <?= $qty ?>
                                </td>

                                <td class="text-right px-5 py-4 text-sm font-semibold text-slate-800 whitespace-nowrap">

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
        <div class="mt-6 flex justify-end relative z-10 avoid-break">
            <div class="w-full md:w-[360px] ml-auto bg-slate-50 rounded-2xl border border-slate-200 p-6">

            <?php

                $potensial = $this->Crud_m
                    ->view_where(
                        'potensial_clients',
                        [
                            'id_session' => $payment->potensial_clients_id_session
                        ]
                    )
                    ->row();

                // fallback jika data tidak ditemukan
                if (!$potensial) {
                    $potensial = (object)[
                        'promo' => 'tidak',
                        'promo_value' => 0
                    ];
                }

                // default
                $promoDiskon = 0;
                $labelPromo = '';

                // kondisi promo
                if (($potensial->promo ?? 'tidak') == 'custom') {

                    // ambil promo custom
                    $promoDiskon = (float) str_replace('.', '', $potensial->promo_value ?? 0);
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
                        Bonus & Cashback
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
        </div>

        <!-- Terms & Payment -->
        <div class="grid md:grid-cols-2 grid-cols-1 gap-8 mt-10 relative z-10 avoid-break">

            <div>
                <h4 class="font-semibold text-slate-800 mb-3">
                    Ketentuan Pembayaran
                </h4>    

                <div class="text-sm text-slate-500 leading-7">  

            <?php
                $today = new DateTime();
                $event = new DateTime($project->event_date);
                $diff  = $today->diff($event);
                $days_to_event = $diff->days;
            ?>

            <?php
                $aa = new DateTime($project->event_date);
                $b  = clone $aa;
                $b->modify('-60 days');
                $c  = clone $aa;
                $c->modify('-30 days');
                $d  = clone $aa;
                $d->modify('-14 days');
            ?>



                <?php

                    // gunakan grand total yang sudah dihitung sebelumnya
                    $totalPembayaran = $grandTotal;

                    // pembayaran pertama
                    $p1 = 1000000;

                    // sisa pembayaran
                    $sisa = max(0, $totalPembayaran - $p1);

                    // reset variable
                    $p2 = 0;
                    $p3 = 0;
                    $p4 = 0;
                    $p5 = 0;

                    if ($days_to_event < 30) {

                        $p2 = $sisa;

                    } elseif ($days_to_event < 60) {

                        $p2 = $sisa * 0.15;
                        $p3 = $sisa * 0.45;
                        $p4 = $sisa * 0.40;

                    } else {

                        $p2 = $sisa * 0.15;
                        $p3 = $sisa * 0.35;
                        $p4 = $sisa * 0.35;
                        $p5 = $sisa * 0.15;
                    }
                ?>

                <?php if ($payment->metodep == 'Default') { ?>

                    <ul class="list-disc pl-5">
                            <li>Pembayaran pertama <b>kunci harga</b> Rp <?= number_format($p1,0,',','.') ?></li>

                            <?php if($days_to_event < 30){ ?>

                            <li>Pembayaran kedua <b>pelunasan</b> Rp <?= number_format($p2,0,',','.') ?></li>

                            <?php }elseif($days_to_event < 60){ ?>

                            <li>Pembayaran kedua <b>kunci tanggal</b> sebesar 15% dari total biaya pada H+14 setelah pembayaran pertama Rp <?= number_format($p2,0,',','.') ?></li>

                            <li>Pembayaran ketiga sebesar 45% dari total biaya pada <b>H-30 acara (<?= tgl_indo($c->format('Y-m-d')) ?>)</b>
                            Rp <?= number_format($p3,0,',','.') ?></li>

                            <li>Pembayaran keempat sebesar 40% dari total biaya pada <b>H-14 acara (<?= tgl_indo($d->format('Y-m-d')) ?>)</b>
                            Rp <?= number_format($p4,0,',','.') ?></li>

                            <?php }else{ ?>

                            <li>Pembayaran kedua <b>kunci tanggal</b> sebesar 15% dari total biaya pada H+14 setelah pembayaran pertama
                            Rp <?= number_format($p2,0,',','.') ?></li>

                            <li>Pembayaran ketiga sebesar 35% dari total biaya pada <b>H-60 acara (<?= tgl_indo($b->format('Y-m-d')) ?>)</b>
                            Rp <?= number_format($p3,0,',','.') ?></li>

                            <li>Pembayaran keempat sebesar 35% dari total biaya pada <b>H-30 acara (<?= tgl_indo($c->format('Y-m-d')) ?>)</b>
                            Rp <?= number_format($p4,0,',','.') ?></li>

                            <li>Pembayaran kelima sebesar 15% dari total biaya pada <b>H-14 acara (<?= tgl_indo($d->format('Y-m-d')) ?>)</b>
                            Rp <?= number_format($p5,0,',','.') ?></li>

                            <?php } ?>
                    </ul>
                <?php }else if ($payment->metodep == 'Custom') { ?>
                    <?php
                        $paymentDetails = json_decode($payment->detail, true);
                    ?>

                     <?php if (!empty($paymentDetails) && is_array($paymentDetails)): ?>

                            <ul class="space-y-3">

                                <?php foreach ($paymentDetails as $index => $item): ?>

                                    <li class="flex items-start gap-3 bg-white border border-slate-200 rounded-xl p-4">

                                        <div class="w-8 h-8 rounded-full bg-slate-800 text-white text-sm flex items-center justify-center shrink-0">
                                            <?= $index + 1 ?>
                                        </div>

                                        <div class="text-sm text-slate-700 leading-6">
                                            <?= htmlspecialchars($item) ?>
                                        </div>

                                    </li>

                                <?php endforeach; ?>

                            </ul>

                        <?php else: ?>

                            <div class="text-sm text-slate-500 italic">
                                Detail pembayaran belum tersedia.
                            </div>

                        <?php endif; ?>

                    </div>

                 <?php }else{ ?>    
                <?php } ?>

                
                    
                    <p>
                        <?php if(($potensial->promo ?? 'tidak') === 'tidak'){ ?>
                            <div><i>Harga dapat berubah sewaktu-waktu jika belum melakukan pembayaran pertama untuk kunci harga.</i>
                            </div><br>                                
                        <?php }else{ ?>     
                            <div><i>Harga dapat berubah sewaktu-waktu jika belum melakukan pembayaran pertama untuk kunci harga. Bonus dan Cashback berlaku hanya sampai H+5 setelah invoice ini diberikan. Dan besaran bonus dan cashback setiap harinya berkurang Rp 200.000. Jadi segera lakukan pembayaran pertama dan amankan bonus dan cashbacknya.</i></div><br>
                        <?php }?>
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

<script>
function printInvoice() {

    // nama file dari PHP
    const fileName =
        "<?= $fileName ?>";

    // ubah title halaman
    const oldTitle =
        document.title;

    document.title =
        fileName;

    // print
    window.print();

    // balikan title setelah print
    setTimeout(() => {
        document.title =
            oldTitle;
    }, 1000);
}
</script>

</body>
</html>
