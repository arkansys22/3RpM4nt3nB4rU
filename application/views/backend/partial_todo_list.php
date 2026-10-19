<?php
// Widget "To Do List" dashboard. Tiap kategori dihitung otomatis dari data
// project mendatang (lihat Todo_model): vendor belum diketahui, data
// pengantin belum lengkap, dan project yang belum punya crew.
if (!in_array($this->session->level, ['1', '2', '3', '4', '9'])) { return; }
$CI =& get_instance(); // di view, $this adalah loader -- model hanya terjangkau lewat instance controller
$CI->load->model('Todo_model');

$edit_pengantin = ($this->session->level == '9') ? 'clients/lihat/' : 'clients/edit/';
$edit_agenda = ($this->session->level == '9') ? 'project/lihat/' : 'agenda/edit/';
$todo_kategori = [
    [
        'judul' => 'Vendor belum diketahui / belum ada',
        'kosong' => 'Semua project mendatang sudah punya vendor yang diketahui.',
        'baris' => array_map(function ($t) {
            return ['row' => $t, 'badge' => (int) $t->total === 0 ? 'Belum ada vendor' : (int) $t->jumlah . ' vendor', 'info' => (int) $t->total === 0 ? null : 'Belum diketahui: ' . (int) $t->jumlah . ' dari ' . (int) $t->total . ' vendor', 'url' => 'project/lihat/' . $t->id_session];
        }, $CI->Todo_model->vendor_belum_diketahui()),
    ],
    [
        'judul' => 'Data pengantin belum lengkap',
        'kosong' => 'Data pengantin di project mendatang sudah lengkap.',
        'baris' => array_map(function ($t) use ($edit_pengantin) {
            return ['row' => $t, 'badge' => count($t->kurang) . ' isian', 'info' => 'Kurang: ' . implode(', ', $t->kurang), 'url' => $edit_pengantin . $t->id_session];
        }, $CI->Todo_model->pengantin_belum_lengkap()),
    ],
    [
        'judul' => 'Crew event masih kosong',
        'kosong' => 'Semua project mendatang sudah punya crew.',
        'baris' => array_map(function ($t) {
            return ['row' => $t, 'badge' => 'Belum ada crew', 'info' => null, 'url' => 'project/lihat/' . $t->id_session];
        }, $CI->Todo_model->project_tanpa_crew()),
    ],
    [
        'judul' => 'Agenda belum terisi',
        'kosong' => 'Agenda di project mendatang sudah terisi lengkap.',
        'baris' => array_map(function ($t) use ($edit_agenda) {
            return ['row' => $t, 'badge' => count($t->kurang) . ' agenda', 'info' => 'Kurang: ' . implode(', ', $t->kurang), 'url' => $edit_agenda . $t->id_session];
        }, $CI->Todo_model->agenda_belum_terisi()),
    ],
];
$total_tugas = 0;
foreach ($todo_kategori as $k) { $total_tugas += count($k['baris']); }
?>
<div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">
  <div class="flex items-center justify-between mb-4">
    <h4 class="text-xl font-bold">To Do List</h4>
    <?php if ($total_tugas > 0): ?>
      <span class="rounded-full bg-danger px-3 py-1 text-xs font-semibold text-white"><?= $total_tugas ?> tugas</span>
    <?php endif; ?>
  </div>

  <div class="space-y-5">
  <?php foreach ($todo_kategori as $kat): ?>
    <?php if (empty($kat['baris'])): ?>
    <div>
      <p class="mb-1 text-sm font-semibold"><?= $kat['judul'] ?></p>
      <p class="text-sm text-success"><?= $kat['kosong'] ?></p>
    </div>
    <?php else: ?>
    <details class="todo-kategori rounded-md border border-stroke dark:border-strokedark">
      <summary class="flex cursor-pointer select-none items-center justify-between gap-3 px-3 py-2 text-sm font-semibold">
        <span><?= $kat['judul'] ?>
          <span class="font-normal text-body dark:text-bodydark">(<?= count($kat['baris']) ?> project)</span>
        </span>
        <svg class="todo-chevron h-4 w-4 flex-shrink-0 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
      </summary>
      <div class="max-h-80 overflow-y-auto px-3 pb-2">
        <div class="divide-y divide-stroke dark:divide-strokedark">
          <?php foreach ($kat['baris'] as $b): $t = $b['row']; ?>
            <a href="<?= site_url($b['url']) ?>" class="flex items-center justify-between gap-3 py-2 hover:bg-whiter dark:hover:bg-meta-4">
              <div class="min-w-0">
                <p class="truncate text-sm font-medium"><?= htmlspecialchars($t->project_name) ?></p>
                <p class="text-xs text-body dark:text-bodydark"><?= htmlspecialchars($t->client_name) ?> &bull; <?= tgl_indo($t->event_date) ?></p>
                <?php if ($b['info']): ?>
                  <p class="text-xs text-body dark:text-bodydark"><?= htmlspecialchars($b['info']) ?></p>
                <?php endif; ?>
              </div>
              <span class="flex-shrink-0 rounded-full bg-warning bg-opacity-20 px-3 py-1 text-xs font-semibold text-warning"><?= htmlspecialchars($b['badge']) ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </details>
    <?php endif; ?>
  <?php endforeach; ?>
  </div>
  <style>
    details.todo-kategori > summary { list-style: none; }
    details.todo-kategori > summary::-webkit-details-marker { display: none; }
    details.todo-kategori[open] .todo-chevron { transform: rotate(180deg); }
  </style>
</div>
