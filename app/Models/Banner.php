<?php

namespace App\Models;

use App\Utils\Links;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    const TYPE_MAIN = 1,
        TYPE_BRAND = 2,
        TYPE_ADS = 3,
        TYPE_ADS_L = 4,
        TYPE_ADS_R = 5;
    const STATUS_SHOW = 1,
        STATUS_HIDE = 0;
    public static $type = [
        self::TYPE_MAIN  => 'Banner chính',
        self::TYPE_BRAND => 'Banner thương hiệu',
        self::TYPE_ADS   => 'Banner quảng cáo',
        self::TYPE_ADS_L   => 'Banner quảng cáo bên trái',
        self::TYPE_ADS_R   => 'Banner quảng cáo bên phải'
    ];
    protected $table = 'lck_banner';
    protected $hidden = [
        'company_id',
    ];
    protected $fillable = [
        'company_id',
        'title',
        'image_id',
        'image_path',
        'mobile_image_id',
        'mobile_image_path',
        'description',
        'type',
        'status',
        'url',
        'product_type_id',
    ];
    protected $appends = array('file_src', 'mobile_file_src');

    public static function get_validation_admin()
    {
        return [
            'company_id'      => 'nullable|integer',
            'title'           => 'required|string',
            'type'            => 'required|integer',
            'description'     => 'nullable|string',
            'url'             => 'nullable|string',
            'status'          => 'required|in:0,1',
            'product_type_id' => 'nullable|exists:lck_product_type,id',
            'image_id'        => 'nullable|integer|exists:lck_files,id',
            'mobile_image_id' => 'nullable|integer|exists:lck_files,id',
        ];
    }

    public static function get_by_where($params = [])
    {
        $params = array_merge(
            [
                'id'         => null,
                'status'     => null,
                'type'       => null,
                'pagin_path' => null,
                'limit'      => 10,
                'pagin'      => true,
            ],
            $params
        );

        $data = self::select('*');
        $data->where('company_id', config('constants.company_id'));

        if ($params['id'])
            $data->where('id', $params['id']);

        if ($params['status'])
            $data->where('status', $params['status']);

        if ($params['type'])
            $data->where('type', $params['type']);

        $data->orderBy('created_at', 'DESC');

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

    public function image_app()
    {
        return $this->belongsTo(Files::class, 'mobile_image_id', 'id')->select(['id', 'file_path']);
    }

    public function getFileSrcAttribute()
    {
        return Links::ImageLink($this->image_path);
    }

    public function getMobileFileSrcAttribute()
    {
        return Links::ImageLink($this->mobile_image_path);
    }
}
