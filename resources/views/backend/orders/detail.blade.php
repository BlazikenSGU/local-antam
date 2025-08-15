@extends('backend.layouts.main')

@section('content')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{ $subtitle }}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.orders.detail') }}
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline-info">
                    <div class="card-body">
                        <form class="form-horizontal my-custom-form" action="" method="post">
                            @include('backend.partials.msg')
                            @include('backend.partials.errors')
                            {{ csrf_field() }}

                            <div class="order_detail_admin">
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <h1 class="page-header text-center"
                                            style="border:0; margin: 5px 0;border-bottom: 1px solid #eee;">
                                            ĐƠN HÀNG</h1>
                                        <p style="text-align: center">Mã đơn hàng:
                                            <b style="color: #428bca; font-size: 16px;">{{ $data_item->order_code }}</b>
                                            {{-- Ngày xuất: {{date('d/m/Y',strtotime($data_item->created_at))}}</p> --}}
                                    </div>
                                    <!-- /.col-lg-12 -->
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row" style="padding-top:20px;">
                                            <div class="col-lg-12 text-left">
                                                <p>{{ $data_item->fullname }}</p>
                                                <p>ĐC: {{ $data_item->address }}
                                                    {{\App\Http\Controllers\Backend\OrdersController::getIdNameWard($data_item->ward_id)}}

                                                    {{\App\Http\Controllers\Backend\OrdersController::getIdNameDistrict($data_item->district_id)}}

                                                    {{\App\Http\Controllers\Backend\OrdersController::getIdNameProvince($data_item->province_id)}}
                                                </p>
                                                <p>ĐT: {{ $data_item->phone }}</p>
                                                <p>Email: {{ $data_item->email }}</p>
                                                <p>Hình thức thanh
                                                    toán:
                                                    {{ \App\Models\Orders::$payment_type[$data_item->payment_type] }}
                                                </p>

                                                <p>Ngày nhận: {{ $data_item->date_receiver }}</p>
                                                <p>Vận chuyển: <span
                                                        style="font-weight: bold">
                                                        @if((int)$data_item->ahamove_type == 1)
                                                            Đơn vị vận chuyển Ahamove - đã hủy.
                                                        @elseif(!empty($data_item->ahamove_type))
                                                            Đơn vị vận chuyển Ahamove.
                                                        @else
                                                            Chưa có đơn vị vận chuyển.
                                                        @endif
                                                    </span></p>
                                                <br>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                @if (!empty($data_item->order_detail))
                                                    {!! $data_item->order_detail !!}
                                                @else
                                                    <table class="cart_summary" cellpadding="0" cellspacing="0"
                                                        style="width: 100%; margin: 0;">
                                                        <tbody>
                                                            <tr bgcolor="#f8f8f8" style="font-weight:bold;height: 30px;">
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
                                                                <tr style="text-align: center;background: #fff;">

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
                                                                            data-thanh-tien="{{ $item->price }}">{{ number_format($item->price * $item->quantity) }}</span>
                                                                        đ
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                            @if ($data_item->total_reduce)
                                                                <tr class="">
                                                                    <td colspan="6" class="text-right">
                                                                        <sup><i class="text-danger">
                                                                                {{\App\Http\Controllers\Backend\OrdersController::getNameDiscount($data_item->discount_code)}}
                                                                               -  Mã giảm giá là :
                                                                                ({{ $data_item->discount_code }})
                                                                                </i></sup> Giảm giá
                                                                    </td>

                                                                    {{\App\Http\Controllers\Backend\OrdersController::getIdNameWard($data_item->ward_id)}}
                                                                    <td class="text-right">
                                                                        -{{ number_format($data_item->total_reduce) }} đ
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @if ($data_item->total_reduce_point)
                                                                <tr class="">
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
                                                                <tr class="">
                                                                    <td colspan="6" class="text-right">
                                                                        Phí ship
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ number_format($data_item->shipping_fee) }} đ
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            <tr style="text-align: center;background: #fff;">
                                                                <td colspan="6"
                                                                    style="border: 1px solid #eee;text-align: right">
                                                                    Tổng tiền trước chiết khấu
                                                                </td>
                                                                <td align="right"
                                                                    style="border: 1px solid #eee;text-align: right">
                                                                    <span style="color: #f00;">
                                                                        <strong><span
                                                                                class="tong-cong"> <?php $a =$data_item->discount_order_total; $b = $data_item->total_price;  echo number_format($a+$b); ?> đ</span>
                                                                        </strong>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr style="text-align: center;background: #fff;">
                                                                <td colspan="6"
                                                                    style="border: 1px solid #eee;text-align: right">
                                                                    Chiếu khấu tổng đơn
                                                                </td>
                                                                <td align="right"
                                                                    style="border: 1px solid #eee;text-align: right">
                                                                    <span style="color: #f00;">
                                                                        <strong><span
                                                                                class="tong-cong">{{ number_format($data_item->discount_order_total) }} đ</span>
                                                                        </strong>
                                                                    </span>
                                                                </td>
                                                            </tr>


                                                            <tr style="text-align: center;background: #fff;">
                                                                <td colspan="6"
                                                                    style="border: 1px solid #eee;text-align: right">
                                                                    Tổng cộng
                                                                </td>
                                                                <td align="right"
                                                                    style="border: 1px solid #eee;text-align: right">
                                                                    <span style="color: #f00;">
                                                                        <strong><span
                                                                                class="tong-cong"> {{ number_format($data_item->total_price) }}</span>
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
                                                    <div class="col-lg-12 col-sm-12 col-xs-12" style="margin-top: 10px">
                                                        Ghi chú: {!! $data_item->note !!}
                                                    </div>
                                                    <div class="col-lg-12 col-sm-12 col-xs-12 text-right"
                                                        style="margin-top: 30px">
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
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

@section('script')
    <!--todo : add lib thong bao va socket -->
    <script src="https://anocha.me/storage/frontend/js/socket.io.min.js" type="text/javascript"></script>
    <script src="https://anocha.me/storage/backend/js/jquery.toast.js"></script>
    <link href="https://anocha.me/storage/backend/main/css/jquery.toast.css" rel="stylesheet">
    <!--todo : add lib thong bao va socket -->
    <script type="text/javascript">
        var socket = io.connect('wss://chat.thietke24h.vn', {
            transports: ["websocket"]
        });

        $('#send-btn').click(function() {

            var data = $('select').find('option:selected').val();

            var id_chinhanh = $(this).attr('data-chinhanh');
            var id_device = $(this).attr('data-device-id');
            var id_ban = $(this).attr('data-ban');

            //parseInt(data);
            if (parseInt(data) === 2) {
                //alert(data);
                /*todo : gui thong tin don hang cho app biet */
                socket.emit("send_order", {
                    api_key: 'thorvina',
                    /* phân biệt các dự án khác */
                    type: 2,
                    /* (1 : app gửi order mới xuống server) , (2 : server gửi len cho app) */
                    status_order: 2,
                    /*  (1-mới đặt)  (2- đang nấu) ) (3-nấu xong) (4-hoàn thành khi khách thanh toán) (5-huỷ đơn) */

                    id_chinhanh: parseInt(id_chinhanh),
                    id_ban: parseInt(id_ban),
                    id_device: id_device,
                    message_order: ""

                });
            }
            if (parseInt(data) === 3) {
                /*todo : gui thong tin don hang cho app biet */
                socket.emit("send_order", {
                    api_key: 'thorvina',
                    /* phân biệt các dự án khác */
                    type: 2,
                    /* (1 : app gửi order mới xuống server) , (2 : server gửi len cho app) */
                    status_order: 3,
                    /*  (1-mới đặt)  (2- đang nấu) ) (3-nấu xong) (4-hoàn thành khi khách thanh toán) (5-huỷ đơn) */

                    id_chinhanh: parseInt(id_chinhanh),
                    id_ban: parseInt(id_ban),
                    id_device: id_device,
                    message_order: ""

                });
            }
            if (parseInt(data) === 4) {
                /*todo : gui thong tin don hang cho app biet */
                socket.emit("send_order", {
                    api_key: 'thorvina',
                    /* phân biệt các dự án khác */
                    type: 2,
                    /* (1 : app gửi order mới xuống server) , (2 : server gửi len cho app) */
                    status_order: 4,
                    /*  (1-mới đặt)  (2- đang nấu) ) (3-nấu xong) (4-hoàn thành khi khách thanh toán) (5-huỷ đơn) */

                    id_chinhanh: parseInt(id_chinhanh),
                    id_ban: parseInt(id_ban),
                    id_device: id_device,
                    message_order: ""

                });
            }
            if (parseInt(data) === 5) {
                /*todo : gui thong tin don hang cho app biet */
                socket.emit("send_order", {
                    api_key: 'thorvina',
                    /* phân biệt các dự án khác */
                    type: 2,
                    /* (1 : app gửi order mới xuống server) , (2 : server gửi len cho app) */
                    status_order: 5,
                    /*  (1-mới đặt)  (2- đang nấu) ) (3-nấu xong) (4-hoàn thành khi khách thanh toán) (5-huỷ đơn) */

                    id_chinhanh: parseInt(id_chinhanh),
                    id_ban: parseInt(id_ban),
                    id_device: id_device,
                    message_order: ""

                });
            }

        });
        $('#send-btn1').click(function() {

            var data = $('select').find('option:selected').val(3);

            var id_chinhanh = $(this).attr('data-chinhanh');
            var id_device = $(this).attr('data-device-id');
            var id_ban = $(this).attr('data-ban');

            //parseInt(data);
            if (parseInt(data) === 2) {
                //alert(data);
                /*todo : gui thong tin don hang cho app biet */
                socket.emit("send_order", {
                    api_key: 'thorvina',
                    /* phân biệt các dự án khác */
                    type: 2,
                    /* (1 : app gửi order mới xuống server) , (2 : server gửi len cho app) */
                    status_order: 2,
                    /*  (1-mới đặt)  (2- đang nấu) ) (3-nấu xong) (4-hoàn thành khi khách thanh toán) (5-huỷ đơn) */

                    id_chinhanh: parseInt(id_chinhanh),
                    id_ban: parseInt(id_ban),
                    id_device: id_device,
                    message_order: ""

                });
            }
            if (parseInt(data) === 3) {
                /*todo : gui thong tin don hang cho app biet */
                socket.emit("send_order", {
                    api_key: 'thorvina',
                    /* phân biệt các dự án khác */
                    type: 2,
                    /* (1 : app gửi order mới xuống server) , (2 : server gửi len cho app) */
                    status_order: 3,
                    /*  (1-mới đặt)  (2- đang nấu) ) (3-nấu xong) (4-hoàn thành khi khách thanh toán) (5-huỷ đơn) */

                    id_chinhanh: parseInt(id_chinhanh),
                    id_ban: parseInt(id_ban),
                    id_device: id_device,
                    message_order: ""

                });
            }
            if (parseInt(data) === 4) {
                /*todo : gui thong tin don hang cho app biet */
                socket.emit("send_order", {
                    api_key: 'thorvina',
                    /* phân biệt các dự án khác */
                    type: 2,
                    /* (1 : app gửi order mới xuống server) , (2 : server gửi len cho app) */
                    status_order: 4,
                    /*  (1-mới đặt)  (2- đang nấu) ) (3-nấu xong) (4-hoàn thành khi khách thanh toán) (5-huỷ đơn) */

                    id_chinhanh: parseInt(id_chinhanh),
                    id_ban: parseInt(id_ban),
                    id_device: id_device,
                    message_order: ""

                });
            }
            if (parseInt(data) === 5) {
                /*todo : gui thong tin don hang cho app biet */
                socket.emit("send_order", {
                    api_key: 'thorvina',
                    /* phân biệt các dự án khác */
                    type: 2,
                    /* (1 : app gửi order mới xuống server) , (2 : server gửi len cho app) */
                    status_order: 5,
                    /*  (1-mới đặt)  (2- đang nấu) ) (3-nấu xong) (4-hoàn thành khi khách thanh toán) (5-huỷ đơn) */

                    id_chinhanh: parseInt(id_chinhanh),
                    id_ban: parseInt(id_ban),
                    id_device: id_device,
                    message_order: ""

                });
            }

        });
        $('#send-btn2').click(function() {

            var data = $('select').find('option:selected').val(4);

            var id_chinhanh = $(this).attr('data-chinhanh');
            var id_device = $(this).attr('data-device-id');
            var id_ban = $(this).attr('data-ban');

            //parseInt(data);
            if (parseInt(data) === 2) {
                //alert(data);
                /*todo : gui thong tin don hang cho app biet */
                socket.emit("send_order", {
                    api_key: 'thorvina',
                    /* phân biệt các dự án khác */
                    type: 2,
                    /* (1 : app gửi order mới xuống server) , (2 : server gửi len cho app) */
                    status_order: 2,
                    /*  (1-mới đặt)  (2- đang nấu) ) (3-nấu xong) (4-hoàn thành khi khách thanh toán) (5-huỷ đơn) */

                    id_chinhanh: parseInt(id_chinhanh),
                    id_ban: parseInt(id_ban),
                    id_device: id_device,
                    message_order: ""

                });
            }
            if (parseInt(data) === 3) {
                /*todo : gui thong tin don hang cho app biet */
                socket.emit("send_order", {
                    api_key: 'thorvina',
                    /* phân biệt các dự án khác */
                    type: 2,
                    /* (1 : app gửi order mới xuống server) , (2 : server gửi len cho app) */
                    status_order: 3,
                    /*  (1-mới đặt)  (2- đang nấu) ) (3-nấu xong) (4-hoàn thành khi khách thanh toán) (5-huỷ đơn) */

                    id_chinhanh: parseInt(id_chinhanh),
                    id_ban: parseInt(id_ban),
                    id_device: id_device,
                    message_order: ""

                });
            }
            if (parseInt(data) === 4) {
                /*todo : gui thong tin don hang cho app biet */
                socket.emit("send_order", {
                    api_key: 'thorvina',
                    /* phân biệt các dự án khác */
                    type: 2,
                    /* (1 : app gửi order mới xuống server) , (2 : server gửi len cho app) */
                    status_order: 4,
                    /*  (1-mới đặt)  (2- đang nấu) ) (3-nấu xong) (4-hoàn thành khi khách thanh toán) (5-huỷ đơn) */

                    id_chinhanh: parseInt(id_chinhanh),
                    id_ban: parseInt(id_ban),
                    id_device: id_device,
                    message_order: ""

                });
            }
            if (parseInt(data) === 5) {
                /*todo : gui thong tin don hang cho app biet */
                socket.emit("send_order", {
                    api_key: 'thorvina',
                    /* phân biệt các dự án khác */
                    type: 2,
                    /* (1 : app gửi order mới xuống server) , (2 : server gửi len cho app) */
                    status_order: 5,
                    /*  (1-mới đặt)  (2- đang nấu) ) (3-nấu xong) (4-hoàn thành khi khách thanh toán) (5-huỷ đơn) */

                    id_chinhanh: parseInt(id_chinhanh),
                    id_ban: parseInt(id_ban),
                    id_device: id_device,
                    message_order: ""

                });
            }

        });
    </script>
@endsection
