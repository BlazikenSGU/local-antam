<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{

    protected $table = 'lck_ticker';

    protected $fillable = [
        'user_id',
        'attachments',
        'client_id',
        'conversations',
        'created_by',
        'description',
        'ticker_id',
        'order_code',
        'status',
        'status_id',
        'type',
    ];
}
