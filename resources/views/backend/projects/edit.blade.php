@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>

        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.projects.edit') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal" id="form-add" action="" method="post">

                        @include('backend.partials.msg')
                        @include('backend.partials.errors')

                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <p><label class="form-control-label">Loại dự án</label></p>
                                            @foreach($relate_data['categories'] as $k=>$v)
                                                <input type="radio" id="categories_{{$v['id']}}" name="category_id" value="{{$v['id']}}"
                                                        {{(empty(old('category_id'))&&$v['id']==1)||(old('category_id')==$v['id'])?'checked':''}}/>
                                                <label for="categories_{{$v['id']}}">{{$v['name']}}</label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <p><label class="form-control-label">Trạng thái</label></p>
                                            @foreach($relate_data['status'] as $k=>$v)
                                                <input type="radio" id="status_{{$v['id']}}" name="status" value="{{$v['id']}}"
                                                        {{(empty(old('status', $project->status))&&$v['id']==1)||(old('status', $project->status)==$v['id'])?'checked':''}}/>
                                                <label for="status_{{$v['id']}}">{{$v['name']}}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group {{$errors->has('title')?'has-danger':''}}">
                                    <label class="form-control-label">Tên dự án
                                        <span class="text-danger">*</span></label>

                                    <input type="text" name="name" class="form-control"
                                           value="{{old('name', $project->name)}}"
                                           placeholder="Nhập Tên dự án" required="required">
                                    {!! $errors->has('name')?'<div class="form-control-feedback">'.$errors->has('name').'</div>':'' !!}
                                </div>

                                <div class="form-group {{$errors->has('investor')?'has-danger':''}}">
                                    <label class="form-control-label">Chủ đầu tư</label>

                                    <input type="text" name="investor" class="form-control"
                                           value="{{old('investor')}}"
                                           placeholder="">
                                    {!! $errors->has('investor')?'<div class="form-control-feedback">'.$errors->has('investor').'</div>':'' !!}
                                </div>

                                <div class="form-group {{$errors->has('date_delivery')?'has-danger':''}}">
                                    <label class="form-control-label">Ngày bàn giao</label>

                                    <input type="text" name="date_delivery" class="form-control"
                                           value="{{old('date_delivery')}}"
                                           placeholder="">
                                    {!! $errors->has('date_delivery')?'<div class="form-control-feedback">'.$errors->has('title').'</div>':'' !!}
                                </div>

                                <div class="form-group {{$errors->has('excerpt')?'has-danger':''}}">
                                    <label class="form-control-label">Giới thiệu ngắn</label>
                                    <textarea type="text"
                                              class="form-control form-control-line" rows="5"
                                              name="excerpt">{{old('excerpt', $project->excerpt)}}</textarea>
                                    {!! $errors->has('excerpt')?'<div class="form-control-feedback">'.$errors->has('excerpt').'</div>':'' !!}
                                </div>

                                <div class="form-group {{$errors->has('detail')?'has-danger':''}}">
                                    <label class="form-control-label">Giới thiệu chi tiết</label>
                                    <textarea type="text" id="detail"
                                              class="form-control form-control-line" rows="5"
                                              name="detail">{!! old('detail', $project->detail) !!}</textarea>
                                    {!! $errors->has('detail')?'<div class="form-control-feedback">'.$errors->has('detail').'</div>':'' !!}
                                </div>

                                <div class="form-group text-center">
                                    <button class="btn btn-info" type="submit">Sửa dự án</button>
                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-group {{$errors->has('province_id')?'has-danger':''}}">
                                    <label class="form-control-label">Tỉnh/TP <span class="text-danger">*</span></label>
                                    <select class="form-control _form-control-sm select_province select2" name="province_id" required="required">
                                        <option value="">Chọn</option>
                                        @foreach($relate_data['provinces'] as $province)
                                            <option value="{{$province->id}}"
                                                    {!! old('province_id',isset($project->province->id)?$project->province->id :null)==$province->id?'selected="selected"':'' !!}
                                            >{{$province->name}}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('province_id')?'<div class="form-control-feedback">'.$errors->has('province_id').'</div>':'' !!}
                                </div>

                                <div class="form-group {{$errors->has('district_id')?'has-danger':''}}">
                                    <label class="form-control-label">Quận/Huyện
                                        <span class="text-danger">*</span></label>
                                    <select class="form-control select_district select2" name="district_id" required="required">
                                        <option value="">Chọn</option>
                                        @foreach($relate_data['districts'] as $districts)
                                            <option value="{{$districts->id}}"
                                                    {!! old('district_id', isset($project->district->id)?$project->district->id:null)==$districts->id?'selected="selected"':'' !!}>{{$districts->name}}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('district_id')?'<div class="form-control-feedback">'.$errors->has('district_id').'</div>':'' !!}
                                </div>

                                <div class="form-group {{$errors->has('ward_id')?'has-danger':''}}">
                                    <label class="form-control-label">Phường/Xã
                                        <span class="text-danger">*</span></label>
                                    <select class="form-control select_ward select2" name="ward_id" required="required">
                                        <option value="">Chọn</option>
                                        @foreach($relate_data['wards'] as $ward)
                                            <option value="{{$ward->id}}"
                                                    {!! old('ward_id',isset($project->ward->id)?$project->ward->id:null)==$ward->id?'selected="selected"':'' !!}>{{$ward->name}}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('ward_id')?'<div class="form-control-feedback">'.$errors->has('ward_id').'</div>':'' !!}
                                </div>

                                <div class="form-group {{$errors->has('address')?'has-danger':''}}">
                                    <label class="form-control-label">Địa chỉ</label>
                                    <input type="text" name="address" class="form-control"
                                           value="{{old('address', $project->address)}}"
                                           placeholder="Nhập Địa chỉ">
                                    {!! $errors->has('address')?'<div class="form-control-feedback">'.$errors->has('address').'</div>':'' !!}
                                </div>

                                <div class="form-group">
                                    <label>Thư viện ảnh</label>
                                    <div class="dropzone" id="myDropzone" action="{{route('backend.ajax.uploadImage')}}">
                                        <div class="dz-message">
                                            <div class="col-xs-8">
                                                <div class="message">
                                                    <h6>Kéo thả tập tin vào hoặc Click để tải lên</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="result">
                                            @foreach($relate_data['file_image_ids'] as $file)
                                                <div class="thumb-nail-view">
                                                    <img src="{{$file->file_src}}" class="img-thumbnail">
                                                    <input name="image_ids[]" value="{{$file->id}}" type="hidden">
                                                    <a href="javascript:;" onclick="removeFile($(this))" data-id="{{$file->id}}">Xóa</a>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="fallback">
                                            <input type="file" name="file" multiple style="opacity: 0">
                                        </div>
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

@section('script_top')
    <script src="{{ asset('/storage/backend')}}/assets/plugins/ckeditor/ckeditor.js"></script>
@stop

@section('style_top')
    <style>
        #form-add label {
            font-size: 13px;
        }

        .result {
            display: inline-block;
        }

        .thumb-nail-view {
            width: 32%;
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
                $.each(reponse.r, function (i, item) {
                    appendImage(item.id, item.url, '#myDropzone .result', 'image_ids[]');
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
                $.each(reponse.r, function (i, item) {
                    appendImage(item.id, item.url, '#myDropzone2 .result', 'image_extra_ids[]');
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