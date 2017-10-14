@extends('layouts.main')

@section('title', $visiting_user['user_name'] . ' - 主页')

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
            <h1 class="user-name">{{ $visiting_user['user_name'] }}</h1>
            <div class="relationship">
                @if ($visiting_user_rel['following'])
                    <button class="btn btn-default follow-btn" id="follow">已关注</button>
                @else
                    <button class="btn btn-primary follow-btn" id="follow">关注</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@php js('user.js') @endphp
@endsection
