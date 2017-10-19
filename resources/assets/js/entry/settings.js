import '@css/entry/settings'

import $ from 'jquery'
import 'bootstrap'

import '@js/common/header'

import axios from 'axios'

import Vue from 'vue'
import iView from 'iview'
import SettingTabs from '@js/components/Settings'

import assign from 'object-assign'

Vue.use(iView)

new Vue({
    el: '.settings-container',

    components: {
        SettingTabs
    },

    data: {
        userinfo: assign({}, window.userinfo)
    }
})
