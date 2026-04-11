<?php
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () { return view('landing'); })->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.auth');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // Route General
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/export', [ItemController::class, 'exportExcel'])->name('items.export');
    Route::get('/lendings/export', [LendingController::class, 'exportExcel'])->name('lendings.export');
    Route::patch('/lendings/{id}/pay-penalty', [LendingController::class, 'payPenalty'])->name('lendings.payPenalty');
    Route::get('/lendings/{id}/print', [LendingController::class, 'printReceipt'])->name('lendings.print');

    // ROUTE DETAIL (Kunci agar Admin & Staff bisa akses detail tanpa 403)
    Route::get('/items/{id}/lendings', [ItemController::class, 'lendingDetail'])->name('items.lending_detail');

    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    // Role Admin
    Route::middleware('checkRole:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', function() { return view('admin.dashboard'); })->name('admin.dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('items', ItemController::class)->except(['index']);
        Route::get('/users/export/{role}', [UserController::class, 'exportExcel'])->name('users.export');
        Route::resource('users', UserController::class);
    });

    // Role Staff
    Route::middleware('checkRole:staff')->prefix('staff')->group(function () {
        Route::get('/dashboard', function() { return view('staff.dashboard'); })->name('staff.dashboard');
        Route::resource('lendings', LendingController::class);
    });
});
