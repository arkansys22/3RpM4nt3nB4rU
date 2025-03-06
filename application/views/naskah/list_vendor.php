<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Vendor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
    .no-print {
        display: none !important; /* Menyembunyikan elemen dengan class 'no-print' */
    }
    @page {
        margin: 0; /* Menghapus margin default browser */
    }
    }
        p.indent {
            text-indent: 40px; /* Indentasi awal paragraf */
        }
    table {
        width: 80%;
        margin: 0 auto; /* Agar tetap di tengah */
    }
</style>
    </style>
</head>
<body class="p-5">

    <div class="max-w-[80%] mx-auto text-left">
    <!-- Bagian Header -->
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="font-bold text-sm">ACARA AKAD RESEPSI</h1>
            <p class="text-xs"><?= $client->location; ?></p>
            <p class="text-xs"><?= hari($client->wedding_date) ?>, <?= tgl_indo($client->wedding_date) ?></p>
        </div>
        <div class="text-right font-bold text-sm">
            <?= $client->client_name; ?>
        </div>
    </div><br>

    <div class = "w-[80%] mx-auto flex flex-col items-center">
    <!-- Bagian Tabel -->
    <table class="w-full border border-black text-left">
        <thead>
            <tr>
                <th colspan="3" class="border border-black text-center text-sm font-semibold p-1">
                    List Vendor
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border border-black p-1 text-xs w-3/6">Venue & Keamanan<br>
                <?= nl2br($vendor->detail_1); ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->vendor_1; ?><br>
                ig: @<?= $vendor->social_media_1; ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->contact_name_1; ?><br>
                (<?= $vendor->phone_1; ?>)</td>
            </tr>
            <tr>
                <td class="border border-black p-1 text-xs w-3/6">MC
                <?= $vendor->detail_2; ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->vendor_2; ?><br>
                ig: @<?= $vendor->social_media_2; ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->contact_name_2; ?><br>
                (<?= $vendor->phone_2; ?>)</td>
            </tr>
            <tr>
                <td class="border border-black p-1 text-xs w-3/6">Wedding Planer & Crew<br>
                <?= nl2br($vendor->detail_3); ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->vendor_3; ?><br>
                ig: @<?= $vendor->social_media_3; ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->contact_name_3; ?><br>
                (<?= $vendor->phone_3; ?>)</td>
            </tr>
            <tr>
                <td class="border border-black p-1 text-xs w-3/6">Perias
                <?= $vendor->detail_4; ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->vendor_4; ?><br>
                ig: @<?= $vendor->social_media_4; ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->contact_name_4; ?><br>
                (<?= $vendor->phone_4; ?>)</td>
            </tr>
            <tr>
                <td class="border border-black p-1 text-xs w-3/6">Perlengkapan Catering<br>
                <?= nl2br($vendor->detail_5); ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->vendor_5; ?><br>
                ig: @<?= $vendor->social_media_5; ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->contact_name_5; ?><br>
                (<?= $vendor->phone_5; ?>)</td>
            </tr>
            <tr>
                <td class="border border-black p-1 text-xs w-3/6">Konsumsi<br>
                <?= nl2br($vendor->detail_6); ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->vendor_6; ?><br>
                ig: @<?= $vendor->social_media_6; ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->contact_name_6; ?><br>
                (<?= $vendor->phone_6; ?>)</td>
            </tr>
            <tr>
                <td class="border border-black p-1 text-xs w-3/6">Dokumentasi<br>
                <?= nl2br($vendor->detail_7); ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->vendor_7; ?><br>
                ig: @<?= $vendor->social_media_7; ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->contact_name_7; ?><br>
                (<?= $vendor->phone_7; ?>)</td>
            </tr>
            <tr>
                <td class="border border-black p-1 text-xs w-3/6">Dekorasi<br>
                <?= nl2br($vendor->detail_8); ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->vendor_8; ?><br>
                ig: @<?= $vendor->social_media_8; ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->contact_name_8; ?><br>
                (<?= $vendor->phone_8; ?>)</td>
            </tr>
            <tr>
                <td class="border border-black p-1 text-xs w-3/6">Entertainment<br>
                <?= nl2br($vendor->detail_9); ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->vendor_9; ?><br>
                ig: @<?= $vendor->social_media_9; ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->contact_name_9; ?><br>
                (<?= $vendor->phone_9; ?>)</td>
            </tr>
        </tbody>
    </table><br>

    </div>

    <div class="mt-6 flex justify-between no-print">
    <div class="flex">
        <a href="<?= base_url('naskah/list_vendor/pdf/' . $client->id_session); ?>" 
           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
           Download PDF
        </a>
        <button onclick="window.print()" 
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
            Print
        </button>
    </div>
    <a href="javascript:history.back()" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 inline-block text-center w-auto">Kembali</a>
    </div>
    </div>
</body>
</html>
