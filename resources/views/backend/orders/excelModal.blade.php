<div class="modal fade" id="exampleModalExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tải File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form   method="post" action="{{ route('backend.orders.excel') }}" >
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-sm">
                                <input type="text" name="date_start" value="" id="working_date_form1" readonly="" class="form-control form-control-sm date_select" placeholder="Từ ngày" style="cursor: pointer;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-sm">
                                <input type="text" name="date_end" value="" id="working_date_to1" readonly="" class="form-control form-control-sm date_select" placeholder="Đến ngày" style="cursor: pointer;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            Tùy chọn
                            <input type="checkbox" id="Status" name="status1" value="all">
                            <label for="Status"> Tất cả</label><br>
                        </div>
                        <div class="col-md-4">

                            <input type="checkbox" id="cod1" name="cod[]" value="1">
                            <label for="cod1">  Chưa chuyển COD</label><br>
                        </div>
                        <div class="col-md-4">

                            <input type="checkbox" id="cod2" name="cod[]" value="2">
                            <label for="cod2"> Đã chuyển COD</label><br>
                        </div>
                        <div class="col-md-12">
                            Trạng thái
                        </div>
                        @foreach($statusNames as $k=>$iStatus)
                            <div class="col-md-4">
                                <input type="checkbox" id="Status{{$k}}" name="status[]" value="{{$iStatus->key}}">
                                <label for="Status{{$k}}"> {{$iStatus->name}}</label><br>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Tải</button>
                </div>
            </form>
        </div>
    </div>
</div>
