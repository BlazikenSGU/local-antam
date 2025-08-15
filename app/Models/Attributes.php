<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Attributes extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    use SoftDeletes;

    public static $type_single_selection = 1;
    public static $type_multi_selection = 2;

    const TYPE = [
        ['id' => 1, 'name' => '1 giá trị'],
        ['id' => 2, 'name' => 'Nhiều giá trị'],
    ];

    const STATUS = [
        ['id' => 1, 'name' => 'Hiển thị'],
        ['id' => 0, 'name' => 'Ẩn'],
    ];

    protected $table = 'lck_attributes';
    protected $fillable = [
        'name',
        'description',
        'status',
        'group',
        'type',
        'priority',
        'user_id',
        'user_id_updated',
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
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:0,1',
            'type'        => 'nullable|in:1,2',
            'priority'    => 'nullable|integer',
            'user_id'     => 'nullable|integer',
        ];
    }

    public function attribute_values()
    {
        return $this->hasMany(AttributeValues::class, 'attribute_id', 'id')
            ->select([
                'id',
                'attribute_id',
                'value',
                'alias',
                'status',
                'point',
                'priority',
                'user_id',
            ]);
    }
}
