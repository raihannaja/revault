<?php
/* VIEW halaman detail satu program.
   Data dari Controller: $situs, $judulHalaman, $deskripsiHalaman, $halamanAktif, $p (object Program) */
require_once __DIR__ . '/helpers.php';
require __DIR__ . '/layout/header.php';
[$kelasBadge, $labelBadge, $kelasBar] = $p->getBadge();
?>

  <main>
    <section class="mx-auto max-w-5xl px-5 py-12">
      <a href="program.php#program" class="focus-ring inline-flex items-center gap-1 rounded text-sm font-medium text-forest hover:underline">&larr;
        Kembali ke daftar program</a>

      <article class="mt-6 overflow-hidden rounded-2xl border border-forest/10 bg-white md:grid md:grid-cols-2">
        <div class="relative">
          <img src="<?= e($p->getFoto()) ?>" alt="Foto program <?= e($p->getNama()) ?>"
            class="h-72 w-full bg-forest-soft object-cover md:h-full" onerror="this.style.opacity=0">
          <span class="absolute left-4 top-4 grid h-11 w-11 place-items-center rounded-full bg-sun font-display font-bold text-ink shadow"><?= e($p->getNomor()) ?></span>
          <span class="absolute right-4 top-4 rounded-full px-3 py-1 text-xs font-medium shadow <?= e($kelasBadge) ?>"><?= e($labelBadge) ?></span>
        </div>

        <div class="flex flex-col p-7">
          <span class="self-start rounded-full bg-forest-soft px-3 py-1 text-xs text-forest"><?= e($p->getKategori()) ?></span>
          <h1 class="mt-3 font-display text-3xl font-bold text-forest"><?= e($p->getNama()) ?></h1>
          <p class="mt-1 text-sm text-ink/60"><?= e($p->getProfile()) ?></p>
          <p class="mt-4 text-ink/80"><?= e($p->getDeskripsi()) ?></p>

          <div class="mt-6">
            <div class="flex justify-between text-sm text-ink/60">
              <span><?= $p->getTercapai() ?> dari <?= $p->getTarget() ?> barang</span>
              <span><?= $p->getPersen() ?>%</span>
            </div>
            <div class="mt-1 h-2.5 overflow-hidden rounded-full bg-forest-soft" role="progressbar"
              aria-valuenow="<?= $p->getPersen() ?>" aria-valuemin="0" aria-valuemax="100"
              aria-label="Progres <?= e($p->getNama()) ?>">
              <div class="h-full rounded-full <?= e($kelasBar) ?>" style="width: <?= $p->getPersen() ?>%"></div>
            </div>
            <?php if ($p->getStatus() === 'selesai'): ?>
              <p class="mt-1 text-sm text-forest">Target tercapai.</p>
            <?php else: ?>
              <p class="mt-1 text-sm text-ink/60">Kurang <?= $p->getSisa() ?> barang lagi.</p>
            <?php endif; ?>
          </div>

          <h2 class="mt-6 font-display text-lg font-bold">Tahap kerja</h2>
          <ol class="mt-2 flex-1 space-y-2 text-sm text-ink/80">
            <?php foreach ($p->getTahap() as $i => $tahap): ?>
              <li class="flex items-center gap-2">
                <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-forest-soft text-xs font-medium text-forest"><?= $i + 1 ?></span>
                <?= e($tahap) ?>
              </li>
            <?php endforeach; ?>
          </ol>

          <div class="mt-6 flex flex-wrap gap-2">
            <a href="index.html#barang"
              class="focus-ring rounded-full bg-forest px-5 py-2.5 text-sm font-medium text-white transition hover:bg-forest-dark">Lihat
              barang</a>
            <a href="<?= e($p->getWaLink($situs->getNama())) ?>" target="_blank" rel="noopener"
              class="focus-ring inline-flex items-center gap-2 rounded-full border border-forest px-4 py-2.5 text-sm font-medium text-forest transition hover:bg-forest-soft"><?= WA_ICON ?>
              Hubungi <?= e($p->getPenanggungNama()) ?></a>
          </div>
        </div>
      </article>
    </section>
  </main>

<?php require __DIR__ . '/layout/footer.php'; ?>
