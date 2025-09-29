@php
    $tree = $me->buildTree($me->menus());
@endphp

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Menu Tree</h5>
    </div>
    <div class="card-body">
            @csrf
            <ul class="list-unstyled menu-tree">
                @foreach ($tree as $node)
                    {{ $me->getMenuBlock($node)->render() }}
                @endforeach
            </ul>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function(){

    function serializeTree($ul) {
        let result = [];
        $ul.children('li').each(function(){
            let $li = $(this);
            let id = $li.data('id');
            let checked = $li.find('> .form-check input[type=checkbox]').is(':checked') ? 1 : 0;

            let node = {id: id, checked: checked};

            let $children = $li.children('ul');
            if($children.length) {
                node.children = serializeTree($children);
            }

            result.push(node);
        });
        return result;
    }

    $('#saveBtn').on('click', function(e){
        e.preventDefault();

        let treeData = serializeTree($('.menu-tree').first());

        // Remove any previous hidden input
        $('input[name="tree"]').remove();

        // Add new hidden input with JSON
        $('<input>').attr({
            type: 'hidden',
            name: 'tree',
            value: JSON.stringify(treeData)
        }).appendTo($(this).closest('form'));

        $(this).closest('form').submit();
    });

});
$(document).on('change', '.menu-checkbox', function () {
    let isChecked = $(this).prop('checked');
    let $li = $(this).closest('li');

    // 🔽 Cascade DOWN
    $li.find('.menu-checkbox').prop('checked', isChecked);

    // 🔼 Cascade UP
    function updateParent($checkbox) {
        let parentId = $checkbox.data('parent');
        if (!parentId) return;

        let $parent = $('.menu-checkbox[data-id="' + parentId + '"]');
        let $siblings = $('.menu-checkbox[data-parent="' + parentId + '"]');

        if ($siblings.length) {
            if ($siblings.filter(':checked').length === $siblings.length) {
                // all children checked → check parent
                $parent.prop('checked', true);
            } else if ($siblings.filter(':checked').length === 0) {
                // none checked → uncheck parent
                $parent.prop('checked', false);
            }
            // recursively update parent of parent
            updateParent($parent);
        }
    }

    updateParent($(this));
});

</script>
@endpush
