@if(Session::has('msg'))
    <div class="alert alert-{{ Session::get('msg')[0]}} auto-close" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>

        @if(is_array(Session::get('msg')[1]))
            @foreach(Session::get('msg')[1] as $msg)
                <p> {{ $msg }}</p>
            @endforeach
        @else
            <p> {{ Session::get('msg')[1] }}</p>
        @endif
    </div>
@endif

@if(Session::has('popup'))
    @section('script')
    <script>
        $(document).ready(function () {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: '{{ Session::get('popup')[1] }}' // Chú ý dấu ngoặc kép ở đây
            });
        });

    </script>
    @endsection
@endif
