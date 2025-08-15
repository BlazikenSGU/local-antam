@extends('backend.layouts.main')
@section('style_top')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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



    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline-info">
                    <div class="card-body">


                        <div class="ajax-result">
                            <form action="" method="GET">
                                <div class="table-responsive ajax-result">
                                    <table class="table table-striped table-hover" style="overflow: hidden">
                                        <thead style="background-color: #00467F; color: #fff">
                                        <tr class="font-weight-bold">
                                            <th>
                                                <div class="">
                                                    <input class="form-check-input" type="checkbox" value="" id="selectAllCheckbox">
                                                    <label class="form-check-label" for="selectAllCheckbox">
                                                        STT
                                                    </label>
                                                </div>
                                            </th>
                                            <th style="width: 150px">Mã đơn hàng</th>
                                            <th>Bên nhận</th>
                                            <th>Tổng dịch vụ</th>
                                            <th>Thu hộ COD</th>
                                            <th style="max-width: 100px">GH thất bại/ Thu tiền</th>
                                            <th>Tùy chọn thanh toán</th>
                                            <th class="text-center"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data_list as $key => $item)
                                            <tr class="font-weight-bold text-dark">
                                                <td>
                                                    <input class="form-check-input check-input " type="checkbox" value="{{ $item->id }}" name="selected_items[]"
                                                           id="checkbox{{ $key }}" data-order-code="{{$item->order_code}}">
                                                    <label for="checkbox{{ $key }}">{{ $key + 1 }}</label>
                                                </td>

                                                <td style="width: 50px">
                                                    <a href="{{Route('backend.orders.create',[$item->id]). '?_ref=' .$current_url }}"
                                                       style="text-decoration: none">
                                                        @if(!empty($item->order_code))
                                                            <span class="text-dark">{{$item->order_code}} </span>  <br>
                                                            <span
                                                                class=""
                                                                style="font-size: 12px;  color: #F26522"> {{ \App\Models\StatusName::where('key', $item->statusName)->first()->name }}</span>
                                                        @else
                                                            <span class="text-dark">Chưa có mã </span> <br>
                                                            <span class="" style="font-size: 12px; color: #F26522"> Đơn nháp</span>

                                                        @endif
                                                    </a>
                                                </td>

                                                <td style="max-width: 230px">
                                                    <a href="{{Route('backend.orders.create',[$item->id]). '?_ref=' .$current_url }}"
                                                       style="text-decoration: none">
                                                        @if(empty($item->to_name))
                                                            <span class="text-dark">Chưa có thông tin</span>
                                                        @else
                                                            <span class="text-dark">{{$item->to_name}} - {{ $item->to_phone }}</span>
                                                            <p class="text-dark" style="font-size: 14px">{{$item->to_address}}
                                                                , {{$item->to_ward_name}}
                                                                , {{$item->to_district_name}} , {{$item->to_province_name}} </p>
                                                            <i class="text-dark" style="font-size: 12px">Ngày
                                                                tạo: {{ $item->created_at->format('d-m-Y') }}</i>
                                                        @endif
                                                    </a>

                                                </td>
                                                <td>
                                                    {{ empty($item->insurance_value) ? '0 vnđ' : $item->insurance_value . ' vnđ' }}


                                                </td>
                                                <td>
                                                    <a href="{{Route('backend.orders.create',[$item->id]). '?_ref=' .$current_url }}"
                                                       style="text-decoration: none" class="text-dark">

                                                        {{ empty($item->cod_amount) ? '0 vnđ' : $item->cod_amount . ' vnđ' }}
                                                    </a>
                                                </td>

                                                <td>
                                                    <a href="{{Route('backend.orders.create',[$item->id]). '?_ref=' .$current_url }}"
                                                       style="text-decoration: none" class="text-dark">

                                                        {{ empty($item->cod_failed_amount) ? '0 vnđ' : $item->cod_failed_amount . ' vnđ' }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{Route('backend.orders.create',[$item->id]). '?_ref=' .$current_url }}"
                                                       style="text-decoration: none">
                                                        @if($item->payment_method == 1)
                                                            <span class="" style="color: #F26522">Bên gủi trả phí</span> <br>
                                                            <span class="text-dark"> Tổng thu: {{ number_format($item->total_fee) }} vnđ@else</span>
                                                            <span class="" style="color: #F26522">Bên nhận trả phí</span> <br>
                                                            <span class="text-dark">Tổng thu:{{ number_format($item->total_fee) }} vnđ</span>
                                                        @endif
                                                    </a>

                                                </td>
                                                <td style="width: 200px; " class="text-center">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <a href="{{Route('backend.orders.create',[$item->id]). '?_ref=' .$current_url }}">
                                    <span class="btn btn-sm" style="margin: 5px; background-color: #196d1e; color: #fff">
                                        <i class="fa-solid fa-pen-to-square"></i> Chỉnh Sửa
                                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6">
                                <span class="canncel_order" data-id="{{$item->id}}"
                                      data-shopId="{{$item->product_type}}">
                                    <span class="btn btn-sm" style="margin: 5px;background-color: #196d1e; color: #fff ">
                                       <i class="fa-solid fa-handshake-angle"></i> Hủy đơn
                                    </span>
                                </span>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <a href="https://donhang.ghn.vn/?order_code={{$item->order_code}}">
                                     <span class="">
                                    <span class="btn btn-sm" style="margin: 5px; background-color: #196d1e; color: #fff">
                                       <i class="fa-solid fa-magnifying-glass"></i> Tra cứu
                                    </span>
                                    </span>
                                                            </a>
                                                        </div>

                                                        <div class="col-md-6">
                                                            @if($item->status == 1)
                                                                <a href="{{ route('backend.orders.delete', $item->id) }}">
                                         <span class="">
                                        <span class="btn btn-sm" style="margin: 5px; background-color: #ef2f40; color: #fff">
                                           <i class=""></i> Xóa đơn
                                        </span>
                                        </span>
                                                                </a>
                                                            @else

                                                                <span class="btn-history-order" data-id="{{ $item->id}}">
                                        <span class="btn btn-sm" style="margin: 5px; background-color: #ef2f40; color: #fff">
                                           <i class=""></i> Lịch sử
                                        </span>
                                    </span>

                                                            @endif
                                                        </div>
                                                    </div>






                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>


                            </form>
                            <div class="pull-right ajax-pagination">
                                {{ $data_list->links() }}
                            </div>
                            <style>
                                tr {
                                    font-size: 14px !important;
                                }
                                .btn-sm {
                                    width: 75px !important;
                                    font-size: 10px;
                                }
                            </style>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Get the "select all" checkbox element in the header
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');

        // Get all checkboxes in the table body
        const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');

        // Add event listener to the "select all" checkbox
        selectAllCheckbox.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
        $(".btn-history-order").click(function(){
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


        $(".canncel_order").click(function(){
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
                window.location.href = 'https://vipshop.dev24h.net/admin/orders/cancel/'+order_id;
            });
        });


    </script>


@endsection
