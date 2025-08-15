@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.policy.add') }}
        </div>

        <div class="col-md-12">
            <form class="form-horizontal" action="" method="post">

                @include('backend.partials.msg')
                @include('backend.partials.errors')

                {{ csrf_field() }}
                <div class="row">

                    <div class="col-md-8">
                        <div class="card card-outline-info">
                            <div class="card-body">

                                <div class="form-group form-group-sm">
                                    <label class="form-control-label">Tên bài viết
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           class="form-control form-control-sm"
                                           name="name" required="required"
                                           value="{{old('name')}}">
                                </div>

                                <div class="form-group form-group-sm">
                                    <label class="form-control-label">Mô tả</label>

                                    <textarea type="text"
                                              class="form-control form-control-line form-control-sm" rows="3"
                                              name="excerpt">{{old('excerpt')}}</textarea>
                                </div>

                                <div class="form-group form-group-sm">
                                    <label class="form-control-label">Nội dung</label>

                                    <textarea type="text"
                                              class="form-control form-control-line form-control-sm" rows="5" id="detail"
                                              name="detail">{!! old('detail') !!}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-group-sm text-center p-t-10">
                                            <button class="btn btn-info" type="submit">Thêm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-outline-info">
                            <div class="card-body">

                                <div class="form-group form-group-sm">
                                    <label class="form-control-label">Danh mục
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-control form-control-line form-control-sm" name="category_id">
                                        {!! $categories_html !!}}
                                    </select>
                                </div>

                                <div class="form-group form-group-sm">
                                    <p><label class="form-control-label">Trạng thái</label></p>
                                    @foreach($status as $k=>$v)
                                        <input type="radio" id="status_{{$v['id']}}" name="status" value="{{$v['id']}}"
                                            {{(empty(old('status'))&&$v['id']==1)||(old('status')==$v['id'])?'checked':''}}/>
                                        <label for="status_{{$v['id']}}">{{$v['name']}}</label>
                                    @endforeach
                                </div>

                                <div class="form-group">
                                    <div class="checkbox-out">
                                        <input type="checkbox" id="can_index" class="filled-in"
                                               {{(empty(old('can_index'))||old('can_index')==1)?'checked':''}}
                                               name="can_index" value="1">
                                        <label for="can_index">Google index</label>
                                    </div>
                                </div>

                                <div class="form-group form-group-sm">
                                    <label>Hình đại diện</label>
                                    <div class="dropzone" id="myDropzone" action="{{route('backend.ajax.uploadImage')}}">
                                        <div class="dz-message">
                                            <div class="col-xs-8">
                                                <div class="message">
                                                    <h6>Kéo thả tập tin vào hoặc Click để tải lên</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="result">
                                            @if($thumbnail_image)
                                                <div class="thumb-nail-view">
                                                    <img src="{{$thumbnail_image->file_src}}" class="img-thumbnail">
                                                    <input name="thumbnail_file_id" value="{{$thumbnail_image->id}}" type="hidden">
                                                    <a href="javascript:;" onclick="removeFile($(this))" data-id="{{$thumbnail_image->id}}">Xóa</a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="fallback">
                                            <input type="file" name="file" multiple style="opacity: 0">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-group-sm">
                                    <label>Hình share FB</label>
                                    <div class="dropzone" id="myDropzone2" action="{{route('backend.ajax.uploadImage')}}">
                                        <div class="dz-message">
                                            <div class="col-xs-8">
                                                <div class="message">
                                                    <h6>Kéo thả tập tin vào hoặc Click để tải lên</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="result">
                                            @if($image_fb)
                                                <div class="thumb-nail-view">
                                                    <img src="{{$image_fb->file_src}}" class="img-thumbnail">
                                                    <input name="image_fb_file_id" value="{{$image_fb->id}}" type="hidden">
                                                    <a href="javascript:;" onclick="removeFile($(this))" data-id="{{$image_fb->id}}">Xóa</a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="fallback">
                                            <input type="file" name="file" multiple style="opacity: 0">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-group-sm">
                                    <label class="form-control-label">Seo title</label>

                                    <input type="text"
                                           class="form-control form-control-sm"
                                           name="seo_title"
                                           value="{{old('seo_title')}}">
                                </div>

                                <div class="form-group form-group-sm">
                                    <label class="form-control-label">Seo descriptions</label>

                                    <textarea type="text"
                                              class="form-control form-control-line form-control-sm" rows="5"
                                              name="seo_descriptions">{{old('seo_descriptions')}}</textarea>
                                </div>

                                <div class="form-group form-group-sm">
                                    <label class="form-control-label">Seo keywords</label>

                                    <input type="text"
                                           class="form-control form-control-sm"
                                           name="seo_keywords"
                                           value="{{old('seo_keywords')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('script_top')
    <script src="{{ asset('/storage/backend')}}/assets/plugins/ckeditor/ckeditor.js?v=111"></script>
@stop

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
    <script>
        CKEDITOR.replace('detail');
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
                    appendImage(item.id, item.url, '#myDropzone2 .result', 'image_fb_file_id');
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
