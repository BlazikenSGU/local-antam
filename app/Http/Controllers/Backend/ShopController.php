<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\Orders;
use App\Models\Ticker;
use App\VNShipping\GHN;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends BaseBackendController
{
    //

    public function index(Request $request, $id)
    {
        return view('backend.shop.index');
    }

    public function indexshopid(Request $request)
    {
        return view('backend.shop.shopidgetall');
    }
    public function cod(Request $request)
    {
        return view('backend.shop.cod');
    }
    public function ticket(Request $request)
    {

        $userId = Auth()->guard('backend')->user()->id;
        $this->_data['data'] = Ticker::where('user_id', $userId)->orderBy('id', 'desc')->get();
        return view('backend.shop.ticket', $this->_data);
    }
    public function ticketcreate(Request $request)
    {

        $category = $request->get('category');
        $params = $request->all();
        if ($category == 1) {
            $params['type'] = 'Tư vấn';
        } elseif ($category == 2) {
            $params['type'] = 'Hối Giao/Lấy/Trả hàng';
        } elseif ($category == 3) {
            $params['type'] = 'Thay đổi thông tin';
        } elseif ($category == 4) {
            $params['type'] = 'Khiếu nại';
        }

        $order = Orders::where('order_code', $params['order_code'])->first();
        if (empty($order)) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại! Không tìm tháy đơn hàng mã ' . $params['order_code']]);
            return redirect()->back();
        }

        $Ticker = Ticker::create([
            'user_id' => Auth()->guard('backend')->user()->id,
            'attachments' => null,
            'client_id' => request()->getClientIp(),
            'conversations' => null,
            'created_by' => null,
            'description' => $params['description'],
            'ticker_id' => null,
            'order_code' => $params['order_code'],
            'status' => null,
            'status_id' => null,
            'type' => $params['type'],
        ]);

        $ticket = GHN::CraeteTicket($Ticker);
        $arrays = json_decode($ticket);
        $Ticker->status = $arrays->data->status;
        $Ticker->status_id = $arrays->data->status_id;
        $Ticker->created_by = $arrays->data->created_by;
        $Ticker->ticker_id = $arrays->data->id;
        $Ticker->save();
        $request->session()->flash('msg', ['info', 'Tạo khiếu nại thành công!']);
        return redirect()->back();
    }
}
