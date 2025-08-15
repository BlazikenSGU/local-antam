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
                        <div class="mb-4 buttomwrap" style="">
                            <a href="{{ url('admin/ops-live/amount?status=1') }}">
                                <button type="button" class="btn btn-tag order-draft " >
                                    <h6>0 DH</h6>
                                    <span>Láy hàng thành công  </span>
                                </button>
                            </a>

                            <a href="{{ url('admin/ops-live/amount?status=2') }} ">
                                <button type="button" class="btn btn-tag order-draft " >
                                    <h6>0 DH</h6>
                                    <span>Giao thất bại - chờ xác nhận giao lại  </span>
                                </button>
                            </a>

                            <a href=" {{ url('admin/ops-live/amount?status=3') }}">
                                <button type="button" class="btn btn-tag order-draft " >
                                    <h6>0 DH</h6>
                                    <span>Giao thành công  </span>
                                </button>
                            </a>

                            <a href="{{ url('admin/ops-live/amount?status=4') }} ">
                                <button type="button" class="btn btn-tag order-draft " >
                                    <h6>0 DH</h6>
                                    <span>Giao thất bại - lưu lại kho  </span>
                                </button>
                            </a>

                            <a href=" {{ url('admin/ops-live/amount?status=5') }}">
                                <button type="button" class="btn btn-tag order-draft " >
                                    <h6>0 DH</h6>
                                    <span>Hoàn hàng thành công  </span>
                                </button>
                            </a>

                            <a href=" {{ url('admin/ops-live/amount?status=6') }} ">
                                <button type="button" class="btn btn-tag order-draft " >
                                    <h6>0 ĐH</h6>
                                    <span>Hàng thất lạc - hư hỏng  </span>
                                </button>
                            </a>
                        </div>

                        <style>

                            .buttomwrap {
                                max-width: 100%;
                                overflow: auto;
                                display: flex;
                                padding: 15px;
                            }
                            .buttomwrap::-webkit-scrollbar {
                                /* Tùy chỉnh thanh cuộn (nếu cần) */
                                width: 5px; /* Độ rộng của thanh cuộn */
                                height: 5px;
                                margin-top: 10px;
                                margin-bottom: 10px;

                            }

                            .buttomwrap::-webkit-scrollbar-thumb {
                                /* Tùy chỉnh đối tượng cuộn */

                                background-color: gray; /* Màu sắc của thanh cuộn */
                                border-radius: 10px; /* Độ cong của thanh cuộn */
                            }

                            .btn-tag {
                                border-radius: 16px !important;
                                border: none !important;
                                font-size: 15px;
                                margin-right: 6px;
                                text-align: left;
                            }
                        </style>

                        <div class="ajax-result">
                            <form action="" method="GET">
                                <div class="table-responsive ajax-result">
                                    <table class="table table-striped table-hover" style="overflow: hidden">
                                        <thead style="background-color: #00467F; color: #fff">
                                        <tr class="font-weight-bold">
                                            <th  style="width:100px">
                                                STT
                                            </th>
                                            <th>Mã đơn hàng</th>
                                            <th>Bên nhận</th>
                                            <th>Tổng dịch vụ</th>
                                            <th>Thu hộ COD</th>
                                            <th style="max-width: 100px">GH thất bại/ Thu tiền</th>
                                            <th>Tùy chọn thanh toán</th>
                                            <th class="text-center"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($data) > 0)
                                        @foreach($data as $k=>$item)
                                        <tr>
                                            <td>{{$k++}}</td>

                                            <td>
                                                <span class="text-dark">{{$item->order_code}} </span>  <br>
                                                <span
                                                    class=""
                                                    style="font-size: 12px;  color: #F26522"> {{ \App\Models\StatusName::where('key', $item->statusName)->first()->name }}</span>
                                            </td>
                                            <td>
                                                <span class="text-dark">{{$item->to_name}} </span>  <br>
                                                <span class="text-dark">{{$item->to_phone}} - {{$item->to_province}} </span>  <br>
                                                <i class="text-dark" style="font-size: 11px">Ngày tạo: {{$item->created_at->fortmat('d-m-Y')}} </i>
                                            </td>
                                            <td><span class="text-dark">{{$item->cod_amount}} vnđ</span>  </td>
                                            <td><span class="text-dark">{{$item->cod_failed_amount}} vnđ</span>  </td>
                                            <td>
                                                @if($item->payment_method == 1)
                                                    <span class="" style="color: #F26522">Bên gủi trả phí</span> <br>
                                                    <span class="text-dark"> Tổng phí: {{ number_format($item->total_fee) }} vnđ@else</span>
                                                    <span class="" style="color: #F26522">Bên nhận trả phí</span> <br>
                                                    <span class="text-dark">Tổng phí:{{ number_format($item->total_fee) }} vnđ</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>


                            </form>
{{--                            <div class="pull-right ajax-pagination">--}}
{{--                                {{ $data_list->links() }}--}}
{{--                            </div>--}}
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
