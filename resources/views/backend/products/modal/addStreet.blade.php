<div id="addStreet-modal" class="modal fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thêm tên đường</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="form-add-street">
                    <div class="form-group">
                        <label>Tỉnh/TP <span class="text-danger">(*)</span></label>
                        <select class="form-control select_province select2" name="province_id" required="required" style="width: 100%">
                            <option value="">Chọn</option>
                            @foreach($relate_data['provinces'] as $province)
                                <option value="{{$province->id}}">{{$province->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Quận/Huyện <span class="text-danger">(*)</span></label>
                        <select class="form-control select_district select2" name="district_id" required="required" style="width: 100%">
                            <option value="">Chọn</option>
                        </select>
                    </div>

                    {{--<div class="form-group">--}}
                    {{--<label class="form-control-label">Phường/Xã <span class="text-danger">(*)</span></label>--}}
                    {{--<select class="form-control select_ward select2" name="ward_id" required="required" style="width: 100%">--}}
                    {{--<option value="">Chọn</option>--}}
                    {{--</select>--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <label class="form-control-label">Tên đường <span class="text-danger">(*)</span></label>
                        <input type="text" name="street_name" class="form-control input_street_name" placeholder="Nhập tên đường" required="required">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect waves-light" onclick="addStreet();">Thêm
                </button>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

@section('script2')
    <script type="application/javascript">
        new SelectLocation('#form-add-street ');

        function addStreet() {
            var form = jQuery('#form-add-street');
            var modal = jQuery('#addStreet-modal');
            var objDistrict = jQuery('#form-add-street .select_district');
            var objStreet = jQuery('#form-add-street .input_street_name');

            objDistrict.removeClass('form-control-danger');
            objDistrict.parent().removeClass('has-danger');
            objStreet.removeClass('form-control-danger');
            objStreet.parent().removeClass('has-danger');

            var district_id = objDistrict.val();
            var street_name = objStreet.val();

            if (district_id == null || district_id == '') {
                objWard.addClass('form-control-danger');
                objWard.parent().addClass('has-danger');
                return false;
            }
            if (street_name == null || street_name == '') {
                objStreet.addClass('form-control-danger');
                objStreet.parent().addClass('has-danger');
                return false;
            }

            jQuery.ajax({
                type: 'POST',
                url: '{{route('backend.ajax.addStreet')}}',
                data: {district_id: district_id, street_name: street_name, _token: '{{csrf_token()}}'},
                dataType: 'json',
                success: function (data) {
                    objDistrict.removeClass('form-control-danger');
                    objDistrict.parent().removeClass('has-danger');
                    objStreet.removeClass('form-control-danger');
                    objStreet.parent().removeClass('has-danger');

                    if (data.e == 0) {
                        form.trigger("reset");
                        modal.modal('hide');
                    } else if (data.e == -1) {
                        alert(data.r);
                    }
                }
            });
        }

    </script>
@stop
