@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')

    <!-- my account wrapper start -->
    <div class="my-account-wrapper section-padding">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- My Account Page Start -->
                        <div class="myaccount-page-wrapper pt-50 pb-80">

                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    @include('frontend.user.menu')


                                </div>
                                <!-- My Account Tab Menu End -->

                                <div class="col-lg-9 col-md-8">
                                    <div class="table-responsive ajax-result">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tài khoản</th>
                                                    <th>Nội dung</th>
                                                    <th>Thời gian</th>
                                                    <th>Ngân hàng</th>
                                                    <th>Số tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($list_data as $key => $value)
                                                    <tr>
                                                        <td>{{$value->id}}</td>
                                                        <td>
                                                            {{!empty($value->user->fullname)?$value->user->fullname:'Chưa cập nhật'}}<br><br>
                                                            {{!empty($value->user->email)?$value->user->email:"Chưa cập nhật"}}<br><br>
                                                            {{!empty($value->user->phone)?$value->user->phone:"Chưa cập nhật"}}
                                                        </td>
                                                        <td>
                                                            {{$value->title}}
                                                        </td>
                                                        <td>
                                                            {{date('d-m-Y, H:i:s',strtotime($value->created_at))}}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $json = json_decode($value->bank,TRUE);
                                                                if(!empty($json) && is_array($json)){
                                                                if(!empty($json))
                                                                {
                                                                       $bank = \App\Models\Banks::findOrFail($json['bank_id']);
                                                                            echo '<p>'.$bank->name.'</p>';
                                                                            echo '<p>Họ&Tên: <b>'.$json['fullname'].'</b></p>';
                                                                            echo '<p>STK: <b>'.$json['bank_account_number'].'</b></p>';
                                                                }else
                                                                    {
                                                                        echo $value->bank ?  '<p>'.$value->bank.'</p>' : '';
                                                                    }
                }
                                                            @endphp
                                                        </td>
                                                        <td class="text-danger">
                                                            {{number_format($value->salary)}}VND
                                                        </td>

                                                    </tr>
                                                @empty
                                                @endforelse
                                            </tbody>
                                        </table>
                                        @include('frontend.parts.pagination', ['paginator' => $list_data])


                                    </div>

                                </div>

                            </div>
                        </div> <!-- My Account Page End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- my account wrapper end -->
@endsection
