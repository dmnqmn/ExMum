import Vue from 'vue'
import Router from 'vue-router'
import Cookie from 'js-cookie'
import store from '@js/store'

import Home from '@js/components/Home'
import Settings from '@js/components/Settings'

Vue.use(Router)

const router = new Router({
    routes: [{
        name: 'settings',
        path: '/settings',
        component: Settings
    }, {
        name: 'home',
        path: '*',
        component: Home
    }]
})

export default router
