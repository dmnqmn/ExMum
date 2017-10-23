<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php dllCss() @endphp
    @section('css')
    @show
    <title>ExMum - @yield('title')</title>
</head>
<body>
@yield('main')
@php jsVars($sharedJsVars) @endphp
@php jsVars($jsVars) @endphp
@php dllJs() @endphp
@section('js')
@show
</body>
</html>
