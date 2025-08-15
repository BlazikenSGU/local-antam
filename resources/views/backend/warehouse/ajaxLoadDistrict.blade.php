@if(isset($district) && count($district) > 0)
    @foreach($district as $value)
        <option value="{{$value->id}}">{{ $value->name }}</option>
    @endforeach
@endif
