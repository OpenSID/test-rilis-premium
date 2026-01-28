<?php

/**
 * Module Routes - Console/CLI
 * 
 * PENTING: 
 * Gunakan Route::group dengan namespace untuk CLI commands
 */

// CLI commands dengan namespace
Route::group('contoh', ['namespace' => 'Contoh/Console'], function () {
    Route::cli('migrate', 'MigrateCommand@run');
    Route::cli('seed', 'SeedCommand@run');
});
