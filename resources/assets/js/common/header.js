import Vue from 'vue'
import iView from 'iview'

Vue.use(iView)
import AccountManager from '@js/components/AccountManager'
import 'iview/dist/styles/iview.css'

new Vue({
    el: '.normal-site-header',
    components: {
        AccountManager
    },
    mounted() {

    }
})
