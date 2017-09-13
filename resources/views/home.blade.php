@extends('layouts.main')

@section('title', '首页')

@section('css')
@php css('home.css') @endphp
@endsection

@section('header')
    @parent
@endsection

@section('main')
<div class="home-container">
    <div class="photo-masonry" id="photo-masonry">
        <div class="photo-sizer" id="photo-sizer"></div>
    @foreach ($photos as $photo)
        <div class="photo-wrapper">
            <img class="photo" src="{{ $photo['url'] }}">
        </div>
    @endforeach
    </div>
</div>
@endsection

@section('js')
@php js('home.js') @endphp
@endsection
