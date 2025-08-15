<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    const STATUS_HIDE = 0;
    const STATUS_SHOW = 1;
    const STATUS = [
        ['id' => 0, 'name' => 'Ẩn'],
        ['id' => 1, 'name' => 'Hiển thị'],
    ];
    const TYPE = [
        ['id' => 1, 'name' => 'Menu BĐS'],
        ['id' => 2, 'name' => 'Menu tin tức'],
    ];
    const TYPE_PRODUCT = 1;
    const TYPE_ARTICLE = 2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_menus';
    protected $fillable = [
        'company_id',
        'name',
        'description',
        'link',
        'priority',
        'status',
        'parent_id',
        'type',
        'user_id',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function get_all($type = 1)
    {
        $data = self::where('status', self::STATUS_SHOW)
            ->where('type', $type)
            ->where('company_id', config('constants.company_id'))
            ->orderBy('priority', 'ASC')
            ->get();

        return $data;
    }

    public static function get_validation_admin()
    {
        return [
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:0,1',
            'priority'    => 'nullable|integer',
            'link'        => 'nullable|string|max:255',
            'parent_id'   => 'nullable|integer',
            'type'        => 'nullable|in:2,1',
            'user_id'     => 'nullable|integer',
        ];
    }
}
