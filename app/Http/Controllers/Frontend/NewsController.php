<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseFrontendController;
use App\Http\Controllers\BaseFrontendNewsController;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\ProductType;
use App\Models\Settings;
use App\Utils\Category;
use App\Utils\Filter;
use App\Utils\Common as Utils;
use Illuminate\Http\Request;

class NewsController extends BaseFrontendController
{
    protected $_data = [];

    public function __construct()
    {
        parent::__construct();

        $categories = PostCategory::get_all();
        $array_categories_db = ProductType::get_all();


        $this->_data['array_categories'] = Category::treeToArray($this->_data['array_tree_categories']);
        $array_categories_db = ProductType::get_by_where([
            'status'     => 1,
            'type'       => 1,
            'assign_key' => true,
        ]);
        $array_categories_db_2 = ProductType::get_by_where([
            'status'     => 1,
            'type'       => 2,
            'assign_key' => true,
        ]);

        $this->_data['array_tree_categories'] = Category::buildTreeType($array_categories_db);
        $this->_data['array_tree_categories_2'] = Category::buildTreeType($array_categories_db_2);
        $this->_data['all_categories'] = $categories;
        $this->_data['category_id'] = null;

        $tree_categories = Category::buildTreeNews($categories);

        $this->_data['tree_categories'] = $tree_categories;

        $this->_data['top_post'] = Post::get_by_where([
            'pagin'  => false,
            'limit'  => 5,
            'sort'   => 'top-view',
            'status' => Post::STATUS_SHOW,
        ]);
    }

    public function main(Request $request)
    {
        $filter = array_merge(array(
            'page' => null,
            'q'    => null,
        ), $request->all());

        $this->_data['menu_active'] = 'news';

        $params = [
            'pagin_path' => Utils::get_pagin_path($filter),
            'keyword'    => $filter['q'],
            'status'     => Post::STATUS_SHOW,
            'limit'      => 4,
        ];

        $this->_data['items'] = Post::get_by_where($params);

        $this->_data['title'] = 'Tin tức';
        $this->_data['category'] = [];

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => 'Tin tức'
        );

        $this->_data['breadcrumbs'] = $breadcrumbs;

        return view('frontend.news.index', $this->_data);
    }

    public function route(Request $request, $slug)
    {
        $aSlug = explode('/', $slug);

        $slug = end($aSlug);

        $category = PostCategory::where('slug', $slug)
            ->where('status', PostCategory::STATUS_SHOW)
            ->first();

        if (!empty($category))
            return $this->category($request, $category);
        else
            return $this->detail($request, $slug);
    }

    public function category(Request $request, $category)
    {
        $filter = array_merge(array(
            'page' => null,
            'q'    => null,
        ), $request->all());

        $this->_data['menu_active'] = 'news';

        $category_ids = [];

        $category_ids[] = $category->id;

        $category_ids = array_merge($category_ids, Category::get_all_child_categories($this->_data['all_categories'], $category->id));

        $this->_data['category_id'] = $category->id;

        $params = [
            'pagin_path'   => Utils::get_pagin_path($filter),
            'keyword'      => $filter['q'],
            'status'       => Post::STATUS_SHOW,
            'category_ids' => array_unique($category_ids),
            'limit'        => 6,
        ];

        $this->_data['items'] = Post::get_by_where($params);

        $this->_data['title'] = $category->name;
        $this->_data['seo_title'] = $category->seo_title;

        $this->_data['description'] = $category->description;
        $this->_data['seo_description'] = $category->seo_descriptions;

        $this->_data['seo_keywords'] = $category->seo_keywords;

        $this->_data['category'] = $category;

        if ($category) {
            $this->_data['can_index'] = $category->can_index;
            $this->_data['title'] = $category->name;
        }

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => 'Tin tức'
        );

        $this->_data['breadcrumbs'] = $breadcrumbs;

        return view('frontend.news.index', $this->_data);
    }

    public function detail(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->where('status', Post::STATUS_SHOW)
            ->with(['thumbnail', 'image_fb', 'category'])
            ->first();

        if (empty($post))
            return abort(404);

        $this->_data['menu_active'] = 'news';

        $this->_data['title'] = $post->name;
        $this->_data['seo_title'] = $post->seo_title;

        $this->_data['description'] = $post->description;
        $this->_data['seo_description'] = $post->seo_descriptions;

        $this->_data['seo_keywords'] = $post->seo_keywords;

        if($post->image_fb)
            $this->_data['image_fb_url'] = $post->image_fb->file_src;

        $this->_data['post'] = $post;

        $post->views = (int)$post->views + 1;
        $post->save();

        $relative_post = [];

        if ($post->category_id) {
            $relative_post = Post::get_by_where([
                'pagin'        => false,
                'limit'        => 5,
                'category_ids' => [$post->category_id],
                'skip_ids'     => [$post->id],
                'status'       => Post::STATUS_SHOW,
            ]);
        }

        $this->_data['relative_post'] = $relative_post;
        $this->_data['category_id'] = $post->id;
        $this->_data['can_index'] = $post->can_index;

        $breadcrumbs[] = array(
            'link' => '/tin-tuc',
            'name' => 'Tin tức'
        );

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => $post->name
        );

        $this->_data['breadcrumbs'] = $breadcrumbs;

        return view('frontend.news.detail', $this->_data);
    }
}
