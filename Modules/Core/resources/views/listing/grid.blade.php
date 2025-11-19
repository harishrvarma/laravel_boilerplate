@php
$primaryKey = $me->primaryKey();
$dropdownColumns = [];
foreach ($me->columns() as $key => $column) {
    if (
        $key !== 'mass_ids' &&
        $key !== $primaryKey &&
        !in_array($column['name'], array_column($dropdownColumns, 'name'))
    ) {
        $dropdownColumns[$key] = $column;
    }
}
$module = $me->gridKey();
$hiddenColumns = session("hidden_columns_{$module}", []);
@endphp
@if(session('success_message'))
    <div class="alert alert-success">
        <div style="font-size:15px;">
            <strong>✅ Module Creator:</strong> {{ session('success_message') }}<br>
            Please copy the below command and execute it in your console to generate the scaffold module for 
            <strong>{{ session('module_code') }}</strong> based on the EAV structure.
        </div>

        <div class='mt-3'>
            @if(session('commands'))
                @foreach(session('commands') as $cmd)
                    <pre class="mt-2 mb-0 d-inline-block command-line">{{ $cmd }}</pre><br>
                @endforeach
            @endif
            <button id='copyCommand' class='btn btn-sm btn-primary ms-2'>
                <i class='fas fa-copy'></i>
            </button>
        </div>
    </div>
@endif
<div class="col-sm-6">
        <h2 class="mb-3">{{ $me->title() }}</h2>
    </div>
<div class="card shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between">
        {{-- Left side: select actions --}}
        @if ($me->massActions() && count($me->massActions()) > 1)
        <input type="hidden" name="selectAll" id="selectAllInput" value="{{ request()->input('selectAll', 0) }}">
        <div class="d-flex align-items-center gap-2">
            <a class="text-decoration-none select-all-link" data-value="1">Select All</a>
            <span>|</span>
            <a class="text-decoration-none select-all-link" data-value="0">Unselect All</a>
        </div>
        @endif

        {{-- Right side: custom buttons --}}
        @if($me->buttons())
            <div class="ms-auto">
            @foreach ($me->buttons() as $button)
                @php
                    $href = '#';
                    if (!empty($button['route'])) {
                        $href = $button['route'];
                    } elseif (!empty($button['method'])) {
                        $href = "javascript:void(0)";
                        $onclick = $button['method'];
                    } else {
                        $onclick = '';
                    }
                @endphp

                <a href="{{ $href }}" 
                @if(!empty($onclick)) onclick="{{ $onclick }}" @endif
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
        {{-- Right: Columns Dropdown --}}
        <input type="hidden" name="columns[]" value="">
        <div class="dropdown ml-auto">
            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="columnsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Columns
            </button>
            <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="columnsDropdown" style="min-width: 220px;">

                    @foreach($dropdownColumns as $key => $column)
                        <div class="form-check">
                            <input class="form-check-input column-checkbox" type="checkbox"
                                name="columns[]" value="{{ $key }}"
                                id="col_{{ $key }}"
                                {{ !in_array($key, $hiddenColumns) ? 'checked' : '' }}>
                            <label class="form-check-label" for="col_{{ $key }}">
                                {{ $column['label'] }}
                            </label>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary btn-sm mt-2 w-100">Apply</button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped table-bordered mb-0">
            <thead class="thead-light">
                <tr>
                    @foreach($me->columns() as $column)
                        @if(!in_array($column['name'], $hiddenColumns))
                            <th class="text-start">
                                <div class="d-flex align-items-center justify-content-between">
                                    {{-- Sortable --}}
                                    @if(isset($column['sortable']) && $column['sortable'])
                                        <a href="{{ $me->urlx(null, [
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
                                                    type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                                <i class="fas fa-filter"></i>
                                            </button>
                                            <div class="dropdown-menu p-3" style="min-width:250px;">
                                                {!! $me->getFilterBlock($column,$filter)->render() !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </th>
                        @endif
                    @endforeach

                    @if(!empty($me->actions()))
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    <tr>
                        @foreach($me->columns() as $column)
                            @if(!in_array($column['name'], $hiddenColumns))
                                <td>
                                    @if(isset($column['columnClassName']))
                                        {!! $me->getRendererBlock($column,$row)->render() !!}
                                    @else
                                        {{ $row->{$column['name']} }}
                                    @endif
                                </td>
                            @endif
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
                        <td colspan="{{ count($me->columns()) + (!empty($me->actions()) ? 1 : 0) }}" class="text-center">
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
        let massIds = jQuery('.mass_ids:checked').map(function() {
            return this.value;
        }).get();

        // Get visible columns from hidden input
        let visibleColumns = jQuery('#visible_columns').val();

        if(ele === 'mass_export'){
            // Export does not require selection
            actionUrl = urlx(actionUrl, {
                mass_ids: massIds.length ? massIds : [], // selected IDs or empty array
                visible_columns: visibleColumns
            });
        } else {
            // Other actions (like delete) require at least one checkbox
            if(massIds.length === 0){
                alert('Please select at least one ID');
                jQuery('#mass_action').val('none');
                return;
            }
            actionUrl = urlx(actionUrl, {mass_ids: massIds});
        }

        // Only set form action; submission is handled by the existing button
        jQuery('#main-form').attr('action', actionUrl);
    }

    $(document).on('keydown', '.dropdown-menu input, .dropdown-menu select', function(e){
        if(e.key === 'Enter'){
            e.preventDefault();
            $(this).closest('form').submit();
        }
    });

    $(document).ready(function() {
        $('.select-all-link').click(function(e) {
            e.preventDefault();            // prevent default link behavior
            var value = $(this).data('value');
            $('#selectAllInput').val(value);
            $('#main-form').submit();      // corrected ID
        });
    });

    $(document).on('click', '#copyCommand', function () {

        let commands = [];

        $('.command-line').each(function () {
            commands.push($(this).text().trim());
        });

        let finalText = commands.join("\n");

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(finalText)
                .then(() => alert('✅ Commands copied to clipboard!'))
                .catch(err => {
                    console.error(err);
                    alert('❌ Clipboard permission denied!');
                });
        } else {
            let tempInput = document.createElement("textarea");
            tempInput.value = finalText;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            alert('✅ Commands copied to clipboard!');
        }
    });

</script>
@endpush
