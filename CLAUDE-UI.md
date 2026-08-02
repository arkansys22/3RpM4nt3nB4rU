# CLAUDE-UI.md

Frontend/view conventions for `application/views/backend/*.php` — the current page style. Read `CLAUDE.md` first for where this fits in the wider architecture.

## Page skeleton

Every page in this style follows the same shell — copy an existing one (`v_absensi.php` or `v_rekap_gaji.php` are good recent references) rather than building from scratch:

```
<!DOCTYPE html> ... <title>Page Title</title>
  Tailwind CDN script, style.css link
<body x-data="{ darkMode: ..., ... }" x-init="... reads darkMode from localStorage ...">
  Preloader div (x-show="loaded")
  Page wrapper: sidebar partial + (header partial + <main>)
    <main> → mx-auto max-w-screen-2xl → grid grid-cols-12 → one col-span-12 card
      <div class="rounded-sm border ... bg-white dark:bg-boxdark ...">  <!-- the actual content card -->
  bundle.js script
</body>
```

Dark mode is Alpine-driven (`darkMode` boolean persisted to `localStorage`), not a CSS media query — every custom color needs an explicit `dark:` variant or it'll look wrong in one mode.

## Flash messages

Standard block, copy verbatim at the top of the content card:

```php
<?php if ($this->session->flashdata('error')): ?>
  <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm"><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
  <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 text-sm"><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>
```

## Forms

Every POST form needs the CSRF hidden field (CSRF protection is on globally):

```php
<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
```

Destructive GET links (delete) use a plain `onclick="return confirm('...')"` — no JS modal library in use.

## Recurring interaction patterns (reuse, don't reinvent)

**Month-period browser** (`v_sales_ranking.php`, `v_absensi_rekap.php`, `v_gaji_saya.php`): prev/next arrow links computed server-side with `strtotime($periode . '-01 ±1 month')`, plus an `<input type="month">` whose `onchange` does a client-side redirect:
```php
onchange="window.location.href = '<?= site_url('some-route/') ?>' + this.value"
```

**"Pick a name first" pages** (`v_rekap_gaji.php` "Setting Salary"): a `<select>` of names with `onchange` navigating to `<route>/<id_session>`, and the page shows nothing but a prompt until a selection lands in the URL. Don't render every user's data up front "just in case" — this was a deliberate fix for a page that used to dump ~40 users' rows/cards at once and was unusable on mobile.

**Multi-select assignment**: native `<select multiple size="N">` + an explicit "Simpan" submit button (not auto-submit-on-change — multi-select needs a deliberate "done choosing" moment, unlike a single dropdown). Preselect existing values with `in_array((int)$k->id, $id_terpilih, true)`.

**Live camera capture** (`v_absensi.php`): a hidden-by-default modal (`#camera-modal`, toggled via `classList`), `getUserMedia` for the video stream, `navigator.geolocation.getCurrentPosition` for coordinates, both gated behind a readiness flag pair (`absensiKameraSiap` / `absensiLokasiSiap`) before the capture button appears — capturing before the video's `loadedmetadata` fires produces a blank frame, which was a real bug here once. The coordinate + timestamp + (optionally) a reverse-geocoded place name get drawn onto the `<canvas>` before `toDataURL()`, so they're burned into the photo pixels rather than stored as separate metadata that could get detached from the image. Reverse geocoding is OpenStreetMap Nominatim (free, no key), best-effort with a 6s timeout — never block the check-in flow on it succeeding.

## Responsive: table → cards, not table → horizontal-scroll

When a table has rich per-row content (forms, multi-selects, thumbnails) rather than short text, don't just wrap it in `overflow-x-auto` and call it responsive — that's unusable on a real phone. Instead render two versions of the same loop:

```php
<div class="sm:hidden space-y-4"> ... one <div> "card" per row, stacked ... </div>
<div class="hidden sm:block overflow-x-auto"> <table>...</table> </div>
```

Yes, this duplicates the row markup. It's worth it — this was retrofitted onto the salary/attendance recap pages after a user-reported screenshot showed the table version unusable at 318px wide. (The salary page later dropped this entirely by switching to "pick a name first" instead, which sidesteps the problem by never showing more than one entity's data at a time — prefer that shape for new pages over the dual-render trick when the page's purpose allows it.)

## Money & unit formatting

Use the shared helper instead of hand-rolling `number_format`:
```php
format_nominal_salary($nominal, $satuan)   // "Rp 3.000.000 / bulanan"  or  "2,5%" for Persentase
```
defined in `application/helpers/customs_helper.php` (autoloaded, no explicit `load->helper()` needed). Never sum raw nominals across mixed `satuan_gaji` values for a "total" — see `CLAUDE-DEV.md`'s payroll section.

Other autoloaded helpers worth knowing about: `hari($date)` (day-of-week name, Indonesian), `tgl_indo($datetime)` (Indonesian long date format), `terbilang($angka)` (number → Indonesian words, used for invoices).
