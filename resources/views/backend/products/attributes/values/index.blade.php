@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.products.attributes.values.index') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 offset-2">
                            <menu id="nestable-menu" style="padding: 0">
                                @if(auth()->guard('backend')->user()->can('products.attributes.values.add')||1)
                                    <a href="{{Route('backend.products.attributes.values.add', $variation_id)}}" class="btn flat btn-info">
                                        Thêm mới
                                    </a>
                                @endif
                            </menu>

                            <div id="load"></div>
                            <div class="myadmin-dd dd" id="nestable">
                                <ol class='dd-list' id='menu-id'>
                                    @foreach($list_data as $v)
                                        <li class='dd-item' data-id='{{$v->id}}'>
                                            <div class='dd-handle'>{{$v->id}}. {{$v->value}}</div>
                                            <div class='ddmenu-right button-group'>

                                                <a href='{{route('backend.products.attributes.values.edit',[$v->variation_id,$v->id])}}' class='btn btn-xs btn-outline-info'>
                                                    <i class='fa fa-pencil'></i>
                                                </a>

                                                <a href='javascript:;' class='del-button btn btn-xs btn-outline-danger' data-id='{{$v->id}}'>
                                                    <i class='fa fa-trash'></i>
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>

                            <input type="hidden" id="nestable-output">

                            <div class="text-center">
                                <a href="{{route('backend.products.attributes.index')}}" class="btn btn-info btn-flat">
                                    <i class="fa fa-reply"></i> &nbsp; Quay lại
                                </a>

                                <button type="button" id="save"
                                        class="btn btn-primary btn-flat d-none"><i class="fa fa-save"></i> &nbsp; Lưu
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('style_top')
    <link href="{{ asset('/storage/backend/')}}/assets/plugins/nestable/nestable.css" rel="stylesheet" type="text/css"/>
    <style>
        .ddmenu-right {
            float: right;
            display: inline-block;
            position: absolute;
            right: 0;
            top: 6px;
        }

        .dd {
            max-width: 100%;
        }

        .dd-handle {
            color: #000000;
        }

        #load {
            position: fixed;
            z-index: 99;
            top: 0;
            left: 0;
            overflow: hidden;
            text-indent: 100%;
            font-size: 0;
            opacity: 0.6;
            height: 100%;
            width: 100%;
            background: #E0E0E0;
        }
    </style>
@stop

@section('script')
    <script src="{{ asset('/storage/backend/')}}/assets/plugins/nestable/jquery.nestable.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            if (jQuery('#nestable').length > 0) {
                //nestable
                var updateOutput = function (e) {
                    var list = e.length ? e : $(e.target),
                        output = list.data('output');
                    if (window.JSON) {
                        var d = window.JSON.stringify(list.nestable('serialize'));
                        console.log(d);
                        output.val(d);
                    } else {
                        output.val('JSON browser support required for this demo.');
                    }
                };

                // activate Nestable for list 1
                jQuery('#nestable').nestable({group: 1}).on('change', updateOutput);

                // output initial serialised data
                updateOutput(jQuery('#nestable').data('output', jQuery('#nestable-output')));

                jQuery('#nestable-menu').on('click', function (e) {
                    var target = jQuery(e.target),
                        action = target.data('action');
                    if (action === 'expand-all') {
                        jQuery('.dd').nestable('expandAll');
                    }
                    if (action === 'collapse-all') {
                        jQuery('.dd').nestable('collapseAll');
                    }
                });
                jQuery('.dd').nestable('collapseAll');
            }

            $("#load").hide();
            $('.dd').on('change', function () {
                //sort_ajax();
            });

            //save type sort
            $("#save").click(function () {
                sort_ajax();
            });

            function sort_ajax() {
                $("#load").show();

                var dataString = {
                    data: $("#nestable-output").val(),
                };

                var _token = $('meta[name="csrf-token"]').attr('content');
                dataString['_token'] = _token;

                $.ajax({
                    type: "POST",
                    url: "{{route('backend.products.attributes.values.sort',$variation_id)}}",
                    data: dataString,
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        if (data.e == 0) {
                            $("#load").hide();
                            $('meta[name="csrf-token"]').attr('content', data._token);
                            alert('Đã lưu thành công!');
                        } else {
                            alert(data.r);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('Có lỗi xảy ra, vui lòng thử lại!');
                    },
                });
            }

            $(document).on("click", ".del-button", function () {
                var x = confirm('Bạn muốn có xóa giá trị thuộc tính này?\nTất cả biến thể sản phẩm có giá trị thuộc tính này sẽ bị xóa và không thể khôi phục. ');
                var id = $(this).data('id');
                if (x) {
                    $("#load").show();
                    var _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: "POST",
                        url: "{{route('backend.products.attributes.values.del',$variation_id)}}",
                        data: {id: id, _token: _token},
                        cache: false,
                        dataType: 'json',
                        success: function (data) {
                            if (data.e == 0) {
                                $("#load").hide();
                                $("li[data-id='" + id + "']").remove();
                                $('meta[name="csrf-token"]').attr('content', data._token);
                            } else {
                                alert(data.r);
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('Có lỗi xảy ra, vui lòng thử lại!');
                        },
                    });
                }
            });
        });

    </script>
@stop
