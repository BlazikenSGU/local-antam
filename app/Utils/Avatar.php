<?php

namespace App\Utils;

use YoHang88\LetterAvatar\LetterAvatar;

class Avatar
{
    public static function generate($name, $uploads_dir = null)
    {
        if (!$uploads_dir)
            return false;

        $temp_name = explode(' ', $name);
        $letter = substr(trim(end($temp_name)), 0, 1);

        $avatar = new LetterAvatar($letter, 'circle', 200);
        $file_name = md5(rand(0, 999999) . microtime()) . '.png';
        $sub_dir = date('Y/m/d');
        $dir_save = $uploads_dir . '/' . $sub_dir;

        if (!is_dir($dir_save))
            mkdir($dir_save, 0777, true);

        $avatar->saveAs($dir_save . '/' . $file_name);
        return $sub_dir . '/' . $file_name;
    }

    public static function delete($file_name, $uploads_dir = null)
    {
        if (!$uploads_dir)
            return false;

        @unlink($uploads_dir . '/' . $file_name);
        return true;
    }
}