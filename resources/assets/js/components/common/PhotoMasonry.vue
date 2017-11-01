<template>
<div class="photo-masonry" ref="masonry" v-infinite-scroll="loadMore">
    <PhotoPanel
        v-for="(photo, index) in photos"
        :key="index"
        :photo="photo"
        @on-image-loaded="appended(index)"
        ref="photos"
        class="masonry-item"
        :class="{ 'item-hide': !photoLoaded[index] }"
    ></PhotoPanel>
    <div class="mask" v-show="!initDone"></div>
</div>
</template>

<script>
import Masonry from 'masonry-layout'
import infiniteScroll from 'vue-infinite-scroll'
import { getPhotos } from '@js/common/photo'
import PhotoPanel from './PhotoPanel'

const LOAD_SIZE = 15

export default {
    directives: {
        infiniteScroll
    },

    components: {
        PhotoPanel
    },

    data() {
        return {
            lastUpdateId: null,
            photos: [],
            photoLoaded: [],
            lastPhotoCount: 0,
            masonry: null,
            initLoading: true,
            initDone: false,
            loadedCount: 0,
            lastFetchedSize: 0,
            lastLoadTime: 0,
            loading: false
        };
    },

    methods: {
        initMasonry() {
            this.masonry = new Masonry(this.$refs.masonry, {
                itemSelector: '.masonry-item',
                columnWidth: 236,
                gutter: 24,
                fitWidth: true,
                transitionDuration: 0
            })
        },

        appended(index) {
            let element = this.$refs.photos[index].$el
            this.masonry.appended(element)
            this.loadedCount++
            this.photoLoaded.splice(index, 1, true)

            if (this.initLoading && this.loadedCount === this.lastFetchedSize) {
                this.initDone = true
                this.initLoading = false
            }
        },

        async loadMore() {
            if (this.loading || Date.now() - this.lastLoadTime < 1000) {
                return
            }

            let result
            try {
                this.loading = true
                result = await getPhotos(this.lastUpdateId, LOAD_SIZE)
                this.loading = false
            } catch (error) {
                this.$Message.warning('网络开小差了，待会再试试吧')
                this.loading = false
                return
            }

            if (result.lastUpdateId != null) {
                this.lastUpdateId = result.lastUpdateId
            }
            this.lastLoadTime = Date.now()

            if (result.photos.length === 0) {
                this.$Message.warning('再拉也没有更多图片啦')
                return
            }

            this.lastPhotoCount = this.photos.length
            this.photos = [...this.photos, ...result.photos]
            this.lastFetchedSize = result.photos.length
            this.photoLoaded = [...this.photoLoaded, ...new Array(result.photos.length)]
        }
    },

    mounted() {
        this.initMasonry()
        this.loadMore()
    }
}
</script>

<style lang="less" scoped>
.photo-masonry {
    position: relative;
    margin: 0 auto;
    width: 100%;
    min-height: 101%;

    .masonry-item {
        margin: 10px 0 10px 0;
    }

    .mask {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #fff;
    }

    .item-hide {
        opacity: 0;
    }
}
</style>
