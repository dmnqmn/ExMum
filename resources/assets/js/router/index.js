import Vue from 'vue'
import Router from 'vue-router'
import Cookie from 'js-cookie'
import store from '@js/store'

import Home from '@js/components/Home'

Vue.use(Router)

const router = new Router({
    routes: [{
            name: 'home',
            path: '/',
            component: Home
    }]
})

export default router
