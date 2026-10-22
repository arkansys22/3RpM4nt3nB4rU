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
