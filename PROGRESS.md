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
