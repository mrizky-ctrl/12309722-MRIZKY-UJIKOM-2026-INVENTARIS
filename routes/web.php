<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// Route untuk Staff
Route::get('/staff/dashboard', function () {
    return view('staff.dashboard');
});

Route::get('/staff/lendings', function () {
    return view('staff.lendings.index');
});
