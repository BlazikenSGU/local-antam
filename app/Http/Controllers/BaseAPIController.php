<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class BaseAPIController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function returnResult($data = [])
    {
        return Response()->json([
            'status'  => true,
            'code'    => 200,
            'data'    => !empty($data) ? $data : new \stdClass(),
            'message' => '',
        ]);
    }


    public function checkCompany($company_id, $api_key)
    {
        $company = Company::where('id', $company_id)
            ->where('api_key', $api_key)
            ->first();

        if (!$company)
            return $this->throwError('Company & API Key Invalid', 400);

        if ($company_id != config('constants.company_id'))
            return $this->throwError('Company & API Key Invalid', 400);

        return false;
    }

    public static function throwError($errors = 'error', $code = 400)
    {
        header('Content-type: application/json');
        echo json_encode([
            'status' => false,
            'code'   => $code,
            'data'   =>  new \stdClass(),
            'message'  => $errors
        ]);
        exit;
    }

    /**
     * @OA\OpenApi(
     *     @OA\Info(
     *         version="1.0",
     *         title="ATai API Documentation",
     *         description="https://atai.org/api/v1.0",
     *         termsOfService="https://atai.org",
     *         @OA\Contact(
     *             email="hotro@atai.org"
     *         ),
     *     ),
     *     @OA\Server(
     *         description="Live host",
     *         url="https://atai.dev24h.net/api/v1.0"
     *     ),
     *     @OA\Server(
     *         description="Local host",
     *         url="http://atai.org/api/v1.0"
     *     ),
     *     @OA\ExternalDocumentation(
     *         description="Find out more about felibambi",
     *         url="https://atai.org/"
     *     )
     * )
     */

    public function getAuthenticatedUser($check_registered = true)
    {
        $request = request(['token']);

        if (empty($request['token'])) {
            return $this->throwError('Empty Token', 400);
        }

        try {
            $user = auth('api')->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return $this->throwError('Xin vui lòng đăng nhập lại!', 401);
        }

        if ($check_registered && $user->status == \App\Models\CoreUsers::STATUS_UNREGISTERED) {
            return $this->throwError('Xin vui lòng đăng nhập lại!', 401);
        }

        if ($user->status == \App\Models\CoreUsers::STATUS_BANNED) {
            return $this->throwError('Tài khoản đã bị khóa', 403);
        }
        return $user;
    }
}
