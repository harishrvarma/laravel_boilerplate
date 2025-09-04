<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Login' }}</title>

    <!-- Bootstrap & AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    {{-- Additional styles --}}
    @foreach ($styles ?? [] as $style)
        <link rel="stylesheet" href="{{ asset($style) }}">
    @endforeach
</head>
<body class="hold-transition login-page">

        <!-- Flash Messages -->
        @foreach (['success', 'error', 'warning', 'info'] as $msg)
            @if(session($msg))
                <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                    {{ session($msg) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endforeach

        <!-- Login Card -->
        <div class="card">
            <div class="card-body login-card-body">
                {{ $me->child('content')->render() }}
            </div>
        </div>

    <!-- Scripts -->
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
