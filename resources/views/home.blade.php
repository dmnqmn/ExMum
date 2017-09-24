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
    <a href="/photo/{{ $photo['id'] }}">
        <div class="photo-wrapper">
            <img class="photo" src="{{ $photo['url'] }}">
            <h3>{{ $photo['name'] }}</h3>
            <p>{{ $photo['description'] }}</p>
        </div>
    </a>
    @endforeach
    </div>
</div>
@endsection

@section('js')
@php js('home.js') @endphp
@endsection
