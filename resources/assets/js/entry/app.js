import '@css/entry/app'

import assign from 'object-assign'
import axios from 'axios'

import NewPhoto from '../components/NewPhoto'

// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import iView from 'iview'
import { sync } from 'vuex-router-sync'
import 'iview/dist/styles/iview.css'
import router from '@js/router'
import store from '@js/store'
sync(store, router);

import App from './App.vue'

Vue.config.productionTip = false;

Vue.use(iView);

new Vue({
    el: '#app-container',
    components: { App },
    router,
    store
});
