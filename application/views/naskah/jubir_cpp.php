<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naskah Jubir CPP</title>
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
            .print-only {
                display: block; /* Menampilkan elemen dengan class 'print-only' */
            }
        }
        @media screen {
            .print-only {
                display: none; /* Menyembunyikan elemen dengan class 'print-only' */
            }
        }
        p.indent {
            text-indent: 40px; /* Indentasi awal paragraf */
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg">
        <h1 class="text-xl font-bold text-center mb-4">Penyerahan Calon Pengantin Pria (Jubir CPP)</h1>
</br></br>
        <p class="text-lg">Bismillahirrohmanirrohim,</p>
        <p class="text-lg">Assalamu’alaikum wr.wb</p>
        <p class="indent text-lg text-justify">Alhamdulillah alhamdulillahi robbil ‘aalamiin, was-sholaatu wassalaamu ‘alaa asyrofil
        anbiyaa-i wal mursaliin, sayyidina muhammadin, wa’ala alihi wa’ashabihi aj’ma’iin, Amma ba’du.</p>
        <p class="indent text-lg text-justify">Shalawat serta salam marilah kita haturkan kepada junjungan Nabi Besar Muhammad
        SAW. , beserta keluarganya, dan para sahabat, dan kepada kita semua yang hingga saat ini
        masih istiqomah dalam mengamalkan risalahnya. Mudah-mudahan kita semua mendapatkan
        syafa’atnya di yaumil akhir kelak. Aamiin....</p>
        <p class="indent text-lg text-justify">Yang kami hormati para ‘Alim ulama yang dimuliakan Allah, para sesepuh, tokoh agama,
        tokoh masyarakat, dan para tamu undangan yang kami hormati wabil khusus <strong>keluarga besar
        bapak <?= $client->f_bride_fathername; ?></strong> dan <strong>ibu <?= $client->f_bride_mothername; ?></strong>.</p>
        <p class="indent text-lg text-justify">Ijinkan kami berdiri dihadapan bapak/ibu serta hadirin untuk memberikan sedikit sambutan
        mewakili keluarga dari <strong><?= $client->m_bride_fname; ?></strong>.</p>
        <p class="indent text-lg text-justify">Pertama - tama kami sekeluarga menyampaikan salam hormat kepada keluarga besar
        bapak & Ibu dengan iringan doa semoga selalu dalam lindungan dan ridho Allah SWT.</p>
        <p class="indent text-lg text-justify">Kedua, saya selaku wakil dari keluarga bermaksud mengantarkan dan menyerahkan
        <strong><?= $client->m_bride_fname; ?></strong>. Sesuai rencana yang disepakati bersama yang akan dinikahkan
        dengan <strong><?= $client->f_bride_fname; ?></strong>.</p>
        <p class="indent text-lg text-justify">Menyertai keperluan proses ini kami juga menyiapkan mas kawin sesuai permintaan,
        sebagai salah satu syarat utama sebuah pernikahan.</p>
        <p class="indent text-lg text-justify">Jika berkenan menerima, kami juga membawa sedikit souvenir sebagai tanda cinta dan
        pengikat tali kekeluargaan.</p>
        <p class="indent text-lg text-justify">Namun yang utama dari barang bawaan ini adalah niat ikhlas kami, jadi kami mohon tidak
        menilai mengenai harganya.</p>
        <p class="indent text-lg text-justify">Selanjutnya, apabila sudah tiba waktunya yang dijadwalkan, kami memohon kiranya yang
        mewakili <strong>keluarga bapak <?= $client->f_bride_fathername; ?></strong> berkenan untuk segera menikahkan mereka
        berdua.</p>
        <p class="indent text-lg text-justify">Kami sekeluarga besar senantiasa mengiringi dengan doa dan restu, semoga proses ini
        dapat berjalan lancar tanpa ada halangan suatu apapun serta dalam berkah dan ridho Allah SWT
        aamiin ya Robbal aalamiin.</p>
        <br class="print-only"><br class="print-only"><br class="print-only"><br class="print-only">
        <p class="indent text-lg text-justify">Saya selaku wakil keluarga yang bertindak dalam penyerahan calon pengantin pria,
        apabila ada tutur kata ataupun tingkah laku saya dan juga segenap rombongan yang kurang
        berkenan, saya memohon maaf yang sebesar besarnya dan berharap semoga penyerahan ini
        kiranya dapat diterima dengan penuh keikhlasan, demikian dan terima kasih.</p>
        <p class="text-lg">Billahi taufik wal hidayah wassalamu-alaikum Wr.Wb.</p>

        <div class="mt-6 flex justify-between no-print">
            <a href="<?= base_url('naskah/jubir_cpp/pdf/' . $client->id_session); ?>" 
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
