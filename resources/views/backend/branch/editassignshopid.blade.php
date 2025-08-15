@extends('backend.layouts.main2222')

@section('content')
    <style>
        .row.page-titles {
            margin: 2rem 1rem;
        }
    </style>

    <div class="row page-titles">
        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    @include('backend.partials.msg')
                    @include('backend.partials.errors')

                    <div class="row">
                        <div class="col-md-12">

                            <h2> Chọn shopID </h2>
                            @php
                                $selectedShopIds = json_decode($user->shopId ?? '[]', true);
                            @endphp
                            <form action="{{ route('backend.brands.updateassignshopid', $user->id) }}" method="POST">
                                @csrf

                                <div class="d-flex flex-column">

                                    @foreach ($branchs as $branch)
                                        <div class="form-check form-check-inline" style="padding: .5rem 0 .5rem 24px;">
                                            <input class="form-check-input" type="checkbox" id="{{ $branch->shopId }}"
                                                name="shopId[]" value="{{ $branch->shopId }}"
                                                {{ in_array($branch->shopId, $selectedShopIds) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="inlineCheckbox1">{{ $branch->shopId }} -
                                                {{ $branch->name }}</label>
                                        </div>
                                    @endforeach

                                </div>

                                <button class="btn btn-primary mt-2" type="submit">Cập nhật</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
