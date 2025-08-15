@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.setting.index') }}

        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal form-bordered" action="" method="post">
                        @include('backend.partials.msg')
                        @include('backend.partials.errors')
                        {{ csrf_field() }}
                        @foreach($settings as $v)
                            <div class="x" @if(!$v['visible']) style="display: none" @endif>
                                <div class="form-group row {{ isset(Session::get('my_errors')[$v['setting_key']]) ? 'has-danger' : ''}}">

                                    <label class="control-label text-left col-md-2" for="{{$v['setting_key']}}">
                                        {{$v['setting_desc']}} @if($v['require'])
                                            <span style="color: red"> (*)</span> @endif
                                    </label>

                                    <div class="col-md-10">
                                        @if($v['setting_type'] == 'image')
                                            <input type="file"
                                                   accept="image/*"
                                                   class="form-control upload_img_select">

                                            <p class="p-image">
                                                <img src="{{url('/storage/uploads').'/'.$v['setting_value']}}"
                                                     class="img-bordered"
                                                     height="80">

                                                <input type="hidden" name="{{$v['setting_key']}}"
                                                       value="{{html_entity_decode($v['setting_value'])}}"
                                                       class="form-control upload_img_value" id="{{$v['setting_key']}}"
                                                       placeholder="{{$v['setting_desc']}}">
                                            </p>
                                        @elseif($v['setting_type'] == 'textarea')
                                            <textarea name="{{$v['setting_key']}}" class="form-control" rows="3"
                                                      placeholder="{{$v['setting_desc']}}"
                                                      id="{{$v['setting_key']}}">{{html_entity_decode($v['setting_value'])}}</textarea>
                                        @else
                                            <input type="text" name="{{$v['setting_key']}}"
                                                   value="{{html_entity_decode($v['setting_value'])}}"
                                                   class="form-control" id="{{$v['setting_key']}}"
                                                   placeholder="{{$v['setting_desc']}}">
                                        @endif

                                        @if(isset(Session::get('my_errors')[$v['setting_key']]))
                                            <small class="form-control-feedback">{{Session::get('my_errors')[$v['setting_key']]}}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <div class="form-group">
                                    <button class="btn btn-info" type="submit">Cập nhật</button>
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
    <link href="{{ asset('/storage/backend')}}/assets/plugins/codemirror/lib/codemirror.css" rel="stylesheet">
    <link href="{{ asset('/storage/backend')}}/assets/plugins/codemirror/theme/monokai.css" rel="stylesheet">
@stop

@section('script')
    <script src="{{ asset('/storage/backend/assets/plugins')}}/codemirror/lib/codemirror.js"></script>
    <script src="{{ asset('/storage/backend/assets/plugins')}}/codemirror/addon/selection/selection-pointer.js"></script>
    <script src="{{ asset('/storage/backend/assets/plugins')}}/codemirror/mode/css/css.js"></script>
    <script src="{{ asset('/storage/backend/assets/plugins')}}/codemirror/addon/edit/matchbrackets.js"></script>
    <script src="{{ asset('/storage/backend/assets/plugins')}}/codemirror/mode/htmlmixed/htmlmixed.js"></script>
    <script src="{{ asset('/storage/backend')}}/assets/plugins/ckeditor/ckeditor.js?v=111"></script>

    <script>

        CKEDITOR.replace('HTML_INTRODUCE');
        CKEDITOR.replace('STORAGE_INSTRUCTIONS');
        CKEDITOR.replace('RETURN_AND_EXCHANGE');
        CKEDITOR.replace('FAQ');
        CKEDITOR.replace('INFORMATION_PRIVACY');
        CKEDITOR.replace('SHIPPING_POLICY');
        CKEDITOR.replace('TERM_OF_USE');
        CKEDITOR.replace('ORDERING_GUIDE');
        // var editor = CodeMirror.fromTextArea(document.getElementById("STATISTIC_HOME"), {
        //     lineNumbers: false,
        //     matchBrackets: true,
        //     mode: "text/css",
        //     indentUnit: 4,
        //     theme: "monokai",
        //     indentWithTabs: true
        // });
        //

        $(document).ready(function () {

            $('input.upload_img_select').change(function () {

                var d = $(this);
                var max_size_allow = 2; //Mb

                $(this.files).each(function (i, file) {
                    var fd = new FormData();
                    fd.append('file', file);
                    fd.append('_token', "{{csrf_token()}}");

                    if (file.size > (1024000 * max_size_allow)) {
                        var msg = "Dung lượng file không được lớn hơn :max_size_allow Mb";
                        alert(msg.replace(':max_size_allow', max_size_allow));
                    } else {
                        sendFileToServer(fd, d);
                    }

                    delete fd;
                });
            });

        });

        function sendFileToServer(formData, d) {

            $.ajax({
                url: "{{route('backend.ajax.uploadImage')}}",
                type: "POST",
                contentType: false,
                processData: false,
                cache: false,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    if (data.e) {
                        alert(data.r);
                    } else {
                        d.parent().find('img').attr('src', data.r[0].url);
                        d.parent().find('input.upload_img_value').val(data.r[0].path);
                        d.parent().parent().find('.upload_img_select').val('');
                    }
                }
            });

        }
    </script>
@stop
