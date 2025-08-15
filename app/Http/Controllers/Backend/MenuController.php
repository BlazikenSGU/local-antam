<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\Menus;
use App\Utils\Common as Utils;
use App\Utils\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends BaseBackendController
{
    protected $_data = array(
        'title'      => 'Menu website',
        'subtitle'   => 'Menu website',
        'route_type' => 'menu',
        'menu_type'  => null,
    );

    public function __construct()
    {
        parent::__construct();

        $this->_data['status'] = Menus::STATUS;
        $this->_data['menu_type'] = Menus::TYPE_PRODUCT;
    }

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'name'   => null,
            'status' => null,
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data = Menus::orderBy('priority', 'ASC')
            ->where('company_id', config('constants.company_id'))
            ->where('type', $this->_data['menu_type'])
            ->get();

        $_data = Menu::buildTree($data->toArray());
        $type_html = Menu::get_menu($_data);

        $this->_data['list_data'] = $data;
        $this->_data['type_html'] = $type_html;
        $this->_data['filter'] = $filter;
        $this->_data['start'] = 0;

        return view('backend.menu.index', $this->_data);
    }

    public function add(Request $request)
    {
        $validator_rule = Menus::get_validation_admin();

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            $params = array_fill_keys(array_keys($validator_rule), null);
            $params = array_merge(
                $params, $request->only(array_keys($validator_rule))
            );

            try {
                $params['user_id'] = Auth()->guard('backend')->user()->id;
                $params['type'] = $this->_data['menu_type'];
                $params['company_id'] = config('constants.company_id');

                Menus::create($params);

                $request->session()->flash('msg', ['info', 'Thêm thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }

        $this->_data['subtitle'] = 'Thêm mới';
        return view('backend.menu.add', $this->_data);
    }

    public function edit(Request $request, $id)
    {
        $data = Menus::findOrFail($id);

        $validator_rule = Menus::get_validation_admin();

        if ($request->getMethod() == 'POST') {
            Validator::make($request->all(), $validator_rule)->validate();

            try {
                $params = array_fill_keys(array_keys($validator_rule), null);
                $params = array_merge(
                    $params, $request->only(array_keys($validator_rule))
                );
                $params['company_id'] = config('constants.company_id');

                $params['type'] = $this->_data['menu_type'];
                unset($params['parent_id']);
                $data->update($params);

                $request->session()->flash('msg', ['info', 'Sửa thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }

        $this->_data['data'] = $data;
        $this->_data['subtitle'] = 'Chỉnh sửa';

        return view('backend.menu.edit', $this->_data);
    }

    public function sort(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            $arrX = array_merge(array(
                'data' => null,
            ), $request->all());

            $data = json_decode($arrX['data'], true);

            $aY = Menu::build_array($data);
            $i = 0;

            foreach ($aY as $row) {
                if (empty($row['id']))
                    continue;

                $i++;
                $update = array(
                    'parent_id' => $row['parent_id'],
                    'priority'  => $i,
                );
                Menus::find($row['id'])->update($update);
            }
            $return = [
                'e' => 0,
                'r' => ''
            ];
            return \Response::json($return);
        }
        exit;
    }

    public function delete(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            try {
                if (!$request->get('id'))
                    exit;

                Menus::where('parent_id', $request->get('id'))->update(['parent_id' => null]);

                $data = Menus::findOrFail($request->get('id'));
                $data->delete();
                $return = [
                    'e' => 0,
                    'r' => 'Đã xóa thành công!'
                ];

            } catch (\Exception $e) {
                $return = [
                    'e' => 1,
                    'r' => 'Có lỗi xảy ra, vui lòng thử lại!'
                ];
            }
            return \Response::json($return);
        }
        exit;
    }
}
