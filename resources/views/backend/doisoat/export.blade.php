<table>
    <thead>
    <tr style="background: #1844c7; border: 1px solid">
        @if(Auth::guard('backend')->check() && Auth::guard('backend')->user()->id == 168)
            <th scope="col">Đối soát</th>
        @endif
        <th scope="col">Mã đơn hàng</th>
        <th scope="col">Mã giao 1 phần</th>
        <th scope="col">Mã đơn hàng riêng</th>
        <th scope="col">Bảng giá</th>
        <th scope="col">ID User </th>
        <th scope="col">Ngày tạo đơn</th>
        <th scope="col">Ngày giao/hoàn thành công</th>
        <th scope="col">Tình trạng thu tiền GTB</th>
        <th scope="col">Trạng thái</th>
        <th scope="col">COD</th>
        <th scope="col">Tiền GTB - Thu tiền</th>
        <th scope="col">Phí giao hàng</th>
        <th scope="col">Phí giao lại</th>
        <th scope="col">Phí khai giá</th>
        <th scope="col">Phí hoàn hàng</th>
        <th scope="col">Phí giao 1 phần</th>
        <th scope="col">Tổng phí</th>
        <th scope="col">Tổng đối soát </th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $item)
        <tr>
            @if(Auth::guard('backend')->check() && Auth::guard('backend')->user()->id == 168)
                <td style="width: 200px">{{$item->doisoat ?? ''}}</td>
            @endif
            <td style="width: 200px">{{$item->OrderCode ?? ''}}</td>
            <td>{{$item->PartialReturnCode ?? ''}}</td>
            <td>{{$item->order_code_custom ?? ''}}</td>
            <td>{{ \App\Models\Branch::where('shopId',$item->ShopID)->first()->name_origi ?? ''}}</td>
            <td>{{\App\Models\CoreUsers::find($item->IDUser)->phone ?? ''}}</td>
            <td>{{($item->created_at ?? '')}}</td>
            <td>{{$item->ngaygiaohoanthanhcong ?? ''}}</td>
            <td>
                @if(!empty($item->tinhtrangthutienGTB))
                    {{ $item->tinhtrangthutienGTB == 1 ? 'Thành Công' : 'Thất bại' }}
                @endif

            </td>
            <td>{{ \App\Models\StatusName::where('key', $item->statusName)->first()->name ?? ''}}</td>
            <td>{{$item->CODAmount  ?? ''}}</td>
            <td>{{ (int) str_replace(['.', ','], '', $item->cod_failed_amount)  ?? ''}}</td>
            <td>{{$item->MainService ?? ''}}</td>
            <td>{{$item->R2S ?? ''}}</td>
            <td>{{$item->Insurance ?? ''}}</td>
            <td>{{$item->Return ?? ''}}</td>
            <td>{{$item->phigiao1lan ?? ''}}</td>
            <td>{{$item->tongphi ?? ''}}</td>
            <td>{{$item->tongdoisoat ?? ''}}</td>
        </tr>
    @endforeach


    </tbody>


</table>

