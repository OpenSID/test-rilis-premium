<?php

/**
 * Contoh penggunaan Route Name di OpenSID Router
 * 
 * Route name dapat digunakan untuk generate URL dengan helper route()
 * Contoh: route('baru.index') atau route('baru.show', ['id' => 123])
 */

//  Route group dengan namespace dan route names
Route::group('baru', ['namespace' => 'Contoh'], function () {
    // http://127.0.0.1:8000/baru
    Route::get('/', 'ContohController@index')->name('baru.index');
    
    // http://127.0.0.1:8000/baru/show/123
    Route::get('show/{id}', 'ContohController@show')->name('baru.show');
    
    // http://127.0.0.1:8000/baru/detail/123
    Route::get('detail/{id}', 'ContohController@detail')->name('baru.detail');
    
    // http://127.0.0.1:8000/baru/demo
    Route::get('demo', 'ContohController@viewDemo')->name('baru.demo');
});