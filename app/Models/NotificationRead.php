<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationRead extends Model
{
    protected $table = 'lck_notification_read';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'notification_id', 'user_id', 'created_at', 'updated_at'
    ];
    protected $hidden = ['updated_at'];
}
