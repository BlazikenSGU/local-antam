<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseFrontendController;
use App\Mail\Contact;
use App\PostCategory;
use App\Posts;
use App\Utils\Common;
use Illuminate\Http\Request;

class PagesController extends BaseFrontendController
{
    protected $_data = [];

    public function about(Request $request)
    {
        $this->_data['title'] = 'Giới thiệu';

        $this->_data['menu_active'] = 'products';

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => 'Giới thiệu'
        );

        $this->_data['breadcrumbs'] = $breadcrumbs;

        return view('frontend.pages.about', $this->_data);
    }

    public function contact(Request $request)
    {

        $this->_data['title'] = 'Liên hệ';

        $this->_data['menu_active'] = 'products';

        $breadcrumbs[] = array(
            'link' => 'javascript:;',
            'name' => 'Liên hệ'
        );

        $this->_data['breadcrumbs'] = $breadcrumbs;

        return view('frontend.pages.contact', $this->_data);
    }
}
