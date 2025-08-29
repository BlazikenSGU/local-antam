@extends('backend.layouts.main')

@section('content')
    <style>
        #table_id_wrapper {
            width: 100%;
            overflow: auto;
        }
    </style>


    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-dark" style="border-bottom: 1px solid #000; display: inline-block">Đối soát</h3>
        </div>
        {{--        <div class="col-md-7 align-self-center"> --}}
        {{--            {{ Breadcrumbs::render('backend.orders.index') }} --}}
        {{--        </div> --}}
    </div>

    <div class="row page-titles desktop">
        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    <div class="">
                        <a href="{{ route('backend.doi_soat.export') }}?id_doiSoat={{ $idDoisoat }}"
                            class="btn btn-info"><i class="mdi mdi-file-excel"></i> Tải Excel</a>
                    </div>

                    <div class="table-responsive mt-5">
                        <table class="table color-table muted-table table-striped" id="grid_table">
                            {{-- <thead>
                                <tr bgcolor="#f8f8f8" style="font-weight:bold;height: 30px; line-height: 15px ">


                                    <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center"
                                        align="center">
                                        Mã đơn hàng
                                    </th>
                                    <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center"
                                        align="center">
                                        Mã giao 1 phần
                                    </th>
                                    <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center"
                                        align="center">
                                        Mã đơn hàng riêng
                                    </th>
                                    <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center"
                                        align="center">
                                        Người nhận
                                    </th>
                                    <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center"
                                        align="center">
                                        Tùy chọn thanh toán
                                    </th>

                                    <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center"
                                        align="center">
                                        Ngày tạo đơn
                                    </th>
                                    <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center"
                                        align="center">
                                        Ngày giao/hoàn thành công
                                    </th>

                                    <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center"
                                        align="center">
                                        Trạng thái
                                    </th>

                                    <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center"
                                        align="center">
                                        COD
                                    </th>
                                    <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center"
                                        align="center">
                                        Tiền GTB - Thu tiền
                                    </th>
                                   
                                    <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center"
                                        align="center">
                                        Tổng phí
                                    </th>
                                    <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center"
                                        align="center">
                                        Tổng đối soát
                                    </th>
                                </tr>
                            </thead> --}}
                            <tbody>
                                @foreach ($data as $key => $item)
                                    <tr>


                                        <td style="width: 200px">{{ $item->OrderCode ?? '' }}</td>
                                        <td>{{ $item->PartialReturnCode ?? '' }}</td>
                                        <td>{{ $item->order_code_custom ?? '' }}</td>
                                        <td>{{ $item->to_name }} - {{ $item->to_phone }}</td>
                                        <td>{{ $item->payment_method == 2 ? 'Bên nhận trả phí' : ' Bên gửi trả phi' }}</td>
                                        <td>{{ $item->created_at ?? '' }}</td>
                                        <td>{{ $item->ngaygiaohoanthanhcong ?? '' }}</td>
                                        {{--                                    <td> --}}
                                        {{--                                        @if (!empty($item->tinhtrangthutienGTB)) --}}
                                        {{--                                            {{ $item->tinhtrangthutienGTB == 1 ? 'Thành Công' : 'Thất bại' }} --}}
                                        {{--                                        @endif --}}

                                        {{--                                    </td> --}}
                                        <td>{{ $item->Status ?? '' }}</td>
                                        <td>{{ $item->CODAmount ?? '' }}</td>
                                        <td>
                                            @if (!empty($item->tinhtrangthutienGTB) and $item->tinhtrangthutienGTB == 1)
                                                {{ (int) str_replace(['.', ','], '', $item->cod_failed_amount) ?? '' }}
                                            @else
                                                0
                                            @endif
                                        </td>

                                        <!--  <td>{{ $item->MainService ?? '' }}</td>
                                        <td>{{ $item->R2S ?? '' }}</td>
                                        <td>{{ $item->Insurance ?? '' }}</td>
                                        <td>{{ $item->Return ?? '' }}</td>
                                        <td>{{ $item->phigiao1lan ?? '' }}</td>-->
                                        <td>{{ $item->tongphi ?? '' }}</td>
                                        <td>{{ $item->tongdoisoat ?? '' }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row container-fluid mobile">
        <div class="col-md-12">
            @foreach ($data as $key => $item)
                <div class="card card-outline-info">
                    <div class="card-body">
                        <div class="col-md-12">
                            <p style="color: #0d6aad; padding: 0; margin: 0">{{ $item->OrderCode ?? '' }} </p>
                            <p style="color: #0d6aad; padding: 0; margin: 0">{{ $item->Status ?? '' }} </p>
                            <p style="padding: 0; margin: 0">Mã giao 1 phần: {{ $item->PartialReturnCode ?? '' }} </p>
                            <p style="padding: 0; margin: 0">COD: {{ $item->CODAmount - $item->payment_fee ?? '' }} </p>
                            <p style="padding: 0; margin: 0">Tổng Phí: {{ $item->tongphi ?? '' }} </p>
                            <p style="padding: 0; margin: 0">Tổng đối soát: {{ $item->tongdoisoat ?? '' }} </p>
                            <p style="padding: 0; margin: 0">GTB - thu tiền:
                                @if (!empty($item->tinhtrangthutienGTB) and $item->tinhtrangthutienGTB == 1)
                                    {{ (int) str_replace(['.', ','], '', $item->cod_failed_amount) ?? '' }}
                                @else
                                    0
                                @endif
                            </p>
                            <p style="padding: 0; margin: 0">Người nhận: {{ $item->to_name }} - {{ $item->to_phone }} </p>
                            <p style="padding: 0; margin: 0">Tuỳ chọn thanh toán:
                                {{ $item->payment_method == 2 ? 'Bên nhận trả phí' : ' Bên gửi trả phi' }} </p>
                            <p style="padding: 0; margin: 0">Ngày giao / hoàn hàng thành công
                                : {{ \Carbon\Carbon::parse($item->ngaygiaohoanthanhcong)->format('d/m/Y') ?? '' }} </p>

                        </div>
                        <div class="col-md-12 text-center">
                            @php
                                $order = \App\Models\Orders::where('order_code', $item->OrderCode)->first();
                                if (!empty($order)) {
                                    $url = route('backend.orders.edit', $order->id);
                                } else {
                                    $url = '#';
                                }
                            @endphp
                            <a href="{{ $url }}">
                                <button class="btn btn-primary"> Chi tiết</button>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>



    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tải File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('backend.doi_soat.export') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-group-sm">
                                    <input type="text" name="date_start" value="" id="working_date_form"
                                        readonly="" class="form-control form-control-sm date_select"
                                        placeholder="Từ ngày" style="cursor: pointer;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-sm">
                                    <input type="text" name="date_end" value="" id="working_date_to"
                                        readonly="" class="form-control form-control-sm date_select"
                                        placeholder="Đến ngày" style="cursor: pointer;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                Tùy chọn
                                <input type="checkbox" id="Status" name="status1" value="all">
                                <label for="Status"> Tất cả</label><br>
                            </div>
                            <div class="col-md-12">
                                Trạng thái

                            </div>
                            @foreach ($statusNames as $k => $iStatus)
                                <div class="col-md-4">
                                    <input type="checkbox" id="Status{{ $k }}" name="status[]"
                                        value="{{ $iStatus->key }}">
                                    <label for="Status{{ $k }}"> {{ $iStatus->name }}</label><br>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Tải</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        #grid_table td {

            font-size: 14px;
            padding: 8px;
            text-align: center;
        }
    </style>
@endsection

@section('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#working_date_to").datepicker({
                dateFormat: "yy-mm-dd", // Định dạng ngày
                changeMonth: true, // Hiển thị chọn tháng
                changeYear: true, // Hiển thị chọn năm
                showButtonPanel: true, // Hiển thị nút điều khiển
            });
            $("#working_date_form").datepicker({
                dateFormat: "yy-mm-dd", // Định dạng ngày
                changeMonth: true, // Hiển thị chọn tháng
                changeYear: true, // Hiển thị chọn năm
                showButtonPanel: true, // Hiển thị nút điều khiển
            });
        });
        $(".modalExport").click(function() {
            $('#exampleModal').modal('show');

        });
    </script>
@endsection
