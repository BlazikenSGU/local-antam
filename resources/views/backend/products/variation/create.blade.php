<div class="create_variation_form {{isset($product_variation)&&count($product_variation) ?'d-none':''}}" style="width: 100%;">
    <div class="col-md-12">
        <div class="form-group form-group-sm">
            <label style="width: 100%;padding: 10px 0">Chọn thuộc tính &nbsp;
                <a href="javascript:;" class="label label-primary flat add_variation">+</a>
            </label>
            <select class="form-control select-variation" name="VariationList[]"
                    id="variation-selection" multiple="multiple">
                @foreach ($variations as $v)
                    <option value="{{$v->id}}">
                        {{$v->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group-sm row">
            <div class="col-md-3">
                Tên thuộc tính
            </div>
            <div class="col-md-9">
                Giá trị thuộc tính
            </div>
        </div>

        <div class="option-list" style="margin-top: 10px">

        </div>

        <div class="option-value" style="margin-top: 10px;display: none">
            <div class="table table-responsive">
                <table class="table p-none table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="select" style="width: 40px">Chọn</th>
                            <th class="min-width-110-px">Biến thể</th>
                            <th class="width-125-px min-width-125-px">Giá</th>
                            <th class="min-width-100-px">Mã SP</th>
                            <th class="min-width-100-px">QL kho</th>
                            <th class="min-width-100-px">Tồn kho</th>
                            <th class="min-width-100-px">Đặt khi hết hàng</th>
                        </tr>
                    </thead>
                    <tbody class="product_variant_list">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
