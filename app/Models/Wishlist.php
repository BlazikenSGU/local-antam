<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const TYPE_A = 1,
        TYPE_B = 2;
    protected $table = 'lck_wishlist';
    protected $fillable = [
        'id',
        'product_id',
        'user_id',
        'created_at'
    ];
    protected $hidden = ['updated_at'];

    public function user()
    {
        return $this->belongsTo(CoreUsers::class, 'user_id', 'id')->select([
            'id',
            'fullname',
            'email',
            'phone',
        ]);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
