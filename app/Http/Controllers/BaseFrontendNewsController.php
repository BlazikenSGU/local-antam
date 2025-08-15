<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Menus;
use App\Models\Settings;
use App\Utils\Menu;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use View;

class BaseFrontendNewsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $_ref;

    public function __construct()
    {
        $this->_ref = Request()->get('_ref', null);

        $settings = Settings::get_all();

        View::share('title', '');
        View::share('description', '');
        View::share('keywords', '');
        View::share('author', '');

        $header_menu = Menus::get_all(Menus::TYPE_PRODUCT);
//        $brands = Banner::get_by_where([
//            'status' => 1,
//            'type'   => 2,
//            'pagin'  => false,
//        ]);


//        View::share('brands', $brands);
        $header_menu_tree = count($header_menu) ? Menu::buildTree($header_menu->toArray()) : [];

        View::share('header_menu_tree', $header_menu_tree);

        View::share('author', '');

        foreach ($settings as $k => $v) {
            View::share($k, $v['setting_value']);
        }
        View::share('current_url', \App\Utils\Common::J_getCurUrl());
    }
}
