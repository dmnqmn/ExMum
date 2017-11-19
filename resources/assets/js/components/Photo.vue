<template>
<div class="photo">
    <Row>
        <Col span="22" push="1" class="container">
            <img :src="photo ? photo.url : ''"></img>
        </Col>
    </Row>
</div>
</template>

<script>
import { getPhoto } from '@js/common/photo'

export default {
    props: {
        photoId: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            photo: null
        }
    },

    async created() {
        this.photo = await getPhoto(this.photoId);
    }
}
</script>

<style lang="less" scoped>
</style>
