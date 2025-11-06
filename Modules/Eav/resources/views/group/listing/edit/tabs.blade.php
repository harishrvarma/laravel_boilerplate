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
                <input type="hidden" name="groups[group_id]" value="{{ $row->group_id ?? '' }}">
                    <div class="form-group">
                        <label for="entity_type_id">Entity Type</label>
                        <select id="entity_type_id" name="groups[entity_type_id]" class="form-control" required>
                            @foreach(\Modules\Eav\Models\Eav\Entity\Type::pluck('name','entity_type_id') as $id => $name)
                                <option value="{{ $id }}" {{ (isset($row->entity_type_id) && $row->entity_type_id == $id) ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" name="groups[code]" id="code" class="form-control" value="{{ $row->code ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" name="groups[position]" id="position" class="form-control" value="{{ $row->position ?? '' }}">
                    </div>

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
                                                    name="label[{{ $lang->code }}][name]"
                                                    class="form-control"
                                                    placeholder="Enter label"
                                                    value="{{ old('label.' . $lang->code . '.name', $translation->name ?? '') }}">
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
