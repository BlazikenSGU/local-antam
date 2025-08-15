<div class="modal-header">
    <h4 class="modal-title text-center">Hình biến thể</h4>
</div>
<form action="" method="post" class="product_variation_images">
    <div class="modal-body">

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="ProductVariationImage">Chọn hình</label>
                    <input type="file" accept="image/*"
                           data-type="{{\App\Models\Files::TYPE_PRODUCT}}"
                           id="ProductVariationImage"
                           name="ProductVariationImage[]"
                           multiple="" accept="image/*">
                </div>
                <input type="hidden" value="{{$product_variation_id}}" class="product-variation-id">
                <input type="hidden" value="{{$product_id}}" class="product-id">
                <div class="row product_variation_thumb_img">
                    @if (count($galleries))
                        @foreach ($galleries as $k => $img)
                            <div class="col-md-3" id="gallery-{{$k}}">
                                <p class="text-center">
                                    <img src="{{\App\Utils\Links::ImageLink($img['file_path'], true)}}" class="img-bordered" height="100"/>
                                    <a href="javascript:;" class="delete_product_variaton_image"
                                       data-file_id="{{$img['file_id']}}">Xóa</a>
                                </p>
                            </div>
                        @endforeach
                    @endif
                </div>

                <p class="text-center">
                    <button class="btn btn-sm btn-primary save_product_variaton_image_sort" type="button">Lưu sắp xếp
                    </button>
                </p>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <div class="col-md-12 text-center">
            <button type="button" class="btn btn-default flat" data-dismiss="modal">Đóng</button>
        </div>
    </div>
</form>
