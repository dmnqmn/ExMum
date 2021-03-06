<template>
<div class="new-photo">
    <div class="new-photo-button" @click="newPhotoModalOn = true">
        <span class="icon fa fa-plus"></span>
    </div>
    <Modal
        class="new-photo-modal"
        v-model="newPhotoModalOn"
        title="发布"
        :mask-closable="false"
        :width="800"
        :styles="{top: '40px'}"
    >
        <Upload
            :action="`//resource.${baseDomain}/upload/image`"
            name="file"
            accept="image/png,image/jpeg,image/gif"
            with-credentials
            :show-upload-list="false"
            :headers="uploadingHeaders"
            :on-success="handleUploadSuccess"
            :on-error="handleUploadError"
            ref="upload"
            class="upload"
        >
            <div class="upload-preview" :class="{ 'upload-preview-placeholder': !imagePreviewSrc }">
                <img :src="imagePreviewSrc">
            </div>
        </Upload>
        <Form v-model="photo" :label-width="80" class="photo-info">
            <FormItem label="标题">
                <Input v-model="photo.title" placeholder="请输入标题"></Input>
            </FormItem>
            <FormItem label="描述">
                <Input v-model="photo.description" type="textarea" placeholder="来讲讲这张图的故事吧"></Input>
            </FormItem>
            <FormItem label="相册">
                <Select v-model="photo.gallery_id" style="width: 200px">
                    <Option v-for="gallery in galleryList" :value="gallery.id" :key="gallery.id">{{ gallery.name }}</Option>
                </Select>
                <Button class="new-gallery" type="success" @click="newGalleryModalOn = true"><Icon type="plus"></Icon>创建新相册</Button>
            </FormItem>
            <FormItem label="标签">
                <Select v-model="photo.tags" multiple @on-change="handleTagsChange">
                    <Option v-for="tag in tagList" :value="tag.id" :key="tag.id">{{ tag.name }}</Option>
                </Select>
            </FormItem>
        </Form>
        <div slot="footer">
            <Button icon="paper-airplane" type="primary" class="publish" @click="shareNewPhoto">发布</Button>
        </div>
    </Modal>
    <Modal v-model="newGalleryModalOn" title="创建相册" @on-ok="createGallery">
        <Form v-model="photo" :label-width="80" class="photo-info">
            <FormItem label="相册名">
                <Input v-model="newGallery.name" placeholder="请输入标题"></Input>
            </FormItem>
            <FormItem label="描述">
                <Input v-model="newGallery.description" type="textarea" placeholder="来讲讲这张图的故事吧"></Input>
            </FormItem>
            <FormItem label="标签">
                <Select v-model="newGallery.tag_id">
                    <Option v-for="tag in tagList" :value="tag.id" :key="tag.id">{{ tag.name }}</Option>
                </Select>
            </FormItem>
            <FormItem label="可见">
                <RadioGroup v-model="newGallery.secret">
                    <Radio :label="0">公开</Radio>
                    <Radio :label="1">私密</Radio>
                </RadioGroup>
            </FormItem>
        </Form>
    </Modal>
</div>
</template>

<script>
import { TAG_LIST, MAX_TAGS } from '@js/common/tags'
import Cookie from 'js-cookie'
import axios from 'axios'

export default {
    data() {
        return {
            tagList: TAG_LIST,
            newPhotoModalOn: false,
            newGalleryModalOn: false,
            photo: {
                file_id: null,
                title: '',
                description: '',
                tags: [],
                gallery_id: null
            },
            baseDomain: window.baseDomain,
            uploadingHeaders: {
                'X-XSRF-TOKEN': Cookie.get('XSRF-TOKEN')
            },
            imagePreviewSrc: '',
            galleryList: [],
            newGallery: {
                name: '',
                description: '',
                uid: window.user.id,
                secret: 0
            }
        }
    },

    watch: {
        newPhotoModalOn(on) {
            if (!on) {
                this.imagePreviewSrc = ''
                this.$refs.upload.clearFiles()
            } else {
                this.getGalleryList()
            }
        }
    },

    methods: {
        async createGallery() {
            try {
                let gallery = (await axios.post('/gallery', this.newGallery)).data
                this.$Message.info('创建成功')
                await this.getGalleryList()
                this.photo.gallery_id = gallery.id
            } catch (error) {
                this.$Message.warning('相册创建失败，请检查您输入的信息再重试')
            }
        },

        async getGalleryList() {
            this.galleryList = (await axios.get('/gallery', {
                params: {
                    uid: window.user.id
                }
            })).data
        },

        handleUploadSuccess({ id, url }) {
            this.photo.file_id = id
            this.imagePreviewSrc = url
        },

        handleUploadError() {
            this.$Message.warning('图片上传失败，请重试')
        },

        handleTagsChange() {
            if (this.photo.tags.length > MAX_TAGS) {
                this.$Message.warning('最多选择三个标签')
                this.photo.tags = this.photo.tags.slice(0, MAX_TAGS)
                return;
            }
        },

        async shareNewPhoto() {
            try {
                let { id } = (await axios.post('/photo/new', this.photo)).data
                this.$Message.info('发布成功！')
                this.newPhotoModalOn = false
            } catch (error) {
                this.$Message.warning('发布失败，请检查填写的信息再重试')
            }
        }
    }
}
</script>

<style lang="less">
.new-photo-modal {
    .ivu-upload {
        width: 100%;
    }
}
</style>

<style lang="less" scoped>
@import '../../../css/common/variables.less';

.upload {
    margin: 0 auto 16px;
    width: 400px;
    height: 200px;

    .publish {
        font-size: 16px;
    }
}

.upload-preview {
    width: 400px;
    height: 200px;
    box-shadow: 0 0 2px 1px rgba(50, 30, 80, 0.7);
    border-radius: 5px;
    margin-bottom: 12px;

    &-placeholder {
        background-image: url('../../../image/plus.png');
        background-repeat: no-repeat;
        background-size: 50px 50px;
        background-position: 175px 75px;
    }

    img {
        display: block;
        margin: 0 auto;
        max-width: 100%;
        max-height: 100%;
    }
}

.new-photo-button {
    @size: 120px;
    @hover-size: 134px;
    position: fixed;
    right: -@size/2;
    bottom: -@size/2;
    width: @size;
    height: @size;
    border-radius: 100%;
    background-color: @primary;
    box-shadow: 0 0 1px 1px @primary;
    transition: background-color 0.1s ease-in,
                right 0.1s ease-in,
                bottom 0.1s ease-in,
                width 0.1s ease-in,
                height 0.1s ease-in;

    .icon {
        position: relative;
        top: 23px;
        left: 26px;
        color: #fff;
        font-size: 24px;
        transition: top 0.1s ease-in, left 0.1s ease-in;
    }

    &:hover {
        right: -@hover-size/2;
        bottom: -@hover-size/2;
        width: @hover-size;
        height: @hover-size;
        background-color: lighten(@primary, 10%);

        .icon {
            top: 28px;
            left: 31px;
        }
    }
}
</style>
