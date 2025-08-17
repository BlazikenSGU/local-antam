<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseFrontendController;
use App\Mail\Order;
use App\Models\Address;
use App\Models\CoreUsers;
use App\Models\DiscountCode;
use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use App\Models\Orders;
use App\Models\OrdersDetail;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Utils\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CartController extends BaseFrontendController
{
    protected $_data = [];

    public function index(Request $request)
    {
        $cart = Cart::get();

        if ($request->isMethod('post')) {
            $data_update = $request->get('cart_data');

            foreach ($data_update as $k => $v) {
                if (!isset($cart[$k])) continue;

                $v = max($v, 0);
                Cart::update($cart[$k]['product_id'], $cart[$k]['product_variation_id'], (int)$v);
            }

            return ;
        }

        $cart_data = Product::get_cart_data([
            'cart'           => $cart,
            'cart_processed' => Cart::get_processed(),
        ]);
        $this->_data['arr_products'] = $cart_data['items'];

        $this->_data['cart'] = $cart;

        $this->_data['menu_active'] = 'products';
        $this->_data['title'] = 'Giỏ hàng';

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => 'Giỏ hàng'
        );

        $this->_data['breadcrumbs'] = $breadcrumbs;

        return view('frontend.cart.index', $this->_data);
    }

    public function checkout(Request $request)
    {
        $cart = Cart::get();

        if (!$cart)
            return redirect(config('app.url'));

        $cart_data = Product::get_cart_data([
            'cart'           => $cart,
            'cart_processed' => Cart::get_processed(),
        ]);

        $products = $cart_data['items'];

        if (!$products)
            return redirect(config('app.url'));

        $validator_rule = [
            'fullname'      => ['required', 'string'],
            'phone'         => ['required', 'string'],
            'email'         => ['nullable', 'email'],
            'street'        => ['required', 'string'],
            'payment_type'  => ['required'],
            'discount_code' => 'nullable|string',
            'point'         => 'nullable|integer',
            'date_receiver' => 'nullable|string',
            'province_id'   => ['required', 'integer', Rule::exists('lck_location_province', 'id'),],
            'district_id'   => ['required', 'integer', Rule::exists('lck_location_district', 'id'),],
            'ward_id'       => ['required', 'integer', Rule::exists('lck_location_ward', 'id'),],
        ];

        $messsages = array(
            'fullname.required'    => 'Vui lòng nhập Họ tên người nhận!',
            'phone.required'       => 'Vui lòng nhập Số điện thoại người nhận!',
            'street.required'      => 'Vui lòng nhập Địa chỉ!',
            'province_id.required' => 'Vui lòng chọn Tỉnh / Thành phố!',
            'province_id.exists'   => "Tỉnh / Thành phố không hợp lệ!",
            'district_id.required' => 'Vui lòng chọn Quận / Huyện!',
            'district_id.exists'   => "Quận / Huyện không hợp lệ!",
            'ward_id.required'     => 'Vui lòng chọn Phường/Xã!',
            'ward_id.exists'       => "Phường/Xã không hợp lệ!",
            'email.email'          => 'Email không hợp lệ!',
        );

        $form_init = array_fill_keys(array_keys($validator_rule), null);
        $form_init = array_merge($form_init, $request->all());

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule, $messsages)->validate();

            $total_reduce = $total_reduce_point = 0;
            $form_init['discount_info'] = '{}';

            if ($form_init['discount_code']) {
                $code = DiscountCode::get_available([
                    'code' => $form_init['discount_code'],
                     'user_ids' => 91
                ]);

                if (!$code) {
                    return redirect()->back()
                        ->withInput($request->all())
                        ->withErrors(['message' => "Mã giảm giá không hợp lệ"]);
                }

                if ($code->limit <= $code->used_count) {
                    return redirect()->back()
                        ->withInput($request->all())
                        ->withErrors(['message' => "Mã giảm giá đã hết lượt sử dụng"]);
                }

                $form_init['discount_info'] = json_encode($code);

            }

            if ($form_init['point']) {
                $total_reduce_point = $form_init['point'] * 1000;
            }
            try {
                \DB::beginTransaction();

                $order_details = [];
                $total_amount = 0;

                foreach ($products as $product) {
                    $price = $product['price'];

                    $quantity = $product['quantity'];
                    $amount = $price * $quantity;
                    $total_amount += $amount;

                    $order_details[] = [
                        'order_id'               => null,
                        'product_id'             => $product['id'],
                        'product_code'           => $product['product_code'],
                        'thumbnail_path'         => $product['picture_path'],
                        'title'                  => $product['title'],
                        'description'            => $product['description'],
                        'specifications'         => $product['specifications'],
                        'quantity'               => $quantity,
                        'price'                  => $price,
                        'total_price'            => $amount,
                        'product_variation_id'   => $product['product_variation_id'],
                        'product_variation_name' => $product['product_variation_name'],
                        'inventory_management'   => $product['inventory_management'],
                        'inventory_policy'       => $product['inventory_policy'],
                        'buy_out_of_stock'       => $product['buy_out_of_stock'],
                    ];
                }

                $province = Province::find($request->get('province_id'));
                $district = District::find($request->get('district_id'));
                $ward = Ward::find($request->get('ward_id'));
                $address = $request->get('street') . ', ' . $ward->name_origin . ', ' . $district->name_origin . ', ' . $province->name_origin;

                if (isset($code)) {
                    $total_reduce = $code->type == 1 ? $code->value : ($code->value * $total_amount) / 100;
                    $total_reduce = $total_reduce >= $total_amount ? $total_amount : $total_reduce;
                }

                $order = Orders::create([
                    'company_id'         => config('constants.company_id'),
                    'order_code'         => null,
                    'user_id'            => Auth()->guard('web')->user() ? Auth()->guard('web')->user()->id : null,
                    'fullname'           => $request->get('fullname'),
                    'phone'              => $request->get('phone'),
                    'email'              => $request->get('email'),
                    'street'             => $request->get('street'),
                    'address'            => $address,
                    'province_id'        => $request->get('province_id'),
                    'district_id'        => $request->get('district_id'),
                    'ward_id'            => $request->get('ward_id'),
                    'total_price'        => $total_amount - $total_reduce - $total_reduce_point,
                    'note'               => $request->get('note'),
                    'product_price'      => $total_amount,
                    'discount_code'      => $request->get('discount_code'),
                    'discount_info'      => $form_init['discount_info'],
                    'total_reduce'       => $total_reduce,
                    'total_reduce_point' => $total_reduce_point,
                    'reduce_point'       => $request->get('point'),
                    'status'             => 1,
                    'send_mail_status'   => 0,
                    'payment_type'       => $form_init['payment_type'],
                    'date_receiver'      => $form_init['date_receiver'],
                ]);

                $order->order_code = 'DH' . $order->id;
                $order->save();

                foreach ($order_details as $k => $v) {
                    $order_details[$k]['order_id'] = $order->id;
                }

                OrdersDetail::insert($order_details);

                Cart::clear();
                if (Auth()->guard('web')->user()) {
                    // tạo địa chỉ giao hàng mới

                    if ($request->get('address') == 0) {
                        Address::create([
                            'company_id'  => config('constants.company_id'),
                            'user_id'     => Auth::guard('web')->user()->id,
                            'name'        => $request->get('fullname'),
                            'phone'       => $request->get('phone'),
                            'street_name' => $request->get('street'),
                            'province_id' => $request->get('province_id'),
                            'district_id' => $request->get('district_id'),
                            'ward_id'     => $request->get('ward_id'),
                        ]);
                    }
                    if ($form_init['point']) {

                        $user = CoreUsers::find(Auth()->guard('web')->user()->id);
                        $user->point = $user->point - $request->get('point');
                        $user->save();
                    }
                }

                //trừ sl kho
                foreach ($order_details as $k => $v) {
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

                if (isset($code)) {
                    $code->used_count = $code->used_count + 1;
                    $code->save();
                }

                \DB::commit();

                try {
                    if ($request->get('email')) {

                        Mail::to($request->get('email'))->send(new Order($order->id));
                    }
                } catch (\Exception $e) {

                    \Log::error('Send email order: ' . $e->getMessage());
                }

                $this->_data['msg'] = 'Quý khách đã đặt hàng thành công';
                $this->_data['next_url'] =  '?phone=' . $order->phone . '&order_code=' . $order->order_code;

                return redirect($this->_data['next_url']);

            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('Insert Orders: ' . $e->getMessage());
                return ;
            }
        }
        $this->_data['form_init'] = (object)$form_init;
        $this->_data['provinces'] = Province::orderBy('priority', 'ASC')->get();

        $this->_data['arr_products'] = $products;
        $this->_data['cart'] = $cart;
        $this->_data['title'] = 'Đặt hàng';
        $this->_data['menu_active'] = 'products';

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => 'Đặt hàng'
        );

        $this->_data['breadcrumbs'] = $breadcrumbs;

        return view('frontend.cart.checkout', $this->_data);
    }
}
