@if ($paginator->total() > 0)
<div class="d-flex flex-wrap align-items-center gap-2 mb-0">

    {{-- Prev Button --}}
    <div>
        @if ($paginator->onFirstPage())
            <span class="btn btn-outline-secondary btn-sm disabled">‹ Prev</span>
        @else
            <a href="#" class="btn btn-outline-primary btn-sm pagination-link" data-page="{{ $paginator->currentPage() - 1 }}">‹ Prev</a>
        @endif
    </div>

    {{-- Page Input --}}
    <div class="d-flex align-items-center gap-2">
        <input type="number"
               id="page"
               name="page"
               value="{{ $paginator->currentPage() }}"
               min="1"
               max="{{ $paginator->lastPage() }}"
               class="form-control form-control-sm text-center"
               style="width:60px;">
    </div>
    <input type="hidden" name="page" id="pageInput" value="{{ $paginator->currentPage() }}">
    {{-- Next Button --}}
    <div>
        @if ($paginator->hasMorePages())
            <a href="#" class="btn btn-outline-primary btn-sm pagination-link" data-page="{{ $paginator->currentPage() + 1 }}">Next ›</a>
        @else
            <span class="btn btn-outline-secondary btn-sm disabled">Next ›</span>
        @endif
    </div>
    <span class="small">of {{ $paginator->lastPage() }} pages</span>
    <span class="text-muted">|</span>

    {{-- Per Page Dropdown --}}
    <div class="d-flex align-items-center gap-2">
        <label for="per_page" class="mb-0 small">View:</label>
        <select name="per_page" id="per_page" 
                class="form-select form-select-sm w-auto">
            @foreach($perPageOptions as $option)
                <option value="{{ $option }}" {{ $paginator->perPage() == $option ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>
    </div>
    <span class="text-muted">|</span>
    <span class="small">Total {{ $paginator->total() }} Records</span>
</div>
@endif
@push('scripts')
<script>
    $(document).ready(function() {
        // Pagination
        $('.pagination-link').click(function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            console.log(page);
            $('#pageInput').val(page);      // set hidden page input
            $('#main-form').submit();        // submit main form via POST
        });

         $('#per_page').change(function() {
            var value = $(this).val();
            $('#pageInput').val(1); // reset page to 1 when changing per_page
            $('#main-form').submit(); // submit via POST
        });
    });

</script>
@endpush