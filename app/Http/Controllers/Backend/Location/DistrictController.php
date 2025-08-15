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

class DistrictController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Quản lý Quận/Huyện',
        'subtitle' => 'Quản lý Quận/Huyện',
    );

    public function index(Request $request)
    {

        $filter = $params = array_merge(array(
            'province_id' => null,
            'name'        => null,
            'limit'       => config('constants.item_per_page_admin'),
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);
        $params['limit'] = (int)$params['limit'] > 0 ? (int)$params['limit'] : config('constants.item_per_page_admin');

        $districts = District::select('*');

        if ($filter['name'])
            $districts->where('name', 'like', "%{$filter['name']}%");

        if ($filter['province_id'])
            $districts->where('province_id', $filter['province_id']);

        $districts = $districts->orderBy('priority')->paginate($params['limit'])->withPath($params['pagin_path']);

        $this->_data['districts'] = $districts;
        $this->_data['filter'] = $filter;
        $this->_data['start'] = ($districts->currentPage() - 1) * $params['limit'];
        $this->_data['_limits'] = config('constants.limit_records');

        $this->_data['provinces'] = Province::where('status', 1)->orderBy('priority')->get();

        return view('backend.location.district.index', $this->_data);
    }

    public function add(Request $request)
    {
        $validator_rule = [
            'name_origin' => ['required', 'string'],
            'province_id' => ['required', 'integer'],
            'type'        => ['required', 'string', Rule::in(['Quận', 'Huyện']),],
        ];

        $params = array_merge(
            array(
                'name_origin' => null,
                'type'        => null,
                'province_id' => null,
            ), array_map('trim', $request->all())
        );

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            $check = District::whereRaw("BINARY name_origin = ?", $params['name_origin'])->count();

            if ($check)
                return redirect()->back()->withInput()->withErrors(['messagge' => 'Tên Quận/Huyện đã tồn tại. Vui lòng kiểm tra lại!']);

            try {

                District::create(
                    [
                        'name'            => $params['type'] . ' ' . $params['name_origin'],
                        'name_ascii'      => Filter::vnToAscii($params['name_origin']),
                        'name_origin'     => $params['name_origin'],
                        'type'            => $params['type'],
                        'province_id'     => $params['province_id'],
                        'user_id_created' => Auth()->guard('backend')->user()->id,
                    ]
                );

                $request->session()->flash('msg', ['info', 'Thêm thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect(Route('backend.location.district.add'));
        }

        $this->_data['provinces'] = Province::orderBy('priority')->get();
        $this->_data['subtitle'] = 'Thêm mới';
        return view('backend.location.district.add', $this->_data);
    }

    public function edit(Request $request, $id)
    {
        $district = District::findOrFail($id);

        $validator_rule = [
            'name_origin' => ['required', 'string'],
            'province_id' => ['required', 'integer'],
            'type'        => ['required', 'string', Rule::in(['Quận', 'Huyện']),],
        ];

        $params = array_merge(
            array(
                'name_origin' => null,
                'type'        => null,
                'province_id' => null,
            ), array_map('trim', $request->all())
        );

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            try {

                $district->update(
                    [
                        'name'        => $params['type'] . ' ' . $params['name_origin'],
                        'name_ascii'  => Filter::vnToAscii($params['name_origin']),
                        'name_origin' => $params['name_origin'],
                        'type'        => $params['type'],
                        'province_id' => $params['province_id'],
                    ]
                );

                $request->session()->flash('msg', ['info', 'Sửa thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect(Route('backend.location.district.index'));
        }

        $this->_data['district'] = $district;
        $this->_data['provinces'] = Province::where('status', 1)->orderBy('priority')->get();
        $this->_data['subtitle'] = 'Chỉnh sửa';
        return view('backend.location.district.edit', $this->_data);
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = District::findOrFail($id);
            $data->delete();
            $request->session()->flash('msg', ['info', 'Đã xóa thành công!']);

        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        return redirect($this->_ref ? $this->_ref : Route('backend.location.district.index'));
    }
}
