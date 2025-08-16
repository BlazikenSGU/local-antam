@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.products.add') }}
        </div>

        <div class="col-md-12">
            <form class="form-horizontal" id="form-add" action="" method="post">
                {{ csrf_field() }}
                @include('backend.partials.msg')
                @include('backend.partials.errors')

                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-outline-info">
                            <div class="card-body">
                                <div class="form-group form-group-sm {{$errors->has('title')?'has-danger':''}}">
                                    <label class="control-label">
                                        Tên sản phẩm<span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="title" name="title"
                                           value="{{old('title', $product->title)}}"
                                           class="form-control form-control-sm" required>
                                </div>

                                <div class="form-group form-group-sm {{$errors->has('description')?'has-danger':''}}">
                                    <label class="control-label">
                                        Mô tả ngắn
                                    </label>
                                    <textarea class="form-control form-control-sm" name="description"
                                              rows="5" id="description"
                                              class="description">{!! old('description', $product->description) !!}</textarea>
                                </div>
                                <div class="form-group form-group-sm {{$errors->has('description')?'has-danger':''}}">
                                    <label class="control-label">
                                        Nội dung chi tiết
                                    </label>
                                    <textarea class="form-control form-control-sm" name="detail"
                                              rows="5" id="detail"
                                              class="detail">{!! old('description', $product->detail) !!}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group form-group-sm">
                                            <label class="control-label">
                                                Quy cách <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="specifications"
                                                   value="{{old('specifications', $product->specifications)}}"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group form-group-sm">
                                            <label class="control-label">
                                                Giá <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" name="price" min="0" step="100"
                                                   value="{{old('price', floatval($product->price))}}"
                                                   required="required"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group form-group-sm">
                                            <label class="control-label">
                                                Giá so sánh
                                            </label>
                                            <input type="number" name="price_old" min="0" step="100"
                                                   value="{{old('price_old', floatval($product->price_old))}}"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group form-group-sm">
                                            <label class="control-label">
                                                % giảm giá
                                            </label>
                                            <input type="number" name="percent_discount" min="0" step="1"
                                                   value="{{old('percent_discount', $product->percent_discount)}}"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group form-group-sm">
                                            <label class="control-label">
                                                Mã sản phẩm
                                            </label>
                                            <input type="text" id="product_code" name="product_code"
                                                   value="{{old('product_code', $product->product_code)}}"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group form-group-sm">
                                            <label class="control-label">
                                                Quản lý tồn kho
                                            </label>

                                            <select name="inventory_management" class="form-control form-control-sm"
                                                    id="inventory_management">
                                                <option
                                                    value="0" {!! old('inventory_management', $product->inventory_management)==0?"selected='selected'":'' !!}>
                                                    Không lý tồn kho
                                                </option>
                                                <option
                                                    value="1" {!! old('inventory_management', $product->inventory_management)==1?"selected='selected'":'' !!}>
                                                    Quản lý tồn kho
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div
                                        class="col-md-3 {!! old('inventory_management', $product->inventory_management)==0?"d-none":'' !!}"
                                        id="warehouse_inventory">
                                        <div class="form-group form-group-sm">
                                            <label class="control-label">
                                                Tồn kho
                                            </label>

                                            <input type="number" id="inventory" name="inventory"
                                                   min="0"
                                                   value="{{old('inventory', $product->inventory)}}"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div
                                        class="col-md-3 {!! old('inventory_management', $product->inventory_management)==0?"d-none":'' !!}"
                                        id="warehouse_inventory_policy">
                                        <div class="form-group form-group-sm">
                                            <label class="control-label">
                                                Khi hết hàng
                                            </label>

                                            <input type="checkbox" id="inventory_policy"
                                                   name="inventory_policy" class="filled-in"
                                                   {!! old('inventory_policy', $product->inventory_policy)?"checked":'' !!}
                                                   value="1"/>
                                            <label for="inventory_policy"
                                                   class="filled-in chk-col-red">Tiếp tục đặt
                                                hàng</label>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="form-group form-group-sm {{$errors->has('product_type_id')?'has-danger':''}}">
                                    <label class="control-label">
                                        Danh mục <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control form-control-sm" name="product_type_id">
                                        {!! $relate_data['product_type_html'] !!}}
                                    </select>
                                    {!! $errors->has('product_type_id')?'<div class="form-control-feedback">'.$errors->first('product_type_id').'</div>':'' !!}
                                </div>

                                <div
                                    class="form-group form-group-sm {{$errors->has('product_type_ids')?'has-danger':''}}">
                                    <label class="control-label">
                                        Danh mục liên quan
                                    </label>
                                    <select class="form-control form-control-sm" name="product_type_ids[]"
                                            multiple="multiple" style="height: 200px">
                                        {!! $relate_data['product_types_html'] !!}}
                                    </select>
                                    {!! $errors->has('product_type_ids')?'<div class="form-control-feedback">'.$errors->first('product_type_ids').'</div>':'' !!}
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-group-sm">
                                            <label class="control-label">
                                                <b>Sản phẩm có nhiều phiên bản dựa trên màu sắc hoặc kích thước?</b>
                                            </label>
                                        </div>
                                    </div>

                                    @include('backend.products.variation.create')
                                    @include('backend.products.variation.edit')
                                </div>

                                <hr>


                                <hr>

                                <div class="form-group text-center">
                                    <button class="btn btn-primary" type="submit">Cập nhật</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-outline-info">
                            <div class="card-body">

                                <div class="form-group form-group-sm">
                                    <label class="control-label">
                                        Trạng thái <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control form-control-sm" name="status">
                                        @foreach($relate_data['status'] as $k=>$v)
                                            <option value="{{$v['id']}}"
                                                {!! old('status',$product->status)==$v['id']?'selected="selected"':'' !!}>
                                                {{$v['name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

{{--                                <div class="form-group form-group-sm">--}}
{{--                                    <div class="checkbox-out">--}}
{{--                                        <input type="checkbox" id="is_best_sell" class="filled-in"--}}
{{--                                               name="is_best_sell" value="1"--}}
{{--                                            {{old('is_best_sell', $product->is_best_sell)==1?'checked':''}}/>--}}
{{--                                        <label for="is_best_sell">Bán chạy</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="checkbox-out">--}}
{{--                                        <input type="checkbox" id="is_sale" class="filled-in"--}}
{{--                                               name="is_sale" value="1"--}}
{{--                                            {{old('is_sale', $product->is_sale)==1?'checked':''}}/>--}}
{{--                                        <label for="is_sale">Đang giảm giá</label>--}}
{{--                                    </div>--}}

{{--                                    <div class="checkbox-out">--}}
{{--                                        <input type="checkbox" id="is_new_arrival" class="filled-in"--}}
{{--                                               name="is_new_arrival" value="1"--}}
{{--                                            {{old('is_new_arrival', $product->is_new_arrival)==1?'checked':''}} />--}}
{{--                                        <label for="is_new_arrival">Hàng mới</label>--}}
{{--                                    </div>--}}

{{--                                    <div class="checkbox-out">--}}
{{--                                        <input type="checkbox" id="is_featured" class="filled-in"--}}
{{--                                               name="is_featured" value="1"--}}
{{--                                            {{old('is_featured', $product->is_featured)==1?'checked':''}}/>--}}
{{--                                        <label for="is_featured">Xu hướng</label>--}}
{{--                                    </div>--}}


{{--                                </div>--}}

                                <div class="form-group">
                                    <div class="checkbox-out">
                                        <input type="checkbox" id="can_index" class="filled-in"
                                               {{old('can_index', $product->can_index)==1?'checked':''}}
                                               name="can_index" value="1">
                                        <label for="can_index">Google index</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">
                                        Hình ảnh
                                    </label>

                                    <div class="dropzone" id="myDropzone"
                                         action="">
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
                                                    <div class="checkbox-out">
                                                        <input type="radio" id="cover_{{$file->id}}" class="filled-in"
                                                               {{old('thumbnail_id', $product->thumbnail_id)==$file->id?'checked':''}}
                                                               name="thumbnail_id" value="{{$file->id}}">
                                                        <label for="cover_{{$file->id}}">Cover</label>
                                                    </div>
                                                    <a href="javascript:;" onclick="removeFile($(this))"
                                                       data-id="{{$file->id}}">Xóa</a>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="fallback">
                                            <input type="file" name="file" multiple style="opacity: 0">
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group form-group-sm {{$errors->has('seo_title')?'has-danger':''}}">
                                    <label class="control-label">
                                        Seo title
                                    </label>
                                    <input type="text" id="seo_title" name="seo_title"
                                           value="{{old('seo_title', $product->seo_title)}}"
                                           class="form-control form-control-sm">
                                </div>

                                <div class="form-group form-group-sm {{$errors->has('title')?'has-danger':''}}">
                                    <label class="control-label">
                                        Seo keywords
                                    </label>
                                    <input type="text" id="seo_keywords" name="seo_keywords"
                                           value="{{old('seo_keywords', $product->seo_keywords)}}"
                                           class="form-control form-control-sm">
                                </div>

                                <div
                                    class="form-group form-group-sm {{$errors->has('seo_descriptions')?'has-danger':''}}">
                                    <label class="control-label">
                                        Seo descriptions
                                    </label>
                                    <textarea class="form-control form-control-sm" name="seo_descriptions"
                                              rows="3">{!! old('seo_descriptions', $product->seo_descriptions) !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    @include('backend.products.mustache.variation')
    @include('backend.products.mustache.product_variation')
    @include('backend.products.modal.add_variation')
    @include('backend.products.modal.add_product_variation')

    <!-- Modal -->
    <div id="modal_variation_images" class="modal fade" role="dialog">
        <div class="modal-dialog flat">
            <div class="modal-content flat" id="modal_product_variation_image_content">

            </div>
        </div>
    </div>

@endsection

@section('style_top')
    <style>
        #form-add label {
            font-size: 13px;
        }

        .result {
            display: inline-block;
        }

        .thumb-nail-view {
            width: 145px;
            /*width: 50%;*/
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
            padding: 20px 0 !important;
        }

        .thumb-nail-view img {
            width: 100% !important;
            height: 150px !important;
            object-fit: contain !important;
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

        .option-value input {
            width: 100%;
            max-width: 100%;
        }

        .edit_variation_form .table td, .table th {
            vertical-align: middle;
        }

        .edit_variation_form [type=checkbox] + label {
            height: 22px;
            vertical-align: middle;
        }

        .img-bordered {
            border: solid 1px #ccc;
            cursor: pointer;
        }
    </style>
@stop

@section('script_top')
    <script
        src="{{ asset('/storage/backend')}}/assets/plugins/ckeditor/ckeditor.js?v={{config('constants.assets_version')}}"></script>
@stop

@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('/storage/backend')}}/js/variations.js?v={{config('constants.assets_version')}}"></script>

    <script>
        CKEDITOR.replace('detail');

        var BACKEND_URL = BASE_URL + '/admin';

        var uploadURL = "{{route('backend.products.index')}}";

        var URL_PRODUCT_VARIATION_DELETE = "{{route('backend.products.variation.delete')}}";
        var URL_PRODUCT_VARIATION_ADD = "{{route('backend.products.variation.add')}}";

        var URL_PRODUCT_VARIATION_IMAGES = "{{route('backend.products.variation.image')}}";
        var URL_PRODUCT_VARIATION_IMAGES_UPLOAD = "{{route('backend.products.variation.image.upload')}}";
        var URL_PRODUCT_VARIATION_IMAGES_DELETE = "{{route('backend.products.variation.image.delete')}}";
        var URL_PRODUCT_VARIATION_IMAGES_SORT = "{{route('backend.products.variation.image.sort')}}";

        var URL_VARIATION_VALUE = "{{route('backend.ajax.variation.value')}}";
        var URL_VARIATION_ADD = "{{route('backend.ajax.variation.create')}}";

        var VARIATION_LIST = {!! json_encode($variations, JSON_UNESCAPED_UNICODE) !!};

        $('#inventory_management').change(function () {
            var v = parseInt($(this).val());
            if (v == 1) {
                $('#warehouse_inventory').removeClass('d-none');
                $('#warehouse_inventory_policy').removeClass('d-none');
            } else {
                $('#warehouse_inventory').addClass('d-none');
                $('#warehouse_inventory_policy').addClass('d-none');
            }
        });

        function sort_image() {
            var ele = $("#myDropzone .result");

            ele.sortable({tolerance: 'pointer'});
            ele.sortable("enable");
        }

        sort_image();

        function sort_image_variation() {
            var ele = $(".product_variation_thumb_img");

            ele.sortable({tolerance: 'pointer'});
            ele.sortable("enable");
        }

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
                type:{{\App\Models\Files::TYPE_PRODUCT}}
                // thumb_sizes: '1200x800'
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
                sort_image();
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
                type:{{\App\Models\Files::TYPE_PRODUCT}}
                // thumb_sizes: '1200x800'
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
                '        <div class="checkbox-out">\n' +
                '           <input type="radio" id="cover_' + id + '" class="filled-in" name="thumbnail_id" value="' + id + '">\n' +
                '           <label for="cover_' + id + '">Cover</label>\n' +
                '        </div>\n' +
                '        <a href="javascript:;" onclick="removeFile($(this))" data-id="' + id + '">Xóa</a>\n' +
                '</div>')
        }
    </script>
@stop
