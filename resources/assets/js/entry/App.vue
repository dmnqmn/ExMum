<template>
<div class="app">
    <SiteHeader></SiteHeader>
    <router-view></router-view>
    <Modal v-model="signManagerShow" :footer-hide="true" title="登录/注册">
        <SignManager @register="handleLoginAndRegister" @login="handleLoginAndRegister"></SignManager>
    </Modal>
</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import SiteHeader from '@js/components/SiteHeader.vue'
import SignManager from '@js/components/SignManager.vue'

export default {
    components: {
        SiteHeader,
        SignManager
    },

    data() {
        return {
            signManagerShow: false
        }
    },

    computed: {
        ...mapGetters(['eventBus', 'identification'])
    },

    methods: {
        ...mapActions(['fetchIdentification']),

        handleLoginAndRegister(success) {
            if (success) {
                this.signManagerShow = false
            }
        }
    },

    async created() {
        this.eventBus.$on('message', (type, message) => this.$Message[type](message))
        await this.fetchIdentification()
        this.signManagerShow = !this.identification
    }
}
</script>

<style lang="less">
body {
    font-family: Helvetica Neue, Helvetica, PingFang SC, Hiragino Sans GB, Microsoft YaHei, Noto Sans CJK SC, WenQuanYi Micro Hei, Arial, sans-serif
}
</style>

<style lang="less" scoped>
.app {
    display: flex;
}
</style>
