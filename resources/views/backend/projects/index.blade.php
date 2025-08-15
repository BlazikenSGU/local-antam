@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.projects.index') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    @if(auth()->guard('backend')->user()->can('projects.add'))
                        <div class="row">
                            <div class="col-md-2 pull-right">
                                <a href="{{Route('backend.projects.add')}}"
                                   class="btn waves-effect waves-light btn-block btn-info">
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Thêm dự án mới
                                </a>
                            </div>
                        </div>
                    @endif

                    @include('backend.projects.formFilter')

                    <div class="ajax-result">
                        @include('backend.projects.ajaxList')
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('style_top')
    <link href="{{ asset('/storage/backend/assets/plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css"/>

    <style>
        .select2 {
            width: 100% !important;
            height: 36px !important;
        }

        .checkbox-basic {
            position: initial !important;
            left: initial !important;
            opacity: 1 !important;
        }

        @media (min-width: 576px) {
            #note-modal .modal-dialog {
                max-width: 1000px;
                margin: 1.75rem auto;
            }
        }
    </style>
@stop

@section('script')
    <script src="{{ asset('/storage/backend/assets/plugins/dropzone-master/dist/dropzone.js')}}" type="text/javascript"></script>
    <script src="{{ asset('/storage/backend/assets/plugins/bootstrap-select/bootstrap-select.min.js')}}" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>

        $(document).on('click', 'ul.pagination li a', function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            load_product(url);

        });

        $(document).on('click', 'a.sort', function (event) {
            event.preventDefault();
            var sort = $(this).data('sort');
            $('#form-filter .input_sort').val(sort);
            var data = $('#form-filter').serialize();
            var url = $('#form-filter').attr('action');
            load_product(url, data)
        });

        function load_product(url, params) {
            var params = params !== undefined ? params : null;
            $.ajax({
                url: url,
                type: 'get',
                data: params,
                success: function (data) {
                    if (data.e == 0) {
                        $('div.ajax-result').html(data.r);
                        $('html, body').animate({scrollTop: 0}, 0);
                    }

                }
            });
        }

        $('#form-filter').on('change', 'select', function () {
            var data = $('#form-filter').serialize();
            var url = $('#form-filter').attr('action');
            load_product(url, data)
        });

        var timeoutID = null;
        $('#form-filter').on('keyup', 'input', function () {
            var data = $('#form-filter').serialize();
            var url = $('#form-filter').attr('action');
            clearTimeout(timeoutID);

            timeoutID = setTimeout(function () {
                load_product(url, data)
            }, 500);
        });

        $(document).on('click', '#delete_btn', function (event) {
            var x = getChecked();
            if (!x)
                return;
            var r = confirm("Xóa các dự án đã chọn?");
            if (r == true) {
                $.ajax({
                    url: '{{route('backend.projects.ajax.delete')}}',
                    type: 'post',
                    data: {project_ids: val, _token: '{{csrf_token()}}'},
                    success: function (data) {
                        alert('Đã xóa thành công!');
                        window.location.reload();
                    }
                });
            }
        });
    </script>
@stop