
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title> don hang</title>
    <style>
        .invoice p {
            margin-bottom: 5px;
        }

        body * {
            font-family: 'Arial';
            font-size: 12px;
        }

        .table > thead > tr > th {
            border-bottom: solid 1px #000;
        }

        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            border-top: none;
        }

        p {
            margin: 0 !important;
        }

        @media print {
            @page {
                margin:0;
            }
            .invoice-col {
                width: 100%;
            }

            .invoice-col-1 {
                float: left;
                width: 100%
            }

            .invoice-col-2,
            .invoice-col-3 {
                float: left;
                width: 100%
            }
        }

        .header-title {
            text-align: center;
            text-transform: uppercase;
            font-size: 13px;
            padding: 0px;
            font-weight: bold;
            margin-bottom: 0;
        }

        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
            padding: 10px 0;
        }

        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
            padding: 5px 0;
        }

    </style>
</head>
<!--    <body onload="">-->
<body onload="window.print();">
<div class="wrapper" style="padding: 0 5px">
    <!-- Main content -->
    <section >

        <div class="row invoice-info" style="margin-bottom: 5px;font-weight: normal; text-align: center">
            <div class="col-md-12 col-xs-12">

                <img src="https://thorvina.dev24h.net/storage/uploads/2022/05/19/6285d646b2e8a.png"  style="filter:grayscale(100%);" height="80px">

                <p>ThorVina</p>
                <p >54A4, Đường Ngô Chí Quốc, Phường Bình Chiểu, TP.Thủ Đức, TP. HCM</p>
                <h4 class="header-title" >ĐƠN HÀNG</h4>

            </div>
        </div>

        <div class="row invoice-info" style="margin-bottom: 5px;font-weight: normal; text-align: left">
            <div class="col-md-12 col-xs-12">
                <div style="padding: 8px 0">

                    <p >{{ $data_item->fullname }}</p>
                    <p >ĐC: {{ $data_item->address }}
                    </p>
                    <p>ĐT: {{ $data_item->phone }}</p>
                    <p >Email: {{ $data_item->email }}</p>
                    <p>Hình thức thanh toán:
                        {{ \App\Models\Orders::$payment_type[$data_item->payment_type] }}
                    </p>

                    <p >Ngày nhận: {{ $data_item->date_receiver }}</p>
                    <p >Số Bàn: <span
                            style="font-weight: bold">{{ $data_item->warehouse }}</span></p>
                    <br>
                </div>
            </div>
        </div>

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12">
                @if (!empty($data_item->order_detail))
                    {!! $data_item->order_detail !!}
                @else

                <table class="table" style="width: 100%; margin: 0;">
                    <thead>
                    <tr>
                        <th class="cart_product" style="text-align: left;">Giá</th>
                        <th style="text-align: center;">Số lượng	</th>
                        <th style="text-align: right;">Tổng cộng</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $total_price=0; @endphp
                    @foreach ($data_item->order_details as $k => $item)
                        @php $total_price += ($item->price*$item->quantity); @endphp

                    <tr style="border-bottom: dotted 1px #ccc;">

                        <td class="price" style="border-bottom: dotted 1px #ccc;"
                            style="text-align: left ;">
                            {{ $item->title }}
                            {!! $item->product_variation_name ? "<br>{$item->product_variation_name}" : '' !!}

                            <span class="don-gia"
                                  data-don-gia="{{ $item->price }}"> - Giá : {{ number_format($item->price) }}</span>
                            đ

                        </td>

                        <td class="qty" style="border-bottom: dotted 1px #ccc;text-align: center ;"
                           >{{ number_format($item->quantity) }}

                        </td>

                        <td class="price" style="text-align: right;border-bottom: dotted 1px #ccc;" >
                            <span style="font-size: 11px;">{{ number_format($item->price * $item->quantity) }}</span> đ

                        </td>

                    </tr>

                    @endforeach

                    </tbody>

                    <tfoot style="display: table-row-group">


                    @if ($data_item->total_reduce)
                        <tr >
                        <td colspan="2" style="text-align: right ;border-bottom: dotted 1px #ccc;">
                            <strong>Giảm giá</strong>
                        </td>
                        <td style="text-align: right ;border-bottom: dotted 1px #ccc;">
                            -{{ number_format($data_item->total_reduce) }} đ
                        </td>
                        </tr>
                    @endif

                    <tr>
                        <td colspan="1" style="text-align: right ;border-bottom: dotted 1px #ccc;">
                            <strong style="text-align: right;font-size: 11px;">Tổng tiền</strong>
                        </td>
                        <td colspan="2"  style="text-align: right ;border-bottom: dotted 1px #ccc;">
                            <strong style="text-align: right;font-size: 10px;">{{ number_format($data_item->total_price) }}</strong> đ
                        </td>
                    </tr>
                    </tfoot>
                </table>
                @endif
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
