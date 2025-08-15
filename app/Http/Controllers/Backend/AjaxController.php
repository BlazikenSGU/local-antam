<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Mail\Order;
use App\Mail\OTPEmail;
use App\Models\Address;
use App\Models\CoreUsers;
use App\Models\CoreUsersActivation;
use App\Models\Files;
use App\Models\Location\Street;
use App\Models\Orders;
use App\Models\Profile;
use App\Models\Salary;
use App\Models\Variations;
use App\Models\VariationValues;
use App\Models\YourBank;
use App\ReportsHourly;
use App\Utils\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\Optimizers\Jpegoptim;
use Spatie\ImageOptimizer\Optimizers\Optipng;
use Spatie\ImageOptimizer\Optimizers\Pngquant;
use Validator;

class AjaxController extends BaseBackendController
{
    public function searchUser(Request $request)
    {
        $term = trim($request->q);
        $t = trim($request->get('t', null));

        if (empty($term)) {
            return \Response::json([]);
        }

        $users = CoreUsers::select(['id', 'phone'])
            ->where('phone', 'like', "%$term%");

        if ($t == 'callback')
            $users = $users->whereIn('account_position', [2, 3]);

        $users = $users->limit(100)->get();

        $formatted_users = [];

        foreach ($users as $user) {
            $formatted_users[] = ['id' => $user->id, 'text' => $user->phone];
        }

        return \Response::json($formatted_users);
    }

    public function addStreet(Request $request)
    {
        $district_id = trim($request->district_id);
        $street_name = trim($request->street_name);

        if (empty($district_id) || empty($street_name)) {
            return \Response::json(['e' => 1, 'r' => 'Dữ liệu không hợp lệ, vui lòng thử lại!']);
        }
        $street_name = ucwords($street_name);
        $street_name = preg_replace('!\s+!', ' ', $street_name);
        $street_name_ascii = Filter::vnToAscii($street_name);

        \DB::enableQueryLog();

        $count = Street::whereRaw("`name` = '{$street_name}' and name_ascii = '{$street_name_ascii}' and district_id = '{$district_id}' ")
            ->count();

//        \Log::info(\DB::getQueryLog());

        if ($count > 0)
            return \Response::json(['e' => -1, 'r' => 'Tên đường đã tồn tại!']);

        Street::create(
            [
                'name'            => $street_name,
                'name_ascii'      => $street_name_ascii,
                'district_id'     => $district_id,
                'user_id_created' => Auth()->guard('backend')->user()->id
            ]
        );

        return \Response::json(['e' => 0]);
    }

    public function uploadImage(Request $request)
    {
        $photos = $request->file('file');
        $type = $request->get('type');

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

            $image = Image::make($photo)->orientate();

            if ($type == 3) {
                $width = $size_w = 1024;
                $height = $size_h = 500;

                if ($image->width() > $width || $image->height() > $height) {
                    $width = null;
                    $height = null;

                    if ($image->width() < $image->height()) {
                        $height = $size_h;
                        $image->resize($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    } else {
                        $width = $size_w;
                        $image->resize($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        if ($image->height() > $size_h) {
                            $height = $size_h;
                            $width = null;
                            $image->resize($width, $height, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }
                    }
                }

                if (in_array($ext, ['webp', 'png']))
                    $image_new = Image::canvas($size_w, $size_h);
                else
                    $image_new = Image::canvas($size_w, $size_h, "#fff");

                $image_new->insert($image, 'center');

                $image_new->save($origin_file_path, 75);
            } else if ($type == Files::TYPE_PRODUCT) {
                $width = $size_w = 1024;
                $height = $size_h = 1024;

                if ($image->width() > $width || $image->height() > $height) {
                    $width = null;
                    $height = null;

                    if ($image->width() < $image->height()) {
                        $height = $size_h;
                        $image->resize($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    } else {
                        $width = $size_w;
                        $image->resize($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        if ($image->height() > $size_h) {
                            $height = $size_h;
                            $width = null;
                            $image->resize($width, $height, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }
                    }
                }

                if (in_array($ext, ['webp', 'png']))
                    $image_new = Image::canvas($size_w, $size_h);
                else
                    $image_new = Image::canvas($size_w, $size_h, "#fff");

                $image_new->insert($image, 'center');

                $image_new->save($origin_file_path, 75);
            } else {
                if ($image->width() > 2048)
                    $image->resize(2048, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                $image->save($origin_file_path, 80);
            }

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

            $type = $request->get('type', Files::TYPE_PRODUCT);

            $image = Files::create([
                'user_id'   => Auth()->guard('backend')->user()->id,
                'file_path' => $file_path,
                'type'      => $type
            ]);
            $items[] = [
                'id'   => $image->id,
                'path' => $file_path,
                'url'  => config('constants.upload_dir.url') . '/' . $file_path,
            ];
        }

        return \Response::json(['e' => 0, 'r' => $items]);
    }


    public function removeImage(Request $request)
    {
        return \Response::json([]);
    }

    public function getVariationValue(Request $request)
    {
        return \Response::json([
            'e' => 0,
            'r' => VariationValues::where('variation_id', $request->get('variation_id'))->get()
        ]);
    }

    public function createVariation(Request $request)
    {
        if (empty($request->name))
            return \Response::json(['e' => 1, 'r' => 'Vui lòng nhập tên thuộc tính!']);

        $check = Variations::where('name', $request->name)->first();
        if ($check)
            return \Response::json(['e' => 1, 'r' => 'Thuộc tính đã tồn tại, vui lòng nhập thuộc tính khác.']);

        $variation = Variations::create([
            'company_id' => config('constants.company_id'),
            'name'       => mb_ucfirst(trim($request->name)),
        ]);

        return \Response::json(['e' => 0, 'r' => ['id' => $variation->id, 'text' => $variation->name], 'v' => $variation]);
    }

    public function ajaxSalary(Request $request)
    {
        $id = (int)$request->id;
        $totalSalary = '';
        $msg = '';
        $error = '';
        $salary = CoreUsers::find($request->id);
        if (!empty($salary)) {
            $totalSalary .= number_format($salary->balance) . 'đ';
        }

        $your_bank = YourBank::where('user_id', $request->id)
            ->with(['bank', 'address'])
            ->get();
        $e_bank = [];
        if (!empty($your_bank)) {

            foreach ($your_bank as $key => $value) {


                $e_bank[] = ' <div class="row ">
                                            <div class="col-md-4 col-lg-3 text-center">
  <input type="radio" id="' . $value['id'] . '" name="bank_id" value="' . $value['id'] . '">
  <label for="' . $value['id'] . '"></label>
                                                                            <a href="#"><img src="' . $value['bank']['image'] . '" alt="user" class="img-circle img-responsive"></a>
                                                                        </div>
                                                                        <div class="col-md-8 col-lg-9">
                                                                            <h3 class="box-title m-b-0 name-bank">' . $value['bank']['code'] . '</h3> <small>' . $value['bank']['name'] . '</small>
                                                                            <address>
                                                                            Họ&Tên: <b>' . $value['fullname'] . '</b>
                                                                            <br>
                                                                                Số tài khoản: <b>' . $value['bank_account_number'] . '</b>
                                                                                <br>
                                                                                Chi nhánh: <b>' . $value['bank_branch'] . '</b>
                                                                                <br>

                                                                            </address>
                                                                        </div>
                                                                         </div>';

            }

        } else {
            $e_bank[] = null;
        }
        $data = [
            'data'  => [
                'total'   => $totalSalary,
                'user_id' => $request->id,
                'e_bank'  => $e_bank,
            ],
            'msg'   => $msg,
            'error' => $error,
        ];
        return response()->json($data, 200);
    }

    public function paySalary(Request $request)
    {

        if ($request->bank_id == null) {
            return $this->throwError('Vui lòng chọn ngân hàng bạn đã chuyển tiền!', 400);
        }
        $your_bank = YourBank::findOrFail($request->bank_id);
        $your_bank_value = [
            'fullname'            => $your_bank->fullname,
            'bank_account_number' => $your_bank->bank_account_number,
            'bank_id'             => $your_bank->bank_id,
        ];

        if ($request->salary_payed < 0 || $request->salary_payed == null) {
            return $this->throwError('Không được bỏ trống số tiền thanh toán hoặc số tiền thanh toán nhỏ hơn 0!', 400);
        }
        $user = CoreUsers::findOrFail($request->user_id);

        $total = $user->balance;

        if ($request->salary_payed > $total) {
            return $this->throwError('Số dư hiện tại không đủ!', 400);
        }


        $user->balance = $total - $request->salary_payed;
        $user->save();
        $fullname = !empty($user->fullname) ? $user->fullname : null;
        $phone = !empty($user->phone) ? $user->phone : null;
        $email = !empty($user->email) ? $user->email : null;
        $salary = new Salary;
        $salary->user_id = $request->user_id;
        $salary->fullname = $fullname;
        $salary->phone = $phone;
        $salary->email = $email;
        $salary->title = 'Thanh toán tiền trong ví!';
        $salary->salary = -$request->salary_payed;
        $salary->bank = json_encode($your_bank_value);
        $salary->save();

        return $this->returnResult($salary);
    }

    public function shippingFee(Request $request)
    {
        $order = Orders::find($request->id);
        if (!$order)
            return $this->throwError('Đơn hàng không tồn tại!', 400);
        $order->shipping_fee = $request->get('shipping_fee');
        $order->total_price = $order->product_price + $order->shipping_fee;

        $order->save();

        try {
            if (!empty($order->email)) {

                Mail::to($order->email)->send(new Order($order->id));
            }
        } catch (\Exception $e) {
            return $this->throwError('Có lỗi xảy ra, vui lòng thử lại' . $e->getMessage());
        }
        return $this->returnResult($order);
    }

    public function changeAddress(Request $request)
    {
        // Lấy địa chỉ từ request
        $address = Address::find($request->get('id'));

        // Kiểm tra xem địa chỉ đã tồn tại hay không
        if (!$address) {
            return $this->returnResult(null, 'Địa chỉ không tồn tại', false);
        }

        // Kiểm tra xem người dùng đang đăng nhập có quyền thay đổi địa chỉ không
        if ($address->user_id !== Auth()->guard('backend')->user()->id) {
            return $this->returnResult(null, 'Bạn không có quyền thay đổi địa chỉ này', false);
        }

        // Kiểm tra xem địa chỉ đang được thay đổi có phải là mặc định không
        if ($address->is_default == 1) {
            return $this->returnResult(null, 'Địa chỉ đã là địa chỉ mặc định', false);
        }

        // Tìm địa chỉ mặc định trước đó của người dùng và cập nhật lại
        $before_default = Address::where('user_id', Auth()->guard('backend')->user()->id)
            ->where('is_default', 1)
            ->first();
        if ($before_default) {
            $before_default->is_default = 0;
            $before_default->save();
        }

        // Đặt địa chỉ mới là địa chỉ mặc định
        $address->is_default = 1;
        $address->save();

        // Cập nhật thông tin địa chỉ mặc định cho người dùng
        $user = CoreUsers::find(Auth()->guard('backend')->user()->id);
        $user->update([
            'province_id' => $address->province_id,
            'district_id' => $address->district_id,
            'ward_id' => $address->ward_id,
            'province_name' => $address->province_name,
            'district_name' => $address->district_name,
            'ward_name' => $address->ward_name,
            'address' => $address->street_name,
        ]);

        // Trả về kết quả
        return $this->returnResult($address, 'Địa chỉ đã được thay đổi thành công', true);
    }

    public function GetOTP()
    {
        $user = CoreUsers::find(Auth()->guard('backend')->user()->id);



        $active = new CoreUsersActivation();
        $otp_code = $active->createOTPActivation($user);
        $user->otp_code = $otp_code;
        $user->email_title = 'Mã reset của bạn là:';
        $mailable = new OTPEmail($user);
        Mail::to($user->email)->send($mailable);

        return $this->returnResult($user->email);

    }
    public function check_otp(Request $request)
    {
        $reset_password_code = $otp = $request->get('otp');
        $active = new CoreUsersActivation();

        $activation = $active->getActivationByOTP($reset_password_code);


        if (empty($activation))
            return $this->throwError('Mã reset mật khẩu không hợp lệ', 400);


        // Kiêm tra thời gian hết hạn OTP là 10 phút
        $current_time = time();
        $activation_created_at = strtotime($activation->created_at);
        if ($current_time - $activation_created_at > 600) {
            $active->deleteOTPActivation($reset_password_code);
            return $this->throwError('Mã reset đã quá hạn.', 400);
        }
        return $this->returnResult($otp);

    }

}
