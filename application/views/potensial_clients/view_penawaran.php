<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Penawaran Potensial Clients</title>

<link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">

<style>

body{
    background:#f3f4f6;
}

.header{
    width:100%;
}

.footer{
    width:100%;
    font-size:10px;
    text-align:center;
}

.background-image{
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    z-index:-1;
    opacity:0.08;
}

@media print {

.no-print{
    display:none !important;
}

@page{
    margin:120px 40px 80px 40px;
}

.header{
    position:fixed;
    top:-100px;
    left:0;
    right:0;
}

.footer{
    position:fixed;
    bottom:-60px;
    left:0;
    right:0;
}

.content{
    width:100%;
}

}

</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <div class="flex justify-between mb-6">
        <div class="w-1/2">
            <img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-02.png') ?>" style="width:200px">
            <p class="text-sm"><strong>MANTENBARU ORGANIZER</strong></p>
            <p class="text-xs">Teras Country Blok H No 38, Tonjong</p>
            <p class="text-xs">Tajurhalang, Kab. Bogor</p>
            <p class="text-xs">Telp / WA : 0812-9292-9396</p>
            <p class="text-xs">Web : www.mantenbaru.com</p>
        </div>

        <div class="w-60 text-center">
            <p class="text-sm mt-6"><strong>Lembar Penawaran</strong></p>
        </div>
    </div>
</div>


<!-- CONTENT -->
<div class="content max-w-full mx-auto p-8 relative">

<div class="mb-6">
<p class="text-sm"><strong>PENAWARAN KEPADA</strong></p>
<p class="text-xs"><?= $pc->pc_name; ?></p>
<p class="text-xs">Untuk acara yang rencana berlokasi di</p>
<p class="text-xs"><?= $pc->location; ?></p>
<p class="text-xs">
<?= hari($pc->event_date) ?>, <?= tgl_indo($pc->event_date) ?>
</p>
</div>


<!-- TABEL -->
<div class="mb-6">

<table class="table-auto w-full border border-black">

<thead>
<tr>
<th class="border border-black p-2 text-sm w-2/5">Nama Produk</th>
<th class="border border-black p-2 text-sm w-1/5">Harga Asli</th>
<th class="border border-black p-2 text-sm w-1/5">Harga Promo</th>
<th class="border border-black p-2 text-sm">Qty</th>
<th class="border border-black p-2 text-sm w-1/5">Total</th>
</tr>
</thead>

<tbody>

<?php $subTotal=0; $diskonTotal=0; foreach($penawaran as $p): ?>

<?php
$namaproduk = $this->Crud_m
->view_where('data_pricelist',
array('data_pricelist_idsession'=> $p->penawaran_klien_idpricelist))
->row();
?>

<tr>

<td class="border border-black p-2 text-xs">
<?= $namaproduk->data_pricelist_judul ?>
<p><small><?= $p->penawaran_klien_deskripsi ?></small></p>
</td>

<td class="border border-black text-center text-xs">
<s>Rp <?= number_format($p->penawaran_klien_harga,0,',','.') ?></s>
</td>

<td class="border border-black text-center text-xs">
Rp <?= number_format($p->penawaran_klien_hargapromo,0,',','.') ?>
</td>

<td class="border border-black text-center text-xs">
<?= $p->penawaran_klien_qty ?>
</td>

<?php $total = $p->penawaran_klien_hargapromo * $p->penawaran_klien_qty ?>

<td class="border border-black text-center text-xs">
Rp <?= number_format($total,0,',','.') ?>
</td>

</tr>

<?php
$subTotal += $total;
$diskonTotal += $p->penawaran_klien_diskon;
?>

<?php endforeach ?>

</tbody>
</table>

</div>


<img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-03.png') ?>"
class="background-image">

</div>


<!-- FOOTER -->
<div class="footer">
<hr class="mb-1">
<p>Mantenbaru Organizer • www.mantenbaru.com</p>
<p>Dokumen penawaran ini dibuat otomatis oleh sistem</p>
</div>


<!-- BUTTON -->
<div class="mt-6 flex justify-between no-print px-8 pb-8">

<button onclick="window.print()"
class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
Print
</button>

<a href="<?= base_url('potensial-clients/lihat/' . $pc->id_session) ?>"
class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
Kembali
</a>

</div>

</body>
</html>