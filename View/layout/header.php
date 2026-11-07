<?php
/* VIEW layout: kepala halaman + menu.
   Data dari Controller: $situs (object Situs), $judulHalaman, $deskripsiHalaman, $halamanAktif */
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= e($deskripsiHalaman) ?>">
  <title><?= e($judulHalaman) ?> - <?= e($situs->getNama()) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700&family=DM+Sans:wght@400;500;700&display=swap"
    rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="tailwind.config.js"></script>
  <link rel="stylesheet" href="style.css">
</head>

<body class="bg-mist text-ink font-sans antialiased">

  <!-- Header: sama dengan index.html, menu dibuat dengan loop -->
  <header class="sticky top-0 z-40 bg-mist/90 backdrop-blur border-b border-forest/10">
    <div class="mx-auto max-w-6xl px-5 h-16 flex items-center justify-between gap-4">
      <a href="index.html#beranda" class="focus-ring rounded"><img src="Assets/logo.png" alt="<?= e($situs->getNama()) ?>"
          class="h-[90px] w-auto"></a>
      <nav class="hidden md:flex gap-7 text-sm font-medium" aria-label="Menu utama">
        <?php foreach ($situs->getMenu() as $m): ?>
          <a href="<?= e($m['href']) ?>"
            class="nav-link hover:text-forest<?= $m['href'] === $halamanAktif ? ' text-forest font-bold' : '' ?>"
            <?= $m['href'] === $halamanAktif ? 'aria-current="page"' : '' ?>><?= e($m['label']) ?></a>
        <?php endforeach; ?>
      </nav>
      <a href="index.html#barang"
        class="focus-ring rounded-full bg-forest px-5 py-2 text-sm font-medium text-white hover:bg-forest-dark">Cari
        barang</a>
    </div>
    <nav class="md:hidden flex gap-5 overflow-x-auto border-t border-forest/10 px-5 py-3 text-sm font-medium whitespace-nowrap"
      aria-label="Menu seluler">
      <?php foreach ($situs->getMenu() as $m): ?>
        <a href="<?= e($m['href']) ?>" class="<?= $m['href'] === $halamanAktif ? 'text-forest font-bold' : '' ?>"><?= e($m['label']) ?></a>
      <?php endforeach; ?>
    </nav>
  </header>
