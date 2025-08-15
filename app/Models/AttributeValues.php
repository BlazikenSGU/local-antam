<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValues extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    use SoftDeletes;

    protected $table = 'lck_attribute_values';
    protected $fillable = [
        'attribute_id',
        'value',
        'alias',
        'status',
        'point',
        'priority',
        'user_id',
        'user_id_updated',
    ];

    const STATUS = [
        ['id' => 1, 'name' => 'Hiển thị'],
        ['id' => 0, 'name' => 'Ẩn'],
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function get_validation_admin()
    {
        return [
            'attribute_id' => 'required|exists:lck_attributes,id',
            'value'        => 'required|string',
            'alias'        => 'required|string',
            'status'       => 'nullable|in:0,1',
            'point'        => 'nullable|numeric',
            'priority'     => 'nullable|integer',
            'user_id'      => 'nullable|integer',
        ];
    }
}
