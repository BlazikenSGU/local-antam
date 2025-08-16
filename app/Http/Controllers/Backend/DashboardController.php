<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Jobs\PushNotification;
use App\Models\CoreUsers;
use App\Models\Notification;
use App\Models\Orders;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductContactInfo;
use App\Models\ProductNote;
use App\Models\ThienMinh\District;
use App\Models\ThienMinh\Lands;
use App\Models\ThienMinh\Notes;
use App\Models\ThienMinh\Ward;
use App\ReportsHourly;
use Illuminate\Http\Request;

class DashboardController extends BaseBackendController
{
    protected $_data = [
        'title' => 'Dashboard',
    ];

    public function merge()
    {
        exit;
    }

    public function mergeNote()
    {
        exit;
    }

    public function test()
    {
        $d1 = District::all()->toArray();

        foreach ($d1 as $v) {
            $d2 = \App\Models\Location\District::find($v['district_id'])->toArray();
            //            echo $v['district_id'];
            //            echo ' - ';
            //            echo $v['name'];
            //            echo ' - ';
            //            echo isset($d2['name']) ? $d2['name'] : '<strong>Thiếu</strong>';
            //            echo '<br>';

            $ward1 = Ward::where('district_id', $v['district_id'])->get()->toArray();
            $_ward2 = \App\Models\Location\Ward::where('district_id', $v['district_id'])->get()->toArray();

            $ward2 = [];
            foreach ($_ward2 as $w2) {
                $ward2[$w2['id']] = $w2;
            }
            echo '-------------------------------------------<br>';

            foreach ($ward1 as $w1) {
                echo $w1['ward_id'];
                echo ' - ';
                echo $w1['name'];
                echo ' - ';
                echo isset($ward2[$w1['ward_id']]['name']) ? $ward2[$w1['ward_id']]['name'] : '<strong style="color:red;">Thiếu</strong>';
                echo '<br>';
            }
        }
    }

    public function index()
    {

        $user = Auth()->guard('backend')->user();

        return view('backend.index', $this->_data);
    }

    private function _getAddress($params)
    {
        if (
            empty($params['province_id'])
            || empty($params['district_id'])
            || empty($params['ward_id'])
            || empty($params['street_id'])
            || empty($params['apartment_number'])
        )
            return null;

        $province = \App\Models\Location\Province::findOrFail($params['province_id']);
        $district = \App\Models\Location\District::findOrFail($params['district_id']);
        $ward = \App\Models\Location\Ward::findOrFail($params['ward_id']);
        $street = \App\Models\Location\Street::findOrFail($params['street_id']);
        $address = $params['apartment_number'] . ' ' . $street->name . ', ' . $ward->name . ', ' . $district->name . ', ' . $province->name;
        return $address;
    }

    public function webhook(Request $request)
    {

        $order_id = '';
        $order_status = '';
        $supplier_name = '';
        $supplier_phone = '';
        $jsonData = $request->json()->all();

        if (isset($jsonData['_id'])) {
            $order_id = $jsonData['_id'];
        }
        if (isset($jsonData['status'])) {
            $order_status = $jsonData['status'];
        }
        if (isset($jsonData['supplier_name'])) {
            $supplier_name = $jsonData['supplier_name'];
        }
        if (isset($jsonData['supplier_id'])) {
            $supplier_phone = $jsonData['supplier_id'];
        }

        $order = Orders::where('ahamove_type', $order_id)->first();


        if (!empty($order)) {
            $order->supplier_name = $supplier_name;
            $order->supplier_phone = $supplier_phone;
            $order->save();
            if ($order_status == 'COMPLETED') {
                $notify = Notification::create([
                    'title' => 'Trạng thái đơn hàng.',
                    'content' => 'Đơn hàng ' . $order->id . ' đã đươc tài xế' . $supplier_name . ' giao thành công. ',
                    'chanel' => 2,
                    'type' => 1,
                    'company_id' => config('constants.company_id'),
                    'relate_id' => 0,
                    'from_user_id' => 168,
                    'to_user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'user_id_created' => 168,
                ]);
                $this->dispatch((new PushNotification($notify))->onQueue('push_notification'));
                $order->status = 4;
                $order->save();
            } elseif ($order_status == 'CANCELLED') {
                $notify = Notification::create([
                    'title' => 'Trạng thái đơn hàng.',
                    'content' => 'Đơn hàng ' . $order->id . ' đã bị hủy bởi tài xế ' . $supplier_name,
                    'chanel' => 2,
                    'type' => 1,
                    'company_id' => config('constants.company_id'),
                    'relate_id' => 0,
                    'from_user_id' => 168,
                    'to_user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'user_id_created' => 168,
                ]);
                $this->dispatch((new PushNotification($notify))->onQueue('push_notification'));
                // gủi thông báo cho tài khoản là chủ chi nhánh
                $admin_branch = CoreUsers::where('branch_id', $order->branch_id)->first();
                $notify1 = Notification::create([
                    'title' => 'Trạng thái đơn hàng.',
                    'content' => 'Đơn hàng ' . $order->id . ' đã bị hủy.',
                    'chanel' => 2,
                    'type' => 1,
                    'company_id' => config('constants.company_id'),
                    'relate_id' => 0,
                    'from_user_id' => 168,
                    'to_user_id' => $admin_branch->id,
                    'order_id' => $order->id,
                    'user_id_created' => 168,
                ]);
                $this->dispatch((new PushNotification($notify1))->onQueue('push_notification'));
            } elseif ($order_status == 'ACCEPTED') {
                $notify = Notification::create([
                    'title' => 'Trạng thái đơn hàng.',
                    'content' => 'Đơn hàng ' . $order->id . ' đã được tại xế ' . $supplier_name . ' chấp nhận. ',
                    'chanel' => 2,
                    'type' => 1,
                    'company_id' => config('constants.company_id'),
                    'relate_id' => 0,
                    'from_user_id' => 168,
                    'to_user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'user_id_created' => 168,
                ]);
                $this->dispatch((new PushNotification($notify))->onQueue('push_notification'));
            }
        }

        $response = [
            'message' => 'successfully',
            'order_status' => $order_status,
        ];



        return response()->json($response);
    }
}
