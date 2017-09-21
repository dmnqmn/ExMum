import '@css/entry/photoNew'

import $ from 'jquery'
import 'bootstrap'

import '@js/common/header'

import axios from 'axios'

import Vue from 'vue'
import iView from 'iview'
import PhotoNew from '@js/components/PhotoNew'

import assign from 'object-assign'

Vue.use(iView)

new Vue({
    el: '.photo-new-container',

    components: {
        PhotoNew
    }
})
