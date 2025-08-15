@extends('frontend.layouts.main')

@push('title')
    Thêm sản phẩm
@endpush

@section('content')

<style>
    @media (max-width: 768px) and (min-width: 375px) {
        .card {
            margin-top: 1rem;
        }
    }
</style>
    <div class="container">
        <div class="card mt-4">
            <div class="card-body">
                <form method="POST" action="{{ route('user.mystore.storeproduct') }}">
                    @csrf

                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <div class="mb-3">
                        <label for="" class="form-label">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control" id="" placeholder="tên sản phẩm">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Mã sản phẩm</label>
                        <input type="text" name="product_code" class="form-control" id=""
                            placeholder="mã sản phẩm">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Khối lượng (g)</label>
                        <input type="number" class="form-control" id="" placeholder="gram" name="amount">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="1">Hoạt động</option>
                            <option value="0">Không hoạt động</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                </form>
            </div>
        </div>
    </div>
@endsection
