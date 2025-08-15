<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingController extends BaseBackendController
{
    protected $_data = [
        'title' => 'Cài đặt chung',
    ];

    public function index(Request $request)
    {
        $settings = Settings::get_all();

        if ($request->getMethod() == 'POST') {

            $validator_rule = [
                'EMAIL'                 => NULL,
                'ADDRESS'               => NULL,
                'PHONE'                 => NULL,
                'HOTLINE'               => NULL,
                'HOTLINE2'              => NULL,
                'HOTLINE3'              => NULL,
                'FAX'                   => NULL,
                'META_TITLE'            => NULL,
                'META_DESCRIPTIONS'     => NULL,
                'META_KEYWORDS'         => NULL,
                'SITE_DESCRIPTION'      => NULL,
                'META_AUTHOR'           => NULL,
                'COMPANY_NAME'          => NULL,
                'COMPANY_INTRO'         => NULL,
                'COMPANY_DETAIL'        => NULL,
                'PRODUCTS_PERPAGE'      => NULL,
                'FACEBOOK'              => NULL,
                'POSTS_PERPAGE'         => NULL,
                'TWITTER'               => NULL,
                'LINKEDIN'              => NULL,
                'PINTEREST'             => NULL,
                'INSTAGRAM'             => NULL,
                'YOUTUBE'               => NULL,
                'GOOGLE_PLUS'           => NULL,
                'PHONE_SUPPORT'         => NULL,
                'IS_PUBLISHED'          => NULL,
                'GA_CODE'               => NULL,
                'OPTION_CODE_FOOTER'    => NULL,
                'ADDRESS_FOOTER'        => NULL,
                'ADDRESS_CONTACT'       => NULL,
                'SITE_SLOGAN'           => NULL,
                'SERVICE_INTRO'         => NULL,
                'HTML_FOOTER'           => NULL,
                'FAVICON'               => NULL,
                'LOGO'                  => NULL,
                'HTML_INTRO_HOME'       => NULL,
                'STATISTIC_HOME'        => NULL,
                'HTML_INTRODUCE'        => NULL,
                'INTRODUCE'             => NULL,
                'BANK_ACCOUNT_NUMBER'   => NULL,
                'BANK_NAME'             => NULL,
                'BANK_ACCOUNT_NAME'     => NULL,
                'RETURN_AND_EXCHANGE'   => NULL,
                'STORAGE_INSTRUCTIONS'  => NULL,
                'FAQ'                   => NULL,
                'INFORMATION_PRIVACY'   => NULL,
                'TERM_OF_USE'           => NULL,
                'ORDERING_GUIDE'        => NULL,
                'SHIPPING_POLICY'       => NULL,
                'MEMBER_SILVER'         => NULL,
                'MEMBER_GOLD'           => NULL,
                'MEMBER_DIAMOND'        => NULL,
                'MEMBER_VIP'            => NULL,
                'MEMBER_COPPER'         => NULL,
                'CONVERT_RATE_POINT'    => NULL,
                'ADDRESS_2'             => NULL,
                'BANK_ACCOUNT_NUMBER_2' => NULL,
                'BANK_NAME_2'           => NULL,
                'BANK_ACCOUNT_NAME_2'   => NULL,
            ];

            $params = array_fill_keys(array_keys($validator_rule), null);
            $params = array_merge(
                $params, $request->only(array_keys($validator_rule))
            );

            $errors = array();

            foreach ($settings as $k => $v) {
                if ($v['require'] && empty($params[$k])) {
                    $errors[$k] = $v['setting_desc'] . ' không được để trống!';
                }
            }

            if (empty($errors)) {
                try {

                    $params['OPTION_CODE_FOOTER'] = htmlspecialchars($params['OPTION_CODE_FOOTER']);
                    $params['GA_CODE'] = htmlspecialchars($params['GA_CODE']);

                    $params['COMPANY_INTRO'] = htmlspecialchars($params['COMPANY_INTRO']);
                    $params['COMPANY_DETAIL'] = htmlspecialchars($params['COMPANY_DETAIL']);
                    $params['ADDRESS_FOOTER'] = htmlspecialchars($params['ADDRESS_FOOTER']);
                    $params['ADDRESS_CONTACT'] = htmlspecialchars($params['ADDRESS_CONTACT']);
                    $params['SITE_SLOGAN'] = htmlspecialchars($params['SITE_SLOGAN']);
                    $params['SERVICE_INTRO'] = htmlspecialchars($params['SERVICE_INTRO']);
                    $params['HTML_FOOTER'] = htmlspecialchars($params['HTML_FOOTER']);
                    $params['HTML_INTRO_HOME'] = htmlspecialchars($params['HTML_INTRO_HOME']);
                    $params['STATISTIC_HOME'] = htmlspecialchars($params['STATISTIC_HOME']);
                    $params['HTML_INTRODUCE'] = htmlspecialchars($params['HTML_INTRODUCE']);
                    $params['INTRODUCE'] = htmlspecialchars($params['INTRODUCE']);
                    $params['STORAGE_INSTRUCTIONS'] = htmlspecialchars($params['STORAGE_INSTRUCTIONS']);
                    $params['RETURN_AND_EXCHANGE'] = htmlspecialchars($params['RETURN_AND_EXCHANGE']);
                    $params['FAQ'] = htmlspecialchars($params['FAQ']);
                    $params['INFORMATION_PRIVACY'] = htmlspecialchars($params['INFORMATION_PRIVACY']);
                    $params['TERM_OF_USE'] = htmlspecialchars($params['TERM_OF_USE']);
                    $params['ORDERING_GUIDE'] = htmlspecialchars($params['ORDERING_GUIDE']);
                    $params['SHIPPING_POLICY'] = htmlspecialchars($params['SHIPPING_POLICY']);
                    $params['CONVERT_RATE_POINT'] = htmlspecialchars($params['CONVERT_RATE_POINT']);
                    $params['MEMBER_COPPER'] = htmlspecialchars($params['MEMBER_COPPER']);
                    $params['MEMBER_SILVER'] = htmlspecialchars($params['MEMBER_SILVER']);
                    $params['MEMBER_GOLD'] = htmlspecialchars($params['MEMBER_GOLD']);
                    $params['MEMBER_DIAMOND'] = htmlspecialchars($params['MEMBER_DIAMOND']);
                    $params['MEMBER_VIP'] = htmlspecialchars($params['MEMBER_VIP']);
                    Settings::update_by_key($params);
                    $request->session()->flash('msg', ['info', 'Cập nhật thành công!']);
                } catch (\Exception $e) {
                    $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
                }
            } else {
                $request->session()->flash('my_errors', $errors);
            }

            return redirect()->back();
        }

        $this->_data['subtitle'] = 'Cài đặt chung';
        $this->_data['settings'] = $settings;
        return view('backend.setting.index', $this->_data);
    }
}
