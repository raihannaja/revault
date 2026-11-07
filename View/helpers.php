<?php
declare(strict_types=1);

/* VIEW: fungsi bantu tampilan (bukan logika data) */

// Aman menampilkan teks ke HTML (sama fungsinya dengan esc() di script.js)
function e(string|int|float $teks): string
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

const WA_ICON = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 0 1-13.4 7.8L3 21l1.3-4.4A9 9 0 1 1 21 12Z"/><path d="M9 9c0 3 3 6 6 6l1-2-2-1-1 .8a4 4 0 0 1-1.800-1.800L12 10l-1-2-2 1Z"/></svg>';
