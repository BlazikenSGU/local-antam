<?php

if (!function_exists('format_order_status')) {
    function format_order_status($status)
    {
        return match ($status) {
            'ready_to_pick' => 'Mới tạo đơn hàng',
            'picking' => 'Nhân viên đang lấy hàng',
            'cancel' => 'Hủy đơn hàng',
            'money_collect_picking' => 'Đang thu tiền người gửi',
            'picked' => 'Nhân viên đã lấy hàng',
            'storing' => 'Hàng đang nằm ở kho',
            'transporting' => 'Đang luân chuyển hàng',
            'sorting' => 'Đang phân loại hàng hóa',
            'delivering' => 'Nhân viên đang giao cho người nhận',
            'money_collect_delivering' => 'Nhân viên đang thu tiền người nhận',
            'delivered' => 'Giao hàng thành công',
            'delivery_fail' => 'Giao hàng thất bại',
            'waiting_to_return' => 'Đang đợi trả hàng về cho người gửi',
            'return' => 'Trả hàng',
            'return_transporting' => 'Đang luân chuyển hàng trả',
            'return_sorting' => 'Đang phân loại hàng trả',
            'returning' => 'Nhân viên đang đi trả hàng',
            'return_fail' => 'Trả hàng thất bại',
            'returned' => 'Trả hàng thành công',
            'exception' => 'Đơn hàng ngoại lệ không nằm trong quy trình',
            'damage' => 'Hàng bị hư hỏng',
            'lost' => 'Hàng bị mất',
            default => 'Không xác định',
        };
    }
}
