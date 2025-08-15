<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Links;

class Banks extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_banks';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
