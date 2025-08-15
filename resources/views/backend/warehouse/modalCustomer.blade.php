<div id="customerModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalLabel">Khách hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="customerForm" method="post" action="">
                    <div class="container">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="fullname">Họ tên</label>
                                <div>
                                    <input type="text" class="form-control" id="fullname" name="fullname"
                                        placeholder="Họ tên">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="email">Email</label>
                                <div>
                                    <input type="text" class="form-control" id="email" name="email"
                                        placeholder="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="phone">Điện thoại</label>
                                <div>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Điện thoại">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Tên Đường</label>
                                    <input type="text" class="form-control" id="street" name="street">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="">
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-4" style="display: none;">
                                <div class="form-group">
                                    <label for="address">Tỉnh/Thành phố</label>
                                    <select class="form-control" id="province_id" name="province_id" style="width:100%">
                                        @if (count($provinces) > 0)
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}">{{ $province->name_origin }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4" style="display: none;">
                                <div class="form-group">
                                    <label for="address">Quận/Huyện</label>
                                    <select class="form-control" id="district_id" name="district_id"
                                        style="width:100%"></select>
                                </div>
                            </div>

                            <div class="col-md-4" style="display: none;">
                                <div class="form-group">
                                    <label for="address">Phường/Xã</label>
                                    <select class="form-control" id="ward_id" name="ward_id"
                                        style="width:100%"></select>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">
                                        Ghi chú
                                    </label>

                                    <div class="input-group">
                                        <textarea class="form-control" rows="2" id="note_customer" name="note_customer" placeholder="Nhập ghi chú"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary updateCustomer">Cập nhật</button>
            </div>
        </div>
    </div>
</div>
