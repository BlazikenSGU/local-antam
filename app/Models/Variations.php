<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variations extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_variations';

    protected $fillable = [
        'id',
        'company_id',
        'name',
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
            'name' => 'required|string',
        ];
    }

    public function variation_values()
    {
        return $this->hasMany(VariationValues::class, 'variation_id', 'id')
            ->select([
                'id',
                'variation_id',
                'value',
            ]);
    }
}
