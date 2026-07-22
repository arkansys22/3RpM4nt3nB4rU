# SECURITY_AUDIT.md — Maid Mantenbaru (Wedding Organizer ERP)

> **Untuk agent (Claude Code) dan reviewer manusia.** Dokumen ini berisi hasil
> audit keamanan awal beserta rencana perbaikan bertahap. Baca bagian
> **"Aturan untuk Agent"** dulu sebelum menyentuh kode apa pun.

| | |
|---|---|
| **Aplikasi** | Maid Mantenbaru — Wedding Organizer ERP |
| **Framework** | CodeIgniter `3.1.13` (PHP, MVC) |
| **Skala** | 27 controller, 19 model, 154 view |
| **Status** | Legacy, sedang berjalan di **produksi** |
| **Tanggal audit** | 2026-07-20 |
| **Sumber** | Audit statik atas source (belum ada pengujian dinamis / pentest) |
| **Status branch** | ✅ **MERGED ke `main`** (2026-07-22) — Fase 1–3 selesai, Fase 4 di-skip (lihat "Status Fase 4"). Pekerjaan lanjutan di `main` sudah di luar cakupan dokumen ini, lihat `PROGRESS.md`. |

---

## Cara pakai dokumen ini

1. **Manusia** mengerjakan bagian **"Wajib Dikerjakan Manual"** (rotate kredensial,
   set environment) — ini di luar jangkauan agent dan **paling mendesak**.
2. **Agent** memverifikasi setiap temuan langsung ke kode (jangan percaya ringkasan
   ini bulat-bulat), lalu mengeksekusi **per-fase** sesuai "Rencana Eksekusi".
3. Setelah tiap fase, **agent berhenti dan menunggu review** sebelum lanjut.

---

## Aturan untuk Agent (WAJIB DIPATUHI)

- [ ] **Kerja di branch `security-hardening`. Jangan commit ke `main`.**
- [ ] **Verifikasi dulu, baru perbaiki.** Buka file & baris yang dirujuk, konfirmasi
      temuannya valid. Kalau ada yang meleset/salah, laporkan — jangan diam-diam
      ganti asumsi.
- [ ] **Kerja per-fase.** Selesai satu fase → stop → tunggu approval manusia.
- [ ] **JANGAN sentuh kredensial DB yang live** di `application/config/database.php`.
      Manusia yang me-rotate manual di panel hosting. Tugas agent hanya menyiapkan
      config yang membaca dari **environment variable**, bukan menaruh secret baru
      di file.
- [ ] **JANGAN menuliskan secret apa pun** (password, key, token) ke dalam file yang
      di-commit — termासuk ke dalam dokumen ini. Kalau butuh nilai contoh, pakai
      placeholder.
- [ ] **Dua fix berikut bisa bikin sistem tak bisa diakses** kalau dikerjakan naif —
      ikuti catatan "⚠️ Hati-hati" di masing-masing temuan **persis**:
      - CSRF (**SEC-03**) → semua form bisa error 403 kalau token tidak di-inject.
      - Migrasi hashing password (**SEC-05**) → semua user lama bisa terkunci login.
- [ ] Untuk tiap perubahan config yang bergantung HTTPS (cookie_secure, dll),
      **konfirmasi dulu ke manusia apakah server sudah HTTPS** sebelum mengaktifkan.

---

## Wajib Dikerjakan Manual (di luar jangkauan agent)

Ini **tidak boleh** dikerjakan agent dan **paling mendesak** — kerjakan paralel
dengan Fase 1 agent:

1. **Rotate password database SEKARANG.** Kredensial produksi (plaintext) sudah
   masuk ke history git dan ikut ter-bundle saat repo di-share. Ganti password DB
   di panel hosting (cPanel / Hostinger), lalu update nilainya lewat environment
   variable (lihat SEC-01), **bukan** dengan menaruh password baru di file.
2. **Paksa `ENVIRONMENT=production` di server.** Set `CI_ENV=production` — umumnya
   via `.htaccess` (`SetEnv CI_ENV production`) atau panel hosting. Ini yang
   mematikan tampilan error & `db_debug` di publik (lihat SEC-02).
3. **(Opsional, prioritas rendah setelah rotate)** Bersihkan password lama dari
   history git dengan `git filter-repo` atau BFG. Karena password sudah di-rotate,
   ini bukan darurat.

---

## Ringkasan Temuan

> **Catatan urutan:** Nomor severity ≠ urutan fase. **SEC-01 & SEC-02 adalah
> CRITICAL** tapi ditangani manusia di luar band (lihat di atas). Agent mulai dari
> fix yang aman-diotomasi dulu untuk membangun keyakinan, baru masuk ke perubahan
> berisiko (CSRF, password). Semua CRITICAL tetap ditangani **segera**, hanya oleh
> pihak yang berbeda.

| ID | Temuan | Severity | Penanggung jawab |
|----|--------|----------|------------------|
| SEC-01 | Kredensial DB plaintext ter-commit ke git | 🔴 CRITICAL | Manusia |
| SEC-02 | Aplikasi kemungkinan jalan mode `development` di produksi | 🔴 CRITICAL | Manusia |
| SEC-03 | CSRF protection dimatikan | 🟠 HIGH | Agent (Fase 2) |
| SEC-04 | Folder `db/` (dump SQL) bisa diakses dari web | 🟠 HIGH | Agent (Fase 1) |
| SEC-05 | Password di-hash `sha1()` tanpa salt | 🟠 HIGH | Agent (Fase 3) |
| SEC-06 | `encryption_key` kosong | 🟠 HIGH | Agent (Fase 1) |
| SEC-07 | Cookie flags tidak aman (`httponly`/`secure` FALSE) | 🟡 MEDIUM | Agent (Fase 1) |
| SEC-08 | SQL injection di helper auth (query tanpa binding) | 🟡 MEDIUM | Agent (Fase 1) |
| SEC-09 | `mysql_query()` usang di `Mylibrary.php` | 🟡 MEDIUM | Agent (Fase 1) |
| SEC-10 | `base_url` diambil dari `HTTP_HOST` | 🟡 MEDIUM | Agent (Fase 1) |
| SEC-11 | Housekeeping (uploads guard, logging, `.git/`, logika authz duplikat) | 🟢 LOW | Agent (Fase 1 & 4) |

---

## Detail Temuan

### SEC-01 — Kredensial DB plaintext ter-commit ke git 🔴

- **Lokasi:** `application/config/database.php` (key `'username'` & `'password'`);
  `.gitignore` (tidak meng-exclude file config ini).
- **Masalah:** Username & password database produksi tersimpan plaintext di file
  yang **masuk version control**. Password sudah ada di history git dan ikut
  tersebar saat repo di-zip/di-share.
- **Perbaikan:**
  1. **(Manusia)** Rotate password DB di panel hosting.
  2. **(Agent)** Ubah `database.php` agar membaca dari environment variable, mis.:
     ```php
     'username' => getenv('DB_USER') ?: '',
     'password' => getenv('DB_PASS') ?: '',
     'database' => getenv('DB_NAME') ?: '',
     ```
     Sediakan file contoh `.env.example` (tanpa nilai asli) dan pastikan file env
     asli masuk `.gitignore`.
- **⚠️ Hati-hati:** Jangan menaruh password baru di file mana pun yang di-commit.
- **Verifikasi:** `git grep` tidak lagi menemukan password literal di working tree;
  aplikasi tetap konek DB saat env var di-set.

---

### SEC-02 — Aplikasi kemungkinan jalan mode `development` di produksi 🔴

- **Lokasi:** `index.php:56` (default `ENVIRONMENT`), `index.php:66-70`
  (`error_reporting(-1)` + `display_errors = 1`), `application/config/database.php:85`
  (`'db_debug' => (ENVIRONMENT !== 'production')`).
- **Masalah:** `ENVIRONMENT` default ke `'development'` bila `CI_ENV` tak di-set. Di
  shared hosting hal ini umum → **stack trace penuh + error SQL bocor ke publik**
  (mengungkap struktur query & path server).
- **Perbaikan:** **(Manusia)** set `CI_ENV=production` di server. Agent boleh
  menambahkan komentar/dokumentasi di `index.php` yang menjelaskan cara set-nya,
  tapi **jangan hardcode** `ENVIRONMENT` ke production di kode (mempersulit dev
  lokal).
- **Verifikasi:** Di produksi, error tidak lagi tampil ke browser; `db_debug` mati.

---

### SEC-03 — CSRF protection dimatikan 🟠

- **Lokasi:** `application/config/config.php:465` → `$config['csrf_protection'] = FALSE;`
- **Masalah:** Semua form (finance, project, user, dll.) rentan Cross-Site Request
  Forgery — aksi bisa dipicu dari situs lain memakai sesi korban.
- **Perbaikan:**
  1. Set `csrf_protection = TRUE`.
  2. Ganti **semua** `<form ...>` HTML polos menjadi `form_open()` (helper CI yang
     otomatis menyuntik hidden field token CSRF). Cari kandidatnya:
     `grep -rn "<form" application/views`.
  3. Untuk request AJAX/`fetch`/jQuery, sertakan token CSRF di setiap POST.
- **⚠️ Hati-hati:**
  - Kalau CSRF diaktifkan tapi ada form yang **tidak** pakai `form_open()`, submit-nya
    langsung **HTTP 403** → fitur patah. **Wajib telusuri & konversi semua form dulu**,
    baru aktifkan.
  - CI3 secara default me-regenerate token tiap request (`csrf_regenerate = TRUE`).
    Ini bisa memecah beberapa AJAX yang jalan bersamaan. Pertimbangkan
    `csrf_regenerate = FALSE`, atau ambil token baru dari response tiap kali.
- **Verifikasi:** **Test manual setiap form** (create/edit di tiap modul) benar-benar
  ter-submit setelah perubahan. Ini fase yang paling perlu pengujian menyeluruh.

---

### SEC-04 — Folder `db/` bisa diakses dari web 🟠

- **Lokasi:** direktori `db/` (berisi `db_erp.sql`, `project.sql`, dll.) — tidak ada
  `index.html` maupun `.htaccess` di dalamnya. Root `.htaccess` melewatkan file yang
  benar-benar ada secara langsung (`RewriteCond %{REQUEST_FILENAME} -s/-l/-d` →
  passthrough).
- **Masalah:** `https://domain/db/db_erp.sql` kemungkinan besar bisa di-download →
  **bocor skema + data**.
- **Perbaikan (urut dari yang paling kuat):**
  1. **Pindahkan folder `db/` keluar dari webroot** (paling aman) — dump SQL tidak
     dilayani aplikasi, jadi memindahkannya tidak memecah fungsi.
  2. Bila belum bisa dipindah, tambahkan `db/.htaccess` berisi `Require all denied`
     (Apache 2.4) / `Deny from all` (2.2), dan pastikan dump tidak ikut di-deploy ke
     depan (tambahkan ke `.gitignore`).
- **Verifikasi:** Request langsung ke file `.sql` mengembalikan 403/404.

---

### SEC-05 — Password di-hash `sha1()` tanpa salt 🟠

- **Lokasi:** `application/controllers/User.php:45` (login), `:102` & `:113`
  (registrasi); `application/models/As_m.php:51` (`cek_login`).
- **Masalah:** SHA1 tanpa salt mudah di-crack via rainbow table bila DB bocor.
- **Perbaikan — pola migrasi-saat-login (WAJIB, jangan tukar langsung):**
  1. Saat login, ambil baris user berdasarkan `username`.
  2. Jika `password_verify($input, $stored)` **true** → sudah termigrasi, lanjut.
  3. Else jika `$stored === sha1($input)` → cocok dengan hash lama:
     **autentikasi berhasil**, lalu segera re-hash dan update baris:
     `password_hash($input, PASSWORD_DEFAULT)`.
  4. Else → gagal login.
  5. Registrasi & ganti-password memakai `password_hash()` sejak awal.
- **⚠️ Hati-hati:**
  - Kalau `sha1()` ditukar `password_hash()` **tanpa** langkah migrasi di atas,
    **semua user lama terkunci** (hash lama tak akan match).
  - **Cek lebar kolom password di DB.** Output `password_hash` (bcrypt) = **60
    karakter**; sha1 = 40. Jika kolomnya `VARCHAR(40)`/`CHAR(40)`, hash baru
    **terpotong** dan verifikasi gagal selamanya. Pastikan kolom minimal
    `VARCHAR(255)` (buat migration `ALTER TABLE` bila perlu).
- **Verifikasi:** User dengan hash lama masih bisa login **dan** baris-nya otomatis
  ter-upgrade ke bcrypt (cek panjang hash di DB setelah login). User baru langsung
  bcrypt.

---

### SEC-06 — `encryption_key` kosong 🟠

- **Lokasi:** `application/config/config.php:334` → `$config['encryption_key'] = '';`
- **Masalah:** Melemahkan Encryption library CI dan fitur terkait sesi/enkripsi.
- **Perbaikan:** Isi dengan key acak kuat (≥32 byte), **dibaca dari environment
  variable**, bukan hardcode. Bisa di-generate via `bin2hex(random_bytes(16))`.
- **⚠️ Hati-hati:** Mengisi key bisa meng-invalidasi sesi aktif → user perlu login
  ulang sekali. Dampak kecil, tapi info-kan.
- **Verifikasi:** Key ter-set dari env; aplikasi tetap berjalan.

---

### SEC-07 — Cookie flags tidak aman 🟡

- **Lokasi:** `application/config/config.php:419` (`cookie_secure = FALSE`), `:420`
  (`cookie_httponly = FALSE`).
- **Masalah:** `httponly` FALSE → cookie sesi bisa dicuri via XSS. `secure` FALSE →
  cookie dikirim lewat HTTP polos.
- **Perbaikan:**
  - `cookie_httponly = TRUE` → **selalu aman**, aktifkan.
  - `cookie_secure = TRUE` → **hanya jika server sudah HTTPS**.
- **⚠️ Hati-hati:** Meng-set `cookie_secure = TRUE` di situs **non-HTTPS** membuat
  cookie tak pernah ter-set → **login patah**. Konfirmasi status HTTPS ke manusia
  dulu.
- **Verifikasi:** Login tetap jalan; cookie punya flag `HttpOnly` (dan `Secure` bila
  HTTPS).

---

### SEC-08 — SQL injection di helper auth 🟡

- **Lokasi:** `application/helpers/customs_helper.php` — 12 fungsi
  `cek_session_akses_*` (baris fungsi: 2, 10, 18, 26, 34, 42, 50, 58, 66, 74, 82, 90),
  semuanya memakai `"... WHERE user.id_session='$id'"` tanpa escape. Pola serupa
  (sudah ter-escape, risiko lebih rendah) di `application/models/Crud_m.php:633`.
- **Masalah:** Interpolasi variabel mentah ke SQL. `$id` berasal dari session
  (server-side) sehingga risikonya lebih rendah, namun tetap harus dibereskan.
- **Perbaikan:** Ganti ke query binding, mis.:
  ```php
  $session = $ci->db->query(
      "SELECT 1 FROM user WHERE id_session = ?", [$id]
  )->num_rows();
  ```
  (Cukup `SELECT 1`, tidak perlu `SELECT *`, karena hanya menghitung baris.)
- **Verifikasi:** Fungsi auth berperilaku sama (redirect saat sesi tak valid),
  tidak ada interpolasi langsung tersisa.

---

### SEC-09 — `mysql_query()` usang di `Mylibrary.php` 🟡

- **Lokasi:** `application/libraries/Mylibrary.php:27` →
  `mysql_query("SELECT * FROM $tabel")`.
- **Masalah:** Ekstensi `mysql_*` **dihapus sejak PHP 7** → fatal error bila kode ini
  terpanggil di PHP modern. Selain itu `SELECT * FROM $tabel` mentah = SQLi bila
  `$tabel` bisa dipengaruhi input.
- **Perbaikan:** Cek dulu apakah fungsi ini dipakai
  (`grep -rn "Mylibrary" application/`). Jika **dead code** → hapus. Jika masih
  dipakai → port ke query builder CI (`$this->db->get(...)`) dengan whitelist nama
  tabel.
- **⚠️ Hati-hati:** **Pastikan benar-benar tak terpakai sebelum menghapus.**
- **Verifikasi:** Tidak ada referensi tersisa (jika dihapus), atau sudah memakai
  `$this->db` (jika dipertahankan).

---

### SEC-10 — `base_url` diambil dari `HTTP_HOST` 🟡

- **Lokasi:** `application/config/config.php:28` — `base_url` dibangun dari
  `$_SERVER['HTTP_HOST']`.
- **Masalah:** Rentan Host header injection (cache poisoning, link reset password
  yang dimanipulasi).
- **Perbaikan:** Set `base_url` eksplisit ke URL produksi yang diketahui (idealnya
  dari environment variable agar beda antara dev/staging/prod).
- **⚠️ Hati-hati:** Hardcode ke satu domain akan memecah akses via hostname lain
  (staging). Pakai env var bila ada banyak lingkungan.
- **Verifikasi:** Aplikasi menghasilkan URL absolut yang benar; mengganti `Host`
  header tidak mengubah link yang dihasilkan.

---

### SEC-11 — Housekeeping 🟢

- **`uploads/` tanpa guard** → tambahkan `uploads/index.html` (dan/atau `.htaccess`)
  untuk mencegah directory listing.
- **Logging mati** — `application/config/config.php:233` → `log_threshold = 0`. Naikkan
  ke level yang wajar (mis. `1` = error saja) agar ada jejak audit. Pastikan
  `application/logs/` tidak dapat diakses web.
- **`.git/` ikut ter-deploy** → jika ada di webroot, blokir akses `/.git/` via
  `.htaccess` (`RedirectMatch 404 /\.git`) agar source + history tak bisa diunduh.
- **Logika otorisasi duplikat** — pengecekan `level` **1–12** (koreksi: bukan 1–7)
  di-copy-paste di ~90-100 method di 16 controller (bukan cuma
  `Crud_project.php`). Bukan celah langsung, tapi rawan bug & sulit dipelihara.
  **Refactor ini masuk Fase 4 (opsional, terakhir) — lihat catatan status di
  bawah, di-skip setelah investigasi menunjukkan risikonya lebih tinggi dari
  perkiraan.**

---

## Yang Sudah Aman — Jangan Dirusak (regression guard)

Bagian ini **sudah benar**; jangan mengubahnya jadi lebih lemah saat menambal yang lain:

- **Upload file dibatasi ke tipe gambar** — `allowed_types` di
  `Crud_potensial_clients.php:1380`, `Crud_vendor.php:313`, `Crud_partner.php:96,422`,
  `Aspanel.php:408,513,632`. Pertahankan whitelist ini.
- **Login memakai `escape_str`** — `As_m.php:51` (`cek_login`). Saat parameterisasi,
  jangan malah menghilangkan proteksinya.
- **Mayoritas query memakai query builder / escape** — permukaan SQLi kecil.
  Pertahankan pola ini di kode baru.

---

## Rencana Eksekusi (per-fase)

> Setelah **setiap** fase: agent berhenti, ringkas perubahan, tunggu approval.

### Fase 0 — Manusia (paralel, segera)
- [ ] Rotate password DB di panel hosting (SEC-01).
- [ ] Set `CI_ENV=production` di server (SEC-02).

### Fase 1 — Agent: low-risk, tanpa memecah fungsi
- [ ] SEC-01: config baca dari env var + `.env.example` + update `.gitignore`.
- [ ] SEC-04: blokir/relokasi folder `db/`.
- [ ] SEC-06: isi `encryption_key` dari env var.
- [ ] SEC-07: `cookie_httponly = TRUE` (dan `cookie_secure = TRUE` **jika HTTPS
      dikonfirmasi**).
- [ ] SEC-08: parameterisasi 12 fungsi auth di `customs_helper.php`.
- [ ] SEC-10: `base_url` eksplisit via env var.
- [ ] SEC-09: verifikasi & hapus/port `mysql_query` di `Mylibrary.php`.
- [ ] SEC-11: guard `uploads/`, aktifkan logging, blokir `/.git/`.

### Fase 2 — Agent: CSRF (butuh pengujian menyeluruh)
- [ ] SEC-03: aktifkan CSRF → konversi semua form ke `form_open()` → tangani AJAX →
      **test tiap form**.

### Fase 3 — Agent: migrasi hashing password (butuh kehati-hatian auth)
- [ ] SEC-05: cek lebar kolom password → terapkan pola migrasi-saat-login → test
      login user lama & baru.

### Fase 4 — Agent: refactor (opsional, terakhir) — **DI-SKIP**
- [x] SEC-11 (authz): investigasi selesai, refactor **tidak dikerjakan**.
      Lihat "Status Fase 4" di bawah untuk alasan dan temuan tambahan.

---

## Status Fase 4 (2026-07-21) — di-skip, tidak direfactor

Investigasi sebelum eksekusi menunjukkan pola ini **tidak seragam** dan
sentralisasi mekanis berisiko mengubah siapa-boleh-akses-apa secara diam-diam:

- **Skala nyata**: ~409 titik pemanggilan 12 fungsi `cek_session_akses_*`, di
  ~90-100 method, 16 controller file (bukan cuma satu-dua contoh).
- **Bug otorisasi yang sudah ada, bukan cuma duplikasi**: beberapa method
  mengizinkan beberapa level di kondisi luar (`level=='1' OR level=='4'`
  dst.) tapi di dalamnya cuma manggil helper untuk SATU level tertentu
  (mis. selalu `cek_session_akses_developer`, khusus level 1) — ditemukan di
  `Crud_user.php` (`delete`, `recycle_bin`, `permanent_delete`) dan
  `Crud_finance_operational.php` (`lihat`, `edit`, `edit2`, `delete`,
  `permanent_delete`). Refactor otomatis akan mempertahankan atau
  memperbaiki bug ini tanpa sengaja — dua-duanya butuh keputusan
  kasus-per-kasus, bukan cocok untuk mechanical find-replace.
- **Bentuk tidak konsisten**: sebagian besar if/elseif+helper, tapi ada juga
  guard-clause+switch dan cek `session->level` langsung tanpa helper sama
  sekali (`Crud_clients.php::edit/update/delete`).
- **Beberapa method beda level dapat data/view yang beda pula** (bukan cuma
  beda gerbang akses), mis. `Crud_user::edit()` merender 3 view berbeda
  tergantung level — bukan checkbox on/off yang bisa disatukan.
- **Temuan baru, di luar cakupan SEC-11**: `Aspanel.php` memanggil fungsi
  `cek_session_akses(...)`, `cek_session_akses_admin(...)`,
  `cek_session_akses_level_3/4/5(...)` di beberapa method (`profil`,
  `user_update`, `user_storage_bin`, `user_delete`, `identitaswebsite`,
  `logactivity`) — nama-nama ini **tidak terdefinisi di manapun** di
  codebase. Kalau branch itu ke-trigger di produksi, PHP akan fatal error
  "Call to undefined function". Ini bug korektnes tersendiri, bukan bagian
  refactor duplikasi — perlu investigasi/fix terpisah.

**Keputusan (dikonfirmasi manusia, 2026-07-21):** skip refactor Fase 4.
Fase 1–3 (semua temuan CRITICAL/HIGH/MEDIUM yang sesungguhnya) sudah selesai
dan diverifikasi. Bug `Aspanel.php` di atas belum diperbaiki — perlu
ditindaklanjuti sebagai item terpisah kalau/ketika mau dikerjakan.

---

## Checklist Ringkas

```
MANUSIA (segera)
  [ ] Rotate password DB
  [ ] Set CI_ENV=production
  [ ] (opsional) Scrub git history

AGENT — Fase 1 (aman)
  [ ] Config → env var (SEC-01)
  [ ] Blokir folder db/ (SEC-04)
  [ ] encryption_key (SEC-06)
  [ ] Cookie flags (SEC-07)
  [ ] Parameterisasi helper auth (SEC-08)
  [ ] base_url eksplisit (SEC-10)
  [ ] Hapus/port mysql_query (SEC-09)
  [ ] Housekeeping: uploads/logging/.git (SEC-11)

AGENT — Fase 2 (hati-hati)
  [ ] CSRF + form_open + test semua form (SEC-03)

AGENT — Fase 3 (hati-hati)
  [ ] Migrasi hashing password saat login (SEC-05)

AGENT — Fase 4 (opsional) — DI-SKIP, lihat "Status Fase 4"
  [x] Investigasi selesai; refactor tidak dikerjakan (risiko > manfaat)
  [ ] TODO terpisah: fix Aspanel.php manggil fungsi tak terdefinisi
```

---

*Audit ini statik dan tidak menggantikan penetration test. Setelah perbaikan,
disarankan pengujian dinamis pada lingkungan staging sebelum rilis ke produksi.*
