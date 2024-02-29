<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthMiddleware;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/book', [BookController::class, 'index']);
Route::get('/book/{id}', [BookController::class, 'show']);

Route::get('/user/{id}', [UserController::class, 'index']);

Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);

    Route::post('/book/{id}/feedback', [FeedbackController::class, 'store']);
    Route::patch('/book/{id}/feedback', [FeedbackController::class, 'update']);
    Route::delete('/book/{id}/feedback', [FeedbackController::class, 'destroy']);

    Route::get('/author', [AuthorController::class, 'index']);
    Route::get('/author/{id}', [AuthorController::class, 'show']);

    Route::get('/favourites', [UserController::class, 'favourites']);
    Route::post('/book/{id}/favourite', [FavouriteController::class, 'store']);
    Route::delete('/book/{id}/favourite', [FavouriteController::class, 'destroy']);

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::post('/book', [BookController::class, 'store']);
        Route::patch('/book/{id}', [BookController::class, 'update']);
        Route::delete('/book/{id}', [BookController::class, 'destroy']);

        Route::post('/author', [AuthorController::class, 'store']);
        Route::patch('/author/{id}', [AuthorController::class, 'update']);
        Route::delete('/author/{id}', [AuthorController::class, 'destroy']);

        Route::get('/genre', [GenreController::class, 'index']);
        Route::post('/genre', [GenreController::class, 'store']);
        Route::patch('/genre/{id}', [GenreController::class, 'update']);
        Route::delete('/genre/{id}', [GenreController::class, 'destroy']);

        Route::get('/user', [UserController::class, 'all']);
        Route::post('/user', [UserController::class, 'create']);
        Route::patch('/user/{id}', [UserController::class, 'edit']);
        Route::delete('/user/{id}', [UserController::class, 'destroy']);
    });
});
