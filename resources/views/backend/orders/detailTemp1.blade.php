@extends('backend.layouts.main')

@section('content')

    <body onload="window.print();">
    <div class="wrapper" style="padding: 0 5px;background: white">
        <!-- Main content -->
        <section class="invoice">

            <div class="row invoice-info" style="margin-bottom: 5px;font-weight: normal; text-align: center;font-size: large">
                <div class="col-md-12 col-xs-12">

                    <img src="https://thorvina.dev24h.net/storage/uploads/2022/05/19/6285d646b2e8a.png" height="300px" width="300px">

                    <p style="font-size: 50px">ThorVina</p>
                    <p style="font-size: 40px">54A4, Đường Ngô Chí Quốc, Phường Bình Chiểu, TP.Thủ Đức, TP. HCM</p>
                    <h4 class="header-title" style="font-size: 50px">ĐƠN HÀNG</h4>

                </div>
            </div>
            <div class="row invoice-info" style="margin-bottom: 5px;font-weight: normal; text-align: left">
                <div class="col-md-12 col-xs-12">
                    <div style="padding: 8px 0">
                        <p style="font-size: 50px">{{ $data_item->fullname }}</p>
                        <p style="font-size: 50px">ĐC: {{ $data_item->address }}
                        </p>
                        <p style="font-size: 50px">ĐT: {{ $data_item->phone }}</p>
                        <p style="font-size: 50px">Email: {{ $data_item->email }}</p>
                        <p style="font-size: 50px">Hình thức thanh toán:
                            {{ \App\Models\Orders::$payment_type[$data_item->payment_type] }}
                        </p>

                        <p style="font-size: 50px">Ngày nhận: {{ $data_item->date_receiver }}</p>
                        <p style="font-size: 50px">Số Bàn: <span
                                style="font-weight: bold">{{ $data_item->warehouse }}</span></p>
                        <br>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            @if (!empty($data_item->order_detail))
                                {!! $data_item->order_detail !!}
                            @else
                                <table class="cart_summary" cellpadding="0" cellspacing="0"
                                       style="width: 100%; margin: 0;">
                                    <tbody>
                                    <tr bgcolor="#f8f8f8" style="font-weight:bold;height: 30px;font-size: 50px;">
                                        <td style="border: 1px solid #eee;text-align: center"
                                            align="center">
                                            STT
                                        </td>
                                        <td style="border: 1px solid #eee;text-align: center"
                                            align="center">
                                            Tên sản phẩm
                                        </td>
                                        <td style="border: 1px solid #eee;text-align: center"
                                            align="center">
                                            Mã
                                        </td>
                                        <td style="border: 1px solid #eee;text-align: center"
                                            align="center">
                                            SL
                                        </td>
                                        <td style="border: 1px solid #eee;text-align: center"
                                            align="center">
                                            Đơn giá
                                        </td>
                                        <td style="border: 1px solid #eee;text-align: center"
                                            align="center">
                                            Chiết khấu
                                        </td>
                                        <td style="border: 1px solid #eee;text-align: center"
                                            align="center">
                                            Thành tiền
                                        </td>
                                    </tr>

                                    @php $total_price=0; @endphp
                                    @foreach ($data_item->order_details as $k => $item)
                                        @php $total_price += ($item->price*$item->quantity); @endphp
                                        <tr style="text-align: center;background: #fff;font-size: 50px">

                                            <td style="border: 1px solid #eee;text-align: center"
                                                width="5%" align="center" valign="middle"
                                                class="stt_item">
                                                {{ $k + 1 }}
                                            </td>
                                            <td style="border: 1px solid #eee;text-align: center"
                                                width="25%" align="left" valign="middle">
                                                {{ $item->title }}
                                                {!! $item->product_variation_name ? "<br>{$item->product_variation_name}" : '' !!}
                                            </td>
                                            <td style="border: 1px solid #eee;text-align: center"
                                                width="14%" align="center" valign="middle">
                                                {{ $item->product_code }}
                                            </td>
                                            <td style="border: 1px solid #eee;text-align: center"
                                                width="8%" align="center" valign="center">
                                                {{ number_format($item->quantity) }}
                                            </td>
                                            <td style="border: 1px solid #eee;text-align: center"
                                                width="10%" align="center" valign="middle">
                                                                        <span class="don-gia"
                                                                              data-don-gia="{{ $item->price }}">{{ number_format($item->price) }}</span>
                                                đ
                                            </td>
                                            <td style="border: 1px solid #eee;text-align: center"
                                                width="10%" align="right" valign="middle">
                                                                        <span
                                                                            class="don-gia">{{ number_format($item->amount_discount) }}
                                                                            đ</span>

                                            </td>
                                            <td style="border: 1px solid #eee;text-align: right"
                                                width="10%" align="right" valign="middle">
                                                                        <span class="thanh-tien"
                                                                              data-thanh-tien="{{ $item->price }}">{{ number_format($item->price) }}</span>
                                                đ
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if ($data_item->total_reduce)
                                        <tr class="" style="font-size: 50px">
                                            <td colspan="6" class="text-right">
                                                <sup><i class="text-danger">Mã giảm giá/Quà
                                                        tặng</i></sup> Giảm giá
                                            </td>
                                            <td class="text-right">
                                                -{{ number_format($data_item->total_reduce) }} đ
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($data_item->total_reduce_point)
                                        <tr class="" style="font-size: 50px">
                                            <td colspan="6" class="text-right">
                                                <sup><i class="text-danger">Điểm tích
                                                        lủy</i></sup>Giảm giá
                                            </td>
                                            <td class="text-right">
                                                -{{ number_format($data_item->total_reduce_point) }}
                                                đ
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($data_item->shipping_fee)
                                        <tr class="" style="font-size: 50px">
                                            <td colspan="6" class="text-right">
                                                Phí ship
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($data_item->shipping_fee) }} đ
                                            </td>
                                        </tr>
                                    @endif

                                    <tr style="text-align: center;background: #fff;font-size: 50px">
                                        <td colspan="6"
                                            style="border: 1px solid #eee;text-align: right">
                                            Chiếu khấu tổng đơn
                                        </td>
                                        <td align="right"
                                            style="border: 1px solid #eee;text-align: right">
                                                                    <span style="color: #f00;">
                                                                        <strong><span
                                                                                class="tong-cong">{{ number_format($data_item->discount_order_total) }}</span>
                                                                        </strong>
                                                                    </span>
                                        </td>
                                    </tr>

                                    <tr style="text-align: center;background: #fff;font-size: 50px">
                                        <td colspan="6"
                                            style="border: 1px solid #eee;text-align: right">
                                            Tổng cộng
                                        </td>
                                        <td align="right"
                                            style="border: 1px solid #eee;text-align: right">
                                                                    <span style="color: #f00;">
                                                                        <strong><span
                                                                                class="tong-cong">{{ number_format($data_item->total_price) }}</span>
                                                                            đ</strong>
                                                                    </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group row">
                                <div class="col-lg-12 col-sm-12 col-xs-12" style="margin-top: 10px;font-size: 50px">
                                    Ghi chú: {!! $data_item->note !!}
                                </div>
                                <div class="col-lg-12 col-sm-12 col-xs-12 text-right"
                                     style="margin-top: 30px;font-size: 50px">
                                    Ngày {{ date('d', strtotime($data_item->created_at)) }}
                                    Tháng {{ date('m', strtotime($data_item->created_at)) }}
                                    Năm {{ date('Y', strtotime($data_item->created_at)) }}
                                </div>
                            </div>

                            <div class="form-group row order-state">
                                <div class="col-lg-3">Trạng thái</div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <select {!! in_array($data_item->status, [4, 5]) ? 'disabled="disabled"' : '' !!} id="status" name="status"
                                                class="form-control main_font">
                                            @foreach (\App\Models\Orders::$status as $k => $v)
                                                <option value="{{ $k }}"
                                                    {{ $k == $data_item->status ? 'selected' : '' }}>
                                                    {{ $v }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="hide-print">

                        <input type="submit" id="send-btn1"
                               data-device-id="{{ $data_item->device_id }}"
                               data-chinhanh="{{ $data_item->branch_id }}"
                               data-ban="{{ $data_item->warehouse_id }}" value="Đã làm xong"
                               class="btn btn-warning" {!! in_array($data_item->status, [4, 5]) ? 'disabled="disabled"' : '' !!} />

                        <input type="submit" id="send-btn2"
                               data-device-id="{{ $data_item->device_id }}"
                               data-chinhanh="{{ $data_item->branch_id }}"
                               data-ban="{{ $data_item->warehouse_id }}" value="Hoàn thành"
                               class="btn btn-success" {!! in_array($data_item->status, [4, 5]) ? 'disabled="disabled"' : '' !!} />

                        <input type="submit" id="send-btn"
                               data-device-id="{{ $data_item->device_id }}"
                               data-chinhanh="{{ $data_item->branch_id }}"
                               data-ban="{{ $data_item->warehouse_id }}" value="Lưu"
                               class="btn btn-primary" {!! in_array($data_item->status, [4, 5]) ? 'disabled="disabled"' : '' !!} />

                        <a href="{{Route('backend.orders.detailTemp',[$data_item->id]). '?_ref=' .$current_url }}">

                            <input type="button" value="In Bill" id="print_button_tam"
                                   class="btn btn-default"
                            />
                        </a>

                        <input type="button" value="Thoát"
                               onclick="javascript:window.location='{{ route('backend.orders.index') }}'"
                               class="btn btn-default" />

                        <input type="button" value="In đơn hàng" id="print_button"
                               class="btn btn-default" onclick="window.print()" />
                    </p>

<!--                    -->
                </div>
            </div>

            <!-- Table row -->
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- ./wrapper -->
    </body>
@endsection

@section('style')
    <style type="text/css" media="print">
        .btn-default,
        .hide-print,
        .page-titles,
        .topbar,
        .left-sidebar,
        .order-state {
            display: none;
        }
    </style>
@endsection

