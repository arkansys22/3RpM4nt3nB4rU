<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naskah Ucapan Terima Kasih</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
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
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg">
        <h1 class="text-xl font-bold text-center mb-4">Kata Sambutan Terima Kasih Oleh Pengantin Pria Di Resepsi</h1>
</br></br>
        <p class="text-lg">Assalamualaikum warahmatullahi wabarakatuh.</p>
        <p class="indent text-lg text-justify">Rasa syukur yang tak terhingga saya panjatkan kehadirat Allah SWT atas
        segala rahmat dan karunia-Nya, sehingga pada hari yang berbahagia ini
        kita dapat berkumpul bersama dalam acara pernikahan saya dan <?= $client->f_bride_fname; ?>.</p>
        <p class="indent text-lg text-justify">Kehadiran Bapak, Ibu, saudara-saudara sekalian merupakan kehormatan
        bagi kami. Doa dan restu yang Bapak, Ibu, dan saudara-saudara sekalian
        berikan akan menjadi semangat bagi kami dalam membangun bahtera
        rumah tangga.</p>
        <p class="indent text-lg text-justify">Terima kasih juga kepada keluarga besar, sahabat, dan rekan kerja yang
        telah banyak membantu dalam mempersiapkan acara ini. Tanpa dukungan
        kalian semua, acara ini tidak akan berjalan dengan lancar.</p>
        <p class="indent text-lg text-justify">Semoga Allah SWT senantiasa melimpahkan rahmat dan karunia-Nya
        kepada kita semua. Amin.</p>
        <p class="text-lg">Terima kasih.</p>

        <div class="mt-6 flex justify-between no-print">
            <a href="<?= base_url('naskah/terima_kasih/pdf/' . $client->id_session); ?>" 
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
               Download PDF
            </a>
            <button onclick="window.print()" 
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Print
            </button>
        </div>
    </div>
</body>
</html>
