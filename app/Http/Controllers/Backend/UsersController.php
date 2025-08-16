<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\Address;
use App\Models\Branch;
use App\Models\CoreUsers;
use App\Models\CoreUsersActivation;
use App\Models\Orders;
use App\Models\Settings;
use App\Utils\Avatar;
use App\Utils\Common as Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Foundation\Auth\RegistersUsers;


class UsersController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Quản lý tài khoản',
        'subtitle' => 'Quản lý tài khoản',
    );
    protected $_limits = [
        10,
        30,
        50,
        100,
        500,
        1000,
        5000,
        10000
    ];
    public function __construct()
    {
        $settings = Settings::get_setting_member();

        foreach ($settings as $k => $v) {
            $this->_data[$k] = $v;
        }

        $this->_data['_limits'] = $this->_limits;
        parent::__construct();
    }
    public function profile(Request $request)
    {
        $validator_rule = [
            'fullname'    => 'required|string',
            'otp_code'    => 'required|string',
            'email'       => [
                'bail',
                'nullable',
                'email',
                Rule::unique('lck_core_users')->ignore(Auth()->guard('backend')->user()->id),
            ],

        ];

        $form_init = array_merge(
            array(
                'fullname'          => null,
                'email'             => null,
                'province_id'       => null,
                'district_id'       => null,
                'ward_id'           => null,
                'province_name'     => null,
                'district_name'     => null,
                'ward_name'         => null,
                'bank_name'         => null,
                'bank_account'      => null,
                'bank_number'       => null,
                'oldpassword'       => null,
                'newpassword'       => null,
            ),
            Utils::J_filterEntities($request->all())
        );

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            try {
                $user = CoreUsers::find(Auth()->guard('backend')->user()->id);
                $otp_code = $form_init['otp_code'];
                $active = new CoreUsersActivation();

                $activation = $active->getActivationByOTP($otp_code);
                if (empty($activation))
                    return redirect()->back()->with('success', 'Mã OTP không hợp lệ.');

                // Kiêm tra thời gian hết hạn OTP là 10 phút
                $current_time = time();
                $activation_created_at = strtotime($activation->created_at);
                if ($current_time - $activation_created_at > 600) {
                    $active->deleteOTPActivation($otp_code);
                    return redirect()->back()->with('success', 'Mã OTP đã quá hạn.');
                }

                $aUpdate = array(
                    'fullname' => $form_init['fullname'],
                    'email'    => $form_init['email'],
                    'province_id'    => $form_init['province_id'],
                    'district_id'    => $form_init['district_id'],
                    'ward_id'    => $form_init['ward_id'],
                    'province_name'    => $form_init['province_name'],
                    'district_name'    => $form_init['district_name'],
                    'ward_name'    => $form_init['ward_name'],
                    'bank_name'    => $form_init['bank_name'],
                    'bank_number'    => $form_init['bank_number'],
                    'bank_account'    => $form_init['bank_account'],

                );

                if (!empty($form_init['newpassword'])) {
                    if (empty($form_init['oldpassword'])) {
                        return redirect()->back()
                            ->withInput($request->all())
                            ->withErrors(['message' => "Vui lòng nhập mật khẩu cũ!"]);
                    } elseif (!Hash::check($form_init['oldpassword'], Auth()->guard('backend')->user()->password)) {
                        return redirect()->back()
                            ->withInput($request->all())
                            ->withErrors(['message' => "Mật khẩu cũ không chính xác!"]);
                    } else {
                        $aUpdate['password'] = bcrypt($form_init['newpassword']);
                    }
                }

                if ($request->hasFile('file_avatar')) {

                    $sub_dir = date('Y/m/d');

                    $filename = md5(time()) . '.' . $request->file_avatar->extension();
                    $full_dir = config('constants.upload_dir.root');

                    if (!is_dir($full_dir . '/' . $sub_dir)) {
                        mkdir($full_dir . '/' . $sub_dir, 0777, true);
                    }

                    Image::make(Input::file('file_avatar'))->fit(300)
                        ->save($full_dir . '/' . $sub_dir . '/' . $filename);

                    $aUpdate['avatar_file_path'] = config('constants.upload_dir.url') . '/' . $sub_dir . '/' . $filename;

                    Avatar::delete($user->avatar, $full_dir);
                }

                $user->update($aUpdate);

                $request->session()->flash('msg', ['info', 'Cập nhật thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
            }
            return ;
        }
        $this->_data['title'] = 'Thông tin tài khoản';

        return view('backend.users.profile', $this->_data);
    }

    public function index(Request $request)
    {

        $userId = Auth()->guard('backend')->user()->id;
        // check defaul data
        $is_default = Address::where('is_default', 1)->where('user_id', $userId)->first();
        if (empty($is_default)) {
            $is_default = Address::where('user_id', $userId)->first();
            if (!empty($is_default)) {
                $is_default->update(['is_default' => 1]);
            }
        }
        $listData = Address::where('user_id', $userId)->get();
        $this->_data['data'] = $listData;
        return view('backend.users.index', $this->_data);
    }

    public function add(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            try {
                $params['phone'] = $request->get('phone');
                $params['name'] = $request->get('name');
                $params['company_id'] = $request->get('5');
                $params['province_id'] = $request->get('province_id');
                $params['district_id'] = $request->get('district_id');
                $params['ward_id'] = $request->get('ward_id');
                $params['street_name'] = $request->get('street_name');
                $params['province_name'] = $request->get('province_name');
                $params['district_name'] = $request->get('district_name');
                $params['ward_name'] = $request->get('ward_name');
                $params['user_id'] = Auth()->guard('backend')->user()->id;
                $params['product_type'] = $request->get('product_type');
                $params['note'] = $request->get('note');
                $params['required_note'] = $request->get('required_note');
                $params['payment_type'] = $request->get('payment_type');
                Address::create($params);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect(Route('backend.users.index'))->with('success', 'Tạo cửa hàng thành công.');
        }
        return view('backend.users.form', $this->_data);
    }
    public function edit(Request $request, $id)
    {
        $data = Address::find($id);
        if ($request->getMethod() == 'POST') {
            try {
                $params['name'] = $request->get('name');
                $params['phone'] = $request->get('phone');
                $params['company_id'] = $request->get('5');
                $params['province_id'] = $request->get('province_id');
                $params['district_id'] = $request->get('district_id');
                $params['ward_id'] = $request->get('ward_id');
                $params['street_name'] = $request->get('street_name');
                $params['province_name'] = $request->get('province_name');
                $params['district_name'] = $request->get('district_name');
                $params['ward_name'] = $request->get('ward_name');
                $params['user_id'] = Auth()->guard('backend')->user()->id;
                $params['product_type'] = $request->get('product_type');
                $params['note'] = $request->get('note');
                $params['required_note'] = $request->get('required_note');
                $params['payment_type'] = $request->get('payment_type');
                $data->update($params);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect(Route('backend.users.index'))->with('success', 'Cập nhập cửa hàng thành công.');
        }
        $this->_data['data'] = $data;
        return view('backend.users.form', $this->_data);
    }


    public function delete(Request $request, $id)
    {
        try {
            $user = CoreUsers::find($id);

            if (empty($user)) {
                $request->session()->flash('msg', ['danger', 'Tài khoản không tồn tại!']);
            } else {
                if (Auth()->guard('backend')->user()->id == $id) {
                    $request->session()->flash('msg', ['danger', 'Không thể xóa tài khoản này!']);
                } else {
                    $full_dir = config('constants.upload_dir.root') . '/backend';
                    Avatar::delete($user->avatar, $full_dir);
                    $user->delete();
                    $user->destroy($id);
                    $request->session()->flash('msg', ['info', 'Đã xóa thành công!']);
                }
            }
        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        return redirect($this->_ref ? $this->_ref : Route('backend.users.index'));
    }
}
