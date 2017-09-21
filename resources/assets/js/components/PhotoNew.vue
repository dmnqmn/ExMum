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
            uploadedList: []
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
                description: this.description
            }).then(({ data }) => {
                this.uploadedList.push(data)
            })
        }
    }
}
</script>

<style lang="less" scoped>
</style>
