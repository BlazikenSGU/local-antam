<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseFrontendController;
use App\Models\Address;
use App\Models\DiscountCode;
use App\Models\Files;
use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Subscribe;
use App\Models\Wishlist;
use App\Utils\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\Optimizers\Jpegoptim;
use Spatie\ImageOptimizer\Optimizers\Optipng;
use Spatie\ImageOptimizer\Optimizers\Pngquant;

class AjaxController extends BaseFrontendController
{

    public function addItemCart(Request $request)
    {
        $product_id = $request->get('product_id');
        $product_variation_id = $request->get('product_variation_id');
        $quantity = $request->get('quantity');

        if (empty($product_id) || $quantity < 1)
            return $this->throwError("Dữ liệu không hợp lệ");

        $product = Product::where('id', $product_id)->where('status', 1)->first();

        if (!$product)
            return $this->throwError("Dữ liệu không hợp lệ");

        if ((int)$product->price <= 0)
            return $this->throwError("Không thể mua sản phẩm này");

        $product_variants = ProductVariation::get_product_variations_with_combine($product_id);

        $variant = null;

        if (count($product_variants)) {
            if (empty($product_variation_id)) {
                return $this->throwError("Vui lòng chọn phiên bản sản phẩm");
            } else {
                $variant = ProductVariation::where('product_id', $product_id)->where('id', $product_variation_id)->first();
                if (!$variant)
                    return $this->throwError("Phiên bản sản phẩm không hợp lệ");
            }
        }

        //check inventory
        $inventory_management = $product->inventory_management;
        $inventory_policy = $product->inventory_policy;
        $inventory = $product->inventory;
        if ($variant) {
            $inventory_management = $variant->inventory_management;
            $inventory_policy = $variant->inventory_policy;
            $inventory = $variant->inventory;
        }

        if ($inventory_management && !$inventory_policy && $inventory < 1)
            return $this->throwError("Sản phẩm đã hết hàng");

        Cart::update($product_id, $product_variation_id, (int)$quantity, true);

        return $this->returnResult([
            'total'        => Cart::get_total_items(),
            'total_format' => number_format(Cart::get_total_items()),
        ]);
    }

    public function throwError($errors = 'error', $code = 400)
    {
        header('Content-type: application/json');
        echo json_encode([
            'status'  => false,
            'code'    => $code,
            'message' => $errors
        ]);
        exit;
    }

    public function returnResult($data = [], $msg = '')
    {
        return Response()->json([
            'status'  => true,
            'code'    => 200,
            'data'    => $data,
            'message' => $msg
        ]);
    }

    public function deleteItemCart(Request $request)
    {
        $item_id = $request->get('item_id');
        if ($item_id === null)
            return $this->throwError("Dữ liệu không hợp lệ");

        Cart::delete_item($item_id);

        return $this->returnResult([
            'total'        => Cart::get_total_items(),
            'total_format' => number_format(Cart::get_total_items()),
        ]);
    }

    public function clearCart()
    {
        Cart::clear();

        return $this->returnResult([
            'total'        => 0,
            'total_format' => 0,
        ]);
    }

    public function uploadImage(Request $request)
    {
        $photos = $request->file('file');

        $thumb_sizes = $request->get('thumb_sizes');

        if (empty($photos))
            return \Response::json(['e' => 1, 'r' => 'Vui lòng chọn hình upload!']);

        if (!is_array($photos)) {
            $photos = [$photos];
        }

        $sub_dir = date('Y/m/d');
        $full_dir = config('constants.upload_dir.root') . '/' . $sub_dir;

        if (!is_dir($full_dir)) {
            mkdir($full_dir, 0755, true);
        }

        $items = [];
        for ($i = 0; $i < count($photos); $i++) {
            $photo = $photos[$i];

            $filename = uniqid();

            $ext = $photo->extension();

            $origin_file_name = $filename . '.' . $ext;

            $file_path = $sub_dir . '/' . $origin_file_name;

            $origin_file_path = $full_dir . '/' . $origin_file_name;

            $optimized_file_path = $full_dir . '/optimized_' . $origin_file_name;

            /*$image = Image::make($photo)->resize(2048, null, function ($constraint) {
                $constraint->aspectRatio();
            })->orientate()->save($origin_file_path);*/

            $image = Image::make($photo)->orientate();

            if ($image->width() > 2048)
                $image->resize(2048, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

            $image->save($origin_file_path, 100);

            if (config('app.env') == 'production') {
                try {
                    $optimizerChain = (new OptimizerChain)
                        ->addOptimizer(new Jpegoptim([
                            '-m85',
                            '--strip-all',
                            '--all-progressive',

                        ]))
                        ->addOptimizer(new Pngquant([
                            '--force'
                        ]))
                        ->addOptimizer(new Optipng([
                            '-i0',
                            '-o2',
                            '-quiet',
                        ]));

                    $optimizerChain->optimize($origin_file_path, $optimized_file_path);
                } catch (\Exception $e) {
                    \Log::error('image optimizer' . $e->getMessage());
                }
            }

            if ($thumb_sizes) {

                $dimension = explode('x', $thumb_sizes);

                //$thumb_filename = $filename . '_' . $thumb_sizes . '.' . $ext;
                $thumb_filename = $filename . '_' . $thumb_sizes . '.png';
                $thumb_file_path = $full_dir . '/' . $thumb_filename;

                $width = $dimension[0];
                $height = $dimension[1];

                $image->width() < $image->height() ? $width = null : $height = null;

                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $image2 = Image::canvas($dimension[0], $dimension[1]);

                $image2->insert($image, 'center')
                    ->save($thumb_file_path, 70);

                $file_path = $sub_dir . '/' . $thumb_filename;
            }


            $image = Files::create([
                'user_id'   => Auth()->guard('web')->user()->id,
                'file_path' => $file_path,
            ]);
            $items[] = [
                'id'   => $image->id,
                'path' => $file_path,
                'url'  => config('constants.upload_dir.url') . '/' . $file_path,
            ];
        }

        return \Response::json(['e' => 0, 'r' => $items]);
    }

    public function subscribeEmail(Request $request)
    {
        $email = $request->get('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return $this->throwError('Email không hợp lệ!');

        $check = Subscribe::where('email', $email)->first();

        if (!$check) {
            Subscribe::create([
                'email' => $email,
                'ip'    => $request->getClientIp(),
            ]);
        }

        return $this->returnResult([], 'Cám ơn bạn đã đăng ký nhận tin mới.');

    }

    public function checkDiscount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_price' => 'required|integer',
            'discount_code' => 'required|string',
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);

        $code = DiscountCode::get_available([
            'code' => $request->discount_code,
            'user_ids' => 91
        ]);

        if (!$code)
            return $this->throwError('Mã giảm giá không hợp lệ', 400);

        if ($code->limit <= $code->used_count)
            return $this->throwError('Mã giảm giá đã hết lượt sử dụng', 400);

        $total_reduce = $code->type == 1 ? $code->value : ($code->value * $request->product_price) / 100;
        $total_reduce = $total_reduce >= $request->product_price ? $request->product_price : $total_reduce;

        return $this->returnResult([
            'reduce' => $total_reduce
        ]);
    }

    public function addWishlist(Request $request)
    {
        if (!Auth::guard('web')->user()->id)
            return abort(404);

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:lck_product,id',
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);
        $wishlist_exists = Wishlist::where('product_id', $request->get('product_id'))
            ->where('user_id', Auth::guard('web')->user()->id)
            ->first();
        if (!$wishlist_exists) {
            $wishlist = Wishlist::create([
                'product_id' => $request->get('product_id'),
                'user_id'    => Auth::guard('web')->user()->id,
            ]);
            return $this->returnResult($wishlist);
        } else {
            return $this->throwError('Đã có trong danh sách', 400);
        }
    }

    public function deleteWishlist(Request $request)
    {
        if (!Auth::guard('web')->user()->id)
            return abort(404);

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:lck_product,id',
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);
        $wishlist_exists = Wishlist::where('product_id', $request->get('product_id'))
            ->where('user_id', Auth::guard('web')->user()->id)
            ->first();

        if ($wishlist_exists) {
            $wishlist_exists->delete();
            return $this->returnResult();
        } else {
            return $this->throwError('Sản phẩm không tồn tại!', 400);
        }


    }
//    public function subscribe(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'email' => 'required|email|lck_subscribers,email',
//        ]);
//        if ($validator->fails())
//            return $this->throwError($validator->errors()->first(), 400);
//        Subscribe::create([
//            'email' => $request->get('email'),
//            'ip' => $request->ip()
//        ]);
//    }
    public function deleteAddress(Request $request)
    {
        if (!Auth::guard('web')->user()->id)
            return abort(404);

        $validator = Validator::make($request->all(), [
            'address_id' => 'required|integer|exists:lck_address,id',
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);

        $address = Address::where('id', $request->get('address_id'))->first();

        if ($address) {
            $address->delete();
            return $this->returnResult();
        } else {
            return $this->throwError('Địa chỉ không tồn tại!', 400);
        }


    }

    public function addAddress(Request $request)
    {
        $user = $this->getUser();

        if (empty($user))
            return $this->throwError('Vui lòng đăng nhập để tiếp tục!', 401);

        $validate_rule = [
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'phone'                => "required|bail|numeric",
            'province_id'          => "required|bail|required|exists:lck_location_province,id",
            'district_id'          => "required|bail|required|exists:lck_location_district,id",
            'ward_id'              => "required|bail|required|exists:lck_location_ward,id",
            'street_name'          => 'required|bail|string|max:200',
            'is_default_recipient' => "nullable|bail|in:0,1",
            'is_warehouse'         => "nullable|bail|in:0,1",
            'is_return'            => "nullable|bail|in:0,1",
        ];

        if ($request->isMethod('POST')) {

            $validator = Validator::make($request->all(), $validate_rule);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $error_return = [];

                foreach (array_keys($validate_rule) as $v) {
                    if ($errors->first($v))
                        $error_return[$v] = $errors->first($v);
                }

                return $this->throwError($error_return, 1);
            }

            try {
                DB::beginTransaction();

                $params = array_fill_keys(array_keys($validate_rule), null);
                $params = array_merge(
                    $params, $request->only(array_keys($params))
                );
                $params['user_id'] = $user->id;
                $params['full_address'] = Address::get_full_address($params);
                $data = Address::create($params);

                if ($params['is_default_recipient'] == 1) {
                    Address::where('is_default_recipient', '=', 1)
                        ->where('id', '<>', $data->id)
                        ->where('user_id', $user->id)
                        ->update(['is_default_recipient' => 0]);
                }
                if ($params['is_warehouse'] == 1) {
                    Address::where('is_warehouse', '=', 1)
                        ->where('id', '<>', $data->id)
                        ->where('user_id', $user->id)
                        ->update(['is_warehouse' => 0]);
                }
                if ($params['is_return'] == 1) {
                    Address::where('is_return', '=', 1)
                        ->where('id', '<>', $data->id)
                        ->where('user_id', $user->id)
                        ->update(['is_return' => 0]);
                }

                DB::commit();

                $this->returnResult();
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->throwError('Có lỗi xảy ra, vui lòng thử lại!', 500);
            }
        }

//        $this->_data['provinces'] = Province::orderBy('name', 'ASC')->get();
//        $this->_data['districts'] = old('province_id') ? District::where('province_id', old('province_id'))->orderBy('position', 'ASC')->get() : [];
//        $this->_data['wards'] = old('district_id') ? Ward::where('district_id', old('district_id'))->get() : [];
//
//        $this->_data['address'] = (object)array_fill_keys(array_keys($validate_rule), null);

//        $html = view('frontend.ajax.address.add', $this->_data)->render();

        return redirect()->back();
    }

    public function getAddressById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:lck_address,id',
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);

        $address = Address::find($request->get('id'));
        $this->_data['provinces'] = Province::orderBy('name', 'ASC')->get();
        $this->_data['districts'] = District::where('province_id', $address->province_id)->orderBy('priority', 'ASC')->get();
        $this->_data['wards'] = Ward::where('district_id', $address->district_id)->orderBy('priority', 'ASC')->get();
        $this->_data['address'] = $address;
//        return $this->returnResult($address);
        $html = view('frontend.cart.address-template', $this->_data)->render();

        return $this->returnResult(['html' => $html]);
    }

    public function checkDiscountPoint(Request $request)
    {
        $this->getUser();

        if (!Auth::guard('web')->check())
            return $this->throwError('Vui lòng đăng nhập!', 400);

        $validator = Validator::make($request->all(), [
            'product_price' => 'required|integer',
            'point'         => 'required|string',
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);
        if (Auth::guard('web')->user()->point < $request->get('point'))
            return $this->throwError('Điểm tích lũy không đủ', 400);

        $total_price_discount = $request->get('point') * 1000;
        $total_price_can_discount = $request->get('product_price') * 0.5;

        if($total_price_discount > $total_price_can_discount){
            return $this->throwError('Số tiền giảm giá không vượt quá 50% giá trị đơn hàng!', 400);
        }

        $total_reduce = $total_price_discount;

        return $this->returnResult([
            'reduce' => $total_reduce
        ]);
    }
}
