<?php

namespace App\Http\Controllers\Backend\Product\Attributes;

use App\Http\Controllers\BaseBackendController;
use App\Models\Attributes;
use App\Models\AttributeValues;
use App\Models\Post;
use App\Models\Files;
use App\Models\ProductVariation;
use App\Models\VariationCombine;
use App\Models\VariationValues;
use App\Utils\Category;
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

class ValuesController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Giá trị Thuộc tính',
        'subtitle' => 'Giá trị Thuộc tính',
    );

    public function __construct()
    {
        //$this->_data['status'] = VariationValues::STATUS;
        parent::__construct();
    }

    public function index($variation_id, Request $request)
    {
        $data = VariationValues::where('variation_id', $variation_id)->orderBy('id', 'ASC')->get();

        $this->_data['list_data'] = $data;
        $this->_data['variation_id'] = $variation_id;
        $this->_data['start'] = 0;

        return view('backend.products.attributes.values.index', $this->_data);
    }

    public function add($variation_id, Request $request)
    {
        $validator_rule = VariationValues::get_validation_admin();
        $this->_data['variation_id'] = $variation_id;

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            $params = array_fill_keys(array_keys($validator_rule), null);
            $params = array_merge(
                $params, $request->only(array_keys($validator_rule))
            );

            $check = VariationValues::where('value', $params['value'])->where('variation_id', $variation_id)->first();
            if ($check)
                return redirect()->back()->withErrors(['name' => 'Giá trị thuộc tính đã tồn tại.']);

            try {
                $params['variation_id'] = $variation_id;
                $params['user_id'] = Auth()->guard('backend')->user()->id;

                VariationValues::create($params);

                $request->session()->flash('msg', ['info', 'Thêm thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }

        $this->_data['subtitle'] = 'Thêm mới';
        return view('backend.products.attributes.values.add', $this->_data);
    }

    public function edit($variation_id, $id, Request $request)
    {
        $this->_data['variation_id'] = $variation_id;

        $data = VariationValues::findOrFail($id);

        $validator_rule = VariationValues::get_validation_admin();

        if ($request->getMethod() == 'POST') {
            Validator::make($request->all(), $validator_rule)->validate();
            $params = array_fill_keys(array_keys($validator_rule), null);
            $params = array_merge(
                $params, $request->only(array_keys($validator_rule))
            );

            $check = VariationValues::where('value', $params['value'])
                ->where('variation_id', $variation_id)
                ->where('id', '<>', $id)
                ->first();
            if ($check)
                return redirect()->back()->withErrors(['value' => 'Giá trị thuộc tính đã tồn tại.']);

            try {

                $params['variation_id'] = $variation_id;
                $data->update($params);

                $request->session()->flash('msg', ['info', 'Sửa thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }

        $this->_data['data'] = $data;
        $this->_data['subtitle'] = 'Chỉnh sửa';
        return view('backend.products.attributes.values.edit', $this->_data);
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
                    'priority' => $i,
                );
                VariationValues::find($row['id'])->update($update);
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

                $variation_value_id = $request->get('id');

                $product_variations = ProductVariation::whereRaw("id IN (select product_variation_id FROM lck_variation_combine WHERE variation_value_id = {$variation_value_id})")
                    ->get();

                $product_variation_ids = $product_variations->pluck('id')->toArray();

                VariationCombine::whereIn('product_variation_id', $product_variation_ids)->delete();

                ProductVariation::whereIn('id', $product_variation_ids)->delete();

                $data = VariationValues::findOrFail($variation_value_id);
                $data->delete();

                $return = [
                    'e' => 0,
                    'r' => 'Đã xóa thành công!'
                ];

            } catch (\Exception $e) {
                $return = [
                    'e' => 1,
                    'r' => 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()
                ];
            }
            return \Response::json($return);
        }
        exit;

    }
}
