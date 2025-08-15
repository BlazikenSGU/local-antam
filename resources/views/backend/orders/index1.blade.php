@extends('backend.layouts.main')
@section('style_top')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <style>
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
    </style>
@stop
@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-dark" style="border-bottom: 1px solid #000; display: inline-block">{{$subtitle}}</h3>
        </div>
        {{--        <div class="col-md-7 align-self-center">--}}
        {{--            {{ Breadcrumbs::render('backend.orders.index') }}--}}
        {{--        </div>--}}
    </div>

    <div class="container-fluid desktop">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 pull-right">
                                <a href="{{ route('backend.orders.add') }}"
                                   class="btn waves-effect waves-light btn-block"
                                   style="background-color: #00467F; color: #fff">
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Lên đơn hàng
                                </a>
                            </div>
                            <div class="col-md-2 pull-right">
                                <span
                                    class="btn waves-effect waves-light btn-block popupExcel"
                                    style="background-color: #00467F; color: #fff">
                                    &nbsp;Tải Excel
                                </span>
                            </div>
                        </div>

                        @include('backend.orders.formFilter')

                        <div class="ajax-result">
                            @include('backend.orders.ajaxTable')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline-info">
                    <div class="card-body">
                        @include('backend.orders.formFilter')

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline-info">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-10 ">
                                <form id="search_form" method="get" action="{{ route('backend.orders.index1') }}">
                                <div class="input-group  ">

                                    <input type="text" class="form-control  "
                                           name="order_code"
                                           placeholder="Tim kiếm mã đơn hoặc số điện thoại">
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text "
                                              id="basic-addon2">Tìm kiếm</button>
                                        <a href="{{ route('backend.orders.index') }}" class="input-group-text bg-danger text-white"
                                            id="basic-addon2">X</a>
                                    </div>

                                </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 pull-right">
                                <a href="{{ route('backend.orders.add') }}"
                                   class="btn waves-effect waves-light btn-block"
                                   style="background-color: #00467F; color: #fff">
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Lên đơn hàng
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline-info">
                    <div class="card-body">
                        <div class="accordion">
                            @foreach($data_list as $key => $item)


                                    <div class="card-body mb-2"
                                         style=" box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
                                        <td>
                                            <input class="form-check-input check-input " type="checkbox" value="{{$item->id}}" name="selected_items[]" id="checkbox{{$item->id}}" data-order-code="">
                                            <label for="checkbox{{$item->id}}"></label>
                                        </td>
                                        <div class="">
                                                        <span style="color: #0d6aad; font-weight: bold;">
                                                             @if(!empty($item->order_code))
                                                                {{$item->order_code}}
                                                            @else
                                                                Chưa có mã
                                                            @endif

                                                        </span>
                                            <a href="https://donhang.ghn.vn/?order_code={{$item->order_code}}"
                                               target="_blank">
                                                <span style="float: inline-end; color: #f68a5e">Hành trình đơn hàng</span>
                                            </a>
                                        </div>
                                        <div class="">
                                            <span style="color: #0d6aad; font-weight: bold;">
                                               {{ \App\Models\StatusName::where('key', $item->statusName)->first()->name }}
                                            </span>
                                        </div>
                                        <div class="">
                                            <span style="">
                                                {{$item->to_name}} - {{ $item->to_phone }}
                                            </span>
                                        </div>
                                        {{--                                                    <div class="">--}}
                                        {{--                                            <span style="">--}}
                                        {{--                                                {{$item->to_address}}--}}
                                        {{--                                                , {{$item->to_ward_name}}--}}
                                        {{--                                                , {{$item->to_district_name}} , {{$item->to_province_name}}--}}
                                        {{--                                            </span>--}}
                                        {{--                                                    </div>--}}
                                        <div class="container">
                                            <div class="row">
                                                <div class="column">
                                                    <p>Tiền thu hộ (COD)</p>
                                                    <p> {{ empty($item->cod_amount) ? '0' : $item->cod_amount . '' }}</p>
                                                    <p class="text-danger">
                                                            <?php
                                                            $icheck = \App\Models\DoiSoat::where('OrderCode', $item->order_code)->first();
                                                            if ($icheck and $icheck->type == 2) {
                                                                echo 'Đã chuyển COD';
                                                            } else if ($icheck and $icheck->type == 1) {
                                                                echo 'Chưa chuyển COD';
                                                            } else {
                                                                echo '';
                                                            }
                                                            ?>

                                                    </p>
                                                </div>
                                                <div class="column">
                                                    <p>Giao thất bại - thu tiền</p>
                                                    <p>{{ empty($item->cod_failed_amount) ? '0' : $item->cod_failed_amount . '' }}</p>
                                                    <p class="text-danger">
                                                        {{ $item->gtbThuTien }}

                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row ">
                                            <div class="col-md-12 ">
                                                    <?php $key = request()->get('key') ?>
                                                @if($key == 1)

                                                    <a class="text-white btn btn-sm btn-danger  "
                                                       onclick="CancelOrdermobie({{$item->id}})">

                                                        Xóa đơn
                                                    </a>
                                                @endif
                                                <a class="btn btn-primary btn-sm" href="{{Route('backend.orders.edit',[$item->id]). '?_ref=' .$current_url }}">

                                                    Chi tiết
                                                </a>
                                                @if($item->statusName == 'storing' or $item->statusName == 'waiting_to_return')

                                                    <a href="{{ route('backend.orders.return', $item->id) }}" class="comfim_return">
                                                                         <span class="">
                                                                        <span class="btn btn-sm"
                                                                              style="margin: 5px; background-color: #e02dee; color: #fff">
                                                                             Hoàn hàng
                                                                        </span>
                                                                        </span>
                                                    </a>

                                                @endif
                                                @if($item->statusName == 'waiting_to_return')

                                                    <a href="{{ route('backend.orders.storing', $item->id) }}" class="comfim_storing">
                                                                         <span class="">
                                                                        <span class="btn btn-sm"
                                                                              style="margin: 5px; background-color: #4d28f5; color: #fff">
                                                                             Giao Lại
                                                                        </span>
                                                                        </span>
                                                    </a>

                                                @endif


                                            </div>

                                        </div>

                                    </div>

                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Thông tin lịch sử đơn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body html">
                    {{--                    <ul>--}}
                    {{--                        <li>0932891555 - Đã tạo đơn ngày 22/10/2022</li>--}}
                    {{--                    </ul>--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    @include('backend.orders.excelModal')

@endsection

@section('style')

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

    </style>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Get the "select all" checkbox element in the header
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');

        // Get all checkboxes in the table body
        const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');

        // Add event listener to the "select all" checkbox
        selectAllCheckbox.addEventListener('change', function () {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    </script>
    <script>


        $(document).ready(function () {
            let scrollCount = 1;
            $(window).scroll(function () {

                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 1) {
                    scrollCount++;
                    // var page = 2;
                    let next_page_url = `https://vipshop.dev24h.net/admin/ajax/ajaxData?user_id=411&limit=30&page=` + scrollCount;
                    console.log(next_page_url)
                    ajaxData(next_page_url)
                    {{--let data = {--}}
                    {{--    _token: '{{ csrf_token() }}',--}}
                    {{--};--}}
                    {{--$.ajax({--}}
                    {{--    type: "GET",--}}
                    {{--    url: '{{ route('backend.orders.ajaxData') }}',--}}
                    {{--    dataType: 'json',--}}
                    {{--    data: data,--}}
                    {{--    success: function (response) {--}}
                    {{--        console.log(response.data.next_page_url)--}}
                    {{--        ajaxData(response.data.next_page_url)--}}
                    {{--    }--}}
                    {{--});--}}
                }
            });

            function ajaxData(next_page_url) {
                let data = {
                    _token: '{{ csrf_token() }}',
                };

                $.ajax({
                    type: "GET",
                    url: next_page_url,
                    dataType: 'json',
                    data: data,
                    success: function (response) {
                        console.log(response.data.next_page_url);

                        let html = ``;
                        $.each(response.data.data, function (index, value) {
                            html += `<tr class="font-weight-bold text-dark">
                <td>
                    <input class="form-check-input check-input" type="checkbox" value="${value.id}" name="selected_items[]" id="checkbox${index}" data-order-code="${value.order_code}">
                    <label for="checkbox${index + 1 + 30}">${index + 1 + 30}</label>
                </td>
                <td style="width: 50px">
                    <a href="${'{{Route('backend.orders.create', ['id' => ''])}}/' + value.id + '?_ref=' + '${current_url}'}" style="text-decoration: none">
                        ${value.order_code ? `<span class="text-dark">${value.order_code}</span> <br>
                            <span style="font-size: 12px; color: #F26522">${value.statusName}</span>` : `<span class="text-dark">Chưa có mã</span> <br>
                            <span style="font-size: 12px; color: #F26522">Đơn nháp</span>`}
                    </a>
                </td>
                <td style="max-width: 230px">
                    <a href="${'{{Route('backend.orders.create', ['id' => ''])}}/' + value.id + '?_ref=' + '${current_url}'}" style="text-decoration: none">
                        ${value.to_name ? `<span class="text-dark">${value.to_name} - ${value.to_phone}</span> <br>
                            <p class="text-dark" style="font-size: 14px">${value.to_address}, ${value.to_ward_name}, ${value.to_district_name}, ${value.to_province_name}</p>
                            <i class="text-dark" style="font-size: 12px">Ngày tạo: ${value.created_at}</i>` : `<span class="text-dark">Chưa có thông tin</span>`}
                    </a>
                </td>
                <td>${value.insurance_value ? `${value.insurance_value} ` : '0 '}</td>
                <td><a href="${'{{Route('backend.orders.create', ['id' => ''])}}/' + value.id + '?_ref=' + '${current_url}'}" style="text-decoration: none" class="text-dark">${value.cod_amount ? `${value.cod_amount} ` : '0 '}</a></td>
                <td><a href="${'{{Route('backend.orders.create', ['id' => ''])}}/' + value.id + '?_ref=' + '${current_url}'}" style="text-decoration: none" class="text-dark">${value.cod_failed_amount ? `${value.cod_failed_amount} ` : '0 '}</a></td>
                <td><a href="${'{{Route('backend.orders.create', ['id' => ''])}}/' + value.id + '?_ref=' + '${current_url}'}" style="text-decoration: none">
                        ${value.payment_method === 1 ? `<span style="color: #F26522">Bên gửi trả phí</span> <br>
                            <span class="text-dark">Tổng thu: ${value.total_fee} ` : `<span style="color: #F26522">Bên nhận trả phí</span> <br>
                            <span class="text-dark">Tổng thu: ${value.total_fee} `}
                    </a>
                </td>
                <td style="width: 200px;" class="text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="${'{{Route('backend.orders.create', ['id' => ''])}}/' + value.id + '?_ref=' + '${current_url}'}">
                                <span class="btn btn-sm" style="margin: 5px; background-color: #196d1e; color: #fff">
                                    <i class="fa-solid fa-pen-to-square"></i> Chỉnh Sửa
                                </span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <span class="${value.statusName === 'ready_to_pick' || value.statusName === 'picking' || value.statusName === 'money_collect_picking' ? 'canncel_order' : 'not_canncel_order'}" data-id="${value.id}" data-shopId="${value.product_type}">
                                <span class="btn btn-sm" style="margin: 5px; background-color: #196d1e; color: #fff">
                                    <i class="fa-solid fa-handshake-angle"></i> Hủy đơn
                                </span>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <a href="https://donhang.ghn.vn/?order_code=${value.order_code}">
                                <span class="btn btn-sm" style="margin: 5px; background-color: #196d1e; color: #fff">
                                    <i class="fa-solid fa-magnifying-glass"></i> Tra cứu
                                </span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            ${value.status === 1 ? `<a href=" ">
                                <span class="btn btn-sm" style="margin: 5px; background-color: #ef2f40; color: #fff">
                                    <i class=""></i> Xóa đơn
                                </span>
                            </a>` : `<span class="btn-history-order" data-id="${value.id}">
                                <span class="btn btn-sm" style="margin: 5px; background-color: #ef2f40; color: #fff">
                                    <i class=""></i> Lịch sử
                                </span>
                            </span>`}
                        </div>
                    </div>
                </td>
            </tr>`;
                        });

                        $('.table>tbody').append(html);


                    }
                });
            }




            {{--let data = {--}}
            {{--        _token: '{{ csrf_token() }}',--}}

            {{--    }--}}
            {{--    $.ajax({--}}
            {{--        type: "GET",--}}
            {{--        url: '{{ route('backend.orders.ajaxData') }}',--}}
            {{--        dataType: 'json',--}}
            {{--        data: data,--}}
            {{--        success: function (data) {--}}
            {{--            console.log(data)--}}

            {{--        }--}}
            {{--    });--}}



        });
    </script>

    <script>
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });
        $(".btn-history-order").click(function () {
            var id = $(this).attr('data-id')
            $('#staticBackdrop').modal('show');

            let data = {
                _token: '{{ csrf_token() }}',
                id: id,
            }
            console.log(data)

            $.ajax({
                type: "POST",
                url: '{{ route('backend.orders.history') }}',
                dataType: 'json',
                data: data,
                success: function (data) {
                    console.log(data)
                    $('.html').html(data.r)

                }
            });
        });


        $(".canncel_order").click(function () {
            var order_id = $(this).data('id');

            Swal.fire({
                title: "Thông báo?",
                text: "Bạn muốn hủy đơn hàng?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xác nhận"
            }).then((result) => {
                window.location.href = 'https://vipshop.dev24h.net/admin/orders/cancel/' + order_id;
            });
        });
        $(".not_canncel_order").click(function () {


            Swal.fire({
                title: "Thông báo?",
                text: "Đơn hàng đang trong quá trình xử lý. Bạn không thể hủy đơn hàng ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xác nhận"
            });
        });

        $(".comfim_return").click(function () {
            event.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: "Thông báo?",
                text: "Xác nhận trả hàng.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xác nhận"
            }).then((result) => {
                console.log(result)

                if (result.value === true) {
                    // Nếu người dùng đồng ý, chuyển hướng đến href
                    window.location.href = href;
                }
            });
        });
        $(".comfim_storing").click(function () {
            event.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: "Thông báo?",
                text: "Yêu cầu giao lại đơn hàng. Phí giao lại 11.000VNĐ.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xác nhận"
            }).then((result) => {
                console.log(result)

                if (result.value === true) {
                    // Nếu người dùng đồng ý, chuyển hướng đến href
                    Swal.fire("Đơn hàng đã tích giao lại").then((result) => {
                        window.location.href = href;
                    });
                }
            });
        });


    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const accordionItems = document.querySelectorAll('.accordion-item');

            accordionItems.forEach(item => {
                const header = item.querySelector('.accordion-header');

                header.addEventListener('click', function () {
                    const content = this.nextElementSibling;
                    content.style.display = content.style.display === 'block' ? 'none' : 'block';

                    // Đóng tất cả các accordion khác khi mở 1 accordion
                    accordionItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.querySelector('.accordion-content').style.display = 'none';
                        }
                    });
                });
            });
        });

        $(".popupExcel").click(function () {
            $('#exampleModalExcel').modal('show');

        });
    </script>
@endsection
