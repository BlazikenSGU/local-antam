<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    protected $table = 'lck_subscribers';

    protected $fillable = [
        'id',
        'email',
        'ip',
        'created_at',
        'updated_at'
    ];
}
