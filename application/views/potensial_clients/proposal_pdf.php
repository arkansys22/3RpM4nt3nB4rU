<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<style>

body{
font-family: DejaVu Sans, sans-serif;
font-size:12px;
}

.header{
position:fixed;
top:-40px;
left:0;
right:0;
height:50px;
border-bottom:1px solid #ccc;
}

.footer{
position:fixed;
bottom:-30px;
left:0;
right:0;
height:40px;
border-top:1px solid #ccc;
text-align:left;
font-size:10px;
}

.page{
page-break-after:always;
}

table{
width:100%;
border-collapse:collapse;
}

table,th,td{
border:1px solid #000;
}

th,td{
padding:6px;
}

</style>

</head>

<body>

<div class="header">

<table width="100%">

<tr>

<td>
<strong>Mantenbaru Organizer</strong><br>
Tajurhalang Bogor
</td>

<td align="right">
Proposal Penawaran<br>
<?= date('d-m-Y') ?>
</td>

</tr>

</table>

</div>

<div class="footer">

Mantenbaru Organizer

</div>

<!-- COVER -->

<div class="page" style="margin-top:80px;text-align:center">

<h1>Proposal Penawaran Wedding Organizer</h1>

<p>Untuk</p>

<h2><?= $pc->pc_name ?></h2>

<p><?= $pc->location ?></p>

<p>
<?= hari($pc->event_date) ?>,
<?= tgl_indo($pc->event_date) ?>
</p>

</div>

<!-- DETAIL -->

<div class="page">

<h3>Detail Layanan</h3>

<table>

<tr>
<th>Layanan</th>
<th>Harga</th>
<th>Qty</th>
<th>Total</th>
</tr>

<?php $subTotal=0; ?>

<?php foreach($penawaran as $p): ?>

<?php $total=$p->penawaran_klien_hargapromo*$p->penawaran_klien_qty ?>

<tr>

<td><?= $p->penawaran_klien_deskripsi ?></td>

<td>
Rp <?= number_format($p->penawaran_klien_hargapromo,0,',','.') ?>
</td>

<td><?= $p->penawaran_klien_qty ?></td>

<td>
Rp <?= number_format($total,0,',','.') ?>
</td>

</tr>

<?php $subTotal+=$total ?>

<?php endforeach ?>

<tr>

<th colspan="3">TOTAL</th>

<th>
Rp <?= number_format($subTotal,0,',','.') ?>
</th>

</tr>

</table>

</div>

<!-- PEMBAYARAN -->

<div>

<h3>Ketentuan Pembayaran</h3>

<p>DP pertama untuk lock tanggal.</p>
<p>Pembayaran dilakukan dalam 5 tahap.</p>

<br>

<h4>Transfer Pembayaran</h4>

<p>
Bank BCA<br>
No Rek : 1672468421<br>
a/n Nadi Sukses Berkarya PT
</p>

</div>

</body>
</html>