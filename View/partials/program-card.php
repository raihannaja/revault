<?php
/* VIEW partial: satu card program.
   Data dari Controller: $p (object Program), $situs (object Situs) */
[$kelasBadge, $labelBadge, $kelasBar] = $p->getBadge();
?>
      <article
        class="group flex flex-col overflow-hidden rounded-2xl border border-forest/10 bg-white transition duration-300 hover:-translate-y-1.5 hover:border-forest/30 hover:shadow-xl">
        <div class="relative overflow-hidden">
          <img src="<?= e($p->getFoto()) ?>" alt="Foto program <?= e($p->getNama()) ?>" loading="lazy"
            class="h-48 w-full object-cover bg-forest-soft transition duration-500 group-hover:scale-105"
            onerror="this.style.opacity=0">
          <span
            class="absolute left-3 top-3 grid h-10 w-10 place-items-center rounded-full bg-sun font-display font-bold text-ink shadow"><?= e($p->getNomor()) ?></span>
          <span
            class="absolute right-3 top-3 rounded-full px-3 py-1 text-xs font-medium shadow <?= e($kelasBadge) ?>"><?= e($labelBadge) ?></span>
        </div>

        <div class="flex flex-1 flex-col p-5">
          <div class="flex items-start justify-between gap-2">
            <h3 class="font-display text-xl font-bold"><a href="program.php?id=<?= $p->getId() ?>"
                class="focus-ring rounded hover:text-forest"><?= e($p->getNama()) ?></a></h3>
            <span
              class="shrink-0 rounded-full bg-forest-soft px-3 py-1 text-xs text-forest"><?= e($p->getKategori()) ?></span>
          </div>
          <p class="mt-1 text-sm text-ink/60"><?= e($p->getProfile()) ?></p>
          <p class="mt-3 text-sm text-ink/80"><?= e($p->getDeskripsi()) ?></p>

          <!-- progres -->
          <div class="mt-4">
            <div class="flex justify-between text-xs text-ink/60">
              <span><?= $p->getTercapai() ?> dari <?= $p->getTarget() ?> barang</span>
              <span><?= $p->getPersen() ?>%</span>
            </div>
            <div class="mt-1 h-2 overflow-hidden rounded-full bg-forest-soft" role="progressbar"
              aria-valuenow="<?= $p->getPersen() ?>" aria-valuemin="0" aria-valuemax="100"
              aria-label="Progres <?= e($p->getNama()) ?>">
              <div class="h-full rounded-full <?= e($kelasBar) ?>" style="width: <?= $p->getPersen() ?>%"></div>
            </div>
            <?php if ($p->getStatus() === 'selesai'): ?>
              <p class="mt-1 text-xs text-forest">Target tercapai.</p>
            <?php else: ?>
              <p class="mt-1 text-xs text-ink/60">Kurang <?= $p->getSisa() ?> barang lagi.</p>
            <?php endif; ?>
          </div>

          <!-- tahap kerja: loop bersarang dengan penomoran -->
          <ol class="mt-4 flex-1 space-y-1.5 text-sm text-ink/80">
            <?php foreach ($p->getTahap() as $i => $tahap): ?>
              <li class="flex items-center gap-2">
                <span
                  class="grid h-5 w-5 shrink-0 place-items-center rounded-full bg-forest-soft text-xs font-medium text-forest"><?= $i + 1 ?></span>
                <?= e($tahap) ?>
              </li>
            <?php endforeach; ?>
          </ol>

          <div class="mt-5 flex flex-wrap gap-2">
            <a href="index.html#barang"
              class="focus-ring rounded-full bg-forest px-5 py-2.5 text-sm font-medium text-white transition hover:bg-forest-dark">Lihat
              barang</a>
            <a href="<?= e($p->getWaLink($situs->getNama())) ?>"
              target="_blank" rel="noopener"
              class="focus-ring inline-flex items-center gap-2 rounded-full border border-forest px-4 py-2.5 text-sm font-medium text-forest transition hover:bg-forest-soft"><?= WA_ICON ?>
              Hubungi</a>
          </div>
        </div>
      </article>
