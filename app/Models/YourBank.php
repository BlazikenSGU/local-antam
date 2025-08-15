<?php

namespace App\Models;

use App\Models\Location\Province;
use Illuminate\Database\Eloquent\Model;

class YourBank extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'your_bank';
    protected $fillable = [
        'user_id',
        'fullname',
        'passport',
        'bank_code',
        'username_bold_unsigned',
        'bank_account_number',
        'bank_id',
        'address_id',
        'bank_branch',
        'created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function get_validation_admin()
    {
        return [
            'bank_id'             => 'nullable|integer',
            'bank_account_number' => 'nullable|string',
            'fullname'            => 'nullable|string',
            'bank_branch'         => 'nullable|string',
        ];
    }

    public function bank()
    {
        return $this->belongsTo(Banks::class, 'bank_id', 'id')->select([
            'id',
            'code',
            'name',
            'image',
        ]);
    }

    public function address()
    {
        return $this->belongsTo(Province::class, 'address_id', 'id')->select([
            'id',
            'name',
            'name_ascii',
            'name_origin',
        ]);
    }

}
