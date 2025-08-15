<?php

namespace App\Http\Controllers\Backend\Location;

use App\Http\Controllers\BaseBackendController;
use App\Models\Location\District;
use App\Models\Location\Province;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\CoreUsers;
use App\Utils\Common as Utils;
use App\Utils\Avatar;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use App\Utils\Filter;

class ProvinceController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Quản lý Tỉnh/TP',
        'subtitle' => 'Quản lý Tỉnh/TP',
    );

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'name'  => null,
            'limit' => config('constants.item_per_page_admin'),
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);
        $params['limit'] = (int)$params['limit'] > 0 ? (int)$params['limit'] : config('constants.item_per_page_admin');

        $provinces = Province::orderBy('priority')->paginate($params['limit'])->withPath($params['pagin_path']);

        $this->_data['provinces'] = $provinces;
        $this->_data['filter'] = $filter;
        $this->_data['start'] = ($provinces->currentPage() - 1) * $params['limit'];
        $this->_data['_limits'] = config('constants.limit_records');

        return view('backend.location.province.index', $this->_data);
    }

    public function add(Request $request)
    {
        $validator_rule = [
            'name_origin' => ['required', 'string'],
            'type'        => ['required', 'string', Rule::in(['Tỉnh', 'Thành phố']),],
//            'status'      => 'required|integer|in:0,1',
        ];

        $params = array_merge(
            array(
                'name_origin' => null,
                'type'        => null,
            ), array_map('trim', $request->all())
        );

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            $check = Province::whereRaw("BINARY name_origin = ?", $params['name_origin'])->count();

            if ($check)
                return redirect()->back()->withInput()->withErrors(['messagge' => 'Tên Tỉnh/TP đã tồn tại. Vui lòng kiểm tra lại!']);

            try {

                Province::create(
                    [
                        'name'            => $params['type'] . ' ' . $params['name_origin'],
                        'name_ascii'      => Filter::vnToAscii($params['name_origin']),
                        'name_origin'     => $params['name_origin'],
                        'type'            => $params['type'],
//                        'status'          => $params['status'],
                        'user_id_created' => Auth()->guard('backend')->user()->id,
                    ]
                );

                $request->session()->flash('msg', ['info', 'Thêm thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect(Route('backend.location.province.add'));
        }

        $this->_data['subtitle'] = 'Thêm mới';
        return view('backend.location.province.add', $this->_data);
    }

    public function edit(Request $request, $id)
    {
        $province = Province::findOrFail($id);

        $validator_rule = [
            'name_origin' => ['required', 'string'],
            'type'        => ['required', 'string', Rule::in(['Tỉnh', 'Thành phố']),],
//            'status'      => 'required|integer|in:0,1',
        ];

        $params = array_merge(
            array(
                'name_origin' => null,
                'type'        => null,
            ), array_map('trim', $request->all())
        );

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            try {

                $province->update(
                    [
                        'name'        => $params['type'] . ' ' . $params['name_origin'],
                        'name_ascii'  => Filter::vnToAscii($params['name_origin']),
                        'name_origin' => $params['name_origin'],
                        'type'        => $params['type'],
//                        'status'      => $params['status'],
                    ]
                );

                $request->session()->flash('msg', ['info', 'Sửa thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect(Route('backend.location.province.index'));
        }

        $this->_data['province'] = $province;
        $this->_data['subtitle'] = 'Chỉnh sửa';
        return view('backend.location.province.edit', $this->_data);
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = Province::findOrFail($id);
            $data->delete();
            $request->session()->flash('msg', ['info', 'Đã xóa thành công!']);

        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        return redirect($this->_ref ? $this->_ref : Route('backend.location.province.index'));
    }
}