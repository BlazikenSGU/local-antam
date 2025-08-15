@if ($paginator->lastPage() > 1)
    <div class="pro-pagination-style text-center mt-10">
        <ul>
            @if($paginator->currentPage() != 1)
                <li>
                    <a href="{{ $paginator->url(1) }}" class="prev" title="Trang trước"><i class="icon-arrow-left"></i></a>
                </li>
            @endif
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                <li >
                    <a class="{{ ($paginator->currentPage() == $i) ? 'active' : '' }}" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>

            @endfor
                @if($paginator->currentPage() != $paginator->lastPage())
                    <li>
                        <a href="{{ $paginator->url($paginator->currentPage()+1) }}" class="next" title="Trang tiếp theo"><i class="icon-arrow-right"></i></a>
                    </li>
                @endif
        </ul>
        <ul>


        </ul>
    </div>
@endif
