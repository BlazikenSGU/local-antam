<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryView extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_history_view';

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
