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

## Catatan Insiden (untuk konteks, bukan tindak lanjut)

- Sempat salah restore password test di DB lokal (`db_erpmaid`) beberapa
  kali selama sesi testing — semua sudah dipulihkan ke hash asli. Tidak
  berdampak ke data produksi (DB lokal only).
- Sempat tidak sadar working branch berpindah dari `security-hardening`
  ke `main` (karena PR sudah kamu merge via GitHub/GitHub Desktop di
  tengah sesi) — beberapa commit fitur akhirnya masuk langsung ke `main`.
  Dikonfirmasi ke kamu dan disetujui untuk lanjut kerja di `main`.

---

## Next Steps

- [ ] **Jalankan migrasi `db/user_absensi.sql`, `db/pengaturan_absensi.sql`,
      dan `db/kategori_gaji.sql` di database produksi** sebelum fitur
      Absensi & Rekap Gaji dipakai di server (tabel-tabelnya belum ada di
      produksi).
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
