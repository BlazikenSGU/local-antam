<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseAPIController;
use App\Models\Product;
use App\Models\ProductType;
use App\Utils\Category;
use Illuminate\Http\Request;
use Validator;

class HomeController extends BaseAPIController
{


    /**
     * @OA\Get(
     *     path="/home/index",
     *     tags={"Home"},
     *     summary="Get home data",
     *     description="",
     *     operationId="getHomeData",
     *     @OA\Parameter(name="company_id", description="COMPANY ID", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(name="token", description="token from api login", required=false, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Account is banned"),
     *     @OA\Response(response="404", description="Account not exits"),
     *     @OA\Response(response="405", description="Account has not been activated yet"),
     * )
     */
    public function index(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $product_type = ProductType::get_by_where(['assign_key' => true,], ['id', 'name', 'parent_id']);

        foreach ($product_type as $k => $v) {

            /* todo : trang home chỉ hiện ra sản phẩm bán chạy và hàng mới về */
            if($v['id'] === 221){/* Sản phẩm bán chạy */
                if ($v['parent_id']) continue;

                $all_child = [];
                $all_child = Category::get_all_child_categories($product_type, $k);

                $all_child = array_merge($all_child, [$k]);
                $products_by_category[] = ['category' => $v, 'rows' => Product::get_by_where([
                    'status'          => 1,
                    'product_type_id' => $all_child,
                    'limit'           => 12,
                    'sort'            => 'newest',
                    'pagin'           => false,
                ])];
            }elseif ($v['id'] === 222){/*Hàng mới về*/
                if ($v['parent_id']) continue;

                $all_child = [];
                $all_child = Category::get_all_child_categories($product_type, $k);

                $all_child = array_merge($all_child, [$k]);
                $products_by_category[] = ['category' => $v, 'rows' => Product::get_by_where([
                    'status'          => 1,
                    'product_type_id' => $all_child,
                    'limit'           => 12,
                    'sort'            => 'newest',
                    'pagin'           => false,
                ])];
            }
            /* todo : trang home chỉ hiện ra sản phẩm bán chạy và hàng mới về */



        }
//        $user = $this->getAuthenticatedUser();

        return $this->returnResult($products_by_category);
    }

}
