<?php

namespace App\Models;

use App\Models\Location\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    const STATUS_HIDE = 0;
    const STATUS_SHOW = 1;
    const STATUS = [
        ['id' => 0, 'name' => 'Ẩn'],
        ['id' => 1, 'name' => 'Hiển thị'],
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_post';
    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'excerpt',
        'detail',
        'thumbnail_file_id',
        'image_extra_file_id',
        'image_fb_file_id',
        'user_id',
        'views',
        'status',
        'video_url',
        'custom_link',
        'tags',
        'sticky',
        'seo_title',
        'seo_descriptions',
        'seo_keywords',
        'category_id',
        'can_index',
        'province_id',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['company_id'];

    protected $appends = array('link_webview');

    public static function get_by_where($params = [], $columns = [])
    {
        $params = array_merge(
            [
                'id'           => null,
                'skip_ids'     => null,
                'keyword'      => null,
                'category_ids' => null,
                'category_id' => null,
                'status'       => null,
                'multi_status' => null,
                'pagin_path'   => null,
                'limit'        => 10,
                'pagin'        => true,

                'order_by'           => null,
                'order_by_direction' => 'ASC',
                'sort'               => null,
            ],
            $params
        );

        $columns = array_merge([
            'id',
            'name',
            'slug',
            'category_id',
            'excerpt',
            'thumbnail_file_id',
            'views',
            'category_id',
            'created_at',
        ], $columns);

        $data = self::select($columns)
            ->with(['thumbnail', 'category']);
        $data->where('company_id', config('constants.company_id'));

        if ($params['id'])
            $data->where('id', $params['id']);

        if ($params['skip_ids'])
            $data->whereNotIn('id', $params['skip_ids']);

        if ($params['keyword'])
            $data->where('name', 'like', "%{$params['keyword']}%");

        if ($params['category_ids'])
            $data->whereIn('category_id', $params['category_ids']);

        if ($params['category_id'])
            $data->where('category_id', $params['category_id']);

        if ($params['status'])
            $data->where('status', $params['status']);

        $aSort = [
            'recently_changed' => [
                'column'    => 'updated_at',
                'direction' => 'DESC'
            ],
            'newest'           => [
                'column'    => 'created_at',
                'direction' => 'DESC'
            ],
            'oldest'           => [
                'column'    => 'created_at',
                'direction' => 'ASC'
            ],
            'top-view'         => [
                'column'    => 'views',
                'direction' => 'desc'
            ],
        ];

        if (isset($aSort[$params['sort']])) {
            $data->orderBy($aSort[$params['sort']]['column'], $aSort[$params['sort']]['direction']);
        } else if ($params['order_by']) {
            $data->orderBy($params['order_by'], $params['order_by_direction']);
        } else {
            $data->orderBy('created_at', 'DESC');
        }

        if ($params['pagin']) {
            $data = $data->paginate($params['limit'])
                ->withPath($params['pagin_path']);
        } else {
            if ($params['limit'])
                $data = $data->limit($params['limit']);

            $data = $data->get();
        }

        return $data;
    }

    public static function get_validation_admin()
    {
        return [
            'name'                => 'required|string',
            'slug'                => 'nullable|string',
            'excerpt'             => 'nullable|string',
            'detail'              => 'nullable|string',
            'category_id'         => 'nullable|integer',
            'status'              => 'nullable|in:0,1',
            'seo_title'           => 'nullable|string',
            'seo_descriptions'    => 'nullable|string',
            'seo_keywords'        => 'nullable|string',
            'user_id'             => 'nullable|integer',
            'thumbnail_file_id'   => 'nullable|integer|exists:lck_files,id',
            'image_extra_file_id' => 'nullable|integer|exists:lck_files,id',
            'image_fb_file_id'    => 'nullable|integer|exists:lck_files,id',
            'can_index'           => 'nullable|in:0,1',
            'province_id'         => 'nullable',
        ];
    }

    public function getLinkWebViewAttribute()
    {
        return url('/webview/' . $this->id);
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id', 'id')
            ->select(['id', 'name', 'slug']);
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id')->select(['id', 'name', 'name_origin']);
    }

    public function thumbnail()
    {
        return $this->belongsTo(Files::class, 'thumbnail_file_id', 'id')->select(['id', 'file_path']);
    }

    public function image_extra()
    {
        return $this->belongsTo(Files::class, 'image_extra_file_id', 'id')->select(['id', 'file_path']);
    }

    public function image_fb()
    {
        return $this->belongsTo(Files::class, 'image_fb_file_id', 'id')->select(['id', 'file_path']);
    }

    public function post_link()
    {
        return "/tin-tuc/{$this->slug}";
    }
}
