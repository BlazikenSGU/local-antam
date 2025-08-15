<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\CoreUsers;
use App\Models\Menus;
use App\Models\ProductType;
use App\Models\Settings;
use App\Utils\Category;
use App\Utils\Menu;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use View;

class BaseFrontendController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $_ref;
    public $all_category;
    public $all_category_1;
    public $all_category_2;
    public $category_tree;
    public $category_tree_1;
    public $category_tree_2;

    public function __construct()
    {
        $this->_ref = Request()->get('_ref', null);
        $code = Request()->get('_s', null);

        if ($code) {
            $user_code = CoreUsers::where('phone', $code)->first();
            $code_1 = 'code';
            if (!empty($user_code))
                setcookie($code_1, $code, time() + (86400 * 30), "/");
        }


        View::share('title', '');
        View::share('description', '');
        View::share('keywords', '');
        View::share('author', '');

        $header_menu = Menus::get_all(Menus::TYPE_PRODUCT);

        $header_menu_tree = count($header_menu) ? Menu::buildTree($header_menu->toArray()) : [];

        View::share('header_menu_tree', $header_menu_tree);
        View::share('author', '');
        $settings = Settings::get_all();

        foreach ($settings as $k => $v) {
            View::share($k, $v['setting_value']);
        }
        View::share('current_url', \App\Utils\Common::J_getCurUrl());

        $all_category = ProductType::get_by_where([
            'status'     => 1,
            'assign_key' => true,
        ]);
        $all_category_1 = ProductType::get_by_where([
            'status'     => 1,
            'type'       => 1,
            'assign_key' => true,
        ]);
        $all_category_2 = ProductType::get_by_where([
            'status'     => 1,
            'type'       => 2,
            'assign_key' => true,
        ]);
        $brands = Banner::get_by_where([
            'status' => 1,
            'type'   => 2,
            'pagin'  => false,
        ]);


        View::share('brands', $brands);
        $this->category_tree = Category::buildTreeType($all_category);
        $this->category_tree_1 = Category::buildTreeType($all_category_1);
        $this->category_tree_2 = Category::buildTreeType($all_category_2);

        $this->all_category = Category::tree_to_array($this->category_tree);
        $this->all_category_1 = Category::tree_to_array($this->category_tree_1);
        $this->all_category_2 = Category::tree_to_array($this->category_tree_2);
        $array_categories_db = ProductType::get_by_where([
            'status'     => 1,
            'type'       => 1,
            'assign_key' => true,
        ]);
        $array_categories_db_2 = ProductType::get_by_where([
            'status'     => 1,
            'type'       => 2,
            'assign_key' => true,
        ]);

        $this->_data['array_tree_categories'] = Category::buildTreeType($array_categories_db);
        $this->_data['array_tree_categories_2'] = Category::buildTreeType($array_categories_db_2);

        $this->_data['array_categories'] = Category::treeToArray($this->_data['array_tree_categories']);

        View::share('category_tree', $this->category_tree);
        View::share('category_tree_1', $this->category_tree_1);
        View::share('category_tree_2', $this->category_tree_2);
        View::share('all_category', $this->all_category);
        View::share('all_category_1', $this->all_category_1);
        View::share('all_category_2', $this->all_category_2);
    }

    public function getUser()
    {
        return Auth()->guard('web')->check() ? Auth()->guard('web')->user() : null;
    }
    public static function returnResult($data = [], $string = '')
    {
        return Response()->json([
            'status'  => true,
            'code'    => 200,
            'data'    => !empty($data) ? $data : new \stdClass(),
            'message' => $string,
        ]);
    }
}
