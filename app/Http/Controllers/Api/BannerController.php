<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseAPIController;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends BaseAPIController
{
    /**
     * @OA\Get(
     *   path="/banner/getAll",
     *   tags={"Banner"},
     *   summary="Get all banner",
     *   description="",
     *   operationId="BannerGetAll",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *   @OA\Response(response="200", description="Success operation"),
     *   @OA\Response(response="500", description="Server error")
     * )
     */
    public function getAll(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $return = Banner::with(['image_app'])
            ->where('company_id', config('constants.company_id'))
            ->where('type', Banner::TYPE_MAIN)
            ->where('status', Banner::STATUS_SHOW);

        $return = $return->limit(5)->get();
        return $this->returnResult($return);
    }
}
