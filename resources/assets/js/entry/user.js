import '@css/entry/user'

import $ from 'jquery'
import 'bootstrap'

import headerVm from '@js/common/header'
import FollowList from '@js/components/FollowList'

import axios from 'axios'

import Vue from 'vue'
import iView from 'iview'

import assign from 'object-assign'

function setFollowingStatusTo(state) {
    window.visiting_user_rel.following = state
    if (state) {
        $('#follow').html('已关注')
            .addClass('btn-default')
            .removeClass('btn-primary')
    } else {
        $('#follow').html('关注')
            .addClass('btn-primary')
            .removeClass('btn-default')
    }
}

function setVisitingUserFollowedByCount(count) {
    window.visiting_user.followedBy = count
    $('#followed-by-count').html(window.visiting_user.followedBy)
}

async function follow() {
    $('#follow').attr('disabled', 'disabled')
    try {
        await axios.post('/user/follow', {
            follow_uid: window.visiting_user.id
        })
        setFollowingStatusTo(true)
        setVisitingUserFollowedByCount(window.visiting_user.followedBy + 1)
    } catch (error) {
        if (error.response.data) {
            switch (error.response.data.error) {
            case 'NOT_LOGGED':
                headerVm.login()
            default:
                headerVm.$Message.warning('关注用户失败，请稍后重试')
            }
        }
    }
    $('#follow').removeAttr('disabled')
}

async function unfollow() {
    $('#follow').attr('disabled', 'disabled')
    try {
        await axios.post('/user/unfollow', {
            follow_uid: window.visiting_user.id
        })
        setFollowingStatusTo(false)
        setVisitingUserFollowedByCount(window.visiting_user.followedBy - 1)
    } catch (error) {
        console.log(error);
        if (error.response.data) {
            switch (error.response.data.error) {
            case 'NOT_LOGGED':
                headerVm.login()
            default:
                headerVm.$Message.warning('取消关注失败，请稍后重试')
            }
        }
    }
    $('#follow').removeAttr('disabled')
}

$(() => {
    $('#follow').on('click', function () {
        if (window.visiting_user_rel.following) {
            unfollow()
        } else {
            follow()
        }
    })
})

new Vue({
    el: '#relationship-stat',

    data: {
        showFollowListModal: false,
        userListUrl: '',
        followListTitle: ''
    },

    components: {
        FollowList
    },

    methods: {
        showUserFollowedBy() {
            this.followListTitle = '关注者列表'
            this.userListUrl = `/user/${visiting_user.id}/followedBy`
            this.showFollowListModal = true
        },

        showUserFollowing() {
            this.followListTitle = '关注列表'
            this.userListUrl = `/user/${visiting_user.id}/following`
            this.showFollowListModal = true
        }
    }
})

