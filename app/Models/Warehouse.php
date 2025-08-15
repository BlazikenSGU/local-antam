<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_warehouse';
    protected $fillable = [
        'name',
        'branch_id',
        'status',
        'order_id',
    ];

    public static function getAll($params, $limit = 50)
    {
        $params = array_merge([
            'search' => null,
            'branch_id' => null,
        ], $params);
        $result = self::Join('lck_branch', 'lck_warehouse.branch_id', '=', 'lck_branch.id')
           /* ->LeftJoin('lck_orders', 'lck_warehouse.id', '=', 'lck_orders.warehouse_id')*/
            ->select('lck_branch.name as namebranch','lck_warehouse.*');

        if (!empty($params['search'])) {
            $result->where('search', 'LIKE', '%' . $params['search'] . '%');
        }

        if (!empty($params['branch_id'])) {
            $result->where('branch_id', '=', $params['branch_id']);
        }

        $result->orderBy('id', 'asc');
        return empty($limit) ? $result->get() : $result->paginate($limit);
    }

    public function branch() {
        return $this->belongsTo(Branch::class,'id');
    }
}
