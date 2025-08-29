@extends('backend.layouts.admin')

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
                            <div class="table-responsive">
                                <table class="table color-table muted-table ">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên</th>
                                            <th>Sđt</th>
                                            <th>ShopId</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>
                                                    <a href="{{ route('backend.staff.edit', $item->id) }}"
                                                        style="text-decoration: none;">
                                                        {{ $item->fullname }}</a>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary"> {{ $item->phone }}</span>
                                                </td>
                                                <td>

                                                    @php
                                                        $shopIds = json_decode($item->shopId ?? '[]', true);
                                                    @endphp
                                                    @foreach ($shopIds as $shopid)
                                                        <span class="badge bg-warning text-dark">
                                                            {{ $shopid }}
                                                        </span>
                                                    @endforeach

                                                </td>

                                                <td class="text-right">
                                                    <a href="{{ route('backend.brands.editassignshopid', $item->id) }}">
                                                        <button class="btn btn-xs btn-primary"><i
                                                                class="fa-solid fa-pen-to-square"></i></button></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- pagination --}}
                            {{-- <div class="text-center">
                                {{ $data->links() }}
                            </div> --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
