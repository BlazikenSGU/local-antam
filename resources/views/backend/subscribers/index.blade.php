@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table color-table muted-table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>IP</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($list_data as $key => $user)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->ip}}</td>
                                        <td>{{$user->created_at}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">-</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
