<!-- Modal -->
<div id="modal_add_variation" class="modal fade" role="dialog">
    <div class="modal-dialog flat">
        <div class="modal-content flat" id="modal-add-product-variation">
            <div class="modal-header">
                <h4 class="modal-title">Thêm thuộc tính</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <form action="" method="post" class="form_add_variation">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">Tên thuộc tính</div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" required/>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-default flat" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary flat submit_form_add_variation">Lưu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
