@if ($paginator->total() > 0)
<div class="d-flex flex-wrap align-items-center gap-4 mb-0">

    {{-- Prev Button --}}
    <div>
        @if ($paginator->onFirstPage())
            <span class="btn btn-outline-secondary btn-sm disabled">‹ Prev</span>
        @else
            <a href="{{ $grid->urlx(null, ['page' => $paginator->currentPage() - 1]) }}" 
               class="btn btn-outline-primary btn-sm">‹ Prev</a>
        @endif
    </div>

    {{-- Page Input --}}
    <div class="d-flex align-items-center gap-3">
        <span class="small">Page</span>
        <input type="number"
               id="page"
               name="page"
               value="{{ $paginator->currentPage() }}"
               min="1"
               max="{{ $paginator->lastPage() }}"
               class="form-control form-control-sm text-center"
               style="width:60px;">
        <span class="small">of {{ $paginator->lastPage() }}</span>

            {{-- Record Info --}}
        <div class="small text-muted ms-3">
            {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} of {{ $paginator->total() }} records
        </div>
    </div>

    {{-- Next Button --}}
    <div>
        @if ($paginator->hasMorePages())
            <a href="{{ $grid->urlx(null, ['page' => $paginator->currentPage() + 1]) }}" 
               class="btn btn-outline-primary btn-sm">Next ›</a>
        @else
            <span class="btn btn-outline-secondary btn-sm disabled">Next ›</span>
        @endif
    </div>

    {{-- Per Page Dropdown --}}
    <div class="d-flex align-items-center gap-1">
        <label for="per_page" class="mb-0 small">Per Page:</label>
        <select name="per_page" id="per_page" 
                class="form-select form-select-sm w-auto"
                onchange="window.location.href=urlx(null,{per_page:this.value,page:1})">
            @foreach($perPageOptions as $option)
                <option value="{{ $option }}" {{ $paginator->perPage() == $option ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>
    </div>

</div>
@endif
