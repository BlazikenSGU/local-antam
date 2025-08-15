<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusName extends Model
{
    protected $table = 'lck_statusname';

    protected $fillable = [
        'key',
        'name'
    ];

    /**
     * Relationship với bảng orders
      */
    public function orders()
    {
        return $this->hasMany(Orders::class, 'statusName', 'key');
    }

    /**
     * Relationship với bảng doi_soat
     */
    public function doiSoats()
    {
        return $this->hasMany(DoiSoat::class, 'statusName', 'key');
    }
} 