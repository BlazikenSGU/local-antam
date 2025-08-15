<div class="table-responsive">
    <!-- table-bordered   -->
    <!--    <table class="table color-table muted-table table-striped ">-->
    <!--        <thead>
        <tr>
            <th>id</th>
            <th >Bàn</th>
            <th style=" text-align: center ;">Chi nhánh</th>
            <th class="text-right">Hành động</th>
        </tr>
        </thead>
        <tbody>-->
    <div class="col-md-12">
        @forelse($data as $key => $item)
{{--  ===============Nhấn vào bàn sang trang chi tiết để thanh toán==============    --}}
{{--            @if ($item->status == 1)--}}
{{--                <a--}}
{{--                    href="{{ Route('backend.warehouses.detail', [\App\Http\Controllers\Backend\OrdersController::getIdOrderByIdBan($item->id)]) . '?_ref=' . $current_url }}">--}}
{{--                    <div class="btn btn-primary" style=" padding: 20px;margin-bottom: 10px">--}}
{{--                        <h1 style=" padding: 20px;color: white"> {{ $item->name }}</h1>--}}
{{--                        <h6 style="text-align: right;font-size: 9px;color: white"> {{ $item->namebranch }} </h6>--}}



{{--                        <a href="{{ Route('backend.warehouses.edit', [$item->id]) . '?_ref=' . $current_url }}"--}}
{{--                            class="btn waves-effect waves-light btn-info btn-sm">--}}
{{--                            <i class="fa fa-pencil-square-o"></i> Sửa</a>--}}

{{--                        <a href="#" class="btn waves-effect waves-light btn-danger btn-sm btnShowModal"--}}
{{--                            data-id="{{ $item->id }}">--}}
{{--                            <i class="fa fa-trash-o"></i> Xóa</a>--}}

{{--                    </div>--}}
{{--                </a>--}}
            {{--================================================================--}}


        @if ($item->status == 1)

                <div class="btn btn-primary selectedWarehouse selectedWarehouse1" data-id_item="{{$item->id}}" data-item="{{ $item }}"
                     style=" padding: 20px;margin-bottom: 10px">
                    <h1 style=" padding: 20px "> {{ $item->name }} </h1>
                    <h6 style="text-align: right;font-size: 9px"> {{ $item->namebranch }} </h6>

                    <a href="{{ Route('backend.warehouses.edit', [$item->id]) . '?_ref=' . $current_url }}"
                       class="btn waves-effect waves-light btn-info btn-sm">
                        <i class="fa fa-pencil-square-o"></i> Sửa</a>

                    <a href="#" class="btn waves-effect waves-light btn-danger btn-sm btnShowModal"
                       data-id="{{ $item->id }}">
                        <i class="fa fa-trash-o"></i> Xóa</a>
                </div>

            @elseif($item->status == 2)
                <a
                    href="{{ Route('backend.warehouses.detail', [\App\Http\Controllers\Backend\OrdersController::getIdOrderByIdBan($item->id)]) . '?_ref=' . $current_url }}">
                    <div class="btn btn-info" style=" padding: 20px;margin-bottom: 10px">
                        <h1 style="color: white; padding: 20px"> {{ $item->name }} </h1>
                        <h6 style="color: white; text-align: right;font-size: 9px"> {{ $item->namebranch }} </h6>



                        <a href="{{ Route('backend.warehouses.edit', [$item->id]) . '?_ref=' . $current_url }}"
                            class="btn waves-effect waves-light btn-info btn-sm">
                            <i class="fa fa-pencil-square-o"></i> Sửa</a>

                        <a href="#" class="btn waves-effect waves-light btn-danger btn-sm btnShowModal"
                            data-id="{{ $item->id }}">
                            <i class="fa fa-trash-o"></i> Xóa</a>



                    </div>
                </a>
            @elseif($item->status == 3)
                <a
                    href="{{ Route('backend.warehouses.detail', [\App\Http\Controllers\Backend\OrdersController::getIdOrderByIdBan($item->id)]) . '?_ref=' . $current_url }}">
                    <div class="btn btn-warning" style=" padding: 20px;margin-bottom: 10px">
                        <h1 style="color: white; padding: 20px"> {{ $item->name }}</h1>
                        <h6 style="color: white; text-align: right;font-size: 9px"> {{ $item->namebranch }} </h6>



                        <a href="{{ Route('backend.warehouses.edit', [$item->id]) . '?_ref=' . $current_url }}"
                            class="btn waves-effect waves-light btn-info btn-sm">
                            <i class="fa fa-pencil-square-o"></i> Sửa</a>

                        <a href="#" class="btn waves-effect waves-light btn-danger btn-sm btnShowModal"
                            data-id="{{ $item->id }}">
                            <i class="fa fa-trash-o"></i> Xóa</a>



                    </div>
                </a>
            @elseif($item->status == 4)
                <a
                    href="{{ Route('backend.warehouses.detail', [\App\Http\Controllers\Backend\OrdersController::getIdOrderByIdBan($item->id)]) . '?_ref=' . $current_url }}">
                    <div class="btn btn-success" style=" padding: 20px;margin-bottom: 10px">
                        <h1 style="color: white; padding: 20px"> {{ $item->name }} </h1>
                        <h6 style="text-align: right;font-size: 9px;color: white"> {{ $item->namebranch }} </h6>


                        <a href="{{ Route('backend.warehouses.edit', [$item->id]) . '?_ref=' . $current_url }}"
                            class="btn waves-effect waves-light btn-info btn-sm">
                            <i class="fa fa-pencil-square-o"></i> Sửa</a>

                        <a href="#" class="btn waves-effect waves-light btn-danger btn-sm btnShowModal"
                            data-id="{{ $item->id }}">
                            <i class="fa fa-trash-o"></i> Xóa</a>


                    </div>
                </a>
            @elseif($item->status == 5)
                <a
                    href="{{ Route('backend.warehouses.detail', [\App\Http\Controllers\Backend\OrdersController::getIdOrderByIdBan($item->id)]) . '?_ref=' . $current_url }}">
                    <div class="btn btn-danger" style=" padding: 20px;margin-bottom: 10px">
                        <h1 style="color: white; padding: 20px"> {{ $item->name }}</h1>
                        <h6 style="text-align: right;font-size: 9px"> {{ $item->namebranch }} </h6>

                        <a href="{{ Route('backend.warehouses.edit', [$item->id]) . '?_ref=' . $current_url }}"
                            class="btn waves-effect waves-light btn-info btn-sm">
                            <i class="fa fa-pencil-square-o"></i> Sửa</a>

                        <a href="#" class="btn waves-effect waves-light btn-danger btn-sm btnShowModal"
                            data-id="{{ $item->id }}">
                            <i class="fa fa-trash-o"></i> Xóa</a>

                    </div>
                </a>

            @elseif($item->status == 0)
                <div class="btn btn-light selectWarehouse" data-item="{{ $item }}"
                    style=" padding: 20px;margin-bottom: 10px">
                    <h1 style=" padding: 20px "> {{ $item->name }} </h1>
                    <h6 style="text-align: right;font-size: 9px"> {{ $item->namebranch }} </h6>

                    <a href="{{ Route('backend.warehouses.edit', [$item->id]) . '?_ref=' . $current_url }}"
                        class="btn waves-effect waves-light btn-info btn-sm">
                        <i class="fa fa-pencil-square-o"></i> Sửa</a>

                    <a href="#" class="btn waves-effect waves-light btn-danger btn-sm btnShowModal"
                        data-id="{{ $item->id }}">
                        <i class="fa fa-trash-o"></i> Xóa</a>
                </div>
            @endif


            {{-- <tr>
                <td>{{$item->id}}</td>

                <td>
                    @if ($item->status == 1)
                        <div class="btn btn-primary">
                            <h1 style="color:white; padding: 20px"> {{$item->name}} </h1>
                        </div>
                    @elseif($item->status == 2)
                        <div  class="btn btn-info">
                            <h1 style=" padding: 20px"> {{$item->name}}</h1>
                        </div>
                    @elseif($item->status == 3)
                        <div  class="btn btn-warning">
                            <h1 style=" padding: 20px"> {{$item->name}}</h1>
                        </div>
                    @elseif($item->status == 4)
                        <div  class="btn btn-success">
                            <h1 style=" padding: 20px"> {{$item->name}}</h1>
                        </div>
                    @elseif($item->status == 5)
                        <div  class="btn btn-danger">
                            <h1 style=" padding: 20px"> {{$item->name}}</h1>
                        </div>
                    @elseif($item->status == 0)
                        <h1 style=" padding: 20px"> {{$item->name}}</h1>
                    @endif


                </td>

                <td style=" text-align: center ;">
                    {{$item->namebranch}}
                </td>


                <td class="text-right">
                    @if (auth()->guard('backend')->user()->can('posts.edit'))
                        <a href="{{Route('backend.warehouses.edit',[$item->id]). '?_ref=' .$current_url }}"
                           class="btn waves-effect waves-light btn-info btn-sm">
                            <i class="fa fa-pencil-square-o"></i> Sửa</a>
                    @endif

                    @if (auth()->guard('backend')->user()->can('posts.del'))
                        <a href="#"
                           class="btn waves-effect waves-light btn-danger btn-sm btnShowModal"
                           data-id="{{ $item->id }}">
                            <i class="fa fa-trash-o"></i> Xóa</a>
                    @endif

                </td>
            </tr> --}}
        @empty
            <!--            <tr>
                <td colspan="10">-</td>
            </tr>-->
        @endforelse

    </div>
</div>

{{-- pagination --}}
<div class="text-center">
    {{ $data->links() }}
</div>
