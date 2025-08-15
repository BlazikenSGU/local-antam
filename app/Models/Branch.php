<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_branch';
    protected $fillable = [
        'name',
        'shopId',
        'token',
        'to_weight',
        'from_weight',
        'name_show',
        'created_at',
        'updated_at',
        'type',
        'use_create_order',
        'service_type_id'
    ];

    /**
     * @param $params
     * @param int $limit
     * @return mixed
     */
    public static function getAll($params, $limit = 10)
    {
        $params = array_merge([
            'search' => null
        ], $params);
        $result = self::select('lck_branch.*');

        if (!empty($params['search'])) {
            $result->where('search', 'LIKE', '%' . $params['search'] . '%');
        }

        $result->orderBy('id', 'DESC');
        return empty($limit) ? $result->get() : $result->paginate($limit);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'branch_id', 'id');
    }

    public function thumbnail()
    {
        return $this->belongsTo(Files::class, 'image_id', 'id')->select(['id', 'file_path']);
    }
    public static function getByBranchId($id)
    {
        $branch_id = self::select('*')->where('id', '=', $id)->first();
        return $branch_id;
    }
}
