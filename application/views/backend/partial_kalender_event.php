<!-- Widget "Kalender Event" -- kalender bulanan berdasarkan project.event_date.
     Tanggal yang ada event ditandai warna + jumlah event, klik tanggalnya
     buat lihat daftar project di hari itu. Data diambil via AJAX ke
     Aspanel::get_calendar_events() supaya ganti bulan tidak reload halaman. -->
<div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
    <h4 class="text-xl font-bold">Kalender Event</h4>
    <div class="flex items-center justify-between sm:justify-end gap-2">
      <button type="button" id="kalenderPrev" class="px-3 py-1.5 border rounded-md text-sm flex-shrink-0 hover:bg-whiter dark:hover:bg-meta-4">&larr;</button>
      <span id="kalenderLabel" class="flex-1 sm:flex-none sm:w-36 text-sm font-medium text-center"></span>
      <button type="button" id="kalenderNext" class="px-3 py-1.5 border rounded-md text-sm flex-shrink-0 hover:bg-whiter dark:hover:bg-meta-4">&rarr;</button>
    </div>
  </div>

  <div class="grid grid-cols-7 gap-1 mb-1 text-center text-xs font-semibold text-body dark:text-bodydark">
    <div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
  </div>
  <div id="kalenderGrid" class="grid grid-cols-7 gap-1"></div>
</div>

<!-- Modal daftar event pada satu tanggal -->
<div id="kalenderModal" class="fixed inset-0 z-999999 hidden items-center justify-center bg-black bg-opacity-50 p-4" onclick="if (event.target === this) { tutupModalKalender(); }">
  <div class="w-full max-w-md max-h-[80vh] overflow-y-auto rounded-md bg-white dark:bg-boxdark p-5">
    <div class="flex items-center justify-between mb-3">
      <h3 id="kalenderModalTitle" class="text-lg font-bold"></h3>
      <button type="button" onclick="tutupModalKalender()" class="text-2xl leading-none text-body dark:text-bodydark hover:text-black dark:hover:text-white">&times;</button>
    </div>
    <div id="kalenderModalBody" class="space-y-2 text-sm"></div>
  </div>
</div>

<script>
(function () {
    var bulanTampil = new Date();
    bulanTampil.setDate(1);

    var namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    var eventsPerTanggal = {};

    function dua_digit(n) {
        return n < 10 ? '0' + n : '' + n;
    }

    function kunciBulan(d) {
        return d.getFullYear() + '-' + dua_digit(d.getMonth() + 1);
    }

    function muatKalender() {
        document.querySelector('#kalenderLabel').textContent = namaBulan[bulanTampil.getMonth()] + ' ' + bulanTampil.getFullYear();

        fetch('<?= base_url('Aspanel/get_calendar_events') ?>?month=' + kunciBulan(bulanTampil))
            .then(function (res) { return res.json(); })
            .then(function (data) {
                eventsPerTanggal = {};
                (data.events || []).forEach(function (e) {
                    eventsPerTanggal[e.tanggal] = e;
                });
                renderGridKalender();
            })
            .catch(function (err) {
                console.error('Gagal memuat kalender event:', err);
            });
    }

    function renderGridKalender() {
        var grid = document.querySelector('#kalenderGrid');
        grid.innerHTML = '';

        var tahun = bulanTampil.getFullYear();
        var bulan = bulanTampil.getMonth();
        var mulaiDariHari = new Date(tahun, bulan, 1).getDay(); // 0 = Minggu
        var jumlahHari = new Date(tahun, bulan + 1, 0).getDate();

        var sekarang = new Date();
        var tanggalHariIni = sekarang.getFullYear() + '-' + dua_digit(sekarang.getMonth() + 1) + '-' + dua_digit(sekarang.getDate());

        for (var kosong = 0; kosong < mulaiDariHari; kosong++) {
            grid.appendChild(document.createElement('div'));
        }

        for (var tgl = 1; tgl <= jumlahHari; tgl++) {
            var tanggalStr = tahun + '-' + dua_digit(bulan + 1) + '-' + dua_digit(tgl);
            var info = eventsPerTanggal[tanggalStr];
            var isHariIni = tanggalStr === tanggalHariIni;

            var sel = document.createElement('div');
            sel.className = 'rounded-md border p-1.5 h-16 sm:h-20 text-left ' +
                (info ? 'border-primary bg-primary bg-opacity-10 cursor-pointer hover:bg-opacity-20' : 'border-stroke dark:border-strokedark') +
                (isHariIni ? ' ring-2 ring-primary' : '');

            var labelTgl = document.createElement('div');
            labelTgl.className = 'text-xs font-medium text-black dark:text-white';
            labelTgl.textContent = tgl;
            sel.appendChild(labelTgl);

            if (info) {
                var badge = document.createElement('div');
                badge.className = 'mt-1 text-[11px] font-semibold text-primary text-center leading-tight';
                badge.textContent = info.jumlah + ' event';
                sel.appendChild(badge);

                sel.addEventListener('click', (function (tanggalStr, info) {
                    return function () { bukaModalKalender(tanggalStr, info); };
                })(tanggalStr, info));
            }

            grid.appendChild(sel);
        }
    }

    window.bukaModalKalender = function (tanggalStr, info) {
        var d = new Date(tanggalStr + 'T00:00:00');
        var judul = d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        document.querySelector('#kalenderModalTitle').textContent = judul + ' (' + info.jumlah + ' event)';

        var body = document.querySelector('#kalenderModalBody');
        body.innerHTML = '';

        info.projects.forEach(function (p) {
            var item = document.createElement('a');
            item.href = '<?= base_url('project/lihat/') ?>' + encodeURIComponent(p.id_session);
            item.className = 'block rounded-md border border-stroke dark:border-strokedark p-3 hover:bg-whiter dark:hover:bg-meta-4';

            var nama = document.createElement('p');
            nama.className = 'font-medium text-black dark:text-white';
            nama.textContent = p.client_name || p.project_name || '-';
            item.appendChild(nama);

            if (p.location) {
                var lokasi = document.createElement('p');
                lokasi.className = 'text-xs text-body dark:text-bodydark mt-1';
                lokasi.textContent = p.location;
                item.appendChild(lokasi);
            }

            body.appendChild(item);
        });

        var modal = document.querySelector('#kalenderModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.tutupModalKalender = function () {
        var modal = document.querySelector('#kalenderModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    document.querySelector('#kalenderPrev').addEventListener('click', function () {
        bulanTampil.setMonth(bulanTampil.getMonth() - 1);
        muatKalender();
    });
    document.querySelector('#kalenderNext').addEventListener('click', function () {
        bulanTampil.setMonth(bulanTampil.getMonth() + 1);
        muatKalender();
    });

    document.addEventListener('DOMContentLoaded', muatKalender);
})();
</script>
