<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseFrontendController;
use App\Models\CoreUsers;
use App\Models\PostCategory;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\VariationValues;
use App\Models\Wishlist;
use App\Utils\Category;
use App\Utils\Common;
use App\Utils\Common as Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use View;

class ProductController extends BaseFrontendController
{
    public function main(Request $request)
    {
        $products = Product::get_by_where([
            'keywords'    => $request->get('q', null),
            'sort'        => $request->get('sort', null),
            'price_range' => $request->get('price_range', null),
            'status'      => 1,
            'limit'       => 9,
            'pagin'       => true,
            'pagin_path'  => Common::get_pagin_path($request->all()),
        ]);
        $this->_data['products'] = $products;
        $this->_data['category'] = null;
        $this->_data['title'] = 'Sản phẩm';

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => 'Sản phẩm'
        );

        $this->_data['menu_active'] = '';
        $this->_data['breadcrumbs'] = $breadcrumbs;

        $this->_data['category_id'] = 0;
        $this->_data['all_child'] = [];

        return view('frontend.category.index', $this->_data);
    }

    public function route(Request $request, $slug)
    {
        $aSlug = explode('/', $slug);

        $slug = end($aSlug);

        $category = Category::get_by_slug($this->all_category, $slug);

        if (!empty($category))
            return $this->category($request, $category);
        else
            return $this->detail($request, $slug);
    }

    public function category(Request $request, $category)
    {
        $categories = PostCategory::get_all();

        $this->_data['all_categories'] = $categories;

        $all_child = Category::get_all_child_categories($this->all_category, $category['id']);

        $all_child_near = Category::get_all_child_categories_near($this->all_category, $category['id']);

        $this->_data['all_child'] = $all_child_near;

        $all_child = array_merge($all_child, [$category['id']]);

        $products = Product::get_by_where([
            'sort'            => $request->get('sort', null),
            'price_range'     => $request->get('price_range', null),
            'status'          => 1,
            'product_type_id' => $all_child,
            'limit'           => 9,
            'pagin'           => true,
            'pagin_path'      => Common::get_pagin_path($request->all()),
        ]);

        $this->_data['products'] = $products;
        $this->_data['category'] = $category;
        $this->_data['category_id'] = $category['id'];
        $this->_data['title'] = $category['name'];
        $this->_data['seo_title'] = $category['seo_title'];

        $this->_data['description'] = $category['description'];
        $this->_data['seo_description'] = $category['seo_descriptions'];

        $this->_data['seo_keywords'] = $category['seo_keywords'];

        if ($category['thumbnail'])
            $this->_data['image_fb_url'] = $category['thumbnail']['file_src'];

        $this->_data['can_index'] = $category['can_index'];

        $parent_cate_ids = Category::get_all_parent_id($this->all_category, $category['id']);

        $breadcrumbs = [];

        foreach ($parent_cate_ids as $v) {
            $breadcrumbs[] = array(
                'link' => $this->all_category[$v]['link'],
                'name' => $this->all_category[$v]['name'],
            );
        }

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => $category['name']
        );
        $this->_data['menu_active'] = '';
        $this->_data['breadcrumbs'] = $breadcrumbs;

        return view('frontend.category.index', $this->_data);
    }

    public function detail(Request $request, $slug)
    {
        $product = Product::get_by_slug($slug);
        if (empty($product) || $product->status != 1)
            abort(404);

        if (!isset($this->all_category[$product->product_type_id]))
            abort(404);

        $category = $this->all_category[$product->product_type_id];

        $out_of_stock = 0;

        if ($product->inventory_management && !$product->inventory_policy && $product->inventory < 1)
            $out_of_stock = 1;

        $product->out_of_stock = $out_of_stock;

        $this->_data['category'] = $category;
        $this->_data['title'] = $product->title;
        $this->_data['seo_title'] = $product->seo_title;

        $this->_data['description'] = $product->description;
        $this->_data['seo_description'] = $product->seo_descriptions;

        $this->_data['seo_keywords'] = $product->seo_keywords;

        if ($product->image_fb)
            $this->_data['image_fb_url'] = $product->image_fb->file_src;

        $this->_data['product'] = $product;

        $this->_data['arr_product_variants'] = VariationValues::get_group_variations($product->id);

        $parent_cate_ids = Category::get_all_parent_id($this->all_category, $category['id']);

        $breadcrumbs = [];

        foreach ($parent_cate_ids as $v) {
            $breadcrumbs[] = array(
                'link' => $this->all_category[$v]['link'],
                'name' => $this->all_category[$v]['name'],
            );
        }

        $breadcrumbs[] = array(
            'link' => $this->all_category[$category['id']]['link'],
            'name' => $this->all_category[$category['id']]['name'],
        );

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => $product->title
        );

        $this->_data['menu_active'] = '';
        $this->_data['breadcrumbs'] = $breadcrumbs;
        $this->_data['can_index'] = $product->can_index;

        //get related
        $related_products = Product::get_by_where([
            'status'            => 1,
            'status_censorship' => 1,
            'product_type_id'   => [$category['id']],
            'skip_product_ids'  => $product->id,
            'limit'             => 6,
            'pagin'             => false,
        ]);
        $this->_data['related_products'] = $related_products;

        return view('frontend.product.detail', $this->_data);
    }

    public function system(Request $request)
    {
        $user = CoreUsers::find(Auth::guard('web')->user()->id);

        if (!$user) return abort(404);
        $filter = $params = array_merge(array(
            'name'   => null,
            'status' => null,
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data = CoreUsers::where('referrer_id', $user->id)->get();

        $breadcrumbs = [];

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => 'Quản lý hệ thống'
        );
        $this->_data['breadcrumbs'] = $breadcrumbs;
        $this->_data['list_data'] = $data;
//        $this->_data['type_html'] = $type_html;
        $this->_data['filter'] = $filter;
        $this->_data['start'] = 0;

        return view('frontend.user.system', $this->_data);
    }

    public function ajaxUser(Request $request)
    {
        $msg = '';
        $error = '';
        $user = CoreUsers::where('referrer_id', $request->id)->get();

        $html = [];
        if (!empty($user)) {
            foreach ($user as $k => $v) {
                $fullname = $v->fullname ? $v->fullname : '';
                $name = $v->phone . '/' . $fullname;
                $html[] .= '<li><a class="onClickShowChild" data-id="' . $v->id . '">' . $name . ' </a><ul class="child_' . $v->id . ' tree-view-child" style="padding-left: 100px"></ul>';
            }
            $html[] .= '</li>';
        }
        $data = [
            'data'  => [
                'html' => $html,
            ],
            'msg'   => $msg,
            'error' => $error,
        ];
        return response()->json($data, 200);
    }

    public function variation(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $getData = array_merge(array(
                'product_id'          => null,
                'variation_value_ids' => null,
            ), $request->all());

            $product_variant = [];
            $can_buy = false;
            if (!empty($getData['product_id']) && !empty($getData['variation_value_ids'])) {

                $product_variants = ProductVariation::get_product_variations_with_combine($getData['product_id']);

                $_variation_value_ids = $getData['variation_value_ids'];
                sort($_variation_value_ids);
                foreach ($product_variants as $k => $v) {
                    $VariationValueCombination = explode(',', $v->variation_value_combination);

                    sort($VariationValueCombination);

                    if ($VariationValueCombination == $_variation_value_ids) {
                        $product_variant = $v;
                        $can_buy = true;
                        $galleries = !empty($v->gallery) ? json_decode($v->gallery, true) : [];
                        break;
                    }
                }

                if (!$can_buy) {
                    foreach ($product_variants as $k => $v) {
                        $VariationValueCombination = explode(',', $v->variation_value_combination);

                        sort($VariationValueCombination);
                        $variant_value_id = end($getData['variation_value_ids']);
                        if (in_array($variant_value_id, $VariationValueCombination)) {
                            $galleries = !empty($v->gallery) ? json_decode($v->gallery, true) : [];
                            break;
                        }
                    }
                }
            }

            if ($product_variant) {
                $out_of_stock = 0;

                if ($product_variant->inventory_management && $product_variant->inventory < 1 && !$product_variant->inventory_policy)
                    $out_of_stock = 1;

                $product_variant->out_of_stock = $out_of_stock;

                if ($out_of_stock)
                    $can_buy = 0;

                $product_variant->price_text = number_format($product_variant->price) . ' đ';
            }

            $galleries_html = '';

            if (count($galleries)) {
                $galleries_html = view('frontend.product.galleries')
                    ->with('galleries', $galleries)
                    ->render();
            } else {
                $product = Product::with('images')->find($getData['product_id']);
                $galleries_html = view('frontend.product.galleries')
                    ->with('galleries', $product->images)
                    ->render();
            }

            $return = [
                'status'         => true,
                'variation'      => $product_variant,
                'can_buy'        => $can_buy,
                'inventory_text' => $can_buy ? 'Còn hàng' : 'Hết hàng',
                'galleries_html' => $galleries_html,
            ];
            echo json_encode($return, JSON_UNESCAPED_UNICODE);
        }

        exit;
    }

    public function wishlist(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            return abort(404);
        }

        $user = CoreUsers::find(Auth::guard('web')->user()->id);

        $wish_list = Wishlist::with(['product'])
            ->where('user_id', $user->id)->get();


        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => 'Danh sách yêu thích'
        );

        $this->_data['wish_list'] = $wish_list;
        $this->_data['breadcrumbs'] = $breadcrumbs;

        return view('frontend.product.wishlist', $this->_data);
    }
    public function webviewDetail($id)
    {
        $product = Product::findOrFail($id);
        return view('frontend.product.webviewDetail', ['post' => $product]);
    }
}
