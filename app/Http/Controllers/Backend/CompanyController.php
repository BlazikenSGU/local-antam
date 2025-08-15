<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\Company;
use App\Utils\Common as Utils;
use Illuminate\Http\Request;

class CompanyController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Quản lý công ty',
        'subtitle' => 'Quản lý công ty',
    );

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'fullname' => null,
            'phone'    => null,
            'email'    => null,
            'status'   => null,
        ), Utils::J_filterEntities($request->all()));

        $objU = new Company();

        $params['pagin_path'] = Utils::get_pagin_path($filter);
        $params['is_staff'] = false;

        $company = $objU->all();

        $start = ($company->currentPage() - 1) * config('constants.item_perpage');

        $this->_data['company'] = $company;
        $this->_data['start'] = $start;
        $this->_data['filter'] = $filter;
        return view('backend.company.index', $this->_data);
    }
}
