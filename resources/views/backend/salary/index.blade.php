@extends('backend.layouts.main')

@section('style_top')
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
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">

                <li class="breadcrumb-item active">{{ Breadcrumbs::render('backend.salary.index') }}</li>

            </ol>
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    <form action="" method="get" id="form-filter">
                        <div class="form-body">
                            <div class="row p-t-20">

                                <div class="col-md-12">
                                    @include('backend.partials.msg')
                                </div>

                                <input type="hidden" name="sort" value="" class="input_sort">


                                <div class="col-md-2">
                                    <div class="form-group form-group-sm">
                                        <input type="text"
                                               name="fullname"
                                               value="{{request('fullname')}}"
                                               id="title"
                                               class="form-control form-control-sm" placeholder="Họ tên">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-group-sm">
                                        <input type="text"
                                               name="phone"
                                               value="{{request('phone')}}"
                                               id="phone"
                                               class="form-control form-control-sm" placeholder="SĐT">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-group-sm">
                                        <input type="text"
                                               name="email"
                                               value="{{request('email')}}"
                                               id="email"
                                               class="form-control form-control-sm" placeholder="Email">
                                    </div>
                                </div>
                                {{--                                <div class="col-md-2">--}}
                                {{--                                    <div class="form-group form-group-sm">--}}
                                {{--                                        <input type="text"--}}
                                {{--                                               name="dates"--}}
                                {{--                                               value="{{request('dates')}}"--}}
                                {{--                                               id="dates"--}}
                                {{--                                               class="form-control form-control-sm" >--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="col-md-2">
                                    <button type="submit"
                                            class="btn waves-effect waves-light btn-block btn-info btn-sm">
                                        <i class="fa fa-search"></i>&nbsp;&nbsp;Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form action="{{route('backend.salary.export')}}" method="get">

                        <div class="row">
                            <div class="table-responsive">
                                <table class="table color-table muted-table table-striped" id="my-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="width: 20px;"><input type="checkbox"
                                                                            class="checkbox-basic check-all"></th>
                                            <th>Tài khoản</th>
                                            <th>Nội dung</th>
                                            <th>Thời gian</th>
                                            <th>Ngân hàng</th>
                                            <th>Số tiền</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($list_data as $key => $value)
                                            <tr>
                                                <td>{{$value->id}}</td>
                                                <td>
                                                    <input type="checkbox" name="ids[]" value="{{$value->id}}"
                                                           id="{{$value->id}}" class="checkbox-basic check-all-child">
                                                </td>
                                                <td>
                                                    {{!empty($value->fullname)?$value->fullname:'Chưa cập nhật'}}
                                                    <br><br>
                                                    {{!empty($value->email)?$value->email:"Chưa cập nhật"}}
                                                    <br><br>
                                                    {{!empty($value->phone)?$value->phone:"Chưa cập nhật"}}
                                                </td>
                                                <td>
                                                    {{$value->title}}
                                                </td>
                                                <td>
                                                    {{date('d-m-Y, H:i:s',strtotime($value->created_at))}}
                                                </td>
                                                <td>
                                                    @php
                                                        $json = json_decode($value->bank,TRUE);
                                                        if(!empty($json) && is_array($json)){
                                                        if(!empty($json))
                                                        {
                                                               $bank = \App\Models\Banks::findOrFail($json['bank_id']);
                                                                    echo '<p>'.$bank->name.'</p>';
                                                                    echo '<p>Họ&Tên: <b>'.$json['fullname'].'</b></p>';
                                                                    echo '<p>STK: <b>'.$json['bank_account_number'].'</b></p>';
                                                        }else
                                                            {
                                                                echo $value->bank ?  '<p>'.$value->bank.'</p>' : '';
                                                            }
        }
                                                    @endphp
                                                </td>
                                                <td class="text-danger">
                                                    {{number_format($value->salary)}}VND
                                                </td>
                                                <td>
                                                    <a href="{{Route('backend.salary.delete',[$value->id]) . '?_ref=' .$current_url }}"
                                                       class="btn waves-effect waves-light btn-danger btn-sm"
                                                       data-bb="confirm" onclick="return confirm('Bạn muốn hoàn tác thao tác này?')">
                                                        <i class="fa fa-trash-o"></i> Hoàn tác</a>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div style="margin-top: 10px">
                                <label> Chọn tất cả <input type="checkbox" class="checkbox-basic check-all"></label>

                                <button type="submit" class="btn waves-effect waves-light btn-xs btn-success"
                                >Xuất Excel
                                </button>

                            </div>
                        </div>
                    </form>
                    <div class="text-center">
                        {{--                                {{ $list_data->links() }}--}}

                        {{--                                <small>Hiển thị từ {{number_format($list_data->firstItem())}}--}}
                        {{--                                    - {{number_format($list_data->lastItem())}}--}}
                        {{--                                    trong {{number_format($list_data->total())}} mục--}}
                        {{--                                </small>--}}
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
@endsection
@section('script')
    <script>
        $('input[name="dates"]').daterangepicker();

        jQuery(document).on("click", ".changeStatus", function (e) {

            e.preventDefault();
            var id = jQuery(this).data('id');
            jQuery('#idUpdate').val(id);
            jQuery('#showError').hide();
            jQuery.ajax({
                type: "POST",
                url: "",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {id: id},
                dataType: 'json',
                success: function (response) {
                    jQuery('#iStatus').html(response.data.iStatus);
                    jQuery('#ILeaderId').html(response.data.ILeaderId);
                    jQuery('#changeStatus').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        });
        jQuery(document).on("click", "#updateStatus", function (e) {
            e.preventDefault();
            var id = jQuery('#idUpdate').val();
            var status = jQuery('#iStatus').val();
            var leader_id = jQuery('#ILeaderId').val();
            var params = {
                id: id,
                status: status,
                leader_id: leader_id,
            };
            jQuery.ajax({
                type: "POST",
                url: "",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: params,
                dataType: 'json',
                success: function (response) {
                    if (response.status == false) {
                        jQuery('#showError').show();
                        jQuery('#msgError').html(response.error);
                    } else {
                        jQuery('#showError').hide();
                        jQuery('#changeStatus').modal('hide');
                        window.location.href = '';
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        });
    </script>
@stop
