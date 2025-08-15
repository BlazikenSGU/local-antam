<?php

namespace App\Http\Controllers\Backend;

use App\Models\Menus;

class MenuNewsController extends MenuController
{
    public function __construct()
    {
        parent::__construct();

        $this->_data['title'] = 'Menu trang tin tức';
        $this->_data['subtitle'] = 'Menu trang tin tức';
        $this->_data['route_type'] = 'menu.news';
        $this->_data['menu_type'] = Menus::TYPE_ARTICLE;
    }
}