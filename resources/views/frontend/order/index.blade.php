@extends('frontend.layouts.main')

@push('title')
    Đơn hàng
@endpush

@section('content')
    <style>
        .label_trangthaidonhang {
            font-size: 12px;
            color: #1b4e87;
            font-weight: bold;
        }

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
            /* width: 150px !important; */
            min-width: 150px !important;
            /* max-width: 150px !important; */
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
            position: relative
        }

        .t-o {
            font-size: 12px;
            color: #f26522;
            font-weight: bold;
        }

        .t-w {
            font-size: 12px;

        }

        .t-b {
            font-size: 12px;
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
            color: #71dd37;
            font-weight: bold;
        }

        /* Các màu gốc của Bootstrap để tham khảo */
        .btn-primary {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-success {
            color: #fff;
            background-color: #198754;
            border-color: #198754;
        }

        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-warning {
            color: #000;
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-info {
            color: #000;
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }

        .modal-content {
            width: 100%;
            padding: 0 2rem;
        }

        .modal-dialog {
            max-width: 100%;
            padding: 0 1rem;
        }

        .xuat_excel {
            margin: 0 .5rem;
        }

        @media (min-width: 1000px) {
            .modal-dialog {
                width: 70%;
            }
        }

        @media(max-width: 992px) and (min-width:650px) {
            .modal-content {
                width: 100% !important;
            }
        }

        @media (max-width: 768px) and (min-width: 375px) {
            .mobile {
                flex-direction: column;
            }

            .mobile-status {
                justify-content: space-between !important;
            }

            .mobile-search {
                /* flex-wrap: wrap; */
                flex-direction: column;
            }


            .mobile-search .btn-primary {
                width: 45%;
                margin: .5rem 0;
                margin-right: 25px !important;
                margin-left: 0 !important;
            }

            .mobile-search .btn-secondary {
                margin: .5rem 0;
                width: 45%;
            }

            .table-bordered .w-250 {
                min-width: 120px !important;
            }

            .mobile_date_filter {
                margin-top: .5rem;
                flex-direction: row;
            }

            .mobile_keyword_filter {
                margin-right: 0 !important;
                margin-top: .5rem;
            }

            .card-body {
                margin-top: .5rem !important;
                padding: 0;
            }

        }

        /* giao dien mobile */
        @media (max-width: 650px) {



            .modal-content {
                width: 100%;
                padding: 0;
            }

            .table-responsive,
            .table-paginaton {
                display: none;
            }

            .table-mobile {
                display: block !important;
            }

            .t-b {
                font-size: 12px;
                color: #1b4e87;
                font-weight: bold;
            }

            .card {
                box-shadow: 0 4px 12px 2px rgba(67, 89, 113, 0.2) !important;
            }

            .card_info_3,
            .card_info_2,
            .card_info_1,
            .card_info_4,
            .card_info_5 {
                padding: 0 1rem;
            }

            .card_button {
                margin-top: .5rem;
                background-color: #d4d3d352;
                padding: .5rem;
            }

            .t-o {
                font-size: 12px;
            }

            #bulk-actions {
                width: 100%;
                overflow-x: auto;
                left: 0;
                right: 0;
                transform: none;
                padding: 0;
            }

            .tab_mobile {
                padding: 0.5rem;
                justify-content: flex-start;
            }

            .order-group-title {
                display: flex;
                align-items: center;
                text-align: center;
                font-weight: bold;
                font-size: 16px;
                color: #1b4e87;
                margin: 16px 0;
                cursor: pointer;
            }

            .order-group-title::before,
            .order-group-title::after {
                content: "";
                flex: 1;
                border-bottom: 1px solid #ccc;
            }

            .order-group-title::before {
                margin-right: 10px;
            }

            .order-group-title::after {
                margin-left: 10px;
            }

            .mobile_order {
                flex-direction: column;
            }

            .mb-4.title-menu {
                margin-bottom: .5rem !important;
            }

            .accordion-header {
                width: 100%;
            }
        }

        /* mobile sieu nho */
        @media(max-width: 383px) {

            .mobile_date_filter {
                margin-top: .5rem !important;
                gap: 0 !important;
            }

            .button_search,
            .button_reset {
                margin: .5rem 0 !important;
                font-size: 10px;
            }

            .card-body {
                padding: .5rem;
            }

            .container {
                padding: 0.5rem;
            }

            .button-menu {
                display: flex;
                flex-direction: row;
            }

            .title-menu {
                font-size: 16px;
            }

            .layout {
                flex-direction: column;
            }

            .xuat_excel,
            .button_createorder {
                font-size: 10px;
            }

            .modal-content {
                width: 100%;
                padding: 0;
            }

            .mobile {
                flex-direction: column;
                align-items: center;
            }

            .function_2 {
                margin-top: 1rem;
            }

            .mobile-search {
                flex-direction: column;
                padding: 0;
            }

            .accordion-header {
                width: 100%;
            }
        }

        #bulk-actions {
            max-width: 100%;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        #bulk-actions::-webkit-scrollbar {
            display: none;
        }

        .tab_mobile {
            display: flex;
            gap: 0.5rem;
            padding: 0.5rem;
            min-width: max-content;
        }

        .tab_mobile .btn {
            white-space: nowrap;
            flex-shrink: 0;
        }
    </style>

    <div class="card mt-2">
        <section class="card-body mt-4">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-2 layout mobile_order">
                    <h2 class="mb-4 title-menu">Đơn hàng</h2>
                    <div class="button-menu">
                        <button class="btn btn-success xuat_excel" data-bs-toggle="modal"
                            data-bs-target="#exportExcelModal">Xuất excel</button>
                        <a class="btn btn-primary button_createorder" href="{{ route('user.order.add') }}">Tạo đơn hàng</a>

                        {{-- <a class="btn btn-danger button_createorder" href="javascript:void(0)">(Tạo đơn bảo trì -> 23h)</a> --}}

                    </div>
                </div>

                <input type="hidden" id="id_user" value="{{ auth()->user()->id }}">

                <div class="col-md-12 d-flex mobile mb-3">
                    <div class="accordion-header col-md-6 d-flex flex-column">
                        <div class="col-md-12 d-flex justify-content-start align-items-center mobile-status">
                            <label for="" style="margin-right: .5rem;">Trạng thái</label>
                            <div class="row-cols-md-4 ">
                                <select name="status" id="status" class="form-control">
                                    <option value="0">Tất cả</option>
                                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Chờ bàn giao
                                        ({{ $count_status[2] }})</option>
                                    {{-- <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Đang giao
                                        ({{ $count_status[3] }})</option> --}}
                                    <option value="15" {{ request('status') == '15' ? 'selected' : '' }}>Đang xử lý
                                        ({{ $count_status[15] }})</option>
                                    <option value="13" {{ request('status') == '13' ? 'selected' : '' }}>Đang giao hàng
                                        ({{ $count_status[13] }})</option>
                                    <option value="14" {{ request('status') == '14' ? 'selected' : '' }}>Giao hàng thất
                                        bại
                                        ({{ $count_status[14] }})</option>
                                    <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Đang hoàn hàng
                                        ({{ $count_status[4] }})</option>
                                    <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>Chờ giao lại
                                        ({{ $count_status[5] }})</option>
                                    <option value="9" {{ request('status') == '9' ? 'selected' : '' }}>Hoàn tất - Đã
                                        chuyển COD
                                        ({{ $count_status[9] }})</option>
                                    <option value="10" {{ request('status') == '10' ? 'selected' : '' }}>Hoàn tất -
                                        Chưa chuyển COD
                                        ({{ $count_status[10] }})</option>
                                    <option value="11" {{ request('status') == '11' ? 'selected' : '' }}>Hoàn hàng - Đã
                                        chuyển cod
                                        ({{ $count_status[11] }})</option>
                                    <option value="12" {{ request('status') == '12' ? 'selected' : '' }}>Hoàn hàng -
                                        Chưa chuyển cod
                                        ({{ $count_status[12] }})</option>
                                    <option value="7" {{ request('status') == '7' ? 'selected' : '' }}>Đơn hủy
                                        ({{ $count_status[7] }})</option>
                                    <option value="8" {{ request('status') == '8' ? 'selected' : '' }}>Hàng thất lạc
                                        ({{ $count_status[8] }})</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12 function_2">
                        <form action="{{ route('user.order.index') }}" method="GET" class="mb-4">
                            <div class="row g-3 " style="display: flex;justify-content: flex-end;">

                                <div class="col-md-12 col-12 d-flex justify-content-end mobile-search">
                                    <div class="col-md-6 col-12 mobile_keyword_filter" style="margin-right: .5rem">
                                        <input type="text" name="keyword" class="form-control"
                                            value="{{ request('keyword') }}" placeholder="Nhập mã đơn hàng, sdt">
                                    </div>

                                    <div class="col-md-6 d-flex justify-content-end mobile_date_filter" style="gap: .2rem;">

                                        <div class=" form-floating col-md-6 col-6">
                                            <input type="date" id="from_date" name="from_date" class="form-control"
                                                value="{{ request('from_date') }}">
                                            <label for="from_date">Từ ngày</label>
                                        </div>

                                        <div class="form-floating col-md-6 col-6">
                                            <input type="date" name="to_date" id="to_date" class="form-control"
                                                value="{{ request('to_date') }}" placeholder="Đến ngày">
                                            <label for="to_date">Đến ngày</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="submit" class="btn btn-primary me-2 mx-2 button_search">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>

                                    <a href="#" class="btn btn-danger button_reset" data-toggle="tooltip"
                                        title="Sử dụng sau khi thao tác đơn hàng: tạo, sửa, hủy, giao lại,...">
                                        <i class="fas fa-sync"></i> Reset cache
                                    </a>
                                </div>


                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">

                    @if ($orders->isEmpty())
                        <div class="alert alert-info">
                            @if (request('keyword'))
                                Không tìm thấy đơn hàng nào phù hợp với từ khóa "{{ request('keyword') }}"
                            @else
                                Chưa có đơn hàng nào
                            @endif
                        </div>
                    @else
                        @if (request('keyword'))
                            <div class="alert alert-success">
                                Tìm thấy {{ $orders->total() }} đơn hàng phù hợp với từ khóa "{{ request('keyword') }}"
                            </div>
                        @endif

                        <table class="table table-bordered">

                            <form id="bulk-action-form" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="selected_orders" id="selected-orders-input">
                                <input type="hidden" name="action_type" id="action-type-input">
                            </form>

                            <thead class="">
                                <tr>
                                    <th scope="col">
                                        <input type="checkbox" id="select-all-checkbox" class="form-check-input">
                                    </th>
                                    <th scope="col" class="w-250">Mã đơn</th>
                                    <th scope="col"class="w-250">Bên nhận</th>
                                    <th scope="col">Thu hộ</th>
                                    <th scope="col"class="w-250">Trả ship</th>
                                    <th scope="col">Giao thất bại</th>
                                    <th scope="col">Tùy chọn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $key => $item)
                                    <tr>
                                        <td>
                                            <input class="form-check-input order-checkbox" type="checkbox"
                                                value="{{ $item->order_code }}">
                                        </td>
                                        <td> <a href="{{ route('user.order.edit', $item->id) }}">
                                                <span class="t-b">{{ $item->order_code }}</span>
                                                <br>

                                                @if ($item->statusName ?? '')
                                                    <span class="t-o" data-status="{{ $item->statusName ?? '' }}">
                                                        {{ format_order_status($item->statusName) }}
                                                    </span>
                                                    <br>
                                                @endif

                                                @if ($item->shop_id)
                                                    <span class="t-b">
                                                        @php
                                                            foreach (\App\Models\Branch::all() as $branch) {
                                                                if ($branch->shopId == $item->shop_id) {
                                                                    echo $branch->name_show;
                                                                }
                                                            }
                                                        @endphp
                                                    </span>
                                                    <br>
                                                @endif

                                                @if ($item->order_code_custom)
                                                    <span class="t-r">{{ $item->order_code_custom }}</span>
                                                    <br>
                                                @endif
                                                <span class="t-b"> {{ $item->fullname }}</span>
                                            </a>
                                        </td>

                                        <td>
                                            <span class="t-b">{{ $item->to_name }} </span>
                                            <br>
                                            <span class="t-b"> {{ $item->to_phone }} -
                                                {{ $item->to_province_name }} </span>
                                            <br>
                                            <span class="t-w" style="font-style: italic">
                                                Ngày tạo: {{ $item->created_at->format('d/m/Y') }}</span>
                                            <br>
                                            @if ($item->PartialReturnCode)
                                                <span class="t-o">GH1P:
                                                    {{ $item->PartialReturnCode ?? '' }}
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            @php
                                                if ($item->payment_method == 2) {
                                                    $cod_amount = $item->cod_amount - $item->fee_shopId;
                                                } elseif ($item->payment_method == 1) {
                                                    $cod_amount = $item->cod_amount;
                                                }
                                            @endphp
                                            <span class="t-o">{{ number_format($cod_amount, 0, ',', '.') }}
                                                đ</span>
                                            <br>
                                            @php $type = \App\Models\DoiSoat::where('OrderCode', $item->order_code)->value('type'); @endphp
                                            <span class="t-b {{ $type == 1 ? 'text-danger' : '' }}">
                                                {{ $type == 2 ? 'Đã chuyển COD' : ($type == 1 ? 'Chưa chuyển COD' : '') }}
                                            </span>
                                        </td>
                                        <td>
                                           <span class="t-b">{{ $item->payment_method == 2 ? 'Bên nhận' : 'Bên gửi' }}</span>
                                            <br>
                                            <span class="t-b">Tổng dịch vụ:
                                                <span class="t-o">
                                                    @php

                                                        $fee_cost = $item->fee_shopId ?? 0;
                                                        //phi van chuyen
                                                        $main_service = (int) $item->main_service ?? 0;
                                                        //phi khai gia
                                                        $insurance_fee = (int) $item->insurance_fee ?? 0;
                                                        //phi giao don 1 phan
                                                        $phi_gh1p = (int) $item->phi_gh1p ?? 0;
                                                        //phi hoan hang      //phi giao lai
                                                        $r2s_fee = $item->R2S ? $main_service / 2 : 0;
                                                        // //phi hoan hang
                                                        $ship_again_fee =
                                                            (int) $item->Return == 0 ? 0 : $main_service / 2;

                                                        $total_fee =
                                                            $main_service +
                                                            $insurance_fee +
                                                            $r2s_fee +
                                                            $phi_gh1p +
                                                            $ship_again_fee +
                                                            $fee_cost;

                                                        echo number_format((float) $total_fee, 0, ',', '.') . ' đ';
                                                    @endphp


                                                </span>
                                            </span>

                                        </td>
                                        <td>
                                            <span class="t-o">
                                                {{ number_format($item->cod_failed_amount, 0, ',', '.') }} đ
                                            </span>
                                            <br>
                                            {{ optional(\App\Models\Doisoat::where('OrderCode', $item->order_code)->first())->tinhtrangthutienGTB == 1 ? 'Thành công' : '' }}
                                        </td>
                                        <td>
                                            <a href="https://donhang.ghn.vn/?order_code={{ $item->order_code }}"
                                                target="_blank" class="btn btn-sm btn-warning mt-2">
                                                Tra cứu
                                            </a>
                                            <br>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>


                        </table>
                    @endif
                </div>

                <div class="table-paginaton">
                    {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>

            </div>
        </section>
    </div>

    <div>
        <div id="bulk-actions" class="position-fixed bottom-0 start-50 translate-middle-x mb-3"
            style="display: none; z-index: 1000;">
            <div class="bg-white shadow p-3 overflow-x-auto" style="width: fit-content;">
                <div class="d-flex gap-2 tab_mobile" style="white-space: nowrap;">

                    <button type="button" class="btn btn-danger" onclick="submitBulkAction('cancel')">
                        <i class="bx bx-x"></i> Hủy đơn
                    </button>
                    <button type="button" class="btn btn-warning" onclick="submitBulkAction('print')">
                        <i class="bx bx-printer"></i> In đơn
                    </button>
                    <button type="button" class="btn btn-success" onclick="submitBulkAction('export')">
                        <i class="bx bx-download"></i> Xuất Excel
                    </button>
                    <button type="button" class="btn btn-info" onclick="submitBulkAction('return')">
                        <i class="fa-solid fa-dolly"></i> Hoàn hàng
                    </button>
                    <button type="button" class="btn btn-dark" onclick="submitBulkAction('delivery-again')">
                        <i class="fa-solid fa-dolly"></i> Giao lại
                    </button>

                </div>
            </div>
        </div>
    </div>

    {{-- giao dien tren mobile --}}
    <div class="table-mobile d-none">

        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" value="" id="select-all-checkbox2"
                style="border-radius: 50%; border: 1px solid #333;">
            <label class="form-check-label" for="flexCheckDefault" style="color: black">
                Chọn toàn bộ
            </label>
        </div>

        @php
            // Nhóm đơn hàng theo ngày tạo (d/m/Y)
            $ordersByDate = [];
            foreach ($orders as $item) {
                $date = $item->created_at->format('d/m/Y');
                $ordersByDate[$date][] = $item;
            }
        @endphp

        @foreach ($ordersByDate as $date => $ordersGroup)
            <div class="order-group-by-date mb-3">
                <div class="order-group-title d-flex justify-content-center align-items-center"
                    style="font-weight:bold; font-size:16px; color:#1b4e87; margin-bottom:8px; text-align: center; cursor:pointer;">
                    Ngày tạo: {{ $date }}
                    <span class="ms-2 order-group-arrow">
                        <i class="fa-solid fa-chevron-up"></i>
                    </span>
                </div>

                <div class="order-group-list">
                    @foreach ($ordersGroup as $key => $item)
                        <div class="card" style="margin: 15px 0">
                            <div class="card_info_1 mt-1 d-flex flex-row justify-content-between align-items-center">
                                <div class="form-check ">
                                    <input class="form-check-input order-checkbox2" type="checkbox"
                                        value="{{ $item->order_code }}" id="flexCheckDefault"
                                        style="border-radius: 50%;border: 2px solid #333;">
                                </div>
                                <div class="mt-2">
                                    <span class="t-b">{{ $item->order_code }}</span>
                                    <br>
                                    @if ($item->statusName ?? '')
                                        <span class="t-o" data-status="{{ $item->statusName ?? '' }}">
                                            @php
                                                $statusName = $item->statusName ?? '';
                                                $displayStatus = '';

                                                foreach (\App\Models\Orders::LIST_STATUS_GHN as $key => $statuses) {
                                                    if (!is_array($statuses)) {
                                                        continue;
                                                    }

                                                    if (in_array($statusName, $statuses)) {
                                                        $constantName = 'status_ghn' . ($key + 1);
                                                        $displayStatus = constant(
                                                            '\App\Models\Orders::' . $constantName,
                                                        );
                                                        break;
                                                    }
                                                }
                                            @endphp

                                            {{ format_order_status($item->statusName ?: '') }}
                                        </span>
                                        <br>
                                    @endif
                                </div>

                                <div class="d-flex flex-column align-items-end">
                                    <a href="https://donhang.ghn.vn/?order_code={{ $item->order_code }}" target="_blank"
                                        class="btn btn-sm btn-warning mt-2">
                                        Hành trình đơn
                                    </a>

                                    <div class="d-flex flex-row">
                                    </div>
                                </div>
                            </div>

                            <div class="card_info_2 mt-1 d-flex flex-row justify-content-between"
                                style="font-size: 12px;">
                                <div>
                                    <span>{{ $item->to_name }} - {{ $item->to_phone }}</span> <br>
                                    <span>{{ $item->to_ward_name }}, {{ $item->to_district_name }},
                                        {{ $item->to_province_name }}</span>
                                </div>
                                <span>Ngày tạo: {{ $item->created_at->format('d/m/Y') }}</span>

                            </div>

                            <div class="card_info_5 mt-1">
                                @if ($item->PartialReturnCode)
                                    <span class="t-b">GH1P:
                                        {{ $item->PartialReturnCode ?? '' }}
                                    </span>
                                @endif
                            </div>

                            <div class="card_info_3 d-flex flex-row justify-content-between align-items-center mt-1">
                                <div>
                                    <span class="t-o">COD</span> <br>
                                    <span class="t-b">{{ number_format($cod_amount, 0, ',', '.') }} vnđ</span>
                                </div>

                                <div>
                                    <span class="t-o">GTB</span> <br>
                                    <span class="t-b">{{ number_format($item->cod_failed_amount, 0, ',', '.') }}
                                        vnđ</span>
                                </div>
                            </div>
                            <div class="card_info_4 d-flex flex-row justify-content-between align-items-center mt-1">


                                @php
                                    $type =
                                        \App\Models\Doisoat::where('OrderCode', $item->order_code)->first()->type ??
                                        null;
                                    $color = $type == 1 ? 'color: red;' : '';

                                @endphp

                                <span class="t-o" style="{{ $color }}">
                                    @if ($type == 2)
                                        Đã chuyển COD
                                    @elseif ($type == 1)
                                        Chưa chuyển COD
                                    @endif
                                </span>

                                <span class="t-o">
                                    {{ optional(\App\Models\Doisoat::where('OrderCode', $item->order_code)->first())->tinhtrangthutienGTB == 1 ? 'Thành công' : '' }}
                                </span>

                            </div>

                            <div class="card_button d-flex flex-row justify-content-evenly align-items-center">
                                <div>
                                    <a class="t-b" href="{{ route('user.order.edit', $item->id) }}"
                                        style="font-size:16px"><i class="fa-solid fa-pencil"></i> Chỉnh sửa</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        @endforeach
    </div>

    <!-- Modal xuất excel -->
    <div class="modal fade" id="exportExcelModal" tabindex="-1" aria-labelledby="exportExcelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="export-excel-form" method="POST" action="{{ route('user.order.bulk_export_many') }}"
                target="_blank">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportExcelModalLabel">Xuất Excel Đơn Hàng</h5>
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
                                    <label for="status_money_collect_picking">Đang thu tiền người gửi</label>
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
                                    <input type="checkbox" name="status_export[]" value="delivering"
                                        id="status_delivering">
                                    <label for="status_delivering">Đang giao hàng </label>
                                </div>

                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="delivered"
                                        id="status_delivered">
                                    <label for="status_delivered">Giao hàng thành công</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="money_collect_delivering"
                                        id="status_money_collect_delivering">
                                    <label for="status_money_collect_delivering">Nhân viên đang thu tiền</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="delivery_fail"
                                        id="status_delivery_fail">
                                    <label for="status_delivery_fail">Giao hàng thất bại</label>
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
                                    <label for="status_return_sorting">Đang phân loại hàng trả</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="returning"
                                        id="status_returning">
                                    <label for="status_returning">Đang hoàn hàng</label>
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <input type="checkbox" name="status_export[]" value="return_fail"
                                        id="status_return_fail">
                                    <label for="status_return_fail">Hoàn hàng thất bại</label>
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#export-excel-form').on('submit', function(e) {
                $('#exportExcelModal').modal('hide');
                setTimeout(function() {
                    location.reload();
                }, 3000); // 3 giây
            });
        });
    </script>
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });

        $('.order-group-title .order-group-arrow i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        // Toggle khi click vào tiêu đề ngày
        $('.table-mobile').on('click', '.order-group-title', function() {
            var $list = $(this).next('.order-group-list');
            var $icon = $(this).find('.order-group-arrow i');
            $list.slideToggle(200, function() {
                if ($list.is(':visible')) {
                    $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                } else {
                    $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                }
            });
        });

        if (window.innerWidth <= 650) {
            var $window = $(window);
            var $groups = $('.order-group-by-date');
            var $sticky = $(
                '<div class="order-group-title sticky-order-group-title" style="height: 50px;position:fixed;top:0;left:0;right:0;z-index:999;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,0.07);display:none;"></div>'
            );
            $('body').append($sticky);

            function updateStickyHeader() {
                var scrollTop = $window.scrollTop();
                var found = false;
                $groups.each(function() {
                    var $group = $(this);
                    var $title = $group.find('.order-group-title').first();
                    var offset = $group.offset().top;
                    var height = $group.height();
                    if (scrollTop >= offset && scrollTop < offset + height) {
                        $sticky.html($title.html());
                        $sticky.show();
                        found = true;
                        return false;
                    }
                });
                if (!found) {
                    $sticky.hide();
                }
            }

            $window.on('scroll', updateStickyHeader);
            updateStickyHeader();

            // Khi click sticky header thì trigger click vào group thật
            $sticky.on('click', function() {
                var stickyText = $sticky.text().trim();
                $groups.each(function() {
                    var $group = $(this);
                    var $title = $group.find('.order-group-title').first();
                    if ($title.text().trim() === stickyText) {
                        $title.trigger('click');
                        return false;
                    }
                });
            });
        }

        $(document).ready(function() {

            // Nếu không có status trong URL, tự động chọn status = 2
            // const urlParams = new URLSearchParams(window.location.search);

            // const hasStatus = urlParams.has('status');
            // const hasKeyword = urlParams.has('keyword');
            // const hasFromDate = urlParams.has('from_date');
            // const hasToDate = urlParams.has('to_date');
            // const hasSwal = document.querySelector('.swal2-container');

            // if (!hasStatus && !hasKeyword && !hasFromDate && !hasToDate) {
            //     const currentUrl = new URL(window.location.href);
            //     currentUrl.searchParams.set('status', '2');
            //     window.location.href = currentUrl.toString();
            // }

            // Xử lý thay đổi trạng thái
            $('#status').change(function() {
                const status = $(this).val();
                const newUrl = new URL(window.location.origin + window.location.pathname);

                if (status) {
                    newUrl.searchParams.set('status', status);
                }

                window.location.href = newUrl.toString();
            });

            // Tự động submit form sau khi nhập với độ trễ 500ms
            let searchTimeout = null;

            // Gộp xử lý cho cả keyword, from_date, to_date
            $('input[name="keyword"]').on('input change', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    $(this).closest('form').submit();
                }, 1000); // hoặc 1500ms tùy bạn muốn nhanh hay chậm
            });


            // Clear form khi click nút làm mới
            $('.button_reset').on('click', function(e) {
                e.preventDefault();

                $('input[name="keyword"]').val('');

                //    window.location.href = window.location.origin + '/user/orders';

            });

            // Thêm loading state khi submit
            $('form').on('submit', function() {
                $('.btn-primary').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Đang tìm...');
            });
        });
    </script>

    <script>
        //clear cache
        document.querySelector('.button_reset').addEventListener('click', function(e) {
            e.preventDefault();

            var url = '{{ route('user.reset.cache') }}';
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Thành công',
                        text: 'Đã làm mới cache thành công',
                        icon: 'success',
                        confirmButtonText: 'Đóng'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Lỗi',
                        text: 'Không thể xóa cache',
                    });
                }
            });

        });
    </script>


    {{-- select all checkbox --}}
    <script>
        $(document).ready(function() {

            const bulkActions = $('#bulk-actions');

            // Xử lý checkbox "Select All"
            $('#select-all-checkbox').change(function() {
                $('.order-checkbox').prop('checked', $(this).prop('checked'));
                updateBulkActionsVisibility();
            });

            // Xử lý checkbox "Select All" mobile
            $('#select-all-checkbox2').change(function() {
                $('.order-checkbox2').prop('checked', $(this).prop('checked'));
                updateBulkActionsVisibility();
            });

            // Xử lý từng checkbox
            $('.order-checkbox').change(function() {
                updateBulkActionsVisibility();
                const allChecked = $('.order-checkbox:checked').length === $('.order-checkbox').length;
                $('#select-all-checkbox').prop('checked', allChecked);
            });

            // Xử lý từng checkbox mobile
            $('.order-checkbox2').change(function() {
                updateBulkActionsVisibility();
                const allChecked = $('.order-checkbox2:checked').length === $('.order-checkbox2').length;
                $('#select-all-checkbox2').prop('checked', allChecked);
            });


            // Hiện/ẩn div bulk actions
            function updateBulkActionsVisibility() {
                const hasSelected = $('.order-checkbox:checked, .order-checkbox2:checked').length > 0;
                if (hasSelected) {
                    bulkActions.slideDown();
                } else {
                    bulkActions.slideUp();
                }
            }
        });

        // check trạng thái đơn hàng
        function checkOrderStatus(selectedOrders) {
            let hasCancelledOrders = false;
            $('.order-checkbox:checked,  .order-checkbox2:checked').each(function() {
                const row = $(this).closest('tr');
                const statusName = row.find('[data-status]').data(
                    'status'); // Thêm data-status vào element chứa status
                if (statusName === 'cancel') {
                    hasCancelledOrders = true;
                    return false;
                }
            });
            return !hasCancelledOrders;
        }

        // Hàm submit form với action tương ứng
        function submitBulkAction(action) {
            const selectedOrders = $('.order-checkbox:checked, .order-checkbox2:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedOrders.length === 0) return;

            if (action === 'cancel') {
                Swal.fire({
                    title: 'Xác nhận hủy đơn',
                    text: `Bạn có chắc chắn muốn hủy ${selectedOrders.length} đơn hàng đã chọn?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hủy đơn',
                    cancelButtonText: 'Đóng',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm(action, selectedOrders);
                    }
                });
            }

            if (action === 'print') {
                if (!checkOrderStatus(selectedOrders)) {
                    Swal.fire({
                        title: 'Không thể in đơn',
                        text: 'Không thể in đơn hàng đã hủy. Vui lòng bỏ chọn các đơn hàng đã hủy.',
                        icon: 'error',
                        confirmButtonText: 'Đóng'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Chọn khổ giấy in',
                    html: `
                            <select id="print-size" class="form-control">
                                <option value="A5">Khổ A5</option>
                                <option value="80x80">Khổ 80x80</option>
                                <option value="52x70">Khổ 52x70</option>
                            </select>
                        `,
                    showCancelButton: true,
                    confirmButtonText: 'In đơn',
                    cancelButtonText: 'Đóng',

                }).then((result) => {
                    if (result.isConfirmed) {
                        const printSize = document.getElementById('print-size').value;
                        submitPrintForm(selectedOrders, printSize);
                    }
                });
            }

            if (action === 'export') {
                Swal.fire({
                    title: 'Xác nhận xuất Excel',
                    text: `Bạn có muốn xuất ${selectedOrders.length} đơn hàng ra Excel?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Xuất Excel',
                    cancelButtonText: 'Đóng',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm(action, selectedOrders);
                    }
                });
            }

            if (action == 'return') {
                Swal.fire({
                    title: 'Xác nhận hoàn hàng',
                    text: `Bạn có muốn hoàn ${selectedOrders.length} đơn hàng ?`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Đóng',
                    confirmButtonColor: '#084298'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm(action, selectedOrders);
                    }
                });
            }

            if (action == 'delivery-again') {
                Swal.fire({
                    title: 'Xác nhận giao lại hàng',
                    text: `Bạn có muốn giao lại ${selectedOrders.length} đơn hàng ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Đóng',
                    confirmButtonColor: '#084298'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm(action, selectedOrders);
                    }
                });

            }
        }

        function submitForm(action, selectedOrders) {
            $('#selected-orders-input').val(JSON.stringify(selectedOrders));
            $('#action-type-input').val(action);

            const form = $('#bulk-action-form');
            switch (action) {
                case 'cancel':
                    form.attr('action', '{{ route('user.order.bulk_cancel') }}');
                    break;
                case 'print':
                    form.attr('action', '{{ route('user.order.bulk_print') }}');
                    break;
                case 'export':
                    form.attr('action', '{{ route('user.order.bulk_export') }}');
                    break;
                case 'return':
                    form.attr('action', '{{ route('user.order.bulk_return') }}');
                    break;
                case 'delivery-again':
                    form.attr('action', '{{ route('user.order.bulk_delivery_again') }}');
                    break;
            }

            if (action === 'export') {
                // Tạo form tạm thời để submit
                const tempForm = $('<form>', {
                    'method': 'POST',
                    'action': form.attr('action'),
                    'target': '_blank' // Mở trong tab mới
                }).append(
                    $('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': '{{ csrf_token() }}'
                    }),
                    $('<input>', {
                        'type': 'hidden',
                        'name': 'selected_orders',
                        'value': JSON.stringify(selectedOrders)
                    })
                );

                // Append form vào body, submit và xóa form
                $('body').append(tempForm);
                tempForm.submit();
                tempForm.remove();
            } else {
                form.submit();
            }
        }

        function submitPrintForm(selectedOrders, printSize) {
            $.ajax({
                url: '{{ route('user.order.bulk_print') }}',
                type: 'POST',
                data: {
                    selected_orders: JSON.stringify(selectedOrders),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Mở URL in trong tab mới
                        window.open(response.data.print_urls[printSize], '_blank');

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể kết nối đến máy chủ'
                    });
                }
            });
        }
    </script>

    <script>
        // Thêm JavaScript để xử lý vuốt ngang
        document.addEventListener('DOMContentLoaded', function() {
            const bulkActions = document.getElementById('bulk-actions');
            const tabMobile = document.querySelector('.tab_mobile');

            if (bulkActions && tabMobile) {
                let isDown = false;
                let startX;
                let scrollLeft;

                bulkActions.addEventListener('mousedown', (e) => {
                    isDown = true;
                    bulkActions.style.cursor = 'grabbing';
                    startX = e.pageX - bulkActions.offsetLeft;
                    scrollLeft = bulkActions.scrollLeft;
                });

                bulkActions.addEventListener('mouseleave', () => {
                    isDown = false;
                    bulkActions.style.cursor = 'grab';
                });

                bulkActions.addEventListener('mouseup', () => {
                    isDown = false;
                    bulkActions.style.cursor = 'grab';
                });

                bulkActions.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - bulkActions.offsetLeft;
                    const walk = (x - startX) * 2;
                    bulkActions.scrollLeft = scrollLeft - walk;
                });

                // Thêm touch events cho mobile
                bulkActions.addEventListener('touchstart', (e) => {
                    isDown = true;
                    startX = e.touches[0].pageX - bulkActions.offsetLeft;
                    scrollLeft = bulkActions.scrollLeft;
                });

                bulkActions.addEventListener('touchend', () => {
                    isDown = false;
                });

                bulkActions.addEventListener('touchmove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.touches[0].pageX - bulkActions.offsetLeft;
                    const walk = (x - startX) * 2;
                    bulkActions.scrollLeft = scrollLeft - walk;
                });
            }
        });
    </script>
@endsection
