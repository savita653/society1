<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\UserController;

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

Route::get('users', [UserController::class,'index'])->name('users.index');


Route::get('societies', [SocietyController::class, 'index'])->name('index');

Route::get('societies/create', [SocietyController::class, 'create'])->name('society.create');
Route::post('society/store', [SocietyController::class, 'store'])->name('society.store');

Route::get('societies/{society}/edit', [SocietyController::class, 'edit'])->name('society.edit');
Route::post('societies/update/{society:id}', [SocietyController::class, 'update'])->name('society.update');

Route::delete('societies/delete/{society}', [SocietyController::class, 'delete'])->name('society.delete');


//user

Route::get('users/create', [UserController::class, 'create'])->name('users.create');
Route::post('users/store', [UserController::class, 'store'])->name('users.store');
