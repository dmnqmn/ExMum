<template>
<div class="new-photo">
    <div class="new-photo-button" @click="newPhotoModalOn = true">
        <span class="icon fa fa-plus"></span>
    </div>
    <Modal v-model="newPhotoModalOn" title="发布" :mask-closable="false">
        <Form v-model="photo" :label-width="80">
            <FormItem label="上传图片">
                <Upload
                    :action="`//resource.${baseDomain}/upload/image`"
                    name="file"
                    accept="image/png,image/jpeg,image/gif"
                    with-credentials
                    :headers="uploadingHeaders"
                    :on-success="handleUploadSuccess"
                >
                    <Button type="ghost" icon="ios-cloud-upload-outline">上传图片</Button>
                </Upload>
            </FormItem>
            <FormItem label="标题">
                <Input v-model="photo.title" placeholder="请输入标题"></Input>
            </FormItem>
            <FormItem label="描述">
                <Input v-model="photo.description" type="textarea" placeholder="来讲讲这张图的故事吧"></Input>
            </FormItem>
            <FormItem label="标签">
                <CheckboxGroup v-model="photo.tags" @on-change="handleTagsChange">
                    <Checkbox v-for="(tag, index) in tagList" :label="tag" :key="index"></Checkbox>
                </CheckboxGroup>
            </FormItem>
        </Form>
        <div slot="footer">
            <Button long icon="paper-airplane" type="primary" style="font-size: 16px;" @click="shareNewPhoto">分享</Button>
        </div>
    </Modal>
</div>
</template>

<script>
import TAG_LIST from '../common/tags'
import Cookie from 'js-cookie'
import axios from 'axios'

export default {
    data() {
        return {
            tagList: TAG_LIST,
            newPhotoModalOn: false,
            photo: {
                fileId: null,
                title: '',
                description: '',
                tags: ['Home feed']
            },
            baseDomain: window.baseDomain,
            uploadingHeaders: {
                'X-XSRF-TOKEN': Cookie.get('XSRF-TOKEN')
            }
        }
    },

    methods: {
        handleUploadSuccess({ id }) {
            this.photo.fileId = id;
        },

        handleTagsChange() {
            if (this.photo.tags.length > 5) {
                this.$Message.warning('最多选择五个标签')
                this.photo.tags = this.photo.tags.slice(0, 5)
                return;
            }
        },

        shareNewPhoto() {
            axios.post('/photo/new', this.photo).then(({ data: { id } }) => {
                this.$Message.info('分享成功！');
                window.location.href = `/photo/${id}`;
            })
        }
    }
}
</script>

<style lang="less" scoped>
.new-photo-button {
    position: fixed;
    right: 0;
    bottom: 0;
    width: 40px;
    height: 40px;
    border-radius: 100% 0 0 0;
    background-color: #808080;
    box-shadow: 0 0 3px 3px rgba(179, 179, 179, 0.5);
    transition: width 0.1s ease-in, height 0.1s ease-in, background-color 0.05s ease-in;

    .icon {
        position: relative;
        top: 18px;
        left: 18px;
        color: white;
        font-size: 20px;
        transition: top 0.1s ease-in, left 0.1s ease-in, font-size 0.1s ease-in;
    }

    &:hover {
        width: 60px;
        height: 60px;
        background-color: #B3B3B3;

        .icon {
            top: 26px;
            left: 26px;
            font-size: 28px;
        }
    }
}
</style>
