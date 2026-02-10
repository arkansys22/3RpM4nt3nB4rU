<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penawaran Potensial Clients</title>
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
            width: 100%;
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
                <p class="text-sm mb-10"><strong>Lembar Penawaran</strong></p>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="mb-6">
            <p class="text-sm"><strong>PENAWARAN KEPADA</strong></p>
            <p class="text-xs"><?= $pc->pc_name; ?></p> <!-- Menampilkan nama_client -->
            <p class="text-xs">Untuk acara yang rencana berlokasi di</p>
            <p class="text-xs"><?= $pc->location; ?></p>
            <p class="text-xs">Waktu acara :</p>
            <p class="text-xs"><?= hari($pc->event_date) ?>, <?= tgl_indo($pc->event_date) ?></p>
        </div>

        <!-- Payment Details -->
        <div class="mb-6">
            <table class="table-auto w-full border border-black">
                <thead>
                    <tr>
                        <th class="border border-black p-2 w-2/5 text-sm">Nama Produk</th>
                        <th class="border border-black p-2 w-1/5 text-sm">Harga Asli</th>
                        <th class="border border-black p-2 w-1/5 text-sm">Harga Promo</th>
                        <th class="border border-black p-2 text-sm" style="width: auto;">Qty</th>
                        <th class="border border-black p-2 w-1/5 text-sm">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; $subTotal = 0; $diskonTotal = 0;foreach ($penawaran as $p): ?>
                    <tr>                  
                        <?php $namaproduk = $this->Crud_m->view_where('data_pricelist', array('data_pricelist_idsession'=> $p->penawaran_klien_idpricelist))->row(); ?>
                        <td class="border border-black p-2 text-left text-xs">
                                             
                                <span><?= $namaproduk->data_pricelist_judul ?>  <p><small><?= $p->penawaran_klien_deskripsi ?></small></p></span>
                           
                           
                        </td>
                        <td class="border border-black p-2 text-center text-xs">
                            <s><?= "Rp " . number_format($p->penawaran_klien_harga, 0, ',', '.'); ?></s>
                        </td>
                        <td class="border border-black p-2 text-center text-xs">
                            <?= "Rp " . number_format($p->penawaran_klien_hargapromo, 0, ',', '.') ?>
                        </td>
                        <td class="border border-black p-2 text-center text-xs">
                            <?= $p->penawaran_klien_qty ?>
                        </td>
                        <?php $total = $p->penawaran_klien_hargapromo * $p->penawaran_klien_qty ?>
                        <td class="border border-black p-2 text-center text-xs">
                            <?= "Rp " . number_format($total, 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php $diskonTotal += $p->penawaran_klien_diskon; ?>
                    <?php $subTotal += $total; ?>
                    <?php endforeach; ?>   
                    <tr>
                        <th colspan="4" class="border border-black text-right text-sm p-1">
                            Sub Total
                        </th>
                        <td class="border border-black p-2 text-center text-xs">
                            <div class="flex justify-center items-center h-full">
                                
                                   Rp <?= number_format($subTotal, 0, ',', '.') ?>
                                
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4" class="border border-black text-right text-sm p-1">
                            Promo Diskon
                        </th>
                        <td class="border border-black p-2 text-center text-xs">
                            <div class="flex justify-center items-center h-full">                            
                                   Rp <?= number_format($diskonTotal, 0, ',', '.') ?>                               
                            </div>
                        </td>
                    </tr>
                    <tr>
                    <?php $total = $subTotal - $diskonTotal?>
                        <th colspan="4" class="border border-black text-right text-sm p-1">
                            Total
                        </th>
                        <td class="border border-black p-2 text-center text-xs">
                            <div class="flex justify-center items-center h-full">                                
                           
                                   Rp <?= number_format($total, 0, ',', '.') ?>
                               
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment Terms -->
        <div class="mb-6">
            <p class="text-xs"><strong>Ketentuan Pembayaran </strong></p>
            <?php $p1= $total * 10/100?> 
            <?php $p2= $total * 45/100?> <?php $p3= $total * 30/100?> <?php $p4= $total * 15/100 ?>
            <p class="text-xs">Pembayaran pertama lock tanggal Rp <?= number_format($p1, 0, ',', '.') ?> </p>
            <p class="text-xs">Pembayaran kedua H-60 Rp <?= number_format($p2, 0, ',', '.') ?></p>
            <p class="text-xs">Pembayaran ketiga H-30 Rp <?= number_format($p3, 0, ',', '.') ?></p>
            <p class="text-xs">Pembayaran keempat H-14 Rp <?= number_format($p4, 0, ',', '.') ?></p>
        </div>


        <div class="mb-6">
            <p class="text-xs"><strong>Syarat Dan Ketentuan Promo Diskon </strong></p>
      
            <p class="text-xs">Diskon berlaku hanya sampai H+5 setelah penawaran ini diberikan. Dan besaran diskon setiap harinya berkurang Rp 300.000.</p>
            <p class="text-xs">
                Simulasi diskon contoh pertama : 
                <p>Calon pengantin baru melakukan pembayaran pertama pada H+3 setelah surat penawaran ini diberikan, maka kamu hanya dapat mengklaim diskon sebesar Rp 600.000.</p>

                <p>Simulasi diskon contoh kedua :
                Calon pengantin baru melakukan pembayaran pertama pada H+7 setelah surat penawaran ini diberikan, maka kamu tidak dapat mengklaim diskon sama sekali karena diskon sudah kadaluarsa.</p>
            </p>
        </div>

        <!-- Grand Total -->
        <div class="mb-6">
            <p class="text-xs"><strong>Pembayaran Transfer</strong></p>
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
        <a href="<?= base_url('potensial-clients/lihat/' . $pc->id_session) ?>" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 inline-block text-center w-auto">Kembali</a>
            </div>
    </div>
</div>

</body>
</html>
