<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseAPIController;
use App\Models\Location\Alley;
use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Street;
use App\Models\Location\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends BaseAPIController
{
    /**
     * @OA\Get(
     *     path="/location/province",
     *     tags={"location"},
     *     summary="Get province",
     *     description="",
     *     operationId="locationProvince",
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Server error"
     *     ),
     * )
     */

    public function province(Request $request)
    {
        $name = $request->get('name', null);

        $provinces = Province::select('id', 'name', 'location');
        if ($name)
            $provinces->where('name', 'like', "%{$name}%");

        $provinces = $provinces->orderBy('priority', 'ASC')->get();

        return $this->returnResult($provinces);
    }

    /**
     * @OA\Get(
     *     path="/location/district",
     *     tags={"location"},
     *     summary="Get district",
     *     description="",
     *     operationId="locationDistrict",
     *     @OA\Parameter(
     *         name="province_id",
     *         description="province_id",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *              type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Server error"
     *     ),
     * )
     */

    public function district(Request $request)
    {
        $province_id = $request->get('province_id', null);

        $name = $request->get('name', null);
        $district = District::select('id', 'name', 'location', 'province_id', 'type');

        if ($name)
            $district = $district->where('name', 'like', "%{$name}%");
        if ($province_id)
            $district = $district->where('province_id', $province_id);

        $district = $district->orderBy('priority', 'ASC')->get();

        return $this->returnResult($district);
    }

    /**
     * @OA\Get(
     *     path="/location/ward",
     *     tags={"location"},
     *     summary="Get ward",
     *     description="",
     *     operationId="locationWard",
     *     @OA\Parameter(
     *         name="district_id",
     *         description="district_id",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *              type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         description="name",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Server error"
     *     ),
     * )
     */

    public function ward(Request $request)
    {
        $district_id = $request->get('district_id', null);
        $name = $request->get('name', null);

        $ward = Ward::select('id', 'name', 'location', 'district_id', 'type')->orderBy('name', 'ASC');
        if ($name)
            $ward->where("name", "like", "%{$name}%");

        if ($district_id)
            $ward = $ward->where('district_id', $district_id);

        $ward = $ward->orderBy('priority', 'ASC')->get();

        return $this->returnResult($ward);
    }
}
