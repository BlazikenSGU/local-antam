<div id="callbackRequest-modal" class="modal fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chỉ định nhân viên gọi lại</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="form-add-street">
                    <label class="col-md-12">SĐT thư ký</label>
                    <div class="col-md-12">
                        <select class="select2 form-control user_id"
                                data-placeholder="Nhập sđt thư ký"
                                name="user_id">
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-danger waves-effect waves-light" onclick="callbackRequest();">
                        Yêu cầu gọi lại
                    </button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script2')
    <script type="application/javascript">

    </script>
@stop
