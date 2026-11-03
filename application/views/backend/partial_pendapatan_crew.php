<?php /* Kartu pendapatan crew (Total Semua Pendapatan, Pendapatan Bulan Ini, Potensi Event Selanjutnya) + popup detailnya.
   Dipakai dashboard Staff (v_home_staff), Staff Sales (v_home_salesmarketing) dan Staff Admin (v_home_admin); butuh variabel
   $pendapatan_crew dari Aspanel::home() (Gaji_model::get_ringkasan_pendapatan_crew). Tidak tampil kalau variabelnya tidak ada.
   Variabel $pendapatan_tampilan (SELALU diisi eksplisit saat load->view, karena CI menyimpan variabel antar pemanggilan):
     'kartu'  = tiga kartu + popupnya (dashboard Staff, tidak punya slider statistik)
     'slide'  = tiga <div class="swiper-slide"> bergaya "Pencapaian Revenue"; di-load DI DALAM .swiper-wrapper (Staff Sales/Admin)
     'dialog' = popup-popupnya saja; di-load DI LUAR slider (swiper memakai transform) */ ?>
		            <?php if (isset($pendapatan_crew)): $pc = $pendapatan_crew; $rp = function ($n) { return 'Rp ' . number_format($n, 0, ',', '.'); };
		            // Popup daftar event (<dialog> bawaan browser: Esc / klik di luar menutup, tanpa bergantung pada Alpine).
		            // $dengan_fee = true: tampilkan kolom Fee per event + baris total (rincian pendapatan).
		            $dialog_event = function ($id, $judul, $daftar, $dengan_fee, $atas = null, $judul_tabel = null) use ($rp) { ?>
		            <dialog id="<?= $id ?>" onclick="if (event.target === this) this.close()" class="m-auto w-[calc(100%-2rem)] max-w-3xl rounded-sm bg-transparent p-0 backdrop:bg-black/50">
		              <div class="max-h-[85vh] overflow-y-auto rounded-sm bg-white p-5 text-bodydark2 shadow-default dark:bg-boxdark">
		                <div class="mb-4 flex items-center justify-between">
		                  <h4 class="text-lg font-bold text-black dark:text-white"><?= htmlspecialchars($judul) ?><?= $atas === null ? ' (' . count($daftar) . ')' : '' ?></h4>
		                  <button type="button" onclick="this.closest('dialog').close()" class="text-2xl leading-none" aria-label="Tutup">&times;</button>
		                </div>
		                <?php if ($atas !== null) { $atas(); } ?>
		                <?php if ($judul_tabel !== null): ?><h5 class="mb-2 mt-4 font-bold text-black dark:text-white"><?= htmlspecialchars($judul_tabel) ?></h5><?php endif; ?>
		                <?php if (!$daftar): ?>
		                <p class="text-sm">Belum ada event yang mendapat fee.</p>
		                <?php else: ?>
		                <div class="overflow-x-auto">
		                  <table class="w-full table-auto text-left text-sm">
		                    <thead>
		                      <tr class="border-b border-stroke dark:border-strokedark">
		                        <th class="py-2 pr-3">Tanggal &amp; Lokasi</th>
		                        <th class="py-2 pr-3">Nama Pengantin</th>
		                        <th class="py-2 pr-3">Job Desc</th>
		                        <?php if ($dengan_fee): ?><th class="py-2 pr-3 text-right">Fee</th><?php endif; ?>
		                        <th class="py-2"></th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                      <?php $jumlah = 0; foreach ($daftar as $ev): $jumlah += $dengan_fee ? $ev->fee : 0; ?>
		                      <tr class="border-b border-stroke dark:border-strokedark">
		                        <td class="py-2 pr-3">
		                          <p><?= hari($ev->event_date) ?>, <?= date('d M Y', strtotime($ev->event_date)) ?></p>
		                          <?php if ($ev->location !== null && $ev->location !== ''): ?><p class="text-xs"><?= htmlspecialchars($ev->location) ?></p><?php endif; ?>
		                        </td>
		                        <td class="py-2 pr-3"><?= $ev->client_name !== null ? htmlspecialchars($ev->client_name) : '-' ?></td>
		                        <td class="py-2 pr-3"><?= $ev->role !== null && $ev->role !== '' ? htmlspecialchars($ev->role) : '-' ?></td>
		                        <?php if ($dengan_fee): ?><td class="whitespace-nowrap py-2 pr-3 text-right"><?= $rp($ev->fee) ?></td><?php endif; ?>
		                        <td class="py-2"><?php if ($ev->client_name !== null): ?><a href="<?= base_url('project/crew_lihat/' . $ev->project_id) ?>" class="rounded bg-yellow-500 px-2 py-1 text-white">Lihat</a><?php endif; ?></td>
		                      </tr>
		                      <?php endforeach; ?>
		                    </tbody>
		                    <?php if ($dengan_fee): ?>
		                    <tfoot>
		                      <tr>
		                        <td colspan="3" class="py-2 pr-3 text-right font-bold">Total</td>
		                        <td class="whitespace-nowrap py-2 pr-3 text-right font-bold"><?= $rp($jumlah) ?></td>
		                        <td></td>
		                      </tr>
		                    </tfoot>
		                    <?php endif; ?>
		                  </table>
		                </div>
		                <?php endif; ?>
		              </div>
		            </dialog>
		            <?php }; ?>
		            <?php
		            // Bagian atas popup "Total Semua Pendapatan": ringkasan gaji + event, lalu rincian gaji (rincian event menyusul).
		            $atas_total = function () use ($pc, $rp) { ?>
		              <div class="mb-2 rounded-sm border border-stroke p-3 text-sm dark:border-strokedark">
		                <div class="flex justify-between"><span>Pendapatan Gaji</span><span><?= $rp($pc->total_gaji) ?></span></div>
		                <div class="flex justify-between"><span>Pendapatan Total Event</span><span><?= $rp($pc->total_event) ?></span></div>
		                <div class="mt-1 flex justify-between border-t border-stroke pt-1 font-bold text-black dark:border-strokedark dark:text-white"><span>Total Semua Pendapatan</span><span><?= $rp($pc->total_semua) ?></span></div>
		              </div>
		              <h5 class="mb-2 mt-4 font-bold text-black dark:text-white">Pendapatan Gaji (<?= $pc->jumlah_gaji ?>)</h5>
		              <?php if (!$pc->gaji_semua): ?>
		              <p class="text-sm">Belum ada gaji atau komisi yang dicatat.</p>
		              <?php else: ?>
		              <div class="overflow-x-auto">
		                <table class="w-full table-auto text-left text-sm">
		                  <thead>
		                    <tr class="border-b border-stroke dark:border-strokedark">
		                      <th class="py-2 pr-3">Tanggal</th>
		                      <th class="py-2 pr-3">Keterangan</th>
		                      <th class="py-2 pr-3">Jenis</th>
		                      <th class="py-2 pr-3 text-right">Nominal</th>
		                    </tr>
		                  </thead>
		                  <tbody>
		                    <?php foreach ($pc->gaji_semua as $g): ?>
		                    <tr class="border-b border-stroke dark:border-strokedark">
		                      <td class="whitespace-nowrap py-2 pr-3"><?= date('d M Y', strtotime($g->tanggal_transaksi)) ?></td>
		                      <td class="py-2 pr-3"><?= htmlspecialchars($g->nama_transaksi) ?></td>
		                      <td class="py-2 pr-3"><?= $g->jenis ?></td>
		                      <td class="whitespace-nowrap py-2 pr-3 text-right"><?= $rp($g->nominal) ?></td>
		                    </tr>
		                    <?php endforeach; ?>
		                  </tbody>
		                  <tfoot>
		                    <tr>
		                      <td colspan="3" class="py-2 pr-3 text-right font-bold">Total</td>
		                      <td class="whitespace-nowrap py-2 pr-3 text-right font-bold"><?= $rp($pc->total_gaji) ?></td>
		                    </tr>
		                  </tfoot>
		                </table>
		              </div>
		              <?php endif; ?>
		            <?php }; ?>
			            <?php
			            $mode = isset($pendapatan_tampilan) ? $pendapatan_tampilan : 'kartu';
			            // Tombol "Lihat detail" dan popupnya hanya ada kalau ada isi yang bisa dirinci
			            $ada_total = $pc->jumlah_event_semua > 0 || $pc->jumlah_gaji > 0;
			            $ada_bulan = $pc->jumlah_event_bulan_ini > 0;
			            $ada_depan = $pc->jumlah_event_mendatang > 0;
			            $semua_dialog = function () use ($pc, $dialog_event, $atas_total, $ada_total, $ada_bulan, $ada_depan) {
			                if ($ada_total) { $dialog_event('dialogEventSemua', 'Total Semua Pendapatan', $pc->event_semua, true, $atas_total, 'Pendapatan Total Event (' . $pc->jumlah_event_semua . ')'); }
			                if ($ada_bulan) { $dialog_event('dialogEventBulanIni', 'Pendapatan Bulan Ini', $pc->event_bulan_ini, true); }
			                if ($ada_depan) { $dialog_event('dialogEventMendatang', 'Event yang Sudah Dijadwalkan', $pc->event_mendatang, false); }
			            };
			            ?>
			            <?php if ($mode === 'dialog'): ?>
			            <?php $semua_dialog(); ?>
			            <?php elseif ($mode === 'slide'): ?>
			            <!-- ====== Pendapatan Crew (slide) Start -->
			            <div class="swiper-slide border-r border-stroke px-10 last:border-r-0 dark:border-strokedark">
			              <div class="flex items-center justify-between">
			                <div class="flex items-center gap-2.5">
			                  <h4 class="text-xl font-bold">Total Semua Pendapatan</h4>
			                </div>
			              </div>
			              <div class="mt-5.5 flex flex-col gap-1.5">
			                <div class="flex items-center justify-between gap-1">
			                  <p class="text-sm font-medium">Gaji</p>
			                  <p class="text-sm font-medium"><?= $rp($pc->total_gaji) ?></p>
			                </div>
			                <div class="flex items-center justify-between gap-1">
			                  <p class="text-sm font-medium">Event</p>
			                  <p class="text-sm font-medium"><?= $rp($pc->total_event) ?></p>
			                </div>
			                <div class="flex items-center justify-between gap-1">
			                  <p class="text-sm font-medium">Total</p>
			                  <p class="text-sm font-medium"><?= $rp($pc->total_semua) ?></p>
			                </div>
			                <?php if ($ada_total): ?>
			                <div class="flex items-center justify-between gap-1">
			                  <p class="text-sm font-medium">
			                    <a href="#" onclick="document.getElementById('dialogEventSemua').showModal(); return false;"><span class="text-sm font-medium text-primary">Lihat Detail &gt;&gt;</span></a>
			                  </p>
			                </div>
			                <?php endif; ?>
			              </div>
			            </div>
			            <div class="swiper-slide border-r border-stroke px-10 last:border-r-0 dark:border-strokedark">
			              <div class="flex items-center justify-between">
			                <div class="flex items-center gap-2.5">
			                  <h4 class="text-xl font-bold">Pendapatan Bulan Ini</h4>
			                </div>
			              </div>
			              <div class="mt-5.5 flex flex-col gap-1.5">
			                <div class="flex items-center justify-between gap-1">
			                  <p class="text-sm font-medium">Event</p>
			                  <p class="text-sm font-medium"><?= $pc->jumlah_event_bulan_ini ?> event</p>
			                </div>
			                <div class="flex items-center justify-between gap-1">
			                  <p class="text-sm font-medium">Pendapatan</p>
			                  <p class="text-sm font-medium"><?= $rp($pc->bulan_ini) ?></p>
			                </div>
			                <?php if (!$pc->ada_crew): ?>
			                <p class="text-xs">Akun belum terhubung ke data crew</p>
			                <?php endif; ?>
			                <?php if ($ada_bulan): ?>
			                <div class="flex items-center justify-between gap-1">
			                  <p class="text-sm font-medium">
			                    <a href="#" onclick="document.getElementById('dialogEventBulanIni').showModal(); return false;"><span class="text-sm font-medium text-primary">Lihat Detail &gt;&gt;</span></a>
			                  </p>
			                </div>
			                <?php endif; ?>
			              </div>
			            </div>
			            <div class="swiper-slide border-r border-stroke px-10 last:border-r-0 dark:border-strokedark">
			              <div class="flex items-center justify-between">
			                <div class="flex items-center gap-2.5">
			                  <h4 class="text-xl font-bold">Potensi Event Selanjutnya</h4>
			                </div>
			              </div>
			              <div class="mt-5.5 flex flex-col gap-1.5">
			                <div class="flex items-center justify-between gap-1">
			                  <p class="text-sm font-medium">Event Dijadwalkan</p>
			                  <p class="text-sm font-medium"><?= $pc->jumlah_event_mendatang ?> event</p>
			                </div>
			                <div class="flex items-center justify-between gap-1">
			                  <p class="text-sm font-medium">Terdekat</p>
			                  <p class="text-sm font-medium"><?= $ada_depan ? date('d M Y', strtotime($pc->event_mendatang[0]->event_date)) : '-' ?></p>
			                </div>
			                <?php if (!$pc->ada_crew): ?>
			                <p class="text-xs">Akun belum terhubung ke data crew</p>
			                <?php endif; ?>
			                <?php if ($ada_depan): ?>
			                <div class="flex items-center justify-between gap-1">
			                  <p class="text-sm font-medium">
			                    <a href="#" onclick="document.getElementById('dialogEventMendatang').showModal(); return false;"><span class="text-sm font-medium text-primary">Lihat Detail &gt;&gt;</span></a>
			                  </p>
			                </div>
			                <?php endif; ?>
			              </div>
			            </div>
			            <!-- ====== Pendapatan Crew (slide) End -->
			            <?php else: ?>
			            <!-- ====== Pendapatan Crew Start -->
			            <div class="col-span-12 grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6">
			              <div class="rounded-sm border border-stroke bg-white px-6 py-5 shadow-default dark:border-strokedark dark:bg-boxdark">
			                <p class="text-sm font-medium">Total Semua Pendapatan</p>
			                <h4 class="mt-2 text-title-md font-bold text-black dark:text-white"><?= $rp($pc->total_semua) ?></h4>
			                <p class="mt-1 text-xs">Gaji <?= $rp($pc->total_gaji) ?> + Event <?= $rp($pc->total_event) ?></p>
			                <?php if (!$pc->ada_crew): ?>
			                <p class="mt-1 text-xs">Akun belum terhubung ke data crew</p>
			                <?php endif; ?>
			                <?php if ($ada_total): ?>
			                <button type="button" onclick="document.getElementById('dialogEventSemua').showModal()" class="mt-2 text-xs font-medium text-primary hover:underline">Lihat detail &gt;&gt;</button>
			                <?php endif; ?>
			              </div>
			              <div class="rounded-sm border border-stroke bg-white px-6 py-5 shadow-default dark:border-strokedark dark:bg-boxdark">
			                <p class="text-sm font-medium">Pendapatan Bulan Ini</p>
			                <h4 class="mt-2 text-title-md font-bold text-black dark:text-white"><?= $rp($pc->bulan_ini) ?></h4>
			                <p class="mt-1 text-xs"><?= $pc->ada_crew ? 'Dari ' . $pc->jumlah_event_bulan_ini . ' event, ' . date('m/Y') : 'Akun belum terhubung ke data crew' ?></p>
			                <?php if ($ada_bulan): ?>
			                <button type="button" onclick="document.getElementById('dialogEventBulanIni').showModal()" class="mt-2 text-xs font-medium text-primary hover:underline">Lihat detail &gt;&gt;</button>
			                <?php endif; ?>
			              </div>
			              <div class="rounded-sm border border-stroke bg-white px-6 py-5 shadow-default dark:border-strokedark dark:bg-boxdark">
			                <p class="text-sm font-medium">Potensi Event Selanjutnya</p>
			                <h4 class="mt-2 text-title-md font-bold text-black dark:text-white"><?= $pc->jumlah_event_mendatang ?> event</h4>
			                <?php if (!$pc->ada_crew): ?>
			                <p class="mt-1 text-xs">Akun belum terhubung ke data crew</p>
			                <?php elseif ($ada_depan): ?>
			                <p class="mt-1 text-xs">Sudah dijadwalkan, terdekat <?= date('d M Y', strtotime($pc->event_mendatang[0]->event_date)) ?></p>
			                <button type="button" onclick="document.getElementById('dialogEventMendatang').showModal()" class="mt-2 text-xs font-medium text-primary hover:underline">Lihat detail &gt;&gt;</button>
			                <?php else: ?>
			                <p class="mt-1 text-xs">Belum ada event yang dijadwalkan</p>
			                <?php endif; ?>
			              </div>
			            </div>
			            <!-- ====== Pendapatan Crew End -->
			            <?php $semua_dialog(); ?>
			            <?php endif; ?>
			            <?php endif; ?>
