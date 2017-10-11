@extends('layouts.main')

@section('title', "$user->user_name - 主页")

@section('css')
@php css('user.css') @endphp
@endsection

@section('header')
    @parent
@endsection

@section('main')
<div class="user-container">
</div>
@endsection

@section('js')
@php js('user.js') @endphp
@endsection
