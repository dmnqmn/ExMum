<template>
<div class="home">
    <Row>
        <Col span="22" push="1" class="container">
            <PhotoMasonry></PhotoMasonry>
        </Col>
    </Row>
    <NewPhoto></NewPhoto>
</div>
</template>

<script>
import PhotoMasonry from './common/PhotoMasonry'
import NewPhoto from './common/NewPhoto'

export default {
    components: {
        PhotoMasonry,
        NewPhoto
    }
}
</script>

<style lang="less" scoped>
.home {
    flex: 1;
    overflow: auto;
    padding-top: 15px;

    .container {
        height: 100%;
    }
}
</style>
