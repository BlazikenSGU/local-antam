@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7">
            {{ Breadcrumbs::render('backend.banner.edit') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal" action="" method="post">

                        <div class="row">
                            <div class="col-md-8">
                                @include('backend.partials.msg')
                                @include('backend.partials.errors')
                                @csrf
                                <div class="form-group">
                                    <label class="col-md-12">Tiêu đề</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control form-control-line" name="title"
                                               value="{{old('title',$data->title)}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Nội dung</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control form-control-line" id="description" rows="10"
                                                  name="description"
                                                  value="">{!! old('description',$data->description) !!}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Link</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control form-control-line" name="url"
                                               value="{{old('url',$data->url)}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Loại</label>
                                    <div class="col-md-12">
                                        <select class="form-control" name="type">
                                            <option
                                                value="1" {!! old('type',$data->type)==1?'selected="selected"':'' !!}>
                                                Banner chính
                                            </option>
{{--                                            <option--}}
{{--                                                value="2" {!! old('type',$data->type)==2?'selected="selected"':'' !!}>--}}
{{--                                                Banner thương hiệu--}}
{{--                                            </option>--}}
                                            <option
                                                value="3" {!! old('type',$data->type)==3?'selected="selected"':'' !!}>
                                                Banner quảng cáo
                                            </option>
                                            <option
                                                value="4" {!! old('type',$data->type)==4?'selected="selected"':'' !!}>
                                                Banner quảng cáo
                                            </option>
                                            <option
                                                value="5" {!! old('type',$data->type)==5?'selected="selected"':'' !!}>
                                                Banner quảng cáo
                                            </option>
                                        </select>
                                    </div>
                                </div>


                                <br>


                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="form-group form-group-sm">
                                            <label class="control-label">
                                                Trạng thái <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control form-control-sm" name="status">
                                                <option value="0" {{$data->status == 0 ? "selected" : ''}}>Không hoạt
                                                    động
                                                </option>
                                                <option value="1" {{$data->status == 1 ? "selected" : ''}}>Hoạt động
                                                </option>
                                            </select>
                                        </div>
                                    </div>
{{--                                    <div class="col-md-12">--}}
{{--                                        <div class="form-group form-group-sm">--}}
{{--                                            <label class="control-label">--}}
{{--                                                Danh mục <span class="text-danger">*</span>--}}
{{--                                            </label>--}}
{{--                                            <select class="form-control form-control-sm" name="product_type_id">--}}
{{--                                                <option value="">Chọn</option>--}}
{{--                                                {!! $product_type_html !!}}--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="col-md-12">
                                        <label>Hình
                                            <sup><span class="text-danger">Kích thước chuẩn: banner chính 2048x768, thương hiệu 200x60</span></sup>
                                        </label>
                                        <div class="dropzone" id="myDropzone"
                                             action="{{route('backend.ajax.uploadImage')}}">
                                            <div class="dz-message">
                                                <div>
                                                    <div class="message">
                                                        <h6>Kéo thả tập tin vào hoặc Click để tải lên</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="result">
                                                @if(!empty($image_file))
                                                    <div class="thumb-nail-view">
                                                        <img src="{{url('storage/uploads/'.$image_file->file_path.'')}}"
                                                             class="img-thumbnail"/>
                                                        <input name="image_id" value="{{$image_file->id}}"
                                                               type="hidden">
                                                        <a href="javascript:;" onclick="removeFile($(this))"
                                                           data-id="{{$image_file->id}}">Xóa</a>
                                                        @endif
                                                    </div>
                                                    <div class="fallback">
                                                        <input type="file" name="file" multiple style="opacity: 0">
                                                    </div>
                                            </div>
                                        </div>

                                    </div>

                                    <br>

                                    <div class="col-md-12">
                                        <label>Hình cho mobile
                                            <sup><span
                                                    class="text-danger">Kích thước chuẩn: banner chính 1024x500</span></sup>
                                        </label>
                                        <div class="dropzone" id="myDropzone2"
                                             action="{{route('backend.ajax.uploadImage')}}">
                                            <div class="dz-message">
                                                <div>
                                                    <div class="message">
                                                        <h6>Kéo thả tập tin vào hoặc Click để tải lên</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="result">
                                                @if(!empty($mobile_image_file->id))
                                                    <div class="thumb-nail-view">
                                                        <img
                                                            src="{{url('storage/uploads/'.$mobile_image_file->file_path.'')}}"
                                                            class="img-thumbnail"/>
                                                        <input name="mobile_image_id" value="{{$mobile_image_file->id}}"
                                                               type="hidden">
                                                        <a href="javascript:;" onclick="removeFile($(this))"
                                                           data-id="{{$mobile_image_file->id}}">Xóa</a>
                                                        @endif
                                                    </div>
                                                    <div class="fallback">
                                                        <input type="file" name="file" multiple style="opacity: 0">
                                                    </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-info" type="submit">Lưu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_top')
    <script src="{{ asset('/storage/backend')}}/assets/plugins/ckeditor/ckeditor.js?v=111"></script>
@stop

@section('style')
    <link href="{{ asset('/storage/backend')}}/assets/plugins/codemirror/lib/codemirror.css" rel="stylesheet">
    <link href="{{ asset('/storage/backend')}}/assets/plugins/codemirror/theme/monokai.css" rel="stylesheet">

    <style>
        #form-add label {
            font-size: 13px;
        }

        .result {
            display: inline-block;
        }

        .thumb-nail-view {
            /*width: 32%;*/
            /*height: 100px;*/
            margin: auto;
            padding: 5px;
            float: left;
        }

        .thumb-nail-view a {
            text-align: center;
            font-size: 10px;
            cursor: pointer !important;
            display: inline-block;
            width: 100%;
            color: red;
            font-weight: bold;
        }

        .dropzone .dz-message {
            padding: 0;
        }

        .dropzone .dz-preview .dz-image {
            border-radius: 5px;
            overflow: hidden;
            width: 70px;
            height: 70px;
        }

        .dropzone .dz-preview .dz-image img {
            width: 100%;
        }

        .dropzone .dz-preview .dz-progress {
            width: 60px;
            margin-left: -30px;
        }

        .dropzone .dz-preview .dz-details .dz-size {
            display: none;
        }

        .dropzone {
            min-height: 100px;
        }
    </style>
@stop

@section('script')
    <script src="{{ asset('/storage/backend/assets/plugins')}}/codemirror/lib/codemirror.js"></script>
    <script
        src="{{ asset('/storage/backend/assets/plugins')}}/codemirror/addon/selection/selection-pointer.js"></script>
    <script src="{{ asset('/storage/backend/assets/plugins')}}/codemirror/mode/css/css.js"></script>
    <script src="{{ asset('/storage/backend/assets/plugins')}}/codemirror/addon/edit/matchbrackets.js"></script>
    <script src="{{ asset('/storage/backend/assets/plugins')}}/codemirror/mode/htmlmixed/htmlmixed.js"></script>

    <script>
        // CKEDITOR.replace('detail');

        // var editor = CodeMirror.fromTextArea(document.getElementById("description"), {
        //     lineNumbers: false,
        //     matchBrackets: true,
        //     mode: "text/css",
        //     indentUnit: 4,
        //     theme: "monokai",
        //     indentWithTabs: true
        // });
    </script>

    <script>

        Dropzone.options.myDropzone = {
            uploadMultiple: true,
            parallelUploads: 1,
            maxFilesize: 16,
            addRemoveLinks: false,
            dictFileTooBig: 'Dung lượng hình lớn hơn 16MB',
            timeout: 10000,
            params: {
                _token: $('[name="_token"]').val(),
            },
            init: function () {
                this.on("complete", function (file, reponse) {
                    $('#myDropzone .dz-preview').remove();
                    $('#myDropzone').removeClass('dz-started');
                });
            },
            success: function (file, reponse) {
                $('#myDropzone .result').html('');
                $.each(reponse.r, function (i, item) {
                    appendImage(item.id, item.url, '#myDropzone .result', 'image_id');
                });
            }
        }

        Dropzone.options.myDropzone2 = {
            uploadMultiple: true,
            parallelUploads: 1,
            maxFilesize: 16,
            addRemoveLinks: false,
            dictFileTooBig: 'Dung lượng hình lớn hơn 16MB',
            timeout: 10000,
            params: {
                _token: $('[name="_token"]').val(),
                type: 3
            },
            init: function () {
                this.on("complete", function (file, reponse) {
                    $('#myDropzone2 .dz-preview').remove();
                    $('#myDropzone2').removeClass('dz-started');
                });
            },
            success: function (file, reponse) {
                $('#myDropzone2 .result').html('');
                $.each(reponse.r, function (i, item) {
                    appendImage(item.id, item.url, '#myDropzone2 .result', 'mobile_image_id');
                });
            }
        }

        function removeFile(d) {
            var file_id = d.data('id');
            $.post({
                url: '{{route('backend.ajax.removeImage')}}',
                data: {id: file_id, _token: $('[name="_token"]').val()},
                dataType: 'json',
                success: function (data) {
                    d.parent().remove();
                }
            });
        }

        function appendImage(id, src, parent_div, input_name) {
            $(parent_div).append('<div class="thumb-nail-view">\n' +
                '        <img src="' + src + '" class="img-thumbnail"/>\n' +
                '        <input name="' + input_name + '" value="' + id + '" type="hidden"/>\n' +
                '        <a href="javascript:;" onclick="removeFile($(this))" data-id="' + id + '">Xóa</a>\n' +
                '    </div>')
        }
    </script>
@stop
