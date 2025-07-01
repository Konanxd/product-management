<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\OrgController;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryApiController;
use App\Http\Controllers\API\OrganizationApiController;
use App\Http\Controllers\API\ProductApiController; 

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

Route::middleware(['auth:api', 'tenant'])->get('/dashboard', function () {
    return 'Welcome to your org dashboard';
});

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

    Route::post('/create_organization', [OrgController::class, 'store']);
});



Route::middleware(['auth:api'])->group(function () {
    Route::get('/categories', [CategoryApiController::class, 'index']);
    Route::post('/categories', [CategoryApiController::class, 'store']);
    Route::get('/categories/{id}', [CategoryApiController::class, 'show']);
    Route::put('/categories/{id}', [CategoryApiController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy']);
});


Route::middleware(['auth:api'])->group(function () {
    // API Kategori yang sudah ada
    Route::get('/categories', [CategoryApiController::class, 'index']);
    Route::post('/categories', [CategoryApiController::class, 'store']);
    Route::get('/categories/{id}', [CategoryApiController::class, 'show']);
    Route::put('/categories/{id}', [CategoryApiController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy']);

    // TAMBAHKAN ROUTE INI
    // GET    /organizations
    // POST   /organizations
    // GET    /organizations/{id}
    // PUT/PATCH /organizations/{id}
    // DELETE /organizations/{id}
    Route::apiResource('/organizations', OrganizationApiController::class);
});

Route::middleware('auth:api')->group(function () {
   
    Route::apiResource('products', ProductApiController::class);
});