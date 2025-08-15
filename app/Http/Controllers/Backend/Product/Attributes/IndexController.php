<?php

namespace App\Http\Controllers\Backend\Product\Attributes;

use App\Http\Controllers\BaseBackendController;
use App\Models\ProductVariation;
use App\Models\VariationCombine;
use App\Models\Variations;
use App\Models\VariationValues;
use App\Utils\Category;
use App\Utils\Common as Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndexController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Thuộc tính sản phẩm',
        'subtitle' => 'Thuộc tính sản phẩm',
    );

    public function __construct()
    {
        //$this->_data['status'] = Variations::STATUS;
        //$this->_data['type'] = Variations::TYPE;
        parent::__construct();
    }

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'name'   => null,
            'status' => null,
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data = Variations::where('company_id', config('constants.company_id'))->orderBy('id', 'ASC')->get();

        $this->_data['list_data'] = $data;
        $this->_data['type_html'] = '';
        $this->_data['filter'] = $filter;
        $this->_data['start'] = 0;

        return view('backend.products.attributes.index', $this->_data);
    }

    public function add(Request $request)
    {
        $validator_rule = Variations::get_validation_admin();

        if ($request->getMethod() == 'POST') {
            $params['company_id'] = config('constants.company_id');

            Validator::make($request->all(), $validator_rule)->validate();

            $params = array_fill_keys(array_keys($validator_rule), null);
            $params = array_merge(
                $params, $request->only(array_keys($validator_rule))
            );

            $check = Variations::where('company_id', config('constants.company_id'))->where('name', $params['name'])->first();
            if ($check)
                return redirect()->back()->withErrors(['name' => 'Thuộc tính đã tồn tại.']);

            try {
                $params['company_id'] = config('constants.company_id');
                $params['user_id'] = Auth()->guard('backend')->user()->id;

                Variations::create($params);

                $request->session()->flash('msg', ['info', 'Thêm thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }

        $this->_data['subtitle'] = 'Thêm mới';
        return view('backend.products.attributes.add', $this->_data);
    }

    public function edit(Request $request, $id)
    {
        $data = Variations::findOrFail($id);

        $validator_rule = Variations::get_validation_admin();

        if ($request->getMethod() == 'POST') {
            Validator::make($request->all(), $validator_rule)->validate();
            $params['company_id'] = config('constants.company_id');

            $params = array_fill_keys(array_keys($validator_rule), null);
            $params = array_merge(
                $params, $request->only(array_keys($validator_rule))
            );
            $check = Variations::where('name', $params['name'])->where('id', '<>', $id)->first();
            if ($check)
                return redirect()->back()->withErrors(['name' => 'Thuộc tính đã tồn tại.']);

            try {

                $data->update($params);

                $request->session()->flash('msg', ['info', 'Sửa thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }

        $this->_data['data'] = $data;
        $this->_data['subtitle'] = 'Chỉnh sửa';
        return view('backend.products.attributes.edit', $this->_data);
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
                Variations::where('company_id', config('constants.company_id'))->find($row['id'])->update($update);
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

                $variation_id = $request->get('id');

                $VariationValues = VariationValues::where('variation_id', $variation_id)->get();

                $variation_value_ids = $VariationValues->pluck('id')->toArray();

                $variation_value_ids_str = count($variation_value_ids) ? implode(',', $variation_value_ids) : null;

                if ($variation_value_ids_str) {
                    $product_variations = ProductVariation::whereRaw("id IN (select product_variation_id
                FROM lck_variation_combine WHERE variation_value_id IN ({$variation_value_ids_str}))")
                        ->get();

                    $product_variation_ids = $product_variations->pluck('id')->toArray();

                    VariationCombine::whereIn('product_variation_id', $product_variation_ids)->delete();

                    ProductVariation::whereIn('id', $product_variation_ids)->delete();

                    VariationValues::where('variation_id', $variation_id)->delete();
                }

                $data = Variations::where('company_id', config('constants.company_id'))->findOrFail($variation_id);
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
