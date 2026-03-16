<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="format-detection" content="telephone=no">

<title>Penawaran Potensial Clients</title>

<link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">

<style>
/* PAGE COUNTER */

body{
font-family:Arial, sans-serif;
background:#f3f4f6;
counter-reset: page;
}

/* SETIAP HALAMAN MENAMBAH COUNTER */

.page{
width:210mm;
min-height:297mm;
margin:auto;
background:white;
padding:140px 40px 100px 40px;
page-break-after:always;
position:relative;

counter-increment: page;
}

/* PAGE NUMBER */

.page-number::after{
content: "Halaman " counter(page);
}

/* PRINT SETTING */

@page{
size:A4;
margin:0;
}

html, body{
margin:0;
padding:0;
}

/* PRINT MODE */

@media print{

body{
background:white;
margin:0;
padding:0;
-webkit-print-color-adjust:exact;
print-color-adjust:exact;
}

/* HILANGKAN MARGIN BROWSER */

@page{
margin:0;
}

/* HALAMAN */

.page{
margin:0;
box-shadow:none;
}

/* SEMBUNYIKAN ELEMENT TIDAK PERLU */

.no-print{
display:none;
}

}

body{
font-family:Arial, sans-serif;
background:#f3f4f6;
}

/* UKURAN HALAMAN */

.page{
width:210mm;
min-height:297mm;
margin:auto;
background:white;
padding:140px 40px 100px 40px;
page-break-after:always;
position:relative;
}

.last-page{
page-break-after:auto;
}

/* HEADER */

.print-header{
position:fixed;
top:0;
left:0;
width:100%;
height:110px;
padding:20px 40px;
background:white;
border-bottom:1px solid #ddd;
z-index:1000;
}

/* FOOTER */

.print-footer{
position:fixed;
bottom:0;
left:0;
width:100%;
height:70px;
padding:15px 40px;
background:white;
border-top:1px solid #ddd;
font-size:11px;
}

/* WATERMARK */

.watermark{
position:fixed;
top:50%;
left:50%;
transform:translate(-50%,-50%);
opacity:0.05;
width:420px;
z-index:-1;
}

/* TABEL PRINT FIX */

table{
page-break-inside:auto;
}

tr{
page-break-inside:avoid;
}

/* PAGE NUMBER */

.page-number:after{
content:counter(page);
}

.total-pages:after{
content:counter(pages);
}

/* PRINT */

@media print{

body{
background:white;
}

.page{
margin:0;
}

}

.no-print{
display:block;
}

@media print{

.no-print{
display:none;
}

}

</style>



</head>

<body>


<img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-03.png') ?>" class="watermark">


<!-- HEADER -->

<div class="print-header flex justify-between items-center">

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



<!-- COVER -->

<div class="page text-center flex flex-col justify-center">

<img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-02.png') ?>" width="260" class="mx-auto">

<h1 class="text-3xl font-bold mt-10">
Proposal Penawaran Wedding Organizer
</h1>

<p class="mt-6 text-lg">Untuk</p>

<h2 class="text-2xl font-semibold">
<?= $pc->pc_name ?>
</h2>

<p class="mt-6">Lokasi Acara</p>

<p class="font-semibold">
<?= $pc->location ?>
</p>

<p class="mt-2">
<?= hari($pc->event_date) ?>,
<?= tgl_indo($pc->event_date) ?>
</p>

</div>


<!-- DETAIL LAYANAN -->

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


<!-- PEMBAYARAN -->

<div class="page">

<h2 class="text-lg font-bold mb-4">
Ketentuan Pembayaran
</h2>

<p class="text-sm">
DP pertama untuk lock tanggal.
</p>

<p class="text-sm mt-2">
Pembayaran dilakukan dalam 5 tahap sesuai kesepakatan.
</p>

<br>

<h3 class="font-semibold">
Transfer Pembayaran
</h3>

<p class="text-sm">
Bank BCA<br>
No Rek : 1672468421<br>
a/n Nadi Sukses Berkarya PT
</p>

</div>


<!-- PERSETUJUAN -->

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

<!-- TOMBOL PRINT PDF -->

<div class="no-print text-center mt-16">

<button onclick="printPDF()" 
class="bg-red-600 hover:bg-red-700 text-white font-semibold px-10 py-3 rounded-lg shadow-lg">

📄 Print / Download PDF

</button>

</div>

</div>


<!-- FOOTER -->

<div class="print-footer flex justify-between">

<p>
Mantenbaru Organizer
</p>

<p class="page-number"></p>

</div>

<script>

function printPDF(){

setTimeout(function(){
window.print();
},300);

}

</script>

</body>
</html>