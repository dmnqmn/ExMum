@extends('layouts.main')

@section('title', '分享')

@section('css')
@php css('photoNew.css') @endphp
@endsection

@section('header')
    @parent
@endsection

@section('main')
<div class="photo-new-container">
    <photo-new :domain="'{{ config('app.base_domain') }}'"></photo-new>
</div>
@endsection

@section('js')
@php js('photoNew.js') @endphp
@endsection
