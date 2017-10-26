<template>
<div class="photo-masonry" v-infinite-scroll="loadMore">
    <img :src="photo.url" v-for="photo in photos">
</div>
</template>

<script>
import infiniteScroll from 'vue-infinite-scroll'
import { getPhotos } from '@js/common/photo'

export default {
    directives: {
        infiniteScroll
    },

    data() {
        return {
            lastUpdateId: null,
            photos: []
        };
    },

    methods: {
        async loadMore() {
            let result

            try {
                result = await getPhotos(this.lastUpdateId, 15)
            } catch (error) {
                this.$Message.warning('网络开小差了，待会再试试吧')
            }

            this.lastUpdateId = result.lastUpdateId

            if (result.photos.length === 0) {
                this.$Message.warning('再拉也没有更多图片啦')
                return
            }

            this.photos = [...this.photos, ...result.photos]
        }
    },

    created() {
        this.loadMore()
    }
}
</script>

<style lang="less" scoped>
.photo-masonry {
    width: 100%;
    height: 100%;
    background-color: #ccc;
    overflow-y: scroll;
}
</style>
