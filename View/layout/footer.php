<?php /* VIEW layout: kaki halaman. Data dari Controller: $situs */ ?>
  <footer class="border-t border-forest/10 py-8 text-center text-sm text-ink/60">
    <p>&copy; <?= e($situs->getTahun()) ?> <?= e($situs->getNama()) ?>. <?= e($situs->getTagline()) ?>.</p>
  </footer>

</body>

</html>
