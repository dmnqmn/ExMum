<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?= dllCss() ?>
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

<?= dllJs() ?>
@section('js')
@show
</body>
</html>
