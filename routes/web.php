<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LendingBookController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReturnBookController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Stmt\Return_;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [LoginController::class, 'register'])->name('register');
    Route::post('/register', [LoginController::class, 'registerPost'])->name('register');
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'loginPost'])->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/addbooks', [BookController::class, 'addBook'])->name('addbooks');
    Route::post('/addbooks', [BookController::class, 'postBook'])->name('addbooks');
    Route::get('/booksowned', [BookController::class, 'ownedBooks'])->name('booksowned');
    Route::delete('/booksowned/{bookId}', [BookController::class, 'deleteBook'])->name('deletebook');
    Route::get('/lendbooks', [LendingBookController::class, 'viewBooks'])->name('lendbooks');
    Route::post('/lendbooks/{bookId}', [LendingBookController::class, 'lendBook'])->name('lendbooks');
    Route::get('/lendinghistory', [ReturnBookController::class, 'viewBooks'])->name('lendinghistory');
    Route::post('/lendinghistory/{bookId}', [ReturnBookController::class, 'returnBook'])->name('lendinghistory');
    Route::get('/addreviews', [ReviewController::class, 'viewReviews'])->name('addreviews');
    Route::post('/addreviews', [ReviewController::class, 'addReview'])->name('addreviews');
    Route::get('/viewreview', [ReviewController::class, 'showReview'])->name('viewreview');
    Route::delete('/logout', [LoginController::class, 'logout'])->name('logout');
});
