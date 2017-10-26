<template>
<Row class="home">
    <Col span="22" push="1" class="container">
        <PhotoMasonry></PhotoMasonry>
    </Col>
</Row>
</template>

<script>
import PhotoMasonry from './common/PhotoMasonry'

export default {
    components: {
        PhotoMasonry
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
