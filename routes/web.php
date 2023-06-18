<?php

use Illuminate\Support\Facades\Route;

// Saat menggunakan route resource harus menggunakan dukungan di bawah ini
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [AdminController::class, 'dashboard']);
// Route::get('/home', 'AdminController@dashboard')->name('home');

Auth::routes();

Route::get('/home', [AdminController::class, 'dashboard']);
// Route::get('/home', 'AdminController@dashboard')->name('home');

Route::get('/dashboard', [AdminController::class, 'dashboard']);
Route::get('/catalogs', [AdminController::class, 'catalogs']);
Route::get('/publishers', [AdminController::class, 'publishers']);
Route::get('/authors', [AdminController::class, 'authors']);
Route::get('/members', [AdminController::class, 'members']);
Route::get('/books', [AdminController::class, 'books']);
Route::get('/test_spatie', [AdminController::class, 'testSpatie']);

Route::group(['prefix' => ''], function() {
    Route::resource('catalogs', CatalogController::class);
    Route::resource('publishers', PublisherController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('members', MemberController::class);
    Route::resource('books', BookController::class);
    Route::resource('transactions', TransactionController::class);
});

// API
Route::get('/api/authors', [App\Http\Controllers\AuthorController::class, 'api']);
Route::get('/api/publishers', [App\Http\Controllers\PublisherController::class, 'api']);
Route::get('/api/members', [App\Http\Controllers\MemberController::class, 'api']);
Route::get('/api/books', [App\Http\Controllers\BookController::class, 'api']);
Route::get('/api/catalogs', [App\Http\Controllers\CatalogController::class, 'api']);
Route::get('/api/transactions', [App\Http\Controllers\TransactionController::class, 'api']);



// tutor yang di video
// Route::get('dashboard', 'AdminController@dashboard');
// Route::get('catalogs', 'AdminController@catalogs');
// Route::get('publishers', 'AdminController@publishers');
// Route::get('authors', 'AdminController@authors');
// Route::get('members', 'AdminController@members');
// Route::get('books', 'AdminController@books');
// Route::get('transactions', 'AdminController@transactions');
// Route::get('test_spatie', 'AdminController@test_spatie');




// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

// ROUTE RESOURCE
// Route::resource('catalogs', CatalogController::class);
// Route::resource('publishers', PublisherController::class);
// Route::resource('authors', AuthorController::class);
// Route::resource('members', MemberController::class);
// Route::resource('books', BookController::class);
// Route::resource('transactions', TransactionController::class);





