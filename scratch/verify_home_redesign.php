<?php

$html = file_get_contents('http://127.0.0.1:8000');

$checks = [
    'Fraunces font' => str_contains($html, 'family=Fraunces'),
    'Headline' => str_contains($html, 'Perjalanan antarkota, lebih mudah dipesan.'),
    'Subheadline' => str_contains($html, 'Cari jadwal, pilih kursi, dan pesan tiket dalam satu alur sederhana.'),
    'Search Widget' => str_contains($html, 'id="search-widget"'),
    'Cari Tiket button' => str_contains($html, 'Cari Tiket'),
    'Rute yang sering dicari' => str_contains($html, 'Rute yang sering dicari'),
    'Jadwal perjalanan' => str_contains($html, 'Jadwal perjalanan'),
    'Pesan tiket dalam beberapa langkah' => str_contains($html, 'Pesan tiket dalam beberapa langkah'),
    'Pemesanan yang aman dan jelas' => str_contains($html, 'Pemesanan yang aman dan jelas'),
    'Armada yang digunakan' => str_contains($html, 'Armada yang digunakan'),
    'Fasilitas perjalanan' => str_contains($html, 'Fasilitas perjalanan'),
    'Butuh bantuan?' => str_contains($html, 'Butuh bantuan?'),
    'Footer' => str_contains($html, 'PO CAN Travel. Seluruh hak cipta dilindungi.'),
    'No Luxury/Premium buzzwords' => !str_contains($html, 'Luxury') && !str_contains($html, 'Premium'),
    'No AI buzzwords' => !str_contains($html, 'Seamless') && !str_contains($html, 'Next Generation'),
    'Auto-fill search logic' => str_contains($html, 'search-widget') && str_contains($html, 'scrollIntoView'),
];

$allPass = true;
foreach ($checks as $k => $v) {
    echo ($v ? 'PASS: ' : 'FAIL: ') . $k . PHP_EOL;
    if (!$v) $allPass = false;
}

if ($allPass) {
    echo PHP_EOL . "ALL 16 HOMEPAGE SPEC CHECKS PASSED!" . PHP_EOL;
} else {
    echo PHP_EOL . "SOME CHECKS FAILED!" . PHP_EOL;
}
