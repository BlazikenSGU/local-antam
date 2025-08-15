<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(@OA\Xml(name="District"))
 * @OA\Property(property="id",type="integer",description="id"),
 * @OA\Property(property="name",type="string",description="Tên quận/huyện"),
 **/
class District extends Model
{
    use SoftDeletes;
    protected $table = 'lck_location_district';

    protected $fillable = [
        'id',
        'name',
        'name_ascii',
        'name_origin',
        'location',
        'type',
        'province_id',
        'DistrictID',
        'ProvinceID',
        'user_id_created',
        'priority',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function province()
    {
        return $this->belongsTo(Province::class)->select(['id', 'name']);
    }
}
