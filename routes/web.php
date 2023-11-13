<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlaysController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ConcessionController;

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
// Route::middleware(['auth'])->group(function () {
//     Route::get('/home', [PageController::class, 'toHome'])->name('home');
//     Route::post('/logout', [FirebaseAuthController::class, 'logout'])->name('logout');
// });

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [PageController::class, 'toLogin'])->name('toLogin');

    Route::post('/login', [FirebaseAuthController::class, 'login'])->name('login');
    Route::post('/register', [FirebaseAuthController::class, 'register'])->name('register');
    Route::post('/logout', [FirebaseAuthController::class, 'logout'])->name('logout');
});

// Route::middleware(['auth.user'])->group(function () {
//     Route::get('/home', [PageController::class, 'toHome'])->name('toHome');
//     Route::post('/logout', [FirebaseAuthController::class, 'logout'])->name('logout');
// });

// Route::middleware(['auth.admin'])->group(function () {
//     Route::get('/admin', [PageController::class, 'toDashboard'])->name('admin.dashboard');
//     Route::post('/logout', [FirebaseAuthController::class, 'logout'])->name('logout');
// });

Route::group(['prefix' => 'user'], function() {
    Route::get('/home', [PageController::class, 'toHome'])->name('toHome');
    Route::get('/profile', [PageController::class, 'toProfile'])->name('toProfile');
    Route::get('/saldo', [PageController::class, 'toSaldo'])->name('toSaldo');
    Route::get('/concessions', [PageController::class, 'toConcessions'])->name('toConcessions');

    
    Route::get('/topup/{id}', [FirebaseAuthController::class, 'topup'])->name('topup');

});


Route::group(['prefix' => 'admin'], function() {
    Route::get('/dashboard', [PageController::class, 'toDashboard'])->name('toDashboard');
    Route::get('/cashier-tickets', [ScheduleController::class, 'toCashierTickets'])->name('toCashierTickets');
    Route::get('/checkout-tickets', [ScheduleController::class, 'checkoutTickets'])->name('checkoutTickets');
    Route::get('/cashier-concessions', [ConcessionController::class, 'toCashierConcessions'])->name('toCashierConcessions');
    Route::get('/checkout-concessions', [ConcessionController::class, 'checkoutConcessions'])->name('checkoutConcessions');

    Route::group(['prefix' => 'users'], function() {
        Route::get('/master', [UserController::class, 'index'])->name('toMasterUser');
        Route::post('/add', [UserController::class, 'store'])->name('users.store');
        Route::get('/toggle/{id}', [UserController::class, 'toggle'])->name('users.toggle');
        Route::post('/changeusername/{id}', [UserController::class, 'changeusername'])->name('users.changeusername');
        Route::post('/changepassword/{id}', [UserController::class, 'changepassword'])->name('users.changepassword');
        Route::post('/changerole/{id}', [UserController::class, 'changerole'])->name('users.changerole');
        
        Route::get('/add', [UserController::class, 'viewadd'])->name('toAddUser');
        Route::get('/edit/{id}', [UserController::class, 'viewedit'])->name('toEditUser');
    });

    Route::group(['prefix' => 'plays'], function() {
        Route::get('/master', [PlaysController::class, 'index'])->name('toMasterPlay');
        Route::post('/add', [PlaysController::class, 'store'])->name('plays.store');
        Route::delete('/destroy/{id}', [PlaysController::class, 'destroy'])->name('plays.destroy');
        Route::post('/editing/{id}', [PlaysController::class, 'edit'])->name('plays.edit');
        
        Route::get('/add', [PlaysController::class, 'viewadd'])->name('toAddPlay');
        Route::get('/edit/{id}', [PlaysController::class, 'viewedit'])->name('toEditPlay');
    });

    Route::group(['prefix' => 'schedule'], function() {
        Route::get('/master', [ScheduleController::class, 'index'])->name('toMasterSchedule');
        Route::post('/add', [ScheduleController::class, 'store'])->name('schedule.store');
        Route::delete('/destroy/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
        Route::post('/editing/{id}', [ScheduleController::class, 'edit'])->name('schedule.edit');
        
        Route::get('/add', [ScheduleController ::class, 'viewadd'])->name('toAddSchedule');
        Route::get('/edit/{id}', [ScheduleController::class, 'viewedit'])->name('toEditSchedule');
    });

    Route::group(['prefix' => 'concession'], function() {
        Route::get('/master', [ConcessionController::class, 'index'])->name('toMasterConcession');
        Route::post('/add', [ConcessionController::class, 'store'])->name('concession.store');
        Route::delete('/destroy/{id}', [ConcessionController::class, 'destroy'])->name('concession.destroy');
        Route::post('/editing/{id}', [ConcessionController::class, 'edit'])->name('concession.edit');
        
        Route::get('/add', [ConcessionController ::class, 'viewadd'])->name('toAddConcession');
        Route::get('/edit/{id}', [ConcessionController::class, 'viewedit'])->name('toEditConcession');
    });

    Route::group(['prefix' => 'voucher'], function() {
        Route::get('/master', [VoucherController::class, 'index'])->name('toMasterVoucher');
        Route::post('/add', [VoucherController::class, 'store'])->name('voucher.store');
        Route::delete('/destroy/{id}', [VoucherController::class, 'destroy'])->name('voucher.destroy');
        Route::post('/editing/{id}', [VoucherController::class, 'edit'])->name('voucher.edit');
        
        Route::get('/add', [VoucherController ::class, 'viewadd'])->name('toAddVoucher');
        Route::get('/edit/{id}', [VoucherController::class, 'viewedit'])->name('toEditVoucher');
    });

    Route::group(['prefix' => 'history'], function() {
        Route::group(['prefix' => 'tickets'], function() {
            Route::get('/tickets-history', [PageController::class, 'viewtickets'])->name('viewtickets');
            Route::get('/tickets-details', [PageController::class, 'detailtickets'])->name('detailtickets');
        });

        Route::group(['prefix' => 'concessions'], function() {
            Route::get('/concessions-history', [PageController::class, 'viewconcessions'])->name('viewconcessions');
            Route::get('/concessions-details', [PageController::class, 'detailconcessions'])->name('detailconcessions');
        });

        Route::group(['prefix' => 'seatings'], function() {
            Route::get('/seatings-history', [PageController::class, 'viewseatings'])->name('viewseatings');
            Route::get('/seatings-details', [PageController::class, 'detailseatings'])->name('detailseatings');
        });
        
    });
});

