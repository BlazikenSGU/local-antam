<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseAPIController;
use App\Jobs\PushNotification;
use App\Models\Basket;
use App\Models\CoreUsers;
use App\Models\DiscountCode;
use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use App\Models\Notification;
use App\Models\Orders;
use App\Models\OrdersDetail;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Warehouse;
use App\Models\Branch;
use App\VNShipping\Ahamove;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Validator;

class OrdersController extends BaseAPIController
{
    /**
     * @OA\Get(
     *   path="/orders",
     *   tags={"Orders"},
     *   summary="Get all orders",
     *   description="",
     *   operationId="OrdersGetAll",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="device_id", description="Device ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="token", description="token from api login", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="type", description="null: tất cả, 1:chờ thanh toán, 2:Chờ lấy hàng, 3: Đang giao, 4: Đã giao, 5: Đã hủy", required=false, in="query",
     *         @OA\Schema( enum={1,2,3,4,5} )
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
            'page'  => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
            'type'  => 'nullable|integer|in:1,2,3,4,5',
        ]);

        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }

        $params = $request->all();

        $params = array_filter($params);
        $params['token'] = $request->get('token');
        $params['pagin_path'] = route('orders.getAll') . '?' . http_build_query($params);

        if (isset($user)){
            if( $user->id != 168){
                $params['user_id'] = $user->id;
                if($user->user_category_id == 92){
                    $params['device_id'] = $request->get('device_id');
                }
            }
        }
        /*
         user_category_id : 91 -> Khách hàng
         user_category_id : 92 -> Nhân viên
        */
        $return = Orders::get_by_where($params);

        return $this->returnResult($return);
    }

    /**
     * @OA\Post(
     *   path="/orders",
     *   tags={"Orders"},
     *   summary="add orders",
     *   description="",
     *   operationId="OrdersAdd",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="device_id", description="Device ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="token", description="token from api login", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *			     @OA\Property(property="address_id", description="AddressID", type="integer", ),
     *			     @OA\Property(property="province_id", description="province_id", type="integer", ),
     *			     @OA\Property(property="district_id", description="district_id", type="integer", ),
     *			     @OA\Property(property="ward_id", description="ward_id", type="integer", ),
     *			     @OA\Property(property="home_number", description="home_number", type="string", ),
     *			     @OA\Property(property="full_address", description="full_address", type="string", ),
     *			     @OA\Property(property="fullname", description="Tên đầy đủ", type="string", ),
     *			     @OA\Property(property="phone", description="Số điện thoại", type="string", ),
     *			     @OA\Property(property="email", description="Email", type="string", ),
     *               @OA\Property(property="basket_item_ids", description="basket_item_ids", type="array", @OA\Items()),
     *			     @OA\Property(property="payment_method_id", description="payment_method_id", type="integer", ),
     *			     @OA\Property(property="shipping_method", description="shipping_method", type="integer", ),
     *			     @OA\Property(property="discount_code", description="Mã giảm giá", type="string", ),
     *			     @OA\Property(property="latitude", description="Latitude", type="string", ),
     *			     @OA\Property(property="longitude", description="Longitude", type="string", ),
     *			     @OA\Property(property="branch_id", description="BranchId", type="integer", ),
     *			     @OA\Property(property="type_ahamove", description="1: có 2 không", type="integer", ),
     *			     @OA\Property(property="date_receiver", description="date_receiver", type="string", ),
     *              required={"address_id","payment_method","shipping_method"}
     *          )
     *       )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Account is banned"),
     * )
     */
    public function add(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();

        $branch = Branch::find($request->get('branch_id'));
        if (!$branch) {
            return $this->throwError(400, 'Chi nhánh không tồn tại. Vui lòng thử lại!');
        }

        //return $this->returnResult($request->full_address);

        $validator = Validator::make($request->all(), [
            'basket_item_ids'   => 'nullable|array',
            'address_id'        => 'nullable|integer',
            'payment_method_id' => 'required|integer|in:1,2',
            'discount_code'     => 'nullable|string',
            'notes'             => 'nullable|array',
            'shipping_method'   => 'nullable|integer',
            'date_receiver'     => 'nullable|string',
            'fullname'          => ['required', 'string'],
            'phone'             => ['required', 'string'],
            'email'             => ['nullable', 'email'],
            'home_number'       => ['nullable', 'string'],
            'province_id'       => ['nullable', 'integer'],
            'district_id'       => ['nullable', 'integer'],
            'ward_id'           => ['nullable', 'integer'],
           /* 'province_id'       => ['required', 'integer', Rule::exists('lck_location_province', 'id'),],
            'district_id'       => ['required', 'integer', Rule::exists('lck_location_district', 'id'),],
            'ward_id'           => ['required', 'integer', Rule::exists('lck_location_ward', 'id'),],*/
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);


        if (isset($user))
            $params['user_id'] = $user->id;

        $params['item_ids'] = $request->get('basket_item_ids');

        /*
        user_category_id : 91 -> Khách hàng
        user_category_id : 92 -> Nhân viên
       */
        if(isset($user)){
            if($user->user_category_id == 92){
                $params['device_id'] = $request->get('device_id');
            }
        }
       // $params['device_id'] = $request->get('device_id');
        $params['pagin'] = false;
        \DB::enableQueryLog();

        $baskets = Basket::get_all_product($params);

        if (count($baskets) < 1)
            return $this->throwError('Sản phẩm không tồn tại trong giỏ hàng!', 400);
        $items= [];
        $ahamove_type = null;
        $shipping_fee1 = 0;

        $total_reduce_point = $total_product_price = $discount_amount = $total_weight = $shipping_fee = 0;
        if ($request->get('discount_code')) {
            $code = DiscountCode::get_available([
                'code' => $request->get('discount_code'),
                'user_ids' => $user->user_category_id
            ]);

            if (!$code) {
                return $this->throwError('Mã giảm giá không tồn tại!', 400);
            }

            if ($code->limit <= $code->used_count) {
                return $this->throwError('Mã giảm giá đã hết lượt sử dụng', 400);

            }

            $params['discount_info'] = json_encode($code);

        }
        foreach ($baskets as $product) {
            $price = isset($product->product_variation_price) ? $product->product_variation_price : $product->price;
            $price_old = isset($product->product_variation_price) ? $product->product_variation_price : $product->price_old;

            $total_product_price += $price * $product->quantity;
            $total_weight += $product->weight * $product->quantity;
            $order_detail[] = [
                'order_id'               => null,
                'product_id'             => $product->product_id,
                'product_code'           => $product->product_variation_product_code,
                'title'                  => $product->title,
                'thumbnail_path'         => $product->thumbnail->file_path,
                'description'            => $product->description,
                'quantity'               => $product->quantity,
                'specifications'         => $product->specifications,
                'price'                  => $price,
                'total_price'            => $total_product_price,
                'product_variation_id'   => $product->product_variation_id,
                'product_variation_name' => $product->product_variation_name,
                'inventory_management'   => $product->product_variation_inventory,
                'inventory_policy'       => $product->inventory_policy,
                'buy_out_of_stock'       => $product['buy_out_of_stock'],
            ];
        }

       /* $province = Province::find($request->get('province_id'));
        $district = District::find($request->get('district_id'));
        $ward = Ward::find($request->get('ward_id'));
        $address = $request->get('home_number') . ', ' . $ward->name_origin . ', ' . $district->name_origin . ', ' . $province->name_origin;
        */
        if (isset($code)) {
            $discount_amount = $code->type == 1 ? $code->value : ($code->value * $total_product_price) / 100;
            $discount_amount = $discount_amount >= $total_product_price ? $total_product_price : $discount_amount;
        }

        if (!empty($request->get('type_ahamove')) && $request->get('type_ahamove') == 1) {

            $data = [
                [
                    'lat'=>(double)$branch->o_lat,
                    'lng'=>(double)$branch->o_long,
                    'address'=>$branch->address,
                    'name'=>$branch->name,
                    'mobile'=>(string)$branch->phone,
                ],
                [
                    "lat" =>(double) $request->get('latitude'),
                    "lng" =>(double) $request->get('longitude'),
                    "address" => $request->get('full_address'),
                    "name" => $user->fullname,
                    "mobile" => $user->phone,
                ]
            ];
            try {

                $ahamove = new Ahamove();

                $shipment_fee = ($ahamove->getFee([
                    'id' => $branch->id,
                    'data' => $data,
                ]));

                $shipping_fee1 = $shipment_fee['total_price'];
            }catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                preg_match('/"description": "([^"]+)"/', $errorMessage, $matches);
                $errorDescription = isset($matches[1]) ? $matches[1] : 'Không rõ lỗi cụ thể từ máy chủ Ahamove.';
                return $this->throwError('Có lỗi xảy ra: ' .$errorDescription);
            }
//            return $this->returnResult($shipment_fee);
            //return $this->returnResult(1);
            $total_price = 0;
            foreach ($baskets as $basket) {
                $item = [
                    '_id' => (string) $basket['basket_item_id'],
                    'num' => $basket['quantity'],
                    'name' => $basket['title'],
                    'price' => $basket['price'],
                ];
                $product_total = $basket['price'] * $basket['quantity'];
                $total_price += $product_total;
                $items[] = $item;
            }
            $cod = 0;
            $payment_method = '';
            if ( $branch->payment_method == "CASH") {
                $cod = (int)$total_price - $discount_amount;
                $payment_method = "cash_by_recipient";
            } else if ($branch->payment_method == "BALANCE" ) {
                $cod = $shipping_fee1 + (int)$total_price - $discount_amount;
                $payment_method = "BALANCE";
            }
            $datacreate = [
                [
                    'lat'=>(double)$branch->o_lat,
                    'lng'=>(double)$branch->o_long,
                    'address'=>$branch->address,
                    'name'=>$branch->name,
                    'mobile'=>(string)$branch->phone,
                ],
                [
                    "lat" =>(double) $request->get('latitude'),
                    "lng" =>(double) $request->get('longitude'),
                    "address" => $request->get('full_address'),
                    "name" => $user->fullname,
                    "mobile" => $user->phone,
                    "cod" =>$cod
                ]
            ];
//            $shipmentdatacreate = ($ahamove->createdOrder([
//                'id' => $branch->id,
//                'data' => $datacreate,
//                'items'=>$items,
//                'payment_method'=>$branch->payment_method,
//            ]));
//            $ahamove_type = $shipmentdatacreate['ATAI111'] ??  0;

            try {
                $shipmentdatacreate = $ahamove->createdOrder([
                    'id' => $branch->id,
                    'data' => $datacreate,
                    'items' => $items,
                    'payment_method' => $payment_method,
                ]);
                $ahamove_type = $shipmentdatacreate['order']['_id'] ?? null;

            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                preg_match('/"description": "([^"]+)"/', $errorMessage, $matches);
                $errorDescription = isset($matches[1]) ? $matches[1] : 'Không rõ lỗi cụ thể từ máy chủ Ahamove.';
                return $this->throwError($errorDescription);
            }

        }
        $order = Orders::create([
            'company_id'         => config('constants.company_id'),
            'order_code'         => null,
            'user_id'            => isset($user) ? $user->id : null,
            'fullname'           => $request->get('fullname'),
            'phone'              => $request->get('phone'),
            'email'              => $request->get('email'),
            'street'             => $request->get('full_address'),
            'address'            => $request->get('full_address'),
            'province_id'        => $request->get('province_id'),
            'district_id'        => $request->get('district_id'),
            'ward_id'            => $request->get('ward_id'),
            'total_price'        => $total_product_price - $discount_amount - $total_reduce_point + (int)$shipping_fee1,
            'note'               => $request->get('note'),
            'product_price'      => $total_product_price,
            'discount_code'      => $request->get('discount_code'),
            'total_reduce'       => $discount_amount,
            'total_reduce_point' => $total_reduce_point,
            'status'             => 1,
            'send_mail_status'   => 0,
            'payment_type'       => $request->get('payment_method_id'),
            'device_id'          => $request->get('device_id'),
            'date_receiver'      => $request->get('date_receiver'),
            'branch_id'      => $branch->id,
            'ahamove_type'      => $ahamove_type,
            'shipping_fee'      => $shipping_fee1,
        ]);

        $order->order_code = 'DH' . $order->id;
        $order->save();
        $ban = Warehouse::where('id', $order->warehouse_id)->first();
        $NameBan = "";
        if(!empty($ban)){
            $NameBan = " Số Bàn : " . $ban->name;
        }else{
            $NameBan = "Khách hàng : ". $order->fullname;
        }
        $xachnhan = "Xác Nhận";
        /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */
        $user_branch = CoreUsers::where('branch_id',$request->get('branch_id'))->first();
        $notify = Notification::create([
            'title' => 'Có đơn hàng mới.' ,
            'content' => '' . $NameBan .' - Giá : ' . $order->total_price . '  đ ',
            'chanel' => 2,
            'type' => 1,
            'company_id' =>config('constants.company_id'),
            'relate_id' => 0,
            'from_user_id' => 168,
            'to_user_id' => $user_branch->id,
            'order_id' => $order->id,
            'user_id_created' => 168,
        ]);

        $this->dispatch((new PushNotification($notify))->onQueue('push_notification'));
        // gui thong bao cho branch

        if (!empty($user_branch)) {
            $notify1 = Notification::create([
                'title' => 'Đơn hàng #' . $order->order_code  .'_'. $xachnhan ,
                'content' => '' . $NameBan .' - Giá : ' . $order->total_price . '  đ ',
                'chanel' => 2,
                'type' => 1,
                'company_id' =>config('constants.company_id'),
                'relate_id' => 0,
                'from_user_id' => 168,
                'to_user_id' => $user_branch->id,
                'order_id' => $order->id,
                'user_id_created' => 168,
            ]);
            $this->dispatch((new PushNotification($notify1))->onQueue('push_notification'));
        }
        /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */
        //return $this->returnResult($order);

        foreach ($order_detail as $k => $v) {
            $order_detail[$k]['order_id'] = $order->id;
        }

        OrdersDetail::insert($order_detail);

        foreach ($order_detail as $k => $v) {
            if ($v['inventory_management'] && !$v['buy_out_of_stock']) {
                if ($v['product_variation_id']) {
                    ProductVariation::update_inventory([
                        'id'         => $v['product_variation_id'],
                        'product_id' => $v['product_id'],
                        'quantity'   => -$v['quantity'],
                    ]);
                } else {
                    Product::update_inventory([
                        'product_id' => $v['product_id'],
                        'quantity'   => -$v['quantity'],
                    ]);
                }

                Product::change_inventory([
                    'product_id' => $v['product_id'],
                ]);
            }
        }
        Basket::whereIn('id', $params['item_ids'])->delete();



        return $this->returnResult();
    }


    //Duc 16/6/2022
    /**
     * @OA\Post(
     *   path="/orders/addMoreItem",
     *   tags={"Orders"},
     *   summary="Add more item",
     *   description="",
     *   operationId="OrdersAddMore",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="token", description="token from api login", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="product_id", description="product id", required=true, in="query",
     *         @OA\Schema( type="integer", )
     *     ),
     *     @OA\Parameter( name="amount", description="amount product", required=true, in="query",
     *         @OA\Schema( type="integer", )
     *     ),
     *     @OA\Parameter( name="order_id", description="order id", required=true, in="query",
     *         @OA\Schema( type="integer", )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Account is banned"),
     * )
     */
    public function addMoreItem(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError('Vui lòng nhập Company ID & API_Key!', 400);

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();

        $validator = Validator::make($request->all(), [
            'token'               => ['required', 'string'],
            'product_id'          => ['required', 'integer'],
            'order_id'          => ['required', 'integer'],
            'amount'              => ['required', 'integer'],
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);

        $id = $request->get('product_id');
        $order_id = $request->get('order_id');
        $amount = $request->get('amount');

        $product = Product::with(['product_type', 'thumbnail', 'images', 'images_extra',])
            ->where('company_id', config('constants.company_id'))
            ->find($id);

        $oderItem = OrdersDetail::Where('order_id', $order_id)->first();

        if (Empty($product)){
            return $this->throwError('Sản phẩm hiện tại chưa có', 400);
        }

        if (Empty($oderItem)){
            return $this->throwError('Hiện tại chưa có danh sách bàn này', 400);
        }

        $sameProduct = OrdersDetail::Where('order_id', $order_id)
            ->where('product_id', $product->id)->first();

        if (Empty($sameProduct)){

            $order_detail[] = [
                'order_id'               => $oderItem->order_id,
                'product_id'             => $product->id,
                'product_code'           => $product->product_code,
                'title'                  => $product->title,
                'thumbnail_path'         => $product->thumbnail->file_path,
                'description'            => $product->description,
                'quantity'               => $amount,
                'specifications'         => $product->specifications,
                'price'                  => $product->price,
                'total_price'            => $product->price * $amount,
//            'product_variation_id'   => $product->product_variation_id,
//            'product_variation_name' => $product->product_variation_name,
//            'inventory_management'   => $product->product_variation_inventory,
//            'inventory_policy'       => $product->inventory_policy,
//            'buy_out_of_stock'       => $product['buy_out_of_stock'],
            ];

            OrdersDetail::insert($order_detail);

        }else{

            $quantity = $amount + $sameProduct->quantity;
            $price = $sameProduct->price * $quantity;
            $sameProduct->quantity = $quantity;
            $sameProduct->total_price = $price;
            $sameProduct-> save();

        }



        $updatePrice = OrdersDetail::Where('order_id', $order_id)->get();

//        return $this->returnResult($updatePrice);
        $totalPrice = 0;
        foreach ($updatePrice as $product) {
            $totalPrice += $product->price * $product->quantity;
        }
        /*for($i = 0; $i <= count($updatePrice) - 1; $i++) {
            $totalPrice += $updatePrice[$i]->total_price;
        }*/


        $order = Orders::Where('id', $order_id)->first();

        if (isset($order->discount_code)){
            $discoutCode = $order->discount_code;

           /* $code = DiscountCode::get_available([
                'code' => $discoutCode
            ]);*/
            $code = DiscountCode::get_available([
                'code' => $request->get('discount_code'),
                'user_ids' => $user->user_category_id
            ]);

            if (isset($code)) {
                $discount_amount = $code->type == 1 ? $code->value : ($code->value * $totalPrice) / 100;
                $discount_amount = $discount_amount >= $totalPrice ? $totalPrice : $discount_amount;
            }
//            return $this->returnResult($discount_amount);
//            $total_reduce_point = $totalPrice = $discount_amount = $total_weight = $shipping_fee = 0;
//            $total_reduce_point = $totalPrice = $discount_amount = 0;

            $order->total_price = $totalPrice - $discount_amount;
            $order->product_price = $totalPrice;
            $order->total_reduce = $discount_amount;
//            $order->total_reduce_point = $total_reduce_point;
            $order-> save();

        }else{
            $order->product_price = $totalPrice;
            $order->total_price = $totalPrice;
            $order-> save();
        }




        return $this->returnResult();
    }

//Duc 16/6/2022



    /**
     * @OA\Post(
     *   path="/orders/new",
     *   tags={"Orders"},
     *   summary="add orders New",
     *   description="",
     *   operationId="OrdersAddNew",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="device_id", description="Device ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="warehouse_id", description="Id của bàn đặt", required=true, in="query",
     *         @OA\Schema( type="integer", )
     *     ),
     *     @OA\Parameter( name="branch_id", description="Id của chi nhánh ", required=true, in="query",
     *         @OA\Schema( type="integer", )
     *     ),
     *     @OA\Parameter( name="token", description="token from api login", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *			     @OA\Property(property="home_number", description="Bàn sô 1", type="string", ),
     *               @OA\Property(property="customer_type", description="vd : 0 is staff, 1 is guest", type="integer", ),
     *			     @OA\Property(property="full_address", description="vd : Quận 5 - Bàn số 1", type="string", ),
     *			     @OA\Property(property="fullname", description="Tên đầy đủ", type="string", ),
     *			     @OA\Property(property="phone", description="Số điện thoại", type="string", ),
     *			     @OA\Property(property="email", description="Email", type="string", ),
     *               @OA\Property(property="basket_item_ids", description="basket_item_ids", type="array", @OA\Items()),
     *			     @OA\Property(property="payment_method_id", description="payment_method_id", type="integer", ),
     *			     @OA\Property(property="shipping_method", description="shipping_method", type="integer", ),
     *			     @OA\Property(property="discount_code", description="Mã giảm giá", type="string", ),
     *			     @OA\Property(property="date_receiver", description="date_receiver", type="string", ),
     *              required={"address_id","payment_method","shipping_method"}
     *          )
     *       )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Account is banned"),
     * )
     */
    public function addNew(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();

        $validator = Validator::make($request->all(), [
            'basket_item_ids'   => 'nullable|array',
            'payment_method_id' => 'required|integer|in:1,2',
            'discount_code'     => 'nullable|string',
            'device_id'         => 'required|string',
            'token'         => 'required|string',
            'notes'             => 'nullable|array',
            'shipping_method'   => 'nullable|integer',
            'date_receiver'     => 'nullable|string',
            'customer_type'     => 'required|integer|in:0,1',
            'fullname'          => ['nullable', 'string'],
            'phone'             => ['nullable', 'string'],
            'email'             => ['nullable', 'email'],
            'home_number'       => ['required', 'string'],
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);


        if (isset($user))
            $params['user_id'] = $user->id;

        $warehouse_id = $request->get('warehouse_id');
        $customer_type = $request->get('customer_type');
        $params['item_ids'] = $request->get('basket_item_ids');

        /*
        user_category_id : 91 -> Khách hàng
        user_category_id : 92 -> Nhân viên
       */
        if(isset($user)){
            if($user->user_category_id == 92){
                $params['device_id'] = $request->get('device_id');
            }
        }
        //$params['device_id'] = $request->get('device_id');
        $params['pagin'] = false;
        \DB::enableQueryLog();

        $baskets = Basket::get_all_product($params);
        $ban = Warehouse::where('id', $warehouse_id)->first();
//         cap nhan lai trang thai cua ban
        if(!empty($ban)){
            $ban->status = 2;
            $ban->save();
        }

        if (count($baskets) < 1)
            return $this->throwError('Sản phẩm không tồn tại trong giỏ hàng!', 400);

        $total_reduce_point = $total_product_price = $discount_amount = $total_weight = $shipping_fee = 0;
        if ($request->get('discount_code')) {
            /*$code = DiscountCode::get_available([
                'code' => $request->get('discount_code')
            ]);*/
            $code = DiscountCode::get_available([
                'code' => $request->get('discount_code'),
                'user_ids' => $user->user_category_id
            ]);

            if (!$code) {
                return $this->throwError('Mã giảm giá không tồn tại!', 400);
            }

            if ($code->limit <= $code->used_count) {
                return $this->throwError('Mã giảm giá đã hết lượt sử dụng', 400);

            }

            $params['discount_info'] = json_encode($code);

        }



        foreach ($baskets as $product) {
            $price = isset($product->product_variation_price) ? $product->product_variation_price : $product->price;
            $price_old = isset($product->product_variation_price) ? $product->product_variation_price : $product->price_old;

            $total_product_price += $price * $product->quantity;
            $total_weight += $product->weight * $product->quantity;
            $order_detail[] = [
                'order_id'               => null,
                'product_id'             => $product->product_id,
                'product_code'           => $product->product_variation_product_code,
                'title'                  => $product->title,
                'thumbnail_path'         => $product->thumbnail->file_path,
                'description'            => $product->description,
                'quantity'               => $product->quantity,
                'specifications'         => $product->specifications,
                'price'                  => $price,
                'total_price'            => $total_product_price,
                'product_variation_id'   => $product->product_variation_id,
                'product_variation_name' => $product->product_variation_name,
                'inventory_management'   => $product->product_variation_inventory,
                'inventory_policy'       => $product->inventory_policy,
                'buy_out_of_stock'       => $product['buy_out_of_stock'],
            ];
        }



        $address = $request->get('full_address');


        if (isset($code)) {
            $discount_amount = $code->type == 1 ? $code->value : ($code->value * $total_product_price) / 100;
            $discount_amount = $discount_amount >= $total_product_price ? $total_product_price : $discount_amount;
        }
        $order = Orders::create([
            'company_id'         => config('constants.company_id'),
            'order_code'         => null,
            'user_id'            => isset($user) ? $user->id : null,
            'customer_type'      => $customer_type,
            'fullname'           => $request->get('fullname'),
            'phone'              => $request->get('phone'),
            'email'              => $request->get('email'),
            'street'             => $request->get('home_number'),
            'address'            => $address,
            'total_price'        => $total_product_price - $discount_amount - $total_reduce_point,
            'note'               => $request->get('note'),
            'product_price'      => $total_product_price,
            'discount_code'      => $request->get('discount_code'),
            'total_reduce'       => $discount_amount,
            'total_reduce_point' => $total_reduce_point,
            'status'             => 2,
            'send_mail_status'   => 0,
            'payment_type'       => $request->get('payment_method_id'),
            'device_id'          => $request->get('device_id'),
            'date_receiver'      => $request->get('date_receiver'),
            'warehouse_id'      => $request->get('warehouse_id'),
            'branch_id'      => $request->get('branch_id'),
        ]);

        $order->order_code = 'DH' . $order->id;
        $order->save();



        $ban = Warehouse::where('id', $order->warehouse_id)->first();
        $branche = Branch::where('id', $order->branch_id)->first();




        $NameBan = "";
        if(!empty($ban)){
            $NameBan = "Chi nhánh : ". $branche->name ." - Số Bàn : " . $ban->name;
        }else{
            $NameBan = "Khách hàng : ". $order->fullname;
        }
        $xachnhan = "Xác Nhận";


        /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */
        $notify = Notification::create([
            'title' => 'Đơn hàng #' . $order->order_code .'_'. $xachnhan ,
            'content' => '' . $NameBan .' - Giá : ' . $order->total_price . '  đ ',
            'chanel' => 2,
            'type' => 1,
            'company_id' =>config('constants.company_id'),
            'relate_id' => 0,
            'from_user_id' => 168,
            'to_user_id' => 168,
            'order_id' => $order->id,
            'user_id_created' => 168,
        ]);

       // print_r($notify->title);
       // exit();

        $this->dispatch((new PushNotification($notify))->onQueue('push_notification'));
        /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */


        if(!empty($ban)){
            $ban->status = 2;
            $ban->order_id = $order->id;
            $ban->save();
        }
        foreach ($order_detail as $k => $v) {
            $order_detail[$k]['order_id'] = $order->id;
        }

        OrdersDetail::insert($order_detail);

        foreach ($order_detail as $k => $v) {
            if ($v['inventory_management'] && !$v['buy_out_of_stock']) {
                if ($v['product_variation_id']) {
                    ProductVariation::update_inventory([
                        'id'         => $v['product_variation_id'],
                        'product_id' => $v['product_id'],
                        'quantity'   => -$v['quantity'],
                    ]);
                } else {
                    Product::update_inventory([
                        'product_id' => $v['product_id'],
                        'quantity'   => -$v['quantity'],
                    ]);
                }

                Product::change_inventory([
                    'product_id' => $v['product_id'],
                ]);
            }
        }
        Basket::whereIn('id', $params['item_ids'])->delete();



        return $this->returnResult();
    }


//    duc 23/6/2022

    /**
     * @OA\Post(
     *   path="/orders/changeStatusOrder",
     *   tags={"Orders"},
     *   summary="change status orders",
     *   description="",
     *   operationId="ChangeStatusOrders",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="order_id", description="Order ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="discount_code", description="Mã khuyến mãi", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="phone", description="phone of custom", required=false, in="query",
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
    public function changeStatusOrder(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();

        $validator = Validator::make($request->all(), [
            'order_id'          => ['required', 'integer'],
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);

        $order_id = $request->get('order_id');
        $discount_code = $request->get('discount_code');


        $order = Orders::Where('id', $order_id)->first();
        $oderItemDetail = OrdersDetail::Where('order_id', $order_id)->first();

        if (Empty($order)){
            return $this->throwError('Hiện tại chưa có đơn hàng này!', 400);
        }

        if ($order->status == 4){
            return $this->throwError('Đơn hàng đã được thanh toán!', 400);
        }

        /* Ca - 12-12-22 cộng doanh thu cho nhân viên bán hàng trên app */
        $user = CoreUsers::find($order->user_id);
        $user->balance += $order->total_price;
        $user->save();


        $ban = Warehouse::where('id', $order->warehouse_id)->first();
        $branche = Branch::where('id', $order->branch_id)->first();
        $NameBan = "";
        if(!empty($ban)){
            $NameBan = "Chi nhánh : ". $branche->name ." - Số Bàn : " . $ban->name;
        }else{
            $NameBan = "Khách hàng : ". $order->fullname;
        }


        if ($order->status == 4){
            $xachnhan = "Thanh toán";
            /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */
            $notify = Notification::create([
                'title' => 'Đơn hàng #' . $order->order_code .'_'. $xachnhan ,
                'content' => '' . $NameBan .' - Giá : ' . $order->total_price . '  đ ',
                'chanel' => 2,
                'type' => 1,
                'company_id' =>config('constants.company_id'),
                'relate_id' => 0,
                'from_user_id' => 168,
                'to_user_id' => 168,
                'order_id' => $order->id,
                'user_id_created' => 168,
            ]);

            $this->dispatch((new PushNotification($notify))->onQueue('push_notification'));
            /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */
        }



        /* Ca - 12-12-22 cộng doanh thu cho nhân viên bán hàng trên app */
        $order->phone = $request->get('phone');
        if (isset($discount_code)){

            if (Empty($order->discount_code)){
               /* $code = DiscountCode::get_available([
                    'code' => $discount_code
                ]);*/
                $code = DiscountCode::get_available([
                    'code' => $request->get('discount_code'),
                    'user_ids' => $user->user_category_id
                ]);

                if (!$code) {
                    return $this->throwError('Mã giảm giá không tồn tại!', 400);
                }

                if ($code->limit <= $code->used_count) {
                    return $this->throwError('Mã giảm giá đã hết lượt sử dụng', 400);

                }

                if (isset($code)) {
                    $discount_amount = $code->type == 1 ? $code->value : ($code->value * $order->total_price) / 100;
                    $discount_amount = $discount_amount >= $order->total_price ? $order->total_price : $discount_amount;
                }


                $order->total_price = $order->total_price - $discount_amount;
//            $order->product_price = $totalPrice;
                $order->total_reduce = $discount_amount;
//            $order->total_reduce_point = $total_reduce_point;
                $order->discount_code = $code->code;
                $order->status = Orders::STATUS_FINISH;
                $order-> save();

                $ban = Warehouse::where('id', $order->warehouse_id)->first();
                $ban->status = 0;
                $ban->order_id = 0;
                $ban->save();


            }else{
                return $this->throwError('Bạn đã sử dụng mã này!', 400);
            }

        }else{
//            $order->product_price = $totalPrice;
//            $order->total_price = $totalPrice;
            $order->status = Orders::STATUS_FINISH;
            $order-> save();

            $ban = Warehouse::where('id', $order->warehouse_id)->first();
            $ban->status = 0;
            $ban->order_id = 0;
            $ban->save();
        }



        return $this->returnResult();
    }


    /**
     * @OA\Post(
     *   path="/orders/changeStatusOrderPrepare",
     *   tags={"Orders"},
     *   summary="change status orders Prepare",
     *   description="",
     *   operationId="ChangeStatusOrdersPrepare",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="order_id", description="Order ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="discount_code", description="Mã khuyến mãi", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="phone", description="phone of custom", required=true, in="query",
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
    public function changeStatusOrderPrepare(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();

        $validator = Validator::make($request->all(), [
            'order_id'          => ['required', 'integer'],
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);

        $order_id = $request->get('order_id');
        $discount_code = $request->get('discount_code');


        $order = Orders::Where('id', $order_id)->first();
        $oderItemDetail = OrdersDetail::Where('order_id', $order_id)->first();

        if (Empty($order)){
            return $this->throwError('Hiện tại chưa có đơn hàng này!', 400);
        }

        if ($order->status == 4){
            return $this->throwError('Đơn hàng đã được thanh toán!', 400);
        }

        /* Ca - 12-12-22 cộng doanh thu cho nhân viên bán hàng trên app */
        $user = CoreUsers::find($order->user_id);
        $user->balance += $order->total_price;
        $user->save();
        /* Ca - 12-12-22 cộng doanh thu cho nhân viên bán hàng trên app */


        $order->phone = $request->get('phone');
        if (isset($discount_code)){

            if (Empty($order->discount_code)){
               /* $code = DiscountCode::get_available([
                    'code' => $discount_code
                ]);*/
                $code = DiscountCode::get_available([
                    'code' => $request->get('discount_code'),
                    'user_ids' => $user->user_category_id
                ]);

                if (!$code) {
                    return $this->throwError('Mã giảm giá không tồn tại!', 400);
                }

                if ($code->limit <= $code->used_count) {
                    return $this->throwError('Mã giảm giá đã hết lượt sử dụng', 400);

                }

                if (isset($code)) {
                    $discount_amount = $code->type == 1 ? $code->value : ($code->value * $order->total_price) / 100;
                    $discount_amount = $discount_amount >= $order->total_price ? $order->total_price : $discount_amount;
                }


                $order->total_price = $order->total_price - $discount_amount;
//            $order->product_price = $totalPrice;
                $order->total_reduce = $discount_amount;
//            $order->total_reduce_point = $total_reduce_point;
                $order->discount_code = $code->code;
                $order->status = Orders::STATUS_FINISH;
                //$order-> save();

                $ban = Warehouse::where('id', $order->warehouse_id)->first();
                $ban->status = 0;
                $ban->order_id = 0;
               // $ban->save();


            }else{
                return $this->throwError('Bạn đã sử dụng mã này!', 400);
            }

        }else{
//            $order->product_price = $totalPrice;
//            $order->total_price = $totalPrice;
            $order->status = Orders::STATUS_FINISH;
            //$order-> save();

            $ban = Warehouse::where('id', $order->warehouse_id)->first();
            $ban->status = 0;
            $ban->order_id = 0;
           // $ban->save();
        }

        $return = [
            "discount_amount" =>$discount_amount
        ];

        return $this->returnResult($return);
    }

// duc 23/6/2022


    /**
     * @OA\Get(
     *   path="/orders/{order_id}",
     *   tags={"Orders"},
     *   summary="get a orders detail",
     *   description="",
     *   operationId="OrdersGetDetail",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="token", description="token from api login", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="order_id", description="order_id", required=true, in="path",
     *         @OA\Schema( type="integer", )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Account is banned"),
     * )
     */
    public function detail($order_id, Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $user = $this->getAuthenticatedUser();

        $ord = Orders::find($order_id);
        if ($user->branch_id == $ord->branch_id) {
            $order = Orders::where('id', $order_id)
                ->where('company_id', config('constants.company_id'))
                ->with(['order_details', 'user','chinhanh'])
                ->first();
            return $this->returnResult($order);
        }
        if( $user->id != 168){
            $order = Orders::where('user_id', $user->id)
                ->where('id', $order_id)
                ->where('company_id', config('constants.company_id'))
                ->with(['order_details', 'user','ban','chinhanh'])
                ->first();
        }else{
            $order = Orders::where('id', $order_id)
                ->where('company_id', config('constants.company_id'))
                ->with(['order_details', 'user','chinhanh'])
                ->first();
        }

        $order['shipper'] = [
            'supplier_name' => $order->supplier_name,
            'supplier_phone' => $order->supplier_phone,
        ];

        if (!$order)
            $this->throwError('Đơn hàng không tồn tại!');

        return $this->returnResult($order);
    }

    /**
     * @OA\Post(
     *   path="/orders/cancel/{order_id}",
     *   tags={"Orders"},
     *   summary="Cancel a orders",
     *   description="",
     *   operationId="OrdersCancel",
     *     @OA\Parameter( name="company_id", description="token from api login", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="device_id", description="device id", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="token", description="token from api login", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="order_id", description="order_id", required=true, in="path",
     *         @OA\Schema( type="integer", )
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *			     @OA\Property(property="cancel_reason", description="cancel_reason", type="string", ),
     *              required={"cancel_reason"}
     *          )
     *       )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Account is banned"),
     * )
     */
    public function cancel(Request $request, $order_id)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));
        $validator = Validator::make($request->all(), [
            'cancel_reason' => 'required|string|max:255',
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);

        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();

        $order = Orders::where('user_id', $user->id)
            ->where('company_id', config('constants.company_id'))
            ->where('id',(int) $order_id)
            ->where('status', '<>', Orders::STATUS_CANCEL)
            ->first();
//        $order = Orders::where('user_id', $user->id)->where('id',(int) $order_id)->first();
        //return $this->returnResult($order);
        if (!empty($order->branch_id) and !empty($order->ahamove_type)) {
            // Hủy đơn ahamove
            $ahamove = new Ahamove();
            $shipment_fee = ($ahamove->cancelOrder([
                'id' => $order->branch_id,
                'order_id' =>$order->ahamove_type,
            ]));
        }

        if (!$order)
            $this->throwError('Đơn hàng không tồn tại!');

        $order->status = Orders::STATUS_CANCEL;
//        $order->ahamove_type = '1';
        $order->cancel_reason = $request->get('cancel_reason');
        $order->save();
        $admin_branch = CoreUsers::where('branch_id', $order->branch_id)->first();
        $notify1 = Notification::create([
            'title' => 'Trạng thái đơn hàng.' ,
            'content' => 'Đơn hàng '.$order->id.' đã bị hủy bởi khách hàng.',
            'chanel' => 2,
            'type' => 1,
            'company_id' =>config('constants.company_id'),
            'relate_id' => 0,
            'from_user_id' => 168,
            'to_user_id' => $admin_branch->id,
            'order_id' => $order->id,
            'user_id_created' => 168,
        ]);
        $this->dispatch((new PushNotification($notify1))->onQueue('push_notification'));


        return $this->returnResult();
    }

    public function getRating($order_id)
    {
        $user = $this->getAuthenticatedUser();

        $order = Orders::where('user_id', $user->id)
            ->where('id', $order_id)
            ->with(['store', 'payment', 'shipping', 'orders_detail', 'shipping_logs'])
            ->first();

        if (!$order)
            $this->throwError('Đơn hàng không tồn tại!');

        return $this->returnResult();
    }

    public function postRating(Request $request, $order_id)
    {
        $validator = Validator::make($request->all(), [
            'ratings' => 'required|array',
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);

        $user = $this->getAuthenticatedUser();

        $order = Orders::where('user_id', $user->id)
            ->where('id', $order_id)
            ->where('status', Orders::STATUS_FINISH)
            ->whereNull('is_rated')
            ->first();

        if (!$order)
            $this->throwError('Đơn hàng không tồn tại hoặc đã được đánh giá!');

        $rating = [];
        foreach ($request->ratings as $value) {
            if (isset($value['rating']) && ($value['rating'] > 0) && ($value['rating'] < 6) && isset($value['product_id'])) {
                $rating[$value['product_id']] = $value;
            }
        }

        $order_rates = $insert_rates = [];

        foreach ($order->orders_detail as $detail) {
            if (array_key_exists($detail->product_id, $rating)) {
                $insert_rates[] = array_merge([
                    'order_detail_id' => $detail->id,
                ], $rating[$detail->product_id]);

                $order_rates[] = $rating[$detail->product_id]['rating'];
            }
        }

        try {
            DB::beginTransaction();

            foreach ($insert_rates as $value) {
                $product = Product::select(['id', 'rating', 'total_rate',])->find($value['product_id']);
                if ($product) {
                    $product->total_rate = $product->total_rate + 1;
                    $product->rating = $product->rating > 0 ? round(($product->rating + $value['rating']) / 2, 1) : $value['rating'];
                    $product->save();

                    Rate::create([
                        'user_id'         => $user->id,
                        'order_detail_id' => $value['order_detail_id'],
                        'product_id'      => $value['product_id'],
                        'order_id'        => $order_id,
                        'rating'          => $value['rating'],
                        'comment'         => isset($value['comment']) ? $value['comment'] : null,
                        'status'          => 1,
                    ]);
                }
            }

            $rating = round(array_sum($order_rates) / count($order_rates), 1);

            $order->rating = $rating;
            $order->is_rated = 1;
            $order->save();

            $store = CoreUsers::select(['id', 'rating', 'total_rate',])->find($order->store_id);

            if ($store) {
                $store->total_rate = $store->total_rate + 1;
                $store->rating = $store->rating > 0 ? round(($store->rating + $rating) / 2, 1) : $rating;
                $store->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('rating order ' . $e->getMessage());
            return $this->throwError('Có lỗi xảy ra, vui lòng thử lại! ' . $e->getMessage(), 401);
        }

        return $this->returnResult();
    }

}
