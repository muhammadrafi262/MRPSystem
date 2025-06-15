<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ItemController, BomController, CustomerController, OrderController, PeriodController, ItemPeriodController, MrpController, FileDisplayController};
use App\Http\Controllers\Menu\LoginController;
use App\Http\Controllers\Menu\RegisterController;

require __DIR__.'/auth.php';
// Route group untuk menu dengan middleware guest (hanya untuk yang belum login)
Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('menu.register.store');

    // Login
    Route::get('/login', [LoginController::class, 'index'])->name('login');

    Route::post('/login', [LoginController::class, 'store'])->name('menu.login.store');
    
});

// Route group untuk halaman setelah login (harus sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('home.landingpage');
    })->name('home.landingpage');

    Route::resource('items', ItemController::class);

    // BOM routes pakai 2 parameter
    Route::resource('boms', App\Http\Controllers\BomController::class)->except(['show']);
    Route::get('boms/{parent_id}/{child_id}/edit', [App\Http\Controllers\BomController::class, 'edit'])->name('boms.edit');
    Route::put('boms/{parent_id}/{child_id}', [App\Http\Controllers\BomController::class, 'update'])->name('boms.update');
    Route::delete('boms/{parent_id}/{child_id}', [App\Http\Controllers\BomController::class, 'destroy'])->name('boms.destroy');

    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('periods', PeriodController::class);
    Route::resource('item_periods', ItemPeriodController::class)->only(['index']);

    Route::get('/process-mrp', [MrpController::class, 'processMRP'])->name('mrp.process');

    Route::get('/files/{folder}/{flag}/{filename}', [FileDisplayController::class, 'show'])
        ->where('folder', 'avatars|documents|thumbnails')
        ->where('flag', '[^/]+')  // flag bisa apa saja tanpa slash
        ->where('filename', '.+'); // filename bisa ada titik dan karakter apapun

    // Untuk yang tanpa flag (langsung filename)
    Route::get('/files/{folder}/{filename}', [FileDisplayController::class, 'show'])
        ->where('folder', 'avatars|documents|thumbnails')
        ->where('filename', '.+');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Default / redirect root ke home jika sudah login, ke login jika belum
Route::get('/', function () {
    return view('home.landingpage');
})->name('home.landingpage');