<tr>
    <td>STT</td>
    <td>Đối Soát</td>
    <td>Mã đơn hàng</td>
    <td>Mã giao 1 phần</td>
    <td>Mã đơn hàng riêng</td>
    <td>Bảng giá</td>
    <td>Trạng thái</td>
    <td>Người gửi</td>
    <td>SDT gửi</td>
    <td>Địa chỉ gửi</td>
    <td>Người nhận</td>
    <td>SDT nhận</td>
    <td>Địa chỉ nhận</td>
    <td>Tùy chon thanh toán</td>
    <td>Tổng phí dịch vụ</td>
    <td>Tiền COD</td>
    <td>GTB - Thu Tiền</td>
    <td>Tình trạng thu tiền GTB</td>
    <td>Khối lượng</td>
    <td>Rộng</td>
    <td>Dài</td>
    <td>Cao</td>
    <td>Ghi chú thêm</td>
    <td>Ngày tạo đơn</td>
    <td>Ngày giao hoàn thành công</td>

</tr>
@foreach($data as $k=>$item)
    <tr>
        <td>{{$k+1}}</td>
        <td>{{$item->doisoat}}</td>
        <td>{{$item->OrderCode}}</td>
        <td>{{$item->PartialReturnCode}}</td>
        <td>{{$item->order_code_custom}}</td>
        <td>
            {{ \App\Models\Branch::where('shopId', $item->ShopID)->first()->name }}
        </td>
        <td>
            {{  \App\Models\StatusName::where('key', $item->statusName)->first()->name }}
        </td>
        <td>
            {{ $item->fullname }}
        </td>
        <td>
            {{ $item->phone }}
        </td>
        <td>
            {{$item->address}}
        </td>
        <td>
            {{$item->to_name}}
        </td>
        <td>
            {{ $item->to_phone }}
        </td>
        <td>
            {{$item->to_address}} - {{$item->to_ward_name}} - {{$item->to_district_name}} - {{$item->to_province_name}}
        </td>
        <td>
            @if($item->payment_method == 1 )
                Bên gửi trả phí
            @else
                Bên nhận trả phí
            @endif
        </td>

        <td>
            {{ \App\Utils\Common::FormatNumberVND($item->main_service) }}
        </td>
        <td>
            {{ \App\Utils\Common::FormatNumberVND($item->CODAmount) }}
        </td>
        <td>
            {{ \App\Utils\Common::FormatNumberVND($item->cod_failed_amount) }}
        </td>
        <td>
            @if(!empty($item->tinhtrangthutienGTB))
                {{ $item->tinhtrangthutienGTB == 1 ? 'Thành Công' : 'Thất bại' }}
            @endif
        </td>
        <td>
            {{ $item->weight }}
        </td>
        <td>{{ $item->width }}</td>
        <td>{{ $item->length }}</td>
        <td>{{ $item->height }}</td>
        <td>{{ $item->note }}</td>
        <td>{{ $item->created_at }}</td>
        <td>{{ $item->ngaygiaohoanthanhcong  }}</td>

    </tr>

@endforeach
