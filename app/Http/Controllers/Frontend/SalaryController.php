<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseFrontendController;
use App\Models\CoreUsers;
use App\Models\Salary;
use App\Utils\Common as Utils;
use Illuminate\Http\Request;

class SalaryController extends BaseFrontendController
{
    protected $_data = array(
        'title'    => 'Lịch sử thanh toán',
        'subtitle' => 'Lịch sử thanh toán',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);
        $params['user_id'] = auth('web')->user()->id;
        $params['limit'] = 4;

        $data = Salary::get_by_where($params);
        $this->_data['list_data'] = $data;
        $this->_data['filter'] = $filter;
        $breadcrumbs = [];

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => 'Tài khoản'
        );
        $this->_data['breadcrumbs'] = $breadcrumbs;
        return view('frontend.user.history-salary', $this->_data);
    }

}
