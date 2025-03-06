<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>List Vendor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse; /* Biar border menyatu */
            table-layout: fixed; /* Biar lebar kolom tetap */
        }
        th, td {
            border: 1px solid black; /* Semua tabel punya border sama */
            padding: 5px;
            text-align: left;
        }
        .no-border {
            border: none; /* Buat tabel yang gak butuh border */
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

<div class="container">
<table width="100%" style="border: none; background-color: transparent; margin-bottom: 10px;">
    <tr>
        <td style="text-align: left; vertical-align: top; border: none;">
            <h1 style="font-weight: bold; font-size: 14px; margin: 0;">ACARA AKAD RESEPSI</h1>
            <p style="font-size: 12px; margin: 0;"><?= $client->location; ?></p>
            <p style="font-size: 12px; margin: 0;">
                <?= hari($client->wedding_date) ?>, <?= tgl_indo($client->wedding_date) ?>
            </p>
        </td>
        <td style="text-align: right; font-weight: bold; font-size: 14px; vertical-align: top; border: none;">
            <?= $client->client_name; ?>
        </td>
    </tr>
</table><br>

<table>
        <tr>
        <th colspan="3" style="text-align: center;">List Vendor</th>
        </tr>
        <tr>
            <td>Venue & Keamanan<br>
            <?= $vendor->detail_1; ?></td>
            <td><?= $vendor->vendor_1; ?><br>
            ig: @<?= $vendor->social_media_1; ?></td>
            <td><?= $vendor->contact_name_1; ?><br>
            <?= $vendor->phone_1; ?></td>
        </tr>
        <tr>
            <td>MC<br>
            <?= $vendor->detail_2; ?></td>
            <td><?= $vendor->vendor_2; ?><br>
            ig: @<?= $vendor->social_media_2; ?></td>
            <td><?= $vendor->contact_name_2; ?><br>
            <?= $vendor->phone_2; ?></td>
        </tr>
        <tr>
            <td>Wedding Planer & Crew<br>
            <?= $vendor->detail_3; ?></td>
            <td><?= $vendor->vendor_3; ?><br>
            ig: @<?= $vendor->social_media_3; ?></td>
            <td><?= $vendor->contact_name_3; ?><br>
            <?= $vendor->phone_3; ?></td>
        </tr>
        <tr>
            <td>Perias Keluarga Pengantin<br>
            <?= $vendor->detail_4; ?></td>
            <td><?= $vendor->vendor_4; ?><br>
            ig: @<?= $vendor->social_media_4; ?></td>
            <td><?= $vendor->contact_name_4; ?><br>
            <?= $vendor->phone_4; ?></td>
        </tr>
        <tr>
            <td>Perlengkapan Catering<br>
            <?= $vendor->detail_5; ?></td>
            <td><?= $vendor->vendor_5; ?><br>
            ig: @<?= $vendor->social_media_5; ?></td>
            <td><?= $vendor->contact_name_5; ?><br>
            <?= $vendor->phone_5; ?></td>
        </tr>
        <tr>
            <td>Konsumsi<br>
            <?= $vendor->detail_6; ?></td>
            <td><?= $vendor->vendor_6; ?><br>
            ig: @<?= $vendor->social_media_6; ?></td>
            <td><?= $vendor->contact_name_6; ?><br>
            <?= $vendor->phone_6; ?></td>
        </tr>
        <tr>
            <td>Dokumentasi<br>
            <?= $vendor->detail_7; ?></td>
            <td><?= $vendor->vendor_7; ?><br>
            ig: @<?= $vendor->social_media_7; ?></td>
            <td><?= $vendor->contact_name_7; ?><br>
            <?= $vendor->phone_7; ?></td>
        </tr>
        <tr>
            <td>Dekorasi<br>
            <?= $vendor->detail_8; ?></td>
            <td><?= $vendor->vendor_8; ?><br>
            ig: @<?= $vendor->social_media_8; ?></td>
            <td><?= $vendor->contact_name_8; ?><br>
            <?= $vendor->phone_8; ?></td>
        </tr>
        <tr>
            <td>Entertainment<br>
            <?= $vendor->detail_9; ?></td>
            <td><?= $vendor->vendor_9; ?><br>
            ig: @<?= $vendor->social_media_9; ?></td>
            <td><?= $vendor->contact_name_9; ?><br>
            <?= $vendor->phone_9; ?></td>
        </tr>
    </table>
</div>

</body>
</html>