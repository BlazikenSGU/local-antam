@extends('backend.layouts.main')

@section('content')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-dark" style="border-bottom: 1px solid #000; display: inline-block">khiếu nại</h3>
        </div>
        {{--        <div class="col-md-7 align-self-center">--}}
        {{--            {{ Breadcrumbs::render('backend.orders.index') }}--}}
        {{--        </div>--}}
    </div>


        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 pull-right">
                            <span  class="btn waves-effect waves-light btn-block btn-info btn-add-ticker">
                                <i class="fa fa-plus"></i>&nbsp;&nbsp;Tạo yêu cầu
                            </span>
                        </div>
                    </div>
                    <br>
                    <form action="" method="get" id="form-filter">
                        <div class="form-body">
                            <div class="row p-t-20">


                                <div class="col-md-2">
                                    <div class="form-group ">
                                        <select class="form-control form-control-sm js-example-basic-single"
                                                name="limit">
                                            <option value=""
                                                >Tất cả
                                            </option>
                                            <option value="1">Hòan thành</option>
                                            <option value="1">Chờ xử lý</option>
                                            <option value="1">Chờ bạn phản hổi</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group form-group-sm">
                                        <input type="text"
                                               name="working_date_from"
                                               value=""
                                               id="working_date_from" readonly
                                               class="form-control form-control-sm date_time_select" placeholder="Từ ngày">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-group-sm">
                                        <!--                    <label class="control-label" for="working_date_to">Đến ngày</label>-->
                                        <input type="text"
                                               name="working_date_to"
                                               value=""
                                               id="working_date_to" readonly
                                               class="form-control form-control-sm date_time_select" placeholder="Đến ngày">
                                    </div>
                                </div>


                                <div class="col-md-1">
                                    <button type="submit" class="btn waves-effect waves-light btn-block btn-info btn-sm">
                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;Tìm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>


                    <div class="row">
                        <div class="col-md-12">
                            @include('backend.partials.msg')
                            @include('backend.partials.errors')
                            <div class="table-responsive">
                                <table class="table color-table muted-table table-striped">
                                    <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã yêu cầu</th>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày tạo</th>
                                        <th>Nội dung</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $k=>$item)
                                        <tr>
                                            <td>{{ $k+=1 }}</td>
                                            <td>{{ $item->ticker_id }} <br>
                                                <span class="">{{ $item->status }}</span>
                                            </td>
                                            <td>{{ $item->order_code }}</td>
                                            <td>{{ $item->created_at->format('d-m-Y') }}
                                            </td>

                                            <td>{{ $item->description }}</td>
                                        </tr>

                                    @endforeach



                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Gửi yêu cầu hỗ trợ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('backend.shop.ticket.create') }}" method="post">
                    @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-12">Mã đơn hàng</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control form-control-line" name="order_code"
                                    >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Loại yêu cầu</label>
                        <div class="col-md-12">
                            <select class="form-control" name="category">
                                <option value="1" {!! old('type')==1?'selected="selected"':'' !!}>
                                    Tư vấn
                                </option>
                                <option value="2" {!! old('type')==1?'selected="selected"':'' !!}>
                                    Hối Giao/Lấy/Trả hàng
                                </option>
                                <option value="3" {!! old('type')==1?'selected="selected"':'' !!}>
                                    Thay đổi thông tin
                                </option>
                                <option value="4" {!! old('type')==1?'selected="selected"':'' !!}>
                                    Khiếu nại
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nội dung</label>
                        <div class="col-md-12">
                            <textarea type="text" class="form-control form-control-line" name="description"
                                   rows="4">  </textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Tạo</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        $(".btn-add-ticker").click(function(){
            $('#exampleModal').modal('show');

        });
        $(".btn-close").click(function(){
            $('#exampleModal').modal('hide');

        });

    </script>
@endsection
