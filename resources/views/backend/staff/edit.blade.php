@extends('backend.layouts.main2222')

@section('title', 'Cập nhật tài khoản')

@section('content')
    <style>
        .col-md-12 {
            display: flex;
        }

        .col-md-6 {
            padding: .5rem;
        }

        .card-body {
            min-height: 600px;
        }

        .class_button {
            display: flex;
            justify-content: space-around;
            margin-top: 1rem;
        }

        table.table td,
        table.table th {
            padding: 12px 20px;
        }
    </style>

    <form class="form-horizontal" action="" method="post">
        @csrf
        <div class="container-fluid mt-4 col-md-12">
            <div class="col-md-6">
                <div class="card card-outline-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="">
                                <h2 class="font-weight-bold" style=" color: orangered">
                                    Thông tin tài khoản
                                </h2>

                                <table class="table table-lg">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Thông tin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="">Họ tên</th>
                                            <td>{{ $user->fullname ?: '' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="">ID</th>
                                            <td>{{ $user->id ?: '' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="">Số điện thoại</th>
                                            <td>{{ $form_init->phone ?: '' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="">Email</th>
                                            <td>{{ $form_init->email ?: '' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="">Ngân hàng</th>
                                            <td>
                                                @php
                                                    $banks = format_name_bank();
                                                    $bankName = $banks[$user->bank_name] ?? $user->bank_name;
                                                @endphp
                                                <span class="badge bg-success">{{ $bankName }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="">STK ngân hàng</th>
                                            <td>{{ $form_init->bank_number ?: '' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="">Tên TK ngân hàng</th>
                                            <td>{{ $form_init->bank_account ?: '' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="">Ngày đăng ký</th>
                                            <td>{{ \Carbon\Carbon::parse($form_init->created_at)->format('d/m/Y H:i:s') }}
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>



                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-outline-info">
                    <div class="card-body">
                        <h2 class="font-weight-bold mb-3" style="color: orangered">
                            Cài đặt phí gửi
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                data-bs-target="#modalShopId">
                                <strong>
                                    <i class="fa-solid fa-square-plus"></i> Gán shopid
                                </strong>
                            </button>
                        </h2>

                        @php
                            $shopIds = json_decode($user->shopId ?? '[]', true);
                        @endphp

                        @foreach ($branchs as $product_type)
                            @if (in_array($product_type->shopId, $shopIds))
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="col-md-8">
                                            <label class="font-weight-bold font-20"><span
                                                    class="badge bg-danger">{{ $product_type->shopId }}</span>
                                                {{ $product_type->name }} </label>
                                        </div>

                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-line"
                                                value="{{ \App\Models\SettingFee::get_by_where($form_init->id, $product_type->shopId)->cost ?? 0 }}"
                                                name="fee[]">
                                            <input type="text" hidden class="form-control form-control-line"
                                                value="{{ $product_type->shopId }}" name="shop_ids[]">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Tệp khách hàng</label>
                            <select class="form-control" name="account_type" id="type_customer">
                                <option value=""
                                    {{ isset($user->account_type) && $user->account_type > 0 ? 'disabled' : '' }}>-Chọn-
                                </option>
                                <option value="1" {{ $user->account_type == 1 ? 'selected' : '' }}>Tạo đơn
                                    thủ công</option>
                                <option value="2" {{ $user->account_type == 2 ? 'selected' : '' }}>Kênh quản lý bán
                                    hàng
                                </option>
                                {{-- <option value="3" {{ $user->account_type == 3 ? 'selected' : '' }}>Pancake
                                </option> --}}
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-group">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-control form-control-line" name="status">
                                    <option value="">?</option>
                                    <option value="{{ \App\Models\CoreUsers::STATUS_REGISTERED }}" {!! $form_init->status == \App\Models\CoreUsers::STATUS_REGISTERED ? 'selected="selected"' : '' !!}>
                                        Đang hoạt động
                                    </option>
                                    <option value="{{ \App\Models\CoreUsers::STATUS_NEWACCOUNT }}" {!! $form_init->status == \App\Models\CoreUsers::STATUS_NEWACCOUNT ? 'selected="selected"' : '' !!}>
                                        Tài khoản mới
                                    </option>
                                    <option value="{{ \App\Models\CoreUsers::STATUS_BANNED }}" {!! $form_init->status == \App\Models\CoreUsers::STATUS_BANNED ? 'selected="selected"' : '' !!}>
                                        Đã bị cấm
                                    </option>
                                </select>
                            </div>

                        </div>


                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-12 class_button">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </div>


        {{-- modal --}}
        <div class="modal fade" id="modalShopId" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn shopId để gán</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    @php
                        $userShopIds = json_decode($user->shopId ?? '[]', true);
                    @endphp

                    <div class="modal-body">
                        <div class="row">

                            <div class="mb-3">
                                <input type="text" id="searchShopId" class="form-control"
                                    placeholder="Tìm theo mã shopId. ">
                            </div>

                            @foreach ($branchs as $branch)
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input shopIdCheckbox"
                                            value="{{ $branch->shopId }}" id="shop_{{ $branch->shopId }}"
                                            {{ in_array($branch->shopId, $userShopIds) ? 'checked' : '' }}>
                                        <label for="shop_{{ $branch->shopId }}" class="form-check-label">
                                            <span class="badge {{ $branch->type == 2 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $branch->shopId }}</span> –
                                            {{ $branch->name }} <span class="badge bg-warning text-dark">
                                                {{ number_format($branch->from_weight, 0, ',', '.') }}kg</span>
                                            -
                                            <span
                                                class="badge bg-warning text-dark">{{ number_format($branch->to_weight, 0, ',', '.') }}kg
                                            </span>
                                            - <span
                                                class="badge {{ $branch->use_create_order == 1 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $branch->use_create_order == 1 ? 'Đang dùng xét shopid' : 'Không dùng' }}
                                            </span>

                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="submitShopIds()">Gán shopId</button>
                    </div>

                </div>
            </div>
        </div>

    </form>

@endsection

@section('script')
    <script>
        function submitShopIds() {
            let selectedShopIds = [];

            $('.shopIdCheckbox:checked').each(function() {
                selectedShopIds.push($(this).val());
            });

            if (selectedShopIds.length === 0) {
                alert('Vui lòng chọn ít nhất 1 shopId');
                return;
            }

            $.ajax({
                url: '{{ route('backend.brands.updateassignshopid', $user->id) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    shopId: selectedShopIds
                },

                success: function(res) {
                    alert('Gán thành công');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Lỗi khi gán shopId');
                    console.log(xhr.responseText);
                }
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('searchShopId');

            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase();
                const items = document.querySelectorAll('#modalShopId .form-check');

                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(keyword)) {
                        item.closest('.col-md-12').style.display = '';
                    } else {
                        item.closest('.col-md-12').style.display = 'none';
                    }
                });
            });
        });
    </script>

@endsection
