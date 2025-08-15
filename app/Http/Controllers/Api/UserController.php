<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseAPIController;
use App\Mail\OTPEmail;
use App\Models\CoreUsers;
use App\Models\CoreUsersActivation;
use App\Models\Files;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;
use Validator;

class UserController extends BaseAPIController
{
    // use RegistersUsers;


    /**
     * @OA\Post(
     *     path="/user/login",
     *     tags={"Users"},
     *     summary="User login",
     *     description="",
     *     operationId="UserLogin",
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *			     @OA\Property(property="company_id", description="Company ID", type="string", ),
     *			     @OA\Property(property="api_key", description="API KEY", type="string", ),
     *			     @OA\Property(property="email_or_phone", description="email_or_phone", type="string", ),
     *			     @OA\Property(property="password", description="password", type="string", ),
     *			     @OA\Property(property="fcm_token", description="", type="string", ),
     *			     @OA\Property(property="device_id", description="", type="string", ),
     *			     @OA\Property(property="device_os", description="", type="string", ),
     *              required={"company_id","api_key","email_or_phone","password"}
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
    public function login(Request $request)
    {
        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required',
            'password'       => 'required',
        ]);

        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }
        $credentials = $request->only(['email_or_phone', 'password']);

        $user = CoreUsers::where(['email' => $credentials['email_or_phone']])
            ->orWhere(['phone' => $credentials['email_or_phone']])
            ->first();

        if (!$user)
            return $this->throwError('Tài khoản không tồn tại!', 404);

        if (!Hash::check($credentials['password'], $user->password))
            return $this->throwError('Mật khẩu không chính xác!', 400);

        if ($user->status == CoreUsers::$status_inactive)
            return $this->throwError('Tài khoản chưa được kích hoạt!', 405);

        if ($user->status == CoreUsers::$status_banned)
            return $this->throwError('Tài khoản đã bị khóa!', 403);

        if (!$token = auth('api')->login($user)) {
            return $this->throwError('Đăng nhập thất bại!', 400);
        }

        $user->fcm_token = $request->get('fcm_token', $user->fcm_token);
        $user->device_id = $request->get('device_id', $user->device_id);
        $user->device_os = $request->get('device_os', $user->device_os);

        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        $user->token = $token;
        $user->token_type = 'bearer';
        $user->token_expires_in = auth('api')->factory()->getTTL() * 60;

        return $this->returnResult($user);
    }

    /**
     * @OA\Post(
     *     path="/user/register",
     *     tags={"Users"},
     *     summary="User register",
     *     description="",
     *     operationId="UserRegister",
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *			    @OA\Property(property="company_id", description="Company ID", type="string", ),
     *			    @OA\Property(property="api_key", description="API KEY", type="string", ),
     *			    @OA\Property(property="fullname", description="fullname", type="string", ),
     *			    @OA\Property(property="email", description="email", type="string", ),
     *     			@OA\Property(property="password", description="password, min lenght 6", type="string", ),
     *     			@OA\Property(property="password_confirmation", description="password confirmation", type="string", ),
     *			    @OA\Property(property="phone", description="phone", type="numeric", ),
     *			    @OA\Property(property="address", description="address", type="string", ),
     *     			@OA\Property(property="fcm_token", description="", type="string", ),
     *     			@OA\Property(property="device_id", description="", type="string", ),
     *     			@OA\Property(property="device_os", description="", type="string",),
     *              required={"company_id","api_key","email","phone", "password","password_confirmation"}
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
    public function register(Request $request)
    {
        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $validate_rule = [
            'email'    => "bail|required|email|unique:lck_core_users,email",
            'password' => 'bail|required|string|min:6|confirmed',
            'phone'    => 'bail|nullable|numeric|unique:lck_core_users,phone',
            'fullname' => 'bail|nullable|string',
            'address'  => 'bail|nullable|integer',
        ];
        $validator = Validator::make($request->all(), $validate_rule);

        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }

        try {
            DB::beginTransaction();
            $user = new CoreUsers();

            $user->password = Hash::make($request->get('password'));
            $user->status = CoreUsers::$status_active;

            $phone = $request->get('phone', null);
            $email = $request->get('email', null);
            $address = $request->get('address', null);
            $fullname = $request->get('fullname', null);
            $fcm_token = $request->get('fcm_token', $user->fcm_token);
            $device_id = $request->get('device_id', $user->device_id);
            $device_os = $request->get('device_os', $user->device_os);

            $user->phone = $phone;
            $user->fullname = $fullname;
            $user->address = $address;
            $user->email = $email;
            $user->fcm_token = $fcm_token;
            $user->device_id = $device_id;
            $user->device_os = $device_os;


            $user->device_os = $device_os;

            /*
            user_category_id : 91 -> Khách hàng
            user_category_id : 92 -> Nhân viên
            */
            $user->user_category_id = 91;

            $user->save();

            //            $this->activationService->sendActivationMail($user);

            DB::commit();

            $user->token = $request->get('token');
            $user->token_type = 'bearer';
            $user->token_expires_in = auth('api')->factory()->getTTL() * 60;

            return $this->returnResult($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->throwError('Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/user/update",
     *     tags={"Users"},
     *     summary="User update info",
     *     description="",
     *     operationId="UserUpdate",
     *     @OA\Parameter(name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(name="api_key", description="API KEY", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(name="token", description="token from api login", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *     			@OA\Property(property="fullname", description="Fullname of user", type="string", ),
     *     			@OA\Property(property="email", description="Email of user", type="string", ),
     *     			@OA\Property(property="phone", description="phone", type="numeric", ),
     *     			@OA\Property(property="password_old", description="old password, require when update password", type="string", ),
     *     			@OA\Property(property="password", description="new password, min lenght 6, require when update password", type="string", ),
     *     			@OA\Property(property="password_confirmation", description="password confirmation, require when update password", type="string", ),
     *     			@OA\Property(property="gender", description="1: male, 2: female, 0: unknown, 3: other", type="integer", ),
     *     			@OA\Property(property="avatar_file", description="Avatar file", type="file", ),
     *     			@OA\Property(property="fcm_token", description="fcm_token", type="string", ),
     *     			@OA\Property(property="device_id", description="device_id", type="string", ),
     *     			@OA\Property(property="device_os", description="device_os", type="string", ),
     *              required={"email","phone"}
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
        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        //Log::debug($request->all());

        $user = $this->getAuthenticatedUser();

        $validate_rule = [
            'fullname'              => 'nullable|string|max:255',
            'email'                 => "nullable|bail|string|unique:lck_core_users,email,{$user->id}",
            'phone'                 => "nullable|bail|numeric|unique:lck_core_users,phone,{$user->id}",
            'password_old'          => 'nullable|bail|string',
            'password'              => 'nullable|bail|string|min:6',
            'password_confirmation' => 'nullable|bail|string|min:6',
            'gender'                => 'nullable|bail|integer',
            'avatar_file'           => 'nullable|bail',
        ];

        $validator = Validator::make($request->all(), $validate_rule);
        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }

        try {
            DB::beginTransaction();

            $user = CoreUsers::findOrFail($user->id);

            $password_old = $request->get('password_old', null);
            $password = $request->get('password', null);
            $password_confirmation = $request->get('password_confirmation', null);

            if ($password) {
                if (empty($password_old))
                    return $this->throwError('Vui lòng nhập mật khẩu cũ!', 400);
                if (!Hash::check($password_old, $user->password))
                    return $this->throwError('Mật khẩu cũ không hợp lệ!', 400);
                if ($password != $password_confirmation)
                    return $this->throwError('Xác nhận mật khẩu mới không khớp!', 400);

                $user->password = Hash::make($password);
            }


            $user->phone = $request->get('phone', $user->phone);
            $user->gender = (int)$request->get('gender', $user->gender);
            $user->email = $request->get('email', $user->email);
            $user->fullname = $request->get('fullname', $user->fullname);
            $user->fcm_token = $request->get('fcm_token', $user->fcm_token);
            $user->device_id = $request->get('device_id', $user->device_id);
            $user->device_os = $request->get('device_os', $user->device_os);


            if ($request->file('avatar_file')) {
                $photos = $request->file('avatar_file');

                $sub_dir = date('Y/m/d');

                if (!is_array($photos))
                    $photos = [$photos];

                $full_dir = config('constants.upload_dir.root') . '/' . $sub_dir;


                if (!is_dir($full_dir))
                    mkdir($full_dir, 0777, true);

                $items = [];

                for ($i = 0; $i < count($photos); $i++) {
                    $photo = $photos[$i];

                    $extension = $photo->getClientOriginalExtension();

                    $extension = $extension == 'webp' ? 'jpg' : $extension;

                    $filename = uniqid() . '.' . $extension;

                    $image = Image::make($photo);

                    if ($image->getWidth() > 1920)
                        $image->resize(1920, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });

                    $size = [];

                    $width = $size[0] = 800;
                    $height = $size[1] = 800;

                    $image->width() < $image->height() ? $width = null : $height = null;

                    $image->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $width = $size[0];

                    $image2 = Image::canvas($width, $size[1]);

                    $image2->insert($image, 'center')
                        ->save($full_dir . '/' . $filename, 90);

                    $file_path = $sub_dir . '/' . $filename;
                    $image = Files::create([
                        'user_id'   => $user->id,
                        'file_path' => $file_path,
                    ]);
                }
            }

            //            if (isset($image)) {
            $user->avatar_file_id = isset($image) ? $image->id : $user->avatar_file_id;
            $user->avatar_file_path = isset($image) ? $image->file_path : $user->avatar_file_path;
            //            }

            $user->save();

            DB::commit();

            $user->token = $request->get('token');
            $user->token_type = 'bearer';
            $user->token_expires_in = auth('api')->factory()->getTTL() * 60;

            return $this->returnResult($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->throwError('Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/user/forgot-password",
     *     tags={"Users"},
     *     summary="Request reset Password",
     *     description="",
     *     operationId="UserForgotPassword",
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *			    @OA\Property(property="company_id", description="COMPANY ID", type="integer", ),
     *			    @OA\Property(property="api_key", description="API Key", type="integer", ),
     *			    @OA\Property(property="email_or_phone", description="email_or_phone", type="string", ),
     *              required={"company_id","api_key","email_or_phone"}
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
    public function forgotPassword(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }

        $email_or_phone = $request->get('email_or_phone');
        $user = CoreUsers::where("email", $email_or_phone)
            ->orWhere('phone', $email_or_phone)
            ->first();

        if (!$user)
            return $this->throwError('Tài khoản không tồn tại!', 404);

        if ($user->status == CoreUsers::$status_inactive)
            return $this->throwError('Tài khoản chưa xác thực email!', 405);

        if ($user->status == CoreUsers::$status_banned)
            return $this->throwError('Tài khoản đã bị khóa!', 403);

        $active = new CoreUsersActivation();
        $otp_code = $active->createOTPActivation($user);
        $user->otp_code = $otp_code;
        $user->email_title = 'Mã reset mật khẩu của bạn là:';
        $mailable = new OTPEmail($user);
        Mail::to($user->email)->send($mailable);

        return $this->returnResult([], 'Vui lòng kiểm tra hộp thư email để lấy mã reset password!');
    }

    /**
     * @OA\Post(
     *     path="/user/reset-password",
     *     tags={"Users"},
     *     summary="User Reset Password",
     *     description="",
     *     operationId="UserResetPassword",
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *			    @OA\Property(property="company_id", description="COMPANY ID", type="integer", ),
     *			    @OA\Property(property="api_key", description="API Key", type="integer", ),
     *			    @OA\Property(property="reset_password_code", description="reset_password_code from email", type="integer", ),
     *     			@OA\Property(property="password", description="password, min lenght 6", type="string", ),
     *     			@OA\Property(property="password_confirmation", description="password confirmation", type="string", ),
     *              required={"company_id","api_key","reset_password_code","password","password_confirmation"}
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
    public function resetPassword(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));
        $validator = Validator::make($request->all(), [
            'reset_password_code' => 'required',
            'password'            => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }

        $reset_password_code = $request->get('reset_password_code');

        $active = new CoreUsersActivation();

        $activation = $active->getActivationByOTP($reset_password_code);

        if (empty($activation))
            return $this->throwError('Mã reset mật khẩu không hợp lệ', 400);

        $user = CoreUsers::find($activation->user_id);

        if (!$user) {
            return $this->throwError('Tài khoản không tồn tại', 500);
        }

        if ($user->status == CoreUsers::$status_banned)
            return $this->throwError('Tài khoản đã bị khóa!', 403);

        $password = Hash::make($request->get('password'));
        $user->password = $password;
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        $active->deleteOTPActivation($reset_password_code);

        if (!$token = auth('api')->login($user)) {
            return $this->throwError('Login failed!', 401);
        }

        $user->token = $token;
        $user->token_type = 'bearer';
        $user->token_expires_in = auth('api')->factory()->getTTL() * 60;

        return $this->returnResult($user);
    }

    /**
     * @OA\Get(
     *     path="/user/me",
     *     tags={"Users"},
     *     summary="Get user information",
     *     description="",
     *     operationId="UserGetInfo",
     *     @OA\Parameter(name="company_id", description="COMPANY ID", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(name="token", description="token from api login", required=true, in="query",
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
    public function me(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));
        $user = $this->getAuthenticatedUser();
        $user->token = $request->get('token');
        $user->token_type = 'bearer';
        $user->token_expires_in = auth('api')->factory()->getTTL() * 60;
        return $this->returnResult($user);
    }

    /**
     * @OA\Post(
     *     path="/user/logout",
     *     tags={"Users"},
     *     summary="User logout",
     *     description="",
     *     operationId="UserLogout",
     *     @OA\Parameter(name="company_id", description="COMPANY ID", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(name="token", description="token from api login", required=true, in="query",
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
    public function logout(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));
        $this->getAuthenticatedUser();
        auth('api')->logout();
        return $this->returnResult();
    }


    /**
     * @OA\Get(
     *     path="/user/deltetebyid",
     *     tags={"Users"},
     *     summary="Xoá user theo id user ",
     *     description="",
     *     operationId="DeleteById",
     *     @OA\Parameter(name="company_id", description="COMPANY ID", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter( name="userid", description="id of user ", required=true, in="query",
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

    public function deltetebyid(Request $request)
    {
        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $validator = Validator::make($request->all(), [
            'userid'  => 'required',
        ]);

        if ($validator->fails())
            return $this->throwError($validator->errors()->first(), 400);

        $userid = $request->get('userid');

        $user = CoreUsers::Where('id', $userid)->first();
        if (!empty($user)) {
            $user->delete();
            $user->destroy($userid);
        } else {
            return $this->throwError("tài khoản không tồn tại", 400);
        }
        return $this->returnResult($user);
    }

    /* Ca 22 - 12 -21 */


    public function loginFacebook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fb_access_token' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }

        $user_fb = [
            'id'     => 123,
            'name'   => 'Cui Bap',
            'email'  => '',
            'avatar' => '',
        ];

        try {
            $user_fb = Socialite::driver('facebook')->userFromToken($request->get('fb_access_token')); //object
        } catch (\Exception $e) {
            return $this->throwError('Đăng nhập bằng facebook thất bại! ' . $e->getMessage(), 401);
        }

        $user = CoreUsers::where(['facebook_id' => $user_fb->id])->first();

        if (!$user) {
            $email = null;
            if ($user_fb->email) {
                if (!CoreUsers::where('email', $user_fb->email)->first())
                    $email = $user_fb->email;
            }
            $user = CoreUsers::create([
                'facebook_id' => $user_fb->id,
                'fullname'    => $user_fb->name,
                'avatar'      => $user_fb->avatar,
                'email'       => $email,
                'status'      => CoreUsers::STATUS_UNREGISTERED
            ]);

            if (!$user)
                return $this->throwError('Cannot insert DB', 500);

            $user = CoreUsers::findOrFail($user->id);
        }

        if (!$token = auth('api')->login($user)) {
            return $this->throwError('Login failed!', 401);
        }
        $fcm_token = $request->get('fcm_token');
        $device_id = $request->get('device_id');
        $device_os = $request->get('device_os');
        $user->fcm_token = !empty($fcm_token) ? $fcm_token : $user->fcm_token;
        $user->device_id = !empty($device_id) ? $device_id : $user->device_id;
        $user->device_os = !empty($device_os) ? $device_os : $user->device_os;
        $user->save();

        $user->token = $token;
        $user->token_type = 'bearer';
        $user->token_expires_in = auth('api')->factory()->getTTL() * 60;

        return $this->returnResult($user);
    }

    public function loginGoogle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gg_access_token' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }

        $user_google = [
            'id'     => 123,
            'name'   => 'Cui Bap',
            'email'  => '',
            'avatar' => '',
        ];

        try {
            $user_google = Socialite::driver('google')->userFromToken($request->get('gg_access_token')); //object
        } catch (\Exception $e) {
            return $this->throwError('Đăng nhập bằng google thất bại!' . $e->getMessage(), 401);
        }

        $user = CoreUsers::where(['google_id' => $user_google->id])->first();

        if (!$user) {
            $email = null;
            if ($user_google->email) {
                if (!CoreUsers::where('email', $user_google->email)->first())
                    $email = $user_google->email;
            }
            $user = CoreUsers::create([
                'google_id' => $user_google->id,
                'fullname'  => $user_google->name,
                'avatar'    => $user_google->avatar,
                'email'     => $email,
                'status'    => CoreUsers::STATUS_UNREGISTERED
            ]);

            if (!$user)
                return $this->throwError('Cannot insert DB', 500);

            $user = CoreUsers::findOrFail($user->id);
        }

        if (!$token = auth('api')->login($user)) {
            return $this->throwError('Login failed!', 401);
        }

        $fcm_token = $request->get('fcm_token');
        $device_id = $request->get('device_id');
        $device_os = $request->get('device_os');
        $user->fcm_token = !empty($fcm_token) ? $fcm_token : $user->fcm_token;
        $user->device_id = !empty($device_id) ? $device_id : $user->device_id;
        $user->device_os = !empty($device_os) ? $device_os : $user->device_os;
        $user->save();

        $user->token = $token;
        $user->token_type = 'bearer';
        $user->token_expires_in = auth('api')->factory()->getTTL() * 60;

        return $this->returnResult($user);
    }

    public function refresh()
    {
        $this->getAuthenticatedUser();
        return $this->returnResult([
            'token'            => auth('api')->refresh(),
            'token_type'       => 'bearer',
            'token_expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
