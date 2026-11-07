<?php
/* VIEW halaman "program tidak ditemukan".
   Data dari Controller: $situs, $judulHalaman, $deskripsiHalaman, $halamanAktif */
require_once __DIR__ . '/helpers.php';
require __DIR__ . '/layout/header.php';
?>

  <main>
    <section class="mx-auto max-w-xl px-5 py-24 text-center">
      <p class="font-display text-6xl font-bold text-forest">404</p>
      <h1 class="mt-3 font-display text-2xl font-bold">Program tidak ditemukan</h1>
      <p class="mt-2 text-ink/70">Program yang kamu cari tidak ada atau sudah dihapus.</p>
      <a href="program.php#program"
        class="focus-ring mt-6 inline-block rounded-full bg-forest px-6 py-2.5 text-sm font-medium text-white hover:bg-forest-dark">Kembali
        ke daftar program</a>
    </section>
  </main>

<?php require __DIR__ . '/layout/footer.php'; ?>
