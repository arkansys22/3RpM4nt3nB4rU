# ROADMAP.md

Rencana & pekerjaan yang masih tertunda untuk ERP Mantenbaru. Ini dokumen "ke depan" — untuk riwayat apa yang sudah dikerjakan & sudah dites, lihat `PROGRESS.md` (fitur dashboard/absensi/salary) dan `SECURITY_AUDIT.md` (hardening keamanan).

---

## 🔴 Blocker aktif — perlu ditindaklanjuti sebelum fitur baru dipakai beneran

- [x] ~~Route `/gaji-saya` & `/rekap-gaji/<user>/<periode>` belum ke-commit~~ — sudah di-commit (`2e1c97d`). Pastikan sudah di-push & di-pull ke server produksi.
- [ ] **Jalankan migrasi database di server produksi** — belum satupun dari ini pernah dijalankan di luar DB lokal (`db_erpmaid`):
  - `db/user_absensi.sql` — tabel absensi kehadiran (wajib untuk tombol Absensi & Rekap Absensi jalan)
  - `db/pengaturan_absensi.sql` — pengaturan jam masuk ketentuan
  - `db/kategori_gaji.sql` — tabel `kategori_gaji` + `user_kategori_gaji` (wajib untuk Setting Salary & Rekap Gaji Saya jalan)

  Tanpa ini, mengklik tombol Absensi/Rekap Gaji di server akan error "table doesn't exist", bukan 404 lagi.

## 🟠 Sudah di-flag, belum dikerjakan

- [ ] **Rotasi kredensial produksi** (ditunda atas permintaan sendiri saat security audit): ganti password DB produksi, set `CI_ENV=production` di server, opsional scrub password lama dari git history. Detail lengkap di `SECURITY_AUDIT.md` → "Wajib Dikerjakan Manual".
- [ ] **Bug lama di `Aspanel.php`**: beberapa method (`profil`, `user_update`, `user_storage_bin`, `user_delete`, `identitaswebsite`, `logactivity`) memanggil fungsi `cek_session_akses_*` yang **tidak terdefinisi** (mis. `cek_session_akses_admin`, `cek_session_akses_level_3/4/5`). Kalau ke-trigger, PHP fatal error. Belum diperbaiki — sudah ada task terpisah yang di-spawn untuk ini sebelumnya.
- [ ] **Verifikasi aturan bisnis "achieved"**: definisi project "achieved" saat ini cuma cek Pembayaran Kesatu + Kedua Paid. Beberapa project ternyata punya Pembayaran Ketiga/Keempat (ditemukan di kasus "Pernikahan Sania Daffa") yang tidak ikut dicek sama sekali. Perlu dikonfirmasi: apakah cukup Kesatu+Kedua, atau harus SEMUA installment lunas baru dianggap "achieved"? Ini memengaruhi 3 fitur sekaligus: estimasi revenue dashboard, top sales ranking, dan komisi persentase payroll — kalau aturannya berubah, ketiganya perlu diupdate bareng (lihat `CLAUDE-DEV.md` untuk daftar lokasi kodenya).
- [ ] **Testing menyeluruh di staging/produksi** — semua fitur di atas baru diuji di data lokal, belum pernah terhadap data produksi asli.

## 🟡 Ide pengembangan lanjutan (belum dikonfirmasi user — didiskusikan dulu sebelum dikerjakan)

Ini ekstensi wajar dari fitur yang sudah ada, dicatat supaya tidak hilang — bukan komitmen, tunggu keputusan sebelum mulai:

**Payroll / Rekap Gaji**
- Kunci ("lock") payroll bulan yang sudah "final" supaya angkanya tidak ikut berubah kalau ada data absensi/project/pembayaran yang diedit setelah bulan itu ditutup. Saat ini semua angka dihitung live setiap kali halaman dibuka — kalau ada koreksi data lama, angka payroll bulan lalu bisa diam-diam berubah.
- Export slip gaji per user per bulan ke PDF (infrastruktur PDF/dompdf sudah ada di project untuk fitur naskah, tinggal reuse).
- Halaman rekap gaji **semua user sekaligus** untuk satu bulan (bukan pilih-1-nama) — berguna buat finance yang mau lihat total payroll bulanan sebelum transfer, bukan cek orang per orang.
- Approval workflow: staff accounting hitung → admin/developer approve sebelum "final".

**Absensi**
- Radius/geofencing: tolak absen kalau titik koordinat GPS terlalu jauh dari lokasi kantor/venue yang ditentukan (saat ini titik koordinat cuma direkam, tidak divalidasi jaraknya).
- Laporan absensi bulanan bisa di-export (CSV/PDF) buat lampiran ke slip gaji Harian.
- Toleransi keterlambatan (mis. 15 menit pertama tidak dihitung terlambat) — saat ini "Terlambat" langsung terhitung dari menit pertama lewat jam ketentuan.

**Umum**
- Notifikasi (email/WhatsApp) saat payroll bulan baru siap dilihat, atau saat ada yang belum absen di jam tertentu.
- Bersihkan sisa-sisa dua tabel database lama (`db_erp` vs `db_erpmaid`) yang sempat bikin bingung saat development — pastikan cuma satu sumber kebenaran sebelum fitur baru terus ditambah di atasnya.

---

## Cara pakai dokumen ini

Centang item kalau sudah selesai & sudah dites (bukan cuma "sudah dikerjakan di lokal"). Kalau ada keputusan bisnis baru yang mengubah salah satu item di atas, catat keputusannya di sini juga supaya tidak perlu digali ulang dari riwayat chat.
