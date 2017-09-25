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
    <div class="photo-wrapper">
        <a href="/photo/{{ $photo['id'] }}">
            <img class="photo" src="{{ $photo['url'] }}">
            <h5>{{ $photo['title'] }}</h5>
            <p>{{ $photo['description'] }}</p>
        </a>
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
