import Vue from 'vue'
import iView from 'iview'

Vue.use(iView)
import AccountManager from '@js/components/AccountManager'
import 'iview/dist/styles/iview.css'

export default new Vue({
    el: '.normal-site-header',

    components: {
        AccountManager
    },

    methods: {
        login() {
            this.$refs.accountManager.showModal = true
        }
    }
})
