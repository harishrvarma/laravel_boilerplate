<!DOCTYPE html>
<html lang="en">
{{ $me->child('head')->render() }}
<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        {{-- Header / Sidebar --}}
        {{ $me->child('header')->render() }}

        {{-- Flash Messages --}}
        <div class="content-wrapper p-3">
            @foreach (['success', 'error', 'warning', 'info'] as $msg)
                @if(session($msg))
                    <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                        {{ session($msg) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endforeach

            {{-- Main Content --}}
            <section class="content">
                {{ $me->child('content')->render() }}
            </section>
        </div>

        {{-- Footer --}}
        {{ $me->child('footer')->render() }}
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    @foreach ($scripts ?? [] as $script)
        <script src="{{ asset($script) }}"></script>
    @endforeach
    @stack('scripts')
</body>
</html>
