<?php

namespace App\Listeners;

use App\Events\UpdateProduct;
use App\Models\Product;
use App\Models\ProductChangeLog;
use App\Models\ProductNote;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class UpdateProductListener implements ShouldQueue
{
    use InteractsWithQueue;
//    public $connection = 'database';
    public $queue = 'update_product';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function handle(UpdateProduct $event)
    {
        $old_product = $event->old_product;

        try {
            $new_product = Product::select(DB::raw("*, X(location) as location_long, Y(location) as location_lat, AsText(location) as location"))
                ->with('contact_info2')
                ->find($old_product['id'])->toArray();

            $time = Carbon::now();

            $content = '';

            if (isset($old_product['_merge']))
                $content = '<p><strong>Gộp dự án *</strong></p>';

            //địa chỉ
            $old_address = $old_product['address'];
            $new_address = $new_product['address'];

            if ($old_address != $new_address) {
                $content .= "<p>Địa chỉ cũ: {$old_address}</p>";
                $content .= "<p>Địa chỉ mới: {$new_address}</p>";
            }

            //ngang
            $old_length = (float)$old_product['length'];
            $new_length = (float)$new_product['length'];

            if ($old_length != $new_length) {
                $content .= "<p>Chiều ngang cũ: {$old_length}</p>";
                $content .= "<p>Chiều ngang mới: {$new_length}</p>";
            }

            //dài
            $old_width = (float)$old_product['width'];
            $new_width = (float)$new_product['width'];

            if ($old_width != $new_width) {
                $content .= "<p>Chiều dài cũ: {$old_width}</p>";
                $content .= "<p>Chiều dài mới: {$new_width}</p>";
            }

            //giá bán
            $old_price = $old_product['price'] . ' ' . $old_product['price_type_name'];
            $new_price = $new_product['price'] . ' ' . $new_product['price_type_name'];

            if ($old_price != $new_price) {
                $content .= "<p>Giá cũ: {$old_price}</p>";
                $content .= "<p>Giá mới: {$new_price}</p>";
            }

            //kết cấu
            $old_structure = $old_product['home_structure_text'];
            $new_structure = $new_product['home_structure_text'];

            if ($old_structure != $new_structure) {
                $content .= "<p>Kết cấu cũ: {$old_structure}</p>";
                $content .= "<p>Kết cấu mới: {$new_structure}</p>";
            }

            //hiện trạng
            $old_status = $old_product['status_name'];
            $new_status = $new_product['status_name'];

            if ($old_status != $new_status) {
                $content .= "<p>Hiện trạng cũ: {$old_status}</p>";
                $content .= "<p>Hiện trạng mới: {$new_status}</p>";
            }

            /*thông tin liên hệ*/
            //danh tính
            $old_contact_type = isset($old_product['contact_info2']['type_name']) ? $old_product['contact_info2']['type_name'] : null;
            $new_contact_type = isset($new_product['contact_info2']['type_name']) ? $new_product['contact_info2']['type_name'] : null;

            if ($old_contact_type != $new_contact_type) {
                $content .= "<p>Danh tính cũ: {$old_contact_type}</p>";
                $content .= "<p>Danh tính mới: {$new_contact_type}</p>";
            }

            //tên
            $old_contact_name = isset($old_product['contact_info2']['name']) ? $old_product['contact_info2']['name'] : null;
            $new_contact_name = isset($new_product['contact_info2']['name']) ? $new_product['contact_info2']['name'] : null;

            if ($old_contact_name != $new_contact_name) {
                $content .= "<p>Người liên hệ cũ: {$old_contact_name}</p>";
                $content .= "<p>Người liên hệ mới: {$new_contact_name}</p>";
            }

            //sdt
            $old_contact_phone = isset($old_product['contact_info2']['phone']) ? $old_product['contact_info2']['phone'] : null;
            $new_contact_phone = isset($new_product['contact_info2']['phone']) ? $new_product['contact_info2']['phone'] : null;

            if ($old_contact_phone != $new_contact_phone) {
                $content .= "<p>Số đt cũ: {$old_contact_phone}</p>";
                $content .= "<p>Số đt mới: {$new_contact_phone}</p>";
            }

            //sdt 01
            $old_contact_phone1 = isset($old_product['contact_info2']['phone1']) ? $old_product['contact_info2']['phone1'] : null;
            $new_contact_phone1 = isset($new_product['contact_info2']['phone1']) ? $new_product['contact_info2']['phone1'] : null;

            if ($old_contact_phone1 != $new_contact_phone1) {
                $content .= "<p>Số đt 01 cũ: {$old_contact_phone1}</p>";
                $content .= "<p>Số đt 01 mới: {$new_contact_phone1}</p>";
            }

            //sdt 02
            $old_contact_phone2 = isset($old_product['contact_info2']['phone2']) ? $old_product['contact_info2']['phone2'] : null;
            $new_contact_phone2 = isset($new_product['contact_info2']['phone2']) ? $new_product['contact_info2']['phone2'] : null;

            if ($old_contact_phone2 != $new_contact_phone2) {
                $content .= "<p>Số đt 02 cũ: {$old_contact_phone2}</p>";
                $content .= "<p>Số đt 02 mới: {$new_contact_phone2}</p>";
            }

            if (!empty($content)) {
                ProductNote::create([
                    'product_id' => $old_product['id'],
                    'content'    => htmlentities($content),
                    'user_id'    => $event->user->id,
                    'created_at' => $time,
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('event_update_product: ' . $e->getMessage());
        }

    }
}
