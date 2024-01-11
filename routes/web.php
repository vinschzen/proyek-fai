<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlaysController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ConcessionController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\PaymentController;

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
    return redirect('/login');
});

Route::post('/login', [FirebaseAuthController::class, 'login'])->name('login');
Route::post('/register', [FirebaseAuthController::class, 'register'])->name('register');
Route::post('/logout', [FirebaseAuthController::class, 'logout'])->name('logout');
Route::get('/confirm/{token}', [FirebaseAuthController::class, 'confirm'])->name('confirm.email');
Route::get('/home', [PageController::class, 'toHome'])->name('toHome');


Route::middleware(['not_logged'])->group(function () {
    Route::get('/login', [PageController::class, 'toLogin'])->name('toLogin');
    Route::get('/register', [PageController::class, 'toRegister'])->name('toRegister');
});


Route::middleware(['admin'])->group(function () {
    Route::group(['prefix' => 'admin'], function() {
        Route::get('/dashboard', [PageController::class, 'toDashboard'])->name('toDashboard');
    
        Route::get('/cashier-tickets', [CashierController::class, 'toCashierTickets'])->name('toCashierTickets');
        Route::get('/checkout-tickets/{id}', [CashierController::class, 'checkoutTickets'])->name('checkoutTickets');
        Route::post('/buytickets/{id}', [CashierController::class, 'buytickets'])->name('cashier.buytickets');
    
        Route::get('/cashier-concessions', [ConcessionController::class, 'toCashierConcessions'])->name('toCashierConcessions');
        Route::get('/checkout-concessions', [CashierController::class, 'checkoutConcessions'])->name('checkoutConcessions');
        Route::post('/buyconcessions', [CashierController::class, 'buyconcessions'])->name('cashier.buyconcessions');
    
    
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
            Route::get('/adddetails', [VoucherController::class, 'viewadddetails'])->name('voucher.adddetails');
            Route::delete('/destroy/{id}', [VoucherController::class, 'destroy'])->name('voucher.destroy');
            Route::post('/editing/{id}', [VoucherController::class, 'edit'])->name('voucher.edit');
            
            Route::get('/add', [VoucherController ::class, 'viewadd'])->name('toAddVoucher');
            Route::get('/edit/{id}', [VoucherController::class, 'viewedit'])->name('toEditVoucher');
        });
    
        Route::group(['prefix' => 'history'], function() {
            Route::group(['prefix' => 'tickets'], function() {
                Route::get('/tickets-history', [PageController::class, 'viewtickets'])->name('viewtickets');
                Route::get('/tickets-details/{id}', [PageController::class, 'detailtickets'])->name('detailtickets');
            });
    
            Route::group(['prefix' => 'concessions'], function() {
                Route::get('/concessions-history', [PageController::class, 'viewconcessions'])->name('viewconcessions');
                Route::get('/concessions-details/{id}', [PageController::class, 'detailconcessions'])->name('detailconcessions');
    
                Route::get('/removefromcart/{id}', [ConcessionController::class, 'removeFromCart'])->name('removeFromCart');
                Route::get('/addtocart/{id}', [ConcessionController::class, 'addToCart'])->name('addToCart');
                Route::get('/clearcart', [ConcessionController::class, 'clearCart'])->name('clearCart');
            });
    
            Route::group(['prefix' => 'seatings'], function() {
                Route::get('/seatings-history', [PageController::class, 'viewseatings'])->name('viewseatings');
                Route::get('/seatings-details/{id}', [PageController::class, 'detailseatings'])->name('detailseatings');
            });
            
        });
    });
});

Route::middleware(['staff'])->group(function () {
    Route::group(['prefix' => 'admin'], function() {
        Route::get('/dashboard', [PageController::class, 'toDashboard'])->name('toDashboard');
        Route::get('/search-ticket', [PageController::class, 'toSearchTicket'])->name('toSearchTicket');

        Route::get('/searching-ticket', [PageController::class, 'toSearchedTicket'])->name('toSearchedTicket');
        Route::get('/cashier-tickets', [CashierController::class, 'toCashierTickets'])->name('toCashierTickets');
        Route::get('/checkout-tickets/{id}', [CashierController::class, 'checkoutTickets'])->name('checkoutTickets');
        Route::post('/buytickets/{id}', [CashierController::class, 'buytickets'])->name('cashier.buytickets');
    
        Route::get('/cashier-concessions', [ConcessionController::class, 'toCashierConcessions'])->name('toCashierConcessions');
        Route::get('/checkout-concessions', [CashierController::class, 'checkoutConcessions'])->name('checkoutConcessions');
        Route::post('/buyconcessions', [CashierController::class, 'buyconcessions'])->name('cashier.buyconcessions');

        Route::group(['prefix' => 'history'], function() {
    
            Route::group(['prefix' => 'concessions'], function() {
                Route::get('/removefromcart/{id}', [ConcessionController::class, 'removeFromCart'])->name('removeFromCart');
                Route::get('/addtocart/{id}', [ConcessionController::class, 'addToCart'])->name('addToCart');
                Route::get('/clearcart', [ConcessionController::class, 'clearCart'])->name('clearCart');
            });
        });
    });
});

Route::middleware(['user'])->group(function () {
    Route::group(['prefix' => 'user'], function() {
        Route::get('/play/{id}', [PageController::class, 'playDetails'])->name('toPlay');
        Route::get('/checkout/{id}', [PageController::class, 'toCheckout'])->name('toCheckout');
        Route::get('/password', [PageController::class, 'toPassword'])->name('toPassword');
        
        Route::post('/userbuytickets/{id}', [CashierController::class, 'userbuytickets'])->name('user.buytickets');
        
        Route::get('/profile', [PageController::class, 'toProfile'])->name('toProfile');
        Route::get('/tickets/{id}', [PageController::class, 'toTicket'])->name('toTicket');
        Route::get('/saldo', [PageController::class, 'toSaldo'])->name('toSaldo');
        Route::get('/concessions', [PageController::class, 'toConcessions'])->name('toConcessions');
        
        Route::get('/topup/{id}', [FirebaseAuthController::class, 'topup'])->name('topup');
        Route::get('/userchangepassword/{id}', [UserController::class, 'changepassworduser'])->name('user.resetpassword');

        Route::get('/payment', [PaymentController::class, 'payment'])->name('payment');
        Route::post('/notification', [PaymentController::class, 'notification'])->name('handle');
    });
    
    Route::get('/removefromcart/{id}', [ConcessionController::class, 'removeFromUsersCart'])->name('removeFromUsersCart');
    Route::get('/addtocart/{id}', [ConcessionController::class, 'addToUsersCart'])->name('addToUsersCart');
    Route::get('/clearcart', [ConcessionController::class, 'clearUsersCart'])->name('clearUsersCart');
});

Route::post('/callback', [PaymentController::class, 'callback'])->name('callback');




