<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    const CHANEL_BROADCAST = 1;
    const CHANEL_PRIVATE = 2;
    const TYPE_COMMON = 1;
    const TYPE_MESSAGE = 2;
    const TYPE_SHARE = 3;
    const TYPE_CALL_REQUEST = 4;
    const TYPE_REWARD_POINT = 5;
    const TYPE_MISSION = 6;
    const TYPE_NEW_PRODUCT = 7;
    const TYPE_PRODUCT_UDPATE = 8;
    const TYPE_PRODUCT_SAVE = 9;
    protected $table = 'lck_notification';
    protected $fillable = [
        'id',
        'company_id',
        'title',
        'content',
        'chanel',
        'type',
        'relate_id',
        'from_user_id',
        'to_user_id',
        'user_id_created',
        'extra',
        'created_at',
        'updated_at'
    ];
    protected $hidden = ['updated_at', 'company_id',];
    protected $appends = array('is_read');

    public static function get_by_where($params = [])
    {
        $params = array_merge(
            [
                'user_id'    => null,
                'pagin_path' => null,
                'limit'      => 10,
            ],
            $params
        );

        $notifications = self::with('from_user')
            ->orderBy('created_at', 'DESC')
            ->where('company_id', config('constants.company_id'));

        if ($params['user_id'])
            $notifications->whereRaw("((to_user_id = {$params['user_id']} and chanel=2) or chanel=1)");
        else
            $notifications->where('chanel', 1);


        $notifications = $notifications->paginate($params['limit'])
            ->withPath($params['pagin_path']);

        return $notifications;
    }

    public function getExtraAttribute($val)
    {
        $extra = (array)json_decode($val, true);
        return $extra;
    }

    public function getIsReadAttribute()
    {
        if (!array_key_exists('notification_read', $this->relations)) $this->load('notification_read');
        $notification_read = $this->getRelation('notification_read');
        return count($notification_read) > 0;
    }

    public function from_user()
    {
        return $this->belongsTo(CoreUsers::class, 'from_user_id', 'id')->select(['id', 'fullname', 'avatar']);
    }

    public function to_user()
    {
        return $this->belongsTo(CoreUsers::class, 'to_user_id', 'id')->select(['id', 'fullname', 'avatar', 'fcm_token']);
    }

    public function notification_read()
    {
        return $this->hasMany(NotificationRead::class, 'notification_id', 'id')->select(['notification_id', 'created_at']);
    }
}
