<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naskah Jubir CPW - Ngunduh Mantu</title>
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
        <h1 class="text-xl font-bold text-center mb-4">Sambutan Ngunduh Mantu dari Pihak Pengantin Wanita</h1>
</br>
        <p class="text-lg">Assalamu'alaikum Warahmatullahi Wabarakatuh.</p>
        <p class="text-lg">Sejahtera untuk kita semua yang hadir.</p>
        <p class="indent text-lg text-justify">Pertama marilah kita panjatkan puji syukur kehadirat Alloh SWT. Tuhan Yang
        Maha Kuasa, karena atas kuasaNYA kita masih diberikan keberkahan, kesehatan sehingga pada hari ini kita bisa
        melaksanakan resepsi pernikahan ini.</p>
        <p class="indent text-lg text-justify">Saya mewakili keluarga mempelai wanita ingin mengucapkan terima kasih
        kepada semua yang telah datang untuk merayakan pernikahan ananda <strong><?= $client->m_bride_fname; ?></strong>
        dan <strong><?= $client->f_bride_fname; ?></strong> yang telah dilangsungkan akad nikahnya pada hari
        <strong><?= format_tanggal_acara($client->wedding_date); ?> di <?= $client->location; ?></strong>.</p>
        <p class="indent text-lg text-justify">Kami ingin memberikan sedikit wejangan kepada kedua mempelai, dalam
        perjalanan rumah tangga, ada kalanya kalian akan menghadapi tantangan, dan cobaan itu sesuatu yang sangat wajar.
        Kenapa? Yang namanya dua pikiran, dua prinsip yang berbeda tentu masing-masing akan mempertahankan argumennya.
        Namun untuk menghadapi semua itu yang dibutuhkan adalah kejujuran satu sama lain, tidak lagi menyimpan rahasia
        untuk keluarga, karena baik buruknya keluarga itu yang kalian berdua yang menjalani. Untuk itu kejujuran adalah
        modal yang sangat penting untuk dijaga.</p>
        <p class="indent text-lg text-justify">Yang kedua kuncinya adalah saling mendukung dan berkomunikasi dengan
        baik, apapun yang akan dilakukan, dilaksanakan sebaiknya dikomunikasikan berdua, sehingga keputusan dan
        hasilnya adalah merupakan keputusan bersama dan baik buruknya hasil keputusan itu merupakan kesepakatan
        sebelum bertindak.</p>
        <p class="indent text-lg text-justify">Yang ketiga jangan pernah lupa untuk saling menghormati dan selalu
        menghargai perbedaan. Nah ini modal yang harus dijaga adalah menghargai pendapat satu sama lain, tidak boleh
        menang sendiri, atau egois.</p>
        <p class="indent text-lg text-justify">Jika tiga hal ini mampu untuk dijalani insyaallah dengan bekal
        kedewasaan kalian berpikir, berperilaku dewasa, pasti kalian bisa menghadapi segala tantangan, cobaan di masa
        yang akan datang. Kami mendoakan agar kehidupan kalian selalu sejahtera dan dipenuhi dengan cinta, Aamiin.</p>
        <p class="text-lg" style="font-style: italic;">
            Menanam padi di tengah sawah<br>
            Disiram hujan tumbuh subur<br>
            Rumah tangga jangan banyak amarah<br>
            Saling mengerti, saling maklum
        </p>
        <p class="text-lg">Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>

        <div class="mt-6 flex justify-between items-center no-print">
            <a href="<?= base_url('naskah/jubir_cpw/pdf/' . $client->id_session); ?>"
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
