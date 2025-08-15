<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ExportOrder;
use App\Exports\ReportsSupplier;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\BaseBackendController;
use App\Jobs\PushNotification;
use App\Models\Address;
use App\Models\Branch;
use App\Models\CoreUsers;
use App\Models\Files;
use App\Models\HistoryOrder;
use App\Models\Notification;
use App\Models\Orders;
use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use App\Models\OrdersDetail;
use App\Models\ProductType;
use App\Models\Warehouse;
use App\Models\DiscountCode;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Settings;
use App\Utils\Common;
use App\Utils\Common as Utils;
use App\VNShipping\Ahamove;
use App\VNShipping\GHN;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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
            'limit' => config('constants.item_per_page_admin'),
        ), $request->all());
        $deletedOrders = Orders::whereNull('to_name')->delete();

        $objModel = new Orders();

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data_list = $objModel->get_by_where($params);

        $start = ($data_list->currentPage() - 1) * $filter['limit'];

        $this->_data['data_list'] = $data_list;
        $this->_data['next_page_url'] = $params['pagin_path'];


        $data_distinct = $objModel->get_by_where_distinct($params);


        $this->_data['start'] = $start;
        $this->_data['filter'] = $filter;
        $this->_data['data_distinct'] = $data_distinct;
        return view('backend.orders.index', $this->_data);
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
            'key' => null,
            'limit' => config('constants.item_per_page_admin'),
        ));
        $deletedOrders = Orders::whereNull('to_name')->delete();

        $objModel = new Orders();

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data_list = $objModel->get_by_where($params);

        $start = ($data_list->currentPage() - 1) * $filter['limit'];

        $this->_data['data_list'] = $data_list;


        $this->_data['start'] = $start;
        $this->_data['filter'] = $filter;
        return $this->returnResult($this->_data['data_list']);
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


//                        foreach ($data as $item) {
//                            $params['user_id'] = $user['id'];
//                            $params['fullname'] = $user['fullname'];
//                            $params['phone'] = $user['phone'];
//                            $params['email'] = $user['email'];
//                            $params['address'] = $address;
//                            $params['province_id']= $user['province_id'];
//                            $params['district_id'] = $user['district_id'];
//                            $params['ward_id'] = $user['ward_id'];
//                            /*Insert vào data*/
//                            $params['to_name'] = $item[0];
//                            $params['to_phone'] = (String)$item[1];
//                            $params['to_address'] = $item[2];
//                            $params['to_province'] = '';
//                            $params['to_province_name'] = $item[3];
//                            $params['to_district'] = '';
//                            $params['to_district_name'] = $item[4];
//                            $params['to_ward'] = '';
//                            $params['to_ward_name'] = $item[5];
//                            $params['required_note'] = $item[6];
//                            $params['weight'] = $item[7];
//                            $params['length'] =$item[8];
//                            $params['width'] = $item[9];
//                            $params['height'] = $item[10];
//                            $params['insurance_value'] = $item[11];
//                            $params['payment_method'] = $item[12];
//                            $params['order_code_custom'] = $item[13];
//                            $params['note'] = $item[15];
//                            $params['caGiaohang'] = $item[16];;
//                            $params['order_code'] = '';
//                            $params['cod_amount'] = $item[18];
//                            $params['cod_failed_amount'] = $item[17];
//                            $params['product_type'] =Branch::where('id', $AD->product_type)->first()->shopId ?? '4521661';
//
//                            $order = Orders::create($params);
//                            $orderDetails = OrdersDetail::create([
//                                'order_id' =>  $order->id,
//                                'product_voluem' => 200,
//                                'product_name' => $item[14],
//                                'quantity' => 1,
//                            ]);
//
//                            $externalItems [] = [
//                                "name" => $item[14],
//                                "code" =>  '',
//                                "quantity" => 1,
//                            ];
//
//                            $PreviewOrder = GHN::Order($params, $externalItems);
//
//
//                            $detail = GHN::Detail($PreviewOrder['data']['data']['order_code']);
//
//                            $order->order_code = $PreviewOrder['data']['data']['order_code'];
//                            $order->total_fee = $PreviewOrder['data']['data']['fee']['main_service'];
//                            $order->statusName = $detail['data']['status'];
//                            $order->save();
//
//                        }


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

//        $address = Common::FullAddress($AD);
        $address = $AD->street_name . ',' . $AD->ward_name . ',' . $AD->district_name . ',' . $AD->province_name;

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
            'required_note' => 'KHONGCHOXEMHANG',


            'product_type' => Branch::where('id', $AD->product_type)->first()->shopId ?? '4521661',
            'product_type_cost' => \App\Models\SettingFee::get_by_where($user['id'], Branch::where('id', $AD->product_type)->first()->shopId)->cost ?? 0,
            'to_district' => 1766,
            'length' => 10,
            'width' => 10,
            'height' => 10,
            'weight' => 1,
            'statusName' => 'create_order',
            'cod_amount' => 0,
            'cod_failed_amount' => 0,
            'insurance_value' => 0,
        ]);

        $orderDetails = OrdersDetail::create([
            'order_id' => $order->id,
            'product_voluem' => 1,
            'quantity' => 1,
        ]);

        return redirect()->route('backend.orders.create', $order->id);

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
        $this->_data['shopAddress'] = $order->address;
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
        $data = Orders::whereIn('id', $arrays)->get();
        $date = date('d-m-Y');
        return \Maatwebsite\Excel\Facades\Excel::download(new  ExportOrder($data), 'Don_hang_' . $date . '.xlsx');
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
//        if (empty($orderDetails)) {
//
//            $oRder = Orders::where('id', $id)->first();
//            if (!empty($oRder->to_province) && !empty($oRder->to_district)) {
//                $GetFeeParams = [
//                    'weight' => $oRder->weight,
//                    'from_district_id' => $oRder->district_id,
//                    'to_district_id' => $oRder->to_district,
//                    'to_ward_code' => $oRder->to_ward,
//                    'shopId' => $oRder->product_type,
//                    'insurance_value' => $oRder->insurance_value,
//                ];
//                $service_fee = GHN::GetFee($GetFeeParams);
//
//                if ($service_fee['r'] == 0) {
//                    $oRder->total_fee = $service_fee['data']; // chưa công phí của hàng
//                    $oRder->save();
//                }
//                $sumVoluem = $oRder->weight;
//                return \Response::json(['e' => $orderDetails, 'r'=>$sumVoluem, 'fee' => $service_fee]);
//            }
//
//        } else {
//
//            $params['product_name'] = $request->get('product_name', $orderDetails->product_name);
//            $params['product_code'] = $request->get('product_code', $orderDetails->product_code);
//            $params['product_voluem'] = $request->get('product_voluem', $orderDetails->product_voluem);
//            $params['quantity'] = $request->get('product_quantity', $orderDetails->quantity);
//            $orderDetails->update($params);
//            $sumVoluem = OrdersDetail::where('order_id', $orderDetails->order_id)->sum('product_voluem');
//
//            $oRder = Orders::where('id', $orderDetails->order_id)->first();
//            $oRder->weight = $sumVoluem;
//            $oRder->save();
//
//            $GetFeeParams = [
//                'weight' => $sumVoluem,
//                'from_district_id' => $oRder->district_id,
//                'to_district_id' => $oRder->to_district,
//                'to_ward_code' => $oRder->to_ward,
//                'shopId' => $oRder->product_type,
//                'insurance_value' => $oRder->insurance_value,
//            ];
//            $service_fee = GHN::GetFee($GetFeeParams);
//            if ($service_fee['r'] == 0) {
//                $oRder->total_fee = $service_fee['data']; // chưa công phí của hàng
//                $oRder->save();
//            }
//            return \Response::json(['e' => $orderDetails, 'r'=>$GetFeeParams, 'fee' => $service_fee]);
//        }


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
        $order->update($params);
        return \Response::json(['e' => 0, 'r' => $order]);
    }

    public function ordersPreview(Request $request)
    {
        $order = Orders::find($request->get('id'));

        $OrderItems = OrdersDetail::where('order_id', $order->id)->get();
        $externalItems = [];
        foreach ($OrderItems as $OrderItem) {
            $externalItems [] = [
                "name" => $OrderItem->product_name,
                "code" => $OrderItem->product_code,
                "quantity" => 5,
            ];
        }

        $PreviewOrder = GHN::GetPreview($order, $externalItems);


        return \Response::json(['e' => 0, 'r' => $PreviewOrder]);
    }

    public function orderscheckout(Request $request, $id)
    {


        $order = Orders::find($id);

        $OrderItems = OrdersDetail::where('order_id', $order->id)->get();
        $externalItems = [];
        foreach ($OrderItems as $OrderItem) {
            $externalItems [] = [
                "name" => $OrderItem->product_name,
                "code" => $OrderItem->product_code,
                "quantity" => $OrderItem->quantity,
            ];
        }

        $PreviewOrder = GHN::Order($order, $externalItems);

        if ($PreviewOrder['r'] == 1) {
            // $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại! '. $PreviewOrder['msg']]);

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
            $order->main_service = $Updatefee['data']['detail']['main_service'];
            $order->insurance = $Updatefee['data']['detail']['insurance'];
//            $order->cod_failed_amount = $Updatefee['data']['detail']['cod_failed_fee'];


            // return \Response::json(['e' => 0, 'r' => 'Tạo đơn hàng thành công. Mã đơn hàng '. $Updatefee['data']['detail']['main_service']]);


            $order->save();
            HistoryOrder::create([
                'name' => $order->phone,
                'content' => 'Đã tao đơn hàng.',
                'order_id' => $order->id,
            ]);
//            $request->session()->flash('msg', ['info',  $PreviewOrder['data']['message_display']]);
            return \Response::json(['e' => 0, 'r' => 'Tạo đơn hàng thành công. Mã đơn hàng ' . $PreviewOrder['data']['data']['order_code']]);
//            return \Response::json(['e' => 0, 'r' => 'Tạo đơn hàng thành công. Mã đơn hàng '.$Updatefee]);

        }


//        return redirect()->route('backend.orders.index');
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



//    public function detail(Request $request, $id)
//    {
//
//        $data = Orders::get_detail($id);
//        // $order_detail = OrdersDetail::where('order_id', $id)->get();
//        /*todo: tim ban theo don hang */
//        $ban = Warehouse::where('id', $data->warehouse_id)->first();
//
//        if (empty($data)) {
//            $request->session()->flash('msg', ['danger', 'Dữ liệu không tồn tại!']);
//            return redirect($this->_ref ? $this->_ref : Route('backend.orders.index'));
//        }
//        if ($request->isMethod('POST')) {
//
//
//
//
//            if (!in_array($data->status, [4, 5])) {
//
//                /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */
//                $ban = Warehouse::where('id', $data->warehouse_id)->first();
//                $branche = Branch::where('id', $data->branch_id)->first();
//                $NameBan = "";
//                if(!empty($ban)){
//                    $NameBan = "Chi nhánh : ". $branche->name ." - Số Bàn : " . $ban->name;
//                }else{
//                    $NameBan = "Khách hàng : ". $data->fullname;
//                }
//                $xachnhan = "Xác Nhận";
//               /* if(!empty($ban)){
//                    $NameBan = " Số Bàn : " . $ban->name;
//                }else{
//                    $NameBan = "Khách hàng : ". $data->fullname;
//                }*/
//
//
//
//                \DB::beginTransaction();
//                try {
//
//                    $status = $request->get('status', $data->status);
//
//                    $data->status = $status;
//
//                    $data->save();
//
//                    /*todo: cap nhan lai trang thai cua ban*/
//                    if (!empty($ban)) {
//                        $ban->status = $status;
//                        $ban->order_id = $id;
//                        $ban->save();
//                    }
//
//
//                    if ($status == 4) {
//                        $xachnhan = "Thanh Toán";
//                        /*todo: cap nhan lai trang thai cua ban*/
//                        if (!empty($ban)) {
//                            $ban->status = 0;
//                            $ban->order_id = 0;
//                            $ban->save();
//                        }
//
//                        if (!empty($data->user_id)) {
//
//                            $user = CoreUsers::find($data->user_id);
//                            // Tích điểm, tổng thanh toán đã mua, level up
//
//                            //Set thành viên
//                            $total_expense = $user->balance * 0.00001;
//                            $after_expense = $user->expense + $total_expense;
//                            $user->expense += $after_expense;
//
//                            if( $user->expense >= 1 &&  $user->expense <= 10){
//                                $user->account_type= 1;
//                            }
//                            else if( $user->expense >= 11 &&  $user->expense <=49){
//                                $user->account_type= 2;
//                            }
//                            else if( $user->expense >= 50 &&  $user->expense <=150){
//                                $user->account_type= 3;
//                            }
//                            else if( $user->expense >= 151 &&  $user->expense <=300){
//                                $user->account_type= 4;
//                            }
//                            else if( $user->expense >=301){
//                                $user->account_type= 5;
//                            }
//                            $user->save();
//
//
////                            if ($after_expense >= $this->_data['MEMBER_SILVER']['setting_value'] && $after_expense < $this->_data['MEMBER_GOLD']['setting_value']) {
////                                $user->account_type = CoreUsers::ACCOUNT_TYPE_SILVER;
////                            } else if ($after_expense >= $this->_data['MEMBER_GOLD']['setting_value'] && $after_expense < $this->_data['MEMBER_DIAMOND']['setting_value']) {
////                                $user->account_type = CoreUsers::ACCOUNT_TYPE_GOLD;
////                            } else if ($after_expense >= $this->_data['MEMBER_DIAMOND']['setting_value'] && $after_expense < $this->_data['MEMBER_VIP']['setting_value']) {
////                                $user->account_type = CoreUsers::ACCOUNT_TYPE_DIAMOND;
////                            } else if ($after_expense > $this->_data['MEMBER_VIP']['setting_value']) {
////                                $user->account_type = CoreUsers::ACCOUNT_TYPE_VIP;
////                            } else {
////                                $user->account_type = CoreUsers::ACCOUNT_TYPE_NEW;
////                            }
//
//
//                            // Tích điểm
//                            $total_point = 0;
//
//                            if ($user->account_type == CoreUsers::ACCOUNT_TYPE_NEW) {
//                                $total_point = $data->total_price * 0.00001;
//                            } else if ($user->account_type == CoreUsers::ACCOUNT_TYPE_SILVER) {
//                                $total_point = $data->total_price * 0.00003;
//                            } else if ($user->account_type == CoreUsers::ACCOUNT_TYPE_GOLD) {
//                                $total_point = $data->total_price * 0.00005;
//                            } else if ($user->account_type == CoreUsers::ACCOUNT_TYPE_DIAMOND) {
//                                $total_point = $data->total_price * 0.00007;
//                            } else if ($user->account_type == CoreUsers::ACCOUNT_TYPE_VIP) {
//                                $total_point = $data->total_price * 0.0001;
//                            }
//                            $user->point += $total_point;
//                            $user->balance += $data->total_price;
//                            $user->save();
//                        }
//
//
//
//                    }
//
//                    //hoàn kho nếu hủy
//                    if ($status == 5) {
//                        $xachnhan = "Hủy";
//                        $order = Orders::find($id);
//                        if (!empty($order->branch_id) and !empty($order->ahamove_type)) {
//                            // Hủy đơn ahamove
//                            $ahamove = new Ahamove();
//                            $shipment_fee = ($ahamove->cancelOrder([
//                                'id' => $order->branch_id,
//                                'order_id' =>$order->ahamove_type,
//                            ]));
//                        }
//                        /*todo: cap nhan lai trang thai cua ban*/
//                        if (!empty($ban)) {
//                            $ban->status = 0;
//                            $ban->order_id = 0;
//                            $order->ahamove_type = '1';
//                            $ban->save();
//                            $order->save();
//                        }
//
//                        foreach ($data->order_details as $v) {
//                            if ($v->product_variation_id) {
//                                ProductVariation::update_inventory([
//                                    'id'         => $v->product_variation_id,
//                                    'product_id' => $v->product_id,
//                                    'quantity'   => $v->quantity,
//                                ]);
//                            } else {
//                                Product::update_inventory([
//                                    'product_id' => $v->product_id,
//                                    'quantity'   => $v->quantity,
//                                ]);
//                            }
//                            Product::change_inventory([
//                                'product_id' => $v->product_id,
//                            ]);
//                        }
//
//
//                    }
//
//                    /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */
//
//                    $notify = Notification::create([
//                        'title' => '' . $data->order_code .'_'. $xachnhan ,
//                        'content' => '' . $NameBan .' - Giá : ' . $data->total_price . '  đ ',
//                        'chanel' => 2,
//                        'type' => 1,
//                        'company_id' =>config('constants.company_id'),
//                        'relate_id' => 0,
//                        'from_user_id' => 168,
//                        'to_user_id' => 168,
//                        'order_id' => $data->id,
//                        'user_id_created' => 168,
//                    ]);
//
//                   /* $this->dispatch((new PushNotification($notify))->onQueue('push_notification'));*/
//
//                    /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */
//
//
//
//                    \DB::commit();
//                    $request->session()->flash('msg', ['info', 'Cập nhật trạng thái thành công!']);
//                } catch (\Exception $e) {
//                    \DB::rollBack();
//                    \Log::error('status order ' . $e->getMessage());
//                    $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại' . $e->getMessage()]);
//                }
//
//                return redirect($this->_ref ? $this->_ref : Route('backend.orders.index'));
//            }
//        }
//
//        $this->_data['subtitle'] = 'Chi tiết đơn hàng';
//        $this->_data['data_item'] = $data;
//
//        $warehouse = Warehouse::where('order_id', $data->id)->first();
//        if ($warehouse) {
//            $branch = Branch::where('id', $warehouse->branch_id)->first();
//            $this->_data['data_item']['warehouse'] = $warehouse->name . ' - ' . $branch->name;
//        }else{
//            $this->_data['data_item']['warehouse'] = "N/A";
//        }
//
//        return view('backend.orders.detail', $this->_data);
//    }
//
//
//    public function detailTemp(Request $request, $id)
//    {
//        $data = Orders::get_detail($id);
//        // $order_detail = OrdersDetail::where('order_id', $id)->get();
//        /*todo: tim ban theo don hang */
//        $ban = Warehouse::where('id', $data->warehouse_id)->first();
//
//        if (empty($data)) {
//            $request->session()->flash('msg', ['danger', 'Dữ liệu không tồn tại!']);
//            return redirect($this->_ref ? $this->_ref : Route('backend.orders.index'));
//        }
//        if ($request->isMethod('POST')) {
//
//
//
//
//            if (!in_array($data->status, [4, 5])) {
//
//                /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */
//               /* $notify = Notification::create([
//                    'title' => 'Đơn hàng #' . $data->order_code . ' đã được đặt ' ,
//                    'content' => 'Đơn hàng #' . $data->order_code . '  đã được đặt ',
//                    'chanel' => 2,
//                    'company_id' =>config('constants.company_id'),
//                    'relate_id' => 0,
//                    'from_user_id' => 168,
//                    'to_user_id' => 168,
//                    'order_id' => $data->id,
//                    'user_id_created' => 168,
//                ]);*/
//                /* $notify = Notification::create([
//                   'title' => 'Bạn có đơn hàng mới' ,
//                   'content' => 'Bạn có đơn hàng mới',
//                   'chanel' => 2,
//                     'type' => 1,
//                   'company_id' =>6,
//                   'relate_id' => 0,
//                   'from_user_id' => 168,
//                   'to_user_id' => 168,
//                   'order_id' => 0,
//                   'user_id_created' => 168,
//               ]);*/
//                /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */
//                $ban = Warehouse::where('id', $data->warehouse_id)->first();
//                $branche = Branch::where('id', $data->branch_id)->first();
//                $NameBan = "";
//                if(!empty($ban)){
//                    $NameBan = "Chi nhánh : ". $branche->name ." - Số Bàn : " . $ban->name;
//                }else{
//                    $NameBan = "Khách hàng : ". $data->fullname;
//                }
//
//                /* if(!empty($ban)){
//                     $NameBan = " Số Bàn : " . $ban->name;
//                 }else{
//                     $NameBan = "Khách hàng : ". $data->fullname;
//                 }*/
//                $notify = Notification::create([
//                    'title' => 'Đơn hàng #' . $data->order_code . ' đã thay đổi trạng thái' ,
//                    'content' => '' . $NameBan .' - Giá : ' . $data->total_price . '  đ ',
//                    'chanel' => 2,
//                    'type' => 1,
//                    'company_id' =>config('constants.company_id'),
//                    'relate_id' => 0,
//                    'from_user_id' => 168,
//                    'to_user_id' => 168,
//                    'order_id' => $data->id,
//                    'user_id_created' => 168,
//                ]);
//
//               /* $this->dispatch((new PushNotification($notify))->onQueue('push_notification'));*/
//
//
//                \DB::beginTransaction();
//                try {
//
//                    $status = $request->get('status', $data->status);
//
//                    $data->status = $status;
//
//                    $data->save();
//
//                    /*todo: cap nhan lai trang thai cua ban*/
//                    if (!empty($ban)) {
//                        $ban->status = $status;
//                        $ban->order_id = $id;
//                        $ban->save();
//                    }
//
//
//                    if ($status == 4) {
//                        /*todo: cap nhan lai trang thai cua ban*/
//                        if (!empty($ban)) {
//                            $ban->status = 0;
//                            $ban->order_id = 0;
//                            $ban->save();
//                        }
//
//                        if (!empty($data->user_id)) {
//
//                            $user = CoreUsers::find($data->user_id);
//                            // Tích điểm, tổng thanh toán đã mua, level up
//                            //Set thành viên
//                            $after_expense = $user->expense + $data->total_price;
//                            $user->expense = $after_expense;
//
//                            if ($after_expense >= $this->_data['MEMBER_SILVER']['setting_value'] && $after_expense < $this->_data['MEMBER_GOLD']['setting_value']) {
//                                $user->account_type = CoreUsers::ACCOUNT_TYPE_SILVER;
//                            } else if ($after_expense >= $this->_data['MEMBER_GOLD']['setting_value'] && $after_expense < $this->_data['MEMBER_DIAMOND']['setting_value']) {
//                                $user->account_type = CoreUsers::ACCOUNT_TYPE_GOLD;
//                            } else if ($after_expense >= $this->_data['MEMBER_DIAMOND']['setting_value'] && $after_expense < $this->_data['MEMBER_VIP']['setting_value']) {
//                                $user->account_type = CoreUsers::ACCOUNT_TYPE_DIAMOND;
//                            } else if ($after_expense > $this->_data['MEMBER_VIP']['setting_value']) {
//                                $user->account_type = CoreUsers::ACCOUNT_TYPE_VIP;
//                            } else {
//                                $user->account_type = CoreUsers::ACCOUNT_TYPE_NEW;
//                            }
//                            // Tích điểm
//                            $total_point = 0;
//
//                            if ($user->account_type == 0) {
//                                $total_point = $data->total_price * 0.000001;
//                            } else if ($user->account_type == CoreUsers::ACCOUNT_TYPE_SILVER) {
//                                $total_point = $data->total_price * 0.000003;
//                            } else if ($user->account_type == CoreUsers::ACCOUNT_TYPE_GOLD) {
//                                $total_point = $data->total_price * 0.000005;
//                            } else if ($user->account_type == CoreUsers::ACCOUNT_TYPE_DIAMOND) {
//                                $total_point = $data->total_price * 0.000007;
//                            } else if ($user->account_type == CoreUsers::ACCOUNT_TYPE_VIP) {
//                                $total_point = $data->total_price * 0.00001;
//                            }
//                            $user->point += $total_point;
//                            $point = $user->point;
//                            $user->expense = $point;
//                            $user->balance += $data->total_price;
//                            $user->save();
//                        }
//                    }
//                    //hoàn kho nếu hủy
//                    if ($status == 5) {
//                        /*todo: cap nhan lai trang thai cua ban*/
//                        if (!empty($ban)) {
//                            $ban->status = 0;
//                            $ban->order_id = 0;
//                            $ban->save();
//                        }
//
//                        foreach ($data->order_details as $v) {
//                           if ($v->product_variation_id) {
//                                ProductVariation::update_inventory([
//                                    'id'         => $v->product_variation_id,
//                                    'product_id' => $v->product_id,
//                                    'quantity'   => $v->quantity,
//                                ]);
//                            } else {
//                                Product::update_inventory([
//                                    'product_id' => $v->product_id,
//                                    'quantity'   => $v->quantity,
//                                ]);
//                            }
//                            Product::change_inventory([
//                                'product_id' => $v->product_id,
//                           ]);
//                        }
//                    }
//
//                    \DB::commit();
//                    $request->session()->flash('msg', ['info', 'Cập nhật trạng thái thành công!']);
//                } catch (\Exception $e) {
//                    \DB::rollBack();
//                    \Log::error('status order ' . $e->getMessage());
//                    $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại' . $e->getMessage()]);
//                }
//
//                return redirect($this->_ref ? $this->_ref : Route('backend.orders.index'));
//            }
//      }
//
//        $this->_data['subtitle'] = 'Chi tiết đơn hàng';
//        $this->_data['data_item'] = $data;
//
//        $warehouse = Warehouse::where('order_id', $data->id)->first();
//        if ($warehouse) {
//            $branch = Branch::where('id', $warehouse->branch_id)->first();
//            $this->_data['data_item']['warehouse'] = $warehouse->name . ' - ' . $branch->name;
//        }else{
//            $this->_data['data_item']['warehouse'] = "N/A";
//        }
//
//        return view('backend.orders.detailTemp', $this->_data);
//    }
//
//
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

//        dd($request->all());

        $data->save();
        $request->session()->flash('msg', ['info', 'Thay đổi địa chỉ thành công!']);
        return redirect()->back();
    }

    public function returnAddress(Request $request, $id)
    {
        $data = Orders::find($id);
        $data->return_phone = $request->get('return_phone');
        $data->return_province = $request->get('province_id');
        $data->return_province_name = $request->get('province_name1');
        $data->return_district = $request->get('district_id');
        $data->return_district_name = $request->get('district_name1');
        $data->return_ward = $request->get('ward_id');
        $data->return_ward_name = $request->get('ward_name1');
        $data->return_address = $request->get('street_name') . ',' . $request->get('ward_name1') . ',' . $request->get('district_name1') . ',' . $request->get('province_name1');
//        dd($request->all());
        $data->save();
//        $request->session()->flash('msg', ['info', 'Thay đổi địa chỉ thành công!']);
//        dd($request->all());
        return redirect()->back();
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

        $data = GHN::updateOrder($order);
        dd($data);
        $data1 = GHN::ChangeCOD($order);

        if ($data['r'] == 0) {
            $request->session()->flash('msg', ['info', 'Cập nhập đơn hàng thành công!']);
        }
        if ($data1['r'] == 0) {
            $request->session()->flash('msg', ['info', 'Cập nhập đơn hàng thành công!']);
        } else if ($data['r'] == 1) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $data['msg']]);
        }else if ($data1['r'] == 1) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $data1['msg']]);
        }
        return redirect()->back();
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

//
//    public function export(Request $request)
//    {
//        if ($request->ids) {
//            $ids = !is_array($request->get('ids')) ? explode(',', $request->get('ids')) : $request->get('ids');
//            $orders = Orders::whereIn('id', $ids)->get();
//            $spreadsheet = new Spreadsheet();
//            $sheet = $spreadsheet->getActiveSheet();
//            $styleArray = array(
//                'font'      => array(
//                    'bold' => true,
//                    'size' => 16,
//                ),
//                'alignment' => array(
//                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
//                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
//                ),
//                'borders'   => array(
//                    'bottom' => array(
//                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
//                        'color'       => array('rgb' => '333333'),
//                    ),
//                ),
//            );
//            $styleName = array(
//                'font' => array(
//                    'bold' => true,
//                    'size' => 20,
//                ),
//            );
//            $today = date('d-m-Y');
//
//            $spreadsheet->getActiveSheet()->getStyle('C1:H1')->applyFromArray($styleArray)->getFont()->getColor()
//                ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);
//            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(16);
//            $spreadsheet->getActiveSheet()->getDefaultRowDimension()->setRowHeight(24);
//            $spreadsheet->getDefaultStyle()->getFont()->setSize(16);
//            $sheet->setCellValue('C1', 'Mã đơn hàng');
//            $sheet->setCellValue('D1', 'Họ&Tên');
//            $sheet->setCellValue('E1', 'SĐT');
//            $sheet->setCellValue('F1', 'Ngày đặt');
//            $sheet->setCellValue('G1', 'Số tiền');
//            $sheet->setCellValue('H1', 'Tình trạng');
//            $rows = 2;
//
//            foreach ($orders as $k => $v) {
//                $sheet->setCellValue('C' . $rows, $v->order_code);
//                $sheet->setCellValue('D' . $rows, $v->fullname);
//                $sheet->setCellValue('E' . $rows, $v->phone);
//                $sheet->setCellValue('F' . $rows, $v->created_at);
//                $sheet->setCellValue('G' . $rows, number_format($v->total_price) . 'VND');
//                $sheet->setCellValue('H' . $rows, \App\Models\Orders::$status[$v->status]);
//                $rows++;
//            }
//            $writer = new Xlsx($spreadsheet);
//            $filename = 'LỊCH SỬ ĐƠN HÀNG: ' . date('d-m-Y_H-i-s') . '.xlsx';
//            header('Content-Type: application/vnd.ms-excel');
//            header('Content-Disposition: attachment;filename="' . $filename . '"');
//            header('Cache-Control: max-age=0');
//            $writer->save('php://output');
//        }
//        return back();
//    }
//
//    public static function getIdOrderByIdBan($id)
//    {
//        $ban = Orders::where('warehouse_id', $id)->orderBy('id', 'DESC')->first();
//        $id_order = 0;
//        if (!empty($ban)) {
//            $id_order = $ban->id;
//        } else {
//            $id_order = 0;
//        }
//        return $id_order;
//    }
//
//    public static function getUserByIdOrder($id)
//    {
//        $user = Orders::join('lck_core_users', 'lck_core_users.id', '=', 'lck_orders.user_id')
//            ->where('lck_orders.id', $id)
//            ->select(['lck_core_users.*'])->orderBy('id', 'DESC')->first();
//        $user_category_id = 0;
//        if (!empty($user)) {
//            $user_category_id = $user->user_category_id;
//        } else {
//            $user_category_id = 0;
//        }
//
//        return $user_category_id;
//    }
//
//    public function updatePayment(Request $request)
//    {
//        $params = $request->only(
//            'fullname',
//            'phone',
//            'email',
//            'address',
//            'street',
//            'province_id',
//            'district_id',
//            'ward_id',
//            'status',
//            'company_id',
//            'note',
//            'note_customer',
//            'total_price',
//            'warehouse_id',
//            'branch_id',
//            'discount_order_total'
//        );
//
//        $order = Orders::create($params);
//        $order['order_code'] = 'DH' . $order['id'];
//        $order['payment_type'] = 1;
//        $order['user_id'] = Auth()->guard('backend')->user()->id;
//        $order->save();
//
//        $warehouse = Warehouse::find($order['warehouse_id']);
//
//        $warehouse['status'] = 1;
//        $warehouse['order_id'] = $order['id'];
//        $warehouse->save();
//
//        // create order detail
//        $newProductsSelected = $request->newProductsSelected;
//
//        foreach ($newProductsSelected as $productSelect) {
//
//            $product = Product::find($productSelect['id']);
//            $paramsOrderDetail['order_id'] = $order['id'];
//            $paramsOrderDetail['specifications'] = $product['specifications'];
//            $paramsOrderDetail['product_id'] = $product['id'];
//            $paramsOrderDetail['product_code'] = $product['product_code'];
//            $paramsOrderDetail['thumbnail_path'] = $product['thumbnail_image'];
//            $paramsOrderDetail['title'] = $product['title'];
//            $paramsOrderDetail['description'] = $product['description'];
//            $paramsOrderDetail['price'] = $product['price'];
//            $paramsOrderDetail['quantity'] = $productSelect['quantity'];
//            $paramsOrderDetail['price_after_discount'] = $productSelect['price_after_discount'];
//            $paramsOrderDetail['amount_discount'] = $productSelect['amount_discount'];
//            $paramsOrderDetail['total_price'] = (int)$productSelect['price_after_discount'] * (int)$productSelect['quantity'];
//
//            OrdersDetail::create($paramsOrderDetail);
//        }
//
//        return ResponseHelper::success('Thành công');
//    }


//    public function updateOrder(Request $request)
//    {
//        $order = Orders::find($request->id_order);
//        $params = $request->only(
//            'fullname',
//            'phone',
//            'email',
//            'address',
//            'street',
//            'province_id',
//            'district_id',
//            'ward_id',
//            'status',
//            'company_id',
//            'note',
//            'note_customer',
//            'total_price',
//            'warehouse_id',
//            'branch_id',
//            'discount_order_total'
//        );
//        $order->update($params);
//        $newProductsSelected = $request->newProductsSelected;
//        $a = [];
//
//        foreach ($newProductsSelected as $productSelect) {
//            $order_detail = OrdersDetail::where('id',$productSelect['id'])->first();
//            if (!empty($order_detail)) {
//                $product = Product::where('id',$order_detail->product_id)->first();
//                $paramsOrderDetail['specifications'] = $product['specifications'];
//                $paramsOrderDetail['product_id'] = $product['id'];
//                $paramsOrderDetail['product_code'] = $product['product_code'];
//                $paramsOrderDetail['thumbnail_path'] = $product['thumbnail_image'];
//                $paramsOrderDetail['title'] = $product['title'];
//                $paramsOrderDetail['description'] = $product['description'];
//                $paramsOrderDetail['price'] = $product['price'];
//                $paramsOrderDetail['quantity'] = $productSelect['quantity'];
//                $paramsOrderDetail['price_after_discount'] = $productSelect['price_after_discount'];
//                $paramsOrderDetail['amount_discount'] = $productSelect['amount_discount'];
//                $paramsOrderDetail['total_price'] = (int)$productSelect['price_after_discount'] * (int)$productSelect['quantity'];
//
//                $order_detail->update($paramsOrderDetail);
//            } else {
//                $product = Product::find($productSelect['id']);
//                $paramsOrderDetail['order_id'] = $order['id'];
//                $paramsOrderDetail['specifications'] = $product['specifications'];
//                $paramsOrderDetail['product_id'] = $product['id'];
//                $paramsOrderDetail['product_code'] = $product['product_code'];
//                $paramsOrderDetail['thumbnail_path'] = $product['thumbnail_image'];
//                $paramsOrderDetail['title'] = $product['title'];
//                $paramsOrderDetail['description'] = $product['description'];
//                $paramsOrderDetail['price'] = $product['price'];
//                $paramsOrderDetail['quantity'] = $productSelect['quantity'];
//                $paramsOrderDetail['price_after_discount'] = $productSelect['price_after_discount'];
//                $paramsOrderDetail['amount_discount'] = $productSelect['amount_discount'];
//                $paramsOrderDetail['total_price'] = (int)$productSelect['price_after_discount'] * (int)$productSelect['quantity'];
//
//                OrdersDetail::create($paramsOrderDetail);
//            }
//
//         }
//
//
//
//        return \Response::json($a);
//    }
//
//    public function deleteproduct(Request $request) {
//
//        $id = $request->id;
//        $order_detail = OrdersDetail::findOrFail($id);
//        if (!empty($order_detail)) {
//            $order_detail->delete();
//
//        }
//
//        return \Response::json($order_detail);
//    }
//
//
//    public static function getIdNameProvince($id)
//    {
//        $nameProvince = Province::where('id', $id)->orderBy('id', 'DESC')->first();
//        $tempName = 0;
//        if(!empty($nameProvince)){
//            $tempName = "- ".$nameProvince->name;
//        }else{
//            $tempName = "";
//        }
//        return $tempName;
//    }
//
//    public static function getIdNameDistrict($id)
//    {
//        $nameDistrict = District::where('id', $id)->orderBy('id', 'DESC')->first();
//        $tempName = 0;
//        if(!empty($nameDistrict)){
//            $tempName = "- ".$nameDistrict->name;
//        }else{
//            $tempName = "";
//        }
//        return $tempName;
//    }
//
//    public static function getIdNameWard($id)
//    {
//        $nameWard = Ward::where('id', $id)->orderBy('id', 'DESC')->first();
//        $tempName = 0;
//        if(!empty($nameWard)){
//            $tempName = "- ".$nameWard->name;
//        }else{
//            $tempName = "";
//        }
//        return $tempName;
//    }
//
//    public static function getNameDiscount($code)
//    {
//        $nameDiscount = DiscountCode::where('code', $code)->orderBy('id', 'DESC')->first();
//        $tempName = 0;
//        if(!empty($nameDiscount)){
//            $tempName = $nameDiscount->title;
//        }else{
//            $tempName = "";
//        }
//        return $tempName;
//    }
//
//
//    public static function getNameBanChiNhanh($id_order)
//    {
//
//        $tempName = 0;
//        $warehouse = Warehouse::where('id', $id_order)->first();
//        if ($warehouse) {
//            $branch = Branch::where('id', $warehouse->branch_id)->first();
//            $tempName = $warehouse->name . ' - ' . $branch->name;
//        }else{
//            $tempName = "N/A";
//        }
//        return $tempName;
//
//    }


}
