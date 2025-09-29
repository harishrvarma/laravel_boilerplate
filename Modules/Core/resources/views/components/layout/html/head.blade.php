<head>
    <meta charset="UTF-8">
    <title>{{ $me->title() ?? 'Admin Panel' }}</title>

    <!-- Bootstrap & AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    {{-- Additional styles --}}
    @foreach ($me->styles() ?? [] as $style)
        <link rel="stylesheet" href="{{ asset($style) }}">
    @endforeach
</head>