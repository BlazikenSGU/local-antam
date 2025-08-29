@extends('backend.layouts.admin')

@section('content')
    <div class="container-fluid mt-4">

        <div class=" desktop">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline-info">
                        <div class="card-body">

                            <div class="row page-titles">
                                <div class="col-md-5 align-self-center mb-3">
                                    <h3>Báo cáo thống kê đơn hôm nay</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div>
                                        <strong>Đơn 3 ngày gần nhất:</strong> <br>
                                        <span>{{ $now->format('d-m-Y') }}: {{ $today }}</span> <br>
                                        <span>{{ $yesterday->format('d-m-Y') }}: {{ $yesterdayCount }}</span> <br>
                                        <span>{{ $dayBeforeYesterday->format('d-m-Y') }}: {{ $dayBeforeYesterdayCount }}</span> <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
