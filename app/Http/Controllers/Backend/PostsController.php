<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\Files;
use App\Models\Location\Province;
use App\Models\Post;
use App\Models\PostCategory;
use App\Utils\Category;
use App\Utils\Common as Utils;
use App\Utils\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostsController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Quản lý bài viết',
        'subtitle' => 'Quản lý bài viết',
    );

    public function __construct()
    {
        $this->_data['status'] = Post::STATUS;
        parent::__construct();
    }

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'name'        => null,
            'category_id' => null,
            'status'      => null,
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data = Post::where('company_id', config('constants.company_id'))->orderBy('created_at', 'DESC')->get();

        $this->_data['list_data'] = $data;
        $this->_data['filter'] = $filter;
        $this->_data['start'] = 0;

        return view('backend.posts.index', $this->_data);
    }

    public function add(Request $request)
    {
        $validator_rule = Post::get_validation_admin();

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

                Post::create($params);

                $request->session()->flash('msg', ['info', 'Thêm thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }

        $categories = PostCategory::where('company_id', config('constants.company_id'))->select('id', 'name', 'parent_id')->get();
        $categories_html = Category::build_select_tree($categories->toArray(), 0, '', [old('category_id')]);
        $province = Province::orderBy('priority', 'ASC')->get();
        $this->_data['province'] = $province;
        $this->_data['categories'] = $categories;
        $this->_data['categories_html'] = $categories_html;
        $this->_data['subtitle'] = 'Thêm mới';

        $this->_data['thumbnail_image'] = old('thumbnail_file_id') ? Files::find(old('thumbnail_file_id')) : [];
        $this->_data['image_extra'] = old('image_extra_file_id') ? Files::find(old('image_extra_file_id')) : [];
        $this->_data['image_fb'] = old('image_fb_file_id') ? Files::find(old('image_fb_file_id')) : [];

        return view('backend.posts.add', $this->_data);
    }

    public function edit(Request $request, $id)
    {
        $data = Post::findOrFail($id);

        $validator_rule = Post::get_validation_admin();

        if ($request->getMethod() == 'POST') {
            Validator::make($request->all(), $validator_rule)->validate();

            try {
                $params = array_fill_keys(array_keys($validator_rule), null);
                $params = array_merge(
                    $params, $request->only(array_keys($validator_rule))
                );
                $params['company_id'] = config('constants.company_id');

                $params['slug'] = Filter::setSeoLink($params['name']);
                $params['can_index'] = $params['can_index'] ? 1 : 0;

                $data->update($params);

                $request->session()->flash('msg', ['info', 'Sửa thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }

        $categories = PostCategory::where('company_id', config('constants.company_id'))->select('id', 'name', 'parent_id')->get();
        $categories_html = Category::build_select_tree($categories->toArray(), 0, '', [old('category_id', $data->category_id)]);
        $province = Province::orderBy('priority', 'ASC')->get();
        $this->_data['province'] = $province;
        $this->_data['categories'] = $categories;
        $this->_data['categories_html'] = $categories_html;
        $this->_data['data'] = $data;
        $this->_data['subtitle'] = 'Chỉnh sửa';

        $this->_data['thumbnail_image'] = old('thumbnail_file_id', $data->thumbnail_file_id) ? Files::find(old('thumbnail_file_id', $data->thumbnail_file_id)) : [];
        $this->_data['image_extra'] = old('image_extra_file_id', $data->image_extra_file_id) ? Files::find(old('image_extra_file_id', $data->image_extra_file_id)) : [];
        $this->_data['image_fb'] = old('image_fb_file_id', $data->image_fb_file_id) ? Files::find(old('image_fb_file_id', $data->image_fb_file_id)) : [];

        return view('backend.posts.edit', $this->_data);
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = Post::where('company_id', config('constants.company_id'))->findOrFail($id);

            $data->delete();
            $request->session()->flash('msg', ['info', 'Xóa  thành công!']);

        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        return redirect()->back();
    }
}
