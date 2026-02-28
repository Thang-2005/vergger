@if ($paginator->hasPages())
<ul>
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <li class="disabled">
            <a href="javascript:void(0)"><i class="fas fa-angle-double-left"></i></a>
        </li>
    @else
        <li>
            <a href="javascript:void(0)" class="pagination-link" data-page="{{ $paginator->currentPage() - 1 }}">
                <i class="fas fa-angle-double-left"></i>
            </a>
        </li>
    @endif

    {{-- Page Numbers --}}
    @foreach ($elements as $element)
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="active"><a href="javascript:void(0)">{{ $page }}</a></li>
                @else
                    <li>
                        <a href="javascript:void(0)" class="pagination-link" data-page="{{ $page }}">
                            {{ $page }}
                        </a>
                    </li>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <li>
            <a href="javascript:void(0)" class="pagination-link" data-page="{{ $paginator->currentPage() + 1 }}">
                <i class="fas fa-angle-double-right"></i>
            </a>
        </li>
    @else
        <li class="disabled">
            <a href="javascript:void(0)"><i class="fas fa-angle-double-right"></i></a>
        </li>
    @endif
</ul>
@endif