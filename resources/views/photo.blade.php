@extends('layouts.main')

@section('title', '图片')

@section('css')
@php css('photo.css') @endphp
@endsection

@section('header')
    @parent
@endsection

@section('main')
<div class="photo-container">
</div>
@endsection

@section('js')
@php js('photo.js') @endphp
@endsection
