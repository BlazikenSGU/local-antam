@extends('backend.layouts.admin')

@section('title', 'Quản lý đối soát')
@section('page_title', 'Quản lý đối soát')

@section('content')
    <style>
        /* CSS cho bảng đối soát chính */
        .table-bordered th,
        .table-bordered td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* CSS cho bảng đối soát phụ */
        #table_id1 th,
        #table_id1 td {
            width: 200px !important;
            min-width: 200px !important;
            max-width: 200px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Đảm bảo bảng có thể cuộn ngang */
        .table-responsive {
            max-height: 70vh;
        }

        /* Đảm bảo bảng không bị co lại */
        .table-bordered,
        #table_id1 {
            width: auto !important;
            table-layout: fixed;
        }

        /* Đảm bảo wrapper của DataTable không bị vỡ */
        #table_id_wrapper {
            width: 100%;
            overflow: auto;
        }

        /* CSS cho phân trang */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin: 20px 0;
            padding: 0;
            list-style: none;
        }

        .pagination li {
            margin: 0 2px;
        }

        .pagination li a,
        .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 35px;
            height: 35px;
            padding: 0 10px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            color: #333;
            text-decoration: none;
            background: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .pagination li.active span {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.2);
        }

        .pagination li a:hover {
            background: #f8f9fa;
            border-color: #007bff;
            color: #007bff;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .pagination li.disabled span {
            color: #999;
            background: #f5f5f5;
            border-color: #e0e0e0;
            cursor: not-allowed;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            border-radius: 4px;
        }

        .pagination .page-item.active .page-link {
            z-index: 3;
        }

        .wgp-pagination {
            margin: 30px 0;
            padding: 0 15px;
        }

        .title_h3 {
            background-color: white;
            margin: 0 .5rem 1rem 0;
            padding: 0 .5rem 0 0;
            border-radius: 5px;
        }
    </style>

    <div class="container-fluid mt-4">

            <div class="row page-titles">
                <div class="col-md-12">
                    <div class="card card-outline-info">
                        <div class="card-body">

                            <div class="header_doisoat d-flex justify-content-between align-items-center">
                                <div>
                                    <button class="btn rounded-pill btn-outline-primary xuat_excel" data-bs-toggle="modal"
                                        data-bs-placement="top" data-bs-target="#exportExcelModal" title="Xuất Excel">
                                        <i class="fa-solid fa-download"></i>
                                    </button>

                                    <a href="{{ route('backend.doi_soat.showFormImport') }}"
                                        class="btn rounded-pill btn-outline-primary" data-bs-placement="top"
                                        data-bs-toggle="tooltip" title="Nhập file"> <i class="fa-solid fa-upload"></i></a>

                                    <a href="{{ route('backend.doi_soat.run') }}"
                                        class="btn rounded-pill btn-outline-primary" data-bs-toggle="tooltip"
                                        title="Chạy đối soát"><i class="fa-solid fa-arrows-spin"></i></a>

                                    <a class="btn rounded-pill btn-outline-primary"
                                        href="{{ route('backend.doi_soat.index') }}" data-bs-toggle="tooltip"
                                        title="Reset data"><i class="fa-solid fa-rotate"></i></a>
                                </div>

                                <div class="d-flex align-items-center">
                                    <form action="{{ route('backend.doi_soat.search') }}" method="get">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Mã đơn đối soát"
                                                id="keyword" name="keyword"
                                                value="{{ request('keyword') ? request('keyword') : '' }}">
                                            <button class="btn btn-primary" type="submit" id="button-addon2">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <a class="btn rounded-pill btn-outline-primary" style="margin-left: .5rem;"
                                        href="" data-bs-toggle="tooltip" title="Bộ lọc" data-bs-placement="top"
                                        data-bs-target="#filterModal">
                                        <i class="fa-solid fa-filter"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" style=" overflow-x: auto;">
                                    <thead>
                                        <tr class="table-primary">
                                            <th scope="col">#</th>
                                            <th scope="col">Mã đơn hàng</th>
                                            <th scope="col">Đối soát</th>
                                            <th scope="col">Mã giao 1 phần</th>
                                            <th scope="col">Mã đơn hàng riêng</th>
                                            <th scope="col">Bảng giá</th>
                                            <th scope="col">ID User </th>
                                            <th scope="col">Ngày tạo đơn</th>
                                            <th scope="col">Ngày giao/hoàn thành công</th>
                                            <th scope="col">Tình trạng thu tiền GTB</th>
                                            <th scope="col">Trạng thái</th>
                                            <th scope="col">COD</th>
                                            <th scope="col">Tiền GTB - Thu tiền</th>
                                            <th scope="col">Phí giao hàng</th>
                                            <th scope="col">Phí giao lại</th>
                                            <th scope="col">Phí khai giá</th>
                                            <th scope="col">Phí hoàn hàng</th>
                                            <th scope="col">Phí giao 1 phần</th>
                                            <th scope="col">Tổng phí</th>
                                            <th scope="col">Tổng đối soát</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($doisoat as $item)
                                            <tr>
                                                <td>{{ $item->id ?? '' }}</td>

                                                <td style="width: 200px"><a style="text-decoration: none; font-weight: 600;"
                                                        href="{{ route('backend.doi_soat.edit', $item->id) }}">{{ $item->OrderCode ?? '' }}</a>
                                                </td>

                                                <td>
                                                    <?php
                                                    $icheck = \App\Models\DoiSoat::where('OrderCode', $item->OrderCode)->first();
                                                    if ($icheck and $icheck->type == 2) {
                                                        echo $icheck->doisoat;
                                                    } elseif ($icheck and $icheck->type == 1) {
                                                        echo $icheck->doisoat;
                                                    } else {
                                                        echo '';
                                                    }
                                                    ?>
                                                </td>
                                                <td>{{ $item->PartialReturnCode ?? '' }}</td>
                                                <td>{{ $item->order_code_custom ?? '' }}</td>
                                                <td>
                                                    {{ \App\Models\Branch::where('shopId', $item->ShopID)->first()->name_show ?? '' }}
                                                </td>

                                                <td>{{ \App\Models\CoreUsers::find($item->IDUser)->phone ?? '' }}</td>
                                                <td>{{ $item->created_at ?? '' }}</td>
                                                <td>{{ $item->ngaygiaohoanthanhcong ?? '' }}</td>
                                                <td>
                                                    @if (!empty($item->tinhtrangthutienGTB))
                                                        {{ $item->tinhtrangthutienGTB == 1 ? 'Thành Công' : 'Thất bại' }}
                                                    @endif
                                                </td>
                                                <td>{{ \App\Models\StatusName::where('key', $item->statusName)->first()->name ?? '' }}
                                                </td>
                                                <td>{{ number_format($item->CODAmount ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ (int) str_replace(['.', ','], '', $item->cod_failed_amount) ?? '' }}
                                                </td>
                                                <td>{{ number_format($item->MainService ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ $item->R2S ?? '' }}</td>
                                                <td>{{ $item->Insurance ?? '' }}</td>
                                                <td>{{ $item->Return ?? '' }}</td>
                                                <td>{{ $item->phigiao1lan ?? '' }}</td>
                                                <td>{{ number_format($item->tongphi ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ number_format($item->tongdoisoat ?? 0, 0, ',', '.') }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                {{ $doisoat->links('pagination::bootstrap-4') }}
                            </div>


                        </div>
                    </div>
                </div>

            </div>
      
    </div>


    <!-- Modal xuất excel -->
    <div class="modal modal-lg fade" id="exportExcelModal" tabindex="-1" aria-labelledby="exportExcelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="export-excel-form" method="POST" action="{{ route('user.doisoat.bulk_export_many') }}"
                target="_blank">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportExcelModalLabel">Xuất Excel Đơn đối soát</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Trạng thái đơn hàng</label>
                            <div class="row label_trangthaidonhang">
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="ready_to_pick"
                                        id="status_ready_to_pick">
                                    <label for="status_ready_to_pick">Chờ lấy hàng</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="picking" id="status_picking">
                                    <label for="status_picking">Đang lấy hàng</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="money_collect_picking"
                                        id="status_money_collect_picking">
                                    <label for="status_money_collect_picking">Đang tương tác với người gửi</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="picked" id="status_picked">
                                    <label for="status_picked">Lấy hàng thành công</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="storing" id="status_storing">
                                    <label for="status_storing">Nhập kho</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="transporting"
                                        id="status_transporting">
                                    <label for="status_transporting">Đang trung chuyển</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="sorting" id="status_sorting">
                                    <label for="status_sorting">Đang phân loại</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="delivered"
                                        id="status_delivered">
                                    <label for="status_delivered">Giao hàng thành công</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="money_collect_delivering"
                                        id="status_money_collect_delivering">
                                    <label for="status_money_collect_delivering">Đang tương tác với người nhận</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="delivery_fail"
                                        id="status_delivery_fail">
                                    <label for="status_delivery_fail">Giao hàng không thành công</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="waiting_to_return"
                                        id="status_waiting_to_return">
                                    <label for="status_waiting_to_return">Chờ xác nhận giao lại</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="return" id="status_return">
                                    <label for="status_return">Chuyển hoàn</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="return_transporting"
                                        id="status_return_transporting">
                                    <label for="status_return_transporting">Đang trung chuyển hàng hoàn</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="return_sorting"
                                        id="status_return_sorting">
                                    <label for="status_return_sorting">Đang phân loại hàng hoàn</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="returning"
                                        id="status_returning">
                                    <label for="status_returning">Đang hoàn hàng</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="return_fail"
                                        id="status_return_fail">
                                    <label for="status_return_fail">Hoàn hàng không thành công</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="returned" id="status_returned">
                                    <label for="status_returned">Hoàn hàng thành công</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="cancel" id="status_cancel">
                                    <label for="status_cancel">Đơn hủy</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="exception"
                                        id="status_exception">
                                    <label for="status_exception">Hàng ngoại lệ</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="lost" id="status_lost">
                                    <label for="status_lost">Hàng thất lạc</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="damage" id="status_damage">
                                    <label for="status_damage">Hàng hư hỏng</label>
                                </div>
                            </div>

                        </div>
                        <div class="mb-3">
                            <label for="date_from" class="form-label">Từ ngày</label>
                            <input type="date" class="form-control" name="date_from" id="date_from">
                        </div>
                        <div class="mb-3">
                            <label for="date_to" class="form-label">Đến ngày</label>
                            <input type="date" class="form-control" name="date_to" id="date_to">
                        </div>
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
        .table-responsive th,
        .table-responsive td {
            text-align: center;
            font-size: 14px !important;
            font-weight: 400;
            padding: 5px 15px !important;

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
        $(".btnloadMoreDoiSoat").click(function() {

            var page = parseInt($('.btnloadMoreDoiSoat').attr('data-page')) + 1;
            let data = {
                page: page,
                _token: '{{ csrf_token() }}',
            };
            console.log(data)
            $.ajax({
                type: "GET",
                url: '',
                dataType: 'json',
                data: data,
                success: function(response) {
                    console.log(response)

                    $('#data-ajax-loadmore').append(response.data.html);
                    $('.btnloadMoreDoiSoat').attr('data-page', page);
                }
            });
        });

    </script>
@endsection
