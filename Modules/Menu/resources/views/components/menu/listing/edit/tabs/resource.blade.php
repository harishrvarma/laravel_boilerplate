<ul class="ml-3">
    @foreach($me->buildTree($me->resources()) as $node)
        {!! $me->getResourceBlock($node, $me->selected())->render() !!}
    @endforeach
</ul>
@push('scripts')
    <script>    
       $(function() {
        $('.menu-checkbox').on('change', function() {
            var $this = $(this);

            // 1️⃣ Handle children: check/uncheck all nested children
            $this.closest('li').find('ul li input.menu-checkbox').prop('checked', $this.prop('checked'));

            // 2️⃣ Handle parents recursively
            function updateParent($checkbox) {
                var $parentUl = $checkbox.closest('ul').parent('li').closest('li, body');
                if ($parentUl.length) {
                    var $parentCheckbox = $checkbox.closest('ul').parent('li').children('label').children('input.menu-checkbox');
                    if ($parentCheckbox.length) {
                        // If all siblings are checked → parent checked
                        var allChecked = $checkbox.closest('ul').children('li').children('label').children('input.menu-checkbox')
                                        .length === $checkbox.closest('ul').children('li').children('label').children('input.menu-checkbox:checked').length;
                        $parentCheckbox.prop('checked', allChecked);
                        updateParent($parentCheckbox); // recursive up
                    }
                }
            }

            updateParent($this);
        });
    });
    </script>
@endpush