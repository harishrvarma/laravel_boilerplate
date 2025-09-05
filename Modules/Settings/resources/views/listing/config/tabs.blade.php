<div class="card shadow-sm">
    <div class="d-flex justify-content-end mt-2 me-2 gap-2">
        @if(canAccess('admin.config.add'))
            <a href="{{ $me->urlx('admin.config.add',['tab' => 'group']) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-folder-plus me-1"></i> Add Group
            </a>
        @endif
        @if(canAccess('admin.config.addFields'))
            <a href="{{ $me->urlx('admin.config.addFields',['tab' => 'fields', 'activeTab' => $me->activeTabKey()]) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-square me-1"></i> Add Field
            </a>
        @endif
    </div>
    <div class="card-body">
        <div class="row">
            {{-- Left Sidebar Tabs --}}
            <div class="col-md-3 pe-0">
                <div class="list-group" id="settings-list" role="tablist">
                    @foreach($me->tabs() as $key => $tab)
                        <a 
                            class="list-group-item list-group-item-action {{ ($me->activeTabKey() == $tab['key']) ? 'active' : '' }}" 
                            id="tab-{{ $tab['key'] }}-tab"
                            href="{{ urlx(null, ['tab' => $tab['key']]) }}"
                            role="tab"
                        >
                            <i class="fas fa-cog me-2"></i>
                            {{ $tab['title'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Right Side Content --}}
            <div class="col-md-9 ps-3">
                <div class="card border-2 border-dark shadow-sm h-100">
                    <div class="card-body" id="edit-content">
                        @php
                            $tab = $me->activeTab();
                            if ($tab) {
                                echo $me->block($tab['tabClassName'])->row($me->row())->render();
                            }
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(canAccess('admin.config.saveConfig'))
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    @endif
</div>
