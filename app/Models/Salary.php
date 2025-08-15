<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const TYPE_A = 1,
        TYPE_B = 2;
    protected $table = 'salary';
    protected $fillable = [
        'id',
        'user_id',
        'fullname',
        'phone',
        'email',
        'title',
        'salary',
        'type',
        'bank',
        'created_at'
    ];
    protected $hidden = ['updated_at'];

    public static function get_by_where_backend($params)
    {
        $params = array_merge(array(
            'user_id'  => null,
            'fullname' => null,
            'email'    => null,
            'phone'    => null,
            'limit'    => config('constants.item_perpage'),
        ), $params);

        \DB::enableQueryLog();

        $data = self::orderBy('id', 'DESC')->with('user');

        if (!empty($params['user_id'])) {
            $data->where('user_id', 'like', "%{$params['user_id']}%");
        }
        if (!empty($params['fullname'])) {
            $data->where('fullname', 'like', "%{$params['fullname']}%");
        }
        if (!empty($params['email'])) {
            $data->where('email', 'like', "%{$params['email']}%");
        }
        if (!empty($params['phone'])) {
            $data->where('phone', 'like', "%{$params['phone']}%");
        }

        $data = $data->paginate($params['limit'])->withPath($params['pagin_path']);

        return $data;
    }

    public static function get_by_where($params)
    {
        $params = array_merge(array(
            'user_id' => null,
            'ids'     => null,
            'limit'   => config('constants.item_perpage'),
        ), $params);

        \DB::enableQueryLog();

        $data = self::orderBy('id', 'DESC');

        if (!empty($params['ids'])) {
            $ids = !is_array($params['ids']) ? explode(',', $params['ids']) : $params['ids'];
            $data->whereIn('id', $ids);
        }
        if (!empty($params['user_id'])) {
            $data->where('user_id', 'like', "%{$params['user_id']}%");
        }
        $data = $data->paginate($params['limit'])->withPath($params['pagin_path']);

        return $data;
    }

    public function user()
    {
        return $this->belongsTo(CoreUsers::class, 'user_id', 'id')->select([
            'id',
            'fullname',
            'email',
            'phone',
        ]);
    }
}
