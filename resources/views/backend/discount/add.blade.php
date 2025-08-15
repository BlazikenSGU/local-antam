@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7">
            {{ Breadcrumbs::render('backend.discount.add') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal" action="" method="post">

                        <div class="row">
                            <div class="col-md-6" style="margin: auto">
                                @include('backend.partials.msg')
                                @include('backend.partials.errors')
                                @csrf

                                <div class="form-group">
                                    <label class="col-md-12">Tiêu đề</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control form-control-line" name="title" value="{{old('title')}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Mã giảm giá</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control form-control-line" name="code" value="{{old('code')}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Loại</label>
                                    <div class="col-md-12">
                                        <select name="type" class="form-control">
                                            <option value="1" {{old('type')==1?'selected="selected"':''}}>Giảm số tiền
                                            </option>
                                            <option value="2" {{old('type')==2?'selected="selected"':''}}>Giảm %
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Giá trị</label>
                                    <div class="col-md-12">
                                        <input type="number" class="form-control form-control-line" name="value" value="{{old('value')}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Số lần sử dụng</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control form-control-line" name="limit" value="{{old('limit')}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Thời hạn</label>
                                    <div class="row" style="padding: 0 15px">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control form-control-line date_time_select" name="start_date"
                                                   readonly
                                                   value="{{old('start_date')}}" placeholder="Ngày bắt đầu">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control form-control-line date_time_select" name="end_date"
                                                   readonly
                                                   value="{{old('end_date')}}" placeholder="Ngày kết thúc">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-12">Loại tài khoản áp dụng mã</label>
                                    <div class="col-md-12">
                                        <select name="user_ids" class="form-control">
                                            <option value="91" {{old('user_ids')==91?'selected="selected"':''}}>Khách hàng

                                            </option>
                                            <option value="92" {{old('user_ids')==92?'selected="selected"':''}}>Nhân viên
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Trạng thái</label>
                                    <div class="col-md-12">
                                        <select name="status" class="form-control">
                                            <option value="0" {{old('status')==0?'selected="selected"':''}}>Không hoạt
                                                động
                                            </option>
                                            <option value="1" {{old('status')==1?'selected="selected"':''}}>Hoạt động
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-info" type="submit">Lưu</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style_top')
    <link href="{{ asset('/storage/backend')}}/assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
@stop

@section('script')
    <script src="{{ asset('/storage/backend')}}/assets/plugins/moment/moment.js"></script>
    <script src="{{ asset('/storage/backend')}}/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

    <script>
        $('.date_time_select').bootstrapMaterialDatePicker({format: 'YYYY-MM-DD HH:mm:ss'});
    </script>

@stop
