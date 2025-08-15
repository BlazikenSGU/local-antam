<?php

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

use App\Models\ProductType;
use App\Utils\Category;

Route::group(['prefix' => 'admin'], function () {

    Route::any('/login', 'Backend\AuthController@login')->name('backend.login');
    Route::any('/logout', 'Backend\AuthController@logout')->name('backend.logout');

    Route::group(['middleware' => 'backend'], function () {

        Route::any('/', 'Backend\DashboardController@index')->name('backend.dashboard');

        Route::get('/comming', function () {
            return view('backend.mobie.comingsoon');
        });

        Route::any('/notification', 'Backend\NotificationController@index')->name('backend.notification.index')->middleware('permission:notification.index');
        Route::any('/notification/add', 'Backend\NotificationController@add')->name('backend.notification.add')->middleware('permission:notification.add');
        Route::any('/notification/delete/{id}', 'Backend\NotificationController@delete')->name('backend.notification.delete');
        Route::any('/notification/push/{id}', 'Backend\NotificationController@pushAgent')->name('backend.notification.push');

        Route::any('/profile', 'Backend\UsersController@profile')->name('backend.users.profile');

        Route::any('/users', 'Backend\UsersController@index')->name('backend.users.index');
        Route::any('/users/add', 'Backend\UsersController@add')->name('backend.users.add');
        Route::any('/users/edit/{id}', 'Backend\UsersController@edit')->name('backend.users.edit');
        Route::any('/users/delete/{id}', 'Backend\UsersController@delete')->name('backend.users.delete');

        Route::group(['prefix' => 'salary'], function () {
            Route::any('/', 'Backend\SalaryController@index')->name('backend.salary.index');
            Route::any('/export', 'Backend\SalaryController@export')->name('backend.salary.export');
            Route::any('/delete/{id}', 'Backend\SalaryController@delete')->name('backend.salary.delete');
        });

        Route::group(['prefix' => 'doi_soat'], function () {
            Route::get('/', 'Backend\DoiSoatController@index')->name('backend.doi_soat.index');
            Route::any('', 'Backend\DoiSoatController@index')->name('backend.doi_soat.index');
            Route::any('/export', 'Backend\DoiSoatController@export')->name('backend.doi_soat.export');
            Route::any('/import', 'Backend\DoiSoatController@import')->name('backend.doi_soat.import');
            Route::any('/detail/{user_id}', 'Backend\DoiSoatController@detail')->name('backend.doi_soat.detail');
            Route::any('/showFormImport', 'Backend\DoiSoatController@showFormImport')->name('backend.doi_soat.showFormImport');
            Route::any('/run', 'Backend\DoiSoatController@run')->name('backend.doi_soat.run');

        });


        Route::group(['prefix' => 'staff'], function () {
            Route::any('', 'Backend\StaffController@index')->name('backend.staff.index')->middleware('permission:staff.index');
            Route::any('/add', 'Backend\StaffController@add')->name('backend.staff.add')->middleware('permission:staff.add');
            Route::any('/edit/{id}', 'Backend\StaffController@edit')->name('backend.staff.edit')->middleware('permission:staff.edit');
            Route::any('/delete/{id}', 'Backend\StaffController@delete')->name('backend.staff.delete')->middleware('permission:staff.delete');
        });


        Route::group(['prefix' => 'products'], function () {
            Route::any('', 'Backend\Product\ProductsController@index')->name('backend.products.index')->middleware('permission:products.index');
            Route::any('/inventory', 'Backend\Product\ProductsController@inventory')->name('backend.products.inventory');
            Route::any('/add/{type_id}', 'Backend\Product\ProductsController@add')->name('backend.products.add')->middleware('permission:products.add');
            Route::any('/edit/{id}-{type_id}', 'Backend\Product\ProductsController@edit')->name('backend.products.edit')->middleware('permission:products.edit');
            Route::post('/ajax/delete', 'Backend\Product\ProductsController@ajaxdelete')->name('backend.products.ajax.delete');

            Route::post('/ajax/approved', 'Backend\Product\ProductsController@approved')->name('backend.products.ajax.approved');
            Route::post('/ajax/un_approved', 'Backend\Product\ProductsController@un_approved')->name('backend.products.ajax.un_approved');


            Route::group(['prefix' => 'type'], function () {
                Route::any('/', 'Backend\Product\TypeController@index')->name('backend.products.type.index')->middleware('permission:products.type.index');
                Route::any('/add', 'Backend\Product\TypeController@add')->name('backend.products.type.add')->middleware('permission:products.type.add');
                Route::any('/edit/{id}', 'Backend\Product\TypeController@edit')->name('backend.products.type.edit')->middleware('permission:products.type.edit');
                Route::post('/delete', 'Backend\Product\TypeController@delete')->name('backend.products.type.del')->middleware('permission:products.type.del');
                Route::any('/sort', 'Backend\Product\TypeController@sort')->name('backend.products.type.sort')->middleware('permission:products.type.index');
            });

            Route::group(['prefix' => 'attributes'], function () {
                Route::any('/', 'Backend\Product\Attributes\IndexController@index')->name('backend.products.attributes.index');
                Route::any('/add', 'Backend\Product\Attributes\IndexController@add')->name('backend.products.attributes.add');
                Route::any('/edit/{id}', 'Backend\Product\Attributes\IndexController@edit')->name('backend.products.attributes.edit');
                Route::post('/delete', 'Backend\Product\Attributes\IndexController@delete')->name('backend.products.attributes.del');
                Route::any('/sort', 'Backend\Product\Attributes\IndexController@sort')->name('backend.products.attributes.sort');

                Route::group(['prefix' => 'values'], function () {
                    Route::any('{attribute_id}/', 'Backend\Product\Attributes\ValuesController@index')->name('backend.products.attributes.values.index');
                    Route::any('/{attribute_id}/add', 'Backend\Product\Attributes\ValuesController@add')->name('backend.products.attributes.values.add');
                    Route::any('/{attribute_id}/edit/{value_id}', 'Backend\Product\Attributes\ValuesController@edit')->name('backend.products.attributes.values.edit');
                    Route::post('/{attribute_id}/delete', 'Backend\Product\Attributes\ValuesController@delete')->name('backend.products.attributes.values.del');
                    Route::post('/{attribute_id}/sort', 'Backend\Product\Attributes\ValuesController@sort')->name('backend.products.attributes.values.sort');
                });
            });
        });

        Route::any('/notification', 'Backend\NotificationController@index')->name('backend.notification.index')->middleware('permission:notification.index');
        Route::any('/notification/add', 'Backend\NotificationController@add')->name('backend.notification.add')->middleware('permission:notification.add');

        Route::group(['prefix' => 'location'], function () {
            Route::any('/province', 'Backend\Location\ProvinceController@index')->name('backend.location.province.index');
            Route::any('/province/add', 'Backend\Location\ProvinceController@add')->name('backend.location.province.add');
            Route::any('/province/edit/{id}', 'Backend\Location\ProvinceController@edit')->name('backend.location.province.edit');
            Route::any('/province/del/{id}', 'Backend\Location\ProvinceController@delete')->name('backend.location.province.del');

            Route::any('/district', 'Backend\Location\DistrictController@index')->name('backend.location.district.index');
            Route::any('/district/add', 'Backend\Location\DistrictController@add')->name('backend.location.district.add');
            Route::any('/district/edit/{id}', 'Backend\Location\DistrictController@edit')->name('backend.location.district.edit');
            Route::any('/district/del/{id}', 'Backend\Location\DistrictController@delete')->name('backend.location.district.del');

            Route::any('/ward', 'Backend\Location\WardController@index')->name('backend.location.ward.index');
            Route::any('/ward/add', 'Backend\Location\WardController@add')->name('backend.location.ward.add');
            Route::any('/ward/edit/{id}', 'Backend\Location\WardController@edit')->name('backend.location.ward.edit');
            Route::any('/ward/del/{id}', 'Backend\Location\WardController@delete')->name('backend.location.ward.del');
        });

        //ajax
        Route::group(['prefix' => 'ajax'], function () {
            Route::any('/ajax-shipping-fee', 'Backend\AjaxController@shippingFee')->name('backend.ajax.shipping-fee');

            Route::any('/search-user', 'Backend\AjaxController@searchUser')->name('backend.ajax.searchUser');
            Route::post('/add-street', 'Backend\AjaxController@addStreet')->name('backend.ajax.addStreet');
            Route::post('/upload-image', 'Backend\AjaxController@uploadImage')->name('backend.ajax.uploadImage');
            Route::post('/remove-image', 'Backend\AjaxController@removeImage')->name('backend.ajax.removeImage');

            Route::post('/product/variation/delete', 'Backend\Product\ProductsController@deleteVariation')->name('backend.products.variation.delete');
            Route::post('/product/variation/add', 'Backend\Product\ProductsController@createVariation')->name('backend.products.variation.add');

            Route::get('/variation-image', 'Backend\Product\ProductsController@getVariationImage')->name('backend.products.variation.image');
            Route::post('/variation-image', 'Backend\Product\ProductsController@uploadVariationImage')->name('backend.products.variation.image.upload');
            Route::post('/variation-image/delete', 'Backend\Product\ProductsController@deleteVariationImage')->name('backend.products.variation.image.delete');
            Route::post('/variation-image/sort', 'Backend\Product\ProductsController@sortVariationImage')->name('backend.products.variation.image.sort');

            Route::get('/variation/value', 'Backend\AjaxController@getVariationValue')->name('backend.ajax.variation.value');
            Route::post('/variation/create', 'Backend\AjaxController@createVariation')->name('backend.ajax.variation.create');
            Route::post('/add-salary', 'Backend\AjaxController@addSalary')->name('backend.ajax.addSalary');
            Route::post('/ajax-salary', 'Backend\AjaxController@ajaxSalary')->name('backend.ajax.ajaxSalary');
            Route::post('/ajax-salary/agree', 'Backend\AjaxController@ajaxSalaryAgree')->name('backend.ajax.ajaxSalaryAgree');
            Route::post('/payed-salary', 'Backend\AjaxController@paySalary')->name('backend.ajax.paySalary');

            Route::any('/add/productOrder', 'Backend\OrdersController@AddProductOrder')->name('backend.orders.addproduct.order');
            Route::any('/editproduct', 'Backend\OrdersController@editProduct')->name('backend.orders.editproduct.order');
            Route::any('/deleteproduct', 'Backend\OrdersController@deleteproduct')->name('backend.orders.deleteproduct.order');
            Route::any('/update', 'Backend\OrdersController@update')->name('backend.orders.update');
            Route::any('/changeAddress', 'Backend\AjaxController@changeAddress')->name('backend.ajax.changeAddress');
            Route::any('/list/cancel', 'Backend\OrdersController@cancellist')->name('backend.orders.cancel.list');
            Route::any('/ajaxData', 'Backend\OrdersController@ajaxData')->name('backend.orders.ajaxData');
            Route::any('/update/order/{id}', 'Backend\OrdersController@updateOrder')->name('backend.orders.updateOrder');
            Route::any('/update/cod_amount/{id}', 'Backend\OrdersController@UpdateCODamount')->name('backend.orders.update.cod.amount');
        });

        Route::group(['prefix' => 'brand'], function () {
            Route::get('/', 'Backend\Branch\BranchController@index')->name('backend.brands.index')->middleware('permission:brands.index');
            Route::get('/create', 'Backend\Branch\BranchController@create')->name('backend.brands.create')->middleware('permission:brands.index');
            Route::post('/store', 'Backend\Branch\BranchController@store')->name('backend.brands.store')->middleware('permission:brands.index');
            Route::post('/ajax-wareHouse', 'Backend\Branch\BranchController@ajaxWareHouse')->name('backend.brands.ajaxWareHouse')->middleware('permission:brands.index');
            Route::get('/edit/{id}', 'Backend\Branch\BranchController@edit')->name('backend.brands.edit')->middleware('permission:brands.index');
            Route::post('/update/{id}', 'Backend\Branch\BranchController@update')->name('backend.brands.update')->middleware('permission:brands.index');
            Route::post('/delete', 'Backend\Branch\BranchController@destroy')->name('backend.brands.delete')->middleware('permission:brands.index');
        });


        Route::group(['prefix' => 'warehouses'], function () {
            Route::post('/ajaxResult', 'Backend\Branch\WarehouseController@ajaxResult')->name('backend.warehouses.ajaxResult');

            Route::any('/', 'Backend\Branch\WarehouseController@index')->name('backend.warehouses.index')->middleware('permission:warehouses.index');
            Route::get('/create', 'Backend\Branch\WarehouseController@create')->name('backend.warehouses.create')->middleware('permission:warehouses.index');
            Route::post('/store', 'Backend\Branch\WarehouseController@store')->name('backend.warehouses.store')->middleware('permission:warehouses.index');
            Route::get('/edit/{id}', 'Backend\Branch\WarehouseController@edit')->name('backend.warehouses.edit')->middleware('permission:warehouses.index');
            Route::post('/update/{id}', 'Backend\Branch\WarehouseController@update')->name('backend.warehouses.update')->middleware('permission:warehouses.index');
            Route::post('/delete', 'Backend\Branch\WarehouseController@destroy')->name('backend.warehouses.delete')->middleware('permission:warehouses.index');
            Route::post('/ajaxInsertProduct', 'Backend\Branch\WarehouseController@ajaxInsertProduct')->name('backend.warehouses.ajaxInsertProduct')->middleware('permission:warehouses.index');
            Route::post('/ajaxDetail', 'Backend\Branch\WarehouseController@ajaxDetail')->name('backend.warehouses.ajaxDetail')->middleware('permission:warehouses.index');
            Route::post('/district-ajax', 'Backend\Branch\WarehouseController@ajaxLoadDistrict')->name('backend.warehouses.ajaxLoadDistrict')->middleware('permission:warehouses.index');
            Route::post('/ward-ajax', 'Backend\Branch\WarehouseController@ajaxLoadWard')->name('backend.warehouses.ajaxLoadWard')->middleware('permission:warehouses.index');
            Route::post('/searchProduct', 'Backend\Branch\WarehouseController@searchProduct')->name('backend.warehouses.searchProduct')->middleware('permission:warehouses.index');
            Route::post('/getDanhMuc', 'Backend\Branch\WarehouseController@getDanhMuc')->name('backend.warehouses.getDanhMuc')->middleware('permission:warehouses.index');
            Route::post('/checkCoupon', 'Backend\Branch\WarehouseController@checkCoupon')->name('backend.warehouses.checkCoupon')->middleware('permission:warehouses.index');


            Route::any('/detail/{id}', 'Backend\Branch\WarehouseController@detail')->name('backend.warehouses.detail');
        });


        Route::group(['prefix' => 'post'], function () {
            Route::any('/', 'Backend\PostsController@index')->name('backend.posts.index')->middleware('permission:posts.index');
            Route::any('/add', 'Backend\PostsController@add')->name('backend.posts.add')->middleware('permission:posts.add');
            Route::any('/edit/{id}', 'Backend\PostsController@edit')->name('backend.posts.edit')->middleware('permission:posts.edit');
            Route::any('/delete/{id}', 'Backend\PostsController@delete')->name('backend.posts.del')->middleware('permission:posts.del');

            Route::group(['prefix' => 'category'], function () {
                Route::any('/', 'Backend\PostsCategoryController@index')->name('backend.posts.category.index')->middleware('permission:posts.category.index');
                Route::any('/sort', 'Backend\PostsCategoryController@sort')->name('backend.posts.category.sort')->middleware('permission:posts.category.index');
                Route::any('/add', 'Backend\PostsCategoryController@add')->name('backend.posts.category.add')->middleware('permission:posts.category.add');
                Route::any('/edit/{id}', 'Backend\PostsCategoryController@edit')->name('backend.posts.category.edit')->middleware('permission:posts.category.edit');
                Route::any('/delete', 'Backend\PostsCategoryController@delete')->name('backend.posts.category.del')->middleware('permission:posts.category.del');
            });
        });
        Route::group(['prefix' => 'policy'], function () {
            Route::any('/', 'Backend\PolicyController@index')->name('backend.policy.index')->middleware('permission:policy.index');
            Route::any('/add', 'Backend\PolicyController@add')->name('backend.policy.add')->middleware('permission:policy.add');
            Route::any('/edit/{id}', 'Backend\PolicyController@edit')->name('backend.policy.edit')->middleware('permission:policy.edit');
            Route::any('/delete/{id}', 'Backend\PolicyController@delete')->name('backend.policy.del')->middleware('permission:policy.del');

            Route::group(['prefix' => 'category'], function () {
                Route::any('/', 'Backend\PolicyCategoryController@index')->name('backend.policy.category.index')->middleware('permission:policy.category.index');
                Route::any('/sort', 'Backend\PolicyCategoryController@sort')->name('backend.policy.category.sort')->middleware('permission:policy.category.index');
                Route::any('/add', 'Backend\PolicyCategoryController@add')->name('backend.policy.category.add')->middleware('permission:policy.category.add');
                Route::any('/edit/{id}', 'Backend\PolicyCategoryController@edit')->name('backend.policy.category.edit')->middleware('permission:policy.category.edit');
                Route::any('/delete', 'Backend\PolicyCategoryController@delete')->name('backend.policy.category.del')->middleware('permission:policy.category.del');
            });
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::any('/', 'Backend\OrdersController@index')->name('backend.orders.index')->middleware('permission:orders.index');
            Route::any('/search/mobie', 'Backend\OrdersController@index1')->name('backend.orders.index1');

            Route::any('/add', 'Backend\OrdersController@add')->name('backend.orders.add');
            Route::any('/create/{id}', 'Backend\OrdersController@create')->name('backend.orders.create')->middleware('permission:orders.index');
            Route::any('/edit/{id}', 'Backend\OrdersController@edit')->name('backend.orders.edit');
            Route::any('/importExcel', 'Backend\OrdersController@createExcel')->name('backend.orders.create.excel')->middleware('permission:orders.excel');
            Route::any('/preview', 'Backend\OrdersController@ordersPreview')->name('backend.orders.preview');
            Route::any('/checkout/{id}', 'Backend\OrdersController@orderscheckout')->name('backend.orders.orderscheckout');

            Route::any('/changeAddress/{id}', 'Backend\OrdersController@changeAddress')->name('backend.orders.changeAddress');
            Route::any('/returnAddress/{id}', 'Backend\OrdersController@returnAddress')->name('backend.orders.returnAddress');

            //trả hàng
            Route::any('/returnOrder/{id}', 'Backend\OrdersController@returnOrder')->name('backend.orders.return');
            //giao lai
            Route::any('/storingOrder/{id}', 'Backend\OrdersController@storingOrder')->name('backend.orders.storing');
            // xuất excel
            Route::any('/excel', 'Backend\OrdersController@ExportExcel')->name('backend.orders.excel');


//
//            Route::any('/export', 'Backend\OrdersController@export')->name('backend.orders.export')->middleware('permission:orders.index');
//            Route::post('/updatePayment', 'Backend\OrdersController@updatePayment')->name('backend.orders.updatePayment')->middleware('permission:orders.index');
//            Route::post('/updateOrder', 'Backend\OrdersController@updateOrder')->name('backend.orders.updateOrder');
//            Route::post('/deleteproduct', 'Backend\OrdersController@deleteproduct')->name('backend.orders.deleteproduct');
//            Route::any('/{id}', 'Backend\OrdersController@detail')->name('backend.orders.detail')->middleware('permission:orders.index');
            Route::any('/delete/{id}', 'Backend\OrdersController@delete')->name('backend.orders.delete');
            Route::any('/cancel/{id}', 'Backend\OrdersController@cancel')->name('backend.orders.cancel');
            Route::any('/order/editorder', 'Backend\OrdersController@editorder')->name('backend.orders.edit.order');
            Route::any('/history', 'Backend\OrdersController@history')->name('backend.orders.history');
            Route::any('/export', 'Backend\OrdersController@export')->name('backend.orders.export');
            Route::any('/download/file/{id}', 'Backend\OrdersController@downloadfile')->name('backend.orders.downloadfile');
//
//            Route::any('detailTemp/{id}', 'Backend\OrdersController@detailTemp')->name('backend.orders.detailTemp')->middleware('permission:orders.index');
            // lên đơn băng excel
        });
        Route::group(['prefix' => 'shop'], function () {
            Route::any('/{id}', 'Backend\ShopController@index')->name('backend.shop.index');
        });

        Route::group(['prefix' => 'cod'], function () {
            Route::any('/', 'Backend\ShopController@cod')->name('backend.shop.cod')->middleware('permission:backend.cod');
        });
        Route::group(['prefix' => 'ticket'], function () {
            Route::any('/', 'Backend\ShopController@ticket')->name('backend.shop.ticket')->middleware('permission:backend.report');
            Route::any('/create', 'Backend\ShopController@ticketcreate')->name('backend.shop.ticket.create');
        });


        Route::group(['prefix' => 'menu'], function () {
            Route::group(['prefix' => 'products'], function () {
                Route::any('/', 'Backend\MenuController@index')->name('backend.menu.index');
                Route::any('/sort', 'Backend\MenuController@sort')->name('backend.menu.sort');
                Route::any('/add', 'Backend\MenuController@add')->name('backend.menu.add');
                Route::any('/edit/{id}', 'Backend\MenuController@edit')->name('backend.menu.edit');
                Route::any('/delete', 'Backend\MenuController@delete')->name('backend.menu.del');
            });

            Route::group(['prefix' => 'news'], function () {
                Route::any('/', 'Backend\MenuNewsController@index')->name('backend.menu.news.index');
                Route::any('/sort', 'Backend\MenuNewsController@sort')->name('backend.menu.news.sort');
                Route::any('/add', 'Backend\MenuNewsController@add')->name('backend.menu.news.add');
                Route::any('/edit/{id}', 'Backend\MenuNewsController@edit')->name('backend.menu.news.edit');
                Route::any('/delete', 'Backend\MenuNewsController@delete')->name('backend.menu.news.del');
            });
        });

        Route::group(['prefix' => 'setting'], function () {
            Route::any('/', 'Backend\SettingController@index')->name('backend.setting.index');
        });

        Route::group(['prefix' => 'banner'], function () {
            Route::any('/', 'Backend\BannerController@index')->name('backend.banner.index');
            Route::any('/add', 'Backend\BannerController@add')->name('backend.banner.add');
            Route::any('/edit/{id}', 'Backend\BannerController@edit')->name('backend.banner.edit');
            Route::any('/delete/{id}', 'Backend\BannerController@delete')->name('backend.banner.del');
        });

        Route::any('/subscribers', 'Backend\SubscribersController@index')->name('backend.subscribers.index');

        Route::group(['prefix' => 'discount'], function () {
            Route::any('/', 'Backend\DiscountController@index')->name('backend.discount.index')->middleware('permission:discount.index');
            Route::any('/add', 'Backend\DiscountController@add')->name('backend.discount.add')->middleware('permission:discount.add');
            Route::any('/edit/{id}', 'Backend\DiscountController@edit')->name('backend.discount.edit')->middleware('permission:discount.edit');
            Route::any('/delete/{id}', 'Backend\DiscountController@delete')->name('backend.discount.del')->middleware('permission:discount.del');
        });

        // báo cáo live
        Route::group(['prefix' => 'ops-live'], function () {
            Route::any('/', 'Backend\ReportController@index')->name('backend.ops-live.index');
            Route::any('/list', 'Backend\ReportController@list')->name('backend.ops-live.list');
            Route::any('/amount', 'Backend\ReportController@amount')->name('backend.ops-live.amount');
            Route::any('/change/file', 'Backend\ReportController@ChangeFile')->name('backend.ops-live.change.file');

        });
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

Route::get('/feed', function () {

    $feed = App::make("feed");

    //$feed->setCache(60, 'my_rss');

    if (!$feed->isCached()) {

        $posts = App\Models\Post::get_by_where([
            'status' => App\Models\Post::STATUS_SHOW,
            'limit'  => 1000,
        ], ['detail']);

        $feed->title = 'Balo Nguyên Phúc | Hệ thống cửa hàng Balo, Vali, Túi Xách, Phụ Kiện';
        $feed->description = 'Balo Nguyên phúc chuyên cung cấp sỉ lẻ các loại balo, vali, túi xách cho học sinh, dân công sở, doanh nhân, cho du lịch với nhiều kiểu dáng thời trang thỏa sức cho bạn lựa chọn.';
        $feed->logo = 'https://nguyenphucstore.vn/storage/uploads/2020/08/24/5f43857b0f331.png';
        $feed->link = url('feed');
        $feed->setDateFormat('datetime');
        $feed->pubdate = $posts[0]->created_at->format(DateTime::RFC822);
        $feed->lang = 'vi';
        $feed->setShortening(true);
        $feed->setTextLimit(100);

        foreach ($posts as $post) {
            $feed->add(strip_tags($post->name), 'Nguyen Phuc Store', url($post->post_link()), $post->created_at->format(DateTime::RFC822), strip_tags($post->excerpt), strip_tags($post->details));
        }
    }

    return $feed->render('rss');
});
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

    Route::any('/login', 'Frontend\UserController@login')->name('frontend.user.login');
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

Route::group(['prefix' => 'tin-tuc'], function () {
    Route::any('/', 'Frontend\NewsController@main')->name('frontend.news.main');

    Route::get('/{slug}', 'Frontend\NewsController@route')
        ->name('frontend.news.route')
        ->where('slug', '(.*)');
});
Route::group(['prefix' => 'chinh-sach'], function () {
    Route::any('/', 'Frontend\PolicyController@main')->name('frontend.policy.main');

    Route::get('/{slug}', 'Frontend\PolicyController@route')
        ->name('frontend.policy.route')
        ->where('slug', '(.*)');
});

Route::get('/{slug}', 'Frontend\ProductController@route')
    ->name('frontend.product.route')
    ->where('slug', '(.*)');
