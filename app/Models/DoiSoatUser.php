<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoiSoatUser extends Model
{
    //

    protected $table = 'lck_doisoat_user';
    protected $fillable = [
        'user_id',
        'maphienchuyentien',
        'thoigianchuyentien',
        'tongtienCOD',
        'GTBThutien',
        'thucnhan',
        'soHDtuongung',
    ];

    public function doiSoats()
    {
        return $this->hasMany(DoiSoat::class, 'IdDoiSoatUser', 'id');
    }
}
