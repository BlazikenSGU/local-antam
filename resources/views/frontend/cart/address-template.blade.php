<div class="col-md-12">
    <!-- Input -->
    <div class="js-form-message mb-3">
        <label class="form-label">
            Họ tên <span class="text-danger">*</span>
        </label>
        <input type="text" class="form-control" name="fullname"
               value="{{$address->name}}"
               placeholder=""
               aria-label="" required="" data-msg="Vui lòng nhập họ tên"
               data-error-class="u-has-error" data-success-class="u-has-success"
               autocomplete="off">
    </div>
    <!-- End Input -->
</div>

<div class="col-md-6">
    <!-- Input -->
    <div class="js-form-message mb-3">
        <label class="form-label">
            Số điện thoại <span class="text-danger">*</span>
        </label>
        <input type="text" class="form-control" name="phone"
               placeholder="" aria-label="" required=""
               value="{{$address->phone}}"
               data-msg="Vui lòng nhập số điện thoại"
               data-error-class="u-has-error" data-success-class="u-has-success"
               autocomplete="off">
    </div>
    <!-- End Input -->
</div>

<div class="col-md-6">
    <!-- Input -->
    <div class="js-form-message mb-3">
        <label class="form-label">
            Email
        </label>
        <input type="text" class="form-control"
               value="{{$address->email}}"
               name="email">
    </div>
    <!-- End Input -->
</div>

<div class="col-md-12">
    <!-- Input -->
    <div class="js-form-message mb-3">
        <label class="form-label">
            Địa chỉ <span class="text-danger">*</span>
        </label>
        <input type="text" class="form-control" name="street"
               placeholder="" aria-label="" required=""
               data-msg="Vui lòng nhập địa chỉ"
               data-error-class="u-has-error" data-success-class="u-has-success"
               autocomplete="off" value="{{$address->street_name}}">
    </div>
    <!-- End Input -->
</div>

<div class="w-100"></div>

<div class="col-md-4">
    <!-- Input -->
    <div class="js-form-message mb-3">
        <label class="form-label">
            Tỉnh/Thành phố
            <span class="text-danger">*</span>
        </label>
        <select class="form-control select_province" required=""
                name="province_id"
                data-msg="Vui lòng chọn Tỉnh/Thành phố"
                data-error-class="u-has-error"
                data-success-class="u-has-success"
                data-live-search="true"
                onchange="get_district($(this))"
                data-style="form-control border-color-1 font-weight-normal">
            <option value="">Chọn</option>
            @foreach($provinces as $province)
                <option value="{{$province->id}}"
                    {{$address->province_id == $province->id ? 'selected="checked"':''}}>
                    {{$province->name}}
                </option>
            @endforeach
        </select>
    </div>
    <!-- End Input -->
</div>

<div class="col-md-4">
    <!-- Input -->
    <div class="js-form-message mb-3">
        <label class="form-label">
            Quận/Huyện
            <span class="text-danger">*</span>
        </label>
        <select class="form-control select_district" required=""
                name="district_id"
                data-msg="Vui lòng chọn Quận/Huyện" data-error-class="u-has-error"
                data-success-class="u-has-success"
                data-live-search="true"
                onchange="get_ward($(this))"
                data-style="form-control border-color-1 font-weight-normal">
            <option value="">Chọn</option>
                @foreach($districts as $district)
                    <option value="{{$district->id}}"
                        {{$address->district_id == $district->id ? 'selected="checked"':''}}>
                        {{$district->name}}
                    </option>
                @endforeach
        </select>
    </div>
    <!-- End Input -->
</div>

<div class="col-md-4">
    <!-- Input -->
    <div class="js-form-message mb-3">
        <label class="form-label">
            Phường/Xã
            <span class="text-danger">*</span>
        </label>
        <select class="form-control select_ward" required=""
                name="ward_id"
                data-msg="Vui lòng chọn Phường/Xã" data-error-class="u-has-error"
                data-success-class="u-has-success"
                data-live-search="true"
                data-style="form-control border-color-1 font-weight-normal">
            <option value="">Chọn</option>
                @foreach($wards as $ward)
                    <option value="{{$ward->id}}"
                        {{$address->ward_id == $ward->id ? 'selected="checked"':''}}>
                        {{$ward->name}}
                    </option>
                @endforeach
        </select>
    </div>
    <!-- End Input -->
</div>
