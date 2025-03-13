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
            <?php 
            $order = ['Venue', 'MC Akad', 'MC Resepsi', 'Wedding Organizer', 'MUA', 'Perlengkapan Catering', 'Catering', 'Dokumentasi', 'Dekorasi', 'Entertainment'];
            usort($vendors, function($a, $b) use ($order) {
                $pos_a = array_search($a->type, $order);
                $pos_b = array_search($b->type, $order);
                return $pos_a - $pos_b;
            });
            foreach ($vendors as $vendor): ?>
            <tr>
                <td class="border border-black p-1 text-xs w-3/6"><?= $vendor->type; ?><br>
                <?= nl2br($vendor->detail); ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->vendor; ?><br>
                ig: @<?= $vendor->social_media; ?></td>
                <td class="border border-black p-1 text-xs w-2/6"><?= $vendor->contact_name; ?><br>
                (<?= $vendor->phone; ?>)</td>
            </tr>
            <?php endforeach; ?>
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
