<?php

use App\Http\Controllers\Back\{
    AdminController, BillsController, EmailController, PlanController, SalesController, SettingController, StaffController,
    UserController,
};
use App\Http\Controllers\{
    UpdateController, SupportController
};
use App\Http\Controllers\Back\Auth\AuthController;
use App\Http\Controllers\Back\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

 // Admin Auth
 Route::controller(AuthController::class)->middleware('admin.guest')->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'submitLogin')->name('login');
});

// reset Password
Route::controller(PasswordController::class)->middleware('admin.guest')->group(function () {
Route::get('/password/reset', 'resetView')->name('password.reset');
Route::post('/password/reset', 'forgotPassword')->name('password.reset');
Route::post('password/resend', 'resendCode')->name('password.resend');
Route::get('/password/change', 'changePassword')->name('password.change');
Route::post('password/confirm', 'resetPassword')->name('password.confirm');
});
Route::middleware('admin')->group(function(){

    Route::controller(AdminController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/statistics', 'stats')->name('stats');
        Route::get('/balance', 'balance')->name('balance');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/profile', 'update_profile')->name('profile');
        // Notifications message to users
        Route::prefix('notifications')->as('notifications.')->group(function () {
            Route::get('/', 'notifications')->name('index');
            Route::post('/store', 'storeNotification')->name('store');
            Route::get('/delete/{id}', 'deleteNotification')->name('delete');
        });
        Route::get('/logout', 'index')->name('logout');
    });
    // Bills
    Route::controller(BillsController::class)->as('bills.')->group(function(){
        Route::get('/api-setting' , 'api_setting')->name('api_setting');
        Route::get('/api-selection' , 'api_selection')->name('api.selection');
        Route::get('/api-website' , 'api_website')->name('api.website');
        // API balances
        Route::get('/get-balance/{type?}' , 'apiBalance')->name('balance');
    });
    // Bills Plans
    Route::controller(PlanController::class)->as('plan.')->prefix('plans')->group(function () {

        // Airtime Routes
        Route::prefix('airtime')->as('airtime')->group(function () {
            Route::get('/', 'airtime');
            Route::post('/update/{id}', 'updateAirtime')->name('.update');
            Route::get('/status/{id}/{status}', 'airtimeStatus')->name('.status');
        });

        // Betting Routes
        Route::prefix('betting')->as('bet')->group(function () {
            Route::get('/', 'betting');
            Route::get('/create', 'createBet')->name('.create');
            Route::post('/create', 'storeBet')->name('.create');
            Route::post('/update/{id}', 'updateBet')->name('.update');
        });

        // Bulk SMS Routes
        Route::prefix('bulksms')->as('bulksms')->group(function () {
            Route::get('/', 'bulksms');
        });

        // Cable Routes
        Route::prefix('decoders')->as('decoders')->group(function () {
            Route::get('/', 'decoders');
            Route::get('/status/{id}/{status}', 'decoderStatus')->name('.status');
            Route::get('/plans/{decoder}', 'decoderPlans')->name('.plans');
        });
        Route::prefix('cable')->as('cable')->group(function () {
            Route::get('/plans', 'cablePlans');
            Route::get('/plan/create', 'createCable')->name('.create');
            Route::post('/plan/create', 'storeCable')->name('.create');
            Route::get('/plan/edit/{id}', 'editCable')->name('.edit');
            Route::post('/plan/update/{id}', 'updateCable')->name('.update');
            Route::get('/plan/delete/{id}', 'deleteCable')->name('.delete');
        });

        // Data Routes
        Route::prefix('data')->as('data')->group(function () {
            Route::get('/networks', 'dataNetwork')->name('.network');
            Route::get('/', 'dataPlans');
            Route::get('/plans/{network?}', 'dataPlans')->name('.plans');
            Route::get('/create', 'createData')->name('.create');
            Route::post('/create', 'storeData')->name('.store');
            Route::get('/edit/{id}', 'editData')->name('.edit');
            Route::post('/edit/{id}', 'updateData')->name('.update');
            Route::get('/status/{id}/{status}', 'dataStatus')->name('.status');
        });

        // Datacard Routes
        Route::prefix('datacard')->as('datacard')->group(function () {
            Route::get('/networks', 'datacardNetwork')->name('.network');
            Route::get('/', 'datacardPlans');
            Route::get('/plans/{network?}', 'datacardPlans')->name('.plans');
            Route::get('/create', 'createDatacard')->name('.create');
            Route::post('/create', 'storeDatacard')->name('.store');
            Route::get('/edit/{id}', 'editDatacard')->name('.edit');
            Route::post('/edit/{id}', 'updateDatacard')->name('.update');
            Route::get('/status/{id}/{status}', 'datacardStatus')->name('.status');
            Route::get('/delete/{id}', 'deleteDatacard')->name('.delete');
        });

        // Electricity Routes
        Route::prefix('electricity')->as('electricity')->group(function () {
            Route::get('/', 'electricity');
            Route::get('/create', 'createElectricity')->name('.create');
            Route::post('/create', 'storeElectricity')->name('.store');
            Route::get('/edit/{id}', 'editElectricity')->name('.edit');
            Route::post('/update/{id}', 'updateElectricity')->name('.update');
            Route::get('/status/{id}/{status}', 'electricityStatus')->name('.status');
        });

        // Education Routes
        Route::prefix('education')->as('education')->group(function () {
            Route::get('/', 'education');
            Route::post('/{id}', 'updateEducation')->name('.update');
            Route::get('/status/{id}/{status}', 'educationStatus')->name('.status');
        });

        // Recharge Routes
        Route::prefix('recharge')->as('recharge')->group(function () {
            Route::get('/networks', 'rechargeNetwork')->name('.network');
            Route::get('/plans/{network?}', 'rechargePlans')->name('.plans');
            Route::get('/', 'rechargePlans');
            Route::get('/edit/{id}', 'editRecharge')->name('.edit');
            Route::post('/edit/{id}', 'updateRecharge')->name('.update');
            Route::get('/status/{id}/{status}', 'rechargeStatus')->name('.status');
            Route::get('/plan-status/{id}/{status}', 'rechargePlanStatus')->name('.plan.status');
        });

        // Airtime Swap Routes
        Route::prefix('swap')->as('swap')->group(function () {
            Route::get('/', 'airtimeSwap');
            Route::post('/{id}', 'updateSwap')->name('.update');
            Route::get('/status/{id}/{status}', 'swapStatus')->name('.status');
        });
        // Other Routes
        Route::get('/airtime-vending', 'indexAirtimeVending')->name('airtime-vend');
        Route::get('/slot-keys', 'indexSlotKeys')->name('slot-keys');
        Route::get('/vend-keys', 'indexVendKeys')->name('vend-keys');
        Route::get('/vend-selection', 'indexVendSelection')->name('vend-selection');
        Route::post('/settings/update', 'updateSettings')->name('settings.update');
    });


    // Users
    Route::controller(UserController::class)->as('users.')->prefix('users')->group(function(){
        Route::get('/' , 'users')->name('index');
        Route::get('/view/{id}' , 'view_user')->name('view');
        Route::get('/edit/{id}' , 'edit_user')->name('edit');
        Route::get('/verify/{id}' , 'verify_user')->name('verify');
        Route::get('/withdrawals/{id}' , 'user_withdraw')->name('withdrawals');
        Route::get('/deposits/{id}' , 'user_deposit')->name('deposits');
        Route::get('/deposits/pay/{id}' , 'user_deposit_pay')->name('manual.pay');
        Route::get('/deposits/delete/{id}' , 'user_deposit_delete')->name('manual.delete');
        Route::get('/referrals/{id}' , 'user_referral')->name('referrals');
        Route::get('/transactions/{id}' , 'user_trx')->name('transactions');
        Route::get('/delete/{id}' , 'delete_user')->name('delete');
        Route::post('/ban/{id}/{status}' , 'ban_user')->name('ban');
        Route::get('/ban/{id}/{status}' , 'ban_user')->name('ban');
        Route::post('/edit/{id}' , 'update_user')->name('update');
        Route::post('/balance/{id}' , 'update_user_balance')->name('balance');
        Route::get('/login-user/{id}', 'userLogin')->name('login');
        Route::get('/settings' , 'user_settings')->name('settings');
    });
    // Reports
    Route::controller(SalesController::class)->group(function(){
        Route::get('/deposits' , 'deposits')->name('deposits');
        Route::get('/manual/deposits' , 'manual_deposits')->name('mdeposits');
        Route::get('/mdeposit/del/{id}' , 'manual_deposits_delete')->name('mdeposit.delete');
        Route::get('/mdeposit/pay/{id}' , 'manual_deposits_pay')->name('mdeposit.pay');
        Route::get('/mdeposit/rej/{id}' , 'manual_deposits_reject')->name('mdeposit.reject');
        Route::get('/transactions' , 'transactions')->name('transactions');
        Route::get('/transaction/rev/{id}' , 'reverse_transactions')->name('transactions.reverse');
        Route::get('/transaction/app/{id}' , 'approve_transactions')->name('transactions.approve');
        Route::get('/transaction/failed/{id}' , 'cancel_transactions')->name('transactions.cancel');
    });
    // Sales
    Route::controller(SalesController::class)->prefix('report')->as('reports.')->group(function(){
        Route::get('/data' , 'data_sales')->name('data');
        Route::get('/datacard' , 'datacard')->name('datacard');
        Route::get('/airtime' , 'airtime_sales')->name('airtime');
        Route::get('/cabletv' , 'cable_sales')->name('cable');
        Route::get('/electricity' , 'power_sales')->name('power');
        Route::get('/swap' , 'airtime_swap')->name('swap');
        Route::get('/swap/del/{id}' , 'airtime_swap_delete')->name('swap.delete');
        Route::get('/swap/approve/{id}' , 'airtime_swap_approve')->name('swap.approve');
        Route::get('/education' , 'education_sales')->name('education');
        Route::get('/vouchers' , 'voucher_pins')->name('vouchers');
        Route::get('/bulksms' , 'bulksms')->name('bulksms');
        Route::get('/betting' , 'bet')->name('bet');
    });
    // Staffs and Roles
    Route::controller(StaffController::class)->prefix('staffs')->as('staffs.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{staff}', 'edit')->name('edit');
        Route::post('/update/{staff}', 'update')->name('update');
        Route::get('/delete/{staff}', 'destroy')->name('destroy');
    });
    Route::controller(StaffController::class)->prefix('roles')->as('roles.')->group(function(){
        Route::get('/', 'roles')->name('index');
        Route::get('/create',  'create_role')->name('create');
        Route::post('/store', 'store_role')->name('store');
        Route::get('/edit/{id}', 'edit_role')->name('edit');
        Route::post('/update/{id}', 'update_role')->name('update');
        Route::get('/delete/{id}', 'destroy_role')->name('destroy');
    });
    // Email setting
    Route::controller(EmailController::class)->as('email.')->prefix('email')->group(function(){
        Route::get('/setting' , 'setting')->name('setting');
        Route::get('/templates' , 'templates')->name('template');
        Route::get('/newsletter' , 'newsletter')->name('newsletter');
        Route::post('/newsletter' , 'send_newsletter')->name('newsletter');
        Route::get('/templates/edit/{id}' , 'edit_template')->name('edit_template');
        Route::post('/templates/update/{id}' , 'update_template')->name('update_template');
        Route::post('/test' , 'test_email')->name('test');
    });
    // PAges
    Route::controller(AdminController::class)->as('pages.')->prefix('pages')->group(function(){
        Route::get('/create' , 'create_page')->name('create');
        Route::get('/' , 'pages')->name('index');
        Route::post('/create' , 'store_page')->name('store');
        Route::get('/edit/{id}' , 'edit_page')->name('edit');
        Route::post('/edit/{id}' , 'update_page')->name('update');
        Route::get('/delete/{id}' , 'delete_page')->name('delete');
    });
    // Support
    // Route::controller(SupportController::class)->as('support.')->prefix('support')->group(function(){
    //     Route::get('/tickets' , 'tickets')->name('tickets');
    //     Route::get('/tickets/unread' , 'unread_tickets')->name('unread_tickets');
    //     Route::get('/reply/{id}/{slug}' , 'reply')->name('reply');
    //     Route::post('/comment/{id}' , 'comment')->name('comment');
    //     Route::get('/delete/{id}' , 'delete')->name('delete');
    //     Route::get('/comment/delete/{id}' , 'delete_comment')->name('delete_comment');

    // });
    // Settings
    Route::controller(SettingController::class)->as('setting.')->prefix('settings')->group(function(){
        Route::get('/payment' , 'payment')->name('payment');
        Route::get('/features' , 'features')->name('features');
        Route::get('/custom-styles' , 'custom_styles')->name('custom');
        Route::get('/' , 'index')->name('index');

        Route::post('/update', 'update')->name('update');
        Route::post('/system', 'systemUpdate')->name('sys_settings');
        Route::post('/system/store', 'store_settings')->name('store_settings');
        Route::post('/api-settings', 'api_settings')->name('api_settings');
        Route::post('env_key', 'envkeyUpdate')->name('env_key');
    });
    Route::get('/cache-clear', [AdminController::class, 'clearCache'])->name('clear.cache');
    Route::get('/maintenance', [AdminController::class, 'maintenance'])->name('maintenance');
});
