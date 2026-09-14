<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\OfflineBookingController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\PropertyTypeController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Guest\AccountController;
use App\Http\Controllers\Guest\BookingController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
/*
|--------------------------------------------------------------------------
| Utility Routes (Keep if needed)
|--------------------------------------------------------------------------
*/

Route::get('cache-flush', function () {
    Cache::flush();
    Artisan::call('optimize:clear');
    return back()->with('success', 'Cache cleared successfully!');
});

Route::get('/clear-log', function () {
    $logPath = storage_path('logs/laravel.log');
    if (File::exists($logPath)) {
        file_put_contents($logPath, '');
    }
    return back()->with('success', 'Laravel log cleared successfully!');
})->name('clear.log');


/*
|--------------------------------------------------------------------------
| ADMIN AUTH ROUTES (admins table)
|--------------------------------------------------------------------------
*/

// 

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'adminLogin'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'adminLogout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| ADMIN PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['web', 'auth:admin'])->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'adminDashboard'])->name('dashboard');

    Route::resources([
        'amenities'  => AmenityController::class,
        'cities'  => CityController::class,
        'propertytype'  => PropertyTypeController::class,
        'branches'  => BranchController::class,
        'properties'  => PropertyController::class,
        'roles' => RoleController::class,
        'user'  => AdminUserController::class,
        
        'offlinebookings'  => OfflineBookingController::class,
        'expenses'  => ExpenseController::class,
        'settings'  => SettingsController::class,
    ]);

    Route::post('amenities/status', [AmenityController::class, 'changeStatus'])->name('amenities.status');
    
    Route::post('cities/status', [CityController::class, 'changeStatus'])->name('cities.status');
    
    Route::post('propertytype/status', [PropertyTypeController::class, 'changeStatus'])->name('propertytype.status');

    Route::post('branches/store-or-update/{id?}', [BranchController::class, 'storeOrUpdate'])->name('branches.storeOrUpdate');
    Route::get('branches/{id}/edit', [BranchController::class, 'edit'])->name('branches.edit');
    Route::get('branches/{id}/images', [BranchController::class, 'branchImg'])->name('branches.images');
    Route::post('branches/add-images/{id}', [BranchController::class, 'addImages'])->name('branches.add-images');
    Route::delete('branch-images/{id}', [BranchController::class, 'galleryDestroy'])->name('branches-img.destroy');
    Route::post('branches/status', [BranchController::class, 'changeStatus'])->name('branches.status');
    Route::post('branches/get-branch-empty-properties', [BranchController::class, 'getBranchEmptyProperties'])->name('branches.getBranchEmptyProperties');
    Route::post('branches/get-branch-properties', [BranchController::class, 'getBranchProperties'])->name('branches.getBranchProperties');
    

    Route::post('properties/store-or-update/{id?}', [PropertyController::class, 'storeOrUpdate'])->name('properties.storeOrUpdate');
    Route::get('properties/{id}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::get('properties/{id}/images', [PropertyController::class, 'propertyImg'])->name('properties.images');
    Route::post('properties/add-images/{id}', [PropertyController::class, 'addImages'])->name('properties.add-images');
    Route::delete('room-images/{id}', [PropertyController::class, 'galleryDestroy'])->name('properties-img.destroy');
    Route::post('properties/status', [PropertyController::class, 'changeStatus'])->name('properties.status');


    Route::get('/user/{id}/edit', [AdminUserController::class, 'edit'])->name('user.edit');
    Route::get('/edit-profile', [AdminUserController::class, 'editProfile'])->name('user.editProfile');
    Route::get('/user/profile/{id}', [AdminUserController::class, 'show'])->name('user-profile.show');    
    Route::post('user/status', [AdminUserController::class, 'changeStatus'])->name('user.status');


    Route::post('offlinebookings/store-or-update/{id?}', [OfflineBookingController::class, 'storeOrUpdate'])->name('offlinebookings.storeOrUpdate');
    Route::get('get-booked-dates', [OfflineBookingController::class, 'getBookedDates'])->name('offlinebookings.getBookedDates');
    Route::get('search-guest', [OfflineBookingController::class, 'searchGuest'])->name('offlinebookings.searchguest');
    Route::get('/offline-bookings/pay-remaining/{id}', [OfflineBookingController::class, 'payRemaining'])->name('offlinebookings.payRemaining');
    Route::post('/offline-bookings/store-payment', [OfflineBookingController::class, 'storePayment'])->name('offlinebookings.storePayment');
    Route::get('/offline-booking/payment/edit/{id}', [OfflineBookingController::class, 'editPayment'])->name('offlinebooking.editPayment');
    Route::post('/guest-doc-delete', [OfflineBookingController::class, 'deleteGuestDoc'])->name('guest.doc.delete');
    Route::post('/offline-booking/payment/update/{id}', [OfflineBookingController::class, 'updatePayment'])->name('offlinebooking.updatePayment');
    Route::delete('/offline-booking/payment/delete/{id}', [OfflineBookingController::class, 'deletePayment'])->name('offlinebooking.deletePayment');
    Route::get('/export-offlinebooking-excel', [OfflineBookingController::class, 'exportOfflineBookingExcelList'])->name('export.offlinebooking.excel');
    Route::get('/export-offlinebooking-csv', [OfflineBookingController::class, 'exportOfflineBookingCsvList'])->name('export.offlinebooking.csv');
    Route::get('/offline-bookings/{id}/extend', [OfflineBookingController::class, 'bookingExtended'])->name('offlinebookings.extend');
    Route::post('/offlinebookings/extend-stay', [OfflineBookingController::class, 'extendStay'])->name('offlinebookings.extendStay');
    Route::get('/offline-bookings/view/{id}', [OfflineBookingController::class, 'view'])->name('offlinebookings.view');
    Route::post('/offlinebookings/update-status', [OfflineBookingController::class, 'updateStatus'])->name('offlinebookings.updateStatus');
    
    Route::match(['get', 'post'], '/report/summary-report', [ReportController::class, 'summaryReport'])->name('report.summaryReport');

    Route::post('expenses/store-or-update/{id?}', [ExpenseController::class, 'storeOrUpdate'])->name('expenses.storeOrUpdate');
    Route::get('/export-expense-excel', [ExpenseController::class, 'exportExpenseExcelList'])->name('export.expenses.excel');
    Route::get('/export-expense-csv', [ExpenseController::class, 'exportExpenseCsvList'])->name('export.expenses.csv');    

    Route::get('/change-password', [AdminUserController::class, 'ViewChangePassword'])->name('change-password');
    Route::post('/change-password', [AdminUserController::class, 'changePassword']);

    
});


/*
|--------------------------------------------------------------------------
| Frontend Websites
|--------------------------------------------------------------------------
*/
Route::get('login', [LoginController::class, 'showGuestLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'guestLogin'])->name('login.post');;
Route::post('logout', [LoginController::class, 'guestLogout'])->name('logout');


Route::middleware(['web'])->name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'homepage'])->name('homepage');
    Route::get('properties', [HomeController::class, 'properties'])->name('properties');
    Route::get('property-details/{id}', [HomeController::class, 'propertyDetails'])->name('propertyDetails');
    Route::post('/property/check-availability', [HomeController::class, 'checkPropertyAvailability'])->name('checkPropertyAvailability');
    
    Route::post('/booking-signup', [HomeController::class, 'bookingSignup'])->name('bookingSignup');
    Route::get('/register', [HomeController::class, 'register'])->name('register');
    Route::post('/register', [HomeController::class, 'registerGuest'])->name('registerGuest');
    Route::post('/guest-booking-information', [HomeController::class, 'guestBookingInformation'])->name('guestBookingInformation');
    

    Route::post('/get-branches-city-wise', [BranchController::class, 'getBranchesCityWise'])->name('getBranchesCityWise');
    Route::get('/search', [HomeController::class, 'properties'])->name('search.properties');
    


    Route::post('verify-payment-and-book', [HomeController::class, 'verifyPaymentAndBook'])->name('verifyPaymentAndBook')->withoutMiddleware([ValidateCsrfToken::class]);;
    Route::post('update-failed-payment-status', [HomeController::class, 'updateFailedPaymentStatus'])->name('updateFailedPaymentStatus')->withoutMiddleware([ValidateCsrfToken::class]);;

    Route::get('contact-us', [HomeController::class, 'contactUs'])->name('contactUs');

    Route::get('/bookings/{bookingId}/invoice', [BookingController::class, 'generateInvoice'])->name('generateInvoice');
});

Route::middleware(['web', 'auth'])->name('guest.')->group(function () {

    Route::get('/account', [AccountController::class, 'account'])->name('account');
    Route::post('/account/change-password', [AccountController::class, 'changeAccountPassword'])->name('changeAccountPassword');
    Route::post('/account/update-account', [AccountController::class, 'updateAccount'])->name('updateAccount');

    Route::get('/bookings', [BookingController::class, 'bookings'])->name('bookings');
});
