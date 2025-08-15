<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseFrontendController;
use App\Mail\Order;
use App\Models\Banner;
use App\Models\Branch;
use App\Models\DoiSoat;
use App\Models\Notification;
use App\Models\Orders;
use App\Models\OrdersDetail;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\SettingFee;
use App\Models\StatusName;
use App\Utils\Category;
use App\VNShipping\GHN;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use View;



class IndexController extends BaseFrontendController
{
    protected $_data = [];


    public function index()
    {
        return redirect()->route('user.index');
    }

    public function callback(Request $request)
    {
        Log::info('Nội dung Request: ' . json_encode($request->input()));

        $form_init['company_id'] = config('constants.company_id');
        $form_init['chanel'] = 1;
        $form_init['title'] = 'Thông tin callback';
        $form_init['content'] = json_encode($request->input());
        $form_init['type'] = 1;
        $form_init['to_user_id'] = 168;
        $form_init['user_id_created'] = 168;

        $notification = Notification::create($form_init);

        // lay ma don hang
        $order_code = $request->get('OrderCode');
        $order = Orders::where('order_code', $order_code)->first();

        if (empty($order)) {
            $order = $this->createOrder($request);
        }

        if (!$order) {
            Log::error("Không thể tạo hoặc truy vấn đơn hàng với mã: " . $order_code);
            return;
        }

        $user_id = $order->user_id ?? 0;

        $fee_shopId = $order->fee_shopId ?? 0;
        $GiaoThatBaiThuTien =  (int) str_replace(['.', ','], '', $order->cod_failed_amount ?: 0);
        $PaymentType = $request->get('PaymentType');

        $phiGiao1Phan = 0;
        $phiHoanhang = 0;

        if (!empty($request->get('PartialReturnCode'))) {
            $getMainService = $request->get('Fee')['MainService'];
            $phiGiao1Phan = ceil(($getMainService + $fee_shopId) / 2);
        }

        if ($request->get('Fee')['Return'] != 0) {
            $phiHoanhang = ceil(($request->get('Fee')['MainService'] + $fee_shopId) / 2);
        }

        $pOrder['cod_amount'] = $request->get('CODAmount');
        $pOrder['weight'] =  $request->get('Weight');
        $pOrder['height'] =  $request->get('Height');
        $pOrder['length'] =  $request->get('Length');
        $pOrder['width'] =  $request->get('Width');
        $pOrder['order_code_custom'] =  $request->get('ClientOrderCode');
        $pOrder['statusName'] =  $request->get('Status');
        $pOrder['phi_gh1p'] = $phiGiao1Phan; //phi giao 1 phan
        $pOrder['R2S'] =  $request->get('Fee')['R2S'];  // phí giao lại
        $pOrder['Return'] = $phiHoanhang; // phi hoan hang
        $pOrder['insurance_fee'] =  $request->get('Fee')['Insurance'] == 0 ? $order->insurance_fee : $request->get('Fee')['Insurance']; // phi khai gia
        $pOrder['main_service'] = $request->get('Fee')['MainService']; // phi van chuyen
        $pOrder['total_fee'] = $request->get('TotalFee');
        $pOrder['fee_shopId'] = $fee_shopId;
        $pOrder['ConvertedWeight'] =  $request->get('ConvertedWeight');
        $pOrder['PartialReturnCode'] =  $request->get('PartialReturnCode');
        $order->update($pOrder);

        $statusName = $pOrder['statusName'] ?? '';

        // đối soát
        $Status = StatusName::where('key', $pOrder['statusName'])->first()->name ?? '';
        $shopId = $request->get('ShopID');
        $isCheckBrand = Branch::where('shopId', $shopId)->first();
        $phiGiaoHang = (int) $request->get('Fee')['MainService'];
        $phiGiaoLai =  (int) $request->get('Fee')['R2S'];


        if ($PaymentType == 1) {
            $CODAmount = $request->get('CODAmount');
            $tongPhi = ceil($phiGiaoHang + $phiGiaoLai + ($request->get('Fee')['Insurance'] == 0 ? $order->insurance_fee : $request->get('Fee')['Insurance']) + $phiHoanhang + $phiGiao1Phan + $fee_shopId);
        }

        $tongdoisoat =  (int)($CODAmount) - (int) $tongPhi;

        if ($isCheckBrand) {

            $DoiSoat = DoiSoat::where('OrderCode', $request->get('OrderCode'))->first();

            $commonData = [
                'CODAmount' => $request->get('CODAmount'),
                'ConvertedWeight' => $request->get('ConvertedWeight'),
                'Insurance' => $request->get('Fee')['Insurance'] == 0 ? $order->insurance_fee : $request->get('Fee')['Insurance'],
                'MainService' => $request->get('Fee')['MainService'],
                'R2S' => $request->get('Fee')['R2S'],
                'Return' => $phiHoanhang,
                'Height' => $request->get('Height'),
                'Length' => $request->get('Length'),
                'PartialReturnCode' => $request->get('PartialReturnCode'),
                'ShopID' => $request->get('ShopID'),
                'weight' => $request->get('Weight'),
                'Width' => $request->get('Width'),
                'phigiao1lan' => $phiGiao1Phan,
                'tongphi' => $tongPhi,
                'ngaytaodon' => $request->get('CODTransferDate'),
                'IDUser' => $order->user_id,
                'Status' => $Status,
                'statusName' => $pOrder['statusName'],
                'payment_method' => $PaymentType,
                'cod_failed_amount' => $GiaoThatBaiThuTien,
            ];

            if ($DoiSoat) {

                //neu don doi soat co trang thai type == 1 hay type == 2 thi ngat ket noi webhook
                if ($DoiSoat->type != 1 && $DoiSoat->type != 2) {

                    if ($DoiSoat->tinhtrangthutienGTB == 1) {
                        $commonData['tongdoisoat'] = $CODAmount + (int)$GiaoThatBaiThuTien - (int) $tongPhi;
                    } else {
                        $commonData['tongdoisoat'] = $tongdoisoat;
                    }

                    if ($request->get('CODTransferDate') != null) {
                        $commonData['doisoat'] = 'Chưa chuyển COD';
                        $commonData['type'] = 1;
                    }

                    if ($statusName == 'delivered' or $statusName == 'returned') {
                        if (empty($DoiSoat->ngaygiaohoanthanhcong)) {
                            $commonData['ngaygiaohoanthanhcong'] = $request->get('Time');
                        }
                    } else {
                        $commonData['ngaygiaohoanthanhcong'] = null;
                    }

                    $DoiSoat->update($commonData);
                }
            } else {

                if ($request->get('CODTransferDate')  != null) {
                    $commonData['doisoat'] = 'Chưa chuyển COD';
                    $commonData['type'] = 1;
                } else {
                    $commonData['doisoat'] = null;
                    $commonData['ngaygiaohoanthanhcong'] = null;
                    $commonData['type'] = 0;
                }

                if ($statusName == 'delivered' or $statusName == 'returned' and $DoiSoat->ngaygiaohoanthanhcong == null) {
                    $commonData['ngaygiaohoanthanhcong'] = $request->get('Time');
                } else {
                    $commonData['ngaygiaohoanthanhcong'] = null;
                }

                $commonData['OrderCode'] = $request->get('OrderCode');

                $commonData['fee_shopId'] = $fee_shopId ?? 0;

                DoiSoat::create($commonData);
            }
        }

        return  $this->returnResult($order);
    }

    public function createOrder(Request $request)
    {
        $shopId = $request->get('ShopID');
        $order_code = $request->get('OrderCode');
        $isCheckBrand = Branch::where('shopId', $shopId)->where('type', 2)->first();

        if ($isCheckBrand) {
            $data = [
                'user_id' => 0,
                'order_code' => $order_code,
                'shopId' => $shopId,
                'statusName' => $request->get('Status'),
                'created_at' => time(),
                'order_code_custom' => $request->get('ClientOrderCode'),
                'cod_amount' => $request->get('CODAmount'),
                'weight' => $request->get('Weight'),
                'height' => $request->get('Height'),
                'length' => $request->get('Length'),
                'width' => $request->get('Width'),
                // 'fee_shopId' => $request->get('Fee')['ShopFee'],
                'converted_weight' => $request->get('ConvertedWeight'),
                'partial_return_code' => $request->get('PartialReturnCode'),
                'cod_failed_amount' => 0,
                'main_service' => $request->get('Fee')['MainService'],
                'insurance_fee' => $request->get('Fee')['Insurance'] == 0 ? 0 : $request->get('Fee')['Insurance'],
                'r2s' => $request->get('Fee')['R2S'],
                'return' => $request->get('Fee')['Return'],
                // 'status' => $request->get('Status'),
                'use_create_order' => 0,
                'cod_transfer_date' => $request->get('CODTransferDate'),
                'payment_method' => $request->get('PaymentType'),
                'created_at' => time(),
                // 'updated_at' => time(),
                'ngaygiaohoanthanhcong' => $request->get('Time'),
                // 'ngaytaodon' => $request->get('CODTransferDate'),
                // 'doisoat' => $request->get('CODTransferDate') != null ? 'Chưa chuyển COD' : null,
                // 'tinhtrangthutienGTB' => 0, // mặc định 0
                // 'tongdoisoat' => 0, // mặc định 

            ];

            Log::info('Tạo đơn hàng mới với dữ liệu: ' . $data);
            return Orders::create($data);
        }

        return null;
    }
}
