@extends('backend.layouts.main2222')

@section('content')
    {{-- <div class="row page-titles">
        <div class="col-md-5">
            <h3 class="text-themecolor">Danh sách shopID</h3>
        </div>


        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal" action="" method="post">

                        <div class="row">
                            <div class="col-md-6" style="margin: auto">
                                @include('backend.partials.msg')
                                @include('backend.partials.errors')
                                @csrf

                                <div class="form-group">
                                    <label class="col-md-12">Tên</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control form-control-line" name="fullname" value="{{old('fullname')}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Email</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control form-control-line" name="email" value="{{old('email')}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Số điện thoại </label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control form-control-line" name="phone" value="{{old('phone')}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Tỉnh/ Thành phố</label>
                                    <div class="col-md-12">
                                        <select name="type" class="form-control">
                                            <option value="1" {{old('type')==1?'selected="selected"':''}}>Giảm số tiền
                                            </option>
                                            <option value="2" {{old('type')==2?'selected="selected"':''}}>Giảm %
                                            </option>
                                        </select>                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Quận Huyện</label>
                                    <div class="col-md-12">
                                        <select name="type" class="form-control">
                                            <option value="1" {{old('type')==1?'selected="selected"':''}}>Giảm số tiền
                                            </option>
                                            <option value="2" {{old('type')==2?'selected="selected"':''}}>Giảm %
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Phường Xã</label>
                                    <div class="col-md-12">
                                        <select name="type" class="form-control">
                                            <option value="1" {{old('type')==1?'selected="selected"':''}}>Giảm số tiền
                                            </option>
                                            <option value="2" {{old('type')==2?'selected="selected"':''}}>Giảm %
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Địa chỉ</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control form-control-line" name="address" value="{{old('address')}}">
                                    </div>
                                </div>



                                <div class="form-group">
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-info" type="submit">Lưu</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
