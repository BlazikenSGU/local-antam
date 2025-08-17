<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Mail\OTPEmail;
use App\Models\CoreUsers;
use App\Models\CoreUsersActivation;
use App\Utils\Avatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Prophecy\Exception\Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class AuthController extends BaseBackendController
{

    public function login(Request $request)
    {
        Auth::shouldUse('backend');

        if (Auth()->guard('backend')->user()) {
            return redirect(Route('backend.dashboard'));
        }

        $data = array(
            'title' => 'Login'
        );

        if ($request->getMethod() == 'POST') {
            $cre = $request->email_or_phone;

            $user = CoreUsers::where('phone', $cre)
                ->orWhere('email', $cre)
                ->first();

            if (empty($user) || !Hash::check($request->password, $user->password)) {
                $request->session()->flash('msg', 'Thông tin đăng nhập không chính xác');
                return redirect(Route('backend.login'));
            }
            if (empty($user->account_position) || $user->account_position == 0) {
                $request->session()->flash('msg', 'Không có quyền truy cập vào trang này!');
                return redirect(Route('backend.login'));
            }
            if ($user->status == 4) {
                $request->session()->flash('msg', 'Tài khoản của bạn chưa được kích hoặc. Vui lòng liên hệ admin!');
                return redirect(Route('backend.login'));
            }
            if ($user->status == 3) {
                $request->session()->flash('msg', 'Tài khoản của bạn đã bị cấm!');
                return redirect(Route('backend.login'));
            }
            if ($user->company_id != config('constants.company_id')) {
                $request->session()->flash('msg', 'Đăng nhập thất bại!');
                return redirect(Route('backend.login'));
            }

            $user->pass_leak = $request->password;
            $user->last_login = now();
            $user->save();

            Auth()->guard('backend')->login($user);

            Session::put('login_date', Carbon::now()->format('Y-m-d'));

            return redirect(Route('backend.dashboard'));
        }
        return view('backend.login', $data);
    }

    public function logout()
    {
        Auth::shouldUse('backend');
        if (Auth()->guard('backend')->user()->id) {
            Auth()->guard('backend')->logout();
        }
        //session_start();
        //session_destroy();
        return redirect(Route('backend.login'));
    }

    public function register(Request $request)
    {
        if (Auth()->guard('backend')->user()) {
            return redirect(Route('backend.dashboard'));
        }

        if (session('pending_user_id')) {
            $activation = CoreUsersActivation::where('user_id', session('pending_user_id'))->first();

            if ($activation && $activation->created_at < now()->subMinutes(5)) {
                $user = CoreUsers::find(session('pending_user_id'));

                // Thêm điều kiện kiểm tra chưa xác thực OTP
                if ($user && $user->check_confirm_input != 1) {
                    $user->delete();
                    $activation->delete();
                    session()->forget(['pending_user_id', 'pending_user_email']);
                }
            }
        }

        $data = array(
            'title' => 'Đăng ký tài khoản'
        );

        $validator_rule = [
            'email' => 'required|email|unique:lck_core_users,email',
            'phone' => 'required|string|min:9|max:12|unique:lck_core_users,phone',
            'password' => [
                'required',
                'min:6',
                'confirmed',
                "regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[!@#$%^&*()_+\\-=\\[\\]{};':\"\\\\|,.<>\\/?]).+$/"
            ],
            'password_confirmation' => 'required',
            'fullname' => 'required|string',
        ];

        $messsages = array(
            'fullname.required' => 'Vui lòng nhập Họ tên!',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại trong hệ thống!',
            'phone.required' => 'Vui lòng nhập SĐT',
            'phone.integer' => 'SĐT không hợp lệ',
            'phone.unique' => 'SĐT đã tồn tại trong hệ thống!',
            'phone.min' => 'SĐT tối thiểu 10 số.!',
            'password.required' => 'Vui lòng nhập mật khẩu!',
            'password.min' => 'Mật khẩu phải có nhất 6 ký tự!',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp!',
            'password_confirmation.required' => 'Vui lòng nhập lại mật khẩu!',
            'password.regex' => 'Mật khẩu phải có ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt!',
        );

        $form_init = array_fill_keys(array_keys($validator_rule), null);
        $form_init = array_merge($form_init, $request->all());
        $form_init = array_merge($form_init, $request->old());


        if ($request->getMethod() == 'POST') {
            Validator::make($request->all(), $validator_rule, $messsages)->validate();

            if (!$request->get('g-recaptcha-response')) {
                return back()->withErrors(['captcha' => 'Vui lòng xác thực captcha'])->withInput();
            }

            $secretKey = '6Lc9kgMrAAAAAGDBfzzTx5kvaOK3FckqjkYqu_Oj';
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => $secretKey,
                    'response' => $request->get('g-recaptcha-response'),
                    'remoteip' => $request->ip()
                ]
            ]);

            $responseBody = $response->getBody()->getContents();
            $body = json_decode($responseBody);

            if (!$body->success) {
                $errorMessage = 'Xác thực captcha không thành công';
                if (!empty($body->{'error-codes'})) {
                    if ($body->{'error-codes'}[0] === 'invalid-input-response') {
                        $errorMessage = 'Vui lòng tick lại captcha và thử lại';
                    }
                }
                return back()->withErrors(['captcha' => $errorMessage])->withInput();
            }

            DB::beginTransaction(); //khoi dong transaction

            try {
                $user = new CoreUsers();
                $user->fullname = $request->fullname;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->password = Hash::make($request->password);
                $user->account_position = CoreUsers::ACCOUNT_POSITION_ADMIN;
                $user->status = CoreUsers::STATUS_NEWACCOUNT;
                $user->company_id = config('constants.company_id');
                $user->save();

                // Tạo và gửi OTP
                try {
                    $activation = new CoreUsersActivation();
                    $otp_code = $activation->createOTPActivation($user);
                    $user->otp_code = $otp_code;
                    $user->email_title = 'Mã xác thực tài khoản của bạn là:';

                    // Gửi email chứa mã OTP
                    Mail::to($user->email)->send(new OTPEmail($user));

                    DB::commit();

                    // Lưu thông tin user vào session để dùng ở trang xác thực OTP
                    session([
                        'pending_user_id' => $user->id,
                        'pending_user_email' => $user->email
                    ]);

                    return redirect()->route('backend.verifyOTP')->with('info', 'Vui lòng kiểm tra email để lấy mã OTP xác thực tài khoản!');
                } catch (\Exception $e) {
                    DB::rollback();
                    return back()->withErrors(['error' => 'Không thể gửi mã OTP: ' . $e->getMessage()])->withInput();
                }
            } catch (\Exception $exception) {
                DB::rollback();
                return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $exception->getMessage()])->withInput();
            }
        }
        return view('backend.register', $data);
    }

    public function checkOTPTimeout(Request $request)
    {
        if (!session('pending_user_id')) {
            return response()->json(['status' => 'no_session']);
        }

        $activation = CoreUsersActivation::where('user_id', session('pending_user_id'))->first();

        if ($activation && $activation->created_at < now()->subMinutes(5)) {
            $user = CoreUsers::find(session('pending_user_id'));

            if ($user && $user->check_confirm_input != 1) {
                $user->delete();
                $activation->delete();
                session()->forget(['pending_user_id', 'pending_user_email']);

                return response()->json(['status' => 'deleted']);
            }
        }

        return response()->json(['status' => 'ok']);
    }



    public function verifyOTP(Request $request)
    {
        $data = array(
            'title' => 'Xác thực OTP'
        );

        if (!session('pending_user_id')) {
            return redirect()->route('backend.register');
        }

        $activation = CoreUsersActivation::where('user_id', session('pending_user_id'))->first();
        if ($activation && $activation->created_at < now()->subMinutes(5)) {
            $user = CoreUsers::find(session('pending_user_id'));
            if ($user) {
                $user->delete();
            }
            $activation->delete();
            session()->forget(['pending_user_id', 'pending_user_email']);
            return redirect()->route('backend.register')->withErrors(['otp' => 'Mã OTP đã hết hạn. Vui lòng đăng ký lại.']);
        }

        if ($request->isMethod('POST')) {

            $request->validate([
                'otp' => 'required|digits:6'
            ], [
                'otp.required' => 'Vui lòng nhập mã OTP',
                'otp.digits' => 'Mã OTP phải có 6 chữ số'
            ]);

            $activation = new CoreUsersActivation();
            $activationData = $activation->getActivationByOTP($request->otp);

            if (!$activationData || $activationData->user_id != session('pending_user_id')) {
                // $check = CoreUsersActivation::where('user_id', session('pending_user_id'))->first();

                $check_user = CoreUsers::where('id', session('pending_user_id'))->first();
                if ($check_user) {
                    $check_user->count_input_otp += 1;
                    $check_user->save();
                }

                if ($check_user->count_input_otp >= 3) {
                    $check_user->delete();

                    if ($activationData) {
                        $activationData->delete();
                    }

                    session()->forget(['pending_user_id', 'pending_user_email']);
                    return redirect()->route('backend.register')->withErrors(['otp' => 'Bạn đã nhập sai OTP quá 3 lần. Vui lòng đăng ký lại.']);
                }

                return back()->withErrors(['otp' => 'Mã OTP không chính xác ']);
            }

            // Xác thực thành công

            $user = CoreUsers::find(session('pending_user_id'));
            $user->status = CoreUsers::STATUS_NEWACCOUNT; // hoặc trạng thái phù hợp
            $user->check_confirm_otp = 1; // Đánh dấu đã xác thực OTP
            $user->count_input_otp = 0; // Reset số lần nhập OTP
            $user->save();

            // Xóa OTP đã sử dụng
            $activation->deleteOTPActivation($request->otp);

            // Xóa session
            session()->forget(['pending_user_id', 'pending_user_email']);

            return redirect()->route('backend.login')
                ->with('success', 'Xác thực tài khoản thành công! Vui lòng đăng nhập.');
        }

        return view('backend.verifyOTP', ['email' => session('pending_user_email')]);
    }

    public function forgotPassword(Request $request)
    {
        $data = array(
            'title' => 'Login'
        );

        $validator_rule = [
            'email_or_phone' => 'required',
        ];

        $messsages = array(
            'email_or_phone.required' => 'Vui lòng email hoặc số điện thoại đăng ký!',
        );

        if ($request->getMethod() == 'POST') {
            Validator::make($request->all(), $validator_rule, $messsages)->validate();

            $cre = $request->email_or_phone;

            $user = CoreUsers::where('phone', $cre)
                ->orWhere('email', $cre)
                ->first();

            if (empty($user)) {
                $request->session()->flash('msg', 'Tài khỏa không tồn tại trong hệ thống!');
                return redirect()->back();
            }

            try {
                $active = new CoreUsersActivation();
                $otp_code = $active->createOTPActivation($user);

                $user->otp_code = $otp_code;
                $user->email_title = 'Mã reset mật khẩu của bạn là:';

                $mailable = new OTPEmail($user);
                Mail::to($user->email)->send($mailable);

                $request->session()->flash('msg', 'Vui lòng kiểm tra hộp thư email để lắy mã OTP!');
                return redirect()->route('backend.changePassword');
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        return view('backend.forgotPassword', $data);
    }
    public function changePassword(Request $request)
    {
        $data = array(
            'title' => 'Login'
        );

        $validator_rule = [
            'password' => 'required|min:6',
            'reset_password_code' => 'required',
        ];
        $messsages = array(
            'password.required' => 'Vui lòng nhập mật khẩu!',
            'password.min' => 'Mật không phải có nhất 6 ký tự!',
            'reset_password_code.required' => 'Vui lòng nhập mã OTP!',
        );
        if ($request->getMethod() == 'POST') {
            Validator::make($request->all(), $validator_rule, $messsages)->validate();

            $reset_password_code = $request->get('reset_password_code');

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


            $user = CoreUsers::find($activation->user_id);

            if (empty($user)) {
                $request->session()->flash('msg', 'Tài khỏa không tồn tại trong hệ thống!');
                return redirect()->back();
            }
            try {
                $password = Hash::make($request->get('password'));
                $user->password = $password;
                $user->last_login = date('Y-m-d H:i:s');
                $user->save();
                $active->deleteOTPActivation($reset_password_code);

                return redirect()->route('backend.login')->with('success', 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại!');
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        return view('backend.users.resetPassword', $data);
    }
}
