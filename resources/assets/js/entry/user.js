import '@css/entry/user'

import $ from 'jquery'
import 'bootstrap'

import headerVm from '@js/common/header'

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

async function follow() {
    $('#follow').attr('disabled', 'disabled')
    try {
        await axios.post('/user/follow', {
            follow_uid: window.visiting_user.id
        })
        setFollowingStatusTo(true)
    } catch (error) {
        if (error.response.data) {
            switch (error.response.data.error) {
            case 'NOT_LOGGED':
                headerVm.login()
            default:
            }
        }
        setFollowingStatusTo(false)
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
    } catch (error) {
        if (error.response.data) {
            switch (error.response.data.error) {
            case 'NOT_LOGGED':
                headerVm.login()
            default:
            }
        }
        setFollowingStatusTo(true)
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
