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
@section('header')
@component('components.header')
@endcomponent
@show

<div class="main">
    @yield('main')
</div>

@php jsVars($jsVars) @endphp
@php dllJs() @endphp
@section('js')
@show
</body>
</html>
