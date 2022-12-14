<?php

use App\Http\Controllers\AccountController as AccountController;
use Illuminate\Support\Facades\Route;

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

Auth::routes([
    'register' => false,
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::get('/', [App\Http\Controllers\AccountController::class, 'index'])->name('home');
Route::get('/transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions');
