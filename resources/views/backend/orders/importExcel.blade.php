@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-dark" style="border-bottom: 1px solid #000; display: inline-block">Lên đơn Excel</h3>
        </div>
        {{--        <div class="col-md-7 align-self-center">--}}
        {{--            {{ Breadcrumbs::render('backend.orders.index') }}--}}
        {{--        </div>--}}
    </div>
    @include('backend.partials.msg')
    @include('backend.partials.errors')
    <div class="row p-2">
        <div class="col-md-4">
            <div class="card card-outline-info">
                <div class="card-body">
                    <div class="import-home-title" style="color: #F26522">| Nhập file &amp; Tạo đơn</div>
                    <div>
                        <div class="sub-title" style="font-weight: bold; color: #00467F"><strong>| Bên gửi</strong>
{{--                            <a--}}
{{--                                href="/store/edit/shop/4106717"><i class="mdi mdi-pencil-box-outline"--}}
{{--                                                                   style="font-size: 25px"></i></a>--}}
                        </div>
                        <div class="sender-content pl-3">
                            <div class="shop-name"><span class="font-weight-bold" style="color: #F26522">
                                    {{ $a_address->street_name}} -
                                    {{ $a_address->ward_name }} -
                                    {{ $a_address->district_name }} -
                                    {{ $a_address->province_name }}.
{{--                                  {{Auth()->guard('backend')->user()->fullname}} -   {{Auth()->guard('backend')->user()->phone}}--}}
                                </span></div>
                            <div class="shop-address font-weight-bold" style="color: #00467F">
                                {{ $a_address->name}} -  {{ $a_address->phone}}
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="sub-title" style="font-weight: bold; color: #00467F"><strong>| Tải file Excel đơn hàng</strong>
                        </div>
                        <div class="sender-content pl-3">
                            <div class="shop-name mt-2">
                                <form id="fileUploadForm" action="{{ route('backend.orders.create.excel') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <label class="filelabel">
                                        <i class="fa fa-paperclip"></i>
                                        <span class="title">Tải file lên</span>
                                        <input class="FileUpload1" id="FileInput" name="booking_attachment" type="file"/>
                                    </label>
                                    <button type="submit" class="mt-2 font-weight-bold" style="cursor: pointer;background-color: #00467F;padding: 5px 10px; border-radius: 5px; color: #fff; border: none">Tải</button>
                                </form>

                            </div>
                            <div class="shop-address font-weight-normal mt-2"><i>Chấp nhận file đuôi .xls, .xlsx, .xlsm</i></div>
                            <a href="{{ route('backend.orders.downloadfile',  1080 ) }}">
                                <i>Tải file mẫu </i><i class="mdi mdi-cloud-download" style="font-size: 22px"></i>
                            </a>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-outline-info">
                <div class="card-body">
                    <div class="import-home-title" style="color: #F26522">| Lịch sử lên đơn Excel</div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group form-group-sm">
                                <input type="text"
                                       name="working_date_from"
                                       value=""
                                       id="working_date_from" readonly
                                       class="form-control form-control-sm date_time_select" placeholder="Từ ngày">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group form-group-sm">
                                <input type="text"
                                       name="working_date_to"
                                       value=""
                                       id="working_date_to" readonly
                                       class="form-control form-control-sm date_time_select" placeholder="Đến ngày">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table muted-table table-striped">
                                    <thead class="font-weight-bold" style="background-color: #00467F; color: #fff">
                                    <tr >
                                        <th>STT</th>
                                        <th>File</th>

                                        <th>Thời gian tải lên</th>
                                        <th class="text-right">Trạng thái</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($files as $k=>$file)
                                    <tr class="font-weight-bold">
                                        <td>{{$k +=1}}</td>
                                        <td>{{$file->file_path}}</td>

                                        <td>{{ $file->created_at }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('backend.orders.downloadfile', $file->id ) }}">
                                                <i class="mdi mdi-cloud-download" style="font-size: 22px"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style_top')
    <style>
        .import-home-title {
            color: orange;
            font-size: 16px;
            font-weight: 600;
            height: 24px
        }

        .filelabel {
            width: 120px;
            border: 2px dashed grey;
            border-radius: 5px;
            display: block;
            padding: 5px;
            transition: border 300ms ease;
            cursor: pointer;
            text-align: center;
            margin: 0;
        }

        .filelabel i {
            display: block;
            font-size: 30px;
            padding-bottom: 5px;
        }

        .filelabel i,
        .filelabel .title {
            color: grey;
            transition: 200ms color;
        }

        .filelabel:hover {
            border: 2px solid #1665c4;
        }

        .filelabel:hover i,
        .filelabel:hover .title {
            color: #1665c4;
        }

        #FileInput {
            display: none;
        }

    </style>
@endsection
@section('script')
    <script>
        $("#FileInput").on("change", function (e) {
            var labelVal = $(".title").text();
            var oldfileName = $(this).val();
            fileName = e.target.value.split("\\").pop();

            if (oldfileName == fileName) {
                return false;
            }
            var extension = fileName.split(".").pop();

            if ($.inArray(extension, ["jpg", "jpeg", "png"]) >= 0) {
                $(".filelabel i").removeClass().addClass("fa fa-file-image-o");
                $(".filelabel i, .filelabel .title").css({color: "#208440"});
                $(".filelabel").css({border: " 2px solid #208440"});
            } else if (extension == "pdf") {
                $(".filelabel i").removeClass().addClass("fa fa-file-pdf-o");
                $(".filelabel i, .filelabel .title").css({color: "red"});
                $(".filelabel").css({border: " 2px solid red"});
            } else if (extension == "doc" || extension == "docx") {
                $(".filelabel i").removeClass().addClass("fa fa-file-word-o");
                $(".filelabel i, .filelabel .title").css({color: "#2388df"});
                $(".filelabel").css({border: " 2px solid #2388df"});
            } else {
                $(".filelabel i").removeClass().addClass("fa fa-file-o");
                $(".filelabel i, .filelabel .title").css({color: "black"});
                $(".filelabel").css({border: " 2px solid black"});
            }

            if (fileName) {
                if (fileName.length > 10) {
                    $(".filelabel .title").text(fileName.slice(0, 4) + "..." + extension);
                } else {
                    $(".filelabel .title").text(fileName);
                }
            } else {
                $(".filelabel .title").text(labelVal);
            }
        });

        document.getElementById('fileUploadForm').addEventListener('submit', function(event) {
            // Ngăn chặn việc submit mặc định của form
            event.preventDefault();

            // Hiển thị popup "Loading"
            Swal.fire({
                title: 'Đang tải file Vui lòng đợi.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Lấy phần tử input chứa tệp
            const fileInput = document.getElementById('FileInput');

            // Kiểm tra xem có tệp nào được chọn hay không
            if (fileInput.files.length > 0) {
                // Nếu có, submit form
                this.submit();
            } else {
                // Nếu không, ẩn popup và hiển thị thông báo lỗi
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Vui lòng chọn một tệp trước khi tải!'
                });
            }
        });



    </script>
@endsection
