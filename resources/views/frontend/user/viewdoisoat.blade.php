@extends('frontend.layouts.main')
@push('title')
    Chi tiết đối soát
@endpush

@section('content')
    <style>
        #table_id_wrapper {
            width: 100%;
            overflow: auto;
        }

        @media (max-width: 650px) {
            .desktop {
                display: none;
            }

            .mobile {
                display: block !important;
            }
        }
    </style>

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor mt-2">Chi tiết đối soát</h3>
        </div>
    </div>

    <div class="mb-2">
        <button class="btn btn-primary xuat_excel" data-bs-toggle="modal" data-bs-target="#exportExcelModal">Tải excel</button>
    </div>

    <div class="row page-titles desktop">
        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <div class="table-responsive mt-5">
                        <table class="table color-table muted-table table-striped" id="grid_table">
                            <thead>
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

                                    {{--                                <th style="font-size: 14px; line-height: 25px ;background-color: #1aabc4;color: #fff; border: 1px solid #eee;text-align: center" --}}
                                    {{--                                    align="center"> --}}
                                    {{--                                    Dịch vụTình trạng thu tiền GTB --}}
                                    {{--                                </th> --}}
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
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $item)
                                    <tr>

                                        <td>
                                            <a style="color:#0d6aad"
                                                href="{{ route('user.order.edit', $item->id) }}">{{ $item->OrderCode ?? '' }}
                                            </a>
                                        </td>
                                        <td>{{ $item->PartialReturnCode ?: '' }}</td>
                                        <td>{{ $item->client_order_code ?: '' }}</td>
                                        <td>{{ $item->to_name }} - {{ $item->to_phone }}</td>
                                        <td>{{ $item->payment_method == 2 ? 'Bên nhận trả phí' : ' Bên gửi trả phi' }}</td>
                                        <td>{{ $item->created_at ?: '' }}</td>
                                        <td>{{ $item->ngaygiaohoanthanhcong ?? '' }}</td>
                                        {{-- su dung helper de format --}}
                                        <td>{{ format_order_status($item->statusName) }}</td>
                                        <td>{{ number_format($item->CODAmount ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            @if (!empty($item->tinhtrangthutienGTB) and $item->tinhtrangthutienGTB == 1)
                                                {{ (int) str_replace(['.', ','], '', $item->cod_failed_amount) ?? '' }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>{{ number_format($item->tongphi ? ceil($item->tongphi) : 0, 0, ',', '.') }}
                                        </td>
                                        <td>{{ number_format($item->tongdoisoat ?: 0, 0, ',', '.') }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- mobile --}}
    <div class="row  mobile d-none">
        <div class="col-md-12">
            @foreach ($data as $key => $item)
                <div class="card card-outline-info mt-2">
                    <div class="card-body">
                        <div class="col-md-12">
                            <p style="color: #1b4e87; font-weight: bold; padding: 0; margin: 0">
                                <a style="color:#1b4e87 "
                                    href="{{ route('user.order.edit', $item->id) }}">{{ $item->OrderCode ?? '' }} </a>
                                {{ $item->statusName ? '(' . format_order_status($item->statusName) . ')' : '' }}
                            </p>
                            <p style="color: #1b4e87;font-weight: bold; padding: 0; margin: 0">{{ $item->Status ?? '' }}
                            </p>
                            <p style="padding: 0; margin: 0">
                                {{ $item->PartialReturnCode ? 'Mã giao 1 phần: ' . $item->PartialReturnCode : '' }} </p>
                            <p style="padding: 0; margin: 0"> <strong style="color: #f26522;"> Cod:</strong>
                                {{ $item->CODAmount ? number_format($item->CODAmount, 0, ',', '.') : 0 }} đ </p>
                            <p style="padding: 0; margin: 0"><strong style="color: #f26522;">Tổng Phí:</strong>
                                {{ $item->tongphi ? number_format($item->tongphi, 0, ',', '.') : 0 }} đ</p>
                            <p style="padding: 0; margin: 0"><strong style="color: #f26522;">Tổng đối soát:</strong>
                                {{ $item->tongdoisoat ? number_format($item->tongdoisoat, 0, ',', '.') : 0 }} đ</p>
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

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- modal hien thi xuat excel --}}
    <div class="modal fade" id="exportExcelModal" tabindex="-1" aria-labelledby="exportExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="export-excel-form" method="POST" action="{{ route('user.doisoatUser.bulk_export_many') }}"
                target="_blank">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportExcelModalLabel">Xuất Excel Đơn Hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">

                        <span>Xuất thông tin về phiên đối soát: {{ $idDoisoat }}</span>

                        <input type="hidden" name="id_doisoatuser" value="{{ $idDoisoat }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-success">Xuất Excel</button>
                    </div>
                </div>
            </form>
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
