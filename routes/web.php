<?php

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Client\Core\FailedJobsController;
use App\Http\Controllers\Core\ZoneController;
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
    // user
    Route::resource('users', UserController::class)->names([
        'index'     => 'users.list',
        'store'     => 'users.store',
        'update'    => 'users.update',
        'destroy'   => 'users.delete',
    ])->except([
        'create', 'show', 'create', 'edit',
    ]);
    // provider
    Route::resource('providers', ProviderController::class)->names([
        'index'     => 'providers.list',
        'store'     => 'providers.store',
        'update'    => 'providers.update',
        'destroy'   => 'providers.delete',
    ])->except([
        'create', 'show', 'create', 'edit',
    ]);
    // integration
    Route::resource('integrations', IntegrationController::class)->names([
        'index'     => 'integrations.list',
        'store'     => 'integrations.store',
        'update'    => 'integrations.update',
        'destroy'   => 'integrations.delete',
    ])->except([
        'create', 'show', 'create', 'edit',
    ]);
    // zone
    Route::get('/zones/{integrationId}/{parentId?}', [ZoneController::class, 'index'])->name('zones');
    Route::post('/zones', [ZoneController::class, 'store'])->name('submit');
    // jobs
    Route::get('/failed', [FailedJobsController::class, 'index'])->name('failed');
    Route::get('/retry/{id?}', [FailedJobsController::class, 'retry'])->name('failed.retry');
});

require __DIR__ . '/auth.php';
