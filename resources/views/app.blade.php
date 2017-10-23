@extends('layouts.main')

@section('title', '首页')

@section('css')
@php css('app.css') @endphp
@endsection

@section('main')
<div class="app-container" id="app-container">
    <app></app>
</div>
@endsection

@section('js')
@php js('app.js') @endphp
@endsection
