<div style="width: 100%;display: inline-block;text-align: center">
    <h3 class="page-header text-center" style="border:0; margin: 5px 0;border-bottom: 1px solid #eee;">
        ĐƠN ĐẶT HÀNG
    </h3>

    <p style="text-align: center">Mã đơn hàng: <b>{{$order_data->order_code}}</b>

</div>

<p style="margin: 5px 0;">{{$order_data->fullname}}</p>
<p style="margin: 5px 0;">ĐC: {{$order_data->address}}</p>
<p style="margin: 5px 0;">ĐT: {{$order_data->phone}}</p>
<p style="margin: 5px 0;">Email: {{$order_data->email}}</p>

<table class="cart_summary" cellpadding="0" cellspacing="0"
       style="width: 100%; margin: 30px 0; border-collapse: collapse;">
    <tbody>
        <tr bgcolor="#f8f8f8" style="font-weight:bold;height: 30px;">
            <td style="border: 1px solid #eee;padding: 8px;" align="center">
                STT
            </td>
            <td style="border: 1px solid #eee;padding: 8px;" align="center">
                Tên sản phẩm
            </td>
            <td style="border: 1px solid #eee;padding: 8px;" align="center">
                Mã
            </td>
            <td style="border: 1px solid #eee;padding: 8px;" align="center">
                SL
            </td>
            <td style="border: 1px solid #eee;padding: 8px;" align="center">
                Đơn giá
            </td>
            <td style="border: 1px solid #eee;padding: 8px;" align="center">
                Thành tiền
            </td>
        </tr>
        @php $price_total = 0; @endphp
        @foreach($order_data->order_details as $k=>$item)
            @php
                $price_sum  = $item->price*$item->quantity;
                $price_total += $price_sum;
            @endphp
            <tr style="text-align: center;background: #fff;">
                <td style="border: 1px solid #eee;padding: 8px;" width="5%" align="center" valign="middle"
                    class="stt_item">
                    {{$k+1}}
                </td>
                <td style="border: 1px solid #eee;padding: 8px;" width="25%" align="left" valign="middle">
                    {{$item->title}}
                    {!! $item->product_variation_name?"<br>{$item->product_variation_name}":'' !!}
                </td>
                <td style="border: 1px solid #eee;padding: 8px;" width="14%" align="left" valign="middle">
                    {{$item->product_code}}
                </td>
                <td style="border: 1px solid #eee;padding: 8px;" width="8%" align="right" valign="middle">
                    {{number_format($item->quantity)}}
                </td>
                <td style="border: 1px solid #eee;padding: 8px;" width="8%" align="right" valign="middle">
                    {{number_format($item->price)}} đ
                </td>
                <td style="border: 1px solid #eee;padding: 8px;" width="8%" align="right" valign="middle">
                    {{number_format($price_sum)}} đ
                </td>
            </tr>
        @endforeach

        @if($order_data->total_reduce)
            <tr style="background: #fff;">
                <td style="border: 1px solid #eee;text-align: center;padding: 8px;" colspan="5" valign="middle">
                    <b>Giảm giá</b>
                </td>
                <td style="border: 1px solid #eee;padding: 8px;" align="right" valign="middle">
                    -{{number_format($order_data->total_reduce)}} đ
                </td>
            </tr>
        @endif
        @if($order_data->shipping_fee)
            <tr style="background: #fff;">
                <td style="border: 1px solid #eee;text-align: center;padding: 8px;" colspan="5" valign="middle">
                    <b>Phí Ship</b>
                </td>
                <td style="border: 1px solid #eee;padding: 8px;" align="right" valign="middle">
                    {{number_format($order_data->shipping_fee)}} đ
                </td>
            </tr>
        @endif
        <tr style="background: #fff;">
            <td style="border: 1px solid #eee;text-align: center;padding: 8px;" colspan="5" valign="middle">
                <b>Tổng cộng</b>
            </td>
            <td style="border: 1px solid #eee;padding: 8px;" align="right" valign="middle">
                <b>{{number_format($order_data->total_price)}} đ</b>
            </td>
        </tr>
    </tbody>
</table>

@if($order_data->note)
    <p><b>Ghi chú:</b> {{$order_data->note}}</p>
@endif

<div style="text-align: right;display: inline-block;width: 100%">
    <p style="margin-top: 10px">Ngày {{$order_data->created_at->format('d')}}
        Tháng {{$order_data->created_at->format('m')}} Năm {{$order_data->created_at->format('Y')}}</p>
</div>
