<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Cache;
use App\VNShipping\GHN;
use App\Classes\ActivationService;
use App\Classes\ResetPasswordService;
use App\Http\Controllers\BaseFrontendController;
use App\Models\Address;
use App\Models\Branch;
use App\Models\Banks;
use App\Models\CoreUsers;
use App\Models\CoreUsersActivation;
use App\Models\Files;
use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use App\Models\Orders;
use App\Models\SettingFee;
use App\Models\ProductStore;
use App\Models\YourBank;
use App\Models\DoiSoatUser;
use App\Models\DoiSoat;
use App\Models\StatusName;
use App\Utils\Common as Utils;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\GHNHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Exports\DoisoatUserExport;
use App\Exports\OrdersExport;
use App\Exports\DoisoatExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends BaseFrontendController
{
    protected $_data = [];



    // use RegistersUsers;

    public function __construct(ActivationService $activationService, ResetPasswordService $resetPasswordService)
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('backend.login');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $userId = Auth()->guard('backend')->user()->id;

        $statusDangXuLy = ['picked', 'storing', 'transporting', 'sorting', 'delivering', 'money_collect_delivering', 'delivery_fail', 'return', 'return_transporting', 'return_sorting', 'returning', 'return_fail'];
        $statusHoanTat = ['delivered'];
        $statusHoanHang = ['returned'];
        $statusGiaoLai = ['waiting_to_return'];
        $statusHoanTatHoangHang = ['delivered', 'returned'];
        $statusLuuKho = ['picked', 'storing', 'transporting', 'sorting', 'delivering', 'money_collect_delivering', 'delivery_fail', 'waiting_to_return'];

        $giaolai = DoiSoat::whereIn('statusName', $statusGiaoLai)
            ->where('IDUser', $userId)
            ->count();
        $hoanhangthanhcong = DoiSoat::whereIn('statusName', $statusHoanHang)
            ->where('type', '<>', 2)
            ->where('IDUser', $userId)->count();
        $giaohangthanhcong = DoiSoat::whereIn('statusName', $statusHoanTat)
            ->where('type', '<>', 2)
            ->where('IDUser', $userId)->count();
        $hoantatchuachuyencod  = Doisoat::where('statusName', 'delivered')
            ->where('type', '<>', 2)
            ->where('IDUser', $userId)->count();
        $dangXuly = Orders::whereIn('statusName', $statusDangXuLy)
            ->where('user_id', $userId)
            ->count();

        $dongtien =  DoiSoat::whereIn('statusName', $statusHoanTat)->where('IDUser', $userId);
        $cod = $dongtien->where('type', '<>', 2)->sum('CODAmount'); // Tien thu hộ, tiền lưu kho
        $cod_failed = DoiSoat::whereIn('statusName', $statusHoanTatHoangHang)->where('IDUser', $userId)
            ->where('tinhtrangthutienGTB', '=', 1)->where('type', '<>', 2)->sum('cod_failed_amount'); // giao that bai thu tien
        $main_service =  DoiSoat::whereIn('statusName', ['delivered', 'returned'])->where('IDUser', $userId)
            ->where('type', '<>', 2)->sum('tongphi');
        $no_ton = 0;
        $checkno_ton = DoiSoatUser::where('user_id', $userId)->orderBy('id', 'desc')->first();
        if ($checkno_ton and $checkno_ton->thucnhan < 0) {
            $no_ton = $checkno_ton->thucnhan;
        }
        $a = $cod + $cod_failed - $main_service + $no_ton;

        if ($a < 0) {
            $sum_total = 0;
        } else {
            $sum_total = $a;
        }
        $luuKho = 0;
        $Orders = Orders::whereIn('statusName', $statusLuuKho)->where('user_id', $userId)->get();
        foreach ($Orders as $Order) {
            $amount = (int) str_replace(['.', ','], '', $Order->cod_amount);
            $luuKho += $amount;
        }

        // $check_sell_order = 0;
        // $shopIds = Auth::user()->shopId;

        // if (!is_array($shopIds)) {
        //     $shopIds = explode(',', $shopIds); // ép kiểu nếu là chuỗi
        // }

        // foreach ($shopIds as $shopId) {
        //     $branch = Branch::where('shopId', $shopId)->first();
        //     if ($branch && $branch->type == 2) {
        //         $check_sell_order = 1;
        //         break;
        //     }
        // }

        $this->_data['codlive'] = [
            'sum_cod' => $cod,
            'sum_cod_failed' => $cod_failed,
            'main_service' => $main_service,
            'no_ton' => $no_ton,
            'sum_total' => $sum_total,
            'luuKho' => $luuKho,
            'giaohangthanhcong' => $giaohangthanhcong,
            'hoantatchuachuyencod' => $hoantatchuachuyencod,
            'hoanhangthanhcong' => $hoanhangthanhcong,
            'giaolai' => $giaolai,
            'dangXuly' => $dangXuly,
        ];

        return view('frontend.user.index', $this->_data);
    }

    public function checkShopId($weight_order, $user_id = null)
    {
        if (!$weight_order) {
            return '5721813';
        }

        try {
            $branch = Branch::where('from_weight', '<=', $weight_order)
                ->where('to_weight', '>=', $weight_order)
                ->where('use_create_order', 1)
                ->first();

            if ($branch) {
                $shopId = $branch->shopId;
                $list  = CoreUsers::find($user_id);

                $shopIds = is_array($list->shopId)
                    ? $list->shopId
                    : json_decode($list->shopId, true);

                if (is_array($shopIds) && in_array($shopId, $shopIds)) {
                    return $shopId;
                } else {

                    $branch = Branch::whereIn('shopId', $shopIds)
                        ->orderBy('to_weight', 'desc')
                        ->first();

                    return $branch ? $branch->shopId : '5721813';
                }
            }
        } catch (\Exception $e) {
            Log::error('Lỗi xác định shopId: ' . $e->getMessage());
            return '5721813';
        }
    }


    public function orders(Request $request)
    {
        try {
            $user_id = Auth::id();

            $cacheKey = 'orders_user_' . $user_id . '_' . md5(json_encode($request->all()));

            // Lấy danh sách shopId hợp lệ
            $itemflag = [];
            $branchs = Auth()->user()->shopId;
            $branchs = is_array($branchs) ? $branchs : json_decode($branchs, true);

            if (is_array($branchs) && count($branchs)) {
                $itemflag = Branch::whereIn('shopId', $branchs)
                    ->where('type', 2)
                    ->pluck('shopId')
                    ->toArray();
            }

            //check san pham va dia chi
            $product = ProductStore::where('user_id', $user_id)->first();
            $address = Address::where('user_id', $user_id)->first();
            if (!$product || !$address) {
                return redirect()->route('user.mystore')->with('warning', 'Vui lòng thêm thông tin sản phẩm và cửa hàng trước khi tạo đơn hàng!');
            }

            //check bank
            $checkBank = CoreUsers::where('id', $user_id)->first();
            if ($checkBank->bank_account == null || $checkBank->bank_number == null || $checkBank->bank_name == null) {
                return redirect()->route('user.profile')->with('error', 'Vui lòng thêm tài khoản ngân hàng trước khi tạo đơn hàng!');
            }

            $query = Orders::with('doisoat')
                ->where('user_id', $user_id)
                ->whereNotIn('shop_id', $itemflag);

            // Thêm điều kiện tìm kiếm nếu có keyword
            if ($request->filled('keyword')) {
                $keyword = trim($request->keyword);
                $query->where(function ($q) use ($keyword) {
                    $q->where('order_code', 'like', '%' . $keyword . '%')
                        ->orWhere('to_name', 'like', '%' . $keyword . '%')
                        ->orWhere('to_phone', '=', $keyword);
                });
            }

            // Lọc theo trạng thái
            if ($request->filled('status')) {
                $status = $request->status;
                switch ($status) {
                    case '1':
                        $query->whereIn('statusName', Orders::STATUS_GHN_1);
                        break;
                    case '2':
                        $query->whereIn('statusName', Orders::STATUS_GHN_2);
                        break;
                    case '3':
                        $query->whereIn('statusName', Orders::STATUS_GHN_3);
                        break;
                    case '4':
                        $query->whereIn('statusName', Orders::STATUS_GHN_4);
                        break;
                    case '5':
                        $query->whereIn('statusName', Orders::STATUS_GHN_5);
                        break;
                    case '6':
                        $query->whereIn('statusName', Orders::STATUS_GHN_6);
                        break;
                    case '7':
                        $query->whereIn('statusName', Orders::STATUS_GHN_7);
                        break;
                    case '8':
                        $query->whereIn('statusName', Orders::STATUS_GHN_8);
                        break;
                    case '9':
                        $query->where('statusName', Orders::status_ghn18_1)
                            ->whereHas('doisoat', function ($q) {
                                $q->where('type', 2);
                            });
                        break;
                    case '10':
                        $query->where('statusName', Orders::status_ghn18_1)
                            ->whereHas('doisoat', function ($q) {
                                $q->where('type', '<>', 2);
                            });
                        break;
                    case '11':
                        $query->where('statusName', Orders::status_ghn19_1)
                            ->whereHas('doisoat', function ($q) {
                                $q->where('type', 2);
                            });
                        break;
                    case '12':
                        $query->where('statusName', Orders::status_ghn19_1)
                            ->whereHas('doisoat', function ($q) {
                                $q->where('type', '<>', 2);
                            });
                        break;
                    case '13':
                        $query->where('statusName', Orders::status_ghn9_1);
                        break;
                    case '14':
                        $query->where('statusName', Orders::status_ghn11_1);
                        break;
                    case '15':
                        $query->whereIn('statusName', Orders::STATUS_GHN_3_2);
                        break;
                }
            }

            // fitler ngay thang
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $from = date('Y-m-d 00:00:00', strtotime($request->from_date));
                $to = date('Y-m-d 23:59:59', strtotime($request->to_date));
                $query->whereBetween('created_at', [$from, $to]);
            }

            // $orders = $query->orderBy('created_at', 'desc')->paginate(20);
            $orders = Cache::remember($cacheKey . '_orders', 10, function () use ($query) {
                return $query->orderBy('created_at', 'desc')->paginate(20);
            });

            $statusMap = StatusName::all()->keyBy('key');

            foreach ($orders as $item) {
                $item->status_name = $statusMap[$item->statusName]->name ?? null;
            }

            // Lấy tất cả đơn hàng để đếm số lượng theo trạng thái
            // $count_status = array_fill(1, 15, 0);

            // $allOrders = Orders::where('user_id', $user_id)->whereNotIn('shop_id', $itemflag)->get();

            // foreach ($allOrders as $item) {
            //     $status = $item->statusName;
            //     $doisoat = $item->doisoat;

            //     if (in_array($status, Orders::STATUS_GHN_1)) {
            //         $count_status[1]++;
            //     } elseif (in_array($status, Orders::STATUS_GHN_2)) {
            //         $count_status[2]++;
            //     } elseif (in_array($status, Orders::STATUS_GHN_3_2)) {
            //         $count_status[15]++;
            //     } elseif ($status == Orders::status_ghn9_1) {
            //         $count_status[13]++;
            //     } elseif ($status == Orders::status_ghn11_1) {
            //         $count_status[14]++;
            //     } elseif (in_array($status, Orders::STATUS_GHN_4)) {
            //         $count_status[4]++;
            //     } elseif (in_array($status, Orders::STATUS_GHN_5)) {
            //         $count_status[5]++;
            //     } elseif (in_array($status, Orders::STATUS_GHN_6)) {
            //         if ($status == Orders::status_ghn18_1) {
            //             if ($doisoat && $doisoat->type == 2) {
            //                 $count_status[9]++;
            //             } elseif ($doisoat && $doisoat->type != 2) {
            //                 $count_status[10]++;
            //             } else {
            //                 $count_status[6]++;
            //             }
            //         } elseif ($status == Orders::status_ghn19_1) {
            //             if ($doisoat && $doisoat->type == 2) {
            //                 $count_status[11]++;
            //             } elseif ($doisoat && $doisoat->type != 2) {
            //                 $count_status[12]++;
            //             }
            //         } else {
            //             $count_status[6]++;
            //         }
            //     } elseif (in_array($status, Orders::STATUS_GHN_7)) {
            //         $count_status[7]++;
            //     } elseif (in_array($status, Orders::STATUS_GHN_8)) {
            //         $count_status[8]++;
            //     }
            // }
            $count_status = Cache::remember($cacheKey . '_count_status', 10, function () use ($user_id, $itemflag) {
                $count = array_fill(1, 15, 0);
                $allOrders = Orders::with('doisoat')->where('user_id', $user_id)->whereNotIn('shop_id', $itemflag)->get();

                foreach ($allOrders as $item) {
                    $status = $item->statusName;
                    $doisoat = $item->doisoat;

                    if (in_array($status, Orders::STATUS_GHN_1)) {
                        $count[1]++;
                    } elseif (in_array($status, Orders::STATUS_GHN_2)) {
                        $count[2]++;
                    } elseif (in_array($status, Orders::STATUS_GHN_3_2)) {
                        $count[15]++;
                    } elseif ($status == Orders::status_ghn9_1) {
                        $count[13]++;
                    } elseif ($status == Orders::status_ghn11_1) {
                        $count[14]++;
                    } elseif (in_array($status, Orders::STATUS_GHN_4)) {
                        $count[4]++;
                    } elseif (in_array($status, Orders::STATUS_GHN_5)) {
                        $count[5]++;
                    } elseif (in_array($status, Orders::STATUS_GHN_6)) {
                        if ($status == Orders::status_ghn18_1) {
                            if ($doisoat && $doisoat->type == 2) {
                                $count[9]++;
                            } elseif ($doisoat && $doisoat->type != 2) {
                                $count[10]++;
                            } else {
                                $count[6]++;
                            }
                        } elseif ($status == Orders::status_ghn19_1) {
                            if ($doisoat && $doisoat->type == 2) {
                                $count[11]++;
                            } elseif ($doisoat && $doisoat->type != 2) {
                                $count[12]++;
                            }
                        } else {
                            $count[6]++;
                        }
                    } elseif (in_array($status, Orders::STATUS_GHN_7)) {
                        $count[7]++;
                    } elseif (in_array($status, Orders::STATUS_GHN_8)) {
                        $count[8]++;
                    }
                }

                return $count;
            });

            $branch = Branch::orderBy('created_at', 'desc')->get();

            return view('frontend.order.index', compact('orders', 'branch', 'count_status'));
        } catch (\Exception $e) {
            Log::error('Order Search Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Có lỗi xảy ra khi tìm kiếm đơn hàng');
        }
    }

    public function addOrder(Request $request)
    {

        $user_id  = Auth::check() ? Auth::user()->id : null;

        $product = ProductStore::where('user_id', $user_id)->orderBy('created_at')->get();

        $address = Address::where('user_id', $user_id)->orderBy('created_at')->get();

        $order = new Orders();

        return view('frontend.order.add', compact('order', 'address', 'product'));
    }

    public function storeOrder(Request $request)
    {
        try {

            $user_id = Auth::check() ? Auth::user()->id : null;
            $apiUrl = GHN::getApiUrl();
            $apiToken = GHN::getToken();

            if ($user_id != 555) {

                if ($request->weight_order >= 20000 && $request->pickup_area_hidden == null) {
                    return redirect()->route('user.mystore.edit', $request->from_id)->withInput()->with('error', 'Vui lòng bổ sung khu vực lấy hàng cho cửa hàng của bạn.');
                }

                if ($request->weight_order >= 20000) {
                    $shopId = $request->pickup_area_hidden;
                    $check_servicetypeid = 5;
                } else {
                    $shopId = $this->checkShopId((int)$request->weight_order, $user_id);
                    $check_servicetypeid = 2;
                }
            } else {
                if ($request->weight_order >= 20000) {
                    $shopId = $this->checkShopId((int)$request->weight_order, $user_id);
                    $check_servicetypeid = 5;
                } else {
                    $shopId = $this->checkShopId((int)$request->weight_order, $user_id);
                    $check_servicetypeid = 2;
                }
            }

            if ($shopId) {

                $check = $this->checkExistsShopid($shopId, $user_id);

                if ($check != $shopId) {
                    return redirect()->back()->withInput()->with('error', 'Shop chưa được set cân nặng này. Vui lòng Liên hệ Admin.');
                }

                $fee = SettingFee::where('user_id', $user_id)->where('shop_id', $shopId)->first();
            }

            if ($fee->cost == null || $fee->cost == 0) {
                return redirect()->back()->withInput()->with('error', 'Vui lòng Liên hệ Admin.');
            } else {
                $fee_shopId = $fee->cost;
            }

            $cod_amount = (int)str_replace([',', '.'], '', $request->cod_amount);

            if ($request->filled('order_code_custom')) {
                $checkResponse = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Token' => $apiToken
                ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail-by-client-code', [
                    'client_order_code' => $request->order_code_custom
                ]);

                $checkData = $checkResponse->json();

                if ($checkResponse->successful() && isset($checkData['data']) && !empty($checkData['data'])) {
                    return redirect()->back()->with('error', 'Mã đơn hàng ' . $request->order_code_custom . ' đã tồn tại.');
                }
            }

            $items = [];

            if (!empty($request->name) && is_array($request->name)) {
                foreach ($request->name as $key => $name) {
                    $items[] = [
                        "name" => (string) $name,
                        "code" => (string)$request->code[$key],
                        "quantity" => (int) ($request->quantity[$key] ?? 1),
                        "weight" => (int) ($request->weight_item[$key] ?? 0),
                    ];
                }
            }

            //kiểm tra phương thưc thanh toán để tính toán phí
            $check_payment_method = $request->payment_method;

            if ($check_payment_method) {
                if ($check_payment_method == 2) {
                    $total_COD = $cod_amount + $fee_shopId;
                } elseif ($check_payment_method == 1) {
                    $total_COD = $cod_amount;
                }
            }

            $payload = [
                "payment_type_id" => (int)$check_payment_method,
                "note" => $request->note,
                "required_note" => $request->required_note,
                "service_type_id" => $check_servicetypeid,
                "service_id" => 53321,
                "shopId" => $shopId,

                "from_name" => $request->from_name,
                "from_phone" => $request->from_phone,
                "from_address" => $request->from_address,
                "from_ward_name" => $request->from_ward_name,
                "from_district_name" => $request->from_district_name,
                "from_province_name" => $request->from_province_name,
                "from_ward_code" => $request->from_ward_code,
                "from_district_id" => $request->from_district_id,

                "to_name" => $request->to_name,
                "to_phone" => $request->to_phone,
                "to_address" => $request->to_address,
                "to_ward_name" => $request->ward_name,
                "to_district_name" => $request->district_name,
                "to_province_name" => $request->province_name,
                "to_ward_code" => $request->to_ward_code,
                "to_district_id" => $request->to_district_id,

                "weight" => (int)$request->weight_order,
                "length" => (int)$request->length,
                "width" => (int)$request->width,
                "height" => (int)$request->height,
                "cod_amount" => $total_COD,
                "cod_failed_amount" => (int)str_replace([',', '.'], '', $request->cod_failed_amount),
                "insurance_value" => (int)str_replace([',', '.'], '', $request->insurance_value),

                "client_order_code" => $request->order_code_custom,
                "content" => "Đơn hàng GHN",
                "pick_station_id" => 0,
                "items" => $items
            ];

            if ($request->filled('return_phone')) {
                $payload["return_name"] = $request->return_name;
                $payload["return_phone"] = $request->return_phone;
                $payload["return_address"] = $request->return_address;
                $payload["return_district_id"] = (int)$request->return_district_id ?? null;
                $payload["return_ward_code"] = $request->return_ward_code ?? null;
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $apiToken,
                'ShopId' => $shopId
            ])->post($apiUrl, $payload);

            if ($response->failed()) {
                $errorResponse = json_decode($response->body(), true);
                $errorMessage = isset($errorResponse['message']) ? $errorResponse['message'] : 'Lỗi không xác định';
                Log::error('API Error:', ['error' => $errorResponse]);
                return redirect()->back()->withInput()->with('error', $errorMessage);
            }

            $responseData = $response->json();

            $order = Orders::create([
                'company_id' => 0,
                'shop_id' => $shopId ?? '',
                'user_id' => $user_id,
                'order_code' => $responseData['data']['order_code'],
                'fullname' => $request->from_name ?? '',
                'phone' => $request->from_phone ?? '',
                'email' => $request->email ?? '',
                'address' => $request->from_address,
                'district_id' => $request->from_district_id,
                'ward_id' => $request->from_ward_code,
                'province_id' => $request->from_province_id ?? '',
                'note' => $request->note,
                'required_note' => $request->required_note,
                'to_name' => $request->to_name ?? '',
                'to_phone' => $request->to_phone ?? '',
                'to_address' => $request->to_address ?? '',
                'to_ward_name' => $request->ward_name ?? '',
                'to_district_name' => $request->district_name ?? '',
                'to_province_name' => $request->province_name ?? '',
                'to_province' => $request->province_id ?? '',
                'to_district' => $request->district_id ?? '',
                'to_ward' => $request->ward_id ?? '',
                'cod_amount' => $total_COD,
                'weight' => (int)$request->weight_order,
                'status' => $request->status ?? 'pending',
                'status_payment' => $request->status_payment ?? 'pending',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'caGiaohang' => $request->caGiaohang ?? '',
                'length' => (int)$request->length,
                'width' => (int)$request->width,
                'height' => (int)$request->height,
                'payment_method' => (int)$request->payment_method,
                'payment_fee' => $request->payment_fee ?? 0,
                'total_fee' => $request->total_fee,
                'statusName' => $request->statusName ?? 'create_order',
                'cod_failed_amount' => (int)str_replace([',', '.'], '', $request->cod_failed_amount),
                'product_type' => $request->product_type,
                'product_type_cost' => $request->product_type_cost,
                'insurance_value' => (int)str_replace([',', '.'], '', $request->insurance_value),
                'total_cost' => $request->total_cost,
                'fee_shopId' => $fee_shopId ?: 0,
                'order_code_custom' => $request->order_code_custom,
                'cod_collect_date' => null,
                'cod_transfer_date' => null,
                'finish_date' => null,
                'cod_failed_collect_date' => null,
                'paid_date' => null,
                'main_service' => $responseData['data']['fee']['main_service'],
                'insurance' => $request->insurance,
                'return_address' => $request->return_address ?? '',
                'return_province' => $request->return_province ?? '',
                'return_province_name' => $request->return_province_name ?? '',
                'return_district' => $request->return_district ?? '',
                'return_district_name' => $request->return_district_name ?? '',
                'return_ward' => $request->return_ward ?? '',
                'return_ward_name' => $request->return_ward_name ?? '',
                'return_phone' => $request->return_phone ?? '',
                'province_name' => $request->from_province_name ?? '',
                'district_name' => $request->from_district_name ?? '',
                'ward_name' => $request->from_ward_name ?? '',
                'ConvertedWeight' => null,
                'R2S' => null,
                'R2S_fee' => null,
                'insurance_fee' => $responseData['data']['fee']['insurance'] ?? 0,
                'Return_k' => null,
                'ReturnAgain' => null,
                'PartialReturnCode' => null,
                'phi_gh1p' => null,
                'items' => $items,
            ]);

            $this->resetCache($request);

            return redirect('user/orders')->with('successandopenOrder', $responseData['data']['order_code']);
        } catch (\Exception $e) {

            Log::error('Lỗi tạo đơn hàng: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->withInput()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    public function resetCache(Request $request)
    {
        $user_id = Auth::check() ? Auth::user()->id : null;
        $cacheKey = 'orders_user_' . $user_id . '_' . md5(json_encode($request->all()));
        Cache::forget($cacheKey . '_orders');
        Cache::forget($cacheKey . '_count_status');
    }

    public function checkUser(Request $request)
    {
        $user_id = Auth::check() ? Auth::user()->id : null;

        $user = Orders::where('user_id', $user_id)->where('to_phone', $request->phone)->latest()->first();


        if ($user) {
            return response()->json([
                'success' => true,
                'user' => [
                    'name' => $user->to_name,
                    'address' => $user->to_address,
                    'province_id' => $user->to_province,
                    'province_name' => $user->to_province_name,
                    'district_id' => $user->to_district,
                    'district_name' => $user->to_district_name,
                    'ward_id' => $user->to_ward,
                    'ward_name' => $user->to_ward_name,
                ],
            ]);
        }

        return response()->json(['success' => false]);
    }

    public function getDistrict(Request $request)
    {

        $apiToken = GHN::getToken();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => $apiToken
        ])->post('https://online-gateway.ghn.vn/shiip/public-api/master-data/district', [
            'province_id' => $request->province_id
        ]);

        return response()->json($response->json());
    }

    public function getWard(Request $request)
    {

        $apiToken = GHN::getToken();

        $districtId = (int) $request->district_id;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => $apiToken
        ])->post('https://online-gateway.ghn.vn/shiip/public-api/master-data/ward', [
            'district_id' =>  $districtId
        ]);

        return response()->json($response->json());
    }


    public function myStore(Request $request)
    {
        $user_id  = Auth::check() ? Auth::user()->id : null;

        $product = ProductStore::where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(20);

        $address = Address::where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(20);

        $branch = Branch::orderBy('created_at', 'desc')->get();

        return view('frontend.user.address', compact('address', 'product', 'branch'));
    }

    public function getProduct(Request $request)
    {

        $user_id  = Auth::check() ? Auth::user()->id : null;

        $keyword = $request->input('keyword');

        $query = ProductStore::where('user_id', $user_id);

        if ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        $products = $query->select('id', 'name')->get();

        return response()->json($products);
    }

    public function getProductById($id)
    {
        $product = ProductStore::find($id);
        return view('frontend.product.viewproduct', compact('product'));
    }

    public function editProduct($id)
    {
        $product = ProductStore::find($id);
        return view('frontend.product.editproduct', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = ProductStore::find($id);
        $product->update($request->all());
        return redirect('user/mystore')->with('success', 'Sản phẩm đã được cập nhật!');
    }

    public function addProduct()
    {
        return view('frontend.product.addproduct');
    }

    public function storeProduct(Request $request)
    {

        $message = [
            'user_id.required' => 'Vui lòng nhập user_id',
            'name.required' => 'Vui lòng nhập tên sản phẩm',
            'product_code.required' => 'Vui lòng nhập mã sản phẩm',
            'product_code.unique' => 'Mã sản phẩm đã tồn tại',
            'amount.required' => 'Vui lòng nhập số lượng',
            'amount.min' => 'Tối thiểu là 1 gram',
            'amount.integer' => 'Khối lượng phải là số lớn hơn 0',
            'status.required' => 'Vui lòng chọn trạng thái',
        ];

        $validated = $request->validate([
            'user_id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'product_code' => ['required', 'string', 'max:50', 'unique:products,product_code'],
            'amount' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'integer', 'in:0,1'],
        ], $message);

        $user_id = Auth::check() ? Auth::user()->id : null;

        $productNameCheck = ProductStore::whereRaw('LOWER(name) = ?', [strtolower($validated['name'])])
            ->where('user_id', $user_id)->first();
        if ($productNameCheck) {
            return redirect('user/mystore')->with('error', 'Tên sản phẩm đã tồn tại!');
        }

        $product = ProductStore::create($validated);

        if ($product) {
            return redirect('user/mystore')->with('success', 'Sản phẩm đã được thêm!');
        } else {
            return redirect('user/mystore')->with('errorr', 'loi');
        }
    }

    public function addStore(Request $request)
    {
        $branch = Branch::get();
        return view('frontend.user.addstore', compact('branch'));
    }

    public function storeStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'ward_id' => 'required|string',
            'street_name' => 'required|string|max:255',
            'required_note' => 'nullable|string',
            'payment_type' => 'nullable|integer',
            'money_fail' => 'nullable|integer',
            'transport_unit' => 'nullable|integer',
            'note' => 'required|string',
            'sumshiptocod' => 'nullable|string',
            'pickup_area' => 'required|integer',
        ], [
            'name.required' => 'Vui lòng nhập tên shop',
            'name.max' => 'Tên shop không được vượt quá 255 ký tự',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.numeric' => 'Số điện thoại phải là số',
            'province_id.required' => 'Vui lòng chọn tỉnh/thành phố',
            'province_id.integer' => 'Tỉnh/thành phố không hợp lệ',
            'district_id.required' => 'Vui lòng chọn quận/huyện',
            'district_id.integer' => 'Quận/huyện không hợp lệ',
            'ward_id.required' => 'Vui lòng chọn xã/phường',
            'street_name.required' => 'Vui lòng nhập tên đường',
            'street_name.max' => 'Tên đường không được vượt quá 255 ký tự',
            'payment_type.integer' => 'Bên thanh toán không hợp lệ',
            'transport_unit.integer' => 'Đơn vị vận chuyển không hợp lệ',
            'note.required' => 'Vui lòng nhập ghi chú',
            'pickup_area.required' => 'Vui lòng chọn khu vực lấy hàng',
        ]);

        try {
            $address = new Address();
            $address->company_id = 6;
            $address->user_id = $request->user_id;
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->province_id = $request->province_id;
            $address->district_id = $request->district_id;
            $address->ward_id = $request->ward_id;
            $address->street_name = $request->street_name;
            $address->province_name = $request->province_name;
            $address->district_name = $request->district_name;
            $address->ward_name = $request->ward_name;
            $address->required_note = $request->required_note ?? 'KHONGCHOXEMHANG';
            $address->payment_type = 1;
            $address->transport_unit = $request->transport_unit ?? 1;
            $address->note = $request->note;
            $address->money_fail = $request->money_fail ?? 0;
            $address->sumshiptocod = $request->has('sumshiptocod') ? 1 : 0;
            $address->pickup_area = $request->pickup_area;
            $address->save();

            $addressCount = Address::where('user_id', $request->user_id)->count();

            if ($addressCount == 1) {
                $address->update([
                    'is_default' => 1
                ]);

                return redirect()->route('user.mystore')->with('success', 'Đã tạo cửa hàng và set làm mặc định');
            }

            return redirect('user/mystore')->with('success', 'Thêm mới cửa hàng thành công!');
        } catch (\Exception $e) {

            return back()->with('error', 'Có lỗi xảy ra khi thêm cửa hàng. Vui lòng thử lại!');
        }
    }

    public function setDefault(Request $request)
    {
        try {
            // Lấy user hiện tại
            $user = Auth::user();

            // Reset tất cả địa chỉ về không mặc định
            Address::where('user_id', $user->id)
                ->update(['is_default' => 0]);

            // Set địa chỉ được chọn thành mặc định
            Address::where('id', $request->address_id)
                ->where('user_id', $user->id)
                ->update(['is_default' => 1]);

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function calculateFee(Request $request)
    {
        $user_id = Auth::check() ? Auth::user()->id : null;

        $apiToken = GHN::getToken();

        if ($user_id != 555) {

            if ($request->weight_order >= 20000) {
                $shopId = $request->pickup_area_hidden;
                $check_servicetypeid = 5;
                $weight = (int) $request->weight_order;
            } else {
                $shopId = $this->checkShopId((int)$request->weight_order, $user_id);
                $check_servicetypeid = 2;
            }
        } else {
            if ($request->weight_order >= 20000) {
                $shopId = $this->checkShopId((int)$request->weight_order, $user_id);
                // $check_servicetypeid = 5 ;
                $check_servicetypeid = Branch::where('shopId', $shopId)->value('service_type_id');
                $weight = (int) $request->weight_order;
            } else {
                $shopId = $this->checkShopId((int)$request->weight_order, $user_id);
                $check_servicetypeid = Branch::where('shopId', $shopId)->value('service_type_id');

                // $check_servicetypeid = 2;
            }
        }

        if ($shopId) {
            $getCostUser = SettingFee::where('user_id', $user_id)->where('shop_id', $shopId)->first();
            $additionalCost = $getCostUser ? $getCostUser->cost : 0;

            $token_shopId = Branch::where('shopId', $shopId)->value('token');
        }


        try {
            Log::info('Calculate Fee Request:', $request->all());

            // Tính insurance_value từ request
            $insuranceValue = (int) str_replace([',', '.'], '', $request->insurance_value);

            $payload = [
                'from_district_id' => (int) $request->from_district_id,
                'from_ward_code' => (string) $request->from_ward_code,
                'service_type_id' => $check_servicetypeid ?: 2,
                'to_district_id' => (int) $request->to_district_id,
                'to_ward_code' => (string) $request->to_ward_code,
                'height' => (int) $request->height,
                'length' => (int) $request->length,
                'weight' => (int) $request->weight_order,
                'width' => (int) $request->width,
                'insurance_value' => $insuranceValue,
                'coupon' => null,
                "items" => [
                    [
                        "name" => "test1",
                        "code" => "test1",
                        "quantity"  => 1,
                        "length"  => 10,
                        "width"  => 10,
                        "height"  => 10,
                        "weight" => $request->weight_order >= 20000 ? $weight : 10,
                    ]
                ]
            ];

            Log::info('GHN API Payload:', [$payload, $shopId, $token_shopId]);

            $feeResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $token_shopId ?: $apiToken,
                'ShopId' => $shopId
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee', $payload);

            Log::info('GHN API Response:', $feeResponse->json());

            if ($feeResponse->successful()) {
                $feeData = $feeResponse->json();
                if (isset($feeData['data'])) {
                    // Tính insurance_fee dựa trên insurance_value nếu API không trả về
                    $insurance_fee = $feeData['data']['insurance_fee'] ?? round($insuranceValue * 0.005);

                    return response()->json([
                        'success' => true,
                        'service_fee' => $feeData['data']['service_fee'],
                        'insurance_fee' => $insurance_fee,
                        'fee_warning' => $additionalCost,
                        'service_type_id' => $check_servicetypeid ?: '',
                        'shopId' => $shopId,
                    ]);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Không thể tính phí: ' . ($feeResponse->json()['message'] ?? 'Unknown error')
            ]);
        } catch (\Exception $e) {
            Log::error('Calculate Fee Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
    }

    public function viewStore($id)
    {
        $user_id  = Auth::check() ? Auth::user()->id : null;
        $address = Address::find($id);
        $branch = Branch::orderBy('created_at', 'desc')->get();
        return view('frontend.user.viewstore', compact('address', 'branch'));
    }

    public function editStore($id)
    {
        $user_id  = Auth::check() ? Auth::user()->id : null;
        $address = Address::find($id);
        $branch = Branch::orderBy('created_at', 'desc')->get();

        return view('frontend.user.editstore', compact('address', 'branch'));
    }

    public function updateStore(Request $request, $id)
    {
        $address = Address::find($id);

        $request->validate([
            'pickup_area' => 'required|integer',
        ], [
            'pickup_area.required' => 'Vui lòng chọn khu vực lấy hàng',
        ]);


        $address->update($request->all());
        return redirect()->route('user.mystore')->with('success', 'Cập nhật cửa hàng thành công!');
    }

    public function viewOrder($id)
    {

        $user_id  = Auth::check() ? Auth::user()->id : null;
        $order = Orders::find($id);
        $product = ProductStore::where('user_id', $user_id)->orderBy('created_at')->get();
        $address = Address::where('user_id', $order->user_id)->get();
        $fee = SettingFee::where('user_id', $user_id)->where('shop_id', $order->shop_id)->first();

        return view('frontend.order.view', compact('order', 'address', 'product', 'fee'));
    }

    public function editOrder($id)
    {

        try {
            $user_id  = Auth::check() ? Auth::user()->id : null;
            $order = Orders::find($id);


            if (!$order) {
                return redirect()->back()->with('error', 'Không tìm thấy đơn hàng');
            }

            $doisoat = DoiSoat::where('OrderCode', $order->order_code)->value('weight');

            $apiToken = GHN::getToken();
            $shopId = $order->shop_id;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $apiToken,
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail', [
                'order_code' => $order->order_code
            ]);

            if (!$response->successful()) {
                Log::error('GHN API Error', [
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body(),
                ]);
            }

            $ghnData = $response->json();

            if ($ghnData['code'] != 200) {
                Log::warning('GHN API Response Error:', $ghnData);
            }

            $items = $ghnData['data']['items'] ?? [];
            $orderData = $ghnData['data'];

            $order->update([
                'statusName' => $orderData['status'] ?? $order->statusName,
                'to_name' => $orderData['to_name'] ?? $order->to_name,
                'to_phone' => $orderData['to_phone'] ?? $order->to_phone,
                'to_address' => $orderData['to_address'] ?? $order->to_address,
                'to_ward_name' => $orderData['to_ward_name'] ?? $order->to_ward_name,
                'to_district_name' => $orderData['to_district_name'] ?? $order->to_district_name,
                'to_province_name' => $orderData['to_province_name'] ?? $order->to_province_name,
                'cod_amount' => $orderData['cod_amount'] ?? $order->cod_amount,
                'insurance_value' => $orderData['insurance_value'] ?? $order->insurance_value,
                'weight' => $orderData['weight'] ?: ($doisoat ?: $order->weight),
                'length' => $orderData['length'] ?? $order->length,
                'width' => $orderData['width'] ?? $order->width,
                'height' => $orderData['height'] ?? $order->height,
                'main_service' => $orderData['service_fee'] ?? $order->main_service,
                'insurance_fee' => $orderData['insurance_fee'] ?? $order->insurance_fee,
                'R2S' => $orderData['return_fee'] ?? $order->R2S,
                'payment_method' => $orderData['payment_type_id'] ?? $order->payment_method,
                'cod_failed_amount' => $orderData['cod_failed_amount'] ?? $order->cod_failed_amount,
                'required_note' => $orderData['required_note'] ?? $order->required_note,
                'items' => $orderData['items'] ?? $order->items,
            ]);

            // Refresh order sau khi cập nhật
            $order = $order->fresh();

            // Lấy thông tin phụ trợ
            $product = ProductStore::where('user_id', $order->user_id)
                ->orderBy('created_at')
                ->get();

            $address = Address::where('user_id', $order->user_id)
                ->get();

            $fee = SettingFee::where('user_id', $order->user_id)
                ->where('shop_id', $order->shop_id)
                ->first();

            return view('frontend.order.edit', compact('order', 'product', 'address', 'fee', 'items'));
        } catch (\Exception $e) {
            Log::error('Edit Order Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi lấy thông tin đơn hàng: ' . $e->getMessage());
        }
    }



    public function updateOrder(Request $request, $id)
    {
        try {
            $order = Orders::find($id);

            if (!$order) {
                return redirect()->back()->with('error', 'Không tìm thấy đơn hàng');
            }

            $shopId = $order->shop_id;
            $fee = $order->fee_shopId;

            $payload = [
                "order_code" => $order->order_code
            ];

            //check thay doi item theo trang thai
            $statusOrder = [
                'ready_to_pick',
                'picking',
            ];

            $statusChangeaddress = [
                'ready_to_pick',
                'picking',
                'picked',
                'storing',
                'transporting',
                'sorting',
                'delivering',
            ];

            // Thêm các trường khác nếu được gửi từ form
            if ($request->filled('from_name')) {
                $payload["from_name"] = $request->from_name;
            }
            if ($request->filled('from_phone')) {
                $payload["from_phone"] = $request->from_phone;
            }
            if ($request->filled('from_address')) {
                $payload["from_address"] = $request->from_address;
            }

            if ($request->filled('from_ward_name')) {
                $payload["from_ward_name"] = $request->from_ward_name;
            }
            if ($request->filled('from_district_name')) {
                $payload["from_district_name"] = $request->from_district_name;
            }
            if ($request->filled('from_province_name')) {
                $payload["from_province_name"] = $request->from_province_name;
            }
            if ($request->filled('from_ward_code')) {
                $payload["from_ward_code"] = (string)$request->from_ward_code;
            }
            if ($request->filled('from_district_id')) {
                $payload["from_district_id"] = (int)$request->from_district_id;
            }

            if ($request->filled('return_phone')) {
                $payload["return_phone"] = $request->return_phone;
                $payload["return_address"] = $request->return_address;
            }

            if ($request->filled('ward_name')) {
                $payload["to_ward_name"] = $request->ward_name;
            }
            if ($request->filled('district_name')) {
                $payload["to_district_name"] = $request->district_name;
            }
            if ($request->filled('province_name')) {
                $payload["to_province_name"] = $request->province_name;
            }

            if ($request->filled('province_id')) {
                $payload["province_id"] = (int)$request->province_id;
            }


            //thay doi dia chi
            if (in_array($order->statusName, $statusChangeaddress)) {
                if ($request->filled('to_name')) {
                    $payload["to_name"] = $request->to_name;
                }
                if ($request->filled('to_phone')) {
                    $payload["to_phone"] = $request->to_phone;
                }
                if ($request->filled('to_address')) {
                    $payload["to_address"] = $request->to_address;
                }

                if ($request->filled('to_ward_code')) {
                    $payload["to_ward_code"] = (string)$request->to_ward_code;
                }
                if ($request->filled('to_district_id')) {
                    $payload["to_district_id"] = (int)$request->to_district_id;
                }
            }


            if ($request->filled('weight_order')) {

                if ($request->weight_order > 49999) {
                    return redirect()->back()->with('error', 'Khối lượng đơn không vượt quá 50kg');
                }

                $payload["weight"] = (int)$request->weight_order;



                $check_servicetypeid = $request->weight_order && $request->weight_order >= 20000 ? 5 : 2;
                $payload["service_type_id"] = (int)$check_servicetypeid;
            }

            if ($request->filled('length')) {
                $payload["length"] = (int)$request->length;
            }
            if ($request->filled('width')) {
                $payload["width"] = (int)$request->width;
            }
            if ($request->filled('height')) {
                $payload["height"] = (int)$request->height;
            }

            if ($request->filled('cod_amount')) {

                $oldCodAmount = $order->cod_amount;
                $newCodAmount = (int)str_replace([',', '.'], '', $request->cod_amount);

                if ($oldCodAmount != $newCodAmount) {
                    if ($request->payment_method == 2) {
                        $payload["cod_amount"] = (int)str_replace([',', '.'], '', $request->cod_amount) + $fee;
                    } else {
                        $payload["cod_amount"] = (int)str_replace([',', '.'], '', $request->cod_amount);
                    }
                }
            }

            if ($request->filled('cod_failed_amount')) {
                $payload["cod_failed_amount"] = (int)str_replace([',', '.'], '', $request->cod_failed_amount);
            }
            if ($request->filled('insurance_value')) {
                $payload["insurance_value"] = (int)str_replace([',', '.'], '', $request->insurance_value);
            }

            // Thông tin khác
            if ($request->filled('payment_method')) {
                $payload["payment_type_id"] = (int)$request->payment_method;
                $payment_method = $order->payment_method;

                if ($payment_method != $request->payment_method) {
                    if ($request->payment_method == 2) {
                        $payload["cod_amount"] = (int)str_replace([',', '.'], '', $request->cod_amount) + $fee;
                    } else {
                        $payload["cod_amount"] = (int)str_replace([',', '.'], '', $request->cod_amount);
                    }
                }
            }

            if ($request->filled('required_note')) {
                $payload["required_note"] = $request->required_note;
            }
            if ($request->filled('note')) {
                $payload["note"] = $request->note;
            }

            $items = [];

            if (!empty($request->name) && is_array($request->name)) {
                foreach ($request->name as $key => $name) {
                    $items[] = [
                        "name" => (string) $name,
                        "code" => (string)$request->code[$key],
                        "quantity" => (int) ($request->quantity[$key] ?? 1),
                        "length" => (int) ($request->length[$key] ?? 0),
                        "width" => (int) ($request->width[$key] ?? 0),
                        "height" => (int) ($request->height[$key] ?? 0),
                        "category" => [
                            "level1" => $request->category_level1[$key] ?? '',
                        ],
                        "weight" => (int) ($request->weight_item[$key] ?? 0),
                        "status" => 'active',
                        "item_order_code" => $order->order_code,
                        "current_warehouse_id" => 0
                    ];
                }
            }

            if (in_array($order->statusName, $statusOrder)) {
                $payload["items"] = $items;
            }

            $payload["shop_id"] = $shopId;

            Log::info('GHN Update Payload:', $payload);

            // Gọi API GHN
            $apiToken = GHN::getToken();
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $apiToken,
                'ShopId' => $shopId
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/update', $payload);

            // Log response để debug
            Log::info('GHN Response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($order->statusName == 'cancel') {
                return redirect()->back()->with('error', 'Đơn hàng đã bị hủy không thể cập nhật');
            }

            if ($response->successful()) {
                $responseData = $response->json();

                if ($responseData['code'] == 200) {
                    // Cập nhật thông tin trong database
                    $order->update([
                        'fullname' => $request->from_name,
                        'phone' => $request->from_phone,
                        'address' => $request->from_address,
                        'district_id' => $request->from_district_id,
                        'ward_id' => $request->from_ward_code,
                        'to_name' => $request->to_name,
                        'to_phone' => $request->to_phone,
                        'to_address' => $request->to_address,
                        'to_ward_name' => $request->ward_name,
                        'to_district_name' => $request->district_name,
                        'to_province_name' => $request->province_name,
                        'to_province' => $request->province_id,
                        'to_district' => $request->district_id,
                        'to_ward' => $request->ward_id,
                        'cod_amount' => $request->payment_method == 2 ? (int)str_replace([',', '.'], '', $request->cod_amount) + $fee : (int)str_replace([',', '.'], '', $request->cod_amount),
                        'weight' => (int)$request->weight_order,
                        'length' => (int)$request->length,
                        'width' => (int)$request->width,
                        'height' => (int)$request->height,
                        'payment_method' =>  (int)$request->payment_method,
                        'required_note' => $request->required_note,
                        'note' => $request->note,
                        'insurance_value' => (int)str_replace([',', '.'], '', $request->insurance_value),
                        'cod_failed_amount' => (int)str_replace([',', '.'], '', $request->cod_failed_amount),
                        'return_phone' => $request->return_phone,
                        'return_address' => $request->return_address,
                        'insurance_fee' => (int)str_replace([',', '.'], '', $request->insurance_fee),
                    ]);

                    $user_id = Auth::check() ? Auth::user()->id : null;
                    $cacheKey = 'orders_user_' . $user_id . '_' . md5(json_encode($request->all()));
                    Cache::forget($cacheKey . '_orders');
                    Cache::forget($cacheKey . '_count_status');

                    return redirect()->route('user.order.edit', $order->id)->with('success', 'Cập nhật đơn hàng thành công');
                }

                return redirect()->back()->with('error', 'Lỗi từ GHN: ' . ($responseData['message'] ?? 'Không xác định'));
            }

            return redirect()->back()->with('error', 'Không thể kết nối với GHN');
        } catch (\Exception $e) {
            Log::error('Update Order Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage());
        }
    }

    public function sendProfileOTP(Request $request)
    {
        try {

            $user = Auth::user();
            $activation = new CoreUsersActivation();
            $otp_code = $activation->createOTPActivation($user);

            // Gửi email chứa OTP
            $user->otp_code = $otp_code;
            $user->email_title = 'Mã xác thực thay đổi thông tin tài khoản của bạn là:';
            Mail::to($user->email)->send(new OTPEmail($user));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function verifyProfileOTP(Request $request)
    {
        $activation = new CoreUsersActivation();
        $activationData = $activation->getActivationByOTP($request->otp);

        if (!$activationData || $activationData->user_id != Auth::id()) {
            return response()->json(['success' => false]);
        }

        // Xóa OTP đã sử dụng
        $activation->deleteOTPActivation($request->otp);

        return response()->json(['success' => true]);
    }


    public function doisoat(Request $request)
    {
        $user = Auth::guard('backend')->user()->id;

        $filter = $params = array_merge(array(
            'order_code' => null,
            'fullname' => null,
            'email' => null,
            'phone' => null,
            'status' => null,
            'user_id' => Auth()->guard('backend')->user()->id ?? null,
            'change_branch' => null,
            'working_date_from' => null,
            'working_date_to' => null,
            'key' => null,
            'limit' => 20,
        ), $request->all());

        // Lấy dữ liệu đối soát với eager loading
        $doisoat = DoiSoat::with([
            'order:id,order_code,statusName,payment_method,cod_failed_amount',
            'branch:id,name_show,shopId',
            'user:id,phone',
            'statusName:id,name,key'
        ])
            ->orderBy('id', 'desc')
            ->paginate(20);

        // Lấy dữ liệu đối soát user với eager loading
        $data1 = DoiSoatUser::with([
            'doiSoats' => function ($query) {
                $query->select('id', 'OrderCode', 'IdDoiSoatUser')
                    ->with(['order:id,order_code,statusName,payment_method,cod_failed_amount']);
            }
        ])
            ->where('user_id', $user)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $this->_data['statusNames'] = StatusName::all();
        $this->_data['data1'] = $data1;
        $this->_data['doisoat'] = $doisoat;
        return view('frontend.user.doisoat', $this->_data);
    }

    public function viewDoiSoat($id)
    {
        $data = DoiSoat::join('lck_orders', 'lck_doi_soat.OrderCode', '=', 'lck_orders.order_code')
            ->where('lck_doi_soat.IdDoiSoatUser', $id)
            ->orderBy('lck_doi_soat.id', 'desc')
            ->get(['lck_doi_soat.*', 'lck_orders.*']);


        // $user = Auth::guard('backend')->user()->id;

        // $data1 = DoiSoatUser::where('user_id', $user)->get();
        $this->_data['data'] = $data;
        $this->_data['statusNames'] = StatusName::all();
        // $this->_data['data1'] = $data1;
        $this->_data['idDoisoat'] = $id;

        return view('frontend.user.viewdoisoat', $this->_data);
    }




    public function bulkCancel(Request $request)
    {
        try {

            $user_id = Auth::check() ? Auth::user()->id : null;
            $selectedOrders = json_decode($request->selected_orders, true);

            $allowedStatuses = [
                'create_order',
                'ready_to_pick',
                'picking',
                'money_collect_picking',
                ''
            ];

            $validOrders = Orders::whereIn('order_code', $selectedOrders)
                ->whereIn('statusName', $allowedStatuses)
                ->pluck('order_code')
                ->toArray();

            // $getOrder = Orders::where('order_code', $selectedOrders)->

            if (empty($validOrders)) {
                return back()->with('error', 'Không có đơn hàng nào có thể hủy');
            }

            // Gọi API GHN để hủy đơn
            $apiToken = GHN::getToken();
            $shopId = $this->checkShopId($request->weight, $user_id);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $apiToken,
                'ShopId' => $shopId
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/switch-status/cancel', [
                'order_codes' => $validOrders
            ]);
            // Xử lý response từ GHN
            $result = $response->json();

            $this->resetCache($request);

            if ($response->successful() && $result['code'] == 200) {
                // Đếm số đơn hủy thành công
                $successCount = 0;

                foreach ($result['data'] as $cancelResult) {
                    if ($cancelResult['result']) {
                        Orders::where('order_code', $cancelResult['order_code'])
                            ->update(['statusName' => 'cancel']);
                        $successCount++;
                    }
                }

                // Thông báo chi tiết kết quả
                $message = "Đã hủy thành công $successCount/" . count($selectedOrders) . " đơn hàng.";
                if (count($selectedOrders) > count($validOrders)) {
                    $message .= " " . (count($selectedOrders) - count($validOrders)) . " đơn không thể hủy do trạng thái không hợp lệ.";
                }

                return redirect('user/orders')->with('success', $message);
            }



            return back()->with('error', 'Không thể hủy đơn: ' . ($result['message'] ?? 'Lỗi không xác định'));
        } catch (\Exception $e) {
            Log::error('Lỗi hủy đơn hàng: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi hủy đơn hàng');
        }
    }

    public function bulkPrint(Request $request)
    {
        try {
            $selectedOrders = json_decode($request->selected_orders, true);

            $apiToken = GHN::getToken();

            // Gọi API GHN để in đơn
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $apiToken
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/a5/gen-token', [
                'order_codes' => $selectedOrders
            ]);

            $result = $response->json();

            if ($response->successful() && $result['code'] == 200) {
                // Lấy token từ response
                $printToken = $result['data']['token'];

                // 2. Tạo các URL in với token
                $printUrls = [
                    'A5' => "https://online-gateway.ghn.vn/a5/public-api/printA5?token=" . $printToken,
                    '80x80' => "https://online-gateway.ghn.vn/a5/public-api/print80x80?token=" . $printToken,
                    '52x70' => "https://online-gateway.ghn.vn/a5/public-api/print52x70?token=" . $printToken
                ];

                // Log thành công

                // Trả về các URL in cho frontend
                return response()->json([
                    'success' => true,
                    'message' => 'Lấy link in thành công',
                    'data' => [
                        'print_urls' => $printUrls
                    ]
                ]);
            } else {
                // Log lỗi


                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Không thể tạo token in đơn'
                ], 400);
            }
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkExport(Request $request)
    {
        try {
            $selectedOrders = json_decode($request->selected_orders, true);

            if (empty($selectedOrders)) {
                return back()->with('error', 'Vui lòng chọn ít nhất một đơn hàng');
            }

            $filename = 'orders_' . date('YmdHis') . '.xlsx';

            return Excel::download(new OrdersExport($selectedOrders), $filename);
        } catch (\Exception $e) {
            Log::error('Lỗi xuất Excel: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi xuất Excel: ' . $e->getMessage());
        }
    }

    public function bulkReturn(Request $request)
    {
        try {
            $user_id = Auth::check() ? Auth::user()->id : null;

            $selectedOrders = json_decode($request->selected_orders, true);

            $allowedStatuses = [
                'storing',
                'waiting_to_return',
            ];

            $validOrders = Orders::whereIn('order_code', $selectedOrders)
                ->whereIn('statusName', $allowedStatuses)
                ->pluck('order_code')
                ->toArray();

            if (empty($validOrders)) {
                return back()->with('error', 'Không có đơn hàng nào trạng thái hợp lệ để hoàn hàng');
            }

            // Gọi API GHN để hoàn hàng
            $apiToken = GHN::getToken();
            $shopId = $this->checkShopId($request->weight, $user_id);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $apiToken,
                'ShopId' => $shopId
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/switch-status/return', [
                'order_codes' => $validOrders
            ]);
            // Xử lý response từ GHN
            $result = $response->json();

            if ($response->successful() && $result['code'] == 200) {
                // Đếm số đơn hoàn thành công
                $successCount = 0;

                foreach ($result['data'] as $cancelResult) {
                    if ($cancelResult['result']) {
                        Orders::where('order_code', $cancelResult['order_code'])
                            ->update(['statusName' => 'return_transporting']);
                        $successCount++;
                    }
                }

                // Thông báo chi tiết kết quả
                $message = "Đã hoàn thành công $successCount/" . count($selectedOrders) . " đơn hàng.";

                if (count($selectedOrders) > count($validOrders)) {
                    $message .= " " . (count($selectedOrders) - count($validOrders)) . " đơn không thể hoàn do trạng thái không hợp lệ.";
                }

                return back()->with('success', $message);
            }

            return back()->with('error', 'Không thể hoàn đơn: ' . ($result['message'] ?? 'Lỗi không xác định'));
        } catch (\Exception $e) {
            Log::error('Lỗi hoàn đơn hàng: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi hoàn đơn hàng');
        }
    }

    public function bulkDeliveryAgain(Request $request)
    {
        try {
            $user_id = Auth::check() ? Auth::user()->id : null;

            $selectedOrders = json_decode($request->selected_orders, true);

            $allowedStatuses = [
                'waiting_to_return',
            ];

            $validOrders = Orders::whereIn('order_code', $selectedOrders)
                ->whereIn('statusName', $allowedStatuses)
                ->pluck('order_code')
                ->toArray();

            if (empty($validOrders)) {
                return back()->with('error', 'Không có trạng thái đơn hàng nào hợp lệ để giao lại');
            }

            // Gọi API GHN để giao lại hàng
            $apiToken = GHN::getToken();
            $shopId = $this->checkShopId($request->weight, $user_id);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $apiToken,
                'ShopId' => $shopId
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/switch-status/storing', [
                'order_codes' => $validOrders
            ]);
            // Xử lý response từ GHN
            $result = $response->json();



            if ($response->successful() && $result['code'] == 200) {

                $successCount = 0;

                foreach ($result['data'] as $cancelResult) {
                    if ($cancelResult['result']) {
                        Orders::where('order_code', $cancelResult['order_code'])
                            ->update(['statusName' => 'delivering']);
                        $successCount++;
                    } else {
                    }
                }

                // Thông báo chi tiết kết quả
                $message = "Đã giao lại thành công $successCount/" . count($selectedOrders) . " đơn hàng.";

                if (count($selectedOrders) > count($validOrders)) {
                    $message .= " " . (count($selectedOrders) - count($validOrders)) . " đơn không thể giao lại do trạng thái không hợp lệ.";
                }

                return back()->with('success', $message);
            }

            return back()->with('error', 'Không thể giao lại đơn: ' . ($result['message'] ?? 'Lỗi không xác định'));
        } catch (\Exception $e) {
            Log::error('Lỗi giao lại đơn hàng: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi giao lại đơn hàng');
        }
    }

    public function bulkExportMany(Request $request)
    {
        $statusArr = $request->input('status_export', []);
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $user_id = Auth::guard('backend')->user()->id;
        $type = $request->input('type');

        $query = Orders::query();

        if (!empty($statusArr)) {
            $query->whereIn('statusName', $statusArr);
        }
        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($type != 'admin') {
            if ($user_id) {
                $query->where('user_id', $user_id);
            }
        }

        $orders = $query->get();
        $orderCodes = $orders->pluck('order_code')->toArray();

        // Nếu không có đơn nào
        if ($orders->isEmpty()) {
            return back()->with('error', 'Không có đơn hàng nào phù hợp để xuất Excel');
        }

        $filename = 'orders_' . date('YmdHis') . '.xlsx';

        return Excel::download(new OrdersExport($orderCodes), $filename);
    }

    public function doisoatbulkExportMany(Request $request)
    {
        $statusArr = $request->input('status_export', []);
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = Doisoat::query();

        if (!empty($statusArr)) {
            $query->whereIn('statusName', $statusArr);
        }
        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $doisoat = $query->orderBy('created_at', 'desc')->get();
        $orderCodes = $doisoat->pluck('OrderCode')->toArray();

        // Nếu không có đơn nào
        if ($doisoat->isEmpty()) {
            return back()->with('error', 'Không có đơn hàng nào phù hợp để xuất Excel');
        }

        $filename = 'doisoat_' . date('YmdHis') . '.xlsx';

        return Excel::download(new DoisoatExport($orderCodes), $filename);
    }

    public function doisoatUser(Request $request)
    {
        $statusArr = $request->input('status_export', []);
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $user_id = Auth::guard('backend')->user()->id;
        $id_doisoatUser = $request->input('id_doisoatuser');

        $query = Doisoat::query();

        if (!empty($statusArr)) {
            $query->whereIn('statusName', $statusArr);
        }
        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        if ($user_id) {
            $query->where('IDUser', $user_id);
        }
        if ($id_doisoatUser) {
            $query->where('IdDoiSoatUser', $id_doisoatUser);
        }

        $doisoat = $query->orderBy('created_at', 'desc')->get();
        $orderCodes = $doisoat->pluck('OrderCode')->toArray();

        // Nếu không có đơn nào
        if ($doisoat->isEmpty()) {
            return back()->with('error', 'Không có đơn hàng nào phù hợp để xuất Excel');
        }

        $filename = 'doisoat_id_' . $user_id . '_' . date('YmdHis') . '.xlsx';

        return Excel::download(new DoisoatUserExport($orderCodes), $filename);
    }

    public function returnOrder(Request $request)
    {
        try {
            $orderCode = $request->order_code;
            $apiToken = GHN::getToken();
            $shopId = $request->shop_id;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $apiToken,
                'ShopId' => $shopId
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/switch-status/return', [
                'order_codes' => [$orderCode]
            ]);

            $result = $response->json();

            if ($response->successful() && $result['code'] == 200) {
                Orders::where('order_code', $orderCode)
                    ->update(['statusName' => 'return']);

                return redirect()->back()->with('success', 'Hoàn hàng thành công');
            }

            return redirect()->back()->with('error', $result['message'] ?? 'Lỗi không xác định');
        } catch (\Exception $e) {
            Log::error('Return Order Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function deliveryAgain(Request $request)
    {
        try {
            $orderCode = $request->order_code;
            $apiToken = GHN::getToken();
            $shopId = $request->shop_id;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $apiToken,
                'ShopId' => $shopId
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/switch-status/storing', [
                'order_codes' => [$orderCode]
            ]);

            $result = $response->json();

            if ($response->successful() && $result['code'] == 200) {
                Orders::where('order_code', $orderCode)
                    ->update(['statusName' => 'storing']);

                return redirect()->back()->with('success', 'Chuyển sang giao lại thành công');
            }

            return redirect()->back()->with('error', $result['message'] ?? 'Lỗi không xác định');
        } catch (\Exception $e) {
            Log::error('Delivery Again Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function profile()
    {
        $user = Auth::guard('backend')->user();
        return view('frontend.user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('backend')->user();

        if ($request->filled('old_password')) {

            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->with('error', 'Mật khẩu cũ không đúng');
            }

            if ($request->new_password != $request->new_password_confirmation) {
                return redirect()->back()->with('error', 'Mật khẩu mới không khớp');
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->update($request->all());

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công');
    }

    public function checkExistsShopid($shopId, $userid)
    {
        $listShopId = CoreUsers::where('id', $userid)->value('shopId');

        $listShopId = json_decode($listShopId, true); // chuyển thành mảng PHP

        if (in_array($shopId, $listShopId)) {
            return $shopId;
        }
    }
}
