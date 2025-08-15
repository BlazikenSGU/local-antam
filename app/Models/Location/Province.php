<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(@OA\Xml(name="Province"))
 * @OA\Property(property="id",type="integer",description="id"),
 * @OA\Property(property="name",type="string",description="Tên tỉnh/tp"),
 **/
class Province extends Model
{
    use SoftDeletes;
    protected $table = 'lck_location_province';

    protected $fillable = [
        'id',
        'name',
        'name_ascii',
        'name_origin',
        'location',
        'type',
        'user_id_created',
        'priority',
        'ProvinceID',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
