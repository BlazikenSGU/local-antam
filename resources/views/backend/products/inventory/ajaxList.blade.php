<div class="table-responsive">
    <table class="table table-striped table-hover" id="grid_tablex">
        <thead>
            <tr>
                <th>#</th>
                <th class="text-center">Hình</th>
                <th style="width: 300px">Tên sản phẩm</th>
                <th>Mã sản phẩm</th>
                <th>Giá &nbsp;&nbsp;
                    <a href="" title="Tăng dần" data-sort="price_low_high" class="sort {{$sort=='price_low_high'?'active':''}}"><i class="fa fa-long-arrow-up"></i></a>
                    <a href="" title="Giảm dần" data-sort="price_high_low" class="sort {{$sort=='price_high_low'?'active':''}}"><i class="fa fa-long-arrow-down"></i></a>
                </th>
                <th>Kho</th>
                <th>Hết hàng</th>
                <th>Cập nhật số lượng</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $key => $product)
                <tr>
                    <td>{{++$start}}</td>

                    <td class="text-center">
                        @if($product->thumbnail)
                            <img src="{{$product->thumbnail->file_src}}" height="40">
                        @else
                            <img src="{{\App\Utils\Links::ImageLink('',true)}}" height="40">
                        @endif
                    </td>
                    <td>
                        @if(auth()->guard('backend')->user()->can('products.edit'))
                            <a href="{{route('backend.products.edit',[$product->id])}}">{{$product->title}}</a>
                        @else
                            {{$product->title}}
                        @endif

                        {!! $product->product_variation_name?'<br>'.$product->product_variation_name:'' !!}
                        <br>
                        <small>ID: {{$product->id}} | <span> <a href="#">Xem</a></span></small>
                    </td>

                    <?php if ($product->product_variation_id) {
                    $product->product_variation_price = $product->product_variation_price ? $product->product_variation_price : $product->price
                    ?>
                    <td><?php echo $product->product_variation_product_code; ?></td>
                    <td class="price"><?php echo number_format($product->product_variation_price); ?></td>
                    <td class="type--centered type--no-wrap" context="inventory">
                        <span class="inventory-quantity <?php echo $product->product_variation_inventory < 10 ? 'warning' : '' ?>" data-value="<?php echo floatval($product->product_variation_inventory); ?>"><?php echo number_format($product->product_variation_inventory); ?></span>
                        <span class="inventory-quantity-arrow hide"><i class="fa fa-arrow-right"></i></span>
                        <span class="inventory-quantity inventory-quantity--modified hide"></span>
                    </td>
                    <td class="text-center"><?php echo !$product->product_variation_inventory_policy ? 'Ngừng bán' : 'Tiếp tục bán'; ?></td>

                    <?php } else { ?>

                    <td><?php echo $product->product_code; ?></td>
                    <td class="price"><?php echo number_format($product->price); ?></td>
                    <td class="type--centered type--no-wrap" context="inventory">
                        <span class="inventory-quantity <?php echo $product->inventory < 10 ? 'warning' : '' ?>" data-value="<?php echo floatval($product->inventory); ?>"><?php echo number_format($product->inventory); ?></span>
                        <span class="inventory-quantity-arrow hide"><i class="fa fa-arrow-right"></i></span>
                        <span class="inventory-quantity inventory-quantity--modified hide"></span>
                    </td>
                    <td class="text-center"><?php echo !$product->inventory_policy ? 'Ngừng bán' : 'Tiếp tục bán'; ?></td>

                    <?php } ?>

                    <td>
                        <form action="{{route('backend.products.inventory')}}" method="post" class="form-update-inventory">
                            @csrf
                            <div class="input-group margin inventory_update">
                                <input type="hidden" value="change" class="input_type_update" name="type_update">
                                <input type="hidden" value="{{$product->id}}" name="product_id">
                                <input type="hidden" value="{{$product->product_variation_id}}" name="product_variation_id">
                                <input type="hidden" value="0" name="inventory" class="input_new_inventory">

                                <div class="input-group">
                                    <button type="button" class="btn btn-default btn-sm flat active change_btn">Thêm
                                    </button>

                                    <button type="button" class="btn btn-default btn-sm flat set_btn">Đặt</button>
                                    &nbsp;
                                    <input type="number" class="form-control input-sm input-update-inventory" style="min-width: 70px" value="0">

                                    <button type="button" class="btn btn-primary btn-sm flat submit-update-inventory" disabled="disabled">
                                        <i class="fa fa-save"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">-</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<br>
<div class="text-center">
    {{ $products->links() }}
</div>
