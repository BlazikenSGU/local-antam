@extends('frontend.layouts.main')

@push('title')
    Chính sửa sản phẩm
@endpush

@section('content')
    <div class="container">
        <div class="card">


            <div class="card-body">
                <form method="POST" action="{{ route('user.mystore.updateproduct', $product->id) }}">
                    @csrf

                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                    <div class="mb-3">
                        <label for="" class="form-label">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control" id=""
                            value="{{ $product->name }}" placeholder="tên sản phẩm">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">SKU</label>
                        <input type="text" name="product_code" class="form-control" id=""
                            value="{{ $product->product_code }}" placeholder="mã sản phẩm">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Khối lượng (gram)</label>
                        <input type="number" class="form-control" value="{{ $product->amount }}" id=""
                            placeholder="gram" name="amount">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
