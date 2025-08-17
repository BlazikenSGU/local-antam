@extends('frontend.layouts.frontend')
@section('style')
    <style>
        .tree-view-com ul li {
            width: 100%;
            margin-top: 20px;
            position: relative;
            list-style: none;
        }

        .onClickShowChild {
            margin-left: 66px;
        }

        .first-user {
            margin-left: 0px;
        }

        .tree-view-com .tree-view-child > li:last-of-type {
            padding-bottom: 0px;
        }

        .tree-view-com ul li a .c-icon {
            margin-right: 10px;
            position: relative;
            top: 2px;
        }

        .tree-view-com ul > li > ul {
            margin-top: 20px;
            position: relative;
        }

        .tree-view-com > ul > li:before {
            border-left: 1px dashed #ccc;
            position: absolute;
            height: calc(100% - 30px - 5px);
            z-index: 1;
            left: 8px;
            top: 30px;
        }

        .tree-view-com > ul > li > ul > li:before {
            color: red;
            font-weight: bolder;
            content: "Cấp 2 - ";
            /*border-top: 1px dashed #ccc;*/
            position: absolute;
            width: 55px;
            left: 8px;
            /*top: 12px;*/
        }

        .tree-view-com > ul > li > ul > li > ul > li:before {
            color: red;
            font-weight: bolder;

            content: "Cấp 3 - ";
            /*border-top: 1px dashed #ccc;*/
            position: absolute;
            width: 55px;
            left: 8px;
            /*top: 12px;*/
        }

        .tree-view-com > ul > li > ul > li > ul > li > ul > li:before {
            color: red;
            font-weight: bolder;

            content: "Cấp 4 - ";
            /*border-top: 1px dashed #ccc;*/
            position: absolute;
            width: 55px;
            left: 8px;
            /*top: 12px;*/
        }

        .tree-view-com > ul > li > ul > li > ul > li > ul > li > ul > li:before {
            color: red;
            font-weight: bolder;

            content: "Cấp 5 - ";
            border-top: 1px dashed #ccc;
            position: absolute;
            width: 55px;
            left: 8px;
            /*top: 12px;*/
        }
        .tree-view-com > ul > li > ul > li > ul > li > ul > li > ul > li > ul > li:before {
            color: red;
            font-weight: bolder;

            content: "Cấp 6 - ";
            /*border-top: 1px dashed #ccc;*/
            position: absolute;
            width: 55px;
            left: 8px;
            /*top: 12px;*/
        }
        .tree-view-com > ul > li > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li:before {
            color: red;
            font-weight: bolder;

            content: "Cấp 7 - ";
            /*border-top: 1px dashed #ccc;*/
            position: absolute;
            width: 55px;
            left: 8px;
            /*top: 12px;*/
        }
        .tree-view-com > ul > li > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li:before {
            color: red;
            font-weight: bolder;

            content: "Cấp 8 - ";
            border-top: 1px dashed #ccc;
            position: absolute;
            width: 55px;
            left: 8px;
            /*top: 12px;*/
        }
        .tree-view-com > ul > li > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li > ul > li:before {
            color: red;
            font-weight: bolder;

            content: "Cấp 9 - ";
            /*border-top: 1px dashed #ccc;*/
            position: absolute;
            width: 55px;
            left: 8px;
            /*top: 12px;*/
        }
        .tree-view-com > ul > li > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li > ul > li > ul > li:before {
            color: red;
            font-weight: bolder;

            content: "Cấp 10 - ";
            border-top: 1px dashed #ccc;
            position: absolute;
            width: 55px;
            left: 8px;
            /*top: 12px;*/
        }
    </style>
@endsection
@section('content')
    @include('frontend.parts.breadcrumbs')

    <div class="my-account-wrapper section-padding">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- My Account Page Start -->
                        <div class="myaccount-page-wrapper pt-50 pb-80">

                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    @include('frontend.user.menu')


                                </div>
                                <div class="col-lg-9 col-md-8">
                                    @include('frontend.parts.errors')
                                    @include('frontend.parts.msg')
                                    <div class="row">
                                        <div class="">
                                            <div class="">
                                                {{--                                                    <menu id="nestable-menu" style="padding: 0">--}}
                                                {{--                                                        <a href=""--}}
                                                {{--                                                           class="btn flat btn-info">--}}
                                                {{--                                                            Thêm mới--}}
                                                {{--                                                        </a>--}}

                                                {{--                                                        <button type="button" class="btn flat" data-action="expand-all">--}}
                                                {{--                                                            Mở rộng tất cả--}}
                                                {{--                                                        </button>--}}
                                                {{--                                                        <button type="button" class="btn flat"--}}
                                                {{--                                                                data-action="collapse-all">Thu gọn tất cả--}}
                                                {{--                                                        </button>--}}
                                                {{--                                                    </menu>--}}

                                                <div class="tree-view-com">
                                                    <ul class="my-account-nav tree-view-parent nav">
                                                        @foreach($list_data as $k => $v)
                                                            <li class="">
                                                                <a class="onClickShowChild first-user"
                                                                   data-id="{{$v->id}}"><b style="color: red">Cấp 1
                                                                        - </b> {{$v->phone }}/{{$v->fullname?$v->fullname:''}}</a>
                                                                <ul class="child_{{$v->id}} tree-view-child"
                                                                    style="display:none">
                                                                    <li>a</li>
                                                                </ul>
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                    <input type="hidden" id="nestable-output">
                                                </div>

                                                {{--pagination--}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection
        @section('style_top')
            <link href="{{ asset('/storage/backend/')}}/assets/plugins/nestable/nestable.css"
                  rel="stylesheet" type="text/css"/>
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
        @section('style')
            <link href="{{ asset('/storage/backend/')}}/assets/plugins/nestable/nestable.css"
                  rel="stylesheet" type="text/css"/>
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
            <script
                src="{{ asset('/storage/backend/')}}/assets/plugins/nestable/jquery.nestable.js"></script>
            <script type="text/javascript">

                $(document).ready(function () {
                    $(document).on("click", ".onClickShowChild", function () {
                        var id = $(this).data("id");
                        $.ajax({
                            dataType: "json",
                            type: 'GET',
                            url: '',
                            data: {'id': id},
                            success: function (response) {
                                jQuery('.child_' + id).html('');
                                jQuery('.child_' + id).show();
                                $.each(response.data.html, function (index, value) {
                                    jQuery('.child_' + id).append(value);
                                });
                            }
                        });
                    });

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


                });

            </script>
@stop
