import '@css/entry/settings'

import $ from 'jquery'
import 'bootstrap'

import '@js/common/header'

import axios from 'axios'

import Vue from 'vue'
import iView from 'iview'
import UserSettings from '@js/components/UserSettings'

import assign from 'object-assign'

Vue.use(iView)

new Vue({
    el: '.setting-container',

    components: {
        UserSettings
    },

    data: {
        userinfo: assign({}, window.userinfo)
    }
})
