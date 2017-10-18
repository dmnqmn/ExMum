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
        <div class="photo-gutter" id="photo-gutter"></div>
    @foreach ($photos as $photo)
    <div class="photo-wrapper photo-panel">
        <a href="/photo/{{ $photo['id'] }}">
            <img class="photo" src="{{ $photo['url'] }}">
        </a>
        <div class="photo-panel-info">
            <span class="photo-panel-title">{{ $photo['title'] }}</span> 发布于
            <a class="photo-panel-author" href="{{ $photo['author']->url() }}">{{ $photo['author']->user_name }}</a>
        </div>
        <div class="photo-panel-description">
            <p>{{ $photo['description'] }}</p>
        </div>
    </div>
    @endforeach
    </div>
</div>
<div class="new-photo-container">
    <new-photo></new-photo>
</div>
@endsection

@section('js')
@php js('home.js') @endphp
@endsection
