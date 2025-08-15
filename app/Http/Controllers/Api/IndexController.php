<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseFrontendController;
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
        return redirect()->route('backend.dashboard');
        //        $this->_data['banners'] = Banner::get_by_where([
        //            'company_id' => config('constants.company_id'),
        //            'status'     => 1,
        //            'type'       => 1,
        //            'pagin'      => false,
        //        ]);
        //        $this->_data['banners_ads'] = Banner::get_by_where([
        //            'status' => 1,
        //            'type'   => Banner::TYPE_ADS,
        //            'pagin'  => false,
        //            'limit'  => 3,
        //        ]);
        //        $product_type_1 = ProductType::get_by_where(['assign_key' => true, 'status' => 1, 'type' => 1,], ['id', 'name', 'parent_id']);
        //
        //        $this->_data['product_type_1'] = $product_type_1;
        //
        //        $products_by_category_1 = [];
        //        foreach ($product_type_1 as $k => $v) {
        //            if ($v['parent_id']) continue;
        //
        //            $all_child = [];
        //            $all_child = Category::get_all_child_categories($product_type_1, $k);
        //
        //            $all_child = array_merge($all_child, [$k]);
        //
        //            $products_by_category_1[$k] = Product::get_by_where([
        //                'status'          => 1,
        //                'product_type_id' => $all_child,
        //                'limit'           => 16,
        //                'sort'           => 'newest',
        //                'pagin'           => false,
        //            ]);
        //        }
        //        $this->_data['products_by_category_1'] = $products_by_category_1;
        //        $product_type_2 = ProductType::get_by_where(['assign_key' => true, 'status' => 1, 'type' => 2,], ['id', 'name', 'parent_id']);
        //        $this->_data['product_type_2'] = $product_type_2;
        //
        //        $products_by_category_2 = [];
        //        foreach ($product_type_2 as $k => $v) {
        //            if ($v['parent_id']) continue;
        //
        //            $all_child_2 = [];
        //            $all_child_2 = Category::get_all_child_categories($product_type_2, $k);
        //
        //            $all_child_2 = array_merge($all_child_2, [$k]);
        //
        //            $products_by_category_2[$k] = Product::get_by_where([
        //                'status'          => 1,
        //                'product_type_id' => $all_child_2,
        //                'limit'           => 16,
        //                'sort'           => 'newest',
        //                'pagin'           => false,
        //            ]);
        //        }
        //        $this->_data['products_by_category_2'] = $products_by_category_2;
        //
        //        //get news home
        //        $news = Post::get_by_where([
        //            'status'      => Post::STATUS_SHOW,
        //            'category_id' => 8,
        //            'limit'       => 3,
        //            'pagin'       => false,
        //        ]);
        //        $this->_data['news'] = $news;
        //
        //        $this->_data['menu_active'] = 'home';
        //
        //        $detect = new \Mobile_Detect();
        //        $this->_data['is_mobile'] = $detect->isMobile();
        //        $banner_left = Banner::where('type','=',4)->first();
        //        $banner_right = Banner::where('type','=',5)->first();
        //        $this->_data['banner_left'] = $banner_left;
        //        $this->_data['banner_right'] = $banner_right;
        //
        //        return view('frontend.index.index', $this->_data);
    }

    public function policy()
    {
        return view('frontend.info.policy-return-exchange', $this->_data);
    }

    public function faq()
    {
        return view('frontend.info.faq', $this->_data);
    }

    public function termOfUse()
    {
        return view('frontend.info.term-of-use', $this->_data);
    }

    public function orderingGuide()
    {
        return view('frontend.info.ordering-guide', $this->_data);
    }

    public function informationPrivacy()
    {
        return view('frontend.info.info-privacy', $this->_data);
    }

    public function shippingPolicy()
    {
        return view('frontend.info.shipping-policy', $this->_data);
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

        $statusName = $request->get('Status');
        if ($statusName == 'money_collect_delivering') {
            $statusName = 'delivered';
        }
        // lay ma don hang
        $params['OrderCode'] = $request->get('OrderCode');

        // lay don hang co ma truyeen len tu calback
        $order = Orders::where('order_code', $params['OrderCode'])->first();

        if (empty($order)) {
            return  $this->returnResult($order, 'Không có đơn hàng trong hệ thống để cập nhập');
        }
        if ($request->get('Fee')['Insurance'] == 0) {
            $phiKhaiGia =  (int) $order->insurance_fee; // phí khai giá
        } else {
            $phiKhaiGia =  (int) $request->get('Fee')['Insurance']; // phí khai giá
        }

        $phiAdmin = SettingFee::get_by_where($order->user_id, $order->product_type)->cost ?? 0;

        $CODAmount = $request->get('CODAmount'); // số tiền thu hộ

        //check xem phương thức thanh toán là bên nhận hay bên gửi để xử lý phí giao hàng
        if ($request->get('PaymentType') == 2) {
            $CODAmount2 = $CODAmount - $phiAdmin;
        } elseif ($request->get('PaymentType') == 1) {
            $CODAmount2 = $CODAmount;
        }

        $phiGiaoLai =  (int) $request->get('Fee')['R2S'];  // phí giao lại
        $phiHoanHang =  (int) $request->get('Fee')['Return'];
        $phiGiao1Phan = 0;
        if (!empty($request->get('PartialReturnCode'))) {
            $phiGiao1Phan =   (int) str_replace(['.', ','], '', $order->total_fee) / 2;
        }
        $pOrder['phi_gh1p'] = $phiGiao1Phan;

        $pOrder['order_code_custom'] =  $request->get('ClientOrderCode');

        $pOrder['insurance_fee'] =  $phiKhaiGia;
        $pOrder['product_type_cost'] = $phiAdmin;

        $pOrder['total_fee'] = number_format($request->get('Fee')['MainService'] + $phiAdmin); // phi van chuyen
        $pOrder['main_service'] = (int) str_replace(['.', ','], '', $pOrder['total_fee']) + $phiGiaoLai + $phiKhaiGia + $phiHoanHang + $phiGiao1Phan; //  tông phí

        $pOrder['statusName'] =  $statusName;  // shopId

        $pOrder['weight'] =  $request->get('Weight');
        $pOrder['height'] =  $request->get('Height');
        $pOrder['length'] =  $request->get('Length');
        $pOrder['width'] =  $request->get('Width');


        $pOrder['ConvertedWeight'] =  $request->get('ConvertedWeight');
        $pOrder['R2S'] =  $request->get('Fee')['R2S'];  // phí giao lại
        $pOrder['Return_k'] =  $request->get('Fee')['Return']; // ph hoan hang
        $pOrder['ReturnAgain'] =  $request->get('Fee')['ReturnAgain'];
        $pOrder['PartialReturnCode'] =  $request->get('PartialReturnCode');

        $order->update($pOrder);
        // đối soát
        $Status = StatusName::where('key', $pOrder['statusName'])->first()->name ?? '';
        $shopId = $request->get('ShopID');
        $isCheckBrand = Branch::where('shopId', $shopId)->first();

        $phiGiaoHang = (int) str_replace(['.', ','], '', $order->total_fee); // Phí giao hàng + phí admin

        if ($request->get('PaymentType') == 2) {
            $tongPhi = $phiGiaoHang + $phiGiaoLai + $phiKhaiGia + $phiHoanHang + $phiGiao1Phan - $phiAdmin;
        } elseif ($request->get('PaymentType') == 1) {
            $tongPhi = $phiGiaoHang + $phiGiaoLai + $phiKhaiGia + $phiHoanHang + $phiGiao1Phan;
        }

        $tongdoisoat = $CODAmount2 - $tongPhi;

        if ($isCheckBrand) {

            $DoiSoat = DoiSoat::where('OrderCode', $request->get('OrderCode'))->first();

            $commonData = [
                'CODAmount' => $CODAmount2,
                'ConvertedWeight' => $request->get('ConvertedWeight'),
                'Insurance' => $phiKhaiGia,
                'MainService' => $phiGiaoHang,
                'R2S' => $phiGiaoLai,
                'Return' => $phiHoanHang,
                'Height' => $request->get('Height'),
                'Length' => $request->get('Length'),
                'PartialReturnCode' => $request->get('PartialReturnCode'),
                'ShopID' => $request->get('ShopID'),
                'Weight' => $request->get('Weight'),
                'Width' => $request->get('Width'),
                'phigiao1lan' => $phiGiao1Phan,
                'tongphi' => (int) $tongPhi,
                'ngaytaodon' => $order->created_at,
                'IDUser' => $order->user_id,
                'Status' => $Status,
                'tongdoisoat' => $tongdoisoat,
                'statusName' => $pOrder['statusName'],
            ];

            if ($DoiSoat) {
                if ($request->get('CODTransferDate') != null) {
                    $commonData['doisoat'] = 'Chưa chuyển COD';
                    $commonData['type'] = 1;
                }
                if ($statusName == 'delivered' or $statusName == 'returned' and  $commonData['ngaygiaohoanthanhcong'] == null) {
                    $commonData['ngaygiaohoanthanhcong'] = $request->get('Time');
                }

                $DoiSoat->update($commonData);
            } else {
                if ($request->get('CODTransferDate') != null) {
                    $commonData['doisoat'] = 'Chưa chuyển COD';
                    $commonData['type'] = 1;
                } else {
                    $commonData['doisoat'] = null;
                    $commonData['ngaygiaohoanthanhcong'] = null;
                    $commonData['type'] = 0;
                }

                if ($statusName == 'delivered' or $statusName == 'returned' and  $commonData['ngaygiaohoanthanhcong'] == null) {
                    $commonData['ngaygiaohoanthanhcong'] = $request->get('Time');
                }
                $commonData['OrderCode'] = $request->get('OrderCode');

                $doisoat = DoiSoat::create($commonData);
            }
        }

        return  $this->returnResult($order);
    }
}
