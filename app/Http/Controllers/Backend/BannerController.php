<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\Banner;
use App\Models\CoreUsers;
use App\Models\DoiSoat;
use App\Models\DoiSoatUser;
use App\Models\Files;
use App\Models\Orders;
use App\Models\ProductType;
use App\Utils\Avatar;
use App\Utils\Category;
use App\Utils\Common as Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class BannerController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Banner',
        'subtitle' => 'Banner',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {

        dd('ok');

        exit();
        $doisoatUser = DoiSoatUser::get();
        foreach ($doisoatUser as  $value) {

            $doi_soat = DoiSoat::where('type', 2)->where('IdDoiSoatUser', $value->id);


            if ($value->thucnhan > 0) {
                $value->tongtienCOD = $doi_soat->sum('CODAmount');
                $value->GTBThutien = $doi_soat->sum('cod_failed_amount');
                $value->thucnhan = $doi_soat->sum('tongdoisoat');
                $value->soHDtuongung = count($doi_soat->get());
                $value->save();
            }

        }

        dd(1);
        exit();

        $orders = Orders::get();
        foreach ($orders as $order) {
            $doi_soat = DoiSoat::where('OrderCode', $order->order_code)->first();

            $PaymentType = $order->payment_method;
            $phiAdmin  = $order->payment_fee;
            $GiaoThatBaiThuTien =   (int) str_replace(['.', ','], '', $order->cod_failed_amount) ;
            if ($doi_soat) {

                $CODamount = $doi_soat->CODAmount;
                $phiGiao1Phan = $doi_soat->phigiao1lan;
                $phiGiaoHang = $doi_soat->tongphi;
                $phiKhaiGia = $doi_soat->Insurance;
                $phiHoanHang = $doi_soat->Return;
                $phiGiaoLai = $doi_soat->R2S;

                if ($PaymentType == 1) {
                    $CODAmount = $CODamount;
                    $tongPhi = $phiGiaoHang+$phiGiaoLai+$phiKhaiGia+$phiHoanHang+$phiGiao1Phan;
                } else {
                    $CODAmount = $CODamount;
                    $tongPhi =  $phiGiao1Phan;
                }
                if ( $doi_soat->tinhtrangthutienGTB == 1) {
                    $tongdoisoat = $CODAmount - $phiAdmin + (int)$GiaoThatBaiThuTien - (int) $tongPhi;
                } else {
                    $tongdoisoat = $CODAmount - $phiAdmin - (int) $tongPhi;
                }
                $doi_soat->cod_failed_amount = $GiaoThatBaiThuTien;
                $doi_soat->CODAmount = $CODAmount - $phiAdmin;
                $doi_soat->tongPhi = $tongPhi;
                $doi_soat->tongdoisoat = $tongdoisoat;
                $doi_soat->payment_method = $PaymentType;
                $doi_soat->save();
            }
        }


        dd(1);
        exit();
//        $couuser = CoreUsers::where('id', '<>', 168)->get();
//        foreach ($couuser as $v) {
//            $uploads_dir = config('constants.upload_dir.root');
//            $avartar = Avatar::generate($v->email, $uploads_dir);
//
//            $v->avatar_file_path = config('constants.upload_dir.url') . '/' . $avartar;
//            //$v->password = Hash::make(123123);
//            $v->save();
//
//        }


        dd(1);
        $order = Orders::wherenotnull('PartialReturnCode')->get();
        foreach ($order as $v) {
            $total_fee = (int) str_replace(['.', ','], '', $v->total_fee);
            $phiGiaoLai = $v->R2S;
            $phiKhaiGia = $v->insurance_fee;
            $phiHoanHang = $v->Return;
            $phiGiao1Phan = $total_fee/2;
           // $v->update(['main_service' => $total_fee + $phiGiaoLai + $phiKhaiGia + $phiHoanHang + $phiGiao1Phan]);
        }

        dd($order);

        //$pOrder['main_service'] = (int) str_replace(['.', ','], '', $pOrder['total_fee']) + $phiGiaoLai + $phiKhaiGia + $phiHoanHang + $phiGiao1Phan; //  tông phí



        $filter = $params = array_merge(array(
            'title' => null,
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data = Banner::where('company_id', config('constants.company_id'))
            ->orderBy('created_at', 'desc')->paginate(10);

        $this->_data['list_data'] = $data;
        $this->_data['filter'] = $filter;
        $this->_data['start'] = 0;

        return view('backend.banner.index', $this->_data);
    }

    public function add(Request $request)
    {
        $validator_rule = Banner::get_validation_admin();

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            $params = array_fill_keys(array_keys($validator_rule), null);
            $params = array_merge(
                $params, $request->only(array_keys($validator_rule))
            );

            try {
                $params['company_id'] = config('constants.company_id');

                $image_id = $request->get('image_id');
                if ($image_id) {
                    $file = Files::find($image_id);
                    $params['image_id'] = $file->id;
                    $params['image_path'] = $file->file_path;
                }

                $mobile_image_id = $request->get('mobile_image_id');
                if ($mobile_image_id) {
                    $file = Files::find($mobile_image_id);
                    $params['mobile_image_id'] = $file->id;
                    $params['mobile_image_path'] = $file->file_path;
                }

                Banner::create($params);

                $request->session()->flash('msg', ['info', 'Thêm thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }


        $this->_data['subtitle'] = 'Thêm mới';
        $this->_data['image_file'] = old('image_id') ? Files::find(old('image_id')) : [];
        $this->_data['mobile_image_file'] = old('mobile_image_id') ? Files::find(old('mobile_image_id')) : [];

        return view('backend.banner.add', $this->_data);
    }

    public function edit(Request $request, $id)
    {
        $data = Banner::findOrFail($id);

        $validator_rule = Banner::get_validation_admin();

        if ($request->getMethod() == 'POST') {
            Validator::make($request->all(), $validator_rule)->validate();

            $params = array_fill_keys(array_keys($validator_rule), null);
            $params = array_merge(
                $params, $request->only(array_keys($validator_rule))
            );

            try {
                $params['company_id'] = config('constants.company_id');
                $image_id = $request->get('image_id');
                if ($image_id) {
                    $file = Files::find($image_id);
                    $params['image_id'] = $file->id;
                    $params['image_path'] = $file->file_path;
                }

                $mobile_image_id = $request->get('mobile_image_id');
                if ($mobile_image_id) {
                    $file = Files::find($mobile_image_id);
                    $params['mobile_image_id'] = $file->id;
                    $params['mobile_image_path'] = $file->file_path;
                }

                $data->update($params);

                $request->session()->flash('msg', ['info', 'Sửa thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }
        $this->_data['image_file'] = old('image_id', $data->image_id) ? Files::find(old('image_id', $data->image_id)) : [];
        $this->_data['mobile_image_file'] = old('mobile_image_id', $data->mobile_image_id) ? Files::find(old('mobile_image_id', $data->mobile_image_id)) : [];

        $this->_data['data'] = $data;
        $product_type = ProductType::select('id', 'name', 'parent_id')->get();
        $product_type_id = old('product_type_id', $data->product_type_id);

        $product_type_html = Category::build_select_tree($product_type->toArray(), 0, '', [$product_type_id]);

        $this->_data['product_type_html'] = $product_type_html;
        $this->_data['subtitle'] = 'Chỉnh sửa';

        return view('backend.banner.edit', $this->_data);
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = Banner::where('company_id', config('constants.company_id'))->findOrFail($id);
            $data->delete();
            $request->session()->flash('msg', ['info', 'Đã xóa thành công!']);

        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        return redirect()->back();
    }
}
