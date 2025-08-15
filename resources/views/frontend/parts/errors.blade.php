@if($errors->all())
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <p> Vui lòng nhập đầy đủ các thông tin đánh dấu <span class="text-danger">*</span></p>
        @foreach($errors->all() as $error)
            <p> {{ $error }}</p>
        @endforeach
    </div>
@endif