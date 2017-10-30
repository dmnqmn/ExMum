<template>
<div class="photo-panel">
    <div class="photo-image">
        <img :src="photo.url" @load="onImageLoaded">
    </div>
    <div class="photo-meta">
        <router-link class="photo-author" :to="`/user/${photo.author.id}`">{{ photo.author.username }}</router-link> 发布于
        <router-link class="photo-gallery" :to="`/gallery/${photo.gallery.id}`" v-if="photo.gallery">{{ photo.gallery.name }}</router-link>
    </div>
    <div class="photo-info">
        <h2 class="photo-title">{{ photo.title }}</h2>
        <span class="photo-description">{{ photo.description }}</span>
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
    box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.3);

    img {
        width: 100%;
    }

    .photo-meta,
    .photo-info {
        padding: 6px 8px;
    }

    .photo-meta {
        font-size: 14px;
    }

    .photo-info {
        background-color: #eee;

        .photo-title {
            font-size: 16px;
        }

        .photo-description {
            font-size: 14px;
        }
    }
}
</style>
