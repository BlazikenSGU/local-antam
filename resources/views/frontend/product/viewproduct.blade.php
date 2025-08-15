@extends('frontend.layouts.main')

@push('title')
    Xem sản phẩm
@endpush

@section('content')
    <div class="container">
        <div class="card">


            <div class="card-body">
                <form method="POST" action="{{ route('user.mystore.storeproduct') }}">
                    @csrf

                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                    <div class="mb-3">
                        <label for="">ID</label>
                        <input type="text" class="form-control" name="id" value="{{ $product->id }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control" id="" value="{{ $product->name }}"
                            placeholder="tên sản phẩm" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">SKU</label>
                        <input type="text" name="product_code" class="form-control" id="" value="{{ $product->product_code }}"
                            placeholder="mã sản phẩm" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Khối lượng (gram)</label>
                        <input type="number" class="form-control" value="{{ $product->amount }}" id=""
                            placeholder="gram" name="amount" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Trạng thái</label>
                        <input type="text" class="form-control" value="{{ $product->status == 1 ? 'Hoạt động' : 'Không hoạt động' }}" readonly>
                    </div>

                    <a href="{{ route('user.mystore.editproduct', $product->id) }}" class="btn btn-primary">Chỉnh sửa</a>
                </form>
            </div>
        </div>
    </div>
@endsection
