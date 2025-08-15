<?php
/**
 * User: LuanNT
 * Date: 29/05/2018
 * Time: 2:43 CH
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseAPIController;
use App\Models\Basket;
use App\Models\Branch;
use App\Models\DiscountCode;
use App\Models\Product;
use App\Models\ProductVariation;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BasketController extends BaseAPIController
{
    /**
     * @OA\Get(
     *   path="/basket",
     *   tags={"Basket"},
     *   summary="Get basket of user",
     *   description="",
     *   operationId="BasketGetAll",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="api_key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="device_id", description="Device ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="token", description="Access token", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="discount_code", description="discount code", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="limit", description="limit", required=false, in="query",
     *         @OA\Schema( type="integer", )
     *     ),
     *     @OA\Parameter( name="page", description="page", required=false, in="query",
     *         @OA\Schema( type="integer", )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Account is banned"),
     * )
     */
    public function getAll(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();

        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
        ]);
        if ($request->get('discount_code')) {
            /*$code = DiscountCode::get_available([
                'code' => $request->get('discount_code')
            ]);*/
//            $code = DiscountCode::get_available([
//                'code' => $request->get('discount_code'),
//                'user_ids' => $user->user_category_id
//            ]);
            $code = DiscountCode::where('code', $request->get('discount_code'))->first();
            if (!$code) {
                return $this->throwError('Mã giảm giá không tồn tại!', 400);
            }

            if ($code->limit <= $code->used_count) {
                return $this->throwError('Mã giảm giá đã hết lượt sử dụng', 400);
            }
        }
        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }

        $params = $request->all();

        $params = array_filter($params);
        // $params['token'] = $request->get('token');
        $params['pagin_path'] = route('basket.getAll') . '?' . http_build_query($params);

        $params['pagin'] = false;

        /*
         user_category_id : 91 -> Khách hàng
         user_category_id : 92 -> Nhân viên
        */
        if (isset($user)) {
            if ($user->user_category_id == 92) {
                $params['device_id'] = $request->get('device_id');
            } else {
                $params['device_id'] = "";
                $params['user_id'] = $user->id;
            }
        }
        //print_r($user->id);
        //exit();
        $total_reduce_point = $total_product_price = $discount_amount = $total_weight = $shipping_fee = 0;

        $basket = Basket::get_all_product($params);
        foreach ($basket as $product) {
            $price = isset($product->product_variation_price) ? $product->product_variation_price : $product->price;
            $price_old = isset($product->product_variation_price) ? $product->product_variation_price : $product->price_old;

            $total_product_price += $price * $product->quantity;
            $total_weight += $product->weight * $product->quantity;
        }
        if (!empty($code)) {
            $discount_amount = $code->type == 1 ? $code->value : ($code->value * $total_product_price) / 100;
            $discount_amount = $discount_amount >= $total_product_price ? $total_product_price : $discount_amount;
        }

        $return = [
            'items' => $basket,
            'total_product_price' => $total_product_price,
            'discount_amount' => (int)$discount_amount,
            'total_after_discount' => $total_product_price - $discount_amount,
            'promotion' => !empty($code) ? $code : []
        ];

        return $this->returnResult($return);
    }

    /**
     * @OA\Get(
     *   path="/basket/counter",
     *   tags={"Basket"},
     *   summary="Get basket counter",
     *   description="",
     *   operationId="BasketCounter",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="token", description="token from api login", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Account is banned"),
     * )
     */
    public function counter(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $user = $this->getAuthenticatedUser();

        $total = Basket::select(DB::raw('sum(quantity) as total'))
            ->where('user_id', $user->id)
            ->groupBy('user_id')
            ->first();

        $return = $total;

        return $this->returnResult($return);
    }

    /**
     * @OA\Post(
     *   path="/basket",
     *   tags={"Basket"},
     *   summary="add product to basket",
     *   description="",
     *   operationId="BasketAddProduct",
     *      @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *      @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *			     @OA\Property(property="token", description="access token", type="string", ),
     *			     @OA\Property(property="device_id", description="device id", type="string", ),
     *			     @OA\Property(property="branch_id", description="branch id", type="integer", ),
     *			     @OA\Property(property="product_id", description="product_id", type="integer", ),
     *			     @OA\Property(property="product_variation_id", description="product_variation_id", type="integer", ),
     *			     @OA\Property(property="quantity", description="quantity", type="integer", ),
     *              required={"device_id","product_id","quantity", "branch_id"}
     *          )
     *       )
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
    public function add(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();


        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'product_variation_id' => 'nullable|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }
        $product_id = $request->get('product_id');
        $company_id = $request->get('company_id');
        $device_id = $request->get('device_id');
        $product_variation_id = $request->get('product_variation_id', null);
        $quantity = $request->get('quantity');

        $product = Product::where('company_id', $company_id)->find($product_id);

        $product_variant = ProductVariation::where('product_id', $product_id)->first();

        if (isset($product_variant) && !$product_variation_id) {
            return $this->throwError('Vui lòng chọn thuộc tính sản phẩm!', 400);
        } else if (isset($product_variant) && $product_variation_id) {
            $product_variant = ProductVariation::where('id', $product_variation_id)
                ->where('product_id', $product_id)->first();
            if (empty($product_variant))
                return $this->throwError('Biến thể của sản phẩm không tồn tại!', 400);
        }

        if (empty($product))
            return $this->throwError('Sản phẩm không tồn tại!', 400);

        /*
         user_category_id : 91 -> Khách hàng
         user_category_id : 92 -> Nhân viên
        */
        if (isset($user)) {
            if ($user->user_category_id == 91) {
                $basket = Basket::where('user_id', $user->id)->where('product_id', $product_id);
            } else {
                $basket = Basket::where('device_id', $request->get('device_id'))->where('product_id', $product_id);
            }
        }

        if ($product_variation_id)
            $basket = $basket->where('product_variation_id', $product_variation_id);

        $basket = $basket->first();


        // bao -> 6/7/2023
        // Lấy chi nhánh
        $branchId = $request->get('branch_id');
        $branch = Branch::find($branchId);
        if (empty($branch)) {
            return $this->throwError('Chi nhánh không tồn tại!', 400);
        }
        // kiểm tra sản phẩm có trong chi nhánh đó hay không
        $listProductIds = explode(',', $branch->list_product_id);
        if (!in_array($product_id, $listProductIds)) {
            return $this->throwError('Sản phẩm không thuộc chi nhánh này', 400);
        }
        if ($branch->turnOnTurnOff == 1) {
            // Lấy giờ mở cửa và đóng cửa
            $daily = $branch->daily;
            $arrayTime = explode('-', $daily);
            $openTime = strtotime(trim($arrayTime[0].date('d/m/Y')));
            $closeTime = strtotime(trim($arrayTime[1].date('d/m/Y')));
            // Lấy thời gian hiện tại
            $currentTime = date('H:i:s d/m/Y');
            $currentTimeUnix = strtotime($currentTime);
            // Kiểm tra xem thời gian hiện tại có nằm giữa thời gian mở cửa và đóng cửa hay không
            $isBranchOpen = ($currentTimeUnix >= $openTime && $currentTimeUnix <= $closeTime);
//        return $this->returnResult($isBranchOpen);
            // Trả về kết quả
            if ( $isBranchOpen) {
                // return $this->returnResult($isBranchOpen);
                if (empty($basket)) {
                    // thêm mới sản phẩm khi chưa có sản phẩm đó trong giỏ hàng
                    Basket::create([
                        'company_id' => $company_id,
                        'user_id' => isset($user) ? $user->id : '',
                        'device_id' => $device_id,
                        'product_id' => $product_id,
                        'product_variation_id' => $product_variation_id,
                        'quantity' => $quantity,
                    ]);
                } else {
                    // cập nhật số lượng sản phẩm khi đã có
                    $basket->quantity = $basket->quantity + $quantity;
                    $basket->update();
                }
            } else {
                return $this->throwError('Cửa hàng đã đóng cửa!', 400);
            }
        }else {
            return $this->throwError('Của hàng hiện chưa hoạt động!', 400);
        }

        //return $this->returnResult($currentTimeUnix);
        // end
        return $this->returnResult();
    }

    /**
     * @OA\Post(
     *   path="/basket/update",
     *   tags={"Basket"},
     *   summary="update basket",
     *   description="",
     *   operationId="BasketUpdate",
     *      @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *      @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *      @OA\Parameter( name="device_id", description="Device ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *      @OA\Parameter( name="token", description="Access token", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *			     @OA\Property(property="basket_data", description="Object basket data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="basket_item_id", description="basket_item_id", type="integer", ),
     *			            @OA\Property(property="quantity", description="số lượng", type="integer", ),
     *                  )
     *              ),
     *              required={"basket_data"}
     *          )
     *       )
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
    public function update(Request $request)
    {
        //Log::debug(file_get_contents('php://input'));
        //Log::debug($request->all());
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();

        $validator = Validator::make($request->all(), [
            'basket_data' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }

        $device_id = $request->get('device_id');
        $basket_data = $request->get('basket_data');

        if (empty($basket_data))
            return $this->throwError('Basket_data empty or invalid', 400);

        $basket_data = is_array($basket_data) ? $basket_data : json_decode($basket_data, true);

        foreach ($basket_data as $item) {
            if (isset($user)) {
                $basket = Basket::where('id', $item['basket_item_id'])->where('user_id', $user->id)->first();
            } else {
                $basket = Basket::where('id', $item['basket_item_id'])->where('device_id', $device_id)->first();
            }
            if ($basket) {
                if ($item['quantity'] < 1 || empty($basket->product)) {
                    $basket->delete();
                } else {
                    $basket->quantity = $item['quantity'];
                    $basket->save();
                }
            } else {
                return $this->throwError('Basket invalid', 400);
            }
        }
        return $this->returnResult();
    }

    /**
     * @OA\Post(
     *   path="/basket/remove-item",
     *   tags={"Basket"},
     *   summary="Remove items from basket",
     *   description="",
     *   operationId="BasketRemoveItems",
     *      @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *      @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *      @OA\Parameter( name="device_id", description="token from api login", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *      @OA\Parameter( name="token", description="token from api login", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *			     @OA\Property(property="basket_item_ids", description="list item id in basket", type="string", ),
     *              required={"basket_item_ids",}
     *          )
     *       )
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
    public function removeItems(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));


        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();

        $validator = Validator::make($request->all(), [
            'basket_item_ids' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }

        $basket_item_ids = explode(',', $request->get('basket_item_ids'));

        // Basket::where('device_id', $request->get('device_id'))->whereIn('id', $basket_item_ids)->delete();
        Basket::where('user_id', $user->id)->whereIn('id', $basket_item_ids)->delete();

        return $this->returnResult();
    }

    /**
     * @OA\Post(
     *   path="/basket/clear",
     *   tags={"Basket"},
     *   summary="Clear basket",
     *   description="",
     *   operationId="Basketclear",
     *      @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *      @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *      @OA\Parameter( name="device_id", description="token from api login", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *      @OA\Parameter( name="token", description="token from api login", required=false, in="query",
     *         @OA\Schema( type="string", )
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
    public function basketclear(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));


        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();

        Basket::where('user_id', $user->id)->delete();

        return $this->returnResult();
    }
}
