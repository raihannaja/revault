<?php
/* VIEW halaman daftar program.
   Data dari Controller: $situs, $judulHalaman, $deskripsiHalaman, $halamanAktif,
   $statistik, $semuaProgram, $programTampil, $opsiFilter, $filter */
require_once __DIR__ . '/helpers.php';
require __DIR__ . '/layout/header.php';
?>

  <main>
    <!-- Hero halaman -->
    <section class="mx-auto max-w-6xl px-5 pt-14 pb-10">
      <p class="inline-block rounded-full bg-sun/25 px-4 py-1 text-sm font-medium text-amber-900"><?= e($situs->getTagline()) ?></p>
      <h1 class="mt-4 font-display text-5xl sm:text-6xl font-bold leading-[1.05] text-forest"><?= e($judulHalaman) ?></h1>
      <p class="mt-5 max-w-xl text-lg text-ink/75"><?= e($deskripsiHalaman) ?></p>

      <dl class="mt-8 flex flex-wrap gap-x-10 gap-y-4">
        <div>
          <dt class="text-sm text-ink/60">Total program</dt>
          <dd class="font-display text-3xl font-bold"><?= $statistik['total'] ?></dd>
        </div>
        <div>
          <dt class="text-sm text-ink/60">Sedang berjalan</dt>
          <dd class="font-display text-3xl font-bold"><?= $statistik['berjalan'] ?></dd>
        </div>
        <div>
          <dt class="text-sm text-ink/60">Selesai</dt>
          <dd class="font-display text-3xl font-bold"><?= $statistik['selesai'] ?></dd>
        </div>
        <div>
          <dt class="text-sm text-ink/60">Barang tersalurkan</dt>
          <dd class="font-display text-3xl font-bold"><?= $statistik['tercapai'] ?> <span
              class="text-base font-medium text-ink/60">dari <?= $statistik['target'] ?> (<?= $statistik['persen'] ?>%)</span></dd>
        </div>
      </dl>
    </section>

    <!-- TASK 2: tabel ringkasan, penomoran dari loop -->
    <section class="bg-white border-y border-forest/10" aria-labelledby="ringkasanTitle">
      <div class="mx-auto max-w-6xl px-5 py-12">
        <h2 id="ringkasanTitle" class="font-display text-2xl font-bold text-forest">Ringkasan program</h2>
        <div class="mt-5 overflow-x-auto rounded-2xl border border-forest/10">
          <table class="w-full min-w-[640px] text-left text-sm">
            <thead class="bg-forest-soft text-forest">
              <tr>
                <th class="px-4 py-3 font-medium">No</th>
                <th class="px-4 py-3 font-medium">Program</th>
                <th class="px-4 py-3 font-medium">Kategori</th>
                <th class="px-4 py-3 font-medium">Progres</th>
                <th class="px-4 py-3 font-medium">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($semuaProgram as $p): [$kelasBadge, $labelBadge, $kelasBar] = $p->getBadge(); ?>
                <tr class="border-t border-forest/10">
                  <td class="px-4 py-3 font-display font-bold text-forest"><?= e($p->getNomor()) ?></td>
                  <td class="px-4 py-3 font-medium"><a href="program.php?id=<?= $p->getId() ?>"
                      class="hover:text-forest hover:underline"><?= e($p->getNama()) ?></a></td>
                  <td class="px-4 py-3 text-ink/70"><?= e($p->getKategori()) ?></td>
                  <td class="px-4 py-3 text-ink/70"><?= $p->getTercapai() ?>/<?= $p->getTarget() ?> (<?= $p->getPersen() ?>%)</td>
                  <td class="px-4 py-3"><span
                      class="rounded-full px-3 py-1 text-xs font-medium <?= e($kelasBadge) ?>"><?= e($labelBadge) ?></span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- TASK 3: grid card dinamis dari server -->
    <section id="program" class="mx-auto max-w-6xl px-5 py-14" aria-labelledby="programTitle">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <h2 id="programTitle" class="font-display text-3xl font-bold text-forest">Daftar program prioritas</h2>
          <p class="mt-1 text-ink/65">Menampilkan <?= count($programTampil) ?> dari <?= $statistik['total'] ?> program
            (<?= e($opsiFilter[$filter]) ?>).</p>
        </div>
        <nav class="flex flex-wrap gap-2 text-sm" aria-label="Filter status">
          <?php foreach ($opsiFilter as $kunci => $label): ?>
            <a href="program.php?status=<?= e($kunci) ?>#program"
              class="focus-ring rounded-full px-4 py-1.5 font-medium transition <?= $kunci === $filter ? 'bg-forest text-white' : 'bg-forest-soft text-forest hover:bg-forest hover:text-white' ?>"
              <?= $kunci === $filter ? 'aria-current="true"' : '' ?>><?= e($label) ?></a>
          <?php endforeach; ?>
        </nav>
      </div>

      <?php if (count($programTampil) === 0): ?>
        <div class="mt-8 rounded-2xl border border-dashed border-forest/30 p-10 text-center">
          <p class="font-medium">Belum ada program dengan status ini.</p>
          <a href="program.php#program"
            class="focus-ring mt-4 inline-block rounded-full bg-forest px-5 py-2 text-sm text-white">Tampilkan semua</a>
        </div>
      <?php else: ?>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <?php foreach ($programTampil as $p): ?>
            <?php require __DIR__ . '/partials/program-card.php'; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>

<?php require __DIR__ . '/layout/footer.php'; ?>
