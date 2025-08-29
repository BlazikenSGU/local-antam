@extends('backend.layouts.admin')

@section('title', 'Cập nhật shopId')
@section('page_title', 'Cập nhật shopId')

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

    <div class="container-fluid mt-4">

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal form-bordered" action="{{ Route('backend.brands.update', $branch->id) }}"
                        method="post">
                        @csrf
                        <div class="x">

                            <div class="form-group row">
                                <label class="control-label text-left col-md-12 t-b" for="META_TITLE">
                                    Tên chi nhánh<span style="color: red"> (*)</span>
                                </label>
                                <div class="col-md-12 form_input">
                                    <input type="text" name="name" value="{{ $branch->name }}" class="form-control"
                                        id="name" placeholder="Vui lòng nhập tên chính nhánh">
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-md-12 t-b">Name_show</label>
                                <div class="col-md-12 form_input">
                                    <input type="text" class="form-control form-control-line" name="name_show"
                                        value="{{ $branch->name_show }}">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-12 t-b">ShopID</label>
                                <div class="col-md-12 form_input">
                                    <input type="number" class="form-control form-control-line" name="shopId"
                                        value="{{ $branch->shopId }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12 t-b">Phân loại</label>
                                <div class="col-md-12 form_input">
                                    <select name="type" id="" class="form-control form-control-line">
                                        <option value=""
                                            {{ isset($branch->type) && $branch->type > 0 ? 'disabled' : '' }}>-Chọn-
                                        </option>
                                        <option value="1" {{ $branch->type == 1 ? 'selected' : '' }}>Tạo đơn thủ công
                                        </option>
                                        <option value="2" {{ $branch->type == 2 ? 'selected' : '' }}>Kênh bán hàng
                                        </option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-12 t-b">Token</label>
                                <div class="col-md-12 form_input">
                                    <input type="text" class="form-control form-control-line" name="token"
                                        value="{{ $branch->token }}">
                                </div>
                            </div>

                            <div class="form-group " id="from_weight">
                                <label class="col-md-12 t-b">From weight</label>
                                <div class="col-md-12 form_input">
                                    <input type="number" class="form-control form-control-line" name="from_weight"
                                        value="{{ $branch->from_weight }}">
                                </div>
                            </div>
                            <div class="form-group " id="to_weight">
                                <label class="col-md-12 t-b">To weight</label>
                                <div class="col-md-12 form_input">
                                    <input type="number" class="form-control form-control-line" name="to_weight"
                                        value="{{ $branch->to_weight }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12 t-b">Dùng tạo đơn</label>
                                <div class="col-md-12 form_input">
                                    <select name="use_create_order" id="" class="form-control form-control-line">
                                        <option value="" {{ !is_null($branch->use_create_order) ? 'disabled' : '' }}>
                                            -Chọn-
                                        </option>
                                        <option value="1" {{ $branch->use_create_order == 1 ? 'selected' : '' }}>Có
                                        </option>
                                        <option value="0" {{ $branch->use_create_order === 0 ? 'selected' : '' }}>
                                            Không
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12 t-b">ServiceTypeId</label>
                                <div class="col-md-12 form_input">
                                    <select name="service_type_id" id="" class="form-control form-control-line">
                                        <option value=""
                                            {{ isset($branch->service_type_id) && $branch->service_type_id > 0 ? 'disabled' : '' }}>
                                            -Chọn-
                                        </option>
                                        <option value="2" {{ $branch->service_type_id == 2 ? 'selected' : '' }}>2
                                        </option>
                                        <option value="5" {{ $branch->service_type_id == 5 ? 'selected' : '' }}>5
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <div class="form-group">
                                    <button class="btn btn-info" type="submit">Cập nhật</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
@section('script')
    <script>
        $(document).ready(function() {
            function toggleWeightFields() {
                var selectedType = $('select[name="type"]').val();
                if (selectedType == 2) {
                    $('#from_weight').hide();
                    $('#to_weight').hide();
                } else {
                    $('#from_weight').show();
                    $('#to_weight').show();
                }
            }

            // Gọi khi load trang để kiểm tra giá trị hiện tại
            toggleWeightFields();

            // Gọi lại khi người dùng thay đổi lựa chọn
            $('select[name="type"]').on('change', function() {
                toggleWeightFields();
            });
        });
    </script>
@endsection

@endsection
