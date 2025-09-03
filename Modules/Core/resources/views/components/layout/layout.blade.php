<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Admin Panel' }}</title>

    <!-- Bootstrap & AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    {{-- Additional styles --}}
    @foreach ($styles ?? [] as $style)
        <link rel="stylesheet" href="{{ asset($style) }}">
    @endforeach
</head>
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
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    @foreach ($scripts ?? [] as $script)
        <script src="{{ asset($script) }}"></script>
    @endforeach
    @stack('scripts')
</body>
</html>
