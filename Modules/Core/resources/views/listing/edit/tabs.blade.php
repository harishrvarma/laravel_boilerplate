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
                            href="{{ urlx(null,['tab' => $tab['key']]) }}"
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
        @foreach($me->tabs() as $tab)
            <div 
                class="tab-pane fade {{ ($me->activeTabKey() == $tab['key']) ? 'show active' : '' }}" 
                id="tab-{{ $tab['key'] }}" 
                role="tabpanel"
            >
                @if($me->activeTabKey() == $tab['key'])
                    {!! $me->block($tab['tabClassName'], $tab['tabData'] ?? [])->row($me->row())->render() !!}
                @endif
            </div>
        @endforeach
    </div>
</div>
