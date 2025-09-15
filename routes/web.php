<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/back/api/test', function () {
//     return view('welcome');
// });
