@extends('backend.layouts.main2222')

@section('content')
    <style>
        .row.page-titles {
            margin: 2rem 1rem;
        }

        .form_input {
            margin: .5rem 0;
        }

        .t-b {
            font-size: 1rem;
            font-weight: bold;
            color: #1b4e87;
        }
    </style>

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"></h3>
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal form-bordered" action="{{ route('backend.brands.store') }}" method="post">
                        @csrf
                        <div class="x">
                            <div class="form-group row">
                                <label class="t-b control-label text-left col-md-12" for="META_TITLE">
                                    Tên chi nhánh<span style="color: red"> (*)</span>
                                </label>
                                <div class="col-md-12 form_input">
                                    <input type="text" name="name" value="" class="form-control" id="name"
                                        placeholder="tên chi nhánh..">
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-md-12 t-b">Name_show</label>
                                <div class="col-md-12 form_input">
                                    <input type="text" class="form-control form-control-line" name="name_show" placeholder="tên hiển thị..."
                                        value="">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-12 t-b">ShopID</label>
                                <div class="col-md-12 form_input">
                                    <input type="text" class="form-control form-control-line" name="shopId" placeholder="mã shopId..."
                                        value="">
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-md-12 t-b">Token</label>
                                <div class="col-md-12 form_input">
                                    <input type="text" class="form-control form-control-line" name="token" placeholder="token..."
                                        value="">
                                </div>
                            </div>

                            <div class="form-group" id="from_weight">
                                <label class="col-md-12 t-b">From weight(kg)</label>
                                <div class="col-md-12 form_input">
                                    <input type="text" class="form-control form-control-line" name="from_weight" placeholder="từ trọng lượng(kg)..."
                                        value="">
                                </div>
                            </div>
                            <div class="form-group" id="to_weight">
                                <label class="col-md-12 t-b">To weight(kg)</label>
                                <div class="col-md-12 form_input">
                                    <input type="text" class="form-control form-control-line" name="to_weight" placeholder="đến trọng lượng(kg)..."
                                        value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12 t-b">Kiểu khách hàng</label>
                                <div class="col-md-12 form_input">

                                    <select name="type" id="" class="form-control form-control-line">
                                        <option value="">-Chọn-</option>
                                        <option value="1">Tạo đơn thủ công</option>
                                        <option value="2">Kênh bán hàng</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12 t-b">Dùng cho tạo đơn</label>
                                <div class="col-md-12 form_input">

                                    <select name="use_create_order" id="" class="form-control form-control-line">
                                        <option value="">-Chọn-</option>
                                        <option value="1">CÓ</option>
                                        <option value="0">Không</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 t-b">ServiceTypeId</label>
                                <div class="col-md-12 form_input">

                                    <select name="service_type_id" id="" class="form-control form-control-line">
                                        <option value="">-Chọn-</option>
                                        <option value="2">2</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">Thêm</button>

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
    <link href="{{ asset('/storage/backend') }}/assets/plugins/codemirror/lib/codemirror.css" rel="stylesheet">
    <link href="{{ asset('/storage/backend') }}/assets/plugins/codemirror/theme/monokai.css" rel="stylesheet">
@stop

@section('script')
    <script type="text/javascript">
        function sendFileToServer(formData, d) {

            $.ajax({
                url: "",
                type: "POST",
                contentType: false,
                processData: false,
                cache: false,
                data: formData,
                dataType: 'json',
                success: function(data) {
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


    <script>
        $(document).ready(function() {
            $('select[name="type"]').on('change', function() {
                var selectedType = $(this).val();
                if (selectedType == 2) {
                    $('#from_weight').hide();
                    $('#to_weight').hide();
                } else {
                    $('#from_weight').show();
                    $('#to_weight').show();
                }
            })
        })
    </script>
@stop
