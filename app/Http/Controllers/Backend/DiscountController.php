<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\DiscountCode;
use App\Utils\Common as Utils;
use Illuminate\Http\Request;
use Validator;

class DiscountController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Mã giảm giá',
        'subtitle' => 'Mã giảm giá',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'title' => null,
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data = DiscountCode::where('company_id', config('constants.company_id'))
            ->orderBy('created_at', 'desc')->paginate(10);

        $this->_data['list_data'] = $data;
        $this->_data['filter'] = $filter;
        $this->_data['start'] = 0;

        return view('backend.discount.index', $this->_data);
    }

    public function add(Request $request)
    {
        $validator_rule = DiscountCode::get_validation_admin();

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            $params = array_fill_keys(array_keys($validator_rule), null);
            $params = array_merge(
                $params, $request->only(array_keys($validator_rule))
            );

            try {
                $params['company_id'] = config('constants.company_id');
                DiscountCode::create($params);

                $request->session()->flash('msg', ['info', 'Thêm thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }


        $this->_data['subtitle'] = 'Thêm mới';
        return view('backend.discount.add', $this->_data);
    }

    public function edit(Request $request, $id)
    {
        $data = DiscountCode::findOrFail($id);

        $validator_rule = DiscountCode::get_validation_admin(true);

        if ($request->getMethod() == 'POST') {
            Validator::make($request->all(), $validator_rule)->validate();

            try {
                $params = array_fill_keys(array_keys($validator_rule), null);
                $params = array_merge(
                    $params, $request->only(array_keys($validator_rule))
                );
                $params['company_id'] = config('constants.company_id');

                $data->update($params);

                $request->session()->flash('msg', ['info', 'Sửa thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }

        $this->_data['data'] = $data;
        $this->_data['subtitle'] = 'Chỉnh sửa';

        return view('backend.discount.edit', $this->_data);
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = DiscountCode::where('company_id', config('constants.company_id'))->findOrFail($id);
            $data->delete();
            $request->session()->flash('msg', ['info', 'Đã xóa thành công!']);

        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        return redirect()->back();
    }
}
