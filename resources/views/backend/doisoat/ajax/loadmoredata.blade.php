
<?php $start = $start; ?>
@foreach($data as $key => $item)
        <?php $start = $start + 1; ?>
    <tr>
        <td scope="row">{{ $start }}</td>
        <td>{{$item->doisoat ?? ''}}</td>
        <td style="width: 200px">{{$item->OrderCode ?? ''}}</td>
        <td>{{$item->PartialReturnCode ?? ''}}</td>
        <td>{{$item->order_code_custom ?? ''}}</td>
        <td>{{ \App\Models\Branch::where('shopId',$item->ShopID)->first()->name_origi ?? ''}}</td>
        <td>{{ \App\Models\CoreUsers::find($item->IDUser)->phone ?? ''}}</td>
        <td>{{($item->created_at ?? '')}}</td>
        <td>{{$item->ngaygiaohoanthanhcong ?? ''}}</td>
        <td>
            @if(!empty($item->tinhtrangthutienGTB))
                {{ $item->tinhtrangthutienGTB == 1 ? 'Thành Công' : 'Thất bại' }}
            @endif

        </td>
        <td>{{ \App\Models\StatusName::where('key', $item->statusName)->first()->name ?? ''}}</td>
        <td>{{$item->CODAmount ?? ''}}</td>
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
