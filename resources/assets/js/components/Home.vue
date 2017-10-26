<template>
<Row class="home">
    <Col span="22" push="1" class="container">
    </Col>
</Row>
</template>

<script>
import { getPhotos } from '@js/common/photo'

export default {
    data() {
        return {
            lastUpdateId: null,
            photos: []
        };
    },

    methods: {
        async loadMore() {
            try {
                let result = await getPhotos(this.lastUpdateId, 15)
                this.lastUpdateId = result.lastUpdateId
                this.photos = [...this.photos, ...result.photos]
            } catch (error) {
                this.$Message.warning('网络开小差了，待会再试试吧')
            }
        }
    },

    created() {
        this.loadMore()
    }
}
</script>

<style lang="less" scoped>
.home {
    flex: 1;

    .container {
        height: 100%;
    }
}
</style>
