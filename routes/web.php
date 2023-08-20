<?php

use App\Http\Controllers\Auth\LogInController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Core\ProfileController;
use App\Http\Controllers\Core\SettingController;
use App\Http\Controllers\Client\Core\FailedJobsController;
use App\Http\Controllers\Client\Core\Zone;
use App\Http\Controllers\Core\ClientController;
use Illuminate\Support\Facades\Route;

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
    Route::get('login', [LogInController::class, 'create'])
        ->name('login');

    Route::post('login', [LogInController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect('client');
    });

    // todo it wll open a real dashboard 
    Route::get('/dashboard', function () {
        return redirect('client');
    })->name('dashboard');

    Route::get('/zones/{id?}', [Zone::class, 'index'])->name('zones');
    Route::post('/zones', [Zone::class, 'store'])->name('submit');

    Route::get('/failed/{id?}', [FailedJobsController::class, 'index'])->name('failed');
    Route::get('/retry/{id?}', [FailedJobsController::class, 'retry'])->name('failed.retry');

    Route::get('/setting', [SettingController::class, 'edit'])->name('setting.edit');
    Route::put('setting', [SettingController::class, 'update'])->name('setting.update');

    Route::get('/client', [ClientController::class, 'list'])->name('client');
    Route::get('/client/create', [ClientController::class, 'create'])->name('client.create');
    Route::post('/client/store', [ClientController::class, 'store'])->name('client.store');
    Route::get('/client/edit/{id}', [ClientController::class, 'edit'])->name('client.edit');
    Route::post('/client/update', [ClientController::class, 'update'])->name('client.update');
    Route::post('/client/delete/{id}',   [ClientController::class, 'delete'])->name('client.delete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});
