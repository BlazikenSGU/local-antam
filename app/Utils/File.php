<?php

namespace App\Utils;

class File
{
    public static function delete($file_path = '')
    {
        if (empty($file_path))
            return false;

        $uploads_url = config('constants.upload_dir.url') . '/';
        if (strpos($file_path, $uploads_url) === false)
            return false;

        if (strpos($file_path, 'avatar/avatar.png') !== false)
            return false;

        $file_path = str_replace($uploads_url, '', $file_path);

        \App\Models\Files::where(['file_path' => $file_path])->delete();
        $uploads_dir = config('constants.upload_dir.root');
        @unlink($uploads_dir . '/' . $file_path);
        return true;
    }

    public static function moveFromTempToReal($file_path)
    {
        $check_dir = realpath(config('constants.upload_dir_temp.root'));
        $temp_file = realpath(config('constants.upload_dir_temp.root') . '/' . $file_path);

        if (strpos($temp_file, $check_dir) === false) {
            return false;
        }

        $sub_dir = dirname($file_path);
        $file_name = basename($file_path);

        if ($temp_file) {

            $real_dir = config('constants.upload_dir.root') . '/' . $sub_dir;

            if (!is_dir($real_dir)) {
                mkdir($real_dir, 0777, true);
            }

            @rename($temp_file, $real_dir . '/' . $file_name);
        }

        return true;
    }
}