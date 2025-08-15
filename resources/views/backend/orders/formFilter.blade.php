<form action="" method="get" id="form-filter">
    <div class="form-body">
        <div class="row p-t-20">

{{--            <div class="col-md-1">--}}
{{--                <div class="form-group form-group-sm">--}}
{{--                    <input type="text"--}}
{{--                           name="order_code"--}}
{{--                           value="{{request('order_code')}}"--}}
{{--                           id="order_code"--}}
{{--                           class="form-control form-control-sm" placeholder="Mã DH">--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="col-md-2">--}}
{{--                <div class="form-group form-group-sm">--}}
{{--                    <input type="text"--}}
{{--                           name="fullname"--}}
{{--                           value="{{request('fullname')}}"--}}
{{--                           id="fullname"--}}
{{--                           class="form-control form-control-sm" placeholder="Họ tên">--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="col-md-2">--}}
{{--                <div class="form-group form-group-sm">--}}
{{--                    <input type="email"--}}
{{--                           name="email"--}}
{{--                           value="{{request('email')}}"--}}
{{--                           id="email"--}}
{{--                           class="form-control form-control-sm" placeholder="Email">--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="col-md-2">--}}
{{--                <div class="form-group form-group-sm">--}}
{{--                    <input type="text"--}}
{{--                           name="phone"--}}
{{--                           value="{{request('phone')}}"--}}
{{--                           id="phone"--}}
{{--                           class="form-control form-control-sm" placeholder="Điện thoại">--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="mb-4 buttomwrap" style="">
                @foreach($status as $k=>$v)
                    <?php
                        $link = url('admin/orders?key=').$k;
                        ?>
                    <a href="{{$link}}">
                        <button type="button" class="btn btn-tag order-draft "  @if(request('key') == $k ) style=" background: bisque !important;" @endif>{{$v}}
                            <span>
                            {{ $numberOrderStatus = \App\Models\Orders::NumberOrderStatus($k) ?? 0 }}
                            </span>
                        </button>
                    </a>
                @endforeach
            </div>

            <style>

                .buttomwrap {
                    max-width: 100%;
                    overflow: auto;
                    display: flex;
                    padding: 15px;
                }
                .buttomwrap::-webkit-scrollbar {
                    /* Tùy chỉnh thanh cuộn (nếu cần) */
                    width: 5px; /* Độ rộng của thanh cuộn */
                    height: 5px;
                    margin-top: 10px;
                    margin-bottom: 10px;

                }

                .buttomwrap::-webkit-scrollbar-thumb {
                    /* Tùy chỉnh đối tượng cuộn */

                    background-color: gray; /* Màu sắc của thanh cuộn */
                    border-radius: 10px; /* Độ cong của thanh cuộn */
                }

                .btn-tag {

                    background: #f0f0f0;
                    height: 40px !important;
                    border-radius: 16px !important;
                    border: none !important;
                    font-size: 15px;
                    margin-right: 6px;
                }
            </style>
{{--            <div class="col-md-4">--}}
{{--                <div class="form-group">--}}
{{--                    <select class="form-control form-control-sm js-example-basic-single"--}}
{{--                            name="status">--}}
{{--                        <option value="">Trạng thái</option>--}}
{{--                        @foreach($status as $k=>$v)--}}
{{--                            <option value="{{$k}}"--}}
{{--                                {!! request('status')===$k?'selected="selected"':'' !!}>{{$v}}--}}
{{--                            </option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="col-md-2">--}}
{{--                <div class="form-group ">--}}
{{--                    <select class="form-control form-control-sm js-example-basic-single"--}}
{{--                            name="limit">--}}
{{--                        @foreach($_limits as $st)--}}
{{--                            <option value="{{$st}}"--}}
{{--                                {!! $filter['limit']==$st?'selected="selected"':'' !!}>{{number_format($st)}} record--}}
{{--                            </option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}

            <!--Ca - 20-01-22 them phan loc shipper-->
{{--            <div class="col-md-2">--}}
{{--                <div class="form-group form-group-sm">--}}
{{--                    <select class="form-control form-control-sm" name="user_change_status">--}}
{{--                        <option value="{{0}}">Chọn Nhân viên </option>--}}
{{--                        @foreach($dataShipper as $k=> $v)--}}
{{--                            @if($v->user_category_id == 91)--}}
{{--                            <option  value="{{$v->id}}"--}}
{{--                                {{$v->id==0?"selected":""}}--}}
{{--                            >{{$v->fullname}} --> Khách hàng </option>--}}
{{--                            @elseif($v->user_category_id != 91)--}}
{{--                                <option value="{{$v->id}}"--}}
{{--                                    {{$v->id==0?"selected":""}}--}}
{{--                                >{{$v->fullname}}  </option>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}
            <!-- Ca - 12-12-22 thêm bô lọc chi nhanh -->
{{--            <div class="col-md-2">--}}
{{--                <div class="form-group form-group-sm">--}}
{{--                    <select class="form-control form-control-sm" name="change_branch">--}}
{{--                        <option value="{{0}}">Chọn Chi nhánh </option>--}}
{{--                        @foreach($dataBranch as $k=> $v)--}}
{{--                            <option value="{{$v->id}}"--}}
{{--                                {{$v->id==0?"selected":""}}--}}
{{--                            >{{$v->name}}  </option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}
            <!-- Ca - 12-12-22 thêm bô lọc chi nhanh -->




        </div>
        <div class="row fillter_desktop">
            <div class="col-md-2 col-6">
                <div class="form-group form-group-sm">
                    <!--                    <label class="control-label" for="working_date_to">Từ ngày</label>-->
                    <input type="text"
                           name="working_date_from"
                           value="{{$filter['working_date_from']}}"
                           id="working_date_from" readonly
                           class="form-control form-control-sm date_time_select" placeholder="Từ ngày">
                </div>
            </div>

            <div class="col-md-2 col-6" >
                <div class="form-group form-group-sm">
                    <!--                    <label class="control-label" for="working_date_to">Đến ngày</label>-->
                    <input type="text"
                           name="working_date_to"
                           value="{{$filter['working_date_to']}}"
                           id="working_date_to" readonly
                           class="form-control form-control-sm date_time_select" placeholder="Đến ngày">
                </div>
            </div>
            <!--Ca - 20-01-22 them phan loc shipper-->
            <!--Long - 22-12-22 them phan loc đơn đặt-->
            {{--            <div class="col-md-2">--}}
            {{--                <div class="form-group form-group-sm">--}}
            {{--                    <select class="form-control form-control-sm" name="type_order">--}}
            {{--                        <option value="0">Chọn loại đơn đặt </option>--}}
            {{--                        <option value="1">Đặt trên web </option>--}}
            {{--                        <option value="2">Đặt trên app </option>--}}

            {{--                    </select>--}}
            {{--                </div>--}}
            {{--            </div>--}}


            <div class="col-md-1">
                <button type="submit" class="btn waves-effect waves-light btn-block btn-sm" style="background-color: #00467F; color: #fff">
                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Lọc
                </button>
            </div>
        </div>
    </div>
</form>
