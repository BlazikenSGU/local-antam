<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected static $tbl_lck_core_users = 'lck_core_users';
    protected static $tbl_lck_favorite = 'lck_favorite';

    protected static $tbl_lck_files = 'lck_files';
    protected static $tbl_lck_history_view = 'lck_history_view';

    protected static $tbl_lck_location_district = 'lck_location_district';
    protected static $tbl_lck_location_province = 'lck_location_province';
    protected static $tbl_lck_location_ward = 'lck_location_ward';

    protected static $tbl_lck_notification = 'lck_notification';
    protected static $tbl_lck_notification_read = 'lck_notification_read';

    protected static $tbl_lck_product = 'lck_product';
    protected static $tbl_lck_product_images = 'lck_product_images';
    protected static $tbl_lck_product_images_extra = 'lck_product_images_extra';

    protected static $tbl_lck_product_type = 'lck_product_type';

    protected static $tbl_lck_attributes = 'lck_attributes';
    protected static $tbl_lck_attribute_values = 'lck_attribute_values';
    protected static $tbl_lck_product_attribute_values = 'lck_product_attribute_values';
    protected static $tbl_lck_product_attribute_points = 'lck_product_attribute_points';
}
