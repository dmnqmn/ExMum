<template>
<div>
    <Modal :value="!identificationFetching && !identification" :footer-hide="true">
        <SignManager></SignManager>
    </Modal>
    <router-view></router-view>
</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import SignManager from '@js/components/SignManager.vue'

export default {
    components: {
        SignManager
    },

    data() {
        return {
            identificationFetching: true
        }
    },

    computed: {
        ...mapGetters(['eventBus', 'identification'])
    },

    methods: {
        ...mapActions(['fetchIdentification'])
    },

    async created() {
        this.eventBus.$on('message', (type, message) => this.$Message[type](message))
        await this.fetchIdentification()
        this.identificationFetching = false
    }
}
</script>
