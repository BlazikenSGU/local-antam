@foreach ($products as $key => $value)
    <div class="col col-md-6 mb-4">
        <div class="card h-100">
            @if ($value->thumbnail)
                <img class="card-img-top" src="{{ $value->thumbnail->file_src }}" alt="..." />
            @else
                <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
            @endif

            <div class="card-body p-2">
                <div class="text-center">
                    <h5 class="fw-bolder">
                        {{ $value->title }}</h5>
                    {{ $value->price }}
                </div>
            </div>

            <div class="card-footer p-2 pt-0 border-top-0 bg-transparent">
                <div class="text-center">
                    <a class="btn btn-outline-dark mt-auto chooseProduct" id="chooseProduct_{{ $value->id }}"
                        data-price="{{ $value->price }}" data-title="{{ $value->title }}"
                        data-id="{{ $value->id }}" href="#">Thêm</a>
                </div>
            </div>
        </div>
    </div>
@endforeach
