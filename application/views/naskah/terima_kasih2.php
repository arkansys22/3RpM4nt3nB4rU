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
        <p class="indent text-lg text-justify">Dengan penuh rasa syukur, kami mengucapkan terima kasih yang sebesar-besarnya kepada semua saudara dan saudari yang telah hadir dalam perayaan pernikahan kami. Kehadiran dan doa-doa yang tulus dari Anda semua sangat berarti bagi kami. Kami merasa diberkati karena dikelilingi oleh orang-orang yang begitu mengasihi dan mendukung kami.
Kami berdoa agar kasih Tuhan senantiasa menyertai setiap langkah hidup kita, dan semoga kita semua selalu diberkati dengan kedamaian, kebahagiaan, serta keberhasilan dalam segala hal. Terima kasih telah menjadi bagian dari hari yang sangat istimewa ini.</p>
<p class="text-lg">Tuhan memberkati kita semua.</p>

        <div class="mt-6 flex justify-between no-print">
            <a href="<?= base_url('naskah/terima_kasih2/pdf/' . $client->id_session); ?>" 
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
