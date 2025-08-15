@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.notification.add') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal form-material" action="" method="post">

                        <div class="row">
                            <div class="col-md-6" style="margin: auto">
                                @include('backend.partials.msg')
                                @include('backend.partials.errors')

                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="col-md-12">Tiêu đề <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input type="text"
                                               class="form-control form-control-line"
                                               name="title"
                                               value="{{$form_init->title}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Nội dung <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <textarea type="text"
                                               class="form-control form-control-line" rows="5"
                                               name="content">{{$form_init->content}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-12">Kênh <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control form-control-line chanel"
                                                name="chanel">
                                            <option value="1" {!! $form_init->chanel ==1 ? 'selected="selected"' : '' !!}>
                                                Chung
                                            </option>
                                            <option value="2" {!! $form_init->chanel == 2 ? 'selected="selected"' : '' !!}>
                                                Riêng
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">SĐT người nhận (chỉ nhập khi chọn kênh riêng )</label>
                                    <div class="col-md-12">
                                        <select class="select2 form-control select2-multiple user_ids"
                                                {{empty($form_init->chanel)||$form_init->chanel == 1?'disabled="disabled"':''}}
                                                style="width: 100%; height:36px;"
                                                multiple="multiple" data-placeholder="Nhập sđt"
                                                name="user_ids[]">
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success" type="submit">Push thông báo</button>
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

@section('style')
    <link href="{{ asset('/storage/backend/assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('script')
    <!-- google maps api -->
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyB711stPiEwDrN_Biq6Tcx7KHhtu-QPxm0&sensor=true"></script>
    <script src="{{ asset('/storage/backend/assets/plugins/gmaps/gmaps.min.js')}}"></script>
    <script src="{{ asset('/storage/backend/assets/plugins/gmaps/jquery.gmaps.js')}}"></script>

    <script src="{{ asset('/storage/backend/assets/plugins/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>

    <script>
        $('.chanel').change(function (e) {
            if ($(this).val() == '1') {
                $('.user_ids').prop('disabled', true);
            } else {
                $('.user_ids').prop('disabled', false);
            }
        });

        $('.user_ids').select2(
            {
                minimumInputLength: 4,
                ajax: {
                    url: '{{route('backend.ajax.searchUser')}}',
                    dataType: 'json',
                    method: 'POST',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                            _token: '{{csrf_token()}}'
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            }
        );

    </script>
@stop