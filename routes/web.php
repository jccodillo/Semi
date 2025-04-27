<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\StockController as UserStockController;
use App\Http\Controllers\User\UserRequestController;
use App\Http\Controllers\Admin\AdminRequestController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\SupplyInventoryController;
use App\Http\Controllers\ReturnController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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


Route::group(['middleware' => 'auth'], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


	Route::get('profile', function () {
		return view('profile');
	})->name('profile');


	Route::get('user-management', [UserManagementController::class, 'index'])->name('user-management');




    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/profile', [InfoUserController::class, 'create']);
	Route::post('/profile', [InfoUserController::class, 'store']);
    Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);

    Route::resource('users', UserController::class);
    Route::resource('stock', StockController::class);
    Route::delete('/stock/{id}', [StockController::class, 'destroy'])->name('stock.destroy');

    Route::post('/change-password', [UserProfileController::class, 'changePassword'])->middleware('auth');
        // Return routes
    Route::post('/returns', [ReturnController::class, 'store'])->name('returns.store');
    Route::get('/my-returns', [ReturnController::class, 'myReturns'])->name('returns.my');
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
	Route::get('/login', [SessionsController::class, 'create'])->name('login');
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/session', [SessionsController::class, 'store']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});

// Welcome page (public)
Route::get('/', function () {
    return view('session.welcome');
})->name('welcome');

// Replace the existing scan/item route with these two separate explicit routes
Route::get('/scan/item/{id}', function($id) {
    if (!auth()->check()) {
        // Store the intended URL in session
        session(['intended_url' => url()->current()]);
        return redirect()->route('session.login');
    }
    
    // Check user role and redirect to appropriate URL
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.stock.details', ['id' => $id]);
    } else {
        return redirect()->route('user.stock.details', ['id' => $id]);
    }
})->name('scan.item');

// Authentication Routes
Route::get('/login', [SessionsController::class, 'create'])->name('session.login');
Route::post('/login', [SessionsController::class, 'store']);
Route::get('/register', [RegisterController::class, 'create'])->name('session.register');
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/logout', [SessionsController::class, 'destroy'])->name('session.logout');

// Admin routes
Route::middleware(['auth', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Profile routes
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [App\Http\Controllers\Admin\ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    
    // Request management routes
    Route::get('/requests', [AdminRequestController::class, 'index'])->name('requests.index');
    Route::put('/requests/{requestId}/update-status', [AdminRequestController::class, 'updateStatus'])->name('requests.update-status');
    Route::get('/requests/{requestId}/issue', [SupplyInventoryController::class, 'issueRequestItems'])->name('requests.issue');
    Route::post('/requests/{requestId}/issue', [SupplyInventoryController::class, 'processRequestIssuance'])->name('requests.process-issue');
    
    // Stock routes
    Route::resource('stocks', StockController::class);
    Route::get('/tables', [StockController::class, 'index'])->name('tables');
    Route::get('/stock/{id}/details', [StockController::class, 'showDetails'])->name('stock.details');
    
    // Other admin routes...
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [AdminReportController::class, 'generate'])->name('reports.generate');
    
    // Supply Inventory routes
    Route::get('/stock/supplyinventory', [SupplyInventoryController::class, 'create'])->name('stock.supplyinventory');
    Route::post('/stock/supplyinventory', [SupplyInventoryController::class, 'store'])->name('stock.supplyinventory.store');
    Route::get('/inventory', [SupplyInventoryController::class, 'index'])->name('inventory');
    
    // Edit routes for supplies
    Route::get('/supplies/{id}/edit', [SupplyInventoryController::class, 'edit'])->name('supplies.edit');
    Route::put('/supplies/{id}', [SupplyInventoryController::class, 'update'])->name('supplies.update');
    Route::get('/supplies/{id}/stockcard', [SupplyInventoryController::class, 'stockCard'])->name('supplies.stockcard');
    Route::get('/supplies/{id}/issuance', [SupplyInventoryController::class, 'issuance'])->name('supplies.issuance');
    Route::put('/supplies/{id}/issuance', [SupplyInventoryController::class, 'processIssuance'])->name('supplies.process-issuance');
    
    // Procurement routes
    Route::get('/procurement', [App\Http\Controllers\Admin\ProcurementController::class, 'index'])->name('procurement.index');
    Route::get('/procurement/create', [App\Http\Controllers\Admin\ProcurementController::class, 'create'])->name('procurement.create');
    Route::post('/procurement', [App\Http\Controllers\Admin\ProcurementController::class, 'store'])->name('procurement.store');
    Route::get('/procurement/{id}', [App\Http\Controllers\Admin\ProcurementController::class, 'show'])->name('procurement.show');
    Route::get('/procurement/{id}/iar', [App\Http\Controllers\Admin\ProcurementController::class, 'generateIAR'])->name('procurement.iar');

     // Return management
    Route::get('/returns', [\App\Http\Controllers\Admin\ReturnController::class, 'index'])->name('returns.index');
    Route::put('/returns/{id}', [\App\Http\Controllers\Admin\ReturnController::class, 'update'])->name('returns.update');
});

// User routes
Route::middleware(['auth', 'check.role:user'])->prefix('user')->name('user.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Stocks (view only)
    Route::get('/tables', [UserStockController::class, 'index'])->name('tables'); // Changed from 'stocks' to 'tables'
    Route::get('/stock/{id}/details', [UserStockController::class, 'showDetails'])->name('stock.details');

    // Request routes
    Route::get('/create-request', [UserRequestController::class, 'create'])->name('requests.createreq');
    Route::post('/store-request', [UserRequestController::class, 'store'])->name('requests.store');
    Route::get('/view-requests', [UserRequestController::class, 'index'])->name('requests.viewrequests');
});

// Common auth routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [SessionsController::class, 'destroy'])->name('logout');
    // ... other common auth routes ...
    
    // Messaging routes
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [App\Http\Controllers\MessageController::class, 'chat'])->name('messages.chat');
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'sendMessage'])->name('messages.send');
    Route::get('/messages/unread/count', [App\Http\Controllers\MessageController::class, 'getUnreadCount'])->name('messages.unread');
    Route::get('/messages/unread/from/{userId}', [App\Http\Controllers\MessageController::class, 'getUnreadFromUser'])->name('messages.unread.from');
});

Route::post('/upload-avatar', [UserProfileController::class, 'uploadAvatar'])->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::post('/password/change', [UserProfileController::class, 'changePassword'])->name('password.change');
});


