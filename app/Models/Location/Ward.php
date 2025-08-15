<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(@OA\Xml(name="Ward"))
 * @OA\Property(property="id",type="integer",description="id"),
 * @OA\Property(property="name",type="string",description="Tên phường/xã"),
 **/
class Ward extends Model
{
    use SoftDeletes;
    protected $table = 'lck_location_ward';

    protected $fillable = [
        'id',
        'name',
        'name_ascii',
        'name_origin',
        'location',
        'type',
        'district_id',
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

    public function district()
    {
        return $this->belongsTo(District::class)->select(['id', 'name', 'province_id']);
    }
}
