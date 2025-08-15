<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Utils\Category;
use http\Url;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use View;

class BaseBackendController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $_ref;

    public function __construct()
    {
        $this->_ref = Request()->get('_ref', null);

        View::share('title', '');
        View::share('description', '');
        View::share('keywords', '');
        View::share('author', '');
        View::share('current_url', url()->current());

        $all_category = ProductType::get_by_where([
            'assign_key' => true,
        ]);

        $this->category_tree = Category::buildTreeType($all_category);

        $this->all_category = Category::tree_to_array($this->category_tree);

        View::share('category_tree', $this->category_tree);
        View::share('all_category', $this->all_category);
    }
    public static function returnResult($data = [])
    {
        return Response()->json([
            'status' => true,
            'code'   => 200,
            'data'   => $data
        ]);
    }

    public static function throwError($errors = 'error', $code = 400)
    {
        header('Content-type: application/json');
        echo json_encode([
            'status' => false,
            'code'   => $code,
            'error'  => $errors
        ]);
        exit;
    }

    public function getUser()
    {
        return Auth()->guard('seller')->check() ? Auth()->guard('seller')->user() : null;
    }
}
