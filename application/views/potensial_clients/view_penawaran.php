<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Penawaran Potensial Clients</title>

<link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png">

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">

<style>

body{
font-family:Arial, sans-serif;
}

.page{
page-break-after:always;
min-height:1000px;
position:relative;
}

.last-page{
page-break-after:auto;
}

/* HEADER */

@media print{

@page{
margin:120px 40px 100px 40px;
}

.print-header{
position:fixed;
top:-110px;
left:0;
right:0;
height:100px;
border-bottom:1px solid #ddd;
}

.print-footer{
position:fixed;
bottom:-80px;
left:0;
right:0;
height:80px;
border-top:1px solid #ddd;
font-size:11px;
}

.page-number:after{
content:counter(page);
}

.total-pages:after{
content:counter(pages);
}

}

.watermark{
position:fixed;
top:50%;
left:50%;
transform:translate(-50%,-50%);
opacity:0.04;
width:500px;
}

</style>

</head>


<body class="bg-gray-100">

<div class="print-header flex justify-between">

<div>

<img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-02.png') ?>" width="200">

<p class="text-xs">
Teras Country Blok H No 38<br>
Tajurhalang Bogor<br>
WA : 0812-9292-9396
</p>

</div>

<div class="text-right">

<p class="text-lg font-bold">
PROPOSAL PENAWARAN
</p>

<p class="text-xs">
Tanggal : <?= date('d-m-Y') ?>
</p>

</div>

</div>

<div class="page flex flex-col justify-center items-center text-center">

<img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-02.png') ?>" width="300">

<h1 class="text-3xl font-bold mt-6">
Proposal Penawaran Wedding Organizer
</h1>

<p class="mt-4 text-lg">
Untuk
</p>

<h2 class="text-2xl font-semibold">
<?= $pc->pc_name ?>
</h2>

<p class="mt-4">
Lokasi Acara
</p>

<p class="font-semibold">
<?= $pc->location ?>
</p>

<p class="mt-2">
<?= hari($pc->event_date) ?>,
<?= tgl_indo($pc->event_date) ?>
</p>

</div>

<div class="page">

<h2 class="text-lg font-bold mb-4">
Detail Layanan
</h2>

<table class="w-full border border-black">

<thead>

<tr class="bg-gray-100">

<th class="border p-2">Layanan</th>
<th class="border p-2">Harga</th>
<th class="border p-2">Qty</th>
<th class="border p-2">Total</th>

</tr>

</thead>

<tbody>

<?php $subTotal=0; ?>

<?php foreach($penawaran as $p): ?>

<?php $total=$p->penawaran_klien_hargapromo*$p->penawaran_klien_qty ?>

<tr>

<td class="border p-2 text-xs">
<?= $p->penawaran_klien_deskripsi ?>
</td>

<td class="border text-center text-xs">
Rp <?= number_format($p->penawaran_klien_hargapromo,0,',','.') ?>
</td>

<td class="border text-center text-xs">
<?= $p->penawaran_klien_qty ?>
</td>

<td class="border text-center text-xs">
Rp <?= number_format($total,0,',','.') ?>
</td>

</tr>

<?php $subTotal+=$total ?>

<?php endforeach ?>

<tr>

<th colspan="3" class="border text-right p-2">

TOTAL

</th>

<th class="border text-center">

Rp <?= number_format($subTotal,0,',','.') ?>

</th>

</tr>

</tbody>

</table>

</div>

<div class="page">

<h2 class="text-lg font-bold mb-4">
Ketentuan Pembayaran
</h2>

<p class="text-sm">

DP pertama untuk lock tanggal

</p>

<p class="text-sm mt-2">

Pembayaran dilakukan dalam 5 tahap sesuai kesepakatan.

</p>

<br>

<h3 class="font-semibold">

Transfer Pembayaran

</h3>

<p class="text-sm">

Bank BCA  
No Rek : 1672468421  
a/n Nadi Sukses Berkarya PT

</p>

</div>

<div class="page last-page">

<h2 class="text-lg font-bold">
Persetujuan Penawaran
</h2>

<p class="text-sm mt-4">

Dengan melakukan pembayaran pertama, maka client dianggap telah menyetujui seluruh isi penawaran ini.

</p>

<br><br>

<div class="flex justify-between">

<div>

<p>Hormat Kami</p>

<br><br><br>

<p class="font-semibold">
Mantenbaru Organizer
</p>

</div>

<div>

<p>Client</p>

<br><br><br>

<p class="font-semibold">
<?= $pc->pc_name ?>
</p>

</div>

</div>

</div>


<div class="print-footer flex justify-between">

<p>
Mantenbaru Organizer
</p>

<p>

Halaman
<span class="page-number"></span>
/
<span class="total-pages"></span>

</p>

</div>

</body>
</html>