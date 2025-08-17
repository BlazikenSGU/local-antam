<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DoiSoatController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\StaffController;
use App\Http\Controllers\Backend\Branch\BranchController;
use App\Http\Controllers\Backend\OrdersController;
use App\Http\Controllers\Backend\SubscribersController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\UserController;

//authentication routes
Route::any('/login', [AuthController::class, 'login'])->name('backend.login');
Route::any('/logout', [AuthController::class, 'logout'])->name('backend.logout');
Route::any('/register', [AuthController::class, 'register'])->name('backend.register');
Route::get('/check-otp-expire', [AuthController::class, 'checkOTPTimeout'])->name('backend.checkOTPTimeout');
Route::any('/forgotPassword', [AuthController::class, 'forgotPassword'])->name('backend.forgotPassword');
Route::any('/changePassword', [AuthController::class, 'changePassword'])->name('backend.changePassword');
Route::get('/verify-otp', [AuthController::class, 'verifyOTP'])->name('backend.verifyOTP');
Route::post('/verify-otp', [AuthController::class, 'verifyOTP'])->name('backend.verifyOTP.post');

Route::prefix('admin')->middleware('backend')->group(function () {
    // Dashboard admin
    Route::get('/', [DashboardController::class, 'index'])->name('backend.dashboard');

    // xem check log 
    Route::get('/view-log', [NotificationController::class, 'index'])->name('backend.notification.index');

    // Quản lý đối soát
    Route::group(['prefix' => 'doi_soat'], function () {
        Route::get('/', [DoiSoatController::class, 'index'])->name('backend.doi_soat.index');
        Route::get('/edit/{id}', [DoiSoatController::class, 'edit'])->name('backend.doi_soat.edit');
        Route::post('/edit/{id}', [DoiSoatController::class, 'update'])->name('backend.doi_soat.update');
        Route::get('/searchs', [DoiSoatController::class, 'search'])->name('backend.doi_soat.search');
        Route::get('/export', [DoiSoatController::class, 'export'])->name('backend.doi_soat.export');
        Route::get('/import', [DoiSoatController::class, 'import'])->name('backend.doi_soat.import');
        Route::get('/file-import', [DoiSoatController::class, 'showFormImport'])->name('backend.doi_soat.showFormImport');
        //chạy đối soát
        Route::any('/run', [DoiSoatController::class, 'run'])->name('backend.doi_soat.run');
    });

    // quản lý tài khoản khách hàng
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [StaffController::class, 'index'])->name('backend.staff.index');
        Route::any('/edit/{id}', [StaffController::class, 'edit'])->name('backend.staff.edit');
        Route::any('/delete/{id}', [StaffController::class, 'delete'])->name('backend.staff.delete');
    });

    //quản lý shopID
    Route::group(['prefix' => 'shopid'], function () {
        Route::get('/', [BranchController::class, 'index'])->name('backend.brands.index');
        Route::get('/create', [BranchController::class, 'create'])->name('backend.brands.create');
        Route::post('/store', [BranchController::class, 'store'])->name('backend.brands.store');
        Route::get('/edit/{id}', [BranchController::class, 'edit'])->name('backend.brands.edit');
        Route::post('/update/{id}', [BranchController::class, 'update'])->name('backend.brands.update');
        Route::post('/delete', [BranchController::class, 'destroy'])->name('backend.brands.delete');
    });

    // gán shopId trong edit user
    Route::group(['prefix' => 'assignshopid'], function () {
        Route::get('/', [BranchController::class, 'assignshopid'])->name('backend.brands.assignshopid');
        Route::get('/edit/{id}', [BranchController::class, 'editassignshopid'])->name('backend.brands.editassignshopid');
        Route::post('/update/{id}', [BranchController::class, 'updateassignshopid'])->name('backend.brands.updateassignshopid');
    });

    // quản lý đơn hàng
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrdersController::class, 'index'])->name('backend.orders.index');
        Route::get('/search', [OrdersController::class, 'search'])->name('backend.orders.search');
        Route::any('/excel', [OrdersController::class, 'ExportExcel'])->name('backend.orders.excel');
        Route::any('/export', [OrdersController::class, 'export'])->name('backend.orders.export');
    });

    // quản lý email nhận tin thông báo
    Route::any('/subscribers', [SubscribersController::class, 'index'])->name('backend.subscribers.index');

    // báo cáo live admin
    Route::group(['prefix' => 'ops-live'], function () {
        Route::get('/', [ReportController::class, 'index'])->name('backend.ops-live.index');
    });
});

// Trang index
Route::any('/', [IndexController::class, 'index'])->name('frontend.index');
Route::any('/callback', [IndexController::class, 'callback'])->name('frontend.callback');


Route::group(['prefix' => 'user'], function () {
    // Dashboard user    
    Route::get('/', [UserController::class, 'index'])->name('user.index');

    // Quản lý đơn hàng
    Route::get('/orders', [UserController::class, 'orders'])->name('user.order.index');
    Route::get('/orders/count-status', [UserController::class, 'countStatusOrders'])->name('user.order.countStatus');
    Route::get('/orders/add', [UserController::class, 'addOrder'])->name('user.order.add');
    Route::post('/orders/add', [UserController::class, 'storeOrder'])->name('user.order.store');
    Route::get('/orders/view/{id}', [UserController::class, 'viewOrder'])->name('user.order.view');
    Route::get('/orders/edit/{id}', [UserController::class, 'editOrder'])->name('user.order.edit');
    Route::post('/orders/edit/{id}', [UserController::class, 'updateOrder'])->name('user.order.update');

    //Reset cache
    Route::post('reset-cache', [UserController::class, 'resetCache'])->name('user.reset.cache');

    // channel orthers
    Route::get('/channel-sell', [OrderController::class, 'list'])->name('user.channel.index');

    //Cửa hàng kho
    Route::get('/mystore', [UserController::class, 'myStore'])->name('user.mystore');

    // Sản phẩm cửa hàng
    Route::get('/mystore/products', [UserController::class, 'addProduct'])->name('user.mystore.addproduct');
    Route::post('/mystore/products', [UserController::class, 'storeProduct'])->name('user.mystore.storeproduct');
    Route::get('/get-products', [UserController::class, 'getProduct'])->name('user.mystore.getproduct');
    Route::get('/get-products/{id}', [UserController::class, 'getProductById'])->name('user.mystore.getproductbyid');
    Route::get('/get-products/edit/{id}', [UserController::class, 'editProduct'])->name('user.mystore.editproduct');
    Route::post('/get-products/edit/{id}', [UserController::class, 'updateProduct'])->name('user.mystore.updateproduct');

    // Quản lý cửa hàng kho
    Route::get('/mystore/addstore', [UserController::class, 'addStore'])->name('user.mystore.addstore');
    Route::post('/mystore/addstore', [UserController::class, 'storeStore'])->name('user.mystore.storestore');
    Route::get('/mystore/view/{id}', [UserController::class, 'viewStore'])->name('user.mystore.view');
    Route::get('/mystore/edit/{id}', [UserController::class, 'editStore'])->name('user.mystore.edit');
    Route::post('/mystore/edit/{id}', [UserController::class, 'updateStore'])->name('user.mystore.update');
    Route::post('/address/setdefault', [UserController::class, 'setDefault'])->name('user.mystore.setDefault');

    //tính phí vận chuyển
    Route::post('/calculate-fee', [UserController::class, 'calculateFee'])->name('user.calculate.fee');

    // Show đối soát của user
    Route::get('/doisoat', [UserController::class, 'doisoat'])->name('user.doisoat');
    Route::get('/doisoat/{id}', [UserController::class, 'viewDoiSoat'])->name('user.doisoat.view');

    // In ra tài liệu excel
    Route::post('/order/bulk-cancel', [UserController::class, 'bulkCancel'])->name('user.order.bulk_cancel');
    Route::post('/order/bulk-print', [UserController::class, 'bulkPrint'])->name('user.order.bulk_print');
    Route::post('/order/bulk-export', [UserController::class, 'bulkExport'])->name('user.order.bulk_export');
    Route::post('/order/bulk-return', [UserController::class, 'bulkReturn'])->name('user.order.bulk_return');
    Route::post('/order/bulk-delivery-again', [UserController::class, 'bulkDeliveryAgain'])->name('user.order.bulk_delivery_again');
    Route::post('/order/bulk-export-many', [UserController::class, 'bulkExportMany'])->name('user.order.bulk_export_many');
    Route::post('/doisoat/bulk-export-many', [UserController::class, 'doisoatbulkExportMany'])->name('user.doisoat.bulk_export_many');
    Route::post('/doisoatuser/bulk-export-many', [UserController::class, 'doisoatUser'])->name('user.doisoatUser.bulk_export_many');

    // Call api hoàn hàng
    Route::post('/order/return', [UserController::class, 'returnOrder'])->name('user.order.return');

    // Call api giao lại
    Route::post('/order/delivery-again', [UserController::class, 'deliveryAgain'])->name('user.order.delivery-again');

    //Hồ sơ cá nhân user
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/profile/send-otp', [UserController::class, 'sendProfileOTP'])->name('user.profile.sendOTP');
    Route::post('/profile/verify-otp', [UserController::class, 'verifyProfileOTP'])->name('user.profile.verifyOTP');
});
