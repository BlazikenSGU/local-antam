<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách đơn hàng</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h2>Danh sách đơn hàng</h2>
<table>
    <thead>
    <tr>
        <th>STT</th>
        <th>Mã đơn hàng</th>
        <th>ID cửa hàng</th>
        <th>Người gửi</th>
        <th>SĐT gửi</th>
        <th>Địa chỉ gửi</th>

        <th>Người nhận</th>
        <th>SĐT nhận</th>
        <th>Địa chỉ nhận</th>

        <th>Tổng phí dịch vụ</th>
        <th>Phí hoàn hàng</th>
        <th>Tiền COD</th>
        <th>GTB - Thu tiền</th>
        <th>Tuỳ chọn thanh toán</th>

        <th>Khối lượng</th>
        <th>Rộng</th>
        <th>Dài</th>
        <th>Cao</th>

        <th>Mã đơn hàng riêng</th>

        <th>Ghi chú thêm</th>
        <th>Ngày tạo đơn</th>
        <th>Ngày Giao hàng thành công</th>


    </tr>
    </thead>
    <tbody>
    @foreach($data as $index => $order)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $order->order_code }}</td>
            <td>{{ $order->user_id }}</td>


            <td>{{ $order->fullname }}</td>
            <td>{{ $order->phone }}</td>
            <td>{{ $order->address }}</td>

            <td>{{ $order->to_name }}</td>
            <td>{{ $order->to_phone }}</td>
            <td>{{ $order->to_address }} - {{ $order->to_ward_name }} - {{ $order->to_district_name }} - {{ $order->to_province_name }}</td>

            <td>{{ $order->insurance_value }}</td>

            <td>{{ $order->service_package }}</td>
            <td>{{ $order->cod_amount }}</td>
            <td>{{ $order->cod_failed_amount }}</td>
            <td>
                @if($order->payment_method == 1 )
                    Bên gủi thanh toán
                @else
                    Bên Nhận thanh toán
                @endif
            </td>

            <td>{{ $order->weight }}</td>
            <td>{{ $order->width }}</td>
            <td>{{ $order->length }}</td>
            <td>{{ $order->height }}</td>


            <td>{{ $order->order_code_custom }}</td>

            <td>{{ $order->note }}</td>
            <td>{{ $order->created_at }}</td>


        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
