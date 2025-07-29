<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\OrgController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Models\Organization;
use App\Models\OrganizationInvitation;
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
    Route::get('/create-inv', [OrganizationInvitation::class, 'create']);
    Route::post('/join-inv', [OrganizationInvitation::class, 'join']);
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
    Route::put('/products', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/members', [MembersController::class, 'data'])->name('members.get');

    Route::get('/report', [ReportController::class, 'data'])->name('report.get');
});
