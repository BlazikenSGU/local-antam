<form action="" method="get" id="form-filter">
    <div class="form-body">
        <div class="row p-t-20">

            <div class="col-md-12">
                @include('backend.partials.msg')
            </div>

            <input type="hidden" name="sort" value="" class="input_sort">

            <div class="col-md-1">
                <div class="form-group form-group-sm">
                    <label class="control-label" for="product_id">Mã số</label>
                    <input type="number"
                           name="id"
                           value="{{request('id')}}"
                           id="product_id"
                           class="form-control form-control-sm" placeholder="Mã số">
                </div>
            </div>

            <div class="col-md-1">
                <div class="form-group form-group-sm">
                    <label class="control-label" for="name">Tên</label>
                    <input type="text"
                           name="name"
                           value="{{request('name')}}"
                           id="name"
                           class="form-control form-control-sm" placeholder="Tên">
                </div>
            </div>

            <div class="col-md-1">
                <div class="form-group form-group-sm">
                    <label class="control-label" for="address">Địa chỉ</label>
                    <input type="text"
                           name="address"
                           value="{{request('address')}}"
                           id="address"
                           class="form-control form-control-sm" placeholder="Địa chỉ">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-group-sm">
                    <label class="control-label" for="ward_name">Phường/Xã</label>
                    <input type="text"
                           name="ward_name"
                           value="{{request('ward_name')}}"
                           id="ward_name"
                           class="form-control form-control-sm" placeholder="Phường/Xã">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-group-sm">
                    <label class="control-label" for="district_name">Quận/Huyện</label>
                    <input type="text"
                           name="district_name"
                           value="{{request('district_name')}}"
                           id="district_name"
                           class="form-control form-control-sm" placeholder="Quận/Huyện">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-group-sm">
                    <label class="control-label" for="province_name">Tỉnh/TP</label>
                    <input type="text"
                           name="province_name"
                           value="{{request('province_name')}}"
                           id="province_name"
                           class="form-control form-control-sm" placeholder="Tỉnh/TP">
                </div>
            </div>

            <div class="col-md-1">
                <div class="form-group form-group-sm">
                    <label class="control-label">Trạng thái</label>
                    <select class="form-control form-control-sm"
                            name="status">
                        <option value="">Tất cả</option>
                        @foreach($status as $st)
                            <option value="{{$st['id']}}"
                                    {!! request('status')==$st['id']?'selected="selected"':'' !!}>{{$st['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-1">
                <div class="form-group form-group-sm">
                    <label class="control-label">Số record</label>
                    <select class="form-control form-control-sm"
                            name="limit">
                        @foreach($_limits as $st)
                            <option value="{{$st}}"
                                    {!! $filter['limit']==$st?'selected="selected"':'' !!}>{{number_format($st)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>
