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
        <?php 
        $order = ['Venue', 'MC Akad', 'MC Resepsi', 'Wedding Organizer', 'MUA', 'Perlengkapan Catering', 'Catering', 'Dokumentasi', 'Dekorasi', 'Entertainment'];
        usort($vendors, function($a, $b) use ($order) {
            $pos_a = array_search($a->type, $order);
            $pos_b = array_search($b->type, $order);
            return $pos_a - $pos_b;
        });
        foreach ($vendors as $vendor): ?>
        <tr>
            <td><?= $vendor->type; ?><br>
            <?= nl2br($vendor->detail); ?></td>
            <td><?= $vendor->vendor; ?><br>
            ig: @<?= $vendor->social_media; ?></td>
            <td><?= $vendor->contact_name; ?><br>
            <?= $vendor->phone; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>