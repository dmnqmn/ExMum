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
    <div class="panel panel-default info-panel">
        <div class="panel-body row">
            <div class="user-info col-sm-8">
                <h1 class="user-name">{{ $visiting_user['user_name'] }}</h1>
                <p class="description">{{ $visiting_user['description'] ? $visiting_user['description'] : '这个人什么都没写' }}</p>
            </div>
            <div class="relationship col-sm-4">
                <div class="following">
                    <label>关注了</label>
                    <span id="following-count">{{ $visiting_user['following'] }}</span>
                </div>
                <div class="followedBy">
                    <label>关注者</label>
                    <span id="followed-by-count">{{ $visiting_user['followedBy'] }}</span>
                </div>
                <div class="follow">
                    @if ($visiting_user_rel['following'])
                        <button class="btn btn-default follow-btn" id="follow">已关注</button>
                    @else
                        <button class="btn btn-primary follow-btn" id="follow">关注</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@php js('user.js') @endphp
@endsection
