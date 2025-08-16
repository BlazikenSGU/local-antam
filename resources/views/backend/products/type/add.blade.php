@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.products.type.add') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal" action="" method="post">

                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                @include('backend.partials.msg')
                                @include('backend.partials.errors')
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Tên danh mục
                                                <span class="text-danger">*</span>
                                            </label>

                                            <input type="text"
                                                   class="form-control"
                                                   name="name" required="required"
                                                   value="{{old('name')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Loại danh mục</label>
                                            <select class="form-control" name="type">
                                                <option
                                                    {{old('type') == 1 ? "selected" : ''}}
                                                    value="1">Danh mục sản phẩm
                                                </option>
                                                <option
                                                    {{old('type') == 2 ? "selected" : ''}}
                                                    value="2">Danh mục sản phẩm ăn vặt
                                                </option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <p><label class="form-control-label">Trạng thái</label></p>
                                            @foreach($status as $k=>$v)
                                                <input type="radio" id="status_{{$v['id']}}" name="status" value="{{$v['id']}}"
                                                    {{(empty(old('status'))&&$v['id']==1)||(old('status')==$v['id'])?'checked':''}}/>
                                                <label for="status_{{$v['id']}}">{{$v['name']}}</label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <p><label class="form-control-label" for="can_index">&nbsp;</label></p>
                                            <div class="checkbox-out">
                                                <input type="checkbox" id="can_index" class="filled-in"
                                                       {{(empty(old('can_index'))||old('can_index')==1)?'checked':''}}
                                                       name="can_index" value="1">
                                                <label for="can_index">Google index</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">Mô tả</label>

                                    <textarea type="text"
                                              class="form-control form-control-line" rows="3"
                                              name="description">{{old('description')}}</textarea>
                                </div>

                                <div class="form-group form-group-sm">
                                    <label class="form-control-label">Chi tiết</label>

                                    <textarea type="text"
                                              class="form-control form-control-line form-control-sm" rows="5" id="detail"
                                              name="detail">{!! old('detail') !!}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Hình đại diện</label>
                                        <div class="dropzone" id="myDropzone" action="">
                                            <div class="dz-message">
                                                <div class="col-xs-8">
                                                    <div class="message">
                                                        <h6>Kéo thả tập tin vào hoặc Click để tải lên</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="result">
                                                @if($category_file_image)
                                                    <div class="thumb-nail-view">
                                                        <img src="{{$category_file_image->file_src}}" class="img-thumbnail">
                                                        <input name="thumbnail_file_id" value="{{$category_file_image->id}}" type="hidden">
                                                        <a href="javascript:;" onclick="removeFile($(this))" data-id="{{$category_file_image->id}}">Xóa</a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="fallback">
                                                <input type="file" name="file" multiple style="opacity: 0">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Icon</label>
                                        <div class="dropzone" id="myDropzone2" action="">
                                            <div class="dz-message">
                                                <div class="col-xs-8">
                                                    <div class="message">
                                                        <h6>Kéo thả tập tin vào hoặc Click để tải lên</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="result">
                                                @if($category_file_icon)
                                                    <div class="thumb-nail-view">
                                                        <img src="{{$category_file_icon->file_src}}" class="img-thumbnail">
                                                        <input name="icon_file_id" value="{{$category_file_icon->id}}" type="hidden">
                                                        <a href="javascript:;" onclick="removeFile($(this))" data-id="{{$category_file_icon->id}}">Xóa</a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="fallback">
                                                <input type="file" name="file" multiple style="opacity: 0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Seo title</label>

                                    <input type="text"
                                           class="form-control"
                                           name="seo_title"
                                           value="{{old('seo_title')}}">
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">Seo descriptions</label>

                                    <textarea type="text"
                                              class="form-control form-control-line" rows="3"
                                              name="seo_descriptions">{{old('seo_descriptions')}}</textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">Seo keywords</label>

                                    <input type="text"
                                           class="form-control"
                                           name="seo_keywords"
                                           value="{{old('seo_keywords')}}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <button class="btn btn-info" type="submit">Thêm</button>
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

    <script src="{{ asset('/storage/backend')}}/assets/plugins/ckeditor/ckeditor.js?v=111"></script>

    <script>

        CKEDITOR.replace('detail');

        Dropzone.options.myDropzone = {
            uploadMultiple: true,
            parallelUploads: 1,
            maxFilesize: 16,
            addRemoveLinks: false,
            dictFileTooBig: 'Dung lượng hình lớn hơn 16MB',
            timeout: 10000,
            params: {
                _token: $('[name="_token"]').val(),
                type: 2
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
                type: 2
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
