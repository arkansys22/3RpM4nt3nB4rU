# PROGRESS.md — Dashboard & Sales Achievement

> Catatan kerja harian di luar cakupan `SECURITY_AUDIT.md` (yang sudah
> selesai & merged ke `main` per 2026-07-22). Dokumen ini nge-track
> perbaikan bug dan fitur baru di dashboard home & modul sales.

---

## 2026-07-22

### Perbaikan Perhitungan Revenue & Target (Home Dashboard)

- **Double-counting revenue** — `Aspanel::get_revenue_data()` men-JOIN
  `project` ke `payment` tanpa dedup, jadi satu project dengan beberapa
  pembayaran Paid (DP + pelunasan) ke-hitung berkali-kali. Diganti pakai
  helper `getProjectAchievement()` yang hitung tiap project sekali.
- **Atribusi tahun salah** — project dengan cicilan menyeberang tahun
  (DP 2025, pelunasan 2026) sempat ke-hitung penuh di DUA tahun sekaligus.
  Sekarang diatribusikan ke tahun pembayaran Paid pertamanya saja.
- **Target/Pencapaian bulan ini salah untuk role developer/admin** —
  ikut aturan "global" yang harusnya cuma berlaku untuk metrik
  tahun-ini/tahun-lalu/keseluruhan. Target & pencapaian personal sekarang
  selalu berdasarkan user yang login, semua role.
- **`cookie_secure` bikin login lokal gagal** — di-derive dari
  `is_https()` (bukan hardcode `TRUE`), otomatis benar di HTTP lokal
  maupun HTTPS produksi.

### Perbaikan Tabel `sales-setting-target` & `finance-operational`

- Search/sort tidak berfungsi — root cause: jQuery + plugin DataTables
  tidak pernah ter-load di halaman itu. Ditambahkan via CDN.
- Muncul 2 kotak search — ternyata ada library LAIN (`simple-datatables`,
  bawaan template `bundle.js`) yang auto-attach ke elemen `id="dataTableTwo"`
  dan bentrok sama jQuery DataTables. Di-fix dengan ganti ID tabel
  (`salesTargetTable` / `operationalTable`) supaya `bundle.js` tidak lagi
  auto-attach — cuma 1 library yang jalan sekarang.
- Tampilan mobile tidak presisi — ditambahkan ekstensi resmi **DataTables
  Responsive** supaya kolom auto-collapse di layar kecil.

### Fitur Baru: Top 5 Sales Ranking

- Slide baru "Top 5 Sales Bulan Ini" di home, antara "Clients" dan
  "Estimasi Revenue Bulan Ini" — team-wide ranking, sama buat semua role
  yang lihat (developer/admin/accounting/staff admin/sales marketing).
- Nama di ranking bisa diklik → `/sales-achievement/<user_id>` — daftar
  pencapaian per bulan untuk user itu, semua periode yang tercatat di DB.
- Klik satu bulan → `/sales-achievement/<user_id>/detail/<periode>` —
  daftar project/invoice (kolom **Kwitansi Kesatu** & **Kwitansi Kedua**)
  yang menyusun pencapaian bulan itu, dengan link ke detail project.
- Link "Lihat Bulan Lainnya" di home → `/sales-ranking(/:periode)` —
  ranking top 5 bisa di-browse ke bulan mana pun (navigasi prev/next +
  month picker), bukan cuma bulan berjalan.

### Fitur Baru: Absensi Kehadiran

- Tombol "Button 2" (placeholder) di home — untuk role developer/admin/staff
  admin/sales marketing (level 1,2,3,4,9) — sekarang jadi tombol **Absensi**,
  link ke `/absensi`.
- Halaman `/absensi`: absen masuk/keluar harian (1x masuk + 1x keluar per
  hari, tombol otomatis hilang/berganti sesuai status), plus riwayat
  kehadiran per bulan dengan navigasi bulan (`/absensi/2026-06`, dst — pola
  sama seperti `sales-ranking`).
- Tabel baru `user_absensi` (`user_id_session`, `tanggal`, `jam_masuk`,
  `jam_keluar`, `keterangan`) dengan unique key `(user_id_session, tanggal)`
  supaya tidak bisa dobel absen di hari yang sama.
- Sudah dites end-to-end di browser lokal (login, absen masuk, absen keluar,
  navigasi bulan) — sudah dibersihkan setelahnya, tabel `user_absensi` di DB
  lokal kosong lagi.
- **Belum ada di database produksi.** File migrasi ada di
  `db/user_absensi.sql` (tidak ter-commit ke git — folder `db/*.sql`
  memang di-gitignore, sama seperti dump lain di folder itu). Sebelum fitur
  ini dipakai di server, jalankan SQL tersebut manual di DB produksi.

#### Update: Wajib Foto Live + Titik Koordinat

- Absen masuk & keluar sekarang **wajib** menyertakan foto yang diambil
  langsung dari kamera (live, via `getUserMedia` — bukan upload dari galeri)
  dan titik koordinat lokasi (`navigator.geolocation`) pada saat foto
  diambil. Koordinat di-overlay/di-"bakar" langsung ke pixel foto (via
  `<canvas>`) sebelum dikirim, jadi menempel permanen di gambarnya.
  Backend menolak request yang tidak menyertakan foto atau koordinat
  (lihat `Aspanel::validasi_foto_lokasi_absensi()`).
- Kolom baru di `user_absensi`: `foto_masuk`, `lat_masuk`, `lng_masuk`,
  `foto_keluar`, `lat_keluar`, `lng_keluar`. Foto disimpan di
  `./uploads/absensi/` (nama file di-generate sendiri di server, bukan dari
  input user — supaya tidak ada celah path traversal).
  Riwayat kehadiran menampilkan thumbnail foto + link "Lihat Lokasi" (Google
  Maps) untuk masuk & keluar.
- **Kamera & GPS butuh HTTPS** (atau `localhost` untuk dev) — sudah aman
  karena produksi sudah HTTPS dan browser modern hanya izinkan
  `getUserMedia`/`geolocation` di secure context.
- Sudah dites end-to-end via HTTP request langsung (login → submit foto
  dummy + koordinat dummy → verifikasi baris DB & file foto tersimpan →
  verifikasi tampilan thumbnail/link Maps → cleanup) karena environment
  testing ini tidak punya kamera/GPS fisik. **Alur kamera live yang
  sebenarnya (izin kamera, alur ambil-foto-di-perangkat) belum pernah
  dites di device/browser sungguhan** — perlu dicoba manual di HP/laptop
  dengan kamera sebelum dianggap siap pakai.

#### Bug Fix: Tombol "Ambil Foto" Muncul Sebelum Kamera Siap

- Dilaporkan lewat testing HP asli: foto berhasil diambil tapi teks lokasi
  tidak muncul di fotonya. Root cause: tombol "Ambil Foto" hanya menunggu
  GPS siap, tidak menunggu kamera — di HP, GPS (apalagi kalau ada cache
  lokasi) sering lebih cepat siap daripada stream kamera. Kalau foto
  di-capture sebelum `video.videoWidth/videoHeight` punya nilai (masih 0),
  hasil gambarnya kosong dan overlay teks otomatis tidak kelihatan.
- Fix: tombol "Ambil Foto" sekarang baru muncul kalau GPS **dan** kamera
  (event `loadedmetadata`) sama-sama sudah siap, plus ada pengaman kalau
  tetap ke-trigger saat dimensi video masih 0.

#### Update: Nama Kecamatan/Kabupaten di Foto (bukan cuma koordinat)

- User minta info lokasinya berupa nama kecamatan/kabupaten-kota, bukan
  cuma angka lat/lng mentah. Ditambahkan reverse-geocode di browser lewat
  **OpenStreetMap Nominatim** (gratis, tanpa API key) begitu koordinat GPS
  didapat — hasilnya (mis. "Gambir, Jakarta Pusat") ikut di-overlay ke foto
  bersama waktu & koordinat, dan juga disimpan ke kolom baru
  `alamat_masuk`/`alamat_keluar` untuk ditampilkan di tabel riwayat.
- Sifatnya **best-effort dengan timeout 6 detik**: kalau Nominatim
  lambat/gagal, absen tetap bisa lanjut pakai koordinat mentah saja (nama
  daerah opsional, tidak memblokir absen — beda dengan foto/koordinat yang
  wajib ada).
- Sudah dites: reverse-geocode dicoba langsung ke Nominatim dengan
  koordinat Monas Jakarta → hasil "Gambir, Jakarta Pusat" (pakai
  `accept-language=id` supaya nama daerah dalam Bahasa Indonesia, bukan
  Inggris). Submit lengkap (foto + koordinat + alamat) juga sudah dites
  end-to-end, kolom `alamat_masuk` tersimpan & tampil di halaman.

### Fitur Baru: Pengaturan Jam Masuk + Deteksi Terlambat Otomatis

- Halaman baru `/absensi-pengaturan` (developer & administrator saja, level
  1 & 2) untuk atur **jam masuk ketentuan** (default 08:00) — singleton
  setting di tabel baru `pengaturan_absensi` (selalu 1 baris, id=1).
  Ada link "Pengaturan" di halaman Absensi (cuma tampil untuk level 1/2)
  dan info "Jam masuk ketentuan: HH:MM" ditampilkan ke semua user.
- Setiap absen masuk sekarang otomatis dibandingkan ke jam ketentuan itu.
  Kalau lewat, kolom `keterangan` terisi otomatis **"Terlambat X menit"**
  (`Aspanel::hitung_keterangan_absensi()`), ditampilkan merah/bold di
  status hari ini maupun di tabel riwayat. Kalau tidak terlambat,
  keterangan tetap kosong (bukan user-editable — cuma auto-computed).
- Sudah dites end-to-end: ganti jam ketentuan ke waktu lampau lewat
  `/absensi-pengaturan`, lalu absen masuk beneran → keterangan otomatis
  terisi "Terlambat 82 menit" sesuai selisih yang benar. Juga dites akses
  ditolak untuk role non-admin (staff sales level 9 tidak bisa buka
  `/absensi-pengaturan` dan tidak melihat link "Pengaturan").
- **Belum ada di database produksi** — sama seperti `user_absensi`, tabel
  `pengaturan_absensi` migrasinya di `db/pengaturan_absensi.sql` (gitignore,
  belum dijalankan di server).

### Fitur Baru: Rekap Absensi Semua User (Admin)

- Tombol "Rekap Semua User" di halaman Absensi (developer & administrator
  saja, level 1 & 2 — sama seperti "Pengaturan") → `/absensi-rekap`.
  Nampilin tabel semua user yang punya minimal 1 catatan absen di bulan
  yang dipilih (nama, total hadir, total terlambat), dengan navigasi
  bulan sebelum/sesudah (pola sama seperti `sales-ranking`).
- Klik "Lihat Detail" di satu baris → `/absensi-rekap/<periode>/detail/<id>`
  — log harian lengkap user itu untuk bulan tsb (jam masuk/keluar, foto,
  lokasi, keterangan), reuse tampilan tabel yang sama dengan riwayat
  pribadi di `/absensi`.
- **Bug yang ketemu & langsung diperbaiki saat testing**: parameter
  `absensi_rekap_detail($user_id_session, $periode)` urutannya salah —
  route `/absensi-rekap/(:any)/detail/(:any)` mengirim `$1`=periode lebih
  dulu baru `$2`=id_session, tapi method-nya menerima id duluan. Efeknya
  klik "Lihat Detail" selalu nyasar balik ke halaman rekap kosong (user
  tidak ketemu). Fix: urutan parameter di-swap jadi
  `absensi_rekap_detail($periode, $user_id_session)` sesuai urutan route.
- Sudah dites end-to-end (2 user beda bulan berjalan, angka rekap match
  query manual, drill-down per user benar, navigasi bulan benar, akses
  ditolak untuk role non-admin) — data test sudah dibersihkan.

### Update: Simpan Jam Masuk Ketentuan di Tiap Catatan Absen

- Kolom baru `jam_masuk_ketentuan` di `user_absensi` — setiap absen masuk
  sekarang merekam **jam ketentuan yang berlaku saat itu** (bukan cuma
  hasil hitungan "Terlambat X menit"-nya saja). Tujuannya: kalau
  pengaturan jam masuk diubah admin di kemudian hari, riwayat lama tetap
  bisa diaudit terhadap ketentuan yang benar-benar berlaku waktu itu, tidak
  ikut berubah retroaktif.
- Ditampilkan sebagai subtext "ketentuan HH:MM" di bawah Jam Masuk — baik
  di status hari ini, tabel riwayat pribadi (`/absensi`), maupun tabel
  drill-down admin (`/absensi-rekap/.../detail/...`).
- Sudah dites end-to-end: set ketentuan ke 08:00, absen masuk beneran →
  kolom `jam_masuk_ketentuan` tersimpan `08:00:00` dan tampil di ketiga
  tempat di atas. Data test sudah dibersihkan.

### Fitur Baru: Catat Tidak Masuk (Sakit/Izin)

- Kolom baru `status` di `user_absensi` (`Hadir` / `Sakit` / `Izin`,
  default `Hadir`). Di halaman `/absensi`, kalau belum ada catatan hari
  itu, sekarang ada tombol **"Tidak Masuk (Sakit/Izin)"** di samping
  "Absen Masuk" — klik membuka form inline (dropdown status + textarea
  keterangan, **keterangan wajib diisi**) yang POST ke `/absensi/izin`.
  Beda dengan absen masuk/keluar, tidak butuh foto/lokasi karena user
  memang tidak di tempat.
- Satu catatan per hari tetap berlaku (constraint unique yang sudah ada):
  kalau sudah Sakit/Izin hari itu, tidak bisa absen masuk/keluar lagi
  (dan sebaliknya) — ditolak dengan pesan "Sudah ada catatan absensi
  untuk hari ini."
- Tampilan: status hari ini menunjukkan "Sakit"/"Izin" + keterangan (tanpa
  tombol aksi lain), tabel riwayat pribadi & drill-down admin dapat kolom
  **Status** baru, dan rekap admin (`/absensi-rekap`) dapat kolom **Total
  Sakit** & **Total Izin** terpisah dari Total Hadir/Terlambat (yang cuma
  dihitung dari hari berstatus Hadir, supaya angkanya tidak bias oleh hari
  Sakit/Izin).
- Sudah dites end-to-end: validasi kosong/status invalid ditolak, submit
  valid tersimpan & tampil di status hari ini, absen masuk/keluar setelah
  Sakit tercatat berhasil diblokir (tidak ada foto orphan tersimpan), dan
  rekap admin menghitung total_hadir/sakit/izin dengan benar (diverifikasi
  silang ke query manual). Data test sudah dibersihkan.

### Fitur Baru: Rekap Gaji (sub menu Fin & Acc)

- Menu baru **"Rekap Gaji"** di dropdown sidebar Fin & Acc (jadi otomatis
  cuma kelihatan untuk developer/administrator/staff accounting — level
  1/2/3, sama seperti item Fin & Acc lainnya) → `/rekap-gaji`.
- **Kategori Gaji** (tabel baru `kategori_gaji`: nama + nominal) bisa
  dibuat/edit/hapus bebas lewat `/rekap-gaji/kategori` — "ketentuan
  kategori bisa di-input mandiri" sesuai permintaan. Controller baru
  `Crud_kategori_gaji.php`.
- Halaman Rekap Gaji menampilkan semua user staff internal (developer,
  administrator, staff accounting, staff admin, staff/crew, staff sales —
  level 1/2/3/4/7/9, yang belum di-soft-delete) dengan role-nya (join ke
  tabel `user_level` yang sudah ada), dan dropdown per baris untuk
  assign/ganti kategori gaji-nya (auto-submit saat dipilih). Nominal gaji
  & total keseluruhan mengikuti kategori yang di-assign.
- Kolom baru `user.kategori_gaji_id` (nullable) — user yang belum
  di-assign kategori tampil "- Belum ada kategori -" dan nominalnya "-".
  Hapus kategori otomatis melepas assignment user yang masih pakai
  kategori itu (bukan dibiarkan jadi referensi yatim).
- Sudah dites end-to-end (browser + HTTP): tambah/edit/hapus kategori,
  assign ke user lewat dropdown (nominal & total ikut update), delete
  kategori melepas assignment user dengan benar, dan akses ditolak untuk
  role di luar Fin & Acc (staff sales level 9 — link-nya juga otomatis
  tidak kelihatan di sidebar). Data test sudah dibersihkan.
- **Belum ada di database produksi** — migrasi di `db/kategori_gaji.sql`
  (tabel baru `kategori_gaji` + `ALTER TABLE user ADD COLUMN
  kategori_gaji_id`), belum dijalankan di server.

#### Update: Satuan Gaji (Harian/Bulanan/Project)

- Tiap kategori sekarang wajib pilih **satuan gaji**: `Harian`, `Bulanan`,
  atau `Project` (kolom `satuan_gaji` ENUM di `kategori_gaji`). Nominal
  ditampilkan dengan suffix satuannya, mis. "Rp 250.000 / harian".
- Total di halaman Rekap Gaji dipecah jadi **3 total terpisah** (Total
  Gaji Bulanan, Harian, Project) — sengaja tidak dijumlah gabung jadi satu
  angka karena satuannya beda-beda (gaji bulanan + rate harian + ongkos
  project tidak bisa dijumlah langsung jadi angka yang berarti).
- Sudah dites: 3 kategori dibuat (satu per satuan), di-assign ke user
  berbeda, total per satuan di halaman rekap cocok (mis. Bulanan
  Rp 3.200.000, Harian Rp 250.000, terpisah — tidak tercampur). Data test
  sudah dibersihkan.

#### Update: Satuan Persentase + Rename "Rekap Gaji" → "Rekap Salary"

- Tambah satuan ke-4: **Persentase** (mis. komisi sales 2.5%). Nominal
  untuk satuan ini boleh desimal (input step diubah dari kelipatan 1000
  jadi bebas) dan divalidasi tidak boleh lebih dari 100. Ditampilkan
  sebagai "2,5%" (bukan "Rp"), lewat helper baru
  `format_nominal_salary()` di `customs_helper.php` (dipakai di halaman
  Rekap Salary & Kategori Salary supaya format konsisten).
- Total per satuan di Rekap Salary sekarang ada 4: Bulanan, Harian,
  Project, **Persentase** (dijumlah terpisah, bukan digabung ke Rupiah —
  jumlah persentase & jumlah Rupiah tidak sepadan).
- Semua teks tampilan yang sebelumnya "Gaji" diganti jadi "Salary": judul
  halaman ("Rekap Salary", "Kategori Salary"), link sidebar, label form
  ("Satuan Salary", "Nominal Salary"), header tabel, dan pesan
  flashdata. **Nama tabel/kolom/route DB tidak diubah** (`kategori_gaji`,
  `nominal_gaji`, `satuan_gaji`, URL `/rekap-gaji`) — itu detail
  implementasi, bukan "judul" yang diminta diganti, dan mengubahnya butuh
  migrasi rename yang tidak diminta.
- Sudah dites: validasi >100% ditolak, kategori persentase 2.5% dibuat &
  ditampilkan sebagai "2,5%", total Persentase di rekap menjumlah benar
  terpisah dari Rupiah, dan judul/label di semua halaman + sidebar sudah
  "Salary" bukan "Gaji" lagi. Data test sudah dibersihkan.

#### Update: Satu User Bisa Pilih Lebih dari Satu Kategori Salary

- Sebelumnya tiap user cuma bisa punya 1 kategori (kolom tunggal
  `user.kategori_gaji_id`). Sekarang **many-to-many** lewat tabel
  penghubung baru `user_kategori_gaji` — satu user bisa dapat gaji pokok
  bulanan + komisi persentase + tunjangan harian sekaligus, dst. Kolom
  `user.kategori_gaji_id` dihapus (belum pernah dipakai di produksi,
  jadi aman diganti tanpa migrasi tambahan).
- Di halaman Rekap Salary, kolom "Kategori Salary" sekarang pakai
  `<select multiple>` (Ctrl/Cmd+klik untuk pilih beberapa) + tombol
  Simpan sendiri (tidak auto-submit seperti dropdown tunggal sebelumnya,
  karena multi-select butuh pilihan final dulu baru di-submit). Kolom
  "Nominal Salary" menampilkan tiap kategori yang di-assign sebagai baris
  terpisah (mis. "Gaji Pokok: Rp 3.000.000 / bulanan" +
  "Komisi Sales: 2,5%").
- Simpan pakai strategi hapus-lalu-insert-ulang semua assignment user itu
  tiap kali submit (`Crud_kategori_gaji::assign()`) — lebih sederhana &
  aman daripada diff manual, dan otomatis mendukung deselect (pilih
  kosong = lepas semua kategori user itu).
- Total per satuan di halaman rekap tetap dihitung dari SEMUA baris
  assignment yang aktif (bukan per-user), jadi otomatis benar walau satu
  user punya beberapa kategori sekaligus.
- Sudah dites end-to-end: assign 3 kategori sekaligus ke satu user (baris
  di `user_kategori_gaji` sesuai), lepas satu kategori (submit ulang
  dengan 2 pilihan → baris ketiga hilang dari DB, total per satuan ikut
  turun), dan hapus kategori yang sedang di-assign ke user → assignment-nya
  ikut terhapus otomatis (tidak ada baris yatim). Data test sudah
  dibersihkan.

#### Update: Tampilan Mobile Rekap Salary Dirapikan

- User melaporkan (dengan screenshot) tampilan `/rekap-gaji` berantakan di
  layar HP — header/tombol numpuk, dan tabel lebar jadi harus di-scroll
  horizontal yang canggung untuk kolom kategori (multi-select) + nominal.
- Header di-rombak jadi stack vertikal di mobile (`flex-col` → `sm:flex-row`),
  tombol "Kelola Kategori" teksnya dipendekin biar muat.
  Total per satuan diubah dari 4 baris teks polos jadi grid kartu kecil
  2 kolom (rapi di HP, 4 kolom di layar lebih besar).
- Tabel (yang berisi multi-select + tombol Simpan per baris — susah dibaca
  kalau di-scroll horizontal sempit) diganti jadi **kartu per user** khusus
  di layar mobile (`sm:hidden`), sementara tabel biasa tetap dipakai untuk
  tablet/desktop (`hidden sm:block`) — pola "responsive table jadi card"
  yang umum dipakai, bukan sekadar bikin tabelnya bisa di-scroll.
- Sudah dites di viewport 375px (HP) dan 1280px (desktop): kartu mobile
  menampilkan nama+role, daftar kategori yang sudah di-assign, multi-select
  full-width, dan tombol Simpan full-width — tabel desktop tetap seperti
  sebelumnya. Data uji visual dibersihkan tanpa menyentuh kategori/assignment
  yang sudah dibuat user sendiri di database (dicek dulu sebelum hapus).

#### Update: Nama Harus Dipilih Dulu Baru Data User Muncul

- Diganti dari "tampilkan semua user sekaligus" (list panjang, salah satu
  penyebab tampilan mobile berantakan sebelumnya) jadi **dropdown "Pilih
  Nama" dulu** — data kategori/assign satu user baru muncul setelah
  namanya dipilih. Otomatis lebih rapi di mobile juga karena tidak ada
  lagi daftar panjang kartu/tabel semua user yang harus di-scroll.
- Routing: `/rekap-gaji` (belum ada nama dipilih → cuma dropdown + pesan
  "pilih nama dulu") dan `/rekap-gaji/<id_session>` (data 1 user itu
  muncul, dropdown otomatis ke-set ke nama itu). Route baru
  `rekap-gaji/(:any)` ditaruh SETELAH route `rekap-gaji/kategori*` dan
  `rekap-gaji/assign/*` yang lebih spesifik (kalau kebalik, `:any` yang
  greedy bakal nyaplok "kategori"/"assign" duluan).
- Total per satuan (Bulanan/Harian/Project/Persentase) di atas tetap
  ringkasan SELURUH user (bukan cuma yang lagi dipilih) — supaya tetap ada
  gambaran umum meski sedang fokus ke satu orang.
- Setelah klik Simpan di form assign, redirect balik ke halaman user yang
  sama (`/rekap-gaji/<id_session>`), bukan ke halaman dropdown kosong —
  jadi tidak perlu pilih ulang nama tiap habis simpan.
- Sudah dites: halaman dasar cuma nampilin dropdown (tidak ada data user
  manapun bocor ke tampilan), pilih 1 nama → cuma data orang itu yang
  muncul, submit assign tetap di halaman orang itu, dan route
  `/rekap-gaji/kategori` & `/rekap-gaji/kategori/edit/<id>` dicek masih
  jalan normal (tidak ke-shadow oleh route baru). Data test dibersihkan.

#### Update: Hapus 4 Kartu Total, Rename "Rekap Salary" → "Setting Salary"

- 4 kartu ringkasan total per satuan (Salary Bulanan/Harian/Project/
  Persentase) di atas dropdown **dihapus** — bukan cuma disembunyikan,
  perhitungannya di controller (`rekap()`) juga dibuang karena sudah tidak
  dipakai di mana pun lagi.
- Judul halaman diganti dari "Rekap Salary" jadi **"Setting Salary"**
  (`<title>`, `<h1>`), dan link di sub menu Fin & Acc sidebar ikut diganti
  jadi "Setting Salary". URL/route tetap `/rekap-gaji` (tidak diminta
  ganti URL, cuma teks yang tampil).
- Sudah dites: halaman langsung ke pesan "pilih nama dulu" tanpa kartu
  total sama sekali, judul tab browser & heading jadi "Setting Salary",
  dan sidebar Fin & Acc ikut berubah.

### Fitur Baru: Detail Gaji Peruser per Periode Bulan (Perhitungan Otomatis)

- Halaman Setting Salary sekarang bisa browse per **periode bulan**
  (navigasi prev/next + month picker, sama pola dengan `absensi-rekap` /
  `sales-ranking`) — URL jadi `/rekap-gaji/<id_session>/<periode>`. Route
  baru `rekap-gaji/(:any)/(:any)` ditaruh SEBELUM route 1-segmen
  (`rekap-gaji/(:any)`) karena `:any` di CI3 itu greedy (`.+`, bisa nyaplok
  slash) — kalau kebalik, url 2 segmen bakal ke-capture semua jadi 1
  parameter.
- Setelah nama **dan** periode dipilih, tiap kategori salary yang
  di-assign ke user itu **dihitung jadi nominal aktual bulan itu**
  (`Crud_kategori_gaji::hitung_detail_gaji()`), bukan cuma nominal
  kategori mentah:
  - **Bulanan** — flat, nominal kategori apa adanya.
  - **Harian** — nominal x jumlah hari berstatus `Hadir` di `user_absensi`
    bulan itu.
  - **Project** — nominal x jumlah project berbeda yang `event_date`-nya
    jatuh di bulan itu, dari jadwal crew (`crew_projects` JOIN `project`
    JOIN `user.crews_idsession` — pola sama persis dengan yang sudah
    dipakai di dashboard staff untuk daftar event mereka).
  - **Persentase** — persen x total pencapaian sales (closing) user itu di
    bulan itu, pakai definisi "achieved" yang **sama persis** dengan
    `Aspanel::getEstimasiRevenue()` (Pembayaran Kesatu Paid tanggal
    berapa pun + Pembayaran Kedua Paid DI BULAN itu) — supaya angka
    komisi konsisten dengan pencapaian yang sudah ditampilkan di
    dashboard & sales-ranking, bukan aturan baru yang beda sendiri.
  - Tiap baris menampilkan cara hitungnya juga (mis. "3 hari hadir x
    Rp 100.000"), plus baris **Total** — kali ini penjumlahan masuk akal
    karena semua kategori sudah dikonversi jadi Rupiah aktual bulan itu
    (beda dengan kartu total lama yang dihapus, yang menjumlah satuan
    berbeda-beda mentah-mentah).
- Form "Atur Kategori Salary" (assign/multi-select) tetap ada di bawah
  detail, sekarang bawa `periode` sebagai hidden field supaya setelah
  Simpan tetap di bulan yang sama (bukan balik ke bulan berjalan).
- Sudah dites end-to-end dengan kombinasi data asli + data uji: dhawy
  (data crew_projects & sales asli, ditambah 3 baris absensi Hadir buatan
  di Juni 2026) di-assign 4 kategori test (satu per satuan) →
  Bulanan Rp 3.000.000, Harian "3 hari hadir x Rp 100.000" = Rp 300.000,
  Project "5 project x Rp 200.000" = Rp 1.000.000 (dicocokkan manual ke
  query crew_projects), Persentase "5% x Rp 135.630.000 pencapaian" =
  Rp 6.781.500 (dicocokkan manual ke query achieved sales), Total
  Rp 11.081.500 — semua angka tepat. Ganti ke periode Mei 2026 juga
  dites, angkanya ikut berubah benar (0 hari hadir, 3 project, pencapaian
  beda). Data uji (4 kategori TEST + 3 baris absensi) dihapus total tanpa
  menyentuh kategori/assignment asli yang sudah dibuat user sendiri
  (13 kategori & 11 assignment real, dicek masih utuh setelah cleanup).
- **Belum ada di database produksi** — fitur ini tidak butuh migrasi baru
  (semua tabel yang dipakai sudah ada), tapi tetap bergantung pada tabel
  `kategori_gaji` & `user_kategori_gaji` dari `db/kategori_gaji.sql` yang
  juga belum dijalankan di server.

### Fitur Baru: Rekap Gaji Saya (Self-Service, Tombol di Home)

- Tombol baru **"Rekap Gaji"** di halaman home, persis di samping tombol
  "Absensi" (3 view: `v_home.php`, `v_home_admin.php`,
  `v_home_salesmarketing.php` — role level 1/2/3/4/9, akses sama dengan
  Absensi). Link ke `/gaji-saya`.
  Bedanya dengan "Setting Salary" (admin Fin & Acc, bisa pilih SIAPA saja
  & UBAH kategori): halaman ini cuma buat staff **lihat rincian gaji
  dirinya sendiri** — tidak ada dropdown pilih nama (selalu user yang
  login), dan tidak ada form ubah kategori (view-only).
- Ada navigasi bulan sendiri (prev/next + month picker) di
  `/gaji-saya/<periode>`, pola sama seperti halaman-halaman rekap lain.
  Kalau belum ada kategori yang di-assign admin, tampil pesan "Anda belum
  memiliki kategori salary. Hubungi Finance/Admin."
- **Refactor**: logika hitung (`hitung_detail_gaji`, dan private
  `hitung_pencapaian_sales`) yang sebelumnya cuma ada di
  `Crud_kategori_gaji` dipindah ke model baru `Gaji_model.php`, dipakai
  bareng oleh `Crud_kategori_gaji` (Setting Salary) dan `Aspanel`
  (Rekap Gaji Saya) — supaya rumus gajinya dijamin konsisten di kedua
  tempat, tidak ada 2 salinan logic yang bisa beda sendiri-sendiri.
- Sudah dites end-to-end pakai data ASLI dhawy (3 kategori yang sudah
  di-assign sendiri sebelumnya: Sales 2,5%, Admin Harian Rp 80.000, Crew
  WO Project Manajer Rp 550.000) — hasil di halaman Rekap Gaji Saya untuk
  Juni 2026 (Total Rp 6.140.750) **persis sama** dengan hasil di halaman
  Setting Salary admin untuk user & bulan yang sama, membuktikan refactor
  ke `Gaji_model` konsisten. Juga dites: tombol muncul di home, user tanpa
  kategori menampilkan pesan kosong yang benar. Tidak ada data test yang
  perlu dibersihkan (semua verifikasi pakai data baca-saja).

### Bug Fix: Project Hilang dari Pencapaian Bulanan

- Root cause: syarat "achieved" lama mewajibkan Pembayaran Kesatu **dan**
  Kedua sama-sama Paid di bulan yang **sama persis**. Project yang DP dan
  pelunasannya beda bulan (kasus nyata: "Pernikahan Akad Sania Daffa",
  Kesatu 26 Mei, Kedua 5 Jun) jadi hilang total, tidak masuk ke bulan
  manapun — padahal sudah lunas.
- Fix: bulan pencapaian sekarang ditentukan oleh **Pembayaran Kedua**
  (pelunasan); Kesatu cukup Paid tanggal berapa pun. Berlaku konsisten di
  4 tempat: `getEstimasiRevenue`, `get_top_sales_ranking`,
  `get_sales_achievement_per_month`, `get_sales_achievement_projects`.
- Dampaknya lebih luas dari kasus yang dilaporkan — beberapa project
  dengan jarak DP-pelunasan berbulan-bulan (mis. DP Januari, pelunasan
  Juni) sebelumnya hilang total dari pencapaian bulanan manapun, sekarang
  ikut terhitung di bulan pelunasannya.

---

## 2026-08-02 — 2026-08-03

### Perbaikan Halaman Potensial Klien (Lihat)

- **Dropdown "Pilih Dari Klien Potensial" di `/project/create`**: sekarang
  mengecualikan klien yang `id_session`-nya sudah terpasang ke sebuah
  `project` (sudah pernah dipakai bikin project), supaya tidak bisa
  dipilih dobel. Halaman `project/edit` tetap menampilkan klien yang
  sudah terpasang ke project itu sendiri (lewat parameter `keep_id_session`
  di `Crud_m::view_where_potensial_clients_deal()`), jadi tidak hilang
  dari dropdown edit-nya sendiri.
- **Notifikasi wajib isi Setting Diskon sebelum Cetak Penawaran** — cek di
  sisi client (JS, buka otomatis modal Setting Diskon) & server
  (`Crud_potensial_clients::download_proposal()`, redirect + flash error
  kalau `promo` masih kosong).
- **Notifikasi wajib isi Quantity sebelum simpan penawaran produk** — field
  `required min="1"` + validasi JS on-submit + validasi server di
  `update_penawaran()`.
- **Fix dark mode**: teks Sub Total/Promo Diskon/Total tidak ikut berubah
  warna pas toggle dark mode. Root cause: pakai class `!text-black
  dark:!text-white` (kombinasi `!important` + `dark:`) yang tidak ada di
  `style.css` precompiled, jadi harus digenerate live oleh script CDN
  Tailwind — dan versi live itu pakai strategi `prefers-color-scheme` OS,
  bukan toggle manual aplikasi. Fix: pakai class biasa (`text-black
  dark:text-white`, tanpa `!`) yang sudah ada di `style.css` dan memang
  benar mengikuti toggle `.dark` di `<body>`.
- **Rapikan layout mobile/tablet**: tombol Menu & 2 tombol aksi (Tambah
  Penawaran Produk, Setting Diskon) dibikin full-width & bertumpuk di
  layar sempit, sejajar horizontal lagi di layar besar. Dropdown Edit/
  Kembali sempat melebar sampai keluar batas card di desktop (anchor
  `left-0` padahal tombolnya di ujung kanan) — dibetulkan jadi `right-0`.
- **Modal "Tambah Penawaran Produk"**: overlay & kotak modal sekarang bisa
  discroll (`max-h-[85vh] overflow-y-auto`) supaya tidak "mentok"/terpotong
  di layar pendek. Field Detail diganti dari `<input>` jadi `<textarea>`
  yang tingginya auto-menyesuaikan panjang teks (`resizeDetailTextarea()`).

### Fitur Baru: Show/Hide Password di Halaman Login

- Ikon mata ditambahkan di field Password `v_login.php` (`/login`) & 
  `v_login_client.php` (`/client/login`) — toggle `type="password"` ↔
  `type="text"` murni di sisi client, tidak mengubah logic login sama
  sekali.

---

## 2026-08-08

### Debugging: Fitur Baru Tidak Muncul di Server Produksi

Beberapa fitur (potensial klien, notif diskon/qty, dark mode, layout
mobile, login password toggle) sempat dilaporkan "tidak ada" di
`maid.mantenbaru.com` walau sudah di-push dari GitHub Desktop. Ternyata
ada 2 penyebab terpisah yang ketemu berurutan:

1. **Working branch lokal sempat pindah ke `security-hardening`** (branch
   lama, sebelum fitur-fitur sesi ini ada) tanpa disadari — sempat
   menimbulkan kebingungan waktu ngecek file lokal. Dibetulkan balik ke
   `main`.
2. **Root cause sebenarnya**: deploy Git di Hostinger hPanel (bukan
   cPanel — repo `arkansys22/3RpM4nt3nB4rU`, branch `main`, deploy ke
   `public_html` langsung) melaporkan "sukses" di UI-nya, tapi log
   deploy yang sebenarnya menunjukkan `git pull` di server **gagal
   total** sejak commit yang menambahkan 3 file font `dompdf`
   (`DejaVuSans{,-Bold,-Oblique}.ufm.php`) — file itu sudah ada di server
   sebagai untracked file (kemungkinan pernah di-upload manual), jadi
   `git pull` menolak menimpanya (`untracked working tree files would be
   overwritten by merge`) dan membatalkan seluruh proses. Karena 1
   pull yang gagal ini, **semua commit setelahnya ikut tidak pernah
   ter-deploy**, bukan cuma yang menambahkan file font itu.
   **Fix**: hapus 3 file konflik itu via File Manager Hostinger, deploy
   ulang — langsung "menyusul" semua commit yang ketahan sekaligus.
   Dicatat di memory (`hostinger-git-deploy-gotcha.md`) sebagai playbook
   diagnosa untuk kejadian serupa ke depan: selalu cek GitHub dulu →
   cek branch lokal & Hostinger → **buka log deploy, jangan percaya
   cuma status "sukses"**.

---

## 2026-08-19

### Fitur Baru: Buku Tamu & Nama Janur (Clients)

- Halaman baru di `Clients → Lihat → Menu` untuk preview desain cetak
  **Buku Tamu** (3 tampilan: depan/isi/belakang, `clients/buku-tamu/<id>`)
  dan **Nama Janur** (1 lembar, `clients/nama-janur/<id>`), diambil dari
  file template PNG resolusi cetak asli (Buku Tamu 3508×2481px / A4
  landscape 300dpi, Nama Janur 4961×3508px / A3 landscape 300dpi) yang
  **tidak pernah di-resize** — cuma ditampilkan responsif di layar lewat
  `<svg viewBox>` yang scale proporsional.
- Nama, tanggal, dan lokasi acara diambil otomatis dari data `clients`
  (`client_name` dipecah jadi 2 bagian buat format "Nama1 & Nama2",
  `wedding_date` diformat pakai helper `hari()`/`tgl_indo()`, `location`
  langsung).
- User awalnya kirim template yang masih ada teks contoh ("Rizki &
  Lutfia") — di-mask dulu pakai kotak warna senada background sebelum
  ditimpa teks baru; belakangan user upload ulang versi **polos** (tanpa
  teks), jadi kode mask-nya disederhanakan (tidak perlu lagi).
- Tombol **Download PNG** di tiap tampilan — generate ulang gambar
  dasarnya + teks di atas **canvas resolusi asli** (bukan screenshot
  layar), lalu didownload sebagai file PNG. Teks otomatis mengecil
  (`textLength`/`ctx.measureText` + scale) kalau nama/lokasinya panjang,
  supaya tidak pernah meluber keluar kotak desainnya — sudah dites pakai
  nama sangat panjang.
- Font nama pakai Google Font script "Alex Brush" warna gradasi emas
  (dihitung dari sampel warna asli di gambar template), tanggal/lokasi
  pakai font serif tebal warna pink brand (`#ed126b`).
- Beberapa iterasi penyesuaian posisi/ukuran/alignment sesuai feedback
  visual (font diperbesar, "&" dibuat aksen berbeda ukuran & posisi,
  badge lokasi di Nama Janur dibikin lebar dinamis mengikuti panjang
  teks lokasi & di-center ke tengah halaman, dll).

### Fitur Baru: Susunan Acara (Kategori → Kegiatan → Presentasi)

- Menu baru independen (tidak terikat client tertentu) untuk menyusun
  rangkaian acara pernikahan. Alurnya 2 tingkat:
  1. **Kategori** (`/susunan-acara`) — index cuma menampilkan daftar
     kategori acara (mis. Akad Nikah, Resepsi) + jumlah kegiatan per
     kategori. Kelola kategori (tambah/edit/hapus/reorder naik-turun) di
     halaman terpisah `/susunan-acara/kategori`.
  2. **Kegiatan** (`/susunan-acara/lihat/<kategori_id_session>`) — detail
     satu kategori: tombol "+ Tambah Kegiatan", dan daftar kegiatan
     (nama kegiatan, durasi, vendor/PJ, foto ilustrasi) dengan reorder
     naik-turun **di dalam kategori itu saja** (bukan urutan global).
- Tabel baru: `susunan_acara_kategori` & `susunan_acara` (migrasi
  `db/susunan_acara.sql`, **belum dijalankan di produksi**). Kolom
  `nama_kegiatan` dibuat `TEXT` (bukan `VARCHAR`) supaya bisa menampung
  teks panjang/multi-baris — input-nya pakai `<textarea>`, ditampilkan
  pakai `nl2br()`.
- Foto ilustrasi diupload ke `assets/uploads/susunan_acara/`; hapus
  kegiatan atau kategori (cascade) ikut membersihkan file fisiknya.
- **Fitur Presentasi** — tombol "Presentasi" di tiap kategori (index &
  halaman detail) membuka halaman full-screen mode-gelap tanpa
  sidebar/header (`/susunan-acara/presentasi/<kategori_id_session>`):
  slide judul kategori, lalu 1 slide per kegiatan (nama, durasi,
  vendor/PJ, foto besar), navigasi tombol + keyboard (←/→/spasi/Esc),
  dibangun murni client-side pakai Alpine.js (`x-data`, data kegiatan
  dikirim sekali lewat `json_encode`).
  - **Bug sempat ketemu**: halaman presentasi kebuka tapi kosong/rusak.
    Root cause: data `json_encode()` (yang selalu pakai tanda kutip
    ganda) disisipkan langsung ke atribut HTML `x-data="..."` yang JUGA
    dibatasi tanda kutip ganda — tabrakan, HTML-nya rusak dari tanda
    kutip pertama. Fix: bungkus dengan
    `htmlspecialchars(json_encode(...), ENT_QUOTES, 'UTF-8')`. Sudah
    dites ulang pakai data sengaja berisi tanda kutip ganda, apostrof,
    dan teks 2 baris — semua tampil benar setelah fix.

### Perbaikan Kecil: Format Bulan Indonesia

- Helper `getBulan()` (dipakai `tgl_indo()`, dipakai luas di seluruh
  app) — singkatan bulan Agustus-Desember (`Agu`, `Sep`, `Okt`, `Nov`,
  `Des`) diganti jadi nama penuh (`Agustus`, `September`, `Oktober`,
  `November`, `Desember`). Jan-Jul sengaja tidak diubah (tidak diminta).
  Berlaku otomatis di semua halaman yang pakai `tgl_indo()`.

---

## 2026-10-17 — 2026-10-18

> Semua perubahan di bagian ini **belum di-commit** (working tree, 14 file
> diubah + 2 file baru) dan **belum ada migrasi DB baru**. Review lewat
> `git diff` / `git status`. Dites lewat HTML yang dirender server pakai
> akun test sementara di `db_erpmaid` (sudah dihapus); klik tombol/JS
> interaktif belum dites manual di browser — mohon dicoba sekali.

### Vendor: status "Belum Diketahui" (tambah & edit vendor di project)

- `vendor/create`: pilihan status ke-3 **Belum Diketahui** (selain
  Non-Partner / Partner). Kalau dipilih, hanya input **Jenis** yang
  tampil; nama, sosmed, kontak, HP, detail, dan foto disembunyikan &
  dikosongkan. Server juga memaksa kosong (`Crud_vendor::store`), foto
  tidak diproses, log aktivitas "Tambah Vendor Belum Diketahui".
- `vendor/edit`: kalau **nama vendor masih kosong**, muncul 3 pilihan
  status yang sama (default Belum Diketahui) + opsi Jenis `-`.
  `Crud_vendor::update` baca `vendor_status`; Non-Partner / Belum
  Diketahui mengabaikan `partner_id`. Vendor yang sudah bernama tampil
  seperti biasa (tanpa pilihan status).
- `project/lihat`: vendor tanpa nama tampil "Vendor belum diketahui".
- File: `Crud_vendor.php`, `views/vendor/{create,edit}.php`,
  `views/project/lihat.php`.

### Dashboard: kartu To Do List (baru)

- Partial `backend/partial_todo_list.php` (di atas Kalender Event, di
  `v_home.php` & `v_home_admin.php`), level 1/2/3/4/9. Model baru
  `Todo_model.php`. Hanya project **aktif & tanggal acara belum lewat**.
  4 kategori, tiap kategori collapse/expand (`<details>`, default
  tertutup, scroll di dalam max-h 20rem; kategori kosong = pesan hijau):
  1. **Vendor belum diketahui / belum ada** — project tanpa vendor sama
     sekali, atau punya vendor bernama kosong.
  2. **Data pengantin belum lengkap** — 10 isian inti (nama lengkap,
     panggilan, anak ke, ayah, ibu untuk CPW & CPP; "anak ke" 0 = kosong).
     Daftar ada di `Todo_model::$kolom_pengantin`.
  3. **Crew event masih kosong** — tidak ada baris di `crew_projects`.
  4. **Agenda belum terisi** — 8 milestone dari
     `Agenda_model::daftar_kolom()` (tanggal kosong / `0000-00-00`).
     **Honeymoon ikut dihitung** — bisa bikin hampir semua project
     tertandai; keluarkan kalau dirasa noise.
- Catatan teknis: di view, `$this` = loader, jadi model diambil lewat
  `get_instance()` (sempat error `Call to a member function ... on null`).
- Vendor yang namanya sudah kosong *sebelum* fitur ini (data lama) ikut
  terhitung sebagai "belum diketahui".

### Project: notif perubahan proposal penawaran

- `Crud_project::lihat` memanggil `perubahan_dari_proposal()`:
  bandingkan project vs proposal (`potensial_clients` + `penawaran_klien`)
  untuk **nama client, tanggal pernikahan, lokasi, nilai project**. Kalau
  beda → banner kuning di `project/lihat` (daftar "project → proposal")
  + tombol Edit Project (Staff Sales hanya lihat pesan). Form Edit
  Project sudah otomatis menarik data proposal, jadi cukup klik Update.
- Rumus total proposal dipindah ke
  `Potensial_model::hitung_total_penawaran()` (dipakai bersama
  `Crud_project::get_total_penawaran` dan banner) — perilaku lama tidak
  berubah. Perubahan di item proposal lain (paket/qty) hanya terdeteksi
  lewat selisih nilai total.

### Invoice

- `payment/createinv`: "Nama Invoice" terisi otomatis
  `Invoice <pc_name proposal>` (fallback nama client project), bisa
  diubah. Berlaku untuk "Dari Proposal" maupun "Penambahan" (nama bisa
  kembar kalau lebih dari satu invoice per project — **perlu keputusan**
  apakah Penambahan diberi nama beda).
- Detail pembayaran **Custom** (createinv & editinv): input per
  pembayaran berlabel Pertama/Kedua/…/ke-N; tombol "+ Tambah Pembayaran"
  baru muncul setelah nominal pembayaran terakhir diisi; Hapus hanya di
  item terakhir; ganti metode → reset ke satu item.
- Tiap pembayaran = **nominal** (format titik ribuan, prefix Rp, wajib)
  + **teks custom** (opsional). Disimpan di `payment.detail` sebagai
  JSON `[{"nominal":"5000000","teks":"..."}]` lewat
  `Crud_payment::detail_custom_dari_post()` (dipakai `store` & `update`;
  item kosong dibuang). Data lama (array teks) tetap terbaca; di edit
  muncul sebagai teks custom dengan nominal kosong.
- `view_invoice`: tiap item ditulis
  `Pembayaran <urutan> **<teks custom>** sebesar Rp <nominal>` lewat
  helper baru `format_detail_pembayaran_custom()` (`customs_helper.php`,
  hasil HTML ter-escape; teks custom di-bold). Jangan menulis ulang
  "Pembayaran pertama" di teks custom (jadi dobel).
- **Edit Invoice, metode Default** (2026-10-24): muncul input teks **Pembayaran Pertama …
  terakhir** (jumlah 2/4/5 mengikuti sisa hari ke tanggal acara, sama dengan jadwal
  otomatis di invoice). Isi awal = teks hitungan otomatis (dari `payment.total_bill`);
  hanya baris yang **diubah** yang disimpan, di `payment.detail` sebagai
  `{"default":["","teks baru","",""],"jumlah":4}` (`Crud_payment::detail_dari_post`);
  baris tak diubah tetap otomatis di `view_invoice`. Override diabaikan kalau `jumlah`
  tidak sama dengan jumlah baris jadwal saat ini (jadwal bergeser mendekati tanggal acara).
  Logika jadwal dipindah ke helper `jadwal_pembayaran_default()` +
  `override_pembayaran_default()` (`customs_helper.php`), dipakai `view_invoice` & `editinv`.
  Catatan: isi awal memakai `total_bill`, sedangkan invoice memakai grand total dari item
  proposal — biasanya sama; kalau beda, nominal di input hanya perkiraan (tidak tersimpan
  kalau tidak diubah). Tidak ada migrasi database.
- **Pembayaran yang sudah dibayar disembunyikan di form kwitansi** (2026-10-24): di `createkwt` & `editkwt`, pilihan "Pembayaran ke" yang sudah punya kwitansi **Paid** tidak ditampilkan (edit: kwitansi itu sendiri tetap bisa memilih nilainya sendiri). Helper `urutan_pembayaran_dibayar()`. Project dengan >1 invoice: disaring per invoice setelah "Dari Invoice" dipilih (JS, juga menyembunyikan pembayaran yang sudah dibayar di dropdown "Pilih Pembayaran"); project <=1 invoice: langsung saat halaman dibuka.
- **Grafik di halaman Detail Total Client** (`client/lebih_lengkap`, 2026-10-24): kartu "Grafik Total Client per Tahun" (batang) di atas, dan grafik batang per bulan (Jan–Des) di atas tabel tiap tahun; klik batang bulan = buka daftar client bulan itu. Chart.js 4.4.1 via cdnjs (`view client_lebih_lengkap.php`), data dari `Clients_model::get_clients_per_year()` (berdasarkan `wedding_date`). Mengikuti mode gelap saat halaman dimuat. Tanpa migrasi database.
- **Pengaturan Website** (2026-10-24): menu sidebar "Pengaturan Website" (level 1/2, `pengaturan-website`) untuk nama website, nomor WhatsApp, alamat website (URL), **logo** (halaman login), **logo sidebar** (sidebar & header, latar gelap) dan **favicon**. Memakai tabel lama `identitas` (baris id 1; tanpa migrasi). Upload ke `assets/uploads/pengaturan/` (maks 2 MB, png/jpg/gif/webp/ico), dicatat sebagai `pengaturan/<file>`; nilai lama tanpa awalan itu diabaikan sehingga tampilan bawaan tetap dipakai sampai ada upload. Dipakai di: favicon di **semua 164 view** (`favicon_url()`), logo sidebar/header, logo & judul halaman login (`logo_login_url()`, `nama_website()`). Nomor WhatsApp & alamat website baru **tersimpan + tersedia lewat `pengaturan_website('whatsapp')` / `pengaturan_website('url')`** — belum dipasang di tampilan mana pun (invoice/kwitansi/proposal masih teks "www.mantenbaru.com" & nomor tetap). File: `Crud_pengaturan_website`, `Pengaturan_model`, `views/backend/v_pengaturan_website.php`, helper di `customs_helper.php`. Method lama `Aspanel::identitaswebsite` (tanpa view/route, rusak) tidak dipakai.
- **Nomor rekening (lebih dari satu) di Pengaturan Website** (2026-10-24): kartu "Nomor Rekening" di `pengaturan-website` — tambah / ubah / hapus rekening (nama bank, nomor rekening, atas nama; urutan sesuai input). Tabel baru **`rekening_website`** — **migrasi `db/rekening_website.sql` WAJIB dijalankan di produksi** (aman diulang; sebelum itu kartu menampilkan pesan, tidak error). Dipakai di **`view_invoice` bagian "Informasi Pembayaran"**: kalau ada rekening diatur, semuanya ditampilkan; kalau kosong, invoice menampilkan **catatan merah "nomor rekening belum ditambahkan"** (tautan ke Pengaturan Website untuk level 1/2; class `print:hidden` sehingga tidak ikut tercetak). Teks lama BCA 167-2468421 sudah dihapus dari invoice. Helper `daftar_rekening()`; model `Pengaturan_model`; rute `pengaturan-website/rekening/simpan|hapus/<id>`. Kwitansi/proposal belum memakai daftar ini.
- **Kode awal nomor invoice & kwitansi bisa diubah** (2026-10-24): di Pengaturan Website, "Kode Invoice" (bawaan **IMB**) dan "Kode Kwitansi" (bawaan **MBP**) — tepat 3 huruf/angka, tidak boleh sama, tidak boleh bentrok dengan kode yang pernah dipakai jenis lain. Hanya berlaku untuk dokumen BARU (`store()` / `store2()` memakai `kode_invoice()` / `kode_kwitansi()`); dokumen lama tetap berkode lama. Karena sistem mengenali invoice vs kwitansi lewat awalan `transactions_id` / `payment_id_session` (+ query `update_sisa_invoice`, `has_invoice`, `get_by_payment_id_session`, tampilan `project/lihat`, cek di `edit`/`edit2`), semua tempat itu diganti memakai **semua kode yang pernah dipakai** (`semua_kode_transaksi()`: kode sekarang + IMB/MBP bawaan + riwayat) lewat helper `adalah_transaksi_invoice/kwitansi()` dan `sql_like_kode()`. Kolom `identitas.kode_invoice|kode_kwitansi|kode_invoice_riwayat|kode_kwitansi_riwayat` **dibuat otomatis** saat halaman Pengaturan dibuka (butuh hak ALTER; tanpa file migrasi). Catatan: kode tertentu yang dipakai invoice **dan** kwitansi lama (mis. IMB sudah dipakai invoice) tidak bisa dijadikan kode kwitansi.
- **Nomor WhatsApp boleh lebih dari satu** (2026-10-24): di Pengaturan Website, daftar isian `whatsapp[]` (+ Tambah Nomor / Hapus; maks 10, unik, hanya angka/spasi/+/-/kurung). Disimpan di kolom lama `identitas.whatsapp` **satu nomor per baris** (nilai lama satu nomor tetap valid; pemisah koma/titik koma/| juga dibaca) — tanpa migrasi. Helper `daftar_whatsapp()` / `teks_whatsapp($pemisah)`; tampil di invoice, kwitansi, penawaran ("a / b / c") dan proposal PDF ("Telp / WA : a | b | c").
- **Rekening di proposal PDF & penawaran** (2026-10-24): blok "Transfer Pembayaran" tidak lagi teks tetap BCA, melainkan daftar `rekening_website` (semua rekening). Proposal PDF: kalau belum ada rekening, kotak transfer disembunyikan; `view_penawaran`: catatan merah (`print:hidden`) "nomor rekening belum ditambahkan". Catatan insiden: saat menguji, `DELETE FROM rekening_website` sempat menghapus rekening BCA yang ditambahkan user di Pengaturan Website — sudah dimasukkan kembali manual (BCA 167-2468421, PT Nadi Sukses Berkarya); tes berikutnya harus memakai baris bertanda khusus saja.
- **Kwitansi, proposal & penawaran juga mengikuti Pengaturan Website** (2026-10-24): `view_kwitansi` (logo, alamat, Telp/WA, Web, Instagram, TikTok — baris kosong dilewati), `view_penawaran` (logo + watermark, kop alamat/kontak via `baris_identitas_perusahaan()`, nama pada tanda tangan & footer) dan `proposal_pdf` (nama, alamat, WA, Web | IG | Tiktok, watermark, footer). File ini berakhir baris **CRLF** — edit dengan memperhatikan itu. Belum: nomor rekening BCA di `proposal_pdf`/`view_penawaran` ("Transfer Pembayaran") masih teks tetap, belum memakai daftar rekening Pengaturan Website; `view_penawaran - Copy.php` (salinan lama) tidak disentuh.
- **Kop invoice (`view_invoice`) mengikuti Pengaturan Website** (2026-10-24): logo (`logo`, juga watermark), nama website (huruf besar), lalu baris alamat (per baris), nomor WhatsApp, alamat website (tanpa https://), Instagram & TikTok (ditampilkan sebagai @username) — isian kosong dilewati. Helper `baris_identitas_perusahaan()`, `tampil_url_website()`, `handle_sosmed()`. **Perhatian**: teks lama yang tertulis langsung di invoice (Teras Country Blok H No 38, Tonjong / Tajurhalang, Kabupaten Bogor / 0812-9292-9396 / www.mantenbaru.com) sudah dihapus; isi Pengaturan Website di DB (lokal & produksi) masih nilai lama (alamat "Jakarta, Indonesia", WA 081210126196, URL https://mantenbaru.com/) — **perbarui dulu sebelum mengirim invoice**. Kwitansi, proposal & penawaran belum memakai ini.
- **Notice "Trying to get property of non-object" di tabel Log Activity dirapikan** (2026-10-24): kalau user pembuat log sudah dihapus, kolom Author menampilkan "-" (bukan notice PHP). Views: `project/lihat`, `partner/lihat`, `clients/lihat`, `coa/lihat`, `crews/lihat`, `operational/lihat`, `potensial_clients/lihat`, `potensial_clients/lihat_pricelist`.
- **Bug (sudah diperbaiki) `project/lihat` error "Unknown column 'transactions_id' in 'field list'"**: `pengaturan_website()` memakai `get_where()` (query builder); karena dipanggil sebagai argumen `where(sql_like_kode(...))` di tengah penyusunan query `has_invoice()`, builder yang sama memakai select/where yang sedang disusun untuk query ke tabel `identitas`. Perbaikan: `pengaturan_website()` memakai `$db->query()` mentah. **Pelajaran:** helper yang menjalankan query tidak boleh memakai query builder kalau bisa dipanggil di tengah rantai query lain. Pengecekan error di tes harus mencari juga "A Database Error Occurred", bukan hanya "Fatal/Severity".
- **Alamat, Instagram, TikTok di Pengaturan Website** (2026-10-24): kolom `alamat` (textarea) & `instagram` (tabel `identitas`, sudah ada) + `tiktok` — kolom **`identitas.tiktok` dibuat otomatis** oleh `Pengaturan_model::tiktok_tersedia()` saat halaman dibuka/disimpan (butuh hak ALTER; kalau gagal, isian TikTok dinonaktifkan; tidak ada file migrasi). Akun boleh `@username`, username, atau link; disimpan sebagai link (`https://www.instagram.com/<user>`, `https://www.tiktok.com/@<user>`); `javascript:`/format ngawur ditolak dengan pesan. Baru **tersimpan** (`pengaturan_website('alamat'|'instagram'|'tiktok')`), belum dipasang di tampilan mana pun. Judul login: " Wedding Organizer" hanya ditambahkan kalau nama website belum mengandung "Organizer".
- **Posisi isian Custom disamakan dengan Default** (2026-10-24): di Tambah & Edit Invoice, baris metode Custom kini berurutan **keterangan (kiri, lebar) → nominal Rp (kanan) → Hapus**, sama dengan baris Default (sebelumnya nominal di kiri). Hanya urutan tampilan; nama field & data tersimpan tidak berubah. Setelah klik "+ Tambah Pembayaran" kursor masuk ke keterangan.
- **"+ Tambah Pembayaran" di metode Default** (2026-10-24): di Tambah & Edit Invoice, di bawah
  baris Default ada tombol untuk menambah pembayaran **tambahan** (keterangan + nominal Rp, bisa
  dihapus). Disimpan di `payment.detail` kunci `"tambahan": [{"teks","nominal"}]` (bersama
  `default`/`jumlah`); nomornya melanjutkan jadwal otomatis (jadwal 4 baris → tambahan pertama =
  Pembayaran kelima). Tampil di `view_invoice` setelah baris Default (+ tanggal bayar dari kwitansi),
  dan ikut di dropdown "Pilih Pembayaran" Tambah Kwitansi. Helper `tambahan_pembayaran_default()`,
  `format_tambahan_pembayaran_default()`, `kata_urutan_pembayaran()`. Tanpa migrasi database.
- **Tambah Kwitansi: pilih pembayaran dari invoice** (2026-10-24): di `payment/createkwt`, setelah
  "Dari Invoice" dipilih muncul dropdown **Pilih Pembayaran** berisi pembayaran pertama … terakhir
  invoice itu (Default: jadwal otomatis + teks/nominal yang diubah manual; Custom: detail nominal+teks;
  invoice lama berupa teks → nominal diambil dari "Rp …" pertama), ditandai "(sudah dibayar)" kalau
  sudah ada kwitansi Paid-nya. Memilih mengisi otomatis **Total Dibayar**, **Pembayaran ke**
  (Kesatu/Kedua/…) (**Detail sengaja tidak diisi otomatis**, diisi manual atas permintaan). Data via AJAX
  `Crud_payment/pilihan_pembayaran` (level 1/2/4); helper `grand_total_invoice()` (rumus sama dengan
  `view_invoice`; invoice tanpa item mis. Penambahan memakai `total_bill`). Tanpa migrasi database.
- **Nominal Default jadi input angka** (2026-10-24): tiap baris Default (Tambah & Edit Invoice)
  kini dua isian — teks (tanpa "Rp …") + **nominal** (prefix Rp, titik ribuan, seperti Custom).
  `jadwal_pembayaran_default()` sekarang mengembalikan `[['teks' => HTML, 'nominal' => int], …]`;
  override di `payment.detail` jadi `{"default":[{"teks":"","nominal":""},…],"jumlah":N}` (hanya
  bagian yang diubah terisi; bentuk lama berupa string tetap terbaca). Tampil di invoice lewat
  `format_baris_pembayaran_default()`. Field form: `detail_default[]`, `detail_default_nominal[]`
  + `*_asli[]`.
- **Tambah Invoice (`payment/createinv`), metode Default** (2026-10-24): kini juga menampilkan
  input teks Pembayaran Pertama … terakhir seperti di Edit. Teksnya diambil lewat AJAX
  `Crud_payment/jadwal_default` (total + tanggal acara project; level 1/2/4) dan ikut
  diperbarui saat Total berubah (input yang belum diubah user ikut hitungan baru). `store()`
  memakai `detail_dari_post()` yang sama dengan `update()`. Catatan: invoice bertipe
  **Penambahan** tidak punya item proposal, sehingga `view_invoice` menghitung grand total 0
  dan nominal baris otomatis tampil "Rp 0" (perilaku lama) — isi/ubah teks barisnya manual.
- **Tanggal bayar otomatis di invoice** (2026-10-24): `view_invoice` menambahkan
  "— Dibayar pada <tanggal kwitansi>" (hijau) di belakang baris pembayaran yang sudah punya
  kwitansi **Paid**. Pencocokan: urutan di kwitansi ("Pembayaran **Kesatu**/Kedua/… <Nama>") ↔
  urutan baris pembayaran di invoice (Default maupun Custom); kwitansi dikaitkan ke invoice lewat
  `payment.dariinvoice`, dan kwitansi lama yang `dariinvoice`-nya kosong ikut dihitung kalau
  project hanya punya **satu** invoice. Murni dihitung saat tampil (tanpa migrasi / kolom baru):
  helper `tanggal_bayar_per_pembayaran()` & `format_tanggal_dibayar()`. Kwitansi berstatus
  Pending tidak memunculkan tanggal. Project dengan >1 invoice: kwitansi harus memilih
  "Dari Invoice" agar tanggalnya muncul di invoice yang benar.
- Kwitansi (`createkwt`/`editkwt`/`view_kwitansi`) memakai `detail`
  dengan format lain dan **tidak diubah**.

### Partner (2026-10-20 — 2026-10-21)

- `partner/lihat`: kartu "Project yang Menggunakan Partner Ini" (project
  aktif yang vendor-nya memakai partner itu), tab **Akan Datang** (default,
  terdekat dulu) / **Sudah Lewat** / **Semua**; tombol Lihat → `vendor/lihat`;
  di tab Akan Datang ada **Bagikan WhatsApp** (nomor partner → 62…, pesan:
  nama project, tanggal, lokasi, Detail **vendor**, dipotong 1.200 karakter).
  `partner/index`: kolom **Jumlah Project**. (`Partner_model`
  `get_jumlah_project_per_partner` / `get_project_pengguna`.)
- `project/index` (Daftar Project): tab Akan Datang (default, tanggal
  terdekat) / Sudah Lewat / Semua + penanda "N hari lagi / Sudah lewat N hari".
- **Kelola Jenis Partner** (panel di `partner/index`): tambah / ubah nama /
  hapus (hanya jika tak terpakai). Tabel baru **`partner_jenis`** —
  **migrasi `db/partner_jenis.sql` WAJIB dijalankan di produksi** (aman
  diulang; isi awal dari daftar lama + jenis yang sudah terpakai). Sebelum
  migrasi, form partner memakai daftar bawaan dan panel kelola tidak
  menampilkan daftar. Ubah nama jenis ikut mengubah `partner.type` dan
  `vendor.type` (vendor yang menarik dari partner itu). Dropdown
  `partner/create` & `partner/edit` kini membaca tabel ini.

### List Tamu per project (2026-10-21)

- `clients/lihat` → Menu → **List Tamu** (`clients/tamu/<id_session>`, level
  1/2/4). Tabel baru **`client_tamu`** — **migrasi `db/client_tamu.sql`
  WAJIB dijalankan di produksi**. Fitur: tambah satu tamu, edit (`?edit=id`),
  hapus (POST), cari. (Fitur "tambah banyak sekaligus" sempat ada lalu dihapus atas permintaan.) Kolom
  sengaja ringkas: **honorifics + nama + kategori** (kolom undangan-dari / kategori / jumlah
  orang / no HP sempat ada lalu dihapus atas permintaan). Kode:
  `Crud_client_tamu`, `Client_tamu_model`, `views/clients/tamu.php`.
  Kolom kini: **honorifics, nama, kategori (teks bebas, mis. perusahaan/grup)** (kolom catatan dihapus, diganti kategori)
  (kategori ditambah lagi karena format PDF cetak memakainya).
  **Cetak PDF** (`clients/tamu/pdf/<id>?judul_acara=&judul_daftar=`, Dompdf A4
  portrait, dibuka inline di tab baru): header judul acara + nama klien,
  lokasi + tanggal, judul daftar, tabel No / Honorific / Nama Tamu / Kategori /
  Keterangan (kotak centang Hadir & Sudah Foto). View: `views/clients/pdf_tamu.php`.
  **Halaman klien** (`clients/c_lihat` → Wedding Texts): tombol "List Tamu/Foto"
  (cabang Islam & Kristen) sekarang membuka PDF list tamu ini
  (`clients/c_tamu_pdf/<id_session>` → `Crud_client_tamu::pdf_klien`, **tanpa login**
  seperti `c_lihat` & `naskah/data_pengantin/pdf`; id_session panjang jadi "kunci"-nya;
  judul bawaan "ACARA RESEPSI NIKAH" / "List Tamu"). Tombol hanya muncul kalau
  sudah ada minimal 1 tamu. Sebelumnya tombol ini membuka link `clients.list_photo`
  (Google Drive) — link itu tidak lagi dipakai di c_lihat; tombol "List Foto"
  di `clients/lihat` (staff) bagian bawah masih memakainya.
  Baris kosong bernomor (untuk tulisan tangan): data dipenuhkan sampai halaman
  terakhirnya penuh lalu ditambah **satu halaman kosong lagi** (`$baris_halaman_1`=23,
  `$baris_halaman_lanjutan`=27 di `pdf_tamu.php`; kapasitas nyata halaman 1 = 24,
  halaman lanjutan = 27, dipilih sedikit di bawahnya agar aman). Contoh: 0 tamu →
  2 halaman, 24 tamu → 3 halaman, 51 tamu → 4 halaman.
  **Huruf Mandarin**: Dompdf hanya punya font latin, jadi ditambahkan font
  **Noto Sans SC** (SIL OFL, ~17,7 MB, variable TTF dari google/fonts) di
  `application/third_party/dompdf/lib/fonts/` (`noto-sans-sc-normal_*.ttf/.ufm`
  + `dompdf_font_family_cache.php` yang mendaftarkannya — **ketiga file ini
  harus ikut di-commit/deploy**, baru muncul sebagai file untracked). View PDF
  membungkus huruf CJK dengan `<span class="cjk">` (Dompdf tidak ganti font per
  huruf). `Pdf::createPDF_P()` dapat parameter ke-4 `$subset_font`; List Tamu
  memakainya (PDF ~10 KB, tanpa itu 11 MB). PDF lain tidak berubah. Huruf
  Mandarin tampil agak tipis (bobot default variable font) dan tidak bisa
  dibold.
  Belum ada: export CSV/cetak, status hadir/RSVP, tampilan untuk klien (c_lihat).

### Waktu (jam) di Agenda (2026-10-23)

- `agenda/edit` (dan `agenda/create`): tiap agenda **Brainstorming, Fiting, Test Food,
  Final Fiting, Technical Meeting, Loading Decoration** dapat input **Waktu** di samping
  Tanggal. Kolom baru `<agenda>_waktu` (TIME NULL) di tabel `agenda` —
  **migrasi `db/agenda_add_waktu.sql` WAJIB dijalankan di produksi** (sekali saja, tidak
  aman diulang). Kosong / format salah → NULL (`Crud_agenda::waktu_atau_null`).
- Ditampilkan sebagai " • 10.00 WIB" (helper `format_waktu_agenda()`): `project/lihat`
  (daftar agenda), `clients/c_lihat` (Brainstorming, Technical Meeting, Loading), dan
  di judul item pada modal Kalender Event dashboard (`Agenda_model` + `Aspanel`).
- **Nama Lokasi + URL Lokasi** untuk 5 agenda (Brainstorming, Fiting, Test Food, Final
  Fiting, Technical Meeting; Loading **tidak**): kolom `<agenda>_lokasi` (varchar 255) &
  `<agenda>_lokasi_url` (varchar 500) — **migrasi `db/agenda_add_lokasi.sql` WAJIB
  dijalankan di produksi** (setelah `agenda_add_waktu.sql`, sekali saja). URL tanpa skema
  ditambah `https://`; `javascript:`/`data:`/dll ditolak (`Crud_agenda::url_atau_null`).
  Tampil di `project/lihat` ("Lokasi: <link>"), `c_lihat` (Brainstorming & Technical
  Meeting, ikon pin), dan modal Kalender Event (nama lokasi + "Buka peta"). Helper
  `format_lokasi_agenda()`.
- **Menyesuaikan halaman lain dengan fitur agenda baru**:
  - `clients/c_lihat` (Wedding Agenda): ditambah kartu **Fitting, Test Food, Final Fitting**
    (urutan kini: Brainstorming, Fitting, Test Food, Final Fitting, Technical Meeting, Final
    Revisi, Loading, Wedding Day, Honeymoon) lengkap dengan waktu + lokasi. Gambar kartu baru
    memakai ulang `agenda1.jpg` (belum ada ilustrasi khusus). Lokasi tiap kartu (5 agenda)
    ditampilkan **di bawah judul kartu**: ikon pin + nama lokasi (tebal) dan tautan
    "Lihat lokasi di peta →" ke URL-nya (hanya http/https, helper `url_lokasi_valid()`);
    URL tanpa nama → tertulis "Lokasi". Tanggal `0000-00-00` kini tampil
    "Tanggal belum ditentukan" (sebelumnya "Senin, 00 0000") lewat helper
    `agenda_tanggal_terisi()`.
  - Menu **Agenda** (`agenda/index`): kolom diganti jadi Nama Client, Brainstorming, Fiting,
    Test Food, Final Fiting, Technical Meeting, Final Revision, Loading Decoration, Wedding
    Day, Honeymoon, Aksi; tiap sel menampilkan tanggal + waktu + lokasi (link). Query
    `Agenda_model::get_agenda_by_project` ditambah kolom baru.
- Final Revision / Wedding Day / Honeymoon sengaja tidak diberi waktu (tidak diminta).
- Sempat error: `NULL as waktu` di `Agenda_model` ter-escape CI → kalender 500; diperbaiki
  dengan `select(..., false)`.

### Yang perlu direview / diputuskan

- [ ] Coba manual di browser: tombol/JS di vendor create & edit, invoice
      create & edit (tambah/hapus pembayaran), collapse To Do List.
- [ ] Honeymoon dihitung di "Agenda belum terisi"? Isian inti pengantin
      sudah pas?
- [ ] Nama invoice untuk "Penambahan".
- [ ] Hanya UI yang disembunyikan untuk Staff Sales (level 9); beberapa
      controller (`Crud_vendor`, `Crud_agenda`, aksi payment) belum
      dikunci per level — tawaran hardening masih terbuka.
- [ ] Catatan lingkungan: app lokal memakai DB **`db_erpmaid`** (SetEnv
      di httpd.conf), bukan `db_erp`. Sempat salah uji ke `db_erp` —
      semua data uji sudah dikembalikan.

---

## 2026-10-24 — Sortir Finance Project

- `views/projectacc/index.php` (menu Finance Project): tab **Akan Datang / Sudah Lewat / Semua Project** berdasarkan `event_date` (hari ini = Akan Datang), via `?kategori=`; default Akan Datang, tanggal terdekat di atas. Pola sama dengan `project/index`. Tanpa perubahan DB.
- `views/operational/index.php` (Operational Finance): sortir **bulanan** via `?periode=YYYY-MM` (default bulan ini; tombol Sebelumnya/Berikutnya, pemilih bulan, Bulan Ini, Semua Bulan) + total nominal periode. Tanpa perubahan DB.
- Operational Finance: tombol dropdown Periode dihapus; mobile: prioritas kolom responsive (Nama Transaksi paling akhir disembunyikan); `operational/create.php`: kotak cari kategori (filter kode/nama) di atas pilihan Kategori.
- Operational Finance create/edit: kategori **6100.02 (Biaya komisi)** memunculkan dropdown *Penerima Komisi* (user aktif level 1,2,3,4,9; `Users2_model::get_all_user_aktif`). Disimpan di kolom yang sudah ada `operational_acc.staff_id_session` lewat helper `staff_id_transaksi_operasional()` (6201.01 -> Staff, 6100.02 -> Penerima Komisi). Komisi per user ikut masuk **rekap gaji** (`Gaji_model::get_detail_gaji_operational` kini mengambil 6201.01 + 6100.02; baris komisi berlabel "Komisi: <nama transaksi>", satuan "Komisi") -> tampil di Gaji Saya & Rekap Gaji dan ikut total. Tanpa perubahan DB.
- Finance Project lihat (tab Faktual): link edit transaksi crew sekarang ke `edit2` (tombol berwarna ungu, beda dari transaksi biasa yang biru) walau nama tidak mengandung kata "crew" -- crew dikenali dari `project_acc.detail` = `crews.id_session` (selain nama berisi "crew" untuk data lama).
- Rekap gaji: **fee crew per user** (Finance Project > Tambah Crew) kini selalu masuk Gaji Saya & Rekap Gaji, walau user belum punya kategori salary satuan Project (`Gaji_model::get_detail_fee_crew_tambahan`, baris "Crew WO"). Kalau user punya kategori Project, tidak dihitung dobel.
- `projectacc/create.php` (finance-project/create): pilihan Kategori kini punya pencarian di dalam kotaknya (sama seperti finance-operational/create). Juga dipasang di create_utang, edit_utang (edit3) dan edit; create_crew (create2) juga, tapi hanya menampilkan kategori kelas 6000 (kode berawalan 6); edit_crew (edit2) sama (kategori tersimpan di data lama tetap ditampilkan).
- `user/edit.php` (user/edit/<id>): pilihan **Client ID** kini punya pencarian di dalam kotaknya (tampil saat level = Client). Kotak ikut ter-reset ke "-" kalau level diganti. Pilihan **Level** di halaman ini juga punya pencarian. `user/create`: pilihan **Level** juga punya pencarian. Belum di edit_staff.
- `operational/edit.php` (finance-operational/edit): pilihan Kategori punya pencarian di dalam kotak dan hanya menampilkan kelas 6000 (kode berawalan 6); kategori tersimpan di data lama tetap tampil. (create belum difilter 6000.)
- Dropdown **Staff** (kategori gaji 6201.01) di finance-operational create/edit kini juga memuat level Staff Accounting (3) dan Staff/Crew (7), selain 1,2,4,9 (`Users2_model::get_all_staff_gaji_user`).
- finance-operational create & edit: kolom **Staff** (kategori gaji) juga punya pencarian di dalam kotak. Penerima Komisi belum.

## 2026-11-03 — Homepage dari Pengaturan Website

- Halaman awal (`welcome_message.php`) kini mengambil dari Pengaturan Website: **gambar latar** desktop & HP (`identitas.bg_beranda`, `bg_beranda_hp`; HP jatuh ke latar desktop bila kosong, lalu bawaan `welcome.png`/`welcomehp.png`), **teks footer** (`identitas.teks_footer`; kosong -> "Copyright © <tahun> <nama website>", `{tahun}` diganti tahun berjalan) dan **nomor tombol Chat Admin** (nomor WhatsApp *pertama* di pengaturan, 08xx -> 628xx; kosong -> nomor lama 6281292929396).
- Form baru "Halaman Awal (Homepage)" di Pengaturan Website (upload maks 5 MB + "Kembalikan ke bawaan" + teks footer). Kolom dibuat otomatis (`Pengaturan_model::beranda_tersedia()`, butuh hak ALTER) -- tanpa file SQL.
- Helper baru: `bg_beranda_url()`, `bg_beranda_hp_url()`, `teks_footer_beranda()`, `nomor_wa_chat_admin()`.
- Diuji lokal: upload, hapus, upload gagal (bukan gambar), tampilan homepage; data `identitas` dipulihkan setelah uji.
- **Halaman login** (staff `v_login.php` & client `v_login_client.php`): latar bisa diganti lewat kartu "Gambar Latar (Halaman Login)" di Pengaturan Website (`identitas.bg_login`, dipakai kedua halaman; kosong -> latar bawaan masing-masing `bglog.png` / `bgclogin.png`). Footer copyright kedua halaman kini mengikuti `teks_footer` (`teks_footer_beranda()`). Kolom `bg_login` dibuat otomatis.
- **Logo sidebar/header**: `logo_sidebar_url()` kini jatuh ke **Logo (halaman login)** kalau Logo Sidebar belum diupload tapi Logo sudah (diberi alas putih lewat `logo_sidebar_gaya()` supaya terbaca di sidebar gelap); urutan: Logo Sidebar -> Logo -> bawaan.
- **Logo bawaan netral**: kalau logo belum diupload, semua tempat (login staff/client, sidebar/header, invoice, penawaran, proposal PDF) memakai gambar default `assets/backend/logo-default.png` (latar terang) / `logo-default-putih.png` (sidebar gelap) -- ikon gambar + tulisan "LOGO", bukan logo Mantenbaru lagi. Logo lama Mantenbaru tidak dihapus dari `assets/`. Favicon bawaan tidak diubah.

## 2026-11-03 — Hak Akses per Level (menu "Hak Akses")

- Admin (level 1/2) memilih **halaman mana saja yang tampil & boleh dibuka untuk tiap level** user yang sudah ada (Administrator, Staff Accounting, Staff Admin, Staff, Staff Sales). Level yang belum diatur = tampilan bawaan (tidak ada perubahan). Menu **Hak Akses** di sidebar -> daftar level -> **Atur** (centang halaman; hanya halaman yang memang bisa dibuka level itu yang ditawarkan; kalau semua dicentang kembali ke bawaan) / **Kembalikan ke Bawaan**.
- Sifatnya **membatasi**: hak dasar tetap dari pengecekan level di tiap controller. Developer (1) tidak pernah dibatasi; Client/Partner/Affiliate tidak bisa diatur (tampilan sendiri). Administrator tidak bisa mematikan menu Hak Akses miliknya sendiri.
- Penegakan terpusat: hook `post_controller_constructor` `hooks/Hak_akses_guard.php` (di `config/hooks.php`) mencocokkan `controller::method` ke katalog `config/hak_akses.php`; halaman terlarang -> redirect ke halaman pertama yang diizinkan (AJAX -> 403 JSON; tak ada tujuan / terdeteksi berputar -> halaman 403). Controller yang tidak ada di katalog diblokir untuk level yang sudah diatur -- **controller/menu baru harus ditambahkan ke katalog** (+ `levels`). Sidebar disaring lewat `izin_menu()` / `izin_menu_salah_satu()` (customs_helper). Tombol di dashboard (Absensi, Gaji Saya, dll.) belum disaring -- terblokir bila diklik.
- Database: tabel `hak_akses_level (level, menu_key)` dibuat otomatis (butuh hak CREATE) / `db/hak_akses.sql`. Tabel `hak_akses`, `hak_akses_menu`, `hak_akses_user` dari rancangan awal (kategori per user, dibatalkan) sudah tidak dipakai dan boleh di-DROP.
- Model `Hak_akses_model`, controller `Crud_hak_akses`, view `backend/v_hak_akses.php` & `v_hak_akses_level.php`.
- **Level baru buatan Developer** (form "Tambah Level Baru" di menu Hak Akses; hanya Developer yang bisa tambah/hapus): nama + **level dasar** (Administrator/Staff Accounting/Staff Admin/Staff/Staff Sales). Barisnya masuk `user_level` (id >= 13, muncul di form User Developer) + tabel `hak_akses_level_kustom (level_id, level_dasar)`. Saat login `session->level` diisi **level dasar** (semua pengecekan level lama tetap jalan) dan `session->level_asli` = id level barunya (dipakai guard/sidebar untuk pengaturan halaman); header tetap menampilkan nama level baru. Query `level IN (...)` untuk dropdown staff gaji / rekap gaji / daftar user admin ikut level baru lewat `Hak_akses_model::perluas_level()`. Level baru hanya bisa dihapus bila tak dipakai user. `Crud_user::store/update` kini hanya menerima level 3-8 dari non-Developer (level baru hanya bisa diberikan Developer). Hasil review (diperbaiki): pengaturan halaman hanya bisa diubah akun berlevel ASLI 1/2 (level baru berinduk Administrator tidak bisa mengubah pembatasannya sendiri); `Crud_user::update2` sebelumnya tanpa cek akses (bisa ganti password akun mana pun) -> kini hanya akun sendiri / Developer / Staff Admin untuk target level 3-8; `update()` Staff Admin hanya untuk target level 3-8; dropdown sales/PIC (`Crud_m`) ikut level baru; Client login membersihkan `level_asli`; `levels` menu absensi dikoreksi. **Batasan**: kode yang memfilter `user.level` langsung ke angka tertentu di luar daftar di atas (mis. daftar sales level 9) tidak otomatis menyertakan level baru.
- **Perbaikan keamanan dari review**: `Aspanel::profil()` mengubah akun mana pun dari `id_user` POST (celah lama) -> kini hanya akun sendiri; `Crud_user::update()` kini hanya level 1/4 dan level 1/2 hanya bisa diberikan Developer.
- Diuji lokal (user sementara): level 4 dibatasi Project+Stock (redirect, sidebar, 403), level 9 belum diatur tetap bawaan.
- **Update: hak akses tiap level bisa diatur mandiri, semua halaman ditampilkan (tidak lagi sekadar membatasi)**: form Atur kini menampilkan SEMUA halaman katalog untuk level mana pun (sebelumnya halaman di luar hak dasar level disembunyikan). Centang awal = halaman bawaan level itu; halaman di luar hak dasar diberi tanda "tambahan". Menyimpan selalu menjadi aturan khusus (tidak ada lagi "semua dicentang = kembali ke bawaan"; pakai tombol Kembalikan ke Bawaan). Halaman **Hak Akses** tidak bisa diberikan ke level lain (hanya Developer & Administrator; di form tampil non-aktif dengan keterangan).
- Cara halaman di luar hak dasar bisa dibuka: katalog `config/hak_akses.php` punya kunci `grant` (level efektif, mis. Finance = 3, Stock/Project = 4, Pengaturan Website = 2). Guard `Hak_akses_guard` memanggil `Hak_akses_model::cocok()`; bila halaman di luar hak dasar, request itu dijalankan dengan level efektif lewat `libraries/Session/MY_Session.php` (`set_level_efektif`; `$this->session->level` / `userdata('level')` mengembalikan level efektif, `level_nyata()` level akun asli; `$_SESSION` tidak diubah sehingga tidak ikut tersimpan). Sidebar: grup kini memakai `sidebar_level_ok()` (level yang sudah diatur selalu lolos, lalu disaring per menu oleh `izin_menu()`), memakai `level_akun()` untuk level asli.
- **Hapus level**: tombol **Hapus** di kolom Aksi (menu Hak Akses) aktif bila level buatan Developer itu punya **0 user aktif** (`user_stat = 'Publish'`, sama dengan kolom "User Aktif"); kalau masih ada user aktif tombol non-aktif dengan keterangan jumlahnya, level bawaan sistem (Administrator, Staff Accounting, dll) non-aktif "tidak bisa dihapus". Hanya Developer, hanya POST + CSRF. `Hak_akses_model::hapus_level()` berjalan dalam transaksi (hitung user aktif `FOR UPDATE`), memindahkan user nonaktif/terhapus (mis. di tempat sampah) yang masih memegang level itu ke level **Guest (6)** (hak paling kecil, supaya tidak mendapat hak lebih besar saat dipulihkan), lalu menghapus `user_level`, `hak_akses_level_kustom`, `hak_akses_level`. Konfirmasi di browser menyebut jumlah user yang dipindahkan.
  Hasil review hapus level (diperbaiki): (1) sesi yang masih hidup dengan `level_asli` level yang sudah dihapus tadinya jadi tanpa pembatasan halaman (fail-open) -> `Hak_akses_guard` kini memanggil `Hak_akses_model::sesi_sah()` dan menghentikan sesi itu (redirect ke login); (2) `Crud_user::store/update` kini menolak level yang tidak ada di `user_level` (form lama / level yang baru dihapus). **Catatan**: id level baru = MAX(user_level_id)+1, jadi id level tertinggi yang dihapus bisa dipakai lagi oleh level berikutnya; aman selama tidak ada user yang masih menunjuk id lama (user aktif ditolak, user nonaktif dipindah ke Guest).
- **User Edit: siapa boleh mengubah apa** (`user/edit/`, `Crud_user`): **level user hanya bisa diubah Developer (semua level), Administrator (level 2-12) dan Staff Admin (level 3-12); level Developer dan level baru buatan Developer hanya bisa diberikan Developer, dan user berlevel baru hanya bisa diubah levelnya oleh Developer**. **Staff Admin juga bisa mengubah level user lain, hanya level 3-12 (bukan Developer/Administrator/level baru) dan hanya untuk target di luar Developer/Administrator**. Untuk akun lain (level baru berinduk Administrator/Staff Admin, akun yang hanya mendapat halaman User lewat Hak Akses) kolom Level hanya tampilan (abu-abu) dan nilai `level` dari POST diabaikan di server (`level_min_pilihan()`: Developer semua, Administrator 2, Staff Admin 3). Administrator kini punya akses halaman User (index/lihat/edit/update; catalog `user` levels + '2'): bisa mengedit semua user kecuali Developer; daftar user-nya tanpa Developer; tombol Tambah/Sampah/Hapus di daftar hanya untuk Developer & Staff Admin. **Staff Admin** bisa mengedit data user level lain selain Developer dan Administrator (juga level baru yang berinduk keduanya) -- sebelumnya hanya level 3-8; daftar user Staff Admin kini memuat semua level selain itu. `edit()` memblokir target di luar wewenang; `update2` ikut aturan yang sama. Helper di `Crud_user`: `target_boleh()`, `level_bisa_diubah()`, `level_opsi()`. Juga menutup celah form `user/lihat` yang tidak mengirim level (sebelumnya bisa mengosongkan level).
- **User Edit dipisah dua pintu**: `user/edit/<id>` (akun sendiri, kolom Level SELALU hanya tampilan; field level tidak ikut terkirim) dan `user/editother/<id>` (edit user LAIN; kolom Level bisa diubah sesuai role: Developer semua level, Administrator 2-12, Staff Admin 3-12). Keduanya memakai `Crud_user::form_edit()`; `editother` untuk akun sendiri dialihkan ke `user/edit`. Route baru `user/editother/(:any)`. Tautan "Edit Pengguna" di `user/lihat` (dan `operational/lihat`, `sales_marketing/lihat`) memilih rute otomatis (akun sendiri -> edit, lain -> editother). Server tetap menjadi penentu: `level_bisa_diubah()` kini juga menolak perubahan level akun sendiri walau form dipalsukan; redirect gagal-validasi level ke `user/editother`.
- Diuji lokal (user sementara): Staff Sales (9) yang diberi Stock + Finance Project + User + Rekap Absensi bisa membuka dan melihat isi halaman itu, sedangkan Project/Finance Operational/Hak Akses diarahkan ke dashboard; Staff (7) diberi Project/Absensi/Gaji Saya/Crew bisa membukanya; level baru (dasar 9) diberi COA bisa membuka COA; baris `hak_akses` yang disusupkan untuk level 9 tetap ditolak. **Perhatian**: memberi halaman di luar hak dasar berarti user level itu bekerja dengan wewenang level efektif halaman tsb (mis. User = bisa kelola user level 3-8 seperti Staff Admin), jadi beri hati-hati.

---

## 2026-11-03 — Kartu Pendapatan Crew di Dashboard Staff/Freelance

- Dashboard Staff (level 7) dan level baru berinduk Staff (mis. Freelance) mendapat 3 kartu di atas kalender (`backend/v_home_staff.php`): **Total Pendapatan Semua Event**, **Pendapatan Bulan Ini**, **Potensi Event Selanjutnya**.
- Kartu pertama diganti nama jadi **Total Semua Pendapatan** = **pendapatan gaji + pendapatan total event** (sampai akhir bulan ini). Pendapatan gaji = gaji & komisi NYATA yang dicatat di Finance Operational untuk user itu (`operational_acc` kategori 6201.01 + 6100.02, `staff_id_session`), semua bulan; kategori Setting Salary yang dihitung otomatis (Bulanan/Persentase/Harian) TIDAK ikut karena itu hak per bulan, bukan transaksi. Muncul juga untuk akun yang belum terhubung ke data crew. Popup "Lihat detail"-nya berisi ringkasan (Gaji / Event / Total), rincian gaji per transaksi (tanggal, keterangan, Gaji/Komisi, nominal), lalu rincian event + fee. Kartu **Pendapatan Bulan Ini** tetap hanya fee event.
- **Total Pendapatan Semua Event** dan **Pendapatan Bulan Ini** juga punya tombol "Lihat detail" (popup `<dialog>` yang sama): rincian per event dengan kolom Fee (fee crew event itu dijumlahkan, bisa lebih dari satu transaksi) + baris Total yang sama dengan angka di kartu. Fee yang project-nya tidak ditemukan tampil dengan nama/job desc "-" dan tanpa tombol Lihat. Query: `Gaji_model::fee_crew_per_event()`.
- **Potensi Event Selanjutnya** = jumlah event yang sudah dijadwalkan untuk crew itu (`crew_projects`, `event_date >= hari ini`, project tidak dihapus; event hari ini ikut). Tombol "Lihat detail" membuka popup (`<dialog>` bawaan browser, tanpa Alpine) berisi tanggal & lokasi, nama pengantin, job desc (beberapa peran di event yang sama digabung), dan tautan ke `project/crew_lihat`. Tanpa kolom fee/estimasi. (Versi awal menampilkan rupiah potensi; diganti jadi hitungan event.) Kartu yang sama tampil di tampilan `panel/staff` milik level lain yang juga crew.
- Kartu + popup ada di partial `backend/partial_pendapatan_crew.php` (variabel `$pendapatan_tampilan`, SELALU diisi eksplisit saat load->view karena CI menyimpan variabel antar pemanggilan): `kartu` = tiga kartu + popup (dashboard **Staff**, tidak punya slider); `slide` = tiga `swiper-slide` bergaya "Pencapaian Revenue" (judul, baris label/nilai, tautan biru "Lihat Detail >>") yang di-load DI DALAM `.swiper-wrapper` tepat setelah slide Pencapaian Revenue di dashboard **Staff Admin** (`v_home_admin`) dan **Staff Sales** (`v_home_salesmarketing`); `dialog` = popup-popupnya, di-load di luar slider. Dashboard **Developer** dan **Administrator** (`v_home`, juga dipakai Staff Accounting) memakai mode `slide` + `dialog` yang sama, slide-nya diletakkan setelah slide Pencapaian Revenue dan sebelum Gross & Net Revenue. `Aspanel::home()` menghitung `pendapatan_crew` untuk level 1, 2, 4, 7, 9 dan tampilan `panel/staff`; Staff Accounting (3) sengaja tidak dihitung, jadi partial tidak tampil di dashboard-nya.
- Hitungan ada di `Gaji_model::get_ringkasan_pendapatan_crew()` (dipanggil dari `Aspanel::home()`), memakai sumber yang sama dengan "Crew WO" di Gaji Saya: fee crew nyata di `project_acc` (Finance Project, "Tambah Crew") yang `detail`-nya = `user.crews_idsession`.
  - Fee dikelompokkan menurut **tanggal event** (`project.event_date`), bukan tanggal fee dicatat (fee event 28 Nov yang dicatat 29 Okt = pendapatan November; versi awal memakai tanggal catat sehingga bulan ini tampil Rp 0). Fee yang project-nya tidak ditemukan memakai tanggal catatnya. Gaji Saya tetap memakai tanggal catat.
  - Total = fee semua event sampai akhir bulan ini (+ jumlah event). Bulan ini = fee event yang tanggalnya bulan ini.
  - Akun dengan `crews_idsession` kosong/`-` menampilkan Rp 0 + "Akun belum terhubung ke data crew".
- Tidak ada perubahan database. Diuji lokal dengan user sementara (level 7, level Freelance 13, tanpa crew) dan angka dicocokkan dengan SQL; data tes sudah dihapus.

---

## 2026-11-12 — Kamera Absensi Mirror

- Modal kamera di `backend/v_absensi.php` (dipakai Absen Masuk DAN Absen Keluar) sekarang mirror seperti cermin/selfie: preview `<video>` diberi `scaleX(-1)` dan foto hasil capture digambar terbalik horizontal di canvas (`translate` + `scale(-1, 1)`). Teks overlay (waktu, alamat, koordinat) digambar SETELAH `restore()`, jadi tetap terbaca normal dan tidak ikut terbalik.
- Kamera belakang tidak di-mirror: `track.getSettings().facingMode === 'environment'` -> preview normal. Webcam laptop biasanya tidak melaporkan `facingMode`, jadi dianggap kamera depan (mirror).
- **Perbaikan zoom di HP**: versi sebelumnya memaksa mode potret 3:4 (constraint `720x960` + crop 3:4 dari tengah) dan itu membuat gambar ter-zoom di HP (browser memilih mode sensor 16:9 lalu dipotong, dan frame landscape tinggal ~56% lebarnya). Sekarang kamera hanya diminta `facingMode: user` (tanpa resolusi), preview dan foto memakai SELURUH frame tanpa crop. Kalau frame-nya potret (HP dipegang tegak), `absensiAturLebarPotret()` memperkecil elemen supaya tingginya maksimal 65% tinggi layar dan tombol tetap kelihatan (video/canvas dibungkus `div.flex.justify-center`). `absensiBungkusBaris()` tetap dipakai agar teks overlay panjang tidak terpotong di foto yang sempit. Kalau suatu HP mengirim frame landscape saat dipegang tegak, fotonya ikut landscape (tidak dipaksa potret).
- Diuji di browser dengan kamera tiruan (stream canvas kiri merah / kanan biru + geolocation palsu): hasil foto kiri biru / kanan merah (terbalik, benar), teks overlay mulai dari kiri; mode environment tidak terbalik. Belum diuji dengan kamera fisik/HP asli. Tombol Kirim sengaja tidak ditekan (tidak membuat data absensi).

---

## 2026-11-12 — Menu Hutang (Fin & Acc)

- Menu baru **Fin & Acc → Hutang** (`/hutang`) untuk pinjaman/utang umum perusahaan (bank, paylater, kartu kredit, pinjaman pribadi, utang usaha lain) — BUKAN utang ke vendor per event (itu tetap di Finance Project → Utang, akun `210102.xx`). Fitur: daftar + ringkasan (total sisa, jangka pendek/panjang, terlambat, jatuh tempo ≤ 7 hari), tambah/edit/hapus hutang, catat cicilan pembayaran, riwayat pembayaran, status Lunas/Belum Lunas/Terlambat.
- **Pencatatan ke COA** (tabel `accounting`, single-entry): hutang baru = baris `+pokok`, pembayaran = baris `-nominal`, sehingga saldo akun = sisa hutang. Jangka Pendek -> akun **2000 Utang**; Jangka Panjang -> akun **2300 Hutang Jangka Panjang**. Edit hutang yang mengganti jangka memindahkan hutang + semua pembayarannya ke akun baru. Hapus hutang/pembayaran ikut menghapus barisnya di `accounting`. Catatan: akun 2000/2300 sudah punya baris lama (hasil input manual "bayar pinjaman/shopeepaylater" lewat Finance Operational, bernilai positif), jadi saldo COA = baris lama + sisa hutang dari menu ini.
- Kode: `Crud_hutang`, `Hutang_model`, `backend/v_hutang.php`, `v_hutang_detail.php`, `partial_hutang_form.php`, `partial_hutang_script.php`; route `hutang/*` di `routes.php`; menu `hutang` di `config/hak_akses.php` (levels 1,2,3, grant 3) + sidebar. Aksi tulis semua POST + CSRF; nominal divalidasi (> 0, maks Rp 2 miliar karena kolom `int`), pembayaran tidak boleh melebihi sisa, pokok tidak boleh diedit di bawah total yang sudah dibayar.
- **Migrasi wajib di production**: `db/2026-11-12_hutang.sql` (tabel `hutang`, `hutang_pembayaran`, dan menambah menu `hutang` ke level yang SUDAH punya pengaturan Hak Akses + menu `coa`, lewat tabel `hak_akses_level` — tabel yang dibaca aplikasi; `hak_akses_menu` itu desain lama yang tidak dipakai). Tanpa migrasi menu Hutang error "table doesn't exist". Level yang belum diatur otomatis dapat menu ini lewat hak dasar (Developer/Administrator/Staff Accounting); level lain bisa mencentang "Hutang" di halaman Hak Akses.
- Perbaikan hasil review independen: pembayaran/edit pokok memakai transaksi + `SELECT ... FOR UPDATE` pada baris hutang dan mengecek ulang sisa di dalam transaksi (dua orang membayar bersamaan tidak bisa melebihi sisa); teks dipotong dengan `mb_substr` (aman untuk huruf non-ASCII) dan keterangan dibatasi 2000 karakter; kalau validasi gagal, isian form tidak hilang (flashdata `old`, form terbuka lagi).
- Diuji lewat curl dengan user sementara level 3 dan 4: tambah/edit/bayar/hapus, semua validasi, CSRF (403), level 4 ditolak, XSS ter-escape, saldo COA 2000/2300 benar termasuk nominal negatif di halaman detail COA, 6 pembayaran bersamaan ke hutang dengan sisa 1x bayar -> hanya 1 yang masuk.

---

## 2026-11-13 — Menu Modal (Fin & Acc)

- Menu baru **Fin & Acc → Modal** (`/modal`) untuk setoran modal pemilik/investor, saldo awal modal, dan penarikan modal/deviden. Ringkasan: Modal Bersih = setoran + saldo awal − penarikan.
- **Pencatatan ke COA** (tabel `accounting`, satu baris per data, semua nominal positif di akunnya): Setoran Modal -> **3000 Modal**; Saldo Awal Modal -> **3100 Opening Balance Equity**; Penarikan Modal / Deviden -> **3200.02 Deviden**. Edit yang mengganti jenis memindahkan barisnya ke akun yang sesuai; hapus ikut menghapus barisnya di COA. Akun 320001 Retained Earning tidak dipakai (laba ditahan, bukan input manual).
- Kode: `Crud_modal`, `Modal_model`, `backend/v_modal.php` (daftar + form tambah/edit dalam satu halaman); route `modal/*`; menu `modal` di `config/hak_akses.php` (levels 1,2,3, grant 3) + sidebar. Aksi tulis POST + CSRF, nominal > 0 dan maks Rp 2 miliar, tanggal divalidasi, isian form tidak hilang saat validasi gagal.
- **Migrasi wajib di production**: `db/2026-11-13_modal.sql` (tabel `modal` + menu `modal` ke `hak_akses_level` untuk level yang sudah punya menu `coa`).
- Diuji lewat curl (user sementara level 3 & 4): tiga jenis masuk ke akun yang benar, edit pindah akun, hapus sinkron ke COA, validasi, CSRF, level 4 ditolak, XSS, input UTF-8.
- **Perbaikan halaman COA (`coa/index.php`)**: akun 3200.02 Deviden tidak tampil di daftar COA karena akun induknya (3200) tidak ada di `operational_kategori` — baris ber-parent disembunyikan JS (`collapseAll`/`initTree`), jadi anak tanpa induk tidak pernah kelihatan (saldo Rp 50 jt "Tarik balik modal" ikut tersembunyi). Sekarang akun yang induknya tidak ada diperlakukan sebagai akun tingkat atas. Dicek: 0 baris yatim lain di 101 akun.

---

## 2026-11-13 — Laporan Keuangan (Fin & Acc)

- Menu baru **Fin & Acc → Laporan Keuangan** (`/laporan-keuangan`) untuk laporan ke investor / memantau progres bisnis, disusun otomatis dari data COA (tabel `accounting`). Hanya membaca, tidak mengubah data. Tab: **Ringkasan** (kalimat ringkasan otomatis, KPI, grafik bulanan, rasio, info operasional dari data Project), **Laba Rugi** (+ pembanding & ikhtisar bulanan), **Neraca**, **Arus Kas** (metode langsung), **Perubahan Ekuitas**, **Catatan & Kualitas Data**, **Laporan Lengkap** (semua tab, siap cetak/PDF). Filter periode (preset bulan ini/lalu, kuartal, tahun ini/lalu, semua data, atau tanggal bebas) + pembanding (periode sebelumnya / tahun lalu / tanpa). Ekspor Excel (`.xls` berisi tabel) dan Cetak/Simpan PDF (CSS print).
- **Cara menghitung** (rincian di komentar atas `Laporan_keuangan_model.php`): Pendapatan (4xxx) diakui saat invoice; biaya (5xxx/6xxx/7200) diakui saat dibayar. Piutang TIDAK dibaca dari akun 110302.xx (nominalnya tidak selalu ikut berkurang saat kwitansi dibayar; di data lokal 10 dari 29 baris tidak berkurang, +Rp 296,9 jt) melainkan dihitung per proyek: invoice berakun COA − kwitansi Paid proyek yang sama sampai tanggal laporan (`piutang_hitung()`). **Kas tidak dibaca langsung dari akun 1000** (COA hanya mencatat uang masuk) tetapi dihitung dari seluruh arus: kwitansi Paid + pendapatan lain + modal + pinjaman − biaya − uang muka/aset − cicilan − deviden. Liabilitas = akun 2000/2300; Ekuitas = Modal + Saldo Awal + laba kumulatif − Deviden. Utang vendor per event (210102.xx) hanya komitmen, tidak masuk Neraca (ditampilkan di Catatan sebagai "komitmen belum dibayar").
- **Selisih rekonsiliasi** di Neraca = (Kas akun COA + sisa Piutang hasil hitung) − Pendapatan COA: uang masuk di akun Kas yang tidak terhubung ke invoice berakun COA. Ditampilkan terbuka (bukan disembunyikan). Per 13 Nov 2026: Rp 15.080.000 (sebelum piutang dihitung ulang sempat Rp 311.953.500).
- **Baris biaya ganda**: `Project_model::insert_accounting` memakai `ON DUPLICATE KEY UPDATE` padahal tabel `accounting` tidak punya unique key selain `accounting_id`, jadi setiap EDIT transaksi Finance Project menambah baris baru (14 baris, Rp 19.627.500 di data lokal). Sudah diperbaiki jadi select-then-update, dan laporan mengabaikan baris biaya lama yang sudah digantikan baris terbaru pada transaksi yang sama. Di database LOKAL 14 baris lama (Rp 19.627.500) sudah dihapus pada 13 Nov 2026 setelah backup (`db/backup_accounting_sebelum_bersih_2026-11-13.sql`, gitignored) dan dicek: baris terbarunya sama dengan nominal di project_acc/operational_acc. **Di database PRODUCTION cek dulu apakah ada baris serupa** (query: baris biaya 5/6/7 yang punya baris lebih baru dengan accounting_id_session yang sama; tab Catatan di Laporan Keuangan menampilkan jumlahnya) lalu hapus setelah backup.
- **Kode Deviden**: akun di COA diubah dari `3200.02` menjadi `320002` (13 Nov 2026 13:44), tetapi `Crud_coa::update` tidak memindahkan baris `accounting` lama (1 baris Rp 50 jt masih berkode 3200.02, tidak muncul di halaman COA). Laporan mengenali kedua kode; menu Modal sekarang memposting Penarikan ke 320002 bila akun itu ada (`Modal_model::peta_akun()`). Di database lokal baris lama itu sudah dipindah ke 320002 (accounting + operational_acc). Di production, kalau kode akun Deviden juga diubah, jalankan: `UPDATE accounting SET accounting_nomer_kategori='320002' WHERE accounting_nomer_kategori='3200.02'; UPDATE operational_acc SET kategori='320002' WHERE kategori='3200.02';`.
- **Kualitas data**: pengkategorian invoice/kwitansi ke COA baru konsisten sejak ± April 2026; sebelumnya mayoritas belum dikategorikan. Tab Catatan menampilkan kelengkapan per bulan (invoice & kwitansi Paid yang sudah masuk COA vs semua di tabel payment), transaksi COA tanpa akun (termasuk kwitansi Pending), akun tak dikenal, komitmen vendor. Perbandingan/pertumbuhan otomatis ditandai "belum bermakna" bila periode pembanding < 90% lengkap.
- Kode: `Crud_laporan_keuangan`, `Laporan_keuangan_model`, `helpers/laporan_helper.php`, `backend/v_laporan_keuangan.php`, `v_laporan_keuangan_excel.php`, `backend/laporan/partial_lk_*.php`; route `laporan-keuangan` & `laporan-keuangan/export`; menu `laporan_keuangan` di `config/hak_akses.php` (levels 1,2,3, grant 3) + sidebar. Chart.js 4.4.1 dari cdnjs untuk grafik.
- **Migrasi di production**: `db/2026-11-13_laporan_keuangan.sql` (tidak ada tabel baru; hanya menambah menu ke level yang sudah punya pengaturan Hak Akses + menu `coa`).
- Diuji: semua tab tanpa error PHP; angka dicocokkan dengan perhitungan SQL manual (kas, selisih, laba bersih); kas akhir arus kas = kas Neraca dan ekuitas akhir = ekuitas Neraca di 5 periode berbeda; level 4 & tanpa login ditolak; parameter tanggal/tab/banding aneh aman; XSS ter-escape; ekspor Excel.

---

## 2026-11-14 — Stock Barang disempurnakan (menu Stock, `/supplies`)

- **Yang kurang di versi lama**: tidak ada kategori (kolom `type` isinya "Persediaan" untuk 94 dari 95 barang), total masuk/keluar per barang tidak ditampilkan, batas "menipis" dipatok ≤ 10 untuk semua barang, tidak ada tanggal transaksi/tujuan keluar/koreksi stok/ekspor, semua hapus lewat GET tanpa CSRF. Bug lama: `update()` menimpa SEMUA baris riwayat satu barang dengan angka yang sama, dan hapus→pulihkan menimpa jenis transaksi (Barang Masuk/Keluar) di riwayat menjadi `created`/`deleted`.
- **Fitur baru**: **kategori barang** (tab Kategori + pengisian awal otomatis berdasarkan kata kunci nama — 10 kategori, silakan dikoreksi; ubah kategori banyak barang sekaligus di Daftar Stok), **satuan** & **stok minimum per barang**, daftar stok dengan kolom **Masuk / Keluar / Stok / Status** (+ filter cari, kategori, status, urutan, dan periode untuk masuk/keluar), **tanggal transaksi** (boleh mundur, tidak boleh masa depan), **tujuan event/project** pada barang keluar, **Koreksi Stok (stock opname)**, **riwayat per barang** dengan saldo, ubah & batalkan pergerakan (saldo seluruh riwayat dihitung ulang), tab **Stok Menipis**, **Recycle Bin** yang tidak merusak riwayat, **ekspor Excel** (daftar stok + riwayat per periode).
- **Aturan data** (rinci di komentar atas `Supplies_model.php`): `supplies_stock` tetap buku besar pergerakan (goods_in > 0 masuk, goods_out < 0 keluar); `amount` = saldo berjalan yang SELALU dihitung ulang dari seluruh riwayat urut (tanggal, id) di dalam transaksi dengan row lock pada barang (`SELECT ... FOR UPDATE`). Perubahan yang membuat saldo minus di titik mana pun ditolak. Stok sekarang = jumlah semua pergerakan yang tidak dibatalkan; total masuk/keluar dipisah dari koreksi. Pergerakan dibatalkan tetap tampil (dicoret) tetapi tidak dihitung.
- **Migrasi wajib di production**: `db/2026-11-14_stock_barang.sql` (tabel `supplies_kategori`; kolom baru `supplies.kategori_id/satuan/stok_minimum`, `supplies_stock.tanggal/project_id_session`; tanggal data lama diisi dari `created_at`; kategori awal; stok minimum 0 untuk barang yang belum pernah keluar). Butuh MariaDB; aman diulang KECUALI baris terakhir (UPDATE stok_minimum) — jalankan sekali. Rute lama `supplies/editin|editout|edit2` dihapus; view lama `views/supplies/*` diganti `views/backend/stok/*`.
- **Perbaikan hasil review independen (21 temuan)**: angka desimal tidak lagi dibuang titiknya diam-diam ("2.5" dulu jadi 25; sekarang titik hanya boleh pemisah ribuan "1.500", lainnya ditolak); koreksi stok dihitung selisihnya DI DALAM transaksi terkunci (dan isian kosong tidak lagi dianggap stok fisik 0); ubah/batalkan pergerakan membaca ulang baris di bawah kunci dan menolak koreksi/baris kosong data lama; hapus permanen hanya untuk barang yang masih di Recycle Bin (guard sebelum menghapus riwayat); ekspor Excel kebal formula injection (teks diawali `= + - @` diberi tanda petik, juga di ekspor Laporan Keuangan) dengan kolom Koreksi terpisah; filter periode cukup salah satu tanggal; ubah kategori massal wajib memilih tujuan dan meminta konfirmasi untuk "kosongkan"; tabel `supplies`/`supplies_stock` dikonversi ke utf8mb4 (dulu latin1) dan `supplies.id_session` diberi index (tanpa index `FOR UPDATE` mengunci seluruh tabel).
- Diuji: recompute saldo cocok 100% dengan data lama (95 barang, 0 selisih); total masuk 1.603 / keluar 694 cocok dengan data mentah; semua halaman tanpa error; validasi input, saldo minus (termasuk pencatatan mundur), edit/batal/koreksi, recycle (jenis riwayat utuh), CSRF 403, XSS ter-escape, level 3 ditolak; 8 permintaan keluar serentak pada stok 15 → tepat 3 diterima. Tes juga menemukan & memperbaiki celah SQL (nama barang tanpa escape pada cek duplikat) dan kebocoran state query builder.

---

## 2026-11-14 — Laporan Keuangan: "Lihat detail" (asal angka)

- Tiap kartu di tab Ringkasan (Pendapatan, Laba Kotor, Laba Bersih, Uang Masuk, Estimasi Kas & Bank, Piutang Usaha, Liabilitas, Ekuitas) punya tombol **Lihat detail** yang membuka jendela (`<dialog>`) berisi rincian dari mana angka itu berasal: per akun COA, per project/klien, rumus (mis. 15 komponen kas), piutang per project (invoice − kwitansi Paid), liabilitas + rincian menu Hutang, komponen ekuitas. Di tiap akun ada **lihat transaksi** (dimuat saat diklik) yang menampilkan transaksi COA aslinya beserta **sumber**-nya (Invoice, Kwitansi, Finance Project, Finance Operational, Menu Modal, Menu Hutang, Cicilan Hutang, Utang vendor, Input langsung COA) dan project/klien terkait.
- Teknis: `Crud_laporan_keuangan::detail($kunci)` dan `::transaksi()` (GET, potongan HTML, dimuat lewat `fetch()`; akses level 1/2/3 seperti halaman induk; parameter divalidasi: kunci whitelist, kode/jenis regex); `Laporan_keuangan_model::transaksi_rinci()` (semua join 1:1 dengan `COLLATE`, tanpa GROUP BY; tabel modal/hutang dicek `table_exists`), `per_akun()`, `per_project()`, `piutang_rinci()`, `kwitansi_pending()`, `hutang_rinci()`; view `backend/laporan/v_lk_detail.php` & `v_lk_transaksi.php`; rute `laporan-keuangan/detail/(:any)` dan `laporan-keuangan/transaksi`. Daftar transaksi dibatasi 500 baris terbaru (total di header tetap seluruhnya).
- Diuji: total tiap rincian cocok persis dengan kartunya (8 kartu) dan total transaksi per akun/jenis cocok dengan total akun; level 4 & tanpa login ditolak, parameter aneh 400, kunci tak dikenal 404; jalan di browser sungguhan (buka, buka-tutup transaksi, ganti kartu) tanpa error konsol. Tidak ada migrasi database.

---

## Catatan Insiden (untuk konteks, bukan tindak lanjut)

- Sempat salah restore password test di DB lokal (`db_erpmaid`) beberapa
  kali selama sesi testing — semua sudah dipulihkan ke hash asli. Tidak
  berdampak ke data produksi (DB lokal only).
- Sempat tidak sadar working branch berpindah dari `security-hardening`
  ke `main` (karena PR sudah kamu merge via GitHub/GitHub Desktop di
  tengah sesi) — beberapa commit fitur akhirnya masuk langsung ke `main`.
  Dikonfirmasi ke kamu dan disetujui untuk lanjut kerja di `main`.
- Deploy Hostinger sempat gagal diam-diam berhari-hari (lihat bagian
  2026-08-08 di atas) — UI hPanel menampilkan "sukses" padahal
  `git pull`-nya di server gagal total. Pelajaran: jangan percaya status
  "sukses" itu, selalu cek log deploy aktualnya.

---

## Next Steps

- [x] ~~Jalankan migrasi `user_absensi.sql`, `pengaturan_absensi.sql`,
      `kategori_gaji.sql` di produksi~~ — dikonfirmasi sudah jalan di
      server (2026-08-19).
- [ ] **Jalankan migrasi `db/susunan_acara.sql` di database produksi**
      (bikin tabel `susunan_acara_kategori` & `susunan_acara`) — belum
      dijalankan di luar DB lokal. Tanpa ini menu Susunan Acara di server
      akan error "table doesn't exist". Cara jalanin: phpMyAdmin di
      hPanel → Import file itu (atau paste ke tab SQL) → jalankan.
- [ ] **Setelah migrasi di atas jalan, pastikan kode terbarunya juga
      sudah ke-deploy** (push GitHub Desktop → klik Deploy di Hostinger
      hPanel → **cek log deploy-nya, bukan cuma status "sukses"**, lihat
      catatan insiden 2026-08-08) — baru fitur Buku Tamu, Nama Janur, dan
      Susunan Acara bisa dites di server online.
- [ ] **PR manual security (belum dikerjakan, ditunda atas permintaan kamu):**
      rotate password DB produksi, set `CI_ENV=production` di server,
      opsional scrub password lama dari git history. Lihat
      `SECURITY_AUDIT.md` bagian "Wajib Dikerjakan Manual".
- [ ] **Bug terpisah yang sudah di-flag** (belum dikerjakan): `Aspanel.php`
      manggil beberapa fungsi `cek_session_akses_*` yang **tidak
      terdefinisi** (`cek_session_akses_admin`, `cek_session_akses_level_3/4/5`,
      dll) di method `profil`, `user_update`, `user_storage_bin`,
      `user_delete`, `identitaswebsite`, `logactivity` — kalau ke-trigger,
      PHP fatal error. Ada chip task terpisah untuk ini.
- [ ] **Verifikasi bisnis**: beberapa project ternyata punya lebih dari 2
      installment (Pembayaran Ketiga, Keempat — terlihat di kasus Sania
      Daffa). Syarat "achieved" saat ini cuma cek Kesatu & Kedua, tidak
      peduli Ketiga/Keempat ada/belum. Kalau ini bukan yang dimaksud
      (misal harusnya nunggu SEMUA installment lunas), perlu dikonfirmasi
      ulang aturan bisnisnya.
- [ ] **Testing menyeluruh di staging/produksi** sebelum benar-benar rilis
      — semua fix & fitur di atas baru diuji di DB lokal (`db_erpmaid`),
      belum pernah dites terhadap data produksi asli.
- [ ] Deploy ke server: pastikan env var (`DB_HOST/DB_USER/DB_PASS/DB_NAME`,
      `ENCRYPTION_KEY`, `APP_BASE_URL`, `CI_ENV`) sudah di-set di panel
      hosting sebelum `git pull` — lihat `.env.example`.
