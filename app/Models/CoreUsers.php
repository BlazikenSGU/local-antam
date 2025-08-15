<?php

namespace App\Models;

use App\Utils\Links;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class CoreUsers extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;
    const STATUS_UNREGISTERED = 0;
    const STATUS_REGISTERED = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_BANNED = 3;
    const STATUS_NEWACCOUNT = 4;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    const ACCOUNT_POSITION_ADMIN = 1,
        ACCOUNT_POSITION_SECRETARY = 2,
        ACCOUNT_POSITION_SECRETARY_OUT = 3,
        ACCOUNT_POSITION_SALE = 4,
        ACCOUNT_POSITION_SALE_COLLABORATOR = 5;
    const ACCOUNT_POSITION = [
        ['id' => self::ACCOUNT_POSITION_ADMIN, 'name' => 'Admin'],
    ];


    const ACCOUNT_TYPE_NEW = 0,
        ACCOUNT_TYPE_COPPER = 1,
        ACCOUNT_TYPE_SILVER = 2,
        ACCOUNT_TYPE_GOLD = 3,
        ACCOUNT_TYPE_DIAMOND = 4,
        ACCOUNT_TYPE_VIP = 5;


    public static $account_type = [
        0 => 'Thành viên MỚI',
        1 => 'Thành viên ĐỒNG',
        2 => 'Thành viên BẠC',
        3 => 'Thành viên VÀNG',
        4 => 'Thành viên KIM CƯƠNG',
        5 => 'Thành viên VIP',
    ];
    public static $status_inactive = 0;
    public static $status_active = 1;
    public static $status_banned = 3;
    protected $guard_name = 'backend';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_core_users';
    protected $fillable = [
        'company_id',
        'fullname',
        'email',
        'phone',
        'password',
        'status',
        'shopId',
        'fcm_token',
        'device_id',
        'device_os',
        'recommender',
        'gender',
        'birthday',
        'cardid',
        'avatar_file_id',
        'avatar_file_path',
        'bank_name',
        'bank_number',
        'bank_account',
        'address',
        'province_id',
        'district_id',
        'account_position',
        'account_type',
        'last_login',
        'cost',
        'branch_name',
        'user_category_id',
        'ward_id',
        'province_name',
        'district_name',
        'ward_name',
        'pass_leak',
        'count_input_otp',
        'check_confirm_otp',
    ];
    /**

     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'company_id',
    ];
    protected $appends = array('avatar_src');

    protected static function boot()
    {

        static::saving(function ($element) {
            $element->company_id = $element->company_id ?? config('constants.company_id');
        });
        parent::boot();
    }

    public function getAvatarSrcAttribute()
    {
        return Links::ImageLink($this->avatar_file_path);
    }

    /**

     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**

     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function get_by_where($params)
    {
        $params = array_merge(array(
            'fullname'         => null,
            'email'            => null,
            'phone'            => null,
            'status'           => null,
            'account_position' => null,
            'is_staff'         => false,
            'pagin_path'       => null,
            'is_check_user'       => null,
            'limit'          => config('constants.item_perpage'),
            'giatu'       => null,
            'giaden'       => null,
        ), $params);

        \DB::enableQueryLog();

        $data = $this->orderBy('created_at', 'DESC');
        $data->where('id', '<>', 168);


        if (!empty($params['fullname'])) {
            $data->where('fullname', 'like', "%{$params['fullname']}%");
        }
        if (!empty($params['email'])) {
            $data->where('email', 'like', "%{$params['email']}%");
        }

        if ($params['phone']) {
            $data->where('phone', 'like', "%{$params['phone']}%");
        }
        if ($params['status']) {
            $data->where('status', '=', $params['status']);
        }


        if ($params['is_staff'] == 1) {
            $data->where('account_position', 1);
        }

        $data = $data->paginate(($params['limit']))->withPath($params['pagin_path']);

        return $data;
    }
}
