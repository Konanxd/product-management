<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\AuthPagesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\OrgController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthPagesController::class, 'login'])->name('login');
    Route::get('/daftar', [AuthPagesController::class, 'register'])->name('register');
});

Route::get('/choices', [OrgController::class, 'choices']);
Route::get('/create_organization', [OrgController::class, 'create']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');

Route::get('/produk', [ProductController::class, 'index'])->name('products.index');

Route::get('/anggota', [MembersController::class, 'index']);

Route::get('/laporan', [ReportController::class, 'index']);
