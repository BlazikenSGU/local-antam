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
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" id="name"
                            placeholder="tên sản phẩm">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Mã sản phẩm</label>
                        <input type="text" name="product_code"
                            class="form-control @error('product_code') is-invalid @enderror" id="product_code"
                            placeholder="mã sản phẩm">
                        @error('product_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Khối lượng (g)</label>
                        <input type="number" class="form-control  @error('amount') is-invalid @enderror" id="amount"
                            placeholder="gram" name="amount">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                </form>
            </div>
        </div>
    </div>
@endsection
