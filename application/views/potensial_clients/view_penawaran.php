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
font-family: Arial, Helvetica, sans-serif;
}

/* WATERMARK */

.watermark{
position:fixed;
top:50%;
left:50%;
transform:translate(-50%,-50%);
opacity:0.05;
z-index:-1;
width:500px;
}

/* PRINT STYLE */

@media print{

.no-print{
display:none!important;
}

@page{
margin-top:140px;
margin-bottom:120px;
margin-left:40px;
margin-right:40px;
}

/* HEADER */

.print-header{
position:fixed;
top:-120px;
left:0;
right:0;
height:120px;
border-bottom:2px solid #ddd;
}

/* FOOTER */

.print-footer{
position:fixed;
bottom:-100px;
left:0;
right:0;
height:100px;
border-top:1px solid #ddd;
font-size:11px;
}

/* PAGE NUMBER */

.page-number:after{
content:counter(page);
}

.total-pages:after{
content:counter(pages);
}

/* TABLE FIX */

table{
page-break-inside:auto;
}

tr{
page-break-inside:avoid;
page-break-after:auto;
}

}

/* HEADER STYLE */

.company-name{
font-weight:bold;
font-size:15px;
}

.doc-title{
font-size:18px;
font-weight:bold;
}

</style>

</head>


<body class="bg-gray-100">

<img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-03.png') ?>" class="watermark">

<div class="max-w-6xl mx-auto p-6 bg-white">

<!-- HEADER -->

<div class="print-header flex justify-between items-center">

<div>

<img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-02.png') ?>" style="width:220px">

<p class="company-name">MANTENBARU ORGANIZER</p>

<p class="text-xs">Teras Country Blok H No 38, Tonjong</p>

<p class="text-xs">Tajurhalang, Kabupaten Bogor</p>

<p class="text-xs">Telp / WA : 0812-9292-9396</p>

<p class="text-xs">www.mantenbaru.com</p>

</div>

<div class="text-right">

<p class="doc-title">LEMBAR PENAWARAN</p>

<p class="text-xs">
Tanggal :
<?= date('d-m-Y') ?>
</p>

</div>

</div>


<!-- CONTENT -->

<div class="mt-4">

<p class="text-sm font-semibold">PENAWARAN KEPADA</p>

<p class="text-xs"><?= $pc->pc_name; ?></p>

<p class="text-xs">Lokasi acara :</p>

<p class="text-xs"><?= $pc->location; ?></p>

<p class="text-xs">
Tanggal acara :
<?= hari($pc->event_date) ?>,
<?= tgl_indo($pc->event_date) ?>
</p>

</div>


<!-- TABEL LAYANAN -->

<div class="mt-6">

<table class="table-auto w-full border border-black">

<thead>

<tr class="bg-gray-100">

<th class="border border-black p-2 text-sm w-2/5">Nama Produk</th>

<th class="border border-black p-2 text-sm">Harga Asli</th>

<th class="border border-black p-2 text-sm">Harga Promo</th>

<th class="border border-black p-2 text-sm">Qty</th>

<th class="border border-black p-2 text-sm">Total</th>

</tr>

</thead>

<tbody>

<?php $subTotal=0; $diskonTotal=0; ?>

<?php foreach($penawaran as $p): ?>

<?php
$namaproduk=$this->Crud_m->view_where(
'data_pricelist',
array('data_pricelist_idsession'=>$p->penawaran_klien_idpricelist)
)->row();
?>

<tr>

<td class="border border-black p-2 text-xs">

<?= $namaproduk->data_pricelist_judul ?>

<p><small><?= $p->penawaran_klien_deskripsi ?></small></p>

</td>

<td class="border border-black text-center text-xs">

<s>
Rp <?= number_format($p->penawaran_klien_harga,0,',','.') ?>
</s>

</td>

<td class="border border-black text-center text-xs">

Rp <?= number_format($p->penawaran_klien_hargapromo,0,',','.') ?>

</td>

<td class="border border-black text-center text-xs">

<?= $p->penawaran_klien_qty ?>

</td>

<?php $total=$p->penawaran_klien_hargapromo*$p->penawaran_klien_qty ?>

<td class="border border-black text-center text-xs">

Rp <?= number_format($total,0,',','.') ?>

</td>

</tr>

<?php
$subTotal+=$total;
$diskonTotal+=$p->penawaran_klien_diskon;
?>

<?php endforeach ?>

<tr>

<th colspan="4" class="border border-black text-right text-sm p-2">

SUB TOTAL

</th>

<td class="border border-black text-center text-xs">

Rp <?= number_format($subTotal,0,',','.') ?>

</td>

</tr>

<tr>

<th colspan="4" class="border border-black text-right text-sm p-2">

PROMO DISKON

</th>

<td class="border border-black text-center text-xs">

Rp <?= number_format($diskonTotal,0,',','.') ?>

</td>

</tr>

<tr>

<?php $total=$subTotal-$diskonTotal ?>

<th colspan="4" class="border border-black text-right text-sm p-2">

TOTAL

</th>

<td class="border border-black text-center text-xs font-bold">

Rp <?= number_format($total,0,',','.') ?>

</td>

</tr>

</tbody>

</table>

</div>


<!-- FOOTER -->

<div class="print-footer flex justify-between items-center">

<div>

<p>Mantenbaru Organizer</p>

<p>Wedding Organizer Profesional</p>

</div>

<div>

Halaman
<span class="page-number"></span>
/
<span class="total-pages"></span>

</div>

</div>


<div class="mt-10 flex gap-2 no-print">

<button onclick="window.print()" class="bg-gray-600 text-white px-4 py-2 rounded">

Print

</button>

<a href="<?= base_url('potensial-clients/lihat/'.$pc->id_session) ?>" class="bg-gray-600 text-white px-4 py-2 rounded">

Kembali

</a>

</div>

</div>

</body>
</html>