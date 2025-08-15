<?php

namespace App\Exports;

use App\Orders;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ExportOrder  implements FromView
{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data['data'] = $data;
    }

    public function view(): View
    {
        return view('export.order', $this->data);
    }
}
