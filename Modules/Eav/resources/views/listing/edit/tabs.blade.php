<div class="card">
    {{-- Tabs Header --}}
    @if(count($me->tabs()) > 1)
        <div class="card-header p-2">
            <ul class="nav nav-pills card-header-pills" role="tablist">
                @foreach($me->tabs() as $key => $tab)
                    <li class="nav-item" role="presentation">
                        <a 
                            class="nav-link {{ ($me->activeTabKey() == $tab['key']) ? 'active' : '' }}" 
                            id="tab-{{ $tab['key'] }}-tab"
                            href="{{ urlx(null, ['tab' => $tab['key']]) }}"
                            role="tab"
                        >
                            {{ $tab['title'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabs Body --}}
    <div class="card-body tab-content p-3">
        @php $languages = $me->getLanguages(); @endphp

        @foreach($me->tabs() as $tab)
            <div 
                class="tab-pane fade {{ ($me->activeTabKey() == $tab['key']) ? 'show active' : '' }}" 
                id="tab-{{ $tab['key'] }}" 
                role="tabpanel"
            >

                @if($tab['key'] == 'Attribute')
                <input type="hidden" name="attributes[attribute_id]" value="{{ $row->attribute_id ?? '' }}">
                    <div class="form-group">
                        <label for="entity_type_id">Entity Type</label>
                        <select id="entity_type_id" name="attributes[entity_type_id]" class="form-control" required>
                            <option value="">-- Select Entity Type --</option>
                            @foreach(\Modules\Eav\Models\Eav\Entity\Type::pluck('name','entity_type_id') as $id => $name)
                                <option value="{{ $id }}" {{ (isset($row->entity_type_id) && $row->entity_type_id == $id) ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="group_id">Group</label>
                        <select id="group_id" name="attributes[group_id]" class="form-control" required>
                            <option value="">-- Select Group --</option>
                            @if(isset($row->entity_type_id))
                                @foreach(\Modules\Eav\Models\Eav\Attribute\Group::where('entity_type_id', $row->entity_type_id)->pluck('code','group_id') as $id => $name)
                                    <option value="{{ $id }}" {{ (isset($row->group_id) && $row->group_id == $id) ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" name="attributes[code]" id="code" class="form-control" value="{{ $row->code ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label for="data_type">Data Type</label>
                        <select name="attributes[data_type]" id="data_type" class="form-control">
                            @php
                                $dataTypes = [
                                    'text' => 'Text',
                                    'textarea' => 'Textarea',
                                    'editor' => 'Editor',
                                    'select' => 'Select',
                                    'checkbox' => 'Checkbox',
                                    'number' => 'Number',
                                    'boolean' => 'Boolean',
                                    'file' => 'File',
                                    'date' => 'Date',
                                    'datetime' => 'Datetime',
                                    'hidden' => 'Hidden',
                                    'image' => 'Image',
                                    'password' => 'Password',
                                    'radio' => 'Radio',
                                    'time' => 'Time',
                                ];
                            @endphp

                            <option value="">-- Select Data Type --</option>
                            @foreach ($dataTypes as $key => $label)
                                <option value="{{ $key }}" {{ (isset($row->data_type) && $row->data_type == $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="data_model">Data Model</label>
                        <input type="text" name="attributes[data_model]" id="data_model" class="form-control" value="{{ $row->data_model ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label for="is_required">Is Required</label>
                        <select name="attributes[is_required]" id="is_required" class="form-control">
                            <option value="1" {{ (isset($row->is_required) && $row->is_required == 1) ? 'selected' : '' }}>Yes</option>
                            <option value="2" {{ (isset($row->is_required) && $row->is_required == 2) ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" name="attributes[position]" id="position" class="form-control" value="{{ $row->position ?? '' }}">
                    </div>

                    <!-- <div class="form-group">
                        <label for="default_value">Default Value</label>
                        <input type="text" name="attributes[default_value]" id="default_value" class="form-control" value="{{ $row->default_value ?? '' }}">
                    </div> -->

                    {{-- Labels Table --}}
                    <hr>
                    <h5 class="mt-4 mb-3">Labels</h5>
                    @if($languages->count())
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle bg-light">
                                <thead class="table-secondary">
                                    <tr>
                                        <th style="width: 200px;">Field</th>
                                        @foreach($languages as $lang)
                                            <th class="text-center">
                                                {{ $lang->label }}<br>
                                                <small>({{ strtoupper($lang->code) }})</small>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Label</strong></td>
                                        @foreach($languages as $lang)
                                            @php
                                                $translation = $row && $row->translations
                                                    ? $row->translations->firstWhere('lang_id', $lang->id)
                                                    : null;
                                            @endphp
                                            <td>
                                                <input type="hidden"
                                                    name="label[{{ $lang->code }}][attribute_translation_id]"
                                                    value="{{ $translation->translation_id ?? '' }}">

                                                <input type="text"
                                                    name="label[{{ $lang->code }}][display_name]"
                                                    class="form-control"
                                                    placeholder="Enter label"
                                                    value="{{ old('label.' . $lang->code . '.display_name', $translation->display_name ?? '') }}">
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endif

                {{-- =========================
                    OPTIONS TAB
                ========================== --}}
                @if($tab['key'] == 'options')
                    <h5 class="mt-4 mb-3">Options</h5>

                    @php
                        $existingOptions = $row->options ?? collect();
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle bg-light" id="options-table">
                            <thead class="table-secondary">
                                <tr>
                                    <th style="width:50px;">#</th>
                                    @foreach($languages as $lang)
                                        <th class="text-center">
                                            {{ $lang->label }}<br>
                                            <small>({{ strtoupper($lang->code) }})</small>
                                        </th>
                                    @endforeach
                                    <th style="width:100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($existingOptions->count())
                                    @foreach($existingOptions as $index => $option)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            @foreach($languages as $lang)
                                                @php
                                                    $translation = $option->translations->firstWhere('lang_id', $lang->id);
                                                @endphp
                                                <td>
                                                    <input type="hidden"
                                                        name="options[{{ $index }}][option_id]"
                                                        value="{{ $option->option_id ?? '' }}">
                                                    <input type="text"
                                                        name="options[{{ $index }}][label][{{ $lang->code }}]"
                                                        class="form-control"
                                                        value="{{ $translation->display_name ?? '' }}">
                                                </td>
                                            @endforeach
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger remove-option-btn">Remove</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>1</td>
                                        @foreach($languages as $lang)
                                            <td>
                                                <input type="text"
                                                    name="options[0][label][{{ $lang->code }}]"
                                                    class="form-control">
                                            </td>
                                        @endforeach
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger remove-option-btn">Remove</button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-sm btn-primary mt-2" id="add-option-btn">
                            Add More
                        </button>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@push('scripts')
    <script>
        let optionIndex = {{ $existingOptions->count() ?? 1 }};
        const languages = @json($languages);

        function createOptionRow(index) {
            const tr = document.createElement('tr');

            const tdIndex = document.createElement('td');
            tdIndex.innerText = index + 1;
            tr.appendChild(tdIndex);

            languages.forEach(lang => {
                const td = document.createElement('td');
                td.innerHTML = `<input type="text" name="options[${index}][label][${lang.code}]" class="form-control">`;
                tr.appendChild(td);
            });

            const tdAction = document.createElement('td');
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm btn-danger remove-option-btn';
            removeBtn.innerText = 'Remove';
            removeBtn.addEventListener('click', () => tr.remove());
            tdAction.appendChild(removeBtn);
            tr.appendChild(tdAction);

            return tr;
        }

        document.getElementById('add-option-btn').addEventListener('click', function() {
            const tbody = document.querySelector('#options-table tbody');
            const newRow = createOptionRow(optionIndex);
            tbody.appendChild(newRow);
            optionIndex++;
        });

        document.querySelectorAll('.remove-option-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                btn.closest('tr').remove();
            });
        });

        $(document).ready(function() {
            $('#entity_type_id').on('change', function() {
                var entityTypeId = $(this).val();
                var $groupSelect = $('#group_id');

                $groupSelect.html('<option value="">Loading...</option>');

                if (!entityTypeId) {
                    $groupSelect.html('<option value="">-- Select Group --</option>');
                    return;
                }

                $.ajax({
                    url: '/admin/eav/attributes/byentity/' + entityTypeId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $groupSelect.empty().append('<option value="">-- Select Group --</option>');
                        $.each(data, function(id, name) {
                            $groupSelect.append('<option value="' + id + '">' + name + '</option>');
                        });
                    },
                    error: function() {
                        $groupSelect.html('<option value="">Error loading groups</option>');
                    }
                });
            });
        });
    </script>
@endpush