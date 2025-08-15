
<?php $start = $start; ?>
@foreach($data_list as $key => $item)
        <?php $start = $start + 1; ?>
    <tr class="font-weight-bold text-dark">
        <td>
            <input class="form-check-input check-input " type="checkbox" value="{{ $item->id }}"
                   name="selected_items[]"
                   id="checkbox{{ $start }}" data-order-code="{{$item->order_code}}">
            <label for="checkbox{{ $start }}">{{ $start }}</label>
        </td>

        <td style="width: 50px">
            <a href="{{Route('backend.orders.edit',[$item->id]). '?_ref=' .$current_url }}"
               style="text-decoration: none">
                @if(!empty($item->order_code))
                    <span class="text-dark">{{$item->order_code}} </span>  <br>
                    <span
                        class=""
                        style="font-size: 12px;  color: #F26522"> {{ \App\Models\StatusName::where('key', $item->statusName)->first()->name }}</span>
                    @if(!empty($item->PartialReturnCode))
                        <p
                            class=""
                            style="font-size: 12px;" > Mã GH1P: {{$item->PartialReturnCode}}</p>
                    @endif
                @else
                    <span class="text-dark">Chưa có mã </span> <br>
                    <span class="" style="font-size: 12px; color: #F26522"> Đơn nháp</span>

                @endif
            </a>
        </td>

        <td style="max-width: 230px">
            <a href="{{Route('backend.orders.edit',[$item->id]). '?_ref=' .$current_url }}"
               style="text-decoration: none">
                @if(empty($item->to_name))
                    <span class="text-dark">Chưa có thông tin</span>
                @else
                    <span class="text-dark">{{$item->to_name}} - {{ $item->to_phone }}</span>
                    <p class="text-dark" style="font-size: 14px">{{$item->to_address}}
                        , {{$item->to_ward_name}}
                        , {{$item->to_district_name}} , {{$item->to_province_name}} </p>
                    <i class="text-dark" style="font-size: 12px">Ngày
                        tạo: {{ $item->created_at->format('d-m-Y') }}</i>
                @endif
            </a>

        </td>
        <td>
            {{--                        {{ \App\Utils\Common::FormatNumberVND($item->main_service) }}--}}
            {{ empty($item->main_service) ? '0 ' : $item->main_service }}


        </td>
        <td>
            <a href="{{Route('backend.orders.edit',[$item->id]). '?_ref=' .$current_url }}"
               style="text-decoration: none" class="text-dark">

                {{ empty($item->cod_amount) ? '0 ' : $item->cod_amount  }}
                {{--                            {{ \App\Utils\Common::FormatNumberVND($item->cod_amount) }}--}}

                <p class="text-danger">
                        <?php
                        $icheck = \App\Models\DoiSoat::where('OrderCode', $item->order_code)->first();
                        if ($icheck and $icheck->type == 2) {
                            echo 'Đã chuyển COD';
                        } else if ($icheck and $icheck->type == 1) {
                            echo 'Chưa chuyển COD';
                        } else {
                            echo '';
                        }
                        ?>
                    {{--                               {{ \App\Utils\Common::FormatNumberVND($item->cod_amount) }}--}}
                </p>
            </a>
        </td>

        <td>
            <a href="{{Route('backend.orders.edit',[$item->id]). '?_ref=' .$current_url }}"
               style="text-decoration: none" class="text-dark">

                {{--                            {{ empty($item->cod_failed_amount) ? '0 vnđ' :  number_format($item->cod_failed_amount) . ' vnđ' }}--}}
                {{ empty($item->cod_failed_amount) ? '0' :  $item->cod_failed_amount  }}
                {{--                            {{ \App\Utils\Common::FormatNumberVND($item->cod_failed_amount) }}--}}
                <p class="text-danger">
                    {{ $item->gtbThuTien }}
                    {{ \App\Utils\Common::FormatNumberVND($item->gtbThuTien) }}
                </p>
            </a>
        </td>
        {{--                    <td>--}}
        {{--                        <a href="{{Route('backend.orders.create',[$item->id]). '?_ref=' .$current_url }}"--}}
        {{--                           style="text-decoration: none">--}}
        {{--                            @if($item->payment_method == 1)--}}

        {{--                                <span class="" style="color: #F26522">Bên gủi trả phí</span> <br>--}}
        {{--                                <span class="text-dark"> Tổng phí:--}}

        {{--                                    {{  number_format(\App\Utils\Common::monyeConvert($item->main_service, $item->cod_amount)) }}--}}
        {{--                                 vnđ--}}


        {{--                                    @else</span>--}}
        {{--                                <span class="" style="color: #F26522">Bên nhận trả phí</span> <br>--}}
        {{--                                <span class="text-dark">Tổng phí:--}}
        {{--                                             {{  number_format(\App\Utils\Common::monyeConvert($item->main_service, $item->cod_amount)) }}--}}
        {{--                                 vnđ--}}
        {{--                                </span>--}}
        {{--                            @endif--}}
        {{--                        </a>--}}

        {{--                    </td>--}}
        <td style="width: 200px; " class="text-center">
            @if($item->statusName != 'cancel')
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{Route('backend.orders.create',[$item->id]). '?_ref=' .$current_url }}">
                                    <span class="btn btn-sm"
                                          style="margin: 5px; background-color: #196d1e; color: #fff">
                                        <i class="fa-solid fa-pen-to-square"></i> Chỉnh Sửa
                                    </span>
                        </a>
                    </div>
                        <?php $checkStatus = 'not_canncel_order';
                        if ($item->statusName === 'ready_to_pick' or $item->statusName === 'picking' or $item->statusName === 'money_collect_picking')
                            $checkStatus = 'canncel_order';
                        ?>
                    {{--                            <div class="col-md-6 draft-btn-cancel" >--}}
                    {{--                                <span class="{{$checkStatus}}" data-id="{{$item->id}}"--}}
                    {{--                                      data-shopId="{{$item->product_type}}">--}}
                    {{--                                    <span class="btn btn-sm"--}}
                    {{--                                          style="margin: 5px;background-color: #196d1e; color: #fff ">--}}
                    {{--                                       <i class="fa-solid fa-handshake-angle"></i> Hủy đơn--}}
                    {{--                                    </span>--}}
                    {{--                                </span>--}}
                    {{--                            </div>--}}

                    <div class="col-md-6">
                        <a href="https://donhang.ghn.vn/?order_code={{$item->order_code}}" target="_blank">
                                     <span class="">
                                    <span class="btn btn-sm"
                                          style="margin: 5px; background-color: #196d1e; color: #fff">
                                       <i class="fa-solid fa-magnifying-glass"></i> Tra cứu
                                    </span>
                                    </span>
                        </a>
                    </div>
                    @if($item->statusName == 'storing' or $item->statusName == 'waiting_to_return')
                        <div class="col-md-6">
                            <a href="{{ route('backend.orders.return', $item->id) }}" class="comfim_return">
                                     <span class="">
                                    <span class="btn btn-sm"
                                          style="margin: 5px; background-color: #e02dee; color: #fff">
                                         Hoàn hàng
                                    </span>
                                    </span>
                            </a>
                        </div>
                    @endif
                    @if($item->statusName == 'waiting_to_return')
                        <div class="col-md-6">
                            <a href="{{ route('backend.orders.storing', $item->id) }}" class="comfim_storing">
                                     <span class="">
                                    <span class="btn btn-sm"
                                          style="margin: 5px; background-color: #4d28f5; color: #fff">
                                         Giao Lại
                                    </span>
                                    </span>
                            </a>
                        </div>
                    @endif

                    <div class="col-md-6">
                        @if($item->status == 1)
                            <a href="{{ route('backend.orders.delete', $item->id) }}">
                                         <span class="">
                                        <span class="btn btn-sm"
                                              style="margin: 5px; background-color: #ef2f40; color: #fff">
                                           <i class=""></i> Xóa đơn
                                        </span>
                                        </span>
                            </a>
                        @else

                            {{--                                    <span class="btn-history-order" data-id="{{ $item->id}}">--}}
                            {{--                                        <span class="btn btn-sm"--}}
                            {{--                                              style="margin: 5px; background-color: #ef2f40; color: #fff">--}}
                            {{--                                           <i class=""></i> Lịch sử--}}
                            {{--                                        </span>--}}
                            {{--                                    </span>--}}

                        @endif
                    </div>
                </div>
            @endif

        </td>
    </tr>
@endforeach
