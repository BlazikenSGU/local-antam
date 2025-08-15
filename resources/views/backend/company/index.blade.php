@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.users.index') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    @if(auth()->guard('backend')->user()->can('users.add'))
                        <div class="row">
                            <div class="col-md-2 pull-right">
                                <a href="{{Route('backend.users.add')}}"
                                   class="btn waves-effect waves-light btn-block btn-info">
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Thêm mới
                                </a>
                            </div>
                        </div>
                    @endif

                    <form action="" method="get" id="form-filter">
                        <div class="form-body">
                            <div class="row p-t-20">

                                <div class="col-md-12">
                                    @include('backend.partials.msg')
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Tên công ty</label>
                                        <input type="text"
                                               name="name"
                                               value="{{$filter['name']}}"
                                               id="name"
                                               class="form-control" placeholder="Tên công ty">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table color-table muted-table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên công ty</th>
                                    <th>Ngày tạo</th>
                                    <th>Trạng thái</th>
                                    <th class="text-right">Tùy chọn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($company as $key => $item)
                                    <tr>
                                        <td>{{++$start}}</td>
                                        <td>{{$user->phone}}</td>
                                        <td>{{$user->fullname?$user->fullname:'Chưa cập nhật'}}</td>
                                        <td></td>
                                        <td>{{$user->created_at}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">-</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Thanh toán tiền
                                                trong
                                                ví</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="total-salary" class="col-form-label">Tổng
                                                        tiền:</label>
                                                    <input type="text" class="form-control text-danger"
                                                           id="total-salary" disabled>


                                                </div>
                                                <div class="alert alert-info">
                                                    <div class="card card-body e-bank">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" class="form-control" id="user_id">
                                                    <label for="message-text" class="col-form-label">Số tiền
                                                        đã
                                                        thanh toán:</label>
                                                    <input type="text" class="form-control"
                                                           id="salary_payed" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Đóng
                                                </button>
                                                <button type="button" class="btn btn-primary pay-Salary">Gửi
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </table>
                    </div>

                    {{--pagination--}}
                    <div class="text-center">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        $(document).ready(function () {
            $(".fancybox").fancybox();
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
@endsection
