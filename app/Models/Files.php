<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Links;

/**
 * @OA\Schema(@OA\Xml(name="Files"))
 * @OA\Property(property="id",type="integer",description="id"),
 * @OA\Property(property="file_path",type="string",description="Đường dẫn file"),
 * @OA\Property(property="file_src",type="string",description="Link file"),
 **/
class Files extends Model
{
    const IS_TEMP = 1;
    const TYPE_AVATAR = 1;
    const TYPE_PRODUCT = 2;
    const TYPE_PROJECT = 3;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_files';

    protected $fillable = [
        'user_id', 'file_path', 'is_temp', 'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['pivot'];
    protected $appends = array('file_src');
    
    public function getFileSrcAttribute()
    {
        return Links::ImageLink($this->file_path);
    }

}
