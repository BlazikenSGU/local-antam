btn btn-outline-dark mt-auto chooseProduct<tr id="product_id_show_{{ $product->product_id }}">
    <td>{{ $product->title }}</td>
    <td colspan="2">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <button class="btn btn-outline-secondary downPrice" id="downPrice_{{ $product->product_id }}"
                    type="button" data-price-change="{{ $product->price_in_change }}"
                    data-cash-discount-product="{{ $product->cash_discount_product }}"
                    data-id="{{ $product->product_id }}" data-price="{{ $product->price }}">-
                </button>
            </div>
            <input type="text" class="form-control updownPrice" id="updownPrice_{{ $product->product_id }}"
                value="{{ $product->quantity ?? 1 }}" name="quantity_list" data-id="{{ $product->product_id }}"
                data-price="{{ $product->price }}" data-price-change="{{ $product->price_in_change }}"
                data-cash-discount-product="{{ $product->cash_discount_product }}"
                pattern="[0-9]*" aria-describedby="basic-addon1">

            <div class="input-group-append">
                <button class="btn btn-outline-secondary upPrice" id="upPrice_{{ $product->product_id }}"
                    type="button" data-price-change="{{ $product->price_in_change }}"
                    data-cash-discount-product="{{ $product->cash_discount_product }}"
                    data-type-discount="{{ $product->type_discount }}" data-id="{{ $product->product_id }}"
                    data-price="{{ $product->price }}">+
                </button>
            </div>
        </div>

        <div class="mt-2">
            <input type="text" class="form-control noteProduct" id="noteProduct_{{ $product->product_id }}"
                data-id="{{ $product->product_id }}" value="{{ $product->notes }}" placeholder="Nhập ghi chú">
        </div>
    </td>

    <td class="text-right">
        <div>
            <span data-id="{{ $product->product_id }}" data-price="{{ $product->price }}"
                class="TotalPriceAfterDiscount"
                id="totalPriceAfterDiscount_{{ $product->product_id }}">{{ $product->price_in_change ?? $product->price }}</span>
            <sup>đ</sup>
        </div>
    </td>

    <td class="text-right">
        <div style="width:auto;margin: auto">
            <div style="float: left">
                <div class="input-group mt-2">
                    <input type="text" class="form-control TotalPrice" placeholder="Tùy chỉnh giá bán"
                        id="totalPrice_{{ $product->product_id }}"
                        value="{{ $product->price_in_change ?? $product->price }}"
                        data-price="{{ $product->price }}" data-id="{{ $product->product_id }}" />
                    <div class="input-group-append">
                        <span class="input-group-text">đ</span>
                    </div>
                </div>
            </div>
            <div style="float: right">
                <button class="btn btn-sm btn-danger deleteProduct" data-id="{{ $product->product_id }}"><i
                        class="fa fa-trash"></i>
                </button>
            </div>
        </div>

        <div class="input-group mt-2">
            <input type="text" class="form-control discountProduct"
                id="discountProduct_{{ $product->product_id }}" value="{{ !empty($valueDisountProduct) ?? 0 }}"
                data-id="{{ $product->product_id }}" data-price="{{ $product->price }}"
                placeholder="Nhập chiết khấu" />

            <div class="input-group-append">
                <span data-id="{{ $product->product_id }}" data-type-discount="cash"
                    id="discountCash_{{ $product->product_id }}"
                    class="input-group-text discount-icon discount-cash">đ</span>
            </div>

        </div>
    </td>
</tr>
