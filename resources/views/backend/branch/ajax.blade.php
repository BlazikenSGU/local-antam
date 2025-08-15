@if(!empty($warehouse))
    @foreach($warehouse as $key =>$value)
        <option
            value="{{ $value->id }}"
            @if($value->id == $warehouse_id) selected="selected" @endif
        >
            {{ $value->name }}
        </option>
    @endforeach
@endif
