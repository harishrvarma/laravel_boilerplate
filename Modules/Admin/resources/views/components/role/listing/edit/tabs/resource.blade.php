@php
    $tree = $me->buildTree($me->resources());
    if ($tree->count() === 1 && $tree->first()->code === 'admin') {
        $tree = $tree->first()->children;
    }
    $selected = $me->selected();
@endphp

<div class="card">
    <div class="card-header d-flex align-items-center">
        <h5 class="mb-0">Admin Resources</h5>
        <div class="ms-auto">
            <button type="button" class="btn btn-sm btn-outline-primary me-2" id="expandAll">Expand All</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="collapseAll">Collapse All</button>
        </div>
    </div>
    <div class="card-body">
        <ul class="list-unstyled">
            @foreach ($tree as $node)
                {{ $me->getResourceBlock($node, $selected)->render() }}
            @endforeach
        </ul>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    // expand/collapse per parent
    $(document).on('click', '.toggle', function () {
        let $this = $(this);
        let $childUl = $this.closest('li').children('ul');

        if ($childUl.hasClass('d-none')) {
            $childUl.removeClass('d-none');
            $this.text('[-]');
        } else {
            $childUl.addClass('d-none');
            $this.text('[+]');
        }
    });

    // expand all
    $('#expandAll').on('click', function () {
        $('ul.list-unstyled ul').removeClass('d-none');
        $('.toggle').text('[-]');
    });

    // collapse all
    $('#collapseAll').on('click', function () {
        $('ul.list-unstyled ul').addClass('d-none');
        $('.toggle').text('[+]');
    });

    // checkbox cascade
    $(document).on('change', 'input[type=checkbox][name="resources[]"]', function () {
        let isChecked = $(this).is(':checked');

        // children follow parent
        $(this).closest('li').find('ul input[type=checkbox][name="resources[]"]').prop('checked', isChecked);

        // bubble up to parents
        $(this).parents('ul').each(function () {
            let parentLi = $(this).closest('li');
            let allChecked = $(this).find('> li > label > input[type=checkbox][name="resources[]"]').length ===
                             $(this).find('> li > label > input[type=checkbox][name="resources[]"]:checked').length;

            parentLi.children('label').find('input[type=checkbox][name="resources[]"]').prop('checked', allChecked);
        });
    });
});
</script>
@endpush
