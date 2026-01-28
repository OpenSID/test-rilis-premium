<?php
// -------------------------------------------------------------------------
//
// Letakkan username, password dan database sebetulnya di file ini.
// File ini JANGAN di-commit ke GIT. TAMBAHKAN di .gitignore
// -------------------------------------------------------------------------

// Data Konfigurasi MySQL yang disesuaikan

$db['default']['hostname'] = '127.0.0.1';
$db['default']['username'] = 'root';
$db['default']['password'] = 'eyJpdiI6ImVrNnp5MVZXNGdYOFNadG5SK3E3WHc9PSIsInZhbHVlIjoiSEhyd0xFY0RkY3YxaWNVT0Zodmprdz09IiwibWFjIjoiYTE3ODYyMjU0OWE2MzYzYzNhMTgyMWFmZmZhN2IzNDAzZGNjYzdhZGYwY2YwMGZlNGFmYjY4ODAwODIyYTQyOCIsInRhZyI6IiJ9';
$db['default']['port']     = 3306;
$db['default']['database'] = 'beta10';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbcollat'] = 'utf8mb4_general_ci';
$db['default']['char_set'] = 'utf8mb4';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['encrypt'] = FALSE;

/*
| Untuk setting koneksi database 'Strict Mode'
| Sesuaikan dengan ketentuan hosting
*/
$db['default']['stricton'] = true;

/*
| Konfigurasi options digunakan untuk menyisipkan opsi tambahan
| saat mengatur koneksi ke database.
*/
$db['default']['options'] = [
    // PDO::ATTR_EMULATE_PREPARES => true,
];