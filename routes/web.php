<?php


use App\Models\ProductType;
use App\Utils\Category;
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

Route::any('/partner/update_ahamove_status', 'Backend\DashboardController@webhook')->name('backend.webhook');
Route::any('/', 'Frontend\IndexController@index')->name('frontend.index');
Route::any('/callback', 'Frontend\IndexController@callback')->name('frontend.callback');
Route::any('/chinh-sach-doi-tra-hang.html', 'Frontend\IndexController@policy')->name('frontend.info.policy');
Route::any('/cau-hoi-thuong-gap.html', 'Frontend\IndexController@faq')->name('frontend.info.faq');
Route::any('/dieu-khoan-su-dung.html', 'Frontend\IndexController@termOfUse')->name('frontend.info.termOfUse');
Route::any('/huong-dan-dat-hang-doi-tra.html', 'Frontend\IndexController@orderingGuide')->name('frontend.info.orderingGuide');
Route::any('/chinh-sach-bao-mat-thong-tin-khach-hang.html', 'Frontend\IndexController@informationPrivacy')->name('frontend.info.informationPrivacy');
Route::any('/chinh-sach-van-chuyen-giao-nhan.html', 'Frontend\IndexController@shippingPolicy')->name('frontend.info.shippingPolicy');

Route::any('/ajax/check-discount-code', 'Frontend\AjaxController@checkDiscount')->name('frontend.ajax.checkDiscount');


Route::any('/gio-hang.html', 'Frontend\CartController@index')->name('frontend.cart.index');
Route::any('/lich-su-thanh-toan.html', 'Frontend\SalaryController@index')->name('frontend.salary.index');
Route::any('/dat-hang.html', 'Frontend\CartController@checkout')->name('frontend.cart.checkout');
Route::any('/tra-cuu-don-hang.html', 'Frontend\OrderController@tracking')->name('frontend.order.tracking');
Route::any('/quan-ly-he-thong', 'Frontend\ProductController@system')->name('frontend.products.system');
Route::any('/ajax-user', 'Frontend\ProductController@ajaxUser')->name('frontend.products.ajaxUser');

Route::group(['prefix' => 'ajax'], function () {
    Route::post('/cart/add', 'Frontend\AjaxController@addItemCart')->name('frontend.ajax.cart.add');
    Route::any('/check-discount-point', 'Frontend\AjaxController@checkDiscountPoint')->name('frontend.ajax.checkDiscountPoint');

    Route::post('/wishlist/add', 'Frontend\AjaxController@addWishlist')->name('frontend.ajax.addWishlist');
    Route::post('/wishlist/delete', 'Frontend\AjaxController@deleteWishlist')->name('frontend.ajax.deleteWishlist');
    Route::post('/address/delete', 'Frontend\AjaxController@deleteAddress')->name('frontend.ajax.deleteAddress');
    Route::post('/address/add', 'Frontend\AjaxController@addAddress')->name('frontend.ajax.addAddress');
    Route::get('/address/getById', 'Frontend\AjaxController@getAddressById')->name('frontend.ajax.getAddressById');
    Route::post('/ajax/image', 'Frontend\AjaxController@uploadImage')->name('frontend.ajax.uploadImage');
    Route::post('/cart/delete', 'Frontend\AjaxController@deleteItemCart')->name('frontend.ajax.cart.delete');
    Route::post('/cart/clear', 'Frontend\AjaxController@clearCart')->name('frontend.ajax.cart.clear');

    Route::get('/product-variation', 'Frontend\ProductController@variation')->name('frontend.ajax.variation');

    Route::any('/subscribe-email.html', 'Frontend\AjaxController@subscribeEmail')->name('frontend.ajax.subscribeEmail');
});
Route::get('/danh-sach-yeu-thich', 'Frontend\ProductController@wishlist')->name('frontend.product.wishlist');
Route::get('/san-pham', 'Frontend\ProductController@main')->name('frontend.product.main');
Route::get('/gioi-thieu.html', 'Frontend\PagesController@about')->name('frontend.page.about');
Route::get('/lien-he.html', 'Frontend\PagesController@contact')->name('frontend.page.contact');

Route::get('webview/{id}', 'Frontend\PostsController@webviewDetail')->name('post.webview.detail');
Route::get('webview/product/{id}', 'Frontend\ProductController@webviewDetail')->name('product.webview.detail');

Route::group(['prefix' => 'user'], function () {

    Route::get('/', 'Frontend\UserController@index')->name('user.index');
    Route::get('/orders', 'Frontend\UserController@orders')->name('user.order.index');
    Route::get('/orders/count-status', 'Frontend\UserController@countStatusOrders')->name('user.orders.countStatus');
    Route::get('/orders/add', 'Frontend\UserController@addOrder')->name('user.order.add');
    Route::post('/orders/add', 'Frontend\UserController@storeOrder')->name('user.order.store');
    Route::get('/orders/view/{id}', 'Frontend\UserController@viewOrder')->name('user.order.view');
    Route::get('/orders/edit/{id}', 'Frontend\UserController@editOrder')->name('user.order.edit');
    Route::post('/orders/edit/{id}', 'Frontend\UserController@updateOrder')->name('user.order.update');

    Route::post('reset-cache', 'Frontend\UserController@resetCache')->name('user.reset.cache');

    Route::get('/channel-sell', 'Frontend\OrderController@list')->name('user.channel.index');

    Route::get('/get-user-info', 'Frontend\UserController@checkUser')->name('user.order.checkuser');
    Route::post('/api/get-districts', 'Frontend\UserController@getDistrict')->name('user.order.getDistrict');
    Route::post('/api/get-wards', 'Frontend\UserController@getWard')->name('user.order.getWard');

    Route::get('/mystore', 'Frontend\UserController@myStore')->name('user.mystore');
    Route::get('/mystore/addproduct', 'Frontend\UserController@addProduct')->name('user.mystore.addproduct');
    Route::post('/mystore/addproduct', 'Frontend\UserController@storeProduct')->name('user.mystore.storeproduct');
    Route::get('/get-products', 'Frontend\UserController@getProduct')->name('user.mystore.getproduct');
    Route::get('/get-products/{id}', 'Frontend\UserController@getProductById')->name('user.mystore.getproductbyid');
    Route::get('/get-products/edit/{id}', 'Frontend\UserController@editProduct')->name('user.mystore.editproduct');
    Route::post('/get-products/edit/{id}', 'Frontend\UserController@updateProduct')->name('user.mystore.updateproduct');

    Route::get('/mystore/addstore', 'Frontend\UserController@addStore')->name('user.mystore.addstore');
    Route::post('/mystore/addstore', 'Frontend\UserController@storeStore')->name('user.mystore.storestore');
    Route::get('/mystore/view/{id}', 'Frontend\UserController@viewStore')->name('user.mystore.view');
    Route::get('/mystore/edit/{id}', 'Frontend\UserController@editStore')->name('user.mystore.edit');
    Route::post('/mystore/edit/{id}', 'Frontend\UserController@updateStore')->name('user.mystore.update');
    Route::post('/address/set-default', 'Frontend\UserController@setDefault')->name('user.mystore.setDefault');

    Route::post('/calculate-fee', 'Frontend\UserController@calculateFee')->name('user.calculate.fee');
    Route::get('/doisoat', 'Frontend\UserController@doisoat')->name('user.doisoat');
    Route::get('/doisoat/{id}', 'Frontend\UserController@viewDoiSoat')->name('user.doisoat.view');

    Route::post('/order/bulk-cancel', 'Frontend\UserController@bulkCancel')->name('user.order.bulk_cancel');
    Route::post('/order/bulk-print', 'Frontend\UserController@bulkPrint')->name('user.order.bulk_print');
    Route::post('/order/bulk-export', 'Frontend\UserController@bulkExport')->name('user.order.bulk_export');
    Route::post('/order/bulk-return', 'Frontend\UserController@bulkReturn')->name('user.order.bulk_return');
    Route::post('/order/bulk-delivery-again', 'Frontend\UserController@bulkDeliveryAgain')->name('user.order.bulk_delivery_again');

    Route::post('/order/bulk-export-many', 'Frontend\UserController@bulkExportMany')->name('user.order.bulk_export_many');
    Route::post('/doisoat/bulk-export-many', 'Frontend\UserController@doisoatbulkExportMany')->name('user.doisoat.bulk_export_many');
    Route::post('/doisoatuser/bulk-export-many', 'Frontend\UserController@doisoatUser')->name('user.doisoatUser.bulk_export_many');

    Route::post('/order/return', 'Frontend\UserController@returnOrder')->name('user.order.return');
    Route::post('/order/delivery-again', 'Frontend\UserController@deliveryAgain')->name('user.order.delivery-again');

    Route::get('/profile', 'Frontend\UserController@profile')->name('user.profile');
    Route::post('/profile', 'Frontend\UserController@updateProfile')->name('user.profile.update');
    Route::post('/profile/send-otp', 'Frontend\UserController@sendProfileOTP')->name('user.profile.sendOTP');
    Route::post('/profile/verify-otp', 'Frontend\UserController@verifyProfileOTP')->name('user.profile.verifyOTP');

    Route::any('/register', 'Frontend\UserController@register')->name('frontend.user.register');
    Route::any('/bank', 'Frontend\UserController@bank')->name('frontend.user.bank');
    Route::any('/activate/{token}', 'Frontend\UserController@activate')->name('frontend.user.activate');

    Route::any('/forgot-password', 'Frontend\UserController@forgotPassword')->name('frontend.user.forgotPassword');
    Route::any('/reset-password/{token}', 'Frontend\UserController@resetPassword')->name('frontend.user.resetPassword');

    Route::group(['middleware' => 'frontend'], function () {
        Route::any('/cap-nhat-thong-tin.html', 'Frontend\UserController@index')->name('frontend.user.account');
        Route::any('/thong-tin-tai-khoan.html', 'Frontend\UserController@profile')->name('frontend.user.profile');
        Route::any('/order', 'Frontend\UserController@order')->name('frontend.user.order');
        Route::any('/address', 'Frontend\UserController@address')->name('frontend.user.address');
        Route::any('/address/edit/{id}', 'Frontend\UserController@editAddress')->name('frontend.user.editAddress');
        Route::any('/address/edit/{id}', 'Frontend\UserController@editAddress')->name('frontend.user.editAddress');
        Route::any('/logout', 'Frontend\UserController@logout')->name('frontend.user.logout');
    });
});


Route::get('sitemap.xml', function () {

    $sitemap = App::make('sitemap');

    $sitemap->setCache('laravel.sitemap', 60);

    // check if there is cached sitemap and build new only if is not
    if (!$sitemap->isCached()) {
        // add item to the sitemap (url, date, priority, freq)
        $sitemap->add(URL::to('/'), '2012-08-25T20:10:00+02:00', '1.0', 'daily');
        $sitemap->add(route('frontend.product.main'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');
        $sitemap->add(route('frontend.page.about'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');
        $sitemap->add(route('frontend.news.main'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');

        $all_category_db = ProductType::get_by_where([
            'status'     => 1,
            'assign_key' => true,
        ]);

        $category_tree = Category::buildTreeType($all_category_db);

        $all_category = Category::tree_to_array($category_tree);

        \View::share('category_tree', $category_tree);
        \View::share('all_category', $all_category);

        foreach ($all_category as $cate) {
            $sitemap->add(url($cate['link']), $cate['created_at'], '0.8', 'monthly');
        }

        $products = App\Models\Product::get_by_where([
            'status' => 1,
            'limit'  => 1000,
        ]);

        foreach ($products as $product) {
            $sitemap->add(url(product_link($product->slug, $product->id, $product->product_type_id)), $product->created_at, '0.8', 'monthly');
        }

        $posts = App\Models\Post::get_by_where([
            'status' => App\Models\Post::STATUS_SHOW,
            'limit'  => 1000,
        ]);

        // add every post to the sitemap
        foreach ($posts as $post) {
            $sitemap->add(url($post->post_link()), $post->created_at, '0.8', 'monthly');
        }
    }
    return $sitemap->render('xml');
});


Route::get('/{slug}', 'Frontend\ProductController@route')
    ->name('frontend.product.route')
    ->where('slug', '(.*)');
