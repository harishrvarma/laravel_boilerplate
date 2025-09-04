<div class="col-sm-6">
        <h2 class="mb-3">{{ $me->title() }}</h2>
    </div>
<div class="card shadow-sm">
    <div class="card-header d-flex align-items-center">
        @if($me->buttons())
            <div class="ms-auto">
                @foreach ($me->buttons() as $button)
                    <a href="{{ $button['route'] ?? '#' }}" 
                    class="btn btn-primary btn-sm">
                    {{ $button['label'] ?? '' }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3 p-2">
        {{-- Left: Pagination (stretchable) --}}
        @if($me->allowedPagination())
            <div class="flex-grow-1 d-flex flex-wrap align-items-center gap-2 mb-2 mb-md-0">
                {!! $me->pager()->render(['class' => 'pagination-card']) !!}

                {{-- Pipe Separator + Mass Actions --}}
                @if ($me->massActions() && count($me->massActions()) > 1)
                    <span class="text-muted">|</span>
                    <div class="d-flex align-items-center gap-2">
                        <label for="mass_action" class="mb-0 fw-semibold">Mass Action:</label>
                        <select name="mass_action" id="mass_action" 
                                class="form-select form-select-sm w-auto"
                                onchange="massAction(this.value)">
                            @foreach($me->massActions() as $massAction)
                                <option id="{{ $massAction['value'] }}" 
                                        data-url="{{ $massAction['url'] }}" 
                                        value="{{ $massAction['value'] }}">
                                    {{ $massAction['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Table --}}
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped table-bordered mb-0">
            <thead class="thead-light">
                <tr>
                    @foreach($me->columns() as $column)
                    <th class="text-start">
                        <div class="d-flex align-items-center justify-content-between">
                            {{-- Sortable --}}
                            @if(isset($column['sortable']) && $column['sortable'])
                                <a href="{{ $me->urlx(null,[
                                    'sortcolumn' => $column['name'],
                                    'sortdir'    => ($me->sortColumn() === $column['name'] && $me->sortDir() === 'asc') ? 'desc' : 'asc'
                                ]) }}" class="text-dark text-decoration-none d-flex align-items-center">
                                    <span>{{ $column['label'] }}</span>
                                    @if(request('sortcolumn') == $column['name'])
                                        <small class="ms-1">{{ request('sortdir') == 'asc' ? '▲' : '▼' }}</small>
                                    @endif
                                </a>
                            @else
                                <span>{{ $column['label'] }}</span>
                            @endif

                            {{-- Filter --}}
                            @php $filter = $me->filter($column['name']); @endphp
                            @if($filter)
                                <div class="dropdown ms-2">
                                    <button class="btn btn-sm {{ request('filter.' . $column['name']) ? 'btn-warning' : 'btn-secondary' }}"
                                            type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                    <div class="dropdown-menu p-3" style="min-width:250px;">
                                        {!! $me->getFilterBlock($column,$filter)->render() !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </th>
                    @endforeach

                    @if(!empty($me->actions()))
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse($me->rows() as $row)
                    <tr>
                        @foreach($me->columns() as $column)
                            <td>
                                @if(isset($column['columnClassName']))
                                    {!! $me->getRendererBlock($column,$row)->render() !!}
                                @else
                                    {{ $row->{$column['name']} }}
                                @endif
                            </td>
                        @endforeach

                        @if(!empty($me->actions()))
                            <td class="text-center">
                                @foreach($me->actions() as $action)
                                    {!! $me->getButtonHtml($action, $row) !!}
                                @endforeach
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($me->columns()) + (empty($me->actions()) ? 0 : 1) }}" class="text-center">
                            No records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    function urlx(route = null, params = {}, reset = false, fragment = null) {
        let currentUrl = new URL(window.location.href);
        if (route) {
            currentUrl.pathname = "/" + route.replace(/\./g, "/");
            if (reset) {
                currentUrl.search = "";
            }

        } else if (reset) {
            currentUrl.search = "";
        }
    
        let searchParams = new URLSearchParams(currentUrl.search);
    
        // Handle parameters

        for (let key in params) {
            if (params[key] === null || params[key] === undefined) {
                searchParams.delete(key); // remove param
            } else {
                searchParams.set(key, params[key]); // add/update param
            }
        }
        currentUrl.search = searchParams.toString();
    
        // Add fragment (#anchor)
        if (fragment) {
            currentUrl.hash = fragment;
        }

        return currentUrl.toString();

    }
function massAction(ele){
    let actionUrl = jQuery("#"+ele).data('url');
    if(actionUrl){
        let massIds = jQuery('.mass_ids:checked').map(function() {
            return this.value;
        }).get();
        if (massIds.length === 0) {
            alert('Please select at least one ID');
            jQuery('#mass_action').val('none');
            return;
        }
        actionUrl = urlx(actionUrl,{mass_ids:massIds});
    }else{
        actionUrl = urlx(null,{mass_ids:null})
    }
    jQuery('#main-form').attr('action', actionUrl);
}
</script>
@endpush
