<?php

namespace App\Http\Controllers\Backend\Branch;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\BaseBackendController;
use App\Http\Requests\Backend\Branch\StoreWarehouseRequest;
use App\Jobs\PushNotification;
use App\Models\Branch;
use App\Models\CoreUsers;
use App\Models\DiscountCode;
use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use App\Models\Notification;
use App\Models\Orders;
use App\Models\OrdersDetail;
use App\Models\OrdersDetailTemp;
use App\Models\OrdersTemp;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\ProductType;
use App\Utils\Category;
use Illuminate\Support\Facades\DB;

class WarehouseController extends BaseBackendController
{

    private $data = [];

    /**
     * BranchController constructor.
     */
    public function __construct()
    {
        $this->data['title'] = 'Bàn';
        $this->data['subtitle'] = 'Bàn';
        parent::__construct();
    }

    public function index(Request $request)
    {


        if ($request->getMethod() == 'GET') {

            if (!empty($request->get('branch_id'))) {
                Cookie::queue('branch_id', $request->get('branch_id'));
               // $request->session()->push('branch_id',$request->get('branch_id'));
                $params['branch_id'] = $request->get('branch_id');
                $this->data['data'] = Warehouse::getAll($params);
                $this->data['list_branch'] = Branch::getAll([]);
                $view = \View::make('backend.warehouse.ajaxList', $this->data);
                $return = [
                    'e' => 0,
                    'r' => $view->render()
                ];
                return \Response::json($return);
            }
        }
        //$value = $request->session()->pull('branch_id', '');
        $value = Cookie::get('branch_id');
        //dd($value1);
        if($value){
           // $params['branch_id'] = $value[0];
            $params['branch_id'] = $value;
            $this->data['data'] = Warehouse::getAll($params);
            $this->data['list_branch'] = Branch::getAll([]);
        }else{
            $this->data['data'] = Warehouse::getAll([]);
            $this->data['list_branch'] = Branch::getAll([]);
        }

        $this->data['start'] = 0;
        $this->data['list_product'] = Product::all();
        $this->data['provinces'] = Province::orderBy('priority', 'asc')->get();

        /* Ca --13-09-22 them danh muc*/
        $product_type_id = null;
        $product_type = ProductType::get_by_where(['assign_key' => true,], ['id', 'name', 'parent_id']);
        $product_type_html = Category::build_select_tree($product_type, 0, '', [$product_type_id]);
        $this->data['product_type_html'] = $product_type_html;
        /* Ca --13-09-22 them danh muc*/

        return view('backend.warehouse.index', $this->data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create()
    {
        $this->data['isEditable'] = false;
        $this->data['warehouse'] = [];
        $this->data['branch'] = Branch::getAll([], 50);
        return view('backend.warehouse.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // $params = $request->all();
        $params['name'] =  $request->get('name');
        $params['branch_id'] =  $request->get('branch_id');

        $branch = Warehouse::create($params);
        if (!$branch) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        $request->session()->flash('msg', ['info', 'Thêm thành công!']);
        return redirect()->route('backend.warehouses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $this->data['isEditable'] = true;
        $this->data['warehouse'] = Warehouse::find($id);
        $this->data['branch'] = Branch::getAll([], 20);
        return view('backend.warehouse.create', $this->data);
    }

    /**
     * Update the specified resource in storage.
     * @param StoreWarehouseRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // $params = $request->all();
        $params['name'] =  $request->get('name');
        $params['branch_id'] =  $request->get('branch_id');

        $branch = Warehouse::find($id);
        if (!$branch) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        $branch->update($params);
        $request->session()->flash('msg', ['info', 'Cập nhật thành công!']);
        return redirect()->route('backend.warehouses.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $id = $request->id ?? 0;
        $branch = Warehouse::find($id);
        if (!$branch) {
            return ResponseHelper::error('Không thể Xóa', []);
        }

        $branch->delete();
        return ResponseHelper::success('Đã xóa thành công', []);
    }

    // public function updateNoteProduct(Request $request)
    // {
    //     $params = $request->only(
    //         'tab_id',
    //         'product_id',
    //         'notes'
    //     );

    //     $tab_id = $params['tab_id'] ?? 0;
    //     $product_id = $params['product_id'] ?? 0;
    //     $notes = $params['notes'] ?? '';
    //     $product = Product::find($product_id);

    //     if (!$product) {
    //         return ResponseHelper::error('Sản phẩm không tồn tại', []);
    //     }

    //     $order = OrdersTemp::getTabId($tab_id);
    //     if (!$order) {
    //         return ResponseHelper::error('Order không tồn tại', []);
    //     }

    //     $orderDetail = OrdersDetailTemp::checkProductOrder($product_id, $order->id);

    //     // Nếu có trước đó trong OrdersDetailTemp
    //     if ($orderDetail) {
    //         $orderDetail->update(['notes' => $notes]);
    //     }

    //     return ResponseHelper::success('Đã xóa thành công', []);
    // }


    // public function updateNoteOrder(Request $request)
    // {
    //     $params = $request->only(
    //         'tab_id',
    //         'note'
    //     );

    //     $tab_id = $params['tab_id'] ?? 0;
    //     $note = $params['note'] ?? '';

    //     $order = OrdersTemp::getTabId($tab_id);
    //     if (!$order) {
    //         return ResponseHelper::error('Order không tồn tại', []);
    //     }

    //     // Nếu có trước đó trong OrdersDetailTemp
    //     $order->update(['note' => $note]);

    //     return ResponseHelper::success('Đã xóa thành công', []);
    // }

    public function searchProduct(Request $request)
    {
        $assign = [
            'products' => Product::get_by_where(['sort' => 'newest', 'limit' => 20, 'keywords' => $request->keywords ?? null]),
        ];

        $returnHTML = view("backend.warehouse.searchProduct")->with($assign)->render();
        $json = [
            'returnHTML' => $returnHTML,
            'keywords' => $request->keywords
        ];
        return ResponseHelper::success('Thành công', $json);
    }

    public function checkCoupon(Request $request)
    {
        $params = $request->only(
            [
                'totalprice',
                'code'
            ]
        );

        $code = DiscountCode::where('code', $params['code'])
            ->where('status', 1)
            ->where('start_date', '<=', date('Y-m-d H:i:s'))
            ->where('end_date', '>=', date('Y-m-d H:i:s'))
            ->first();

        if (!$code) {
            return ResponseHelper::error('Mã giảm giá không hợp lệ', []);
        }

        if ($code->limit <= $code->used_count) {
            return ResponseHelper::error('Mã giảm giá đã hết lượt sử dụng', []);
        }

        $total_amount = $request->get('totalprice') ?? 0;

        $total_reduce = $code->type == 1 ? $code->value : ($code->value * $total_amount) / 100;
        $total_reduce = $total_reduce >= $total_amount ? $total_amount : $total_reduce;

        $data = [
            //'total_reduce' => $request->get('code')
            'total_reduce' => $total_reduce
        ];

        return ResponseHelper::success('Đã thanh toán thành công', $data);
    }

    public function getDanhMuc(Request $request)
    {
        $userid = $request->get('id_user');
        //$tab_id = $request->get('tab_id');
        $assign = [
            'products' => Product::get_by_where(['sort' => 'newest', 'limit' => 20, 'product_type_id' => $userid, 'keywords' => null]),
        ];

        $returnHTML = view("backend.warehouse.searchProduct")->with($assign)->render();
        $json = [
            'returnHTML' => $returnHTML,
            'keywords' => ""
        ];
        return ResponseHelper::success('Thành công', $json);

    }

    public function ajaxDetail(Request $request)
    {
        return ResponseHelper::success('Thành công');
    }

    public function ajaxLoadDistrict(Request $request)
    {
        $province_id = $request->province_id ?? 0;
        $assign = [
            'district' => District::where('province_id', $province_id)->get()
        ];
        $returnHTML['returnHTML'] = view("backend.warehouse.ajaxLoadDistrict")->with($assign)->render();
        return ResponseHelper::success('Thanh cong', $returnHTML['returnHTML']);
    }

    public function ajaxLoadWard(Request $request)
    {
        $district_id = $request->district_id ?? 0;
        $assign = [
            'district' => Ward::where('district_id', $district_id)->get()
        ];
        $returnHTML['returnHTML'] = view("backend.warehouse.ajaxLoadWard")->with($assign)->render();
        return ResponseHelper::success('Thanh cong', $returnHTML['returnHTML']);
    }

    public function detail(Request $request, $id)
    {
        $data = Orders::get_detail($id);

        // $order_detail = OrdersDetail::where('order_id', $id)->get();
        /*todo: tim ban theo don hang */
        $ban = Warehouse::where('id', $data->warehouse_id)->first();

        if (empty($data)) {
            $request->session()->flash('msg', ['danger', 'Dữ liệu không tồn tại!']);
            return redirect($this->_ref ? $this->_ref : Route('backend.warehouses.index'));
        }
        if ($request->isMethod('POST')) {
            if (!in_array($data->status, [4, 5])) {
                \DB::beginTransaction();
                try {

                    /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */
                    $ban = Warehouse::where('id', $data->warehouse_id)->first();
                    $branche = Branch::where('id', $data->branch_id)->first();
                    $NameBan = "";
                    if(!empty($ban)){
                        $NameBan = "Chi nhánh : ". $branche->name ." - Số Bàn : " . $ban->name;
                    }else{
                        $NameBan = "Khách hàng : ". $data->fullname;
                    }
                    $xachnhan = "Xác Nhận";
                    /* if(!empty($ban)){
                         $NameBan = " Số Bàn : " . $ban->name;
                     }else{
                         $NameBan = "Khách hàng : ". $data->fullname;
                     }*/



                    $status = $request->get('status', $data->status);

                    $data->status = $status;

                    $data->save();

                    /*todo: cap nhan lai trang thai cua ban*/
                    if (!empty($ban)) {
                        $ban->status = $status;
                        $ban->order_id = $id;
                        $ban->save();
                    }


                    if ($status == 4) {
                        $xachnhan = "Thanh Toán";
                        /*todo: cap nhan lai trang thai cua ban*/
                        if (!empty($ban)) {
                            $ban->status = 0;
                            $ban->order_id = 0;
                            $ban->save();
                        }

                        if (!empty($data->user_id)) {

                            $user = CoreUsers::find($data->user_id);
                            // Tích điểm, tổng thanh toán đã mua, level up

                            //Set thành viên
                            $after_expense = $user->expense + $data->total_price;
                            $user->expense = $after_expense;

                            if ($after_expense >= $this->_data['MEMBER_SILVER']['setting_value'] && $after_expense < $this->_data['MEMBER_GOLD']['setting_value']) {
                                $user->account_type = CoreUsers::ACCOUNT_TYPE_SILVER;
                            } else if ($after_expense >= $this->_data['MEMBER_GOLD']['setting_value'] && $after_expense < $this->_data['MEMBER_DIAMOND']['setting_value']) {
                                $user->account_type = CoreUsers::ACCOUNT_TYPE_GOLD;
                            } else if ($after_expense >= $this->_data['MEMBER_DIAMOND']['setting_value'] && $after_expense < $this->_data['MEMBER_VIP']['setting_value']) {
                                $user->account_type = CoreUsers::ACCOUNT_TYPE_DIAMOND;
                            } else if ($after_expense > $this->_data['MEMBER_VIP']['setting_value']) {
                                $user->account_type = CoreUsers::ACCOUNT_TYPE_VIP;
                            } else {
                                $user->account_type = CoreUsers::ACCOUNT_TYPE_NEW;
                            }
                            // Tích điểm
                            $total_point = 0;

                            if ($user->account_type == CoreUsers::ACCOUNT_TYPE_NEW) {
                                $total_point = $data->total_price * 0.00001;
                            } else if ($user->account_type == CoreUsers::ACCOUNT_TYPE_SILVER) {
                                $total_point = $data->total_price * 0.00003;
                            } else if ($user->account_type == CoreUsers::ACCOUNT_TYPE_GOLD) {
                                $total_point = $data->total_price * 0.00005;
                            } else if ($user->account_type == CoreUsers::ACCOUNT_TYPE_DIAMOND) {
                                $total_point = $data->total_price * 0.00007;
                            } else if ($user->account_type == CoreUsers::ACCOUNT_TYPE_VIP) {
                                $total_point = $data->total_price * 0.0001;
                            }
                            $user->point += $total_point;
                            $user->save();
                        }
                    }
                    //hoàn kho nếu hủy
                    if ($status == 5) {
                        $xachnhan = "Hủy";
                        /*todo: cap nhan lai trang thai cua ban*/
                        if (!empty($ban)) {
                            $ban->status = 0;
                            $ban->order_id = 0;
                            $ban->save();
                        }

                        foreach ($data->order_details as $v) {
                            if ($v->product_variation_id) {
                                ProductVariation::update_inventory([
                                    'id'         => $v->product_variation_id,
                                    'product_id' => $v->product_id,
                                    'quantity'   => $v->quantity,
                                ]);
                            } else {
                                Product::update_inventory([
                                    'product_id' => $v->product_id,
                                    'quantity'   => $v->quantity,
                                ]);
                            }
                            Product::change_inventory([
                                'product_id' => $v->product_id,
                            ]);
                        }
                    }


                    /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */
                    $notify = Notification::create([
                        'title' => '' . $data->order_code .'_'. $xachnhan ,
                        'content' => '' . $NameBan .' - Giá : ' . $data->total_price . '  đ ',
                        'chanel' => 2,
                        'type' => 1,
                        'company_id' =>config('constants.company_id'),
                        'relate_id' => 0,
                        'from_user_id' => 168,
                        'to_user_id' => 168,
                        'order_id' => $data->id,
                        'user_id_created' => 168,
                    ]);

                    /*$this->dispatch((new PushNotification($notify))->onQueue('push_notification'));*/
                    /* todo : 12-08-22 thông báo cho admin biêt  có đơn hàng mới */

                    \DB::commit();
                    $request->session()->flash('msg', ['info', 'Cập nhật trạng thái thành công!']);
                } catch (\Exception $e) {
                    \DB::rollBack();
                    \Log::error('status order ' . $e->getMessage());
                   // $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại' . $e->getMessage()]);
                }

                return redirect($this->_ref ? $this->_ref : Route('backend.warehouses.index'));
            }
        }

        $this->_data['subtitle'] = 'Chi tiết đơn hàng';
        $this->_data['data_item'] = $data;

        $warehouse = Warehouse::where('order_id', $data->id)->first();
        if ($warehouse) {
            $branch = Branch::where('id', $warehouse->branch_id)->first();
            $this->_data['data_item']['warehouse'] = $warehouse->name . ' - ' . $branch->name;
        }else{
            $this->_data['data_item']['warehouse'] = "N/A";
        }


        return view('backend.warehouse.detail', $this->_data);
    }

    public function ajaxResult(Request $request)
    {

        $order = Orders::where('warehouse_id',$request->id)->where('status','=',1)->first();
        $oder_details = OrdersDetail::where('order_id','=',$order->id)->get();
        $username =  CoreUsers::where('id',$order->user_id)->first();
        $a = DB::table('lck_orders')
            ->select('lck_orders_detail.product_id as id','lck_orders_detail.title')
            ->join('lck_orders_detail','lck_orders_detail.order_id','=','lck_orders.id')
            ->join('lck_warehouse','lck_warehouse.order_id','=','lck_orders.id')
            ->where('warehouse_id',$request->id)
            ->get();

        return \Response::json(['e' => $a, 'r' => $order, 'u'=>$username, 'i'=>$oder_details]);
    }
}
