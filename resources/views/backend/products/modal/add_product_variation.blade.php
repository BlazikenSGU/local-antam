<!-- Modal -->
<div id="modal_variation" class="modal fade" role="dialog">
    <div class="modal-dialog flat">
        <div class="modal-content flat" id="modal-add-product-variation">
            <div class="modal-header">
                <h4 class="modal-title">Thêm phiên bản</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <form action="" method="post" class="form_add_product_variation">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="product_id" value="{{$product->id}}"/>
                        @foreach ($current_variation_list as $v)
                            <div class="col-md-4">{{$variations_array[$v]['0']['name']}}</div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control" required
                                           name="product_variation[value][{{$v}}]" placeholder=""/>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-md-4">Giá</div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="number" min="0" class="form-control"
                                       name="product_variation[price]" value=""/>
                            </div>
                        </div>
                        <div class="col-md-4">Mã sản phẩm</div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control"
                                       name="product_variation[product_code]" value=""/>
                            </div>
                        </div>
                        <div class="col-md-4">Quản lý tồn kho</div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="checkbox" id="inventory_management_new" class="filled-in"
                                       name="product_variation[inventory_management]"/>
                                <label for="inventory_management_new"
                                       class="chk-col-red"></label>
                            </div>
                        </div>
                        <div class="col-md-4">Tồn kho</div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="number" class="form-control" min="0"
                                       name="product_variation[inventory]" value="0"/>
                            </div>
                        </div>
                        <div class="col-md-4">Đặt khi hết hàng</div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="checkbox" id="inventory_policy_new" class="filled-in"
                                       name="product_variation[inventory_policy]"/>
                                <label for="inventory_policy_new"
                                       class="chk-col-red"></label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-default flat" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary flat submit_form_add_product_variation">Lưu
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
