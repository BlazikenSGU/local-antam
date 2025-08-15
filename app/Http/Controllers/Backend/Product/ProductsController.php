<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\BaseBackendController;
use App\Models\Attributes;
use App\Models\CallRequest;
use App\Models\Convenience;
use App\Models\Exterior;
use App\Models\Files;
use App\Models\FrontageType;
use App\Models\Location\Alley;
use App\Models\Location\Street;
use App\Models\Product;
use App\Models\ProductAttributeValues;
use App\Models\ProductContactInfo;
use App\Models\ProductConvenience;
use App\Models\ProductExterior;
use App\Models\ProductFrontageType;
use App\Models\ProductImages;
use App\Models\ProductImagesExtra;
use App\Models\ProductNote;
use App\Models\ProductPurpose;
use App\Models\ProductsTypes;
use App\Models\ProductType;
use App\Models\ProductVariation;
use App\Models\Project;
use App\Models\Purpose;
use App\Models\VariationCombine;
use App\Models\Variations;
use App\Models\VariationValues;
use App\Utils\Category;
use App\Utils\Common as Utils;
use App\Utils\Filter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductsController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Sản phẩm',
        'subtitle' => '',
        '_type'    => 'all',
    );
    protected $_limits = [
        10, 30, 50, 100, 500, 1000, 5000, 10000
    ];

    public function __construct()
    {
        $this->_data['status'] = Product::STATUS;

        parent::__construct();
    }

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'id'              => null,
            'title'           => null,
            'sort'            => null,
            'product_type_id' => null,
            'status'          => null,
            'limit'           => config('constants.item_per_page_admin'),
        ), $request->all());

        $objProduct = new Product();

        $params['limit'] = (int)$params['limit'] > 0 ? (int)$params['limit'] : config('constants.item_per_page_admin');
        $params['keywords'] = $params['title'];
        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $params['order_by'] = 'created_at';
        $params['order_by_direction'] = 'DESC';

        $product_type = ProductType::get_by_where(['assign_key' => true, 'status' => 1], ['id', 'name', 'parent_id']);

        $all_child = [];
        $product_type_id = null;

        if ($params['product_type_id']) {
            $product_type_id = $params['product_type_id'];
            $all_child = Category::get_all_child_categories($product_type, $product_type_id);
            $all_child = array_merge($all_child, [$params['product_type_id']]);
        }

        $params['product_type_id'] = $all_child ? $all_child : null;
        $products = $objProduct->get_by_where($params);

        $start = ($products->currentPage() - 1) * config('constants.item_per_page_admin');

        $this->_data['products'] = $products;
        $this->_data['start'] = $start;
        $this->_data['filter'] = $filter;
        $this->_data['sort'] = $params['sort'];

        $this->_data['_limits'] = $this->_limits;

        if ($request->ajax()) {
            $view = \View::make('backend.products.ajaxList', $this->_data);

            $return = [
                'e' => 0,
                'r' => $view->render()
            ];
            return \Response::json($return);
        }

        $product_type_html = Category::build_select_tree($product_type, 0, '', [$product_type_id]);

        $this->_data['product_type_html'] = $product_type_html;

        return view('backend.products.index', $this->_data);
    }

    public function add($type_id, Request $request)
    {
        $aInit = Product::get_validation_admin();

        $image_ids = old('image_ids');
        $image_extra_ids = old('image_extra_ids');

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $aInit)->validate();

            DB::beginTransaction();
            try {
                $params = array_fill_keys(array_keys($aInit), null);
                $params = array_merge(
                    $params, $request->only(array_keys($params))
                );

                $params['thumbnail_id'] = empty($params['thumbnail_id']) ? $params['image_ids'][0] : $params['thumbnail_id'];
                $params['image_fb_file_id'] = $params['thumbnail_id'];
                $params['company_id'] = config('constants.company_id');

                $params['slug'] = Filter::setSeoLink($params['title']);
                $params['type'] = $type_id;
                $params['can_index'] = $params['can_index'] ? 1 : 0;

                $product_type_ids = $params['product_type_ids'];
                $product_type_ids[0] = $params['product_type_id'];

                $params['product_type_ids'] = count($params['product_type_ids']) ? implode(',', $params['product_type_ids']) : [];

                $params['user_id'] = Auth()->guard('backend')->user()->id;

                $product = Product::create($params);

                if ($product) {
                    $image_ids = $image_extra_ids = [];

                    if (!empty($params['image_ids']))
                        $image_ids = Files::select(DB::raw('distinct id'))
                            ->where('is_temp', Files::IS_TEMP)
                            ->where('type', Files::TYPE_PRODUCT)
                            ->whereIn('id', $params['image_ids'])
                            ->orderByRaw("FIELD(id," . implode(',', $params['image_ids']) . ")")
                            ->pluck('id')->toArray();

                    if (!empty($params['image_extra_ids']))
                        $image_extra_ids = Files::select(DB::raw('distinct id'))
                            ->where('is_temp', Files::IS_TEMP)
                            ->where('type', Files::TYPE_PRODUCT)
                            ->whereIn('id', $params['image_extra_ids'])
                            ->pluck('id')->toArray();

                    foreach ($image_ids as $id)
                        ProductImages::create([
                            'product_id' => $product->id,
                            'file_id'    => $id,
                        ]);

                    foreach ($image_extra_ids as $id)
                        ProductImagesExtra::create([
                            'product_id' => $product->id,
                            'file_id'    => $id,
                        ]);

                    Files::whereIn('id', array_merge($image_ids, $image_extra_ids))->update(['is_temp' => null]);

                    //insert attribute
                    $insert_attributes_ids = [];
                    foreach ($request->get('attribute', []) as $k => $v) {
                        if (empty($v)) continue;
                        $v = is_array($v) ? $v : [$v];
                        $insert_attributes_ids = array_merge($insert_attributes_ids, $v);
                    }

                    if ($insert_attributes_ids) {
                        $insert_attributes = [];
                        $insert_attributes_ids = array_unique($insert_attributes_ids);
                        foreach ($insert_attributes_ids as $value) {
                            $insert_attributes[] = [
                                'product_id'         => $product->id,
                                'attribute_value_id' => $value,
                                'created_at'         => Carbon::now(),
                                'updated_at'         => Carbon::now(),
                            ];
                        }
                        ProductAttributeValues::insert($insert_attributes);
                    }

                    $product_variations = $request->get('product_variations');
                    if (!empty($product_variations)) {
                        ProductVariation::create_product_variation($product_variations, $product->id);

                        Product::change_inventory([
                            'product_id' => $product->id,
                        ]);

                        Product::update_total_color([
                            'product_id' => $product->id,
                        ]);
                    }

                    if ($product_type_ids && count($product_type_ids)) {
                        $inserts = [];
                        foreach ($product_type_ids as $v) {
                            $inserts[] = [
                                'product_id' => $product->id,
                                'type_id'    => $v,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }

                        ProductsTypes::insert($inserts);
                    }
                }

                DB::commit();
                $request->session()->flash('msg', ['info', 'Thêm sản phẩm thành công!']);
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->withInput()->withErrors(['messagge' => $e->getMessage()]);
            }
        }

        $this->_data['title'] = 'Thêm sản phẩm';
        $this->_data['subtitle'] = 'Thêm sản phẩm';
        $this->_data['product'] = null;

        $this->_data['variations'] = Variations::where('company_id', config('constants.company_id'))->get();

        $product_type = ProductType::where('company_id', config('constants.company_id'))->where('type', $type_id)->where('status', 1)->select('id', 'name', 'parent_id')->get();

        $product_type_id = old('product_type_id');
        $product_type_ids = old('product_type_ids', []);

        $product_type_html = Category::build_select_tree($product_type->toArray(), 0, '', [$product_type_id]);
        $product_types_html = Category::build_select_tree($product_type->toArray(), 0, '', $product_type_ids);

        $attributes = Attributes::where('status', 1)->orderBy('priority')->get();

        $this->_data['relate_data'] = [
            'file_image_ids'       => $image_ids ? Files::whereIn('id', $image_ids)->orderByRaw("FIELD(id," . implode(',', $image_ids) . ")")->get() : [],
            'file_image_extra_ids' => $image_extra_ids ? Files::whereIn('id', $image_extra_ids)->get() : [],
            'status'               => Product::STATUS,
            'product_type'         => $product_type,
            'product_type_html'    => $product_type_html,
            'product_types_html'   => $product_types_html,
            'attributes'           => $attributes,
        ];

        return view('backend.products.add', $this->_data);
    }

    public function edit(Request $request, $type_id, $product_id)
    {
        $product = Product::get_by_id($product_id);

        if (!$product)
            abort(404);

        $old_product = $product->toArray();

        $aInit = Product::get_validation_admin();

        $image_ids = old('image_ids', isset($product->images) ? $product->images->pluck('id')->toArray() : []);
        $image_extra_ids = old('image_extra_ids', $product->images_extra->pluck('id'));

        $current_attributes_values_ids = ProductAttributeValues::where('product_id', $product->id)->get()->pluck('attribute_value_id');
        $current_attributes_values_ids = count($current_attributes_values_ids) ? $current_attributes_values_ids->toArray() : [];
        $this->_data['attributes_values'] = $current_attributes_values_ids;

        $product_variation = ProductVariation::get_product_variations_with_combine($product_id);

        $current_variation_list = [];

        if (count($product_variation)) {
            $current_variation_list = explode(',', $product_variation[0]['variation_id_combination']);
        }

        if ($request->getMethod() == 'POST') {
            Validator::make($request->all(), $aInit)->validate();

            DB::beginTransaction();
            try {

                $params = array_fill_keys(array_keys($aInit), null);
                $params = array_merge(
                    $params, $request->only(array_keys($params))
                );

                $product_type_ids = $params['product_type_ids'];

                $params['thumbnail_id'] = empty($params['thumbnail_id']) ? $params['image_ids'][0] : $params['thumbnail_id'];
                $params['image_fb_file_id'] = $params['thumbnail_id'];
                $params['can_index'] = $params['can_index'] ? 1 : 0;
                $params['type'] = $type_id;

                $params['slug'] = Filter::setSeoLink($params['title']);
                $params['company_id'] = config('constants.company_id');
                $params['product_type_ids'] = count($params['product_type_ids']) ? implode(',', $params['product_type_ids']) : [];
                $product_type_ids[] = $params['product_type_id'];

                $params['user_id_updated'] = Auth()->guard('backend')->user()->id;

                $bolUpdate = $product->update($params);

                if ($bolUpdate) {
                    $image_ids = $image_extra_ids = [];

                    if (!empty($params['image_ids']))
                        $image_ids = Files::select(DB::raw('distinct id'))
//                            ->where('is_temp', Files::IS_TEMP)
                            ->where('type', Files::TYPE_PRODUCT)
                            ->whereIn('id', $params['image_ids'])
                            ->orderByRaw("FIELD(id," . implode(',', $params['image_ids']) . ")")
                            ->pluck('id')->toArray();

                    if (!empty($params['image_extra_ids']))
                        $image_extra_ids = Files::select(DB::raw('distinct id'))
//                            ->where('is_temp', Files::IS_TEMP)
                            ->where('type', Files::TYPE_PRODUCT)
                            ->whereIn('id', $params['image_extra_ids'])
                            ->pluck('id')->toArray();

                    ProductImages::where('product_id', $product_id)->delete();
                    foreach ($image_ids as $id)
                        ProductImages::create([
                            'product_id' => $product_id,
                            'file_id'    => $id,
                        ]);

                    ProductImagesExtra::where('product_id', $product_id)->delete();
                    foreach ($image_extra_ids as $id)
                        ProductImagesExtra::create([
                            'product_id' => $product_id,
                            'file_id'    => $id,
                        ]);

                    Files::whereIn('id', array_merge($image_ids, $image_extra_ids))->update(['is_temp' => null]);

                    //update attribute
                    $new_attributes_value_ids = [];
                    foreach ($request->get('attribute', []) as $k => $v) {
                        if (empty($v)) continue;
                        $v = is_array($v) ? $v : [$v];
                        $new_attributes_value_ids = array_merge($new_attributes_value_ids, $v);
                    }

                    $delete_attributes_ids = $insert_attributes_ids = [];
                    if (empty($new_attributes_value_ids)) {
                        $delete_attributes_ids = array_unique($new_attributes_value_ids);
                    } else {
                        $new_attributes_value_ids = array_unique($new_attributes_value_ids);
                        $delete_attributes_ids = array_diff($current_attributes_values_ids, $new_attributes_value_ids);
                        $insert_attributes_ids = array_diff($new_attributes_value_ids, $current_attributes_values_ids);
                    }

                    if ($delete_attributes_ids) {
                        ProductAttributeValues::whereIn('attribute_value_id', $delete_attributes_ids)
                            ->where('product_id', $product->id)
                            ->delete();//forceDelete()
                    }

                    if ($insert_attributes_ids) {
                        $insert_attributes = [];
                        foreach ($insert_attributes_ids as $value) {
                            $insert_attributes[] = [
                                'product_id'         => $product->id,
                                'attribute_value_id' => $value,
                                'created_at'         => Carbon::now(),
                                'updated_at'         => Carbon::now(),
                            ];
                        }
                        ProductAttributeValues::insert($insert_attributes);
                    }
                }

                if (count($product_variation)) {
                    if (!empty($request->get('product_variations'))) {
                        ProductVariation::update_product_variation($request->get('product_variations'), $product_id);
                    }
                } else if (!empty($request->get('product_variations'))) {
                    ProductVariation::create_product_variation($request->get('product_variations'), $product_id);
                }

                if (count($product_variation) || !empty($request->get('product_variations'))) {
                    Product::change_inventory([
                        'product_id' => $product_id,
                    ]);
                }

                Product::update_total_color([
                    'product_id' => $product_id,
                ]);

                ProductsTypes::where('product_id', $product_id)->delete();

                if ($product_type_ids && count($product_type_ids)) {
                    $inserts = [];
                    foreach ($product_type_ids as $v) {
                        $inserts[] = [
                            'product_id' => $product_id,
                            'type_id'    => $v,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    }

                    ProductsTypes::insert($inserts);
                }

                DB::commit();
                $request->session()->flash('msg', ['info', 'Chỉnh sửa thành công!']);
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::debug($e);
                return redirect()->back()->withInput()->withErrors(['messagge' => $e->getMessage()]);
            }
        }

        $this->_data['title'] = 'Sửa sản phẩm';
        $this->_data['subtitle'] = 'Sửa sản phẩm';

        $this->_data['product'] = $product;
        $this->_data['current_variation_list'] = $current_variation_list;

        $this->_data['product_variation'] = $product_variation;

        $this->_data['variations'] = Variations::all();
        $this->_data['variations_array'] = $this->_data['variations']->groupBy('id')->toArray();

        $product_type = ProductType::where('company_id', config('constants.company_id'))->where('type', $type_id)->where('status', 1)->select('id', 'name', 'parent_id')->get();

        $product_type_id = old('product_type_id', $product->product_type_id);
        $product_type_ids = old('product_type_ids', $product->product_type_ids ? explode(',', $product->product_type_ids) : []);

        $product_type_html = Category::build_select_tree($product_type->toArray(), 0, '', [$product_type_id]);
        $product_types_html = Category::build_select_tree($product_type->toArray(), 0, '', $product_type_ids);

        $attributes = Attributes::where('status', 1)->orderBy('priority')->get();

        $this->_data['relate_data'] = [
            'file_image_ids'       => $image_ids ? Files::whereIn('id', $image_ids)->orderByRaw("FIELD(id," . implode(',', $image_ids) . ")")->get() : [],
            'file_image_extra_ids' => $image_extra_ids ? Files::whereIn('id', $image_extra_ids)->get() : [],
            'status'               => Product::STATUS,
            'product_type'         => $product_type,
            'product_type_html'    => $product_type_html,
            'product_types_html'   => $product_types_html,
            'attributes'           => $attributes,
        ];

        $_view = 'backend.products.edit';

        return view($_view, $this->_data);
    }

    public function approved(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array',
        ]);

        if ($validator->fails()) {
            return \Response::json([
                'e' => 1,
                'r' => $validator->errors()->first()
            ]);
        }
        $product_ids = $request->get('product_ids');

        Product::whereIn('id', $product_ids)->update(['status' => 1]);

        $return = [
            'e' => 0,
            'r' => ''
        ];
        return \Response::json($return);
    }

    public function un_approved(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array',
        ]);

        if ($validator->fails()) {
            return \Response::json([
                'e' => 1,
                'r' => $validator->errors()->first()
            ]);
        }
        $product_ids = $request->get('product_ids');

        Product::whereIn('id', $product_ids)->update(['status' => 2]);

        $return = [
            'e' => 0,
            'r' => ''
        ];
        return \Response::json($return);
    }

    public function ajaxdelete(Request $request)
    {
       // print_r($request);
       // exit();
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array',
        ]);

        if ($validator->fails()) {
            return \Response::json([
                'e' => 1,
                'r' => $validator->errors()->first()
            ]);
        }
        $product_ids = $request->get('product_ids');

        Product::whereIn('id', $product_ids)->delete();
        $return = [
            'e' => 0,
            'r' => ''
        ];
        return \Response::json($return);
    }

    public function deleteVariation(Request $request)
    {
        $product_variation_ids = $request->get('product_variation_ids');
        $product_id = $request->get('product_id');

        if (!$product_variation_ids || !is_array($product_variation_ids) || !$product_id)
            return \Response::json(['e' => 1, 'r' => 'Dữ liệu không hợp lệ!']);

        ProductVariation::delete_by_where([
            'product_variation_ids' => $product_variation_ids,
        ]);

        Product::change_inventory([
            'product_id' => $product_id,
        ]);

        return \Response::json(['e' => 0, 'r' => 'Thao tác thành công!']);
    }

    public function createVariation(Request $request)
    {
        $product_variation = $request->get('product_variation');
        $product_id = $request->get('product_id');

        if (!$product_variation || !is_array($product_variation) || !$product_id)
            return \Response::json(['e' => 1, 'r' => 'Dữ liệu không hợp lệ!']);

        $variation_value_combination = [];
        foreach ($product_variation['value'] as $k => $v) {
            $variation_value_combination[$k] = mb_ucfirst(trim(strip_tags($v)));
        }

        $name = implode('/', $variation_value_combination);
        $product_variation_check = ProductVariation::get_by_name([
            'name'       => $name,
            'product_id' => $product_id,
        ]);

        if (!empty($product_variation_check))
            return \Response::json([
                'e' => 1,
                'r' => 'Phiên bản đã tồn tại!'
            ]);
        $price = (int)$product_variation['price'] > 0 ? (int)$product_variation['price'] : null;
        $product_code = strip_tags($product_variation['product_code']);
        $product_code = $product_code ? $product_code : null;
        $inventory = (int)$product_variation['inventory'] > 0 ? (int)$product_variation['inventory'] : null;
        if (isset($product_variation['inventory_management'])) {
            $inventory_management = $product_variation['inventory_management'] == 'on' ? 1 : 0;
        } else {
            $inventory_management = 0;
        }
        if (isset($product_variation['inventory_policy'])) {

            $inventory_policy = $product_variation['inventory_policy'] == 'on' ? 1 : 0;
        } else {
            $inventory_policy = 0;
        }

        $product_variation = ProductVariation::create([
            'product_id'           => $product_id,
            'name'                 => $name,
            'price'                => $price,
            'product_code'         => $product_code,
            'inventory_management' => $inventory_management,
            'inventory'            => $inventory,
            'inventory_policy'     => $inventory_policy,
        ]);

        foreach ($variation_value_combination as $k3 => $v3) {
            $variation_value = VariationValues::where('variation_id', $k3)
                ->where('value', $v3)
                ->first();

            if (empty($variation_value)) {
                $variation_value = VariationValues::create([
                    'variation_id' => $k3,
                    'value'        => $v3,
                ]);
            }
            VariationCombine::create([
                'product_variation_id' => $product_variation->id,
                'variation_value_id'   => $variation_value->id,
            ]);
        }

        Product::change_inventory([
            'product_id' => $product_id,
        ]);

        return \Response::json([
            'e' => 0,
            'r' => 'Đã thêm thành công!'
        ]);
    }

    public function getVariationImage(Request $request)
    {
        $params = array_merge([
            'product_id'           => null,
            'product_variation_id' => null,
        ], $request->all());

        $galleries = [];

        if (!empty($params['product_id']) && !empty($params['product_variation_id'])) {
            $product_variation = ProductVariation::where('id', $params['product_variation_id'])
                ->where('product_id', $params['product_id'])
                ->first();

            $galleries = $product_variation && $product_variation->gallery ? json_decode($product_variation->gallery, true) : [];
        }

        $html = view('backend.products.variation.ajax.modal_product_variation_images')
            ->with('galleries', $galleries)
            ->with('product_id', $params['product_id'])
            ->with('product_variation_id', $params['product_variation_id'])
            ->render();

        return \Response::json(['e' => 0, 'r' => $html]);
    }

    public function uploadVariationImage(Request $request)
    {
        $params = array_merge([
            'product_id'           => null,
            'product_variation_id' => null,
            'type'                 => null,
        ], $request->all());

        $return = [
            'e' => 0,
            'r' => '',
            'i' => '',
            's' => '',
        ];

        try {
            if (!empty($params['product_id']) && !empty($params['product_variation_id']) && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $product_variation = ProductVariation::where('id', $params['product_variation_id'])
                    ->where('product_id', $params['product_id'])
                    ->first();

                if ($product_variation) {
                    //upload
                    $photo = $request->file('image');

                    $sub_dir = date('Y/m/d');
                    $full_dir = config('constants.upload_dir.root') . '/' . $sub_dir;

                    $filename = uniqid();
                    $ext = $photo->extension();

                    $origin_file_name = $filename . '.' . $ext;

                    $file_path = $sub_dir . '/' . $origin_file_name;

                    $origin_file_path = $full_dir . '/' . $origin_file_name;

                    if (!is_dir($full_dir))
                        mkdir($full_dir, 0755, true);

                    $image = Image::make($photo)->orientate();

                    $width = $size_w = 1024;
                    $height = $size_h = 1024;

                    if ($image->width() > $width || $image->height() > $height) {
                        $width = null;
                        $height = null;

                        if ($image->width() < $image->height()) {
                            $height = $size_h;
                            $image->resize($width, $height, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        } else {
                            $width = $size_w;
                            $image->resize($width, $height, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            if ($image->height() > $size_h) {
                                $height = $size_h;
                                $width = null;
                                $image->resize($width, $height, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                            }
                        }
                    }

                    if (in_array($ext, ['webp', 'png']))
                        $image_new = Image::canvas($size_w, $size_h);
                    else
                        $image_new = Image::canvas($size_w, $size_h, "#fff");

                    $image_new->insert($image, 'center');

                    $image_new->save($origin_file_path, 75);

                    $type = $request->get('type', Files::TYPE_PRODUCT);

                    $image = Files::create([
                        'user_id'   => Auth()->guard('backend')->user()->id,
                        'file_path' => $file_path,
                        'type'      => $type
                    ]);

                    $galleries = $product_variation && $product_variation->gallery ? json_decode($product_variation->gallery, true) : [];

                    $galleries[] = [
                        'file_id'   => $image->id,
                        'file_path' => $file_path,
                    ];
                    $product_variation->gallery = json_encode(array_values($galleries));
                    $product_variation->save();

                    $image_src = \App\Utils\Links::ImageLink($file_path, true);

                    $html = "<div class=\"col-md-3\">
                                <p class=\"text-center\">
                                    <img src=\"{$image_src}\" class=\"img-bordered\" height=\"100\"/>
                                    <a href=\"javascript:;\" class=\"delete_product_variaton_image\"
                                       data-picture=\"{$image->id}\">Xóa</a>
                                </p>
                            </div>";

                    $return = [
                        'e' => 0,
                        'r' => $html,
                        'i' => \App\Utils\Links::ImageLink($galleries[0]['file_path'], true),
                        's' => $galleries[0]['file_id'],
                    ];
                }
            }
        } catch (\Exception $e) {
            return \Response::json(['e' => 1, 'r' => $e->getMessage()]);
        }

        return \Response::json($return);
    }

    public function deleteVariationImage(Request $request)
    {
        $params = array_merge([
            'product_id'           => null,
            'product_variation_id' => null,
            'file_id'              => null,
        ], $request->all());

        if (!empty($params['product_id']) && !empty($params['product_variation_id']) && !empty($params['file_id'])) {
            $product_variation = ProductVariation::where('id', $params['product_variation_id'])
                ->where('product_id', $params['product_id'])
                ->first();

            $galleries = $product_variation && $product_variation->gallery ? json_decode($product_variation->gallery, true) : [];

            if (count($galleries)) {
                foreach ($galleries as $k => $v) {
                    if ($v['file_id'] == $params['file_id']) {
                        unset($galleries[$k]);
                        break;
                    }
                }
                $galleries = array_values($galleries);
                $product_variation->gallery = json_encode($galleries);
                $product_variation->save();
            }
        }

        $img_src = \App\Utils\Links::ImageLink($galleries ? $galleries[0]['file_path'] : '', true);

        return \Response::json(['e' => 0, 'r' => 'Thao tác thành công!', 'i' => $img_src,]);
    }

    public function inventory(Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $aDataPost = array_merge(
                array(
                    'product_id'           => NULL,
                    'product_variation_id' => NULL,
                    'inventory'            => NULL,
                ), $request->all()
            );

            if (empty($aDataPost['product_id']) || $aDataPost['inventory'] < 0) {
                return \Response::json([
                    'e' => 0,
                    'r' => 'Dữ liệu không hợp lệ',
                    'i' => $aDataPost['inventory'],
                ]);
            }

            if ($aDataPost['product_variation_id']) {
                ProductVariation::find($aDataPost['product_variation_id'])
                    ->update([
                        'inventory' => (int)$aDataPost['inventory']
                    ]);
            } else {
                Product::find($aDataPost['product_id'])
                    ->update([
                        'inventory' => (int)$aDataPost['inventory']
                    ]);
            }

            Product::change_inventory([
                'product_id' => $aDataPost['product_id'],
            ]);

            return \Response::json([
                'e' => 0,
                'r' => 'Success',
                'i' => number_format($aDataPost['inventory']),
            ]);
        }

        $filter = $params = array_merge(array(
            'id'              => null,
            'title'           => null,
            'sort'            => null,
            'product_type_id' => null,
            'status'          => null,
            'limit'           => config('constants.item_per_page_admin'),
        ), $request->all());

        $objProduct = new Product();

        $params['limit'] = (int)$params['limit'] > 0 ? (int)$params['limit'] : config('constants.item_per_page_admin');
        $params['pagin_path'] = Utils::get_pagin_path($filter);
        $params['get_variation'] = 1;
        $params['inventory_management'] = 1;
        $params['order_by'] = 'lck_product.created_at';
        $params['order_by_direction'] = 'DESC';

        $product_type = ProductType::get_by_where(['assign_key' => true,], ['id', 'name', 'parent_id']);

        $all_child = [];
        $product_type_id = null;

        if ($params['product_type_id']) {
            $product_type_id = $params['product_type_id'];
            $all_child = Category::get_all_child_categories($product_type, $product_type_id);
            $all_child = array_merge($all_child, [$params['product_type_id']]);
        }

        $params['product_type_id'] = $all_child ? $all_child : null;
        $products = $objProduct->get_by_where($params);

        $start = ($products->currentPage() - 1) * config('constants.item_per_page_admin');

        $this->_data['products'] = $products;
        $this->_data['start'] = $start;
        $this->_data['filter'] = $filter;
        $this->_data['sort'] = $params['sort'];
        $this->_data['_limits'] = $this->_limits;

        $this->_data['title'] = 'Quản lý kho';
        $this->_data['subtitle'] = 'Quản lý kho';

        if ($request->ajax()) {
            $view = \View::make('backend.products.inventory.ajaxList', $this->_data);

            $return = [
                'e' => 0,
                'r' => $view->render()
            ];
            return \Response::json($return);
        }

        $product_type_html = Category::build_select_tree($product_type, 0, '', [$product_type_id]);

        $this->_data['product_type_html'] = $product_type_html;

        return view('backend.products.inventory.index', $this->_data);
    }

    public function sortVariationImage(Request $request)
    {
        $params = array_merge([
            'product_id'           => null,
            'product_variation_id' => null,
            'file_ids'             => null,
        ], $request->all());

        if (!empty($params['product_id']) && !empty($params['product_variation_id']) && !empty($params['file_ids'])) {
            $product_variation = ProductVariation::where('id', $params['product_variation_id'])
                ->where('product_id', $params['product_id'])
                ->first();

            $files = Files::whereIn('id', $params['file_ids'])
                ->orderByRaw("FIELD(id," . implode(',', $params['file_ids']) . ")")
                ->get();

            $galleries = [];

            foreach ($files as $file) {
                $galleries[] = [
                    'file_id'   => $file->id,
                    'file_path' => $file->file_path,
                ];
            }

            $galleries = array_values($galleries);
            $product_variation->gallery = json_encode($galleries);
            $product_variation->save();
        }

        return \Response::json(['e' => 0, 'r' => 'Thao tác thành công!', 'i' => '',]);
    }
}
