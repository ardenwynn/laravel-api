<?php

use App\Http\Controllers\ApiResources\ArticleController;
use App\Http\Controllers\ApiResources\ArticleImageController;
use App\Http\Controllers\ApiResources\CommentController;
use App\Http\Controllers\ApiResources\RoleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'verified'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
            Route::group(['excluded_middleware' => ['verified', 'auth:api']], function () {
                Route::post('/register', [AuthController::class, 'register']);
                Route::post('/login', [AuthController::class, 'login']);
            });
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::get('/profile', [AuthController::class, 'profile']);
        });

        Route::apiResources([
            'articles' => ArticleController::class,
            'comments' => CommentController::class,
            'roles' => RoleController::class,
        ]);
        Route::post('/upload', [ArticleImageController::class, 'upload']);
    });
});

Route::group(['prefix' => 'email', 'namespace' => 'auth'], function () {
    Route::get('/resend',[VerificationController::class, 'resend'])->name('verification.resend');
    Route::get('/verify/{id}/{hash}',[VerificationController::class, 'verify'])->name('verification.verify');
});

