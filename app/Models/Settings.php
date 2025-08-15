<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_settings';
    protected $fillable = [
        'company_id',
        'setting_id',
        'setting_key',
        'setting_value',
        'setting_ext',
        'setting_desc',
        'setting_type',
        'priority',
        'require',
        'visible',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function get_all()
    {
        $data = self::where('company_id', config('constants.company_id'))
            ->orderBy('priority', 'ASC')->get();

        $return = [];
        foreach ($data as $v) {
            $return[$v->setting_key] = $v->toArray();
        }

        return $return;
    }

    public static function get_setting_member()
    {
        $data = self::whereIn('setting_key', ['MEMBER_SILVER', 'MEMBER_GOLD', 'MEMBER_DIAMOND', 'MEMBER_VIP',])->orderBy('priority', 'ASC')->get();

        $return = [];
        foreach ($data as $v) {
            $return[$v->setting_key] = $v->toArray();
        }

        return $return;
    }

    public static function update_by_key($params)
    {
        $params = array_merge(
            array(
                'EMAIL'              => NULL,
                'ADDRESS'            => NULL,
                'PHONE'              => NULL,
                'HOTLINE'            => NULL,
                'FAX'                => NULL,
                'META_TITLE'         => NULL,
                'META_DESCRIPTIONS'  => NULL,
                'META_KEYWORDS'      => NULL,
                'SITE_DESCRIPTION'   => NULL,
                'META_AUTHOR'        => NULL,
                'COMPANY_NAME'       => NULL,
                'COMPANY_INTRO'      => NULL,
                'COMPANY_DETAIL'     => NULL,
                'PRICE_GLOBAL'       => NULL,
                'PRODUCTS_PERPAGE'   => NULL,
                'FACEBOOK'           => NULL,
                'POSTS_PERPAGE'      => NULL,
                'TWITTER'            => NULL,
                'LINKEDIN'           => NULL,
                'PINTEREST'          => NULL,
                'INSTAGRAM'          => NULL,
                'YOUTUBE'            => NULL,
                'GOOGLE_PLUS'        => NULL,
                'PHONE_SUPPORT'      => NULL,
                'IS_PUBLISHED'       => NULL,
                'GA_CODE'            => NULL,
                'OPTION_CODE_FOOTER' => NULL,
                'TIME_WORKING'       => NULL,
                'ADDRESS_FOOTER'     => NULL,
                'ADDRESS_CONTACT'    => NULL,
                'STATISTIC_HOME'     => NULL,
                'SITE_SLOGAN'        => NULL,
                'HTML_FOOTER'        => NULL,
                'FAVICON'            => NULL,
                'LOGO'               => NULL,
                'HTML_INTRO_HOME'    => NULL,
                'HTML_INTRODUCE'     => NULL,
            ), $params);

        foreach ($params as $k => $v) {
            if ($v !== null) {
                self::where('setting_key', $k)->update([
                    'setting_value' => $v
                ]);
            }
        }
        return true;
    }
}
