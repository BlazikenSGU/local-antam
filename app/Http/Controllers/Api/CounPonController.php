<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseAPIController;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CounPonController extends BaseAPIController
{
    /**
     * @OA\Get(
     *     path="/counpon",
     *     tags={"counpon"},
     *     summary="get all counpon",
     *     description="",
     *     @OA\Parameter(
     *         name="token",
     *         description="access token",
     *         required=true,
     *         in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         description="limit",
     *         required=false,
     *         in="query",
     *         @OA\Schema(type="integer",)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         description="page number",
     *         required=false,
     *         in="query",
     *         @OA\Schema(type="integer",)
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Server error"),
     * )
     */
    public function getCounpon(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        $params['user_id'] = $user->id;
        $params['pagin_path'] = $request->get('page') ?? 1;
        $params['limit'] = $request->get('limit') ?? 10;
        $counpon = DiscountCode::get_by_where($params);
        return $this->returnResult($counpon);
    }
}
