<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\Post;
use App\Models\PostCategory;
use App\Utils\Category;
use App\Models\Files;
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

class PostsCategoryController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Danh mục bài viết',
        'subtitle' => 'Danh mục bài viết',
    );

    public function __construct()
    {
        $this->_data['status'] = PostCategory::STATUS;
        parent::__construct();
    }

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'name'   => null,
            'status' => null,
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data = PostCategory::where('company_id',config('constants.company_id'))
        ->with(['thumbnail', 'icon'])->get();

        $_data = Category::buildTree($data->toArray());
        $type_html = Category::get_menu_post($_data);

        $this->_data['list_data'] = $data;
        $this->_data['type_html'] = $type_html;
        $this->_data['filter'] = $filter;
        $this->_data['start'] = 0;

        return view('backend.posts.category.index', $this->_data);
    }

    public function add(Request $request)
    {
        $validator_rule = PostCategory::get_validation_admin();

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

                PostCategory::create($params);

                $request->session()->flash('msg', ['info', 'Thêm thành công!']);
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
            }
            return redirect()->back();
        }

        $this->_data['subtitle'] = 'Thêm mới';
        $this->_data['category_file_image'] = old('thumbnail_file_id') ? Files::find(old('thumbnail_file_id')) : [];
        $this->_data['category_file_icon'] = old('icon_file_id') ? Files::find(old('icon_file_id')) : [];
        return view('backend.posts.category.add', $this->_data);
    }

    public function edit(Request $request, $id)
    {
        $data = PostCategory::findOrFail($id);

        $validator_rule = PostCategory::get_validation_admin();

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

        $this->_data['data'] = $data;
        $this->_data['subtitle'] = 'Chỉnh sửa';
        $this->_data['category_file_image'] = old('thumbnail_file_id', $data->thumbnail_file_id) ? Files::find(old('thumbnail_file_id', $data->thumbnail_file_id)) : [];
        $this->_data['category_file_icon'] = old('icon_file_id', $data->icon_file_id) ? Files::find(old('icon_file_id', $data->icon_file_id)) : [];
        return view('backend.posts.category.edit', $this->_data);
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
                PostCategory::find($row['id'])->update($update);
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

                PostCategory::where('parent_id', $request->get('id'))->update(['parent_id' => 0]);

                $data = PostCategory::where('company_id',config('constants.company_id'))
                ->findOrFail($request->get('id'));
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
