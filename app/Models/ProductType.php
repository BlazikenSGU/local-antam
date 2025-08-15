<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(@OA\Xml(name="ProductType"))
 * @OA\Property(property="id",type="integer",description="product type id"),
 * @OA\Property(property="name",type="string",description="product type name"),
 * @OA\Property(property="parent_id",type="integer",description="product type parent id"),
 **/
class ProductType extends Model
{
    const STATUS = [
        ['id' => 0, 'name' => 'Ẩn'],
        ['id' => 1, 'name' => 'Hiển thị'],
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_product_type';
    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'description',
        'detail',
        'parent_id',
        'status',
        'priority',
        'thumbnail_file_id',
        'icon_file_id',
        'type',
        'seo_title',
        'seo_descriptions',
        'seo_keywords',
        'user_id',
        'can_index',
        'color',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['company_id'];

    public static function get_all($select = '*')
    {
        $categories = self::select($select)
            ->with(['thumbnail', 'icon'])
            ->where('company_id', config('constants.company_id'))
            ->where('status', 1)
            ->orderBy('priority', 'ASC')->get();
        $return = [];
        foreach ($categories as $category) {
            $return[$category->id] = $category->toArray();
        }
        return $return;
    }

    public static function get_by_where($params = [], $select = '*')
    {
        $params = array_merge([
            'status'     => null,
            'type'     => null,
            'assign_key' => false,
        ], $params);

        $data = self::select($select)
            ->with(['thumbnail'])
            ->with(['icon'])
            ->orderBy('priority', 'ASC');

        $data->where('company_id', config('constants.company_id'));

        if ($params['type'])
            $data->where('type', $params['type']);

        if ($params['status'])
            $data->where('status', $params['status']);

        $data = $data->get();

        $return = $data;
        if ($params['assign_key'] && count($data)) {
            $return = [];
            $data = $data->toArray();
            foreach ($data as $v) {
                $return[$v['id']] = $v;
            }
        }
        return $return;
    }

    public static function get_by_where_home($params = [], $select = '*')
    {
        $params = array_merge([
            'status'     => null,
            'assign_key' => false,
        ], $params);

        $data = self::select($select)
            ->with(['thumbnail'])
            ->orderBy('priority', 'ASC');

        if ($params['status'])
            $data->where('status', $params['status']);

        $data = $data->get();

        $return = $data;
        if ($params['assign_key'] && count($data)) {
            $return = [];
            $data = $data->toArray();
            foreach ($data as $v) {
                $return[] = $v;
            }
        }
        return $return;
    }

    public static function get_validation_admin()
    {
        return [
            'name'              => 'required|string',
            'slug'              => 'nullable|string',
            'description'       => 'nullable|string',
            'detail'            => 'nullable|string',
            'parent_id'         => 'nullable|integer',
            'status'            => 'nullable|in:0,1',
            'type'              => 'nullable|in:1,2',
            'priority'          => 'nullable|integer',
            'seo_title'         => 'nullable|string',
            'seo_descriptions'  => 'nullable|string',
            'seo_keywords'      => 'nullable|string',
            'user_id'           => 'nullable|integer',
            'thumbnail_file_id' => 'nullable|integer|exists:lck_files,id',
            'icon_file_id'      => 'nullable|integer|exists:lck_files,id',
            'can_index'         => 'nullable|in:0,1',
            'color'             => 'nullable|string',
        ];
    }

    public function thumbnail()
    {
        return $this->belongsTo(Files::class, 'thumbnail_file_id', 'id')->select(['id', 'file_path']);
    }

    public function icon()
    {
        return $this->belongsTo(Files::class, 'icon_file_id', 'id')->select(['id', 'file_path']);
    }
}
