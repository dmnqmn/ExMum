<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    @php dllCss() @endphp
    @section('css')
    @show
    <title>ExMum - @yield('title')</title>
</head>
<body>
@section('header')
@show

<div class="container">
    @yield('main')
</div>

@php dllJs() @endphp
@section('js')
@show
</body>
</html>
