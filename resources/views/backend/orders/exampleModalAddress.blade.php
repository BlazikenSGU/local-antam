<div class="modal fade" id="exampleModalAddress" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" action="{{ route('backend.orders.changeAddress',$order->id ) }}" method="post" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-md-12" style="margin: auto">
                        @include('backend.partials.msg')
                        @include('backend.partials.errors')

                        {{ csrf_field() }}


                        <div class="form-group">
                            <label class="col-md-12 font-weight-bold">Tên shop<span class="text-danger">*</span></label>
                            <div class="col-md-12">

                                <input type="text"
                                       class="form-control form-control-line"
                                       name="fullname"
                                       value="@if(!empty($data)) {{$data->fullname}} @endif">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 font-weight-bold">Số điên thoại<span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <input type="text" name="phone"
                                       class="form-control form-control-line"
                                       value="@if(!empty($data)) {{$data->phone}} @endif">

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Tỉnh thành <span class="text-danger">*</span></label>
                                <select class="form-control form-control-line province1 js-example-basic-single"
                                        name="province_id"   value="" style="width: 100%">

                                </select>
                                <input type="text" hidden=""
                                       class="form-control form-control-line province_name1"
                                       value=""
                                       name="province_name1">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Quận huyện <span class="text-danger">*</span></label>
                                <select class="form-control form-control-line district1 js-example-basic-single"
                                        name="district_id" style="width: 100%" >
                                    <option value=""></option>
                                </select>
                                <input type="text" hidden=""
                                       class="form-control form-control-line district_name1"
                                       value=""
                                       name="district_name1">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Phường xã <span class="text-danger">*</span></label>
                                <select class="form-control form-control-line ward1 js-example-basic-single"
                                        name="ward_id" style="width: 100%" >
                                    <option value=""></option>
                                </select>
                                <input type="text" hidden=""
                                       class="form-control form-control-line ward_name1"
                                       value=""
                                       name="ward_name1">
                            </div>
                        </div>


                        {{--                                <div class="form-group">--}}
                        {{--                                    <label class="col-md-12">Địa chỉ</label>--}}
                        {{--                                    <div class="col-md-12">--}}
                        {{--                                        <input type="text" class="form-control form-control-line" name="address" value="@if(!empty($data)) {{$data->street_name}} @endif">--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        <div class="form-group">
                            <label class="col-md-12 font-weight-bold">Tên đường</label>
                            <div class="col-md-12">
                                <input type="text"
                                       class="form-control form-control-line"
                                       value="@if(!empty($data)) {{$data->street_name}} @endif"
                                       name="street_name">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>


        </div>
    </div>
</div>

