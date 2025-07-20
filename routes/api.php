<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrgController;
use App\Http\Controllers\ProductController;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware(['auth:api'])->get('/dashboard', function () {
//     return 'Welcome to your org dashboard';
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/create_organization', [OrgController::class, 'register']);
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'data'
], function ($router) {
    Route::get('/dashboard', [DashboardController::class, 'data'])->name('dashboard.get');

    Route::get('/categories', [CategoryController::class, 'data'])->name('categories.get');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories', [CategoryController::class, 'update'])->name('categories.store');

    Route::get('/products', [ProductController::class, 'data'])->name('products.get');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products', [ProductController::class, 'update'])->name('products.store');
});
