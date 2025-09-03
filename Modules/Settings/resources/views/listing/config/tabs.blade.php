<div class="card shadow-sm">
        <div class="d-flex gap-2">
            <a href="{{ $me->urlx('settings.config.add',['tab' => 'group']) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-folder-plus me-1"></i> Add Config Group
            </a>
            <a href="{{ $me->urlx('settings.config.addFields',['tab' => 'fields', 'activeTab' => $me->activeTabKey()]) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-square me-1"></i> Add Config Field
            </a>
        </div>

    <div class="card-body">
        <div class="row">
            {{-- Left Sidebar Tabs --}}
            <div class="col-md-3 border-end">
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
            <div class="col-md-9">
                <div class="tab-content p-2" id="edit-content">
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

    <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </div>
</div>
