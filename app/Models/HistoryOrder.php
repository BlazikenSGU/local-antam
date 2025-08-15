<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryOrder extends Model
{
    //
    protected $table = 'lck_history_order';
    protected $fillable = [
        'name',
        'content',
        'order_id',
        ];
}
