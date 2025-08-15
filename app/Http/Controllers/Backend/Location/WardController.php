<?php

namespace App\Http\Controllers\Backend\Location;

use App\Http\Controllers\BaseBackendController;
use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use App\Utils\Filter;
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

class WardController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Quản lý Phường/Xã',
        'subtitle' => 'Quản lý Phường/Xã',
    );

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'province_id' => null,
            'district_id' => null,
            'name'        => null,
            'limit'       => config('constants.item_per_page_admin'),
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);
        $params['limit'] = (int)$params['limit'] > 0 ? (int)$params['limit'] : config('constants.item_per_page_admin');

        $wards = Ward::select('*');

        if ($filter['name'])
            $wards->where('name', 'like', "%{$filter['name']}%");

        if ($filter['district_id'])
            $wards->where('district_id', $filter['district_id']);

        $wards = $wards->orderBy('priority')->paginate($params['limit'])->withPath($params['pagin_path']);

        $this->_data['wards'] = $wards;
        $this->_data['filter'] = $filter;
        $this->_data['start'] = ($wards->currentPage() - 1) * $params['limit'];

        $province_id = $filter['province_id'];
        $district_id = $filter['district_id'];

        $this->_data['provinces'] = Province::where('status', 1)->orderBy('priority')->get();
        $this->_data['districts'] = $province_id ? District::where('province_id', $province_id)->orderBy('priority')->get() : [];
        $this->_data['_limits'] = config('constants.limit_records');

        return view('backend.location.ward.index', $this->_data);
    }

    public function add(Request $request)
    {
        $validator_rule = [
            'name_origin' => ['required', 'string'],
            'district_id' => ['required', 'integer'],
//            'type'        => ['required', 'string', Rule::in(['Quận', 'Huyện']),],
        ];

        $params = array_merge(
            array(
                'name_origin' => null,
                'type'        => null,
                'district_id' => null,
            ), array_map('trim', $request->all())
        );

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            $check = Ward::whereRaw("BINARY name_origin = ?", $params['name_origin'])->count();

            if ($check)
                return redirect()->back()->withInput()->withErrors(['messagge' => 'Tên Quận/Huyện đã tồn tại. Vui lòng kiểm tra lại!']);

            try {

                Ward::create(
                    [
                        'name'            => $params['type'] . ' ' . $params['name_origin'],
                        'name_ascii'      => Filter::vnToAscii($params['name_origin']),
                        'name_origin'     => $params['name_origin'],
                        'type'            => $params['type'],
                        'district_id'     => $params['district_id'],
                        'user_id_created' => Auth()->guard('backend')->user()->id,
                    ]
                );

                $request->session()->flash('msg', ['info', 'Thêm thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect(Route('backend.location.ward.add'));
        }

        $this->_data['provinces'] = Province::orderBy('priority')->get();
        $this->_data['districts'] = old('province_id') ? District::where('province_id', old('province_id'))->orderBy('priority')->get() : [];
        $this->_data['subtitle'] = 'Thêm mới';
        return view('backend.location.ward.add', $this->_data);
    }

    public function edit(Request $request, $id)
    {
        $ward = Ward::findOrFail($id);

        $validator_rule = [
            'name_origin' => ['required', 'string'],
            'district_id' => ['required', 'integer'],
            'type'        => ['required', 'string', Rule::in(['Quận', 'Huyện']),],
        ];

        $params = array_merge(
            array(
                'name_origin' => null,
                'type'        => null,
                'district_id' => null,
            ), array_map('trim', $request->all())
        );

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            try {

                $ward->update(
                    [
                        'name'        => $params['type'] . ' ' . $params['name_origin'],
                        'name_ascii'  => Filter::vnToAscii($params['name_origin']),
                        'name_origin' => $params['name_origin'],
                        'type'        => $params['type'],
                        'district_id' => $params['district_id'],
                    ]
                );

                $request->session()->flash('msg', ['info', 'Sửa thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect(Route('backend.location.ward.index'));
        }

        $province_id = old('province_id', $ward->district->province_id);
        $this->_data['provinces'] = Province::orderBy('priority')->get();
        $this->_data['districts'] = $province_id ? District::where('province_id', $province_id)->orderBy('priority')->get() : [];
        $this->_data['ward'] = $ward;
        $this->_data['subtitle'] = 'Chỉnh sửa';
        return view('backend.location.ward.edit', $this->_data);
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = Ward::findOrFail($id);
            $data->delete();
            $request->session()->flash('msg', ['info', 'Đã xóa thành công!']);

        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        return redirect($this->_ref ? $this->_ref : Route('backend.location.ward.index'));
    }
}
