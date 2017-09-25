<template>
<div class="photo-new">
    <Form>
        <FormItem label="上传文件" :label-width="80">
            <Upload :action="`//resource.${domain}/upload/image`" :headers="headers" name="file" with-credentials :max-size="20000" :show-upload-list="false" :on-success="handleUploadSuccess">
                <Button type="ghost" icon="ios-cloud-upload-outline">上传文件</Button> 文件 ID： {{ currentFileId }}
            </Upload>
        </FormItem>
        <FormItem label="标题" :label-width="80">
            <Input :maxlength="20" v-model="title"></Input>
        </FormItem>
        <FormItem label="描述" :label-width="80">
            <Input type="textarea" :maxlength="300" v-model="description"></Input>
        </FormItem>
        <FormItem label="标签" :label-width="80">
            <template v-for="(_, index) in tags">
                <Select v-model="tags[index]" style="width: 120px;" placeholder="请选择标签">
                    <Option v-for="tag in tagList" :value="tag" :key="tag">{{ tag }}</Option>
                </Select><Button @click="removeTag(index)">x</Button>
            </template>
            <Button v-if="tags.length < 5" @click="addNewTag">新标签</Button>
        </FormItem>
        <FormItem :label-width="80">
            <Button type="primary" @click="submit">发布</Button>
        </FormItem>
    </Form>
    <ul>
        <li v-for="item in uploadedList">
            <a :href="`//www.${domain}/photo/${item.id}`">{{ item.id }}: {{ item.title }}</a>
        </li>
    </ul>
</div>
</template>

<script>
import assign from 'object-assign'
import axios from 'axios'
import Cookie from 'js-cookie'
import TAG_LIST from '../common/tags.js'

export default {
    props: {
        domain: {
            type: String,
            default: 'exmum.com'
        }
    },

    data() {
        return {
            headers: { 'X-XSRF-TOKEN': Cookie.get('XSRF-TOKEN') },
            title: '',
            description: '',
            currentFileId: null,
            uploadedList: [],
            tagList: TAG_LIST,
            tags: [null]
        }
    },

    methods: {
        handleUploadSuccess({ id }) {
            this.currentFileId = id
        },

        submit() {
            axios.post('/photo/new', {
                file_id: this.currentFileId,
                title: this.title,
                description: this.description,
                tags: this.tags
            }).then(({ data }) => {
                this.uploadedList.push(data)
            })
        },

        addNewTag() {
            this.tags.push(-1)
        },

        removeTag(index) {
            this.tags.splice(index, 1);
        }
    }
}
</script>

<style lang="less" scoped>
</style>
