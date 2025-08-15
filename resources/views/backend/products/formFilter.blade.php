<form action="" method="get" id="form-filter">
    <div class="form-body">
        <div class="row p-t-20">

            <div class="col-md-12">
                @include('backend.partials.msg')
            </div>

            <input type="hidden" name="sort" value="" class="input_sort">

            <div class="col-md-2">
                <div class="form-group form-group-sm">
                    <select class="form-control form-control-sm"
                            name="product_type_id">
                        <option value="">Danh mục</option>
                        {!! $product_type_html !!}}
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group form-group-sm">
                    <input type="number"
                           name="id"
                           value="{{request('id')}}"
                           id="product_id"
                           class="form-control form-control-sm" placeholder="ID">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-group-sm">
                    <input type="text"
                           name="title"
                           value="{{request('title')}}"
                           id="title"
                           class="form-control form-control-sm" placeholder="Tên sản phẩm">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <select class="form-control form-control-sm"
                            name="status_censorship">
                        <option value="">Trạng thái</option>
                        @foreach($status as $st)
                            <option value="{{$st['id']}}"
                                {!! request('status')===$st['id']?'selected="selected"':'' !!}>{{$st['name']}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-group-sm">
                    <select class="form-control form-control-sm"
                            name="limit">
                        @foreach($_limits as $st)
                            <option value="{{$st}}"
                                {!! $filter['limit']==$st?'selected="selected"':'' !!}>{{number_format($st)}} record
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                @if(auth()->guard('backend')->user()->can('products.add'))
                    <a href="{{Route('backend.products.add',1)}}"
                       class="btn waves-effect waves-light btn-block btn-info btn-sm">
                        <i class="fa fa-plus"></i>&nbsp;&nbsp;Thêm mới sản phẩm
                    </a>
                @endif
               {{-- @if(auth()->guard('backend')->user()->can('products.add'))
                    <a href="{{Route('backend.products.add',2)}}"
                       class="btn waves-effect waves-light btn-block btn-info btn-sm">
                        <i class="fa fa-plus"></i>&nbsp;&nbsp;Thêm mới đồ ăn vặt
                    </a>
                @endif--}}
            </div>
        </div>
    </div>
</form>
