<?php

use App\Models\ProductType;
use App\Utils\Category;


Route::group(['prefix' => 'admin'], function () {

    Route::any('/login', 'Backend\AuthController@login')->name('backend.login');
    Route::any('/logout', 'Backend\AuthController@logout')->name('backend.logout');
    Route::any('/register', 'Backend\AuthController@register')->name('backend.register');
    Route::get('/check-otp-expire', 'Backend\AuthController@checkOTPTimeout');
    Route::any('/forgotPassword', 'Backend\AuthController@forgotPassword')->name('backend.forgotPassword');
    Route::any('/changePassword', 'Backend\AuthController@changePassword')->name('backend.changePassword');
    Route::get('/verify-otp', 'Backend\AuthController@verifyOTP')->name('backend.verifyOTP');
    Route::post('/verify-otp', 'Backend\AuthController@verifyOTP');

    Route::group(['middleware' => 'backend'], function () {

        Route::get('/', 'Backend\DashboardController@index')->name('backend.dashboard');

        Route::get('/comming', function () {
            return view('backend.mobie.comingsoon');
        });



        Route::any('/notification', 'Backend\NotificationController@index')->name('backend.notification.index');
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
            Route::any('/', 'Backend\DoiSoatController@index')->name('backend.doi_soat.index');
            Route::get('/edit/{id}', 'Backend\DoiSoatController@edit')->name('backend.doi_soat.edit');
            Route::post('/edit/{id}', 'Backend\DoiSoatController@update')->name('backend.doi_soat.update');
            Route::any('/export', 'Backend\DoiSoatController@export')->name('backend.doi_soat.export');
            Route::any('/import', 'Backend\DoiSoatController@import')->name('backend.doi_soat.import');
            Route::any('/detail/{user_id}', 'Backend\DoiSoatController@detail')->name('backend.doi_soat.detail');
            Route::any('/showFormImport', 'Backend\DoiSoatController@showFormImport')->name('backend.doi_soat.showFormImport');
            Route::any('/run', 'Backend\DoiSoatController@run')->name('backend.doi_soat.run');
            Route::any('/ajaxData', 'Backend\DoiSoatController@ajaxData')->name('backend.doi_soat.ajaxData');

            Route::get('/search', 'Backend\DoiSoatController@search')->name('backend.doi_soat.search');
        });

        Route::group(['prefix' => 'staff'], function () {
            Route::any('/', 'Backend\StaffController@index')->name('backend.staff.index')->middleware('permission:staff.index');
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

        // Route::any('/notification', 'Backend\NotificationController@index')->name('backend.notification.index')->middleware('permission:notification.index');
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
            Route::any('/update1', 'Backend\OrdersController@update1')->name('backend.orders.update1');
            Route::any('/changeAddress', 'Backend\AjaxController@changeAddress')->name('backend.ajax.changeAddress');
            Route::any('/list/cancel', 'Backend\OrdersController@cancellist')->name('backend.orders.cancel.list');
            Route::any('/ajaxData', 'Backend\OrdersController@ajaxData')->name('backend.orders.ajaxData');
            Route::any('/update/order/{id}', 'Backend\OrdersController@updateOrder')->name('backend.orders.updateOrder');
            Route::any('/update/cod_amount/{id}', 'Backend\OrdersController@UpdateCODamount')->name('backend.orders.update.cod.amount');
            Route::any('/get_otp', 'Backend\AjaxController@GetOTP')->name('backend.get_otp');
            Route::any('/check_otp', 'Backend\AjaxController@check_otp')->name('backend.ajax.check_otp');
        });

        Route::group(['prefix' => 'brand'], function () {
            Route::get('/', 'Backend\Branch\BranchController@index')->name('backend.brands.index');
            Route::get('/create', 'Backend\Branch\BranchController@create')->name('backend.brands.create');
            Route::post('/store', 'Backend\Branch\BranchController@store')->name('backend.brands.store');
            Route::post('/ajax-wareHouse', 'Backend\Branch\BranchController@ajaxWareHouse')->name('backend.brands.ajaxWareHouse');
            Route::get('/edit/{id}', 'Backend\Branch\BranchController@edit')->name('backend.brands.edit');
            Route::post('/update/{id}', 'Backend\Branch\BranchController@update')->name('backend.brands.update');
            Route::post('/delete', 'Backend\Branch\BranchController@destroy')->name('backend.brands.delete');


            //route test thoi nha
            Route::get('/selectpickertest', 'Backend\Branch\BranchController@selectpicker')->name('backend.brands.selectpicker');
            Route::get('ajax/orders-by-user', 'Backend\Branch\BranchController@getByUser')->name('backend.ajax.orders_by_user');
        });

        Route::group(['prefix' => 'assignshopid'], function () {
            Route::get('/', 'Backend\Branch\BranchController@assignshopid')->name('backend.brands.assignshopid');
            Route::get('/edit/{id}', 'Backend\Branch\BranchController@editassignshopid')->name('backend.brands.editassignshopid');
            Route::post('/update/{id}', 'Backend\Branch\BranchController@updateassignshopid')->name('backend.brands.updateassignshopid');
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
            Route::get('/', 'Backend\OrdersController@index')->name('backend.orders.index')->middleware('permission:orders.index');
            Route::get('/search', 'Backend\OrdersController@search')->name('backend.orders.search');

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

            Route::any('/changeAddress2/{phone}', 'Backend\OrdersController@getPhone')->name('backend.orders.phone');
            Route::any('/returnAddress2/{rphone}', 'Backend\OrdersController@getRPhone')->name('backend.orders.rphone');


            Route::any('/delete/{id}', 'Backend\OrdersController@delete')->name('backend.orders.delete');
            Route::any('/cancel/{id}', 'Backend\OrdersController@cancel')->name('backend.orders.cancel');
            Route::any('/order/editorder', 'Backend\OrdersController@editorder')->name('backend.orders.edit.order');
            Route::any('/history', 'Backend\OrdersController@history')->name('backend.orders.history');
            Route::any('/export', 'Backend\OrdersController@export')->name('backend.orders.export');
            Route::any('/download/file/{id}', 'Backend\OrdersController@downloadfile')->name('backend.orders.downloadfile');

            // lên đơn băng excel
        });
        Route::group(['prefix' => 'shop'], function () {
            Route::any('/{id}', 'Backend\ShopController@index')->name('backend.shop.index');
            Route::any('/shopid', 'Backend\ShopController@indexshopid')->name('backend.shop.shopid');
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
            Route::get('/', 'Backend\ReportController@index')->name('backend.ops-live.index');
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
