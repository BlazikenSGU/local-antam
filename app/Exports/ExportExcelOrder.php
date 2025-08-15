<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ExportExcelOrder implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;

    }

    public function view(): View
    {
        $this->_data['data'] = $this->data;

        return view('backend.orders.export',  $this->_data);

    }
}
