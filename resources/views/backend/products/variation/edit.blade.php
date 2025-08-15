<div class="col-md-12 edit_variation_form {{!isset($product_variation)||!count($product_variation) ?'d-none':''}}">

    <a href="javascipt:;" class="pull-right add_product_variation">Thêm phiên bản</a>

    <div class="option-value" style="margin-top: 10px;">
        <div class="table table-responsive">
            <table class="table p-none table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 50px">Hình</th>
                        @foreach ($current_variation_list as $v)
                            <th style="width: 100px"><?php echo $variations_array[$v][0]['name'] ?></th>
                        @endforeach
                        <th style="width: 100px">Giá</th>
                        <th style="width: 100px">Mã SP</th>
                        <th style="width: 70px">QL kho</th>
                        <th style="width: 100px">Tồn kho</th>
                        <th style="width: 130px">Đặt khi hết hàng</th>
                        <th style="width: 50px;text-align: center">&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="product_variant_list_edit">
                    @foreach ($product_variation as $v)
                        @php
                            $variation_value = explode('/', $v->name);
                            $VariationIDCombination = explode(',', $v->variation_id_combination);
                            sort($VariationIDCombination);

                            $aImg = !empty($v->gallery) ? json_decode($v->gallery, true) : [];

                            $img = $aImg?$aImg[0]['file_path']:null;

                        @endphp
                        <tr id="variation_id_{{$v->id}}">

                            <td>
                                <img src="{{\App\Utils\Links::ImageLink($img, true)}}"
                                     class="img-bordered product_variation_images"
                                     data-product-variation-id="{{$v->id}}"
                                     data-product-id="{{$v->product_id}}"
                                     height="40">
                            </td>

                            @foreach ($variation_value as $k => $value)
                                <td>
                                    <input type="text"
                                           value="{{$value}}"
                                           name="product_variations[{{$v->id}}][variation_combine][{{$VariationIDCombination[$k]}}]">
                                </td>
                            @endforeach

                            <td>
                                <input type="number" min="0"
                                       value="{{$v->price}}"
                                       name="product_variations[{{$v->id}}][price]">
                            </td>

                            <td>
                                <input type="text" class="next-input inline"
                                       value="{{$v->product_code}}"
                                       name="product_variations[{{$v->id}}][product_code]">
                            </td>

                            <td>
                                <input type="checkbox" class="next-input inline filled-in"
                                       id="inventory_management_{{$v->id}}"
                                       {!! $v['inventory_management'] ? 'checked="checked"' : '' !!}
                                       name="product_variations[{{$v->id}}][inventory_management]">

                                <label for="inventory_management_{{$v->id}}"
                                       class="chk-col-red"></label>
                            </td>
                            <td>
                                <input type="number" class="next-input inline" min="0"
                                       value="{{$v->inventory}}"
                                       name="product_variations[{{$v->id}}][inventory]">
                            </td>
                            <td>
                                <input type="checkbox" class="next-input inline filled-in"
                                       id="inventory_policy_{{$v->id}}"
                                       {!! $v['inventory_policy'] ? 'checked="checked"' : '' !!}
                                       name="product_variations[{{$v->id}}][inventory_policy]">
                                <label for="inventory_policy_{{$v->id}}"
                                       class="chk-col-red"></label>
                            </td>
                            <td align="center">
                                <button type="button" class="btn btn-xs btn-danger delete_variation" data-id="{{$v->id}}" data-product_id="{{$v->product_id}}">
                                    <i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
