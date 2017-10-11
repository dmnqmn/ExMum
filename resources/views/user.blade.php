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
    <div class="panel panel-default">
        <div class="panel-body">
            <h3>{{ $user->user_name }}</h3>
        </div>
    </div>
</div>
@endsection

@section('js')
@php js('user.js') @endphp
@endsection
