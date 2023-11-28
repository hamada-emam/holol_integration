<?php

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\SettingController;
use App\Http\Controllers\Client\Core\FailedJobsController;
use App\Http\Controllers\Client\Core\Zone;
use App\Http\Controllers\Core\IntegrationController;
use App\Http\Controllers\Core\ProviderController;
use App\Http\Controllers\Core\UserController;
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

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $isAdmin = auth()->user()->isAdmin;
        return view('dashboard', compact(['isAdmin']));
    })->name('dashboard');

    // profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // users
    // Route::get('/users', [UserController::class, 'index'])->name('users.list');
    // Route::post('/users', [UserController::class, 'store'])->name('users.store');
    // Route::put('/users', [UserController::class, 'update'])->name('users.update');
    // Route::delete('/users', [UserController::class, 'delete'])->name('users.delete');

    Route::resource('users', UserController::class)->names([
        'index' => 'users.list',
        'store' => 'users.store',
        'update' => 'users.update',
        'destroy' => 'users.delete',
    ])->except([
        'create', 'show', 'create', 'edit',
    ]);

    // Route::resource('users', UserController::class)->names([
    //     'index' => 'users.list',
    //     'store' => 'users.store',
    //     'update' => 'users.update',
    //     'destroy' => 'users.delete',
    // ])->except([
    //     'create', 'show', 'create', 'edit',
    // ])=;

    Route::get('/zones/{id?}', [Zone::class, 'index'])->name('zones');
    Route::post('/zones', [Zone::class, 'store'])->name('submit');

    Route::post('/integrations', [IntegrationController::class, 'index'])->name('integrations');
    Route::post('/providers', [ProviderController::class, 'index'])->name('providers');

    Route::get('/failed', [FailedJobsController::class, 'index'])->name('failed');
    Route::get('/retry/{id?}', [FailedJobsController::class, 'retry'])->name('failed.retry');

    Route::get('/integrations', [IntegrationController::class, 'edit'])->name('setting.edit');
    Route::get('/setting', [SettingController::class, 'edit'])->name('setting.edit');
    Route::put('setting', [SettingController::class, 'update'])->name('setting.update');
});

require __DIR__ . '/auth.php';
