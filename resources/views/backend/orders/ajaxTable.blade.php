{{-- <form action="" method="get" id="form-filter">
    <div class="form-body">
        <div class="row">
            <div class="col-md-12">
                @include('backend.partials.msg')
                @include('backend.partials.errors')
            </div>
        </div>
    </div>
</form> --}}


<form action="" method="GET">
    <div class="table-responsive ajax-result">
        <table class="table table-striped table-hover" style="overflow: hidden">
            <thead style="background-color: #00467F; color: #fff">
            <tr class="font-weight-bold">
                <th>
                    <div class="">
                        <input class="form-check-input" type="checkbox" value="" id="selectAllCheckbox">
                        <label class="form-check-label" for="selectAllCheckbox">
                            STT
                        </label>
                    </div>
                </th>
                <th style="width: 130px">Mã đơn hàng</th>
                <th>Bên nhận</th>
                <th>Tùy chọn thanh toán</th>
                <th>Tổng dịch vụ</th>
                <th>Thu hộ COD</th>
                <th style="max-width: 130px">GH thất bại/ Thu tiền</th>
                <th class="text-center"></th>
            </tr>
            </thead>
            <tbody id="data-load-table">
            @foreach($data_list as $key => $item)
                <tr class="font-weight-bold text-dark">
                    <td>
                        <input class="form-check-input check-input " type="checkbox" value="{{ $item->id }}"
                               name="selected_items[]"
                               id="checkbox{{ $key }}" data-order-code="{{$item->order_code}}">
                        <label for="checkbox{{ $key }}">{{ $key + 1 }}</label>
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
                    <td  style="max-width: 230px">
                        {{ $item->payment_method == 2 ? 'Bên nhận trả phí' : 'Bên gủi trả phí' }}
                    </td>
                    <td>                      
                            <?php
                            $main_service_str = (string) $item->main_service;

                          
                            $parts = explode('.', $main_service_str);
                            $decimal_part = isset($parts[1]) ? $parts[1] : '';
                            ?>
                        @if (strlen($decimal_part) === 1)
                            {{number_format((int) floor($item->main_service) + 1, 0, ',', '.')}}
                        @else
                            {{ number_format((int) str_replace(['.', ','], '', $item->main_service),0, ',', '.')  }}
                        @endif

                    </td>
                    <td>
                        <a href="{{Route('backend.orders.edit',[$item->id]). '?_ref=' .$current_url }}"
                           style="text-decoration: none" class="text-dark">

                            {{ empty($item->cod_amount) ? '0 ' : number_format((int) str_replace(['.', ','], '', $item->cod_amount),0, ',', '.')  }}

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
                           </p>
                        </a>
                    </td>

                    <td>
                        <a href="{{Route('backend.orders.edit',[$item->id]). '?_ref=' .$current_url }}"
                           style="text-decoration: none" class="text-dark">

                            {{ empty($item->cod_failed_amount) ? '0' :   number_format((int) str_replace(['.', ','], '', $item->cod_failed_amount),0, ',', '.')  }}


                            <p class="text-danger">
                                {{ $item->gtbThuTien }}
                            </p>
                        </a>
                    </td>

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

                                @endif
                            </div>
                        </div>
                        @endif

                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>


</form>
<div class="text-center ajax-pagination">
{{--    {{ $data_list->links() }}--}}
    <button class="btn btn-primary btnloadMore" type="button" onclick="loadMore()" data-page="{{ $data_list->currentPage() }}">Xem thêm</button>
</div>
<style>
    tr {
        font-size: 14px !important;
    }

    .btn-sm {
        width: 75px !important;
        font-size: 10px;
    }
</style>
