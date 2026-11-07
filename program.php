<?php
declare(strict_types=1);

/* =====================================================================
   program.php  -  pintu masuk (route) untuk halaman Program Prioritas

   File ini TIDAK berisi data maupun HTML. Tugasnya hanya menerima
   request lalu menyerahkannya ke Controller (struktur MVC):

     Model       Model/       data program, situs, dan aturannya
     View        View/        tampilan HTML
     Controller  Controller/  ProgramController: index(), show(), process()

   Alamat:
     program.php                  daftar program   -> index()
     program.php?status=berjalan  daftar terfilter -> index()
     program.php?id=3             detail program   -> show(3)

   Cara menjalankan (Live Server TIDAK bisa menjalankan PHP):
     php -S localhost:8000      lalu buka http://localhost:8000/program.php
   ===================================================================== */
require_once __DIR__ . '/Controller/ProgramController.php';

$controller = new ProgramController();

if (isset($_GET['id'])) {
    $controller->show((int) $_GET['id']);
} else {
    $controller->index();
}
