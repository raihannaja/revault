<?php
declare(strict_types=1);

/* =====================================================================
   program.php  -  Halaman PHP Revault (Server-Side Rendering)

   Task 1 : Buat halaman PHP (variabel sesuai kode dan tema Revault)
   Task 2 : Proses data dengan PHP (array multidimensi, status/badge
            kondisional, loop penomoran)
   Task 3 : Bangun konten dinamis di server (grid card Program Prioritas)

   Cara menjalankan (Live Server TIDAK bisa menjalankan PHP):
     php -S localhost:8000      lalu buka http://localhost:8000/program.php
   ===================================================================== */


/* ---------------------------------------------------------------------
   TASK 1 - VARIABEL HALAMAN (nama dan tema sama dengan index.html)
   --------------------------------------------------------------------- */
$namaSitus        = 'Revault';
$tagline          = 'Give Things Another Life';
$judulHalaman     = 'Program Prioritas';
$deskripsiHalaman = 'Program yang kami dahulukan agar barang yang masih bernilai guna sampai ke orang yang membutuhkannya.';
$kotaDefault      = 'Malang';
$tahun            = date('Y');
$halamanAktif     = 'program.php';

$menu = [
    ['label' => 'Beranda',            'href' => 'index.html'],
    ['label' => 'Cari Barang',        'href' => 'index.html#barang'],
    ['label' => 'Inspirasi',          'href' => 'index.html#inspirasi'],
    ['label' => 'Program Prioritas',  'href' => 'program.php'],
    ['label' => 'Cara Kerja',         'href' => 'index.html#cara-kerja'],
    ['label' => 'Tentang',            'href' => 'index.html#tentang'],
];


/* ---------------------------------------------------------------------
   TASK 2 - PROSES DATA
   --------------------------------------------------------------------- */

// (a) Array multidimensi: data program ada di includes/data-program.php
$dataProgram = require __DIR__ . '/includes/data-program.php';

// Aman menampilkan teks ke HTML (sama fungsinya dengan esc() di script.js)
function e(string|int|float $teks): string
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function hitungPersen(int $tercapai, int $target): int
{
    if ($target <= 0) {
        return 0;
    }
    return min(100, (int) round($tercapai / $target * 100));
}

// (b) Status kondisional: ditentukan dari progres program
function tentukanStatus(int $persen, int $tercapai): string
{
    if ($persen >= 100) {
        return 'selesai';
    } elseif ($tercapai > 0) {
        return 'berjalan';
    }
    return 'segera';
}

// (b) Badge kondisional: warna sama dengan badge() di script.js
//     Hasil: [kelas badge, label badge, kelas progress bar]
function badgeStatus(string $status): array
{
    return match ($status) {
        'selesai'  => ['bg-forest-soft text-forest', 'Selesai',  'bg-forest'],
        'berjalan' => ['bg-sun/25 text-amber-900',   'Berjalan', 'bg-sun'],
        default    => ['bg-ink/10 text-ink',         'Segera',   'bg-ink/20'],
    };
}

// (c) Loop penomoran: setiap program diberi nomor urut 01, 02, 03, ...
$program = [];
$no = 1;
foreach ($dataProgram as $p) {
    $persen = hitungPersen($p['tercapai'], $p['target']);
    $status = tentukanStatus($persen, $p['tercapai']);
    [$kelasBadge, $labelBadge, $kelasBar] = badgeStatus($status);

    $program[] = $p + [
        'no'         => $no,
        'nomor'      => str_pad((string) $no, 2, '0', STR_PAD_LEFT),
        'persen'     => $persen,
        'sisa'       => max(0, $p['target'] - $p['tercapai']),
        'status'     => $status,
        'kelasBadge' => $kelasBadge,
        'labelBadge' => $labelBadge,
        'kelasBar'   => $kelasBar,
    ];
    $no++;
}

// (d) Ringkasan statistik dari array
$totalProgram    = count($program);
$jumlahPerStatus = array_count_values(array_column($program, 'status'));
$totalTercapai   = array_sum(array_column($program, 'tercapai'));
$totalTarget     = array_sum(array_column($program, 'target'));
$persenTotal     = hitungPersen($totalTercapai, $totalTarget);

// (e) Filter dari URL: program.php?status=berjalan (hanya nilai yang diizinkan)
$opsiFilter = ['semua' => 'Semua', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai', 'segera' => 'Segera'];
$filter = $_GET['status'] ?? 'semua';
if (!is_string($filter) || !array_key_exists($filter, $opsiFilter)) {
    $filter = 'semua';
}
$programTampil = $filter === 'semua'
    ? $program
    : array_values(array_filter($program, fn(array $p): bool => $p['status'] === $filter));

const WA_ICON = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 0 1-13.4 7.8L3 21l1.3-4.4A9 9 0 1 1 21 12Z"/><path d="M9 9c0 3 3 6 6 6l1-2-2-1-1 .8a4 4 0 0 1-1.800-1.800L12 10l-1-2-2 1Z"/></svg>';
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= e($deskripsiHalaman) ?>">
  <title><?= e($judulHalaman) ?> - <?= e($namaSitus) ?></title>
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
      <a href="index.html#beranda" class="focus-ring rounded"><img src="assets/logo.png" alt="<?= e($namaSitus) ?>"
          class="h-[90px] w-auto"></a>
      <nav class="hidden md:flex gap-7 text-sm font-medium" aria-label="Menu utama">
        <?php foreach ($menu as $m): ?>
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
      <?php foreach ($menu as $m): ?>
        <a href="<?= e($m['href']) ?>" class="<?= $m['href'] === $halamanAktif ? 'text-forest font-bold' : '' ?>"><?= e($m['label']) ?></a>
      <?php endforeach; ?>
    </nav>
  </header>

  <main>
    <!-- Hero halaman -->
    <section class="mx-auto max-w-6xl px-5 pt-14 pb-10">
      <p class="inline-block rounded-full bg-sun/25 px-4 py-1 text-sm font-medium text-amber-900"><?= e($tagline) ?></p>
      <h1 class="mt-4 font-display text-5xl sm:text-6xl font-bold leading-[1.05] text-forest"><?= e($judulHalaman) ?></h1>
      <p class="mt-5 max-w-xl text-lg text-ink/75"><?= e($deskripsiHalaman) ?></p>

      <dl class="mt-8 flex flex-wrap gap-x-10 gap-y-4">
        <div>
          <dt class="text-sm text-ink/60">Total program</dt>
          <dd class="font-display text-3xl font-bold"><?= $totalProgram ?></dd>
        </div>
        <div>
          <dt class="text-sm text-ink/60">Sedang berjalan</dt>
          <dd class="font-display text-3xl font-bold"><?= $jumlahPerStatus['berjalan'] ?? 0 ?></dd>
        </div>
        <div>
          <dt class="text-sm text-ink/60">Selesai</dt>
          <dd class="font-display text-3xl font-bold"><?= $jumlahPerStatus['selesai'] ?? 0 ?></dd>
        </div>
        <div>
          <dt class="text-sm text-ink/60">Barang tersalurkan</dt>
          <dd class="font-display text-3xl font-bold"><?= $totalTercapai ?> <span
              class="text-base font-medium text-ink/60">dari <?= $totalTarget ?> (<?= $persenTotal ?>%)</span></dd>
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
              <?php foreach ($program as $p): ?>
                <tr class="border-t border-forest/10">
                  <td class="px-4 py-3 font-display font-bold text-forest"><?= e($p['nomor']) ?></td>
                  <td class="px-4 py-3 font-medium"><?= e($p['nama']) ?></td>
                  <td class="px-4 py-3 text-ink/70"><?= e($p['kategori']) ?></td>
                  <td class="px-4 py-3 text-ink/70"><?= $p['tercapai'] ?>/<?= $p['target'] ?> (<?= $p['persen'] ?>%)</td>
                  <td class="px-4 py-3"><span
                      class="rounded-full px-3 py-1 text-xs font-medium <?= e($p['kelasBadge']) ?>"><?= e($p['labelBadge']) ?></span>
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
          <p class="mt-1 text-ink/65">Menampilkan <?= count($programTampil) ?> dari <?= $totalProgram ?> program
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
            <article
              class="group flex flex-col overflow-hidden rounded-2xl border border-forest/10 bg-white transition duration-300 hover:-translate-y-1.5 hover:border-forest/30 hover:shadow-xl">
              <div class="relative overflow-hidden">
                <img src="<?= e($p['foto']) ?>" alt="Foto program <?= e($p['nama']) ?>" loading="lazy"
                  class="h-48 w-full object-cover bg-forest-soft transition duration-500 group-hover:scale-105"
                  onerror="this.style.opacity=0">
                <span
                  class="absolute left-3 top-3 grid h-10 w-10 place-items-center rounded-full bg-sun font-display font-bold text-ink shadow"><?= e($p['nomor']) ?></span>
                <span
                  class="absolute right-3 top-3 rounded-full px-3 py-1 text-xs font-medium shadow <?= e($p['kelasBadge']) ?>"><?= e($p['labelBadge']) ?></span>
              </div>

              <div class="flex flex-1 flex-col p-5">
                <div class="flex items-start justify-between gap-2">
                  <h3 class="font-display text-xl font-bold"><?= e($p['nama']) ?></h3>
                  <span
                    class="shrink-0 rounded-full bg-forest-soft px-3 py-1 text-xs text-forest"><?= e($p['kategori']) ?></span>
                </div>
                <p class="mt-1 text-sm text-ink/60"><?= e($p['lokasi'] ?: $kotaDefault) ?> &middot; oleh
                  <?= e($p['penanggung']['nama']) ?></p>
                <p class="mt-3 text-sm text-ink/80"><?= e($p['deskripsi']) ?></p>

                <!-- progres -->
                <div class="mt-4">
                  <div class="flex justify-between text-xs text-ink/60">
                    <span><?= $p['tercapai'] ?> dari <?= $p['target'] ?> barang</span>
                    <span><?= $p['persen'] ?>%</span>
                  </div>
                  <div class="mt-1 h-2 overflow-hidden rounded-full bg-forest-soft" role="progressbar"
                    aria-valuenow="<?= $p['persen'] ?>" aria-valuemin="0" aria-valuemax="100"
                    aria-label="Progres <?= e($p['nama']) ?>">
                    <div class="h-full rounded-full <?= e($p['kelasBar']) ?>" style="width: <?= $p['persen'] ?>%"></div>
                  </div>
                  <?php if ($p['status'] === 'selesai'): ?>
                    <p class="mt-1 text-xs text-forest">Target tercapai.</p>
                  <?php else: ?>
                    <p class="mt-1 text-xs text-ink/60">Kurang <?= $p['sisa'] ?> barang lagi.</p>
                  <?php endif; ?>
                </div>

                <!-- tahap kerja: loop bersarang dengan penomoran -->
                <ol class="mt-4 flex-1 space-y-1.5 text-sm text-ink/80">
                  <?php foreach ($p['tahap'] as $i => $tahap): ?>
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
                  <a href="https://wa.me/<?= e($p['penanggung']['wa']) ?>?text=<?= rawurlencode('Halo ' . $p['penanggung']['nama'] . ', saya tertarik dengan program "' . $p['nama'] . '" di ' . $namaSitus . '.') ?>"
                    target="_blank" rel="noopener"
                    class="focus-ring inline-flex items-center gap-2 rounded-full border border-forest px-4 py-2.5 text-sm font-medium text-forest transition hover:bg-forest-soft"><?= WA_ICON ?>
                    Hubungi</a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <footer class="border-t border-forest/10 py-8 text-center text-sm text-ink/60">
    <p>&copy; <?= e($tahun) ?> <?= e($namaSitus) ?>. <?= e($tagline) ?>.</p>
  </footer>

</body>

</html>
