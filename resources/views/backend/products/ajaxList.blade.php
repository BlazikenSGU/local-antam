<div class="table-responsive">
    <table class="table table-striped table-hover" id="grid_tablex">
        <thead>
            <tr>
                <th>#</th>
                <th style="width: 20px;"><input type="checkbox" class="checkbox-basic check-all"></th>
                <th class="text-center">Hình</th>
                <th>Tên sản phẩm</th>
                <th style="min-width: 120px">Giá &nbsp;&nbsp;
                    <a href="" title="Tăng dần" data-sort="price_low_high"
                       class="sort {{$sort=='price_low_high'?'active':''}}"><i class="fa fa-long-arrow-up"></i></a>
                    <a href="" title="Giảm dần" data-sort="price_high_low"
                       class="sort {{$sort=='price_high_low'?'active':''}}"><i class="fa fa-long-arrow-down"></i></a>
                </th>
                <th style="min-width: 105px">Loại</th>
                <th style="min-width: 105px">Trạng thái</th>
                <th style="min-width: 160px">Ngày tạo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $key => $product)

                @php
                    $link = product_link($product->slug,$product->id,$product->product_type_id);
                @endphp

                <tr>
                    <td>{{++$start}}</td>
                    <td>
                        <input type="checkbox" name="ids[]" value="{{$product->id}}" id="{{$product->id}}"
                               class="checkbox-basic check-all-child">
                    </td>

                    <td class="text-center">
                        @if($product->thumbnail)
                            <img src="{{$product->thumbnail->file_src}}" height="40">
                        @endif
                    </td>
                    <td>
                        @if(auth()->guard('backend')->user()->can('products.edit'))
                            <a href="{{route('backend.products.edit',[$product->type,$product->id])}}">{{$product->title}}</a>
                        @else
                            {{$product->title}}
                        @endif
                        <br>
                        <small>ID: {{$product->id}} | <span> <a href="{{$link}}" target="_blank">Xem</a></span></small>
                    </td>

                    <td>{{number_format($product->price)}}</td>
                    <td>{{$product->type != 1 ? 'Đồ ăn vặt' : 'Sản phẩm'}}</td>

                    <td>
                        @foreach($status as $st)
                            @if($st['id']==$product->status)
                                {{$st['name']}}
                                @break
                            @endif
                        @endforeach
                    </td>

                    <td>{{date('H:i d/m/Y',strtotime($product->created_at))}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">-</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 10px">
    <label> Chọn tất cả <input type="checkbox" class="checkbox-basic check-all"></label>

    <button type="button" class="btn waves-effect waves-light btn-xs btn-success"
            id="approved_btn">Hiển thị
    </button>

    <button type="button" class="btn waves-effect waves-light btn-xs btn-warning"
            id="un_approved_btn">Ẩn
    </button>

    @if(auth()->guard('backend')->user()->can('products.delete'))
        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                id="delete_btn">Xóa
        </button>
    @endif
</div>

<br>
<div class="text-center">
    {{ $products->links() }}
</div>
