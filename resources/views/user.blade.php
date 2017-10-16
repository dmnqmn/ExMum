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
            <div class="user-info col-sm-9">
                <h1 class="user-name">{{ $visiting_user['user_name'] }}</h1>
                <p class="description">{{ $visiting_user['description'] ? $visiting_user['description'] : '这个人什么都没写' }}</p>
            </div>
            <div class="relationship col-sm-3">
                <div class="panel row" id="relationship-stat">
                    <div class="followed-by col-sm-6" @click="showUserFollowedBy">
                        <label>关注者</label><br>
                        <span id="followed-by-count">{{ $visiting_user['followedBy'] }}</span>
                    </div>
                    <div class="following col-sm-6" @click="showUserFollowing">
                        <label>关注了</label><br>
                        <span id="following-count">{{ $visiting_user['following'] }}</span>
                    </div>
                    <Modal :transfer="false" v-model="showFollowListModal" :footer-hide="true" :title="followListTitle">
                        <follow-list v-if="showFollowListModal" :user-list-url="userListUrl"></follow-list>
                    </Modal>
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
