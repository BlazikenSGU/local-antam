<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\BaseBackendController;
use App\Models\Files;
use App\Models\ProductType;
use App\Utils\Category;
use App\Utils\Common as Utils;
use App\Utils\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Danh mục sản phẩm',
        'subtitle' => 'Danh mục sản phẩm',
    );

    public function __construct()
    {
        $this->_data['status'] = ProductType::STATUS;
        parent::__construct();
    }

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'name'   => null,
            'status' => null,
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data = ProductType::where('company_id', config('constants.company_id'))
            ->with(['thumbnail', 'icon'])
            ->get();
        $_data = Category::buildTree($data->toArray());
        $type_html = Category::get_menu($_data);

        $this->_data['list_data'] = $data;
        $this->_data['type_html'] = $type_html;
        $this->_data['filter'] = $filter;
        $this->_data['start'] = 0;

        return view('backend.products.type.index', $this->_data);
    }

    public function add(Request $request)
    {
        $validator_rule = ProductType::get_validation_admin();

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            $params = array_fill_keys(array_keys($validator_rule), null);
            $params = array_merge(
                $params, $request->only(array_keys($validator_rule))
            );

            try {
                $params['company_id'] = config('constants.company_id');

                $params['slug'] = Filter::setSeoLink($params['name']);
                $params['user_id'] = Auth()->guard('backend')->user()->id;
                $params['can_index'] = $params['can_index'] ? 1 : 0;

                ProductType::create($params);

                $request->session()->flash('msg', ['info', 'Thêm thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }

        $this->_data['subtitle'] = 'Thêm mới';
        $this->_data['category_file_image'] = old('thumbnail_file_id') ? Files::find(old('thumbnail_file_id')) : [];
        $this->_data['category_file_icon'] = old('icon_file_id') ? Files::find(old('icon_file_id')) : [];
        return view('backend.products.type.add', $this->_data);
    }

    public function edit(Request $request, $id)
    {
        $data = ProductType::findOrFail($id);

        $validator_rule = ProductType::get_validation_admin();

        if ($request->getMethod() == 'POST') {
            Validator::make($request->all(), $validator_rule)->validate();

            try {
                $params = array_fill_keys(array_keys($validator_rule), null);
                $params = array_merge(
                    $params, $request->only(array_keys($validator_rule))
                );
                $params['company_id'] = config('constants.company_id');

                $params['can_index'] = $params['can_index'] ? 1 : 0;
                $params['slug'] = Filter::setSeoLink($params['name']);
                if (!empty($request->get('slug'))) {
                    $params['slug'] = $request->get('slug');
                }
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
        $this->_data['category_file_image'] = old('thumbnail_file_id', $data->thumbnail_file_id) ? Files::find(old('thumbnail_file_id', $data->thumbnail_file_id)) : [];
        $this->_data['category_file_icon'] = old('icon_file_id', $data->icon_file_id) ? Files::find(old('icon_file_id', $data->icon_file_id)) : [];
        return view('backend.products.type.edit', $this->_data);
    }

    public function sort(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            $arrX = array_merge(array(
                'data' => null,
            ), $request->all());

            $data = json_decode($arrX['data'], true);

            $aY = Category::build_array($data);
            $i = 0;
            foreach ($aY as $row) {

                if (empty($row['id']))
                    continue;

                $i++;
                $update = array(
                    'parent_id' => $row['parent_id'],
                    'priority'  => $i,
                );
                ProductType::find($row['id'])->update($update);
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

                ProductType::where('parent_id', $request->get('id'))->update(['parent_id' => 0]);

                $data = ProductType::findOrFail($request->get('id'));
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
