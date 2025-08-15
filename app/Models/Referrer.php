<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referrer extends Model
{
    protected $table = 'referrer';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'referrer_id',
    ];
    protected $hidden = ['updated_at'];
}
