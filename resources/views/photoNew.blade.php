@extends('layouts.main')

@section('title', '首页')

@section('css')
@php css('photoNew.css') @endphp
@endsection

@section('header')
    @parent
@endsection

@section('main')
<div class="photo-new-container">
</div>
@endsection

@section('js')
@php js('photoNew.js') @endphp
@endsection
