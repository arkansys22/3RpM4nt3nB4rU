<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

.watermark{
position:fixed;
top:35%;
left:45%;
transform:translate(-50%, -50%);
opacity:0.05;
z-index:-1000;
}

.watermark img{
width:100%;

filter: none;
}

@page {
margin:120px 40px 80px 40px;
}

img{
height: 100%;
}

body{
font-family: DejaVu Sans, sans-serif;
font-size:12px;
color:#333;
margin:0;
padding:0;
}

/* HEADER */

.header{
position:fixed;
top:-100px;
left:0;
right:0;
height:80px;

}

.header table{
width:100%;
}

.logo{
font-size:20px;
font-weight:bold;
color:#ed126b;
}

.sublogo{
font-size:11px;
color:#777;
}

/* FOOTER */

.footer{
position:fixed;
bottom:-60px;
left:0;
right:0;
height:50px;
border-top:1px solid #ccc;
font-size:10px;
color:#666;
}

.footer table{
width:100%;
}

.page-number:after{
content: counter(page);
}

/* PAGE */

.page{
page-break-after:always;
}

.page:last-child{
page-break-after:avoid;
}
/* COVER */

.cover{
text-align:center;
margin-top:150px;
}

.cover-title{
font-size:30px;
font-weight:bold;
color:#ed126b;
margin-bottom:10px;
}

.cover-sub{
font-size:16px;
margin-bottom:30px;
}

.cover-client{
font-size:24px;
font-weight:bold;
margin-bottom:10px;
}



.cover-date{
font-size:14px;
color:#555;
}

/* SECTION */

.section-title{
font-size:16px;
font-weight:bold;
margin-bottom:10px;
margin-top:20px;
border-bottom:1px solid #ddd;
padding-bottom:5px;
}

/* TABLE */

table{
width:100%;
border-collapse:collapse;
margin-top:10px;
}

th{
background:#f6f6f6;
border:1px solid #ddd;
padding:8px;
font-size:12px;
}

td{
border:1px solid #ddd;
padding:7px;
font-size:12px;
}

.text-right{
text-align:right;
}

.text-center{
text-align:center;
}

.total-row{
background:#fafafa;
font-weight:bold;
}

.total-cashback{
background:#FFD700;
font-weight:bold;
}

/* PAYMENT */

.payment-box{
border:1px solid #ddd;
padding:15px;
margin-top:20px;
background:#fafafa;
}

.bank{
font-weight:bold;
font-size:14px;
margin-top:10px;
}

</style>

</head>

<body>

<div class="watermark">
<img src="<?= base_url('assets/frontend/assets/images/logomantenbaru-03.png') ?>">
</div>

<!-- HEADER -->

<div class="header">

<table>

<tr>

<td>
<div class="logo">
Mantenbaru Wedding Organizer
</div>
<div class="sublogo">Teras Country Blok H No 38, Tonjong,Tajurhalang Bogor
<br> Telp / WA : 0812-9292-9396 | Web : www.mantenbaru.com <br>
</div>
</td>

<td align="right">
<strong>Proposal Penawaran</strong><br>
<?= date('d M Y') ?>
</td>

</tr>

</table>

</div>

<!-- FOOTER -->

<div class="footer">

<table>

<tr>

<td>
Mantenbaru Organizer
</td>

<td align="right">
Halaman <span class="page-number"></span>
</td>

</tr>

</table>

</div>
<br><br><br><br><br><br>

<!-- COVER -->

<div class="page">

<div class="cover">

<div class="cover-title">
Proposal Penawaran
</div>

<div class="cover-sub">
Wedding Package
</div>

<div class="cover-client">
<?= $pc->pc_name ?>
</div>

<div class="cover-date">
<?= $pc->location ?>
</div>

<br>

<div class="cover-date">
<?= hari($pc->event_date) ?>,
<?= tgl_indo($pc->event_date) ?>
</div>

</div>

</div>

<!-- DETAIL LAYANAN -->

<div class="page">

<div class="section-title">
Detail Layanan
</div>

<table>

<tr>
<th width="45%">Layanan</th>
<th width="20%">Harga</th>
<th width="10%">Qty</th>
<th width="25%">Total</th>
</tr>

<?php $subTotal=0; $diskonTotal = 0; ?>

<?php foreach($penawaran as $p): ?>

<?php 
$total=$p->penawaran_klien_hargapromo*$p->penawaran_klien_qty;
?>

<tr>
<?php $namaproduk = $this->Crud_m->view_where('data_pricelist', array('data_pricelist_idsession'=> $p->penawaran_klien_idpricelist))->row(); ?>

<td><?= $namaproduk->data_pricelist_judul ?><br><small><?= $p->penawaran_klien_deskripsi ?></small></td>

<td class="text-right">
<s>Rp <?= number_format($p->penawaran_klien_harga,0,',','.') ?></s><br>
Rp <?= number_format($p->penawaran_klien_hargapromo,0,',','.') ?>
</td>

<td class="text-center">
<?= $p->penawaran_klien_qty ?>
</td>

<td class="text-right">
Rp <?= number_format($total,0,',','.') ?>
</td>

</tr>
<?php $diskonTotal+=$p->penawaran_klien_diskon ?>
<?php $subTotal+=$total ?>


<?php endforeach ?>

<?php if ($pc->promo === 'default'){?>

<tr class="total-row">
<td colspan="3" class="text-right">
Sub Total
</td>
<td class="text-right">
Rp <?= number_format($subTotal,0,',','.') ?>
</td>
</tr>
<tr class="total-cashback">
<td colspan="3" class="text-right">
Bonus & Cashback
</td>
<td class="text-right">
Rp <?= number_format($diskonTotal,0,',','.') ?>
</td>
</tr>
<?php $total = $subTotal - $diskonTotal?>
<tr class="total-row">
<td colspan="3" class="text-right">
Total
</td>
<td class="text-right">
Rp <?= number_format($total,0,',','.') ?>
</td>
</tr>
<?php }else if($pc->promo === 'tidak'){?>
<tr class="total-row">
<td colspan="3" class="text-right">
Total
</td>
<td class="text-right">
Rp <?= number_format($subTotal,0,',','.') ?>
</td>
</tr>
<?php }else if($pc->promo === 'custom' ){ ?>
<tr class="total-row">
<td colspan="3" class="text-right">
Sub Total
</td>
<td class="text-right">
Rp <?= number_format($subTotal,0,',','.') ?>
</td>
</tr>
<tr class="total-cashback">
<td colspan="3" class="text-right">
Bonus & Cashback
</td>
<td class="text-right">
Rp <?= number_format($pc->promo_value,0,',','.') ?>
</td>
</tr>
<?php $total = $subTotal - $pc->promo_value?>
<tr class="total-row">
<td colspan="3" class="text-right">
Total
</td>
<td class="text-right">
Rp <?= number_format($total,0,',','.') ?>
</td>
</tr>

<?php }?>


</table>

<!-- PEMBAYARAN -->

<?php
$today = new DateTime();
$event = new DateTime($pc->event_date);
$diff  = $today->diff($event);
$days_to_event = $diff->days;
?>

		<?php
            $aa = new DateTime($pc->event_date);
            $b  = clone $aa;
            $b->modify('-60 days');
            $c  = clone $aa;
            $c->modify('-30 days');
            $d  = clone $aa;
            $d->modify('-14 days');
            ?>


        <?php

			$grandTotal = ($pc->promo === 'tidak') ? $subTotal : $total;

			$p1 = 1000000;

			if($days_to_event < 30){

			    $p2 = $grandTotal - $p1;

			}elseif($days_to_event < 60){

			    $p2 = ($grandTotal - $p1) * 15/100;
			    $p3 = ($grandTotal - $p1) * 45/100;
			    $p4 = ($grandTotal - $p1) * 40/100;

			}else{

			    $p2 = ($grandTotal - $p1) * 15/100;
			    $p3 = ($grandTotal - $p1) * 35/100;
			    $p4 = ($grandTotal - $p1) * 35/100;
			    $p5 = ($grandTotal - $p1) * 15/100;

			}
		?>

	<?php if($pc->promo === 'tidak' ){ ?>
	<div><i>Harga dapat berubah sewaktu-waktu jika belum melakukan pembayaran pertama untuk kunci harga.</i>
	</div><br>
        
        <?php }else{ ?>		
<div><i>Harga dapat berubah sewaktu-waktu jika belum melakukan pembayaran pertama untuk kunci harga. Bonus dan Cashback berlaku hanya sampai H+5 setelah penawaran ini diberikan. Dan besaran diskon setiap harinya berkurang Rp 200.000.</i></div><br>
<?php }?>
<div class="section-title">
Ketentuan Pembayaran
</div>

<ul>

<li>Pembayaran pertama <b>kunci harga</b> Rp <?= number_format($p1,0,',','.') ?></li>

<?php if($days_to_event < 30){ ?>

<li>Pembayaran kedua <b>pelunasan</b> Rp <?= number_format($p2,0,',','.') ?></li>

<?php }elseif($days_to_event < 60){ ?>

<li>Pembayaran kedua Rp <?= number_format($p2,0,',','.') ?></li>

<li>Pembayaran ketiga <b>H-30 acara (<?= tgl_indo($c->format('Y-m-d')) ?>)</b>
Rp <?= number_format($p3,0,',','.') ?></li>

<li>Pembayaran keempat <b>H-14 acara (<?= tgl_indo($d->format('Y-m-d')) ?>)</b>
Rp <?= number_format($p4,0,',','.') ?></li>

<?php }else{ ?>

<li>Pembayaran kedua <b>kunci tanggal</b> H+14 setelah pembayaran pertama
Rp <?= number_format($p2,0,',','.') ?></li>

<li>Pembayaran ketiga <b>H-60 acara (<?= tgl_indo($b->format('Y-m-d')) ?>)</b>
Rp <?= number_format($p3,0,',','.') ?></li>

<li>Pembayaran keempat <b>H-30 acara (<?= tgl_indo($c->format('Y-m-d')) ?>)</b>
Rp <?= number_format($p4,0,',','.') ?></li>

<li>Pembayaran kelima <b>H-14 acara (<?= tgl_indo($d->format('Y-m-d')) ?>)</b>
Rp <?= number_format($p5,0,',','.') ?></li>

<?php } ?>

</ul>

<div class="payment-box">

<div>Transfer Pembayaran :</div>

<div class="bank">
Bank BCA
</div>

No Rek : 1672468421<br>
a/n Nadi Sukses Berkarya PT

</div>



</div>


</body>
</html>