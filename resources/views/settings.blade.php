@extends('layouts.main')

@section('title', "$user->user_name - 个人设置 ")

@section('css')
@php css('settings.css') @endphp
@endsection

@section('header')
    @parent
@endsection

@section('main')
<div class="setting-container">
    <user-settings :userinfo="userinfo"></user-settings>
</div>
@endsection

@section('js')
@php js('settings.js') @endphp
@endsection
