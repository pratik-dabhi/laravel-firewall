@if ($paginator->hasPages())
    <div class="pagination-container">

        {{-- Previous Page --}}
        @if ($paginator->onFirstPage())
            <span class="page-btn disabled">←</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">←</a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page-btn disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">→</a>
        @else
            <span class="page-btn disabled">→</span>
        @endif

    </div>
@endif

<style>
.pagination-container {
    display: flex;
    gap: 6px;
    justify-content: flex-end;
    margin-top: 10px;
}

.page-btn {
    padding: 8px 14px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    color: #1e293b;
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
    transition: all .2s;
}

.page-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.page-btn.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.page-btn.disabled {
    opacity: .4;
    cursor: not-allowed;
}
</style>
