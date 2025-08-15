@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.menu.edit') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal form-bordered" action="" method="post">

                        {{ csrf_field() }}
                        <div class="row">

                            <div class="col-md-12">
                                @include('backend.partials.msg')
                                @include('backend.partials.errors')
                            </div>

                            <div class="col-md-12">

                                <div class="form-group row">
                                    <label class="form-control-label text-right col-md-3">Tên menu
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-md-6">
                                        <input type="text"
                                               class="form-control"
                                               name="name" required="required"
                                               value="{{old('name', $data->name)}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label text-right col-md-3">Link
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-md-6">
                                        <input type="text"
                                               class="form-control"
                                               name="link" required="required"
                                               value="{{old('link',$data->link)}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-control-label text-right col-md-3">Trạng thái
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-md-6">
                                        @foreach($status as $k=>$v)
                                            <input type="radio" id="status_{{$v['id']}}" name="status" value="{{$v['id']}}"
                                                    {{old('status', $data->status)==$v['id']?'checked':''}}/>
                                            <label for="status_{{$v['id']}}">{{$v['name']}}</label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-control-label text-right col-md-3">Mô tả</label>

                                    <div class="col-md-6">
                                    <textarea type="text"
                                              class="form-control form-control-line" rows="3"
                                              name="description">{{old('description', $data->description)}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row text-center">
                                    <div class="col-md-12">
                                        <button class="btn btn-info" type="submit">Sửa</button>
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
    <script src="{{ asset('/storage/backend/assets/plugins/dropzone-master/dist/dropzone.js')}}" type="text/javascript"></script>

    <script>
        $.fn.modal.Constructor.prototype._enforceFocus = function () {
        };

        Dropzone.options.myDropzone = {
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
                    $('#myDropzone .dz-preview').remove();
                    $('#myDropzone').removeClass('dz-started');
                });
            },
            success: function (file, reponse) {
                $('#myDropzone .result').html('');
                $.each(reponse.r, function (i, item) {
                    appendImage(item.id, item.url, '#myDropzone .result', 'thumbnail_file_id');
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
                this.on("complete", function (file) {
                    $('#myDropzone2 .dz-preview').remove();
                    $('#myDropzone2').removeClass('dz-started');
                });
            },
            success: function (file, reponse) {
                $('#myDropzone2 .result').html('');
                $.each(reponse.r, function (i, item) {
                    appendImage(item.id, item.url, '#myDropzone2 .result', 'icon_file_id');
                });
            }
        };

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