<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseAPIController;
use App\Http\Controllers\Controller;
use App\Mail\OTPEmail;
use App\Models\Location\Province;
use App\Models\CoreUsersActivation;
use App\Models\Files;
use App\Models\Location\District;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use Illuminate\Support\Facades\Http;

class ProvinceController extends Controller
{
    // use RegistersUsers;


    public function list(Request $request)
    {
        $provinces = Province::orderBy('name', 'asc')->get();
        return response()->json([
            'status' => true,
            'message' => 'List of provinces',
            'data' => $provinces
        ]);
    }

    public function districtList(Request $request)
    {
        try {
            $query = District::query();

            if ($request->filled('districtID')) {
                $query->where('DistrictID', $request->districtID);
            }

            $list = $query->orderBy('name_origin', 'asc')->get();


            if ($request->filled('districtID')) {
                return response()->json([

                    'total' => $list->count(),
                    'message' => 'List of districts',
                    'data' => [
                        'district' => $list,
                        'province' => Province::where('ProvinceID', $list->first()->ProvinceID)->first(),
                    ]
                ]);
            }

            return response()->json([
                'status' => true,
                'total' => $list->count(),
                'message' => 'List of districts',
                'data' => $list
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching district list',
                'error' => ' districID not found'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $item =  Province::find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy',
            ], 404);
        }

        $item->ProvinceID = $request->input('ProvinceID');
        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật thành công',
            'data' => $item->name
        ]);
    }

    public function updateGHNapi(Request $request)
    {

        $token = $request->input('token');
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Thiếu token'
            ], 400);
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => $token
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/province');


        if (!$response->ok()) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi gọi GHN API',
                'error' => $response->json()
            ], 500);
        }

        $data = $response->json();

        if (!isset($data['data'])) {
            return response()->json([
                'status' => false,
                'message' => 'Không có dữ liệu tỉnh/thành từ GHN'
            ], 422);
        }

        $ghnProvinces = $data['data'];
        $provinces = Province::all();

        foreach ($provinces as $province) {
            foreach ($ghnProvinces as $ghnItem) {
                if (mb_strtolower(trim($province->name)) === mb_strtolower(trim($ghnItem['ProvinceName']))) {
                    $province->ProvinceID = $ghnItem['ProvinceID'];
                    $province->save();
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật ProvinceID từ GHN thành công'
        ]);
    }

    public function updateDistrictAPI(Request $request)
    {
        $token = $request->input('token');
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Thiếu token'
            ], 400);
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => $token
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/district');


        if (!$response->ok()) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi gọi GHN API',
                'error' => $response->json()
            ], 500);
        }

        $data = $response->json();

        if (!isset($data['data'])) {
            return response()->json([
                'status' => false,
                'message' => 'Không có dữ liệu quận huyện từ GHN'
            ], 422);
        }

        $ghnDistricts = $data['data'];
        $districts  = District::all();

        foreach ($ghnDistricts as $ghnItem) {
            // Kiểm tra xem DistrictName này đã tồn tại trong DB chưa
            $exists = $districts->first(function ($district) use ($ghnItem) {
                return mb_strtolower(trim($district->name_origin)) === mb_strtolower(trim($ghnItem['DistrictName']));
            });

            if ($exists) {
                // Nếu tồn tại thì cập nhật
                $exists->DistrictID = $ghnItem['DistrictID'];
                $exists->ProvinceID = $ghnItem['ProvinceID'];
                $exists->save();
            } else {
                // Nếu chưa tồn tại thì tạo mới
                District::create([
                    'name_origin' => $ghnItem['DistrictName'],
                    'DistrictID' => $ghnItem['DistrictID'],
                    'ProvinceID' => $ghnItem['ProvinceID'],
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật DistrictID từ GHN thành công'
        ]);
    }
}
