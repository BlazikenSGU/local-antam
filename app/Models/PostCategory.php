<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    const STATUS_HIDE = 0;
    const STATUS_SHOW = 1;
    const STATUS = [
        ['id' => 0, 'name' => 'Ẩn'],
        ['id' => 1, 'name' => 'Hiển thị'],
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_post_category';
    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'description',
        'parent_id',
        'status',
        'priority',
        'thumbnail_file_id',
        'icon_file_id',
        'seo_title',
        'seo_descriptions',
        'seo_keywords',
        'user_id',
        'can_index',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['company_id'];

    public static function get_all()
    {
        $categories = self::where('company_id', config('constants.company_id'))
            ->orderBy('priority', 'ASC')->get();
        $return = [];
        foreach ($categories as $category) {
            $return[$category->id] = $category->toArray();
        }

        return $return;
    }

    public static function get_validation_admin()
    {
        return [
            'company_id'        => 'nullable|integer',
            'name'              => 'required|string',
            'slug'              => 'nullable|string',
            'description'       => 'nullable|string',
            'status'            => 'nullable|in:0,1',
            'priority'          => 'nullable|integer',
            'thumbnail_file_id' => 'nullable|integer|exists:lck_files,id',
            'icon_file_id'      => 'nullable|integer|exists:lck_files,id',
            'seo_title'         => 'nullable|string',
            'seo_descriptions'  => 'nullable|string',
            'seo_keywords'      => 'nullable|string',
            'user_id'           => 'nullable|integer',
            'can_index'         => 'nullable|in:0,1',
        ];
    }

    public function thumbnail()
    {
        return $this->belongsTo(Files::class, 'thumbnail_file_id', 'id')->select(['id', 'file_path']);
    }

    public function icon()
    {
        return $this->belongsTo(Files::class, 'icon_file_id', 'id')->select(['id', 'file_path']);
    }

}
