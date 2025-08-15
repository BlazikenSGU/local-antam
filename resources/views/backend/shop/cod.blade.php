@extends('backend.layouts.main')

@section('content')
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-dark" style="border-bottom: 1px solid #000; display: inline-block">Cod - Đối soát</h3>
            </div>
            {{--        <div class="col-md-7 align-self-center">--}}
            {{--            {{ Breadcrumbs::render('backend.orders.index') }}--}}
            {{--        </div>--}}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                  <div class="row ">
                    <div class="col-md-12 text-center m-auto">
{{--                        <img src="https://khachhang.ghn.vn/images/ghn/empty_result.svg" alt="" width="400">--}}

                        <div class="table-responsive">
                            <table class="table color-table muted-table table-striped">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã phiếu chuyển tiền</th>
                                    <th>Thời gian chuyển tiền</th>
                                    <th>Tổng tiền COD</th>
                                    <th>Gao hàng thất bại thu tiền</th>
                                    <th>Thực nhận</th>
                                    <th>Số ĐH tương ứng</th>
                                    <th></th>

                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>COD_202403041122_3376778</td>
                                    <td>11:22 04/03/2024</td>
                                    <td>378.915.500</td>
                                    <td>780.000</td>
                                    <td>378.915.500</td>
                                    <td>1463</td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                  </div>



                </div>
            </div>
        </div>
@endsection
