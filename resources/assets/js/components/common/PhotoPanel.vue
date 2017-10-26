<template>
<div class="photo-panel">
    <div class="photo-image">
        <img :src="photo.url" @load="onImageLoaded">
    </div>
    <div class="photo-meta">
        <router-link :to="`/user/${photo.author.id}`">{{ photo.author.username }}</router-link>
        <router-link :to="`/gallery/${photo.gallery.id}`" v-if="photo.gallery">{{ photo.gallery.name }}</router-link>
    </div>
    <div class="photo-description">
        <span>{{ photo.title }}</span>
        <span>{{ photo.description }}</span>
    </div>
</div>
</template>

<script>
import { Photo } from '@js/models/Photo'

export default {
    props: {
        photo: {
            type: Photo,
            required: true
        }
    },

    methods: {
        onImageLoaded() {
            this.$emit('on-image-loaded')
        }
    }
}
</script>

<style lang="less" scoped>
.photo-panel {
    width: 236px;
    border: 1px solid #ff0;

    img {
        width: 100%;
    }
}
</style>
