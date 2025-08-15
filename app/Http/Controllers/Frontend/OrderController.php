<?php

namespace App\Http\Controllers\Frontend;


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

class OrderController extends BaseFrontendController
{
    protected $_data = [];

    public function tracking(Request $request)
    {
        $phone = $request->get('phone');
        $order_code = $request->get('order_code');

        $data = null;
        if ($phone && $order_code) {
            $data = Orders::get_detail_tracking($order_code, $phone);
        }

        $this->_data['order'] = $data;
        $this->_data['title'] = 'Tra cứu đơn hàng';
        $this->_data['menu_active'] = 'products';

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => 'Tra cứu đơn hàng'
        );

        $this->_data['breadcrumbs'] = $breadcrumbs;

        return view('frontend.order.tracking', $this->_data);
    }

    public function list(Request $request)
    {
        try {
            $user_id  = Auth::check() ? Auth::user()->id : null;

            //check branch kenh ban hang
            $itemflag = [];
            $branchs = Auth()->user()->shopId;
            $branchs = is_array($branchs) ? $branchs : json_decode($branchs, true);

            if (is_array($branchs)) {
                foreach ($branchs as $branchId) {
                    $check = Branch::where('shopId', $branchId)->first();
                    if ($check && $check->type == 2) {
                        $itemflag[] = $check->shopId;
                    }
                }
            }
            //endcheck

            $query = Orders::where('user_id', $user_id)->whereIn('shop_id', $itemflag);

            $product = ProductStore::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
            $address = Address::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
            $checkBank = CoreUsers::where('id', $user_id)->first();
            $count_product = $product->count();
            $count_address = $address->count();

            //check sp va cua hang sau khi tao tai khoan
            if ($count_product == 0 || $count_address == 0) {
                return redirect()->route('user.mystore')->with('warning', 'Vui lòng thêm thông tin sản phẩm và cửa hàng trước khi tạo đơn hàng!');
            }

            if ($checkBank->bank_account == null || $checkBank->bank_number == null || $checkBank->bank_name == null) {
                return redirect()->route('user.profile')->with('error', 'Vui lòng thêm tài khoản ngân hàng trước khi tạo đơn hàng!');
            }

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

            $orders = $query->orderBy('created_at', 'desc')->paginate(100);

            $statusName = StatusName::all();

            foreach ($orders as $item) {
                $status = $statusName->firstWhere('key', $item->statusName);
                if ($status) {
                    $item->status_name = $status->name;
                }
            }

            $count_status = [
                1 => 0, // Đơn nháp
                2 => 0, // Chờ bàn giao
                3 => 0, // Đã bàn giao - Đang giao
                4 => 0, // Đã bàn giao - Đang hoàn hàng
                5 => 0, // Chờ xác nhận giao lại
                6 => 0, // Hoàn tất
                9 => 0, // Hoàn tất
                10 => 0, // Hoàn tất
                7 => 0, // Đơn hủy
                8 => 0,  // Hàng thất lạc - Hư hỏng
                11 => 0, // hoan hang - da chuyen cod
                12 => 0, // hoan hang - chua chuyen cod  
                13 => 0, // dang giao hang 
                14 => 0, // giao hang that bai
                15 => 0, // dang xu ly
            ];

            // Lấy tất cả đơn hàng để đếm số lượng theo trạng thái
            $allOrders = Orders::where('user_id', $user_id)->whereIn('shop_id', $itemflag)->get();
            foreach ($allOrders as $item) {
                $status = $item->statusName;
                if (in_array($status, Orders::STATUS_GHN_1)) {
                    $count_status[1]++;
                } elseif (in_array($status, Orders::STATUS_GHN_2)) {
                    $count_status[2]++;
                } elseif (in_array($status, Orders::STATUS_GHN_3_2)) {
                    $count_status[15]++;
                } elseif ($status == Orders::status_ghn9_1) {
                    $count_status[13]++;
                } elseif ($status == Orders::status_ghn11_1) {
                    $count_status[14]++;
                } elseif (in_array($status, Orders::STATUS_GHN_4)) {
                    $count_status[4]++;
                } elseif (in_array($status, Orders::STATUS_GHN_5)) {
                    $count_status[5]++;
                } elseif (in_array($status, Orders::STATUS_GHN_6)) {
                    $doisoat = $item->doisoat;
                    if ($doisoat) {
                        if ($doisoat->type == 2 && $item->statusName == Orders::status_ghn18_1) {
                            $count_status[9]++;
                        } elseif ($doisoat->type != 2 && $item->statusName == Orders::status_ghn18_1) {
                            $count_status[10]++;
                        } else {
                            $count_status[6]++;
                        }
                    } else {
                        $count_status[6]++;
                    }
                    if ($item->statusName == Orders::status_ghn19_1) {
                        if ($doisoat->type == 2) {
                            $count_status[11]++;
                        } elseif ($doisoat->type != 2) {
                            $count_status[12]++;
                        } else {
                        }
                    }
                } elseif (in_array($status, Orders::STATUS_GHN_7)) {
                    $count_status[7]++;
                } elseif (in_array($status, Orders::STATUS_GHN_8)) {
                    $count_status[8]++;
                }
            }

            $branch = Branch::orderBy('created_at', 'desc')->get();

            return view('frontend.channel.index', compact('orders', 'branch', 'count_status'));
        } catch (\Exception $e) {
            Log::error('Order Search Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Có lỗi xảy ra khi tìm kiếm đơn hàng');
        }
    }
}
