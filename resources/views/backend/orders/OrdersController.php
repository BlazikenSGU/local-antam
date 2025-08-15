<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ExportExcelDoiSoat;
use App\Exports\ExportOrder;
use App\Exports\ReportsSupplier;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\BaseBackendController;
use App\Jobs\PushNotification;
use App\Models\Address;
use App\Models\Branch;
use App\Models\CoreUsers;
use App\Models\DoiSoat;
use App\Models\Files;
use App\Models\HistoryOrder;
use App\Models\Notification;
use App\Models\Orders;
use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use App\Models\OrdersDetail;
use App\Models\ProductType;
use App\Models\StatusName;
use App\Models\Warehouse;
use App\Models\DiscountCode;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Settings;
use App\Utils\Common;
use App\Utils\Common as Utils;
use App\VNShipping\Ahamove;
use App\VNShipping\GHN;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\Exports\ExportExcelOrder;

class OrdersController extends BaseBackendController
{
    protected $_data = array(
        'title' => 'Quản lý đơn hàng',
        'subtitle' => 'Quản lý đơn hàng',
    );

    protected $_limits = [
        10, 30, 50, 100, 500, 1000, 5000, 10000
    ];

    public function __construct()
    {
        $settings = Settings::get_setting_member();

        foreach ($settings as $k => $v) {
            $this->_data[$k] = $v;
        }
        $this->_data['status'] = Orders::$Keystatus;
        $this->_data['_limits'] = $this->_limits;
        parent::__construct();
    }

    public function index(Request $request)
    {
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
        $deletedOrders = Orders::whereNull('to_name')->delete();


        $objModel = new Orders();

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data_list = $objModel->get_by_where($params);

        $start = ($data_list->currentPage() - 1) * $filter['limit'];

        foreach ($data_list as $item_list) {
            $DoiSOat = DoiSoat::where('OrderCode', $item_list->order_code)->first();
            $thuhoCOD = '';
            $gtbThuTien = '';
            if (!empty($DoiSOat->type) && $DoiSOat->type == 2) {
                $thuhoCOD =  'Đã chuyển COD';
            } elseif ( !empty($DoiSOat->type) &&  $DoiSOat->type == 1) {
                $thuhoCOD = 'Chưa chuyển COD';
            }
            if (!empty($DoiSOat->tinhtrangthutienGTB) &&  $DoiSOat->tinhtrangthutienGTB == 1) {
                $gtbThuTien = 'Thành công';
            } elseif (!empty($DoiSOat->tinhtrangthutienGTB) && $DoiSOat->tinhtrangthutienGTB == 0) {
                $gtbThuTien = '';
            }
            $item_list['gtbThuTien'] = $gtbThuTien;
            $item_list['thuhoCOD'] = $thuhoCOD;
        }

        $this->_data['data_list'] = $data_list;


        $this->_data['next_page_url'] = $params['pagin_path'];
        //dd($this->_data['data_list'] );


        $data = $objModel->get_by_where_distinct($params);
        $data_distinct = array();
        foreach ($data as $item) {
            $data_distinct [] = $item->created_at->format('d-m-Y');
        }
        // Loại bỏ các ngày trùng nhau
        $data_distinct = array_unique($data_distinct);

        // Sắp xếp mảng theo ngày giảm dần
        usort($data_distinct, function($a, $b) {
            $dateA = strtotime($a);
            $dateB = strtotime($b);
            return $dateB - $dateA;
        });
        //dd($data_distinct);

        $this->_data['statusNames'] = StatusName::all();
        $this->_data['start'] = $start;
        $this->_data['filter'] = $filter;
        $this->_data['data_distinct'] = $data_distinct;

        return view('backend.orders.index', $this->_data);
    }
    public function index1(Request $request)
    {
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
        $deletedOrders = Orders::whereNull('to_name')->delete();


        $objModel = new Orders();

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data_list = $objModel->get_by_where($params);

        $start = ($data_list->currentPage() - 1) * $filter['limit'];

        foreach ($data_list as $item_list) {
            $DoiSOat = DoiSoat::where('OrderCode', $item_list->order_code)->first();
            $thuhoCOD = '';
            $gtbThuTien = '';
            if (!empty($DoiSOat->type) && $DoiSOat->type == 2) {
                $thuhoCOD =  'Đã chuyển COD';
            } elseif ( !empty($DoiSOat->type) &&  $DoiSOat->type == 1) {
                $thuhoCOD = 'Chưa chuyển COD';
            }
            if (!empty($DoiSOat->tinhtrangthutienGTB) &&  $DoiSOat->tinhtrangthutienGTB == 1) {
                $gtbThuTien = 'Thành công';
            } elseif (!empty($DoiSOat->tinhtrangthutienGTB) && $DoiSOat->tinhtrangthutienGTB == 0) {
                $gtbThuTien = '';
            }
            $item_list['gtbThuTien'] = $gtbThuTien;
            $item_list['thuhoCOD'] = $thuhoCOD;
        }

        $this->_data['data_list'] = $data_list;


        $this->_data['next_page_url'] = $params['pagin_path'];


        $data = $objModel->get_by_where_distinct($params);
        $data_distinct = array();
        foreach ($data as $item) {
            $data_distinct [] = $item->created_at->format('d-m-Y');
        }
        // Loại bỏ các ngày trùng nhau
        $data_distinct = array_unique($data_distinct);

        // Sắp xếp mảng theo ngày giảm dần
        usort($data_distinct, function($a, $b) {
            $dateA = strtotime($a);
            $dateB = strtotime($b);
            return $dateB - $dateA;
        });
        //dd($data_distinct);

        $this->_data['statusNames'] = StatusName::all();
        $this->_data['start'] = $start;
        $this->_data['filter'] = $filter;
        $this->_data['data_distinct'] = $data_distinct;
        return view('backend.orders.index1', $this->_data);
    }

    public function ajaxData(Request $request)
    {
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
            'key' => $request->get('key') ?? null,
            'page' => $request->get('page') ?? null,
            'limit' => 20,
        ));
        $deletedOrders = Orders::whereNull('to_name')->delete();

        $objModel = new Orders();

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data_list = $objModel->get_by_where($params);

        $start = ($data_list->currentPage() - 1) * $filter['limit'] ;

        $this->_data['data_list'] = $data_list;


        $this->_data['start'] = $start;
        $this->_data['filter'] = $filter;
        $html = view('backend.orders.ajax.loadmoredata', $this->_data)->render();

        return $this->returnResult(['html' => $html, 't' => $params]);
    }

    public function createExcel(Request $request)
    {
        $user = Auth()->guard('backend')->user()->toArray();
        $address = Common::FullAddress($user);
        $AD = Address::where('user_id', $user['id'])->where('is_default', 1)->first();
        if ($request->isMethod('POST')) {
            // Kiểm tra xem tệp đã được tải lên hay không
            try {
                if ($request->hasFile('booking_attachment')) {
                    $file = $request->file('booking_attachment');

                    // Kiểm tra phần mở rộng của tệp
                    $extension = $file->getClientOriginalExtension();
                    if (in_array(strtolower($extension), ['xls', 'xlsx', 'csv'])) {
                        // Tạo validator để kiểm tra tệp
                        $validator = Validator::make($request->all(), [
                            'booking_attachment' => 'required|file|mimes:xls,xlsx,csv|max:2048', // Thay đổi kích thước tệp tối đa nếu cần
                        ]);

                        // Kiểm tra nếu có lỗi validator
                        if ($validator->fails()) {
                            $request->session()->flash('msg', ['danger', 'Lỗi: ' . $validator->errors()->first()]);
                            return redirect()->back()->withErrors($validator)->withInput();
                        }

                        // Di chuyển tệp vào thư mục lưu trữ
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('storage/uploads'), $fileName);

                        // Lưu thông tin tệp vào CSDL
                        Files::create([
                            'user_id' => Auth()->guard('backend')->user()->id,
                            'file_path' => $fileName,
                            'type' => 15 // file Excel
                        ]);

                        // Đường dẫn đến tệp Excel
                        $filePath = public_path('storage/uploads/' . $fileName);

                        // Đọc nội dung của tệp Excel
                        $spreadsheet = IOFactory::load($filePath);

                        // Lấy sheet đầu tiên từ tệp Excel
                        $worksheet = $spreadsheet->getActiveSheet();

                        // Lấy số hàng và số cột của sheet
                        $highestRow = $worksheet->getHighestRow();
                        $highestColumn = $worksheet->getHighestColumn();


                        $data = [];

                        for ($row = 5; $row <= $highestRow; $row++) {
                            // Kiểm tra xem hàng đó có dữ liệu không
                            $value = $worksheet->getCell('A' . $row)->getValue();

                            // Nếu hàng không có dữ liệu, thoát khỏi vòng lặp
                            if (empty($value)) {
                                break;
                            }

                            // Khai báo mảng chứa dữ liệu của từng hàng
                            $rowData = [];

                            // Lặp qua từng cột từ cột A đến cột S
                            for ($col = 'A'; $col <= 'S'; $col++) {
                                // Lấy giá trị của ô
                                $value = $worksheet->getCell($col . $row)->getValue();
                                // Thêm giá trị vào mảng $rowData
                                $rowData[] = $value;
                            }

                            // Thêm mảng dữ liệu của hàng vào mảng chứa toàn bộ dữ liệu
                            $data[] = $rowData;
                        }

                        // Hiển thị dữ liệu hoặc thực hiện xử lý dữ liệu tiếp theo ở đây

                        $errors = []; // Mảng chứa số dòng có lỗi
                        foreach ($data as $index => $item) {
                            $params['user_id'] = $user['id'];
                            $params['fullname'] = $user['fullname'];
                            $params['phone'] = $user['phone'];
                            $params['email'] = $user['email'];
                            $params['address'] = $address;
                            $params['province_id'] = $user['province_id'];
                            $params['district_id'] = $user['district_id'];
                            $params['ward_id'] = $user['ward_id'];

                            // Kiểm tra các điều kiện để xác định xem dữ liệu từ file Excel có hợp lệ không
                            if (intval($item[7]) <= 50000) {
                                // Tạo đơn hàng chỉ khi dữ liệu hợp lệ
                                $params['to_name'] = $item[0];
                                $params['to_phone'] = '0' . (string)$item[1];
                                $params['to_address'] = $item[2];

                                $params['to_province_name'] = $item[5];
                                $params['to_district_name'] = $item[4];
                                $params['to_ward_name'] = $item[3];

                                $params['required_note'] = $item[6];
                                $params['weight'] = $item[7];
                                $params['length'] = $item[8];
                                $params['width'] = $item[9];
                                $params['height'] = $item[10];
                                $params['insurance_value'] = $item[11];
                                $params['payment_method'] = $item[12];
                                $params['order_code_custom'] = $item[13];
                                $params['note'] = $item[15];
                                $params['caGiaohang'] = $item[16];
                                $params['cod_amount'] = $item[18];
                                $params['cod_failed_amount'] = $item[17];
                                $params['product_type'] = Branch::where('id', $AD->product_type)->first()->shopId ?? '4521661';

                                // Thêm đơn hàng vào database
                                try {
                                    $order = Orders::create($params);
                                    $orderDetails = OrdersDetail::create([
                                        'order_id' => $order->id,
                                        'product_voluem' => 1,
                                        'product_name' => $item[14],
                                        'quantity' => 1,
                                    ]);

                                    $externalItems [] = [
                                        "name" => $item[14],
                                        "code" => '',
                                        "quantity" => 1,
                                    ];

                                    $PreviewOrder = GHN::Order($params, $externalItems);


                                    $detail = GHN::Detail($PreviewOrder['data']['data']['order_code']);


                                    $order->order_code = $PreviewOrder['data']['data']['order_code'];
                                    $order->total_fee = $PreviewOrder['data']['data']['fee']['main_service'];
                                    $order->statusName = $detail['data']['status'];
                                    $order->save();
                                } catch (\Exception $e) {
                                    // Ghi nhận số dòng có lỗi vào mảng errors
//                                    $errors[] = $index + 1; // Vì index bắt đầu từ 0, nên cộng thêm 1 để đếm từ 1
                                }
                            } else {
                                // Ghi nhận số dòng có lỗi vào mảng errors
                                $errors[] = $index + 5;
                                $request->session()->flash('popup', ['danger', 'Có lỗi khi tạo đơn hàng dòng số: ' . implode(', ', $errors)]);

                            }
                        }

                    } else {
                        // Nếu không phải là một tệp Excel, trả về thông báo lỗi
                        $request->session()->flash('msg', ['danger', 'Vui lòng chọn một tệp Excel.']);
                        return redirect()->back();
                    }
                }
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }

        $this->_data['files'] = Files::where('user_id', Auth()->guard('backend')->user()->id)
            ->where('type', 15)->get();
        $this->_data['a_address'] = $AD;
        return view('backend.orders.importExcel', $this->_data);
    }

    public function downloadfile($id)
    {
        $file = Files::find($id);
        $filename = $file->file_path;
        $filePath = '/public/uploads/' . $filename;

        // Kiểm tra xem file có tồn tại không
        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Tải file
        return Storage::download($filePath);
    }


    public function add(Request $request)
    {
        $user = Auth()->guard('backend')->user()->toArray();

        $AD = Address::where('user_id', $user['id'])->where('is_default', 1)->first();
        if (empty($AD)) {
            return redirect()->route('backend.users.index')->with('error', 'Vui lòng thêm địa chỉ của hàng trước khi tạo đơn.');
        }

        $address = $AD->street_name . ',' . $AD->ward_name . ',' . $AD->district_name . ',' . $AD->province_name;
        $phiAdmin = \App\Models\SettingFee::get_by_where($user['id'], Branch::where('id', $AD->product_type)->first()->shopId)->cost ?? 0;

        DB::beginTransaction();

        $order = Orders::create([
            'user_id' => $user['id'],
            'fullname' => $AD->name,
            'phone' => $AD->phone,
            'email' => $user['email'],
            'address' => $address,
            'province_id' => $AD->province_id,
            'district_id' => $AD->district_id,
            'ward_id' => $AD->ward_id,
            'province_name' => $AD->province_name,
            'district_name' => $AD->district_name,
            'ward_name' => $AD->ward_name,
            'required_note' => $AD->required_note,
            'note' => $AD->note,


            'product_type' => Branch::where('id', $AD->product_type)->first()->shopId ?? '4521661',
            'product_type_cost' => $phiAdmin,
            'to_district' => 1766,
            'length' => 10,
            'width' => 10,
            'height' => 10,
            'weight' => 1,
            'statusName' => 'create_order',
            'cod_amount' => 0,
            'cod_failed_amount' => 0,
            'insurance_value' => 0,
            'payment_method' => $AD->payment_type,
            'payment_fee'   => $AD->payment_type == 2  ? $phiAdmin : 0,
        ]);


        //insert create
        $orderDetails = OrdersDetail::create([
            'order_id' => $order->id,
            'product_voluem' => 1,
            'quantity' => 1,
        ]);

        return redirect()->route('backend.orders.create', $order->id);

    }

    public function edit(Request $request, $id)
    {

        $order = Orders::find($id);

        $user = Auth()->guard('backend')->user()->toArray();
        $address = Common::FullAddress($user);
        $ShiftDate = GHN::getShiftDate();
        if ($ShiftDate['r'] == 1) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại! ' . $ShiftDate['msg']]);
        }
        $caGiaohang = $ShiftDate['data'];

        $this->_data['shopName'] = Auth()->guard('backend')->user()->fullname . ' - ' . Auth()->guard('backend')->user()->phone;

        $order = Orders::find($id);
        $orderDetails = OrdersDetail::where('order_id', $id)->get();

        if ($request->getMethod() == 'POST') {

            $cod_amount = (int) str_replace(['.', ','], '', $order->cod_amount);
            $requestcod_amount = (int) str_replace(['.', ','], '', $request->get('cod_amount'));
            $payment_method = $request->get('payment_method');

            $phiAdmin = $request->get('product_type_cost', $order->product_type_cost);

            $order->payment_fee  = $payment_method == 2 ? $phiAdmin : 0;
            if ($cod_amount != $requestcod_amount) {
                $params = [
                    'product_type' => $order->product_type,
                    'order_code' => $order->order_code,
                    'cod_amount' => $request->get('cod_amount') +$phiAdmin ,
                ];
                $data1 = GHN::ChangeCOD($params);
                if ($data1['r'] == 1) {
                    $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, trạng thái đơn hàng không hợp lệ.']);
                    return redirect()->back();
                }

                $order->cod_amount = $request->get('cod_amount');
                $order->save();
            }



            $order->to_phone = $request->get('phone');
            $order->to_name = $request->get('username');
            $order->to_address = $request->get('address');
            $order->to_province = $request->get('to_province');
            $order->to_province_name = $request->get('to_province_name');
            $order->to_district = $request->get('to_district');
            $order->to_district_name = $request->get('to_district_name');
            $order->to_ward = $request->get('to_ward');
            $order->to_ward_name = $request->get('to_ward_name');
            $order->weight = $request->get('weight');
            $order->length = $request->get('length');
            $order->width = $request->get('width');
            $order->cod_amount = $request->get('cod_amount');
            $order->insurance_value = $request->get('insurance_value');
            $order->cod_failed_amount = $request->get('cod_failed_amount');
            $order->note = $request->get('note');
            $order->required_note = $request->get('required_note');
            $order->product_type_cost = $request->get('product_type_cost');
            $order->payment_method = $request->get('payment_method');




            $array_product_name = $request->get('product_name');

            $array_product_code = $request->get('product_code');
            $array_product_voluem = $request->get('product_voluem');
            $array_product_quantity = $request->get('product_quantity');
            $externalItems = [];

            // bảo 8/5/2024
            foreach ($array_product_name as $k => $item) {

                    // Thêm vào mảng externalItems
                    $externalItems[] = [
                        "name" => $item,
                        "code" => $array_product_code[$k],
                        "quantity" => (int)$array_product_quantity[$k],
                        "weight" => (int)$array_product_voluem[$k],
                    ];

            }
            // end
            $Params = Common::ParamsInStatus($order, $externalItems);


            if (empty($Params['body'])) {
                return redirect()->back()->with('error', 'Trạng thái đơn hàng không hợp lệ.');
            }
            $data = GHN::updateOrder($Params);
            //dd($data);
            if ($data['r'] == 1) {
                return redirect()->back()->with('error', 'Có lỗi xảy ra, trạng thái đơn hàng không hợp lệ.');
            } else {
                $order->save();



                $totalWight = $weight = $order->weight;
                $weight_old =  $order->length*$order->width*$order->height/5;
                if ($weight <  $weight_old) {
                    $totalWight = $weight_old;
                }

                $shopId = Branch::where('to_weight', '<=', $totalWight)
                    ->where('from_weight', '>=', $totalWight)
                    ->first()->shopId;
                $product_type_cost =   \App\Models\SettingFee::get_by_where($order->user_id, $shopId)->cost ?? 0;


                $order->product_type_cost = $product_type_cost;
                $order->weight = $totalWight;
                $order->product_type = $shopId;
                $order->save();



                $getFee = null;
                if (!empty($order->to_district) and !empty($order->to_ward)) {
                    $fee = [
                        "shopId"                => $order->product_type,
                        "from_district_id"      => intval($order->district_id),
                        "to_district_id"        => intval($order->to_district),
                        "to_ward_code"          => $order->to_ward,
                        "height"                => 0,
                        "length"                => 0,
                        "weight"                => intval($order->weight),
                        "width"                 => 0,
                        "insurance_value"       => intval( str_replace(',', '', $order->insurance_value)),
                    ];
                    $getFee = GHN::GetFee($fee);
                    $order->total_fee = $getFee['data']['service_fee'] + $order->product_type_cost;
                    $order->insurance_fee = $getFee['data']['insurance_fee'];
                    $order->main_service = $getFee['data']['service_fee'] + $order->product_type_cost + $getFee['data']['insurance_fee'];
                    $order->save();
                }


                return redirect()->back()->with('success', 'Cập nhập thành công.');
            }

        }


        $this->_data['orderDetails'] = $orderDetails;
        $this->_data['shopAddress'] = $order->address;
        $this->_data['user'] = $user;
        $this->_data['caGiaohang'] = $caGiaohang;
        $this->_data['order'] = $order;
        $this->_data['address'] = Address::where('user_id', $user['id'])->where('is_default', 1)->first()->product_type ?? 27;

        return view('backend.orders.edit', $this->_data);
    }

    public function create(Request $request, $id)
    {
        $order = Orders::find($id);
        if (!$order) {
            return abort(404);
        }


        $user = Auth()->guard('backend')->user()->toArray();
        $address = Common::FullAddress($user);
        $ShiftDate = GHN::getShiftDate();
        if ($ShiftDate['r'] == 1) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại! ' . $ShiftDate['msg']]);
        }
        $caGiaohang = $ShiftDate['data'];

        $this->_data['shopName'] = Auth()->guard('backend')->user()->fullname . ' - ' . Auth()->guard('backend')->user()->phone;

        $order = Orders::find($id);
        $orderDetails = OrdersDetail::where('order_id', $id)->get();


        $this->_data['orderDetails'] = $orderDetails;
        $this->_data['shopAddress'] = $order->address ?? '';
        $this->_data['user'] = $user;
        $this->_data['caGiaohang'] = $caGiaohang;
        $this->_data['order'] = $order;
        $this->_data['address'] = Address::where('user_id', $user['id'])->where('is_default', 1)->first()->product_type ?? 27;

        return view('backend.orders.create', $this->_data);
    }

    public function AddProductOrder(Request $request)
    {
        $orderId = $request->get('order_id');
        $orderDetails = OrdersDetail::create([
            'order_id' => $orderId,
            'product_voluem' => 1,
            'product_name' => '',
            'quantity' => 1,
        ]);
        $order = Orders::find($orderId);
        $order->weight = $order->weight + 200;
        $order->save();
        return \Response::json(['e' => $orderDetails]);
    }

    public function editorder(Request $request)
    {
        $orderId = $request->get('id');
        $order = Orders::find($orderId);
        $order->weight = $request->get('weight');
        $order->total_fee = $request->get('total_fee');
        $order->total_cost = $request->get('total_cost');
        $order->main_service = $request->get('total_cost');
        $order->insurance_fee = $request->get('insurance_fee');
        $order->save();
        return \Response::json(['e' => $order]);
    }


    public function export(Request $request)
    {
        $arrays = explode(',', $request->get('selectedValues'));
        $query =  Orders::join('lck_doi_soat', 'lck_doi_soat.OrderCode', '=', 'lck_orders.order_code');
        $query->whereIn('lck_orders.id', $arrays);
        $data = $query->get(['lck_doi_soat.*', 'lck_orders.*']);



        $fileName = 'Đơn Hàng - ' . Carbon::now()->format('d-m-Y H-i') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new ExportExcelOrder($data), $fileName);

    }

    public function ExportExcel(Request $request)
    {
        $user = Auth::guard('backend')->user()->id;
        $date_start = $request->get('date_start');
        $date_end = $request->get('date_end');
        $status = $request->get('status');

        $all = $request->get('status1');
        $cod = $request->get('cod');


        $query =  Orders::join('lck_doi_soat', 'lck_doi_soat.OrderCode', '=', 'lck_orders.order_code');


        if ($user != 168) {
            $query->where('user_id', $user);
        }
        if (!empty($date_start) && !empty($date_end)) {
            // Convert date strings to Carbon instances for better date manipulation
            $start = \Carbon\Carbon::parse($date_start)->startOfDay();
            $end = \Carbon\Carbon::parse($date_end)->endOfDay();

            $query->whereBetween('lck_orders.created_at', [$start, $end]);
        }

        if (!empty($status)) {
            $query->whereIn('lck_orders.statusName', $status);
        }

        if (!empty($cod)) {
            $query->where('lck_doi_soat.type', $cod);
        }


        $data = $query->get(['lck_doi_soat.*', 'lck_orders.*']);



        $fileName = 'Đơn Hàng - ' . Carbon::now()->format('d-m-Y H-i') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new ExportExcelOrder($data), $fileName);
    }
    public function editProduct(Request $request)
    {
        $id = $request->get('id');
        $orderDetails = OrdersDetail::find($id);
        $params['product_name'] = $request->get('product_name', $orderDetails->product_name);
        $params['product_code'] = $request->get('product_code', $orderDetails->product_code);
        $params['product_voluem'] = $request->get('product_voluem', $orderDetails->product_voluem);
        $params['quantity'] = $request->get('product_quantity', $orderDetails->quantity);
        $orderDetails->update($params);
        return \Response::json(['e' => $orderDetails]);

    }


    public function deleteproduct(Request $request)
    {
        $id = $request->get('id');
        $orderDetails = OrdersDetail::find($id);
        $weight = $orderDetails->product_voluem * $orderDetails->quantity;
        $order = Orders::find($orderDetails->order_id);
        $order->weight = $order->weight - $weight;
        $order->save();
        $orderDetails->delete();
        return \Response::json(['e' => 0, 'r' => $order]);
    }

    public function update(Request $request)
    {
        $id = $request->get('id');
        $order = Orders::find($id);




        $payment_method = $request->get('payment_method', $order->payment_method);
        $phiAdmin = $request->get('product_type_cost', $order->product_type_cost);

        $params['payment_fee']  = $payment_method == 2 ? $phiAdmin : 0;
        $params['caGiaohang'] = $request->get('caGiaohang', $order->caGiaohang);
        $params['to_phone'] = $request->get('to_phone', $order->to_phone);
        $params['to_name'] = $request->get('to_username', $order->to_name);
        $params['to_address'] = $request->get('to_address', $order->to_address);
        $params['to_province'] = $request->get('to_province', $order->to_province);
        $params['to_province_name'] = $request->get('province_name', $order->to_province_name);
        $params['to_district'] = $request->get('to_district', $order->to_district);
        $params['to_district_name'] = $request->get('district_name', $order->to_district_name);
        $params['to_ward'] = $request->get('to_ward', $order->to_ward);
        $params['to_ward_name'] = $request->get('ward_name', $order->to_ward_name);
        $params['weight'] = $request->get('weight', $order->weight);
        $params['length'] = $request->get('length', $order->length);
        $params['width'] = $request->get('width', $order->width);
        $params['height'] = $request->get('height', $order->height);
        $params['required_note'] = $request->get('required_note', $order->required_note);
        $params['order_code'] = $request->get('order_code', $order->order_code);
        $params['note'] = $request->get('note', $order->note);
        $params['payment_method'] = $request->get('payment_method', $order->payment_method);
        $params['cod_amount'] = $request->get('cod_amount', $order->cod_amount);
        $params['cod_failed_amount'] = $request->get('cod_failed_amount', $order->cod_failed_amount);
        $params['product_type'] = $request->get('product_type', $order->product_type);
        $params['insurance_value'] = $request->get('insurance_value', $order->insurance_value);
        $params['order_code_custom'] = $request->get('order_code_custom', $order->order_code_custom);
        $params['return_phone'] = $request->get('return_phone', $order->return_phone);
        $params['return_address'] = $request->get('return_address', $order->return_address);
        $params['product_type_cost'] = $request->get('product_type_cost', $order->product_type_cost);
        $order->update($params);
        $order->save();
        $totalWight = 0;
         $weight = $order->weight;
        $weight_old =  $order->length*$order->width*$order->height/5;
        if ($weight <  $weight_old) {
            $totalWight = $weight_old;
        } else {
            $totalWight = $weight;
        }

        $shopId = Branch::where('to_weight', '<=', $totalWight)
            ->where('from_weight', '>=', $totalWight)
            ->first()->shopId;
        $product_type_cost =   \App\Models\SettingFee::get_by_where($order->user_id, $shopId)->cost ?? 0;


        $order->product_type_cost = $product_type_cost;
        //$order->weight = ;
        $order->product_type = $shopId;
        $order->save();



        $getFee = null;
        if (!empty($order->to_district) and !empty($order->to_ward)) {
            $fee = [
                "shopId"                => $order->product_type,
                "from_district_id"      => intval($order->district_id),
                "to_district_id"        => intval($order->to_district),
                "to_ward_code"          => $order->to_ward,
                "height"                => 0,
                "length"                => 0,
                "weight"                => intval($totalWight),
                "width"                 => 0,
                "insurance_value"       => intval( str_replace(',', '', $order->insurance_value)),
            ];
            $getFee = GHN::GetFee($fee);
            $order->total_fee = $getFee['data']['service_fee'] + $order->product_type_cost;
            $order->insurance_fee = $getFee['data']['insurance_fee'];
            $order->main_service = $getFee['data']['service_fee'] + $order->product_type_cost + $getFee['data']['insurance_fee'];
            $order->save();
        }




        return \Response::json(['e' => $getFee, 'r' => $order, 't' =>$totalWight, 'j'=>$shopId]);
    }

    public function ordersPreview(Request $request)
    {
        $order = Orders::find($request->get('id'));


        $order_code_custom = $order->order_code_custom;

        $chekclient_order_code = GHN::ClientOrderCode($order_code_custom);
        if ($chekclient_order_code['r'] == 0) {

            return \Response::json(['e' => 1, 'r' => $chekclient_order_code['r']]);
        }


        $OrderItems = OrdersDetail::where('order_id', $order->id)->get();
        $externalItems = [];
        foreach ($OrderItems as $OrderItem) {
            $externalItems [] = [
                "name" => $OrderItem->product_name,
                "code" => $OrderItem->product_code,
                "quantity" => 1,
            ];
        }

        $PreviewOrder = GHN::GetPreview($order, $externalItems);


        return \Response::json(['e' => 0, 'r' => $PreviewOrder]);
    }

    public function orderscheckout(Request $request, $id)
    {



        $order = Orders::find($id);
        $order->insurance_value = $request->get('insurance_value');
        $order->cod_amount = $request->get('cod_amount');
        $order->cod_failed_amount = $request->get('cod_failed_amount');
        $order->save();

        $OrderItems = OrdersDetail::where('order_id', $order->id)->get();
        $externalItems = [];
        foreach ($OrderItems as $OrderItem) {
            $externalItems [] = [
                "name" => $OrderItem->product_name,
                "code" => $OrderItem->product_code,
                "quantity" => $OrderItem->quantity,
                "weight" => (int)$OrderItem->product_voluem,
                "category" => [ "level1"=>"Áo"]
            ];
        }

        $PreviewOrder = GHN::Order($order, $externalItems);
        if ($PreviewOrder['r'] == 1) {

            return \Response::json(['e' => 0, 'r' => 'Có lỗi xảy ra, vui lòng thử lại! ' . $PreviewOrder['msg']]);
        } else {

            $params = [
                'shopId' => $order->product_type,
                'order_code' => $PreviewOrder['data']['data']['order_code'],
            ];
            $Updatefee = GHN::GetDetailFee($params);


            $order->order_code = $PreviewOrder['data']['data']['order_code'];

            $order->statusName = 'ready_to_pick';
            $order->status = '10';
            $order->total_fee =  number_format($PreviewOrder['data']['data']['fee']['main_service'] + $order->product_type_cost).' ';
            $order->main_service = number_format($PreviewOrder['data']['data']['fee']['main_service'] + $PreviewOrder['data']['data']['fee']['insurance'] + $order->product_type_cost).' ';
            $order->insurance_fee = $PreviewOrder['data']['data']['fee']['insurance'];
            $order->R2S = 0;



            $order->save();
            HistoryOrder::create([
                'name' => $order->phone,
                'content' => 'Đã tao đơn hàng.',
                'order_id' => $order->id,
            ]);

            return \Response::json(['e' => 0, 'i'=>$PreviewOrder['i'] ,'r' => 'Tạo đơn hàng thành công. Mã đơn hàng ' . $PreviewOrder['data']['data']['order_code']]);

        }


    }

    public function history(Request $request)
    {
        $data = HistoryOrder::where('order_id', $request->get('id'))->get();
        $html = '';
        $html .= "<ul>";
        foreach ($data as $item) {
            $html .= "<li>{$item['name']}-{$item['content']}-Ngày-{$item['created_at']->format('d/m/Y')}</li>";
        }
        $html .= "</ul>";
        return \Response::json(['e' => 0, 'r' => $html]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = Orders::find($id);
            $data->delete();
            $request->session()->flash('msg', ['info', 'Đã xóa thành công!']);
        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        return redirect($this->_ref ? $this->_ref : Route('backend.orders.index'));
    }

    public function changeAddress(Request $request, $id)
    {
        //dd($request->all());
        $data = Orders::find($id);
        $data->fullname = $request->get('fullname');
        $data->phone = $request->get('phone');
        $data->province_id = $request->get('province_id');
        $data->district_id = $request->get('district_id');
        $data->ward_id = $request->get('ward_id');

        $data->province_name = $request->get('province_name1');
        $data->district_name = $request->get('district_name1');
        $data->ward_name = $request->get('ward_name1');
        $data->address = $request->get('street_name') . ',' . $request->get('ward_name1') . ',' . $request->get('district_name1') . ',' . $request->get('province_name1');


        $data->save();
        $request->session()->flash('msg', ['info', 'Thay đổi địa chỉ thành công!']);
        return redirect()->back();
    }

    public function returnAddress(Request $request, $id)
    {
        $data = Orders::find($id);
        $return_phone = $request->get('return_phone') ?? $data->to_name;
        $return_province = $request->get('province_id') ?? $data->province_id;
        $return_province_name = $request->get('province_name1')??$data->province_name;
        $return_district = $request->get('district_id') ?? $data->district_id;
        $return_district_name = $request->get('district_name1') ?? $data->district_name;
        $return_ward = $request->get('ward_id') ?? $data->ward_id;
        $return_ward_name = $request->get('ward_name1') ?? $data->ward_name;
        if (!empty($request->get('street_name'))) {
            $street_name = $request->get('street_name') . ',' . $request->get('ward_name1') . ',' . $request->get('district_name1') . ',' . $request->get('province_name1');
        } else {
            $street_name = $data->address;
        }



        $data->return_phone = $return_phone;
        $data->return_province = $return_province;
        $data->return_province_name = $return_province_name;
        $data->return_district = $return_district;
        $data->return_district_name = $return_district_name;
        $data->return_ward = $return_ward;
        $data->return_ward_name = $return_ward_name;
        $data->return_address = $street_name;

        $OrderItems = OrdersDetail::where('order_id', $data->id)->get();
        $externalItems = [];
        foreach ($OrderItems as $OrderItem) {
            $externalItems [] = [
                "name" => $OrderItem->product_name,
                "code" => $OrderItem->product_code,
                "quantity" => $OrderItem->quantity,
            ];
        }
        $Params = Common::ParamsInStatus($data, $externalItems);
        if (empty($Params['body'])) {
            return redirect()->back()->with('error', 'Trạng thái đơn hàng khng hợp lệ.');
        }
        $return = GHN::updateOrder($Params);

        if ($return['r'] == 0) {
            $data->save();
            return redirect()->back()->with('success', 'Cập nhập thành công.');

        } else {
            return redirect()->back()->with('error', 'Trạng thái đơn hàng khng hợp lệ.');

        }
    }


    public function cancel(Request $request, $id)
    {
        $data = Orders::find($id);
        $cancel = GHN::CancelOrder($data);
        if ($cancel['r'] == 0) {
            $request->session()->flash('msg', ['info', 'Đã hủy thành công!']);
            $data->status = 9;
            $data->statusName = 'cancel';
            $data->save();
            return redirect()->back();
        } else {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $cancel['msg']]);
            return redirect()->back();
        }

    }

    public function cancellist(Request $request)
    {

        $ids = $request->get('ids');
        $arrays = ['ready_to_pick', 'picking', 'money_collect_picking'];
        try {
            foreach ($ids as $id) {
                $data = Orders::find($id);
                if ($data->order_code and in_array($data->statusName, $arrays)) {
                    $cancel = GHN::CancelOrder($data);
                    $data->status = 9;
                    $data->statusName = 'cancel';
                    $data->save();
                } elseif ($data->statusName == 'create_order') {
                    $data->delete();
                }

            }
            return \Response::json(['e' => 0, 'r' => $ids]);
        } catch (\Exception $e) {
            return \Response::json(['e' => 0, 'r' => $e]);

        }

    }

    public function updateOrder(Request $request, $id)
    {

        $order = Orders::find($id);
//
        $OrderItems = OrdersDetail::where('order_id', $order->id)->get();
        $externalItems = [];
        foreach ($OrderItems as $OrderItem) {
            $externalItems [] = [
                "name" => $OrderItem->product_name,
                "code" => $OrderItem->product_code,
                "quantity" => $OrderItem->quantity,
            ];
        }


        $Params = Common::ParamsInStatus($order, $externalItems);
        if (empty($Params['body'])) {
            return redirect()->back()->with('error', 'Trạng thái đơn hàng khng hợp lệ.');
        }
        $data = GHN::updateOrder($Params);
        $data1 = GHN::ChangeCOD($order);



        if ($data['r'] == 0 && $data1['r'] == 0) {
            $request->session()->flash('msg', ['info', 'Cập nhập đơn hàng thành công!']);
        }
        else if ($data1['r'] == 1) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $data1['msg']]);
        }
        return redirect()->route('backend.orders.create', $id);
    }

    public function UpdateCODamount(Request $request, $id)
    {

        $order = Orders::find($id);


        $data = GHN::ChangeCOD($order);

        if ($data['r'] == 0) {
            $request->session()->flash('msg', ['info', 'Cập nhập giá thành công!']);
        } else {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $data['msg']]);
        }
        return redirect()->back();
    }

    public function returnOrder($id)
    {
        $order = Orders::find($id);

        $data = GHN::ReturnOrder($order);

        if (  $data['data'][0]['result'] == false) {
            return redirect('/admin/orders?key=6')->with('error', 'Có lổi xẩy ra.'.$data['data'][0]['message']  );
        } else {
            return redirect('/admin/orders?key=6')->with('success', 'Đã chuyển sang trả hàng.');
        }


    }

    public function storingOrder($id)
    {
        $order = Orders::find($id);

        $data = GHN::StoringOrder($order);

        if (  $data['data'][0]['result'] == false) {
            return redirect('/admin/orders?key=6')->with('error', 'Có lổi xẩy ra.'.$data['data'][0]['message']  );
        } else {
            return redirect('/admin/orders?key=6')->with('success', 'Đã chuyển sang trả hàng.');
        }


    }

}
