@extends('backend.layouts.admin')

@section('title', 'Danh sách đơn hàng')

@section('style_top')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .scroll-wrapper {
            position: relative;
            width: 100%;
        }

        /* Thanh cuộn ngang phía trên */
        .scroll-top {
            width: 100%;
            overflow-x: auto;
            height: 20px;
        }

        /* Fake nội dung để tạo thanh cuộn */
        .scroll-top div {
            width: 1500px;
            /* Đặt chiều rộng lớn hơn bảng */
            height: 1px;
        }

        .select2 {
            width: 100% !important;
            height: 36px !important;
        }

        .checkbox-basic {
            position: initial !important;
            left: initial !important;
            opacity: 1 !important;
        }

        a.sort.active {
            color: red;
        }

        .sort_btn {
            margin-top: 10px;
        }

        .table-bordered .w-250 {
            min-width: 150px !important;
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

        /* Đảm bảo container phân trang có khoảng cách phù hợp */
        .wgp-pagination {
            margin: 30px 0;
            padding: 0 15px;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            background: #a2a1a1;
            /* Giữ nền trắng để không bị che */
            z-index: 10;
            /* Đảm bảo thead nổi trên */
        }


        .sticky-header th {
            white-space: nowrap;
        }

        .table-responsive {
            max-height: 800px;
            overflow-y: auto;
            position: relative;
        }

        #scroll-bottom {
            overflow-x: hidden !important;
            /* Ẩn thanh cuộn ngang bên dưới */
            position: relative
        }

        .t-o {
            font-size: 14px;
            color: rgb(250 169 0);
            font-weight: bold;
        }

        .t-w {
            font-size: 12px;

        }

        .t-b {
            font-size: 14px;
            color: #1b4e87;
            font-weight: bold;
        }

        .t-r {
            font-size: 12px;
            color: red;
            font-weight: bold;
        }

        .t-g {
            font-size: 12px;
            color: green;
            font-weight: bold;
        }

        .t-s {
            font-style: italic;
            color: gray;
            font-size: 12px;
        }

        .button_order {
            justify-content: space-between;
        }

        .button_reset {
            align-items: center;
            display: flex;
            flex-direction: row;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-4">

        <div class="desktop">
            <div class="row">
                <div class="col-md-12">

                    <div class="card card-outline-info">
                        <div class="card-body">

                            <div class="row page-titles">
                                <div class="col-md-5 align-self-center mb-3">
                                    <h3>Danh sách đơn hàng</h3>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-2 pull-right">
                                    <button class="btn btn-success xuat_excel" data-bs-toggle="modal"
                                        data-bs-target="#exportExcelModal">Tải excel</button>

                                </div>

                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12 d-flex button_order">
                                    <div class="form-group col-lg-4">
                                        <form action="{{ route('backend.orders.search') }}" method="GET">
                                            <label class="t-b" for="statusSelect2">Lọc theo trạng thái GHN</label>

                                            <div class="input-group mt-2">
                                                <select class="form-select" id="inputGroupSelect04" name="status"
                                                    id="statusSelect2" aria-label="Example select with button addon">
                                                    <option value="" selected
                                                        {{ request('status') ? 'disabled' : '' }}>Choose...</option>
                                                    @foreach ($count_status as $key => $count)
                                                        <option value="{{ $key }}"
                                                            {{ request('status') == $key ? 'selected' : '' }}>
                                                            {{ \App\Models\Orders::$Keystatus[$key] }}: {{ $count }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fa-solid fa-magnifying-glass"></i></button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-2">
                                        <form action="{{ route('backend.orders.search') }}" method="GET">
                                            <label class="t-b" for="statusSelect">Search</label>

                                            <div class="input-group mb-3 mt-2">
                                                <input type="text" class="form-control" placeholder="Mã đơn, sđt, tên"
                                                    id="keyword" name="keyword"
                                                    value="{{ request('keyword') ? request('keyword') : '' }}">
                                                <button class="btn btn-primary" type="submit" id="button-addon2"><i
                                                        class="fa-solid fa-magnifying-glass"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="button_reset">
                                    <label style="margin-right: .5rem;" class="t-b">Reset page</label>

                                    <a href="{{ route('backend.orders.index') }}" style="width: fit-content">
                                        <button class="btn btn-primary"><i class="fa-solid fa-rotate"></i></button>
                                    </a>
                                </div>
                            </div>

                            <div class="scroll-wrapper mt-4">

                                <div class="overflow-auto mb-1" id="scroll-top">
                                    <div id="scroll-bar" style="height: 1px;"></div>
                                </div>
                                <div class="table-responsive overflow-auto" id="scroll-bottom">

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Mã đơn hàng</th>
                                                <th scope="col">Bên nhận</th>
                                                <th scope="col">Thu hộ</th>
                                                <th scope="col">Trả ship</th>
                                                <th scope="col">Giao Thất bại</th>
                                                <th scope="col">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $key => $item)
                                                <tr>
                                                    <td>
                                                        <span class="t-b">{{ $item->order_code }}</span>
                                                        <br>
                                                        <span class="t-o">{{ $item->status_name }}</span>
                                                        <br>
                                                        <span
                                                            class="badge rounded-pill bg-danger">{{ $item->shop_id }}</span>
                                                        <br>
                                                        <span class="t-b"> {{ $item->fullname }}</span>
                                                        <br>
                                                        <span class="t-b"> {{ $item->phone }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="t-b"> {{ $item->to_name }}</span>
                                                        <br>
                                                        <span class="t-b"> {{ $item->to_phone }} -
                                                            {{ $item->to_province_name }}</span>
                                                        <br>
                                                        <span class="t-s">
                                                            Ngày tạo:
                                                            {{ $item->created_at->format('H:i:s d-m-Y') }}</span>
                                                        <br>
                                                        <span class="t-s">Ngày
                                                            lấy:
                                                            {{ '' }}</span>
                                                        <br>
                                                        <span class="t-s">Ngày
                                                            giao:
                                                            {{ '' }}</span>
                                                        <br>
                                                    </td>
                                                    <td>
                                                        {{ number_format($item->cod_amount, 0, ',', '.') }}
                                                        <br>
                                                        <span class="checktransfer badge rounded-pill bg-success">
                                                            <?php
                                                            $icheck = \App\Models\DoiSoat::where('OrderCode', $item->order_code)->first();
                                                            if ($icheck and $icheck->type == 2) {
                                                                echo 'Đã chuyển COD';
                                                            } elseif ($icheck and $icheck->type == 1) {
                                                                echo 'Chưa chuyển COD';
                                                            } else {
                                                                echo '';
                                                            }
                                                            ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="t-b">
                                                            {{ $item->payment_method && $item->payment_method == 1 ? 'Bên gửi' : 'Bên nhận' }}</span>
                                                        <br>
                                                        <span class="t-s"> Tổng phí: {{ $item->total_fee }}</span>
                                                    </td>
                                                    <td>
                                                        {{ number_format($item->cod_failed_amount, 0, ',', '.') }}
                                                    </td>
                                                    <td>
                                                        <a href="https://donhang.ghn.vn/?order_code={{ $item->order_code }}"
                                                            target="_blank" class="btn btn-sm btn-primary">
                                                            Tra cứu
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                {{ $orders->links('pagination::bootstrap-4') }}
                            </div>


                            <div class="modal modal-lg fade" id="exportExcelModal" tabindex="-1"
                                aria-labelledby="exportExcelModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form id="export-excel-form" method="POST"
                                        action="{{ route('user.order.bulk_export_many') }}" target="_blank">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exportExcelModalLabel">Xuất Excel Đơn hàng
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Đóng"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Trạng thái đơn hàng</label>
                                                    <div class="row label_trangthaidonhang">
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="ready_to_pick" id="status_ready_to_pick">
                                                            <label for="status_ready_to_pick">Chờ lấy hàng</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]" value="picking"
                                                                id="status_picking">
                                                            <label for="status_picking">Đang lấy hàng</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="money_collect_picking"
                                                                id="status_money_collect_picking">
                                                            <label for="status_money_collect_picking">Đang tương tác với
                                                                người gửi</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]" value="picked"
                                                                id="status_picked">
                                                            <label for="status_picked">Lấy hàng thành công</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]" value="storing"
                                                                id="status_storing">
                                                            <label for="status_storing">Nhập kho</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="transporting" id="status_transporting">
                                                            <label for="status_transporting">Đang trung chuyển</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]" value="sorting"
                                                                id="status_sorting">
                                                            <label for="status_sorting">Đang phân loại</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="delivered" id="status_delivered">
                                                            <label for="status_delivered">Giao hàng thành công</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="money_collect_delivering"
                                                                id="status_money_collect_delivering">
                                                            <label for="status_money_collect_delivering">Đang tương tác với
                                                                người nhận</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="delivery_fail" id="status_delivery_fail">
                                                            <label for="status_delivery_fail">Giao hàng không thành
                                                                công</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="waiting_to_return" id="status_waiting_to_return">
                                                            <label for="status_waiting_to_return">Chờ xác nhận giao
                                                                lại</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]" value="return"
                                                                id="status_return">
                                                            <label for="status_return">Chuyển hoàn</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="return_transporting"
                                                                id="status_return_transporting">
                                                            <label for="status_return_transporting">Đang trung chuyển hàng
                                                                hoàn</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="return_sorting" id="status_return_sorting">
                                                            <label for="status_return_sorting">Đang phân loại hàng
                                                                hoàn</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="returning" id="status_returning">
                                                            <label for="status_returning">Đang hoàn hàng</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="return_fail" id="status_return_fail">
                                                            <label for="status_return_fail">Hoàn hàng không thành
                                                                công</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="returned" id="status_returned">
                                                            <label for="status_returned">Hoàn hàng thành công</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]" value="cancel"
                                                                id="status_cancel">
                                                            <label for="status_cancel">Đơn hủy</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]"
                                                                value="exception" id="status_exception">
                                                            <label for="status_exception">Hàng ngoại lệ</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]" value="lost"
                                                                id="status_lost">
                                                            <label for="status_lost">Hàng thất lạc</label>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <input type="checkbox" name="status_export[]" value="damage"
                                                                id="status_damage">
                                                            <label for="status_damage">Hàng hư hỏng</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="mb-3">
                                                    <label for="date_from" class="form-label">Từ ngày</label>
                                                    <input type="date" class="form-control" name="date_from"
                                                        id="date_from">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="date_to" class="form-label">Đến ngày</label>
                                                    <input type="date" class="form-control" name="date_to"
                                                        id="date_to">
                                                </div>

                                                <input type="hidden" name="type" value="admin">

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-success">Xuất Excel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .column {
            flex: 1;
        }

        .column p {
            margin-bottom: 0;
        }

        .accordion {
            border: 1px solid #ccc;
        }

        .accordion-item {
            border-bottom: 1px solid #ccc;
        }

        .accordion-header {
            background-color: #f1f1f1;
            padding: 10px;
            cursor: pointer;
        }

        .accordion-content {
            padding: 10px;
            display: none;
        }

        .accordion-header-sticky {
            position: sticky;
            top: 0;
            z-index: 1000;
        }
    </style>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.checktransfer').forEach(function(el) {
                if (el.textContent.trim() === '') {
                    el.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const scrollTop = document.getElementById("scroll-top");
            const scrollBottom = document.getElementById("scroll-bottom");
            const scrollBar = document.getElementById("scroll-bar");
            const tableContent = document.getElementById("table-content");

            // Cập nhật chiều rộng thanh cuộn trên
            // function updateScrollWidth() {
            //     scrollBar.style.width = tableContent.scrollWidth + "px";
            // }

            // Gán sự kiện cuộn đồng bộ
            scrollTop.addEventListener("scroll", function() {
                scrollBottom.scrollLeft = scrollTop.scrollLeft;
            });

            scrollBottom.addEventListener("scroll", function() {
                scrollTop.scrollLeft = scrollBottom.scrollLeft;
            });

            // Gọi cập nhật khi tải trang và khi thay đổi kích thước
            // updateScrollWidth();
            // window.addEventListener("resize", updateScrollWidth);
        });
    </script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


@endsection
