<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Shared\Models\Banners;
use Shared\Models\EmailSubscribe;
use Shared\Models\Files;
use Shared\Utils\Category;
use Shared\Utils\Filter;

class SubscribersController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Email nhận tin',
        'subtitle' => 'Email nhận tin',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $data = Subscribe::where('company_id', config('constants.company_id'))->orderBy('created_at', 'DESC')
            ->paginate(1000);

        $this->_data['list_data'] = $data;
        $this->_data['start'] = 0;

        return view('backend.subscribers.index', $this->_data);
    }
}
