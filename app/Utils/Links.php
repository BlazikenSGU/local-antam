<?php
/**
 * Created by PhpStorm.
 * User: ThanhLuan
 * Date: 16/06/2018
 * Time: 10:28 CH
 */

namespace App\Utils;

class Links
{
    public static function ImageLink($path = null, $default = false)
    {
        if (!$path && !$default)
            return null;

        if ($path)
            return config('constants.upload_dir.url') . '/' . $path;
        else
            return config('constants.upload_dir.url') . '/noimage.png';
    }
    public static function CategoryLink(array $category)
    {
        return $category['link'];
    }
    public static function CategoryLinkHasChild(array $category)
    {
        return $category['link'];
    }
}
