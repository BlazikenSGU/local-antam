@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-dark" style="border-bottom: 1px solid #000; display: inline-block">Quản lý cửa hàng</h3>
        </div>
        {{--        <div class="col-md-7 align-self-center">--}}
        {{--            {{ Breadcrumbs::render('backend.orders.index') }}--}}
        {{--        </div>--}}
    </div>
    <div class="col-md-12">
        <div class="card card-outline-info">
            <div class="card-body">

                {{--                    @if(auth()->guard('backend')->user()->can('users.add'))--}}
                
                <div class="row">
                    <div class="col-md-2 pull-right">
                        <a href="{{Route('backend.users.add')}}"
                           class="btn waves-effect waves-light btn-block btn-info"
                           style="background-color: #00467F; color: #fff">
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Thêm mới
                        </a>
                    </div>

                    <div class="col-md-2 pull-right">
                        <a onclick="ShowAddProductForm('{{auth()->guard('backend')->user()->phone}}',{{auth()->guard('backend')->user()->id}})"
                           class="btn waves-effect waves-light btn-block btn-info"
                           style="background-color: #00467F; color: #fff">
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Thêm sản phẩm
                        </a>
                    </div>

                    <script src="/extends/jsx/products.js?v=1.0.2"></script>




                    {{--                    <div class="col-md-4 pull-right">--}}
                    {{--                        <button--}}
                    {{--                            class="btn-order-change btn waves-effect waves-light btn-block btn-warning">--}}
                    {{--                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Test Báo Cáo--}}
                    {{--                        </button>--}}
                    {{--                    </div>--}}

                </div>


                <div class="table-responsive mt-4 desktop">
                    <table class="table muted-table table-striped">
                        <thead style="background-color: #00467F; color: #fff">
                        <tr>
                            <th>#</th>
                            <th>Tên Shop</th>
                            <th>Địa chỉ</th>
                            <th>Bảng giá</th>
                            <th class="text-right">Mặc định</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $k=>$item)
                            <tr class="font-weight-bold">
                                <td>{{ $k +=1 }}</td>
                                <td>
                                    <a href="{{ route('backend.users.edit',$item->id ) }}" class="text-dark">
                                        {{ $item->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('backend.users.edit',$item->id ) }}" class="text-dark">
                                        {{$item->phone}} - {{ $item->street_name }}
                                        - {{ $item->ward_name }} - {{ $item->district_name }}
                                        - {{ $item->province_name }}
                                    </a>
                                </td>
                                <td>
                                    {{ \App\Models\Branch::find($item->product_type)->name }}
                                </td>
                                <td  class="text-right">
                                    <div class="checkbox-wrapper-64">
                                        <label class="switch">
                                            <input type="checkbox" @if($item->is_default == 1) checked
                                                   @endif class="checkbox" data-id="{{$item->id}}">
                                            <span class="slider"></span>
                                        </label>
                                    </div>

                                </td>
                            </tr>
                        @endforeach

                        </tbody>

                    </table>
                </div>


                {{--pagination--}}
                {{--                    <div class="text-center">--}}
                {{--                        {{ $dâa->links() }}--}}
                {{--                    </div>--}}

            </div>
        </div>
    </div>
    <div class="mobile">
        <div class="col-md-12">
            @foreach($data as $k=>$item)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline-info">
                            <div class="card-body">


                                        <a href="{{ route('backend.users.edit',$item->id ) }}" class="text-dark">
                                            {{ $item->name }} -
                                            {{ $item->phone }}
                                        </a>

                                    <br>

                                        <a href="{{ route('backend.users.edit',$item->id ) }}" class="text-dark">
                                            {{ $item->street_name }} - {{ $item->ward_name }} - {{ $item->district_name }}
                                            - {{ $item->province_name }}
                                        </a>

                                        {{ \App\Models\Branch::find($item->product_type)->name }}

                                        <div class="checkbox-wrapper-64">
                                            <label class="switch">
                                                <input type="checkbox" @if($item->is_default == 1) checked
                                                       @endif class="checkbox" data-id="{{$item->id}}">
                                                <span class="slider"></span>
                                            </label>
                                        </div>



                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>


    </div>
    <div class="modal fade" id="exampleModalTestStatusOrder" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('backend.ops-live.change.file') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="input-group mb-3">

                            <input type="file" name="file" class="form-control" placeholder="Username"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tải</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>

                      

        .checkbox-wrapper-64 input[type="checkbox"] {
            visibility: hidden;
            display: none;
        }

        .checkbox-wrapper-64 *,
        .checkbox-wrapper-64 ::after,
        .checkbox-wrapper-64 ::before {
            box-sizing: border-box;
        }

        /* The switch - the box around the slider */
        .checkbox-wrapper-64 .switch {
            font-size: 17px;
            position: relative;
            display: inline-block;
            width: 3.5em;
            height: 2em;
        }

        /* Hide default HTML checkbox */
        .checkbox-wrapper-64 .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .checkbox-wrapper-64 .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #fff;
            border: 1px solid #adb5bd;
            transition: .4s;
            border-radius: 30px;
        }

        .checkbox-wrapper-64 .slider:before {
            position: absolute;
            content: "";
            height: 1.4em;
            width: 1.4em;
            border-radius: 20px;
            left: 0.27em;
            bottom: 0.25em;
            background-color: #adb5bd;
            transition: .4s;
        }

        .checkbox-wrapper-64 input:checked + .slider {
            background-color: #196d1e;
            border: 1px solid #196d1e;
        }

        .checkbox-wrapper-64 input:focus + .slider {
            box-shadow: 0 0 1px #007bff;
        }

        .checkbox-wrapper-64 input:checked + .slider:before {
            transform: translateX(1.4em);
            background-color: #fff;
        }
    </style> 
   <link rel="stylesheet" href="/extends/jsx/products.css?v=0.0.2.1">

@endsection
@section('script')
    <script>

        $(document).ready(function () {
            $(".fancybox").fancybox();
        });
        jQuery(document).on("click", ".btn-order-change", function (e) {
            $('#exampleModalTestStatusOrder').modal('show');

        });


        jQuery(document).on("click", ".for-control", function (e) {
            e.preventDefault();
            var id = jQuery(this).data('id');
            jQuery.ajax({
                type: "POST",
                url: "{{Route('backend.ajax.ajaxSalary')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {id: id},
                dataType: 'json',
                success: function (response) {
                    jQuery('.e-bank').html('');
                    $.each(response.data.e_bank, function (index, value) {
                        jQuery('.e-bank').append(value);
                    });
                    jQuery('#total-salary').val(response.data.total);
                    jQuery('#user_id').val(response.data.user_id);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        });


        jQuery(document).on("change", ".checkbox", function (e) {
            e.preventDefault();
            var id = jQuery(this).data('id');
            jQuery.ajax({
                type: "POST",
                url: "{{Route('backend.ajax.changeAddress')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {id: id},
                dataType: 'json',
                success: function (response) {

                    if (response.code === 200) {
                        Swal.fire({
                            title: "Thông báo !",
                            text: "Đặt làm mặc định thành công",
                            icon: "success"
                        }).then((result) => {
                            window.location.reload()
                        });
                    } else {
                        Swal.fire({
                            title: "Thông báo !",
                            text: "Có lỗi xẩy ra. Vui lòng thử lại ! ",
                            icon: "error"
                        }).then((result) => {
                            window.location.reload()
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        });
        $(".pay-Salary").on('click', function (e) {
            var salary_payed = parseInt($('#salary_payed').val());
            var user_id = $('#user_id').val();
            var bank_id = $('input[name=bank_id]:checked').val();

            if (isNaN(salary_payed) || salary_payed == null || salary_payed <= 0) {
                Swal.fire({
                    title: 'Thông báo',
                    text: 'Vui lòng nhập số tiền đã thanh toán!',
                    icon: 'error'
                });
            }
            Swal.fire({
                title: "Bạn có chắc đã thanh toán: " + salary_payed + "đ ?",
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'OK',
                cancelButtonText: 'Suy nghĩ lại!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('backend.ajax.paySalary')}}",
                        data: {
                            'user_id': user_id,
                            'salary_payed': salary_payed,
                            'bank_id': bank_id,
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function (response) {
                            if (response.status === true) {
                                Swal.fire(
                                    'Thông báo',
                                    'Thanh toán thành công!',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                })
                            } else {
                                Swal.fire(
                                    'Thông báo',
                                    response.error,
                                    'error'
                                )
                            }
                        }
                    })
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    )
                }
            })
        })
    </script>

    <div id='frm_Products' style="display:none">
        
    </div>
@endsection
