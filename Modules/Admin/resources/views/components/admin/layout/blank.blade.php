<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- CSS --}}
    @foreach ($styles as $style)
        <link rel="stylesheet" href="{{ asset($style) }}">
    @endforeach
</head>
<body>


    {{-- Content --}}
    {{ $me->child('content')->render() }}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/jquery.ccc.1.0.js') }}"></script>
    @foreach ($scripts as $sctipt)
        <script src="{{ asset($script) }}"></script>
    @endforeach
    @stack('scripts')  
</body>
</html>
