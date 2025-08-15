@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')

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
                                    <form action="" method="post">
                                        @csrf
                                        @include('frontend.parts.msg')
                                        @include('frontend.parts.errors')

                                        <div class="form-group row">
                                            <label for="bank_id" class="col-sm-4 col-form-label">Ngân Hàng</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="bank_id">
                                                    <option class="input-text" value="0"> ---------Chọn---------
                                                    </option>
                                                    @foreach($bank as $k => $v)
                                                        <option class="input-text"
                                                                value="{{$v['id']}}" {!! $banks['bank_id'] == $v['id'] ? 'selected="selected"' : '' !!}> {{$v['name']}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="bank_account_number" class="col-sm-4 col-form-label">Số tài
                                                khoản</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="bank_account_number"
                                                       name="bank_account_number"
                                                       value="{{old('bank_account_number',$banks['bank_account_number'])}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fullname" class="col-sm-4 col-form-label">Tên chủ
                                                thẻ</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="fullname"
                                                       name="fullname"
                                                       value="{{old('fullname',$banks['fullname'])}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fullname" class="col-sm-4 col-form-label">Chi nhánh</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="bank_branch"
                                                       name="bank_branch"
                                                       value="{{old('bank_branch',$banks['bank_branch'])}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label"></label>
                                            <div class="col-sm-8">
                                                <button type="submit" class="btn btn-black">Cập nhật</button>
                                            </div>
                                        </div>
                                </div>


                                </form>
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
