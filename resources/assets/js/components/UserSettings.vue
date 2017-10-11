<template>
<div class="user-settings">
<Form :model="settingUserinfo">
    <FormItem label="姓氏" :label-width="80">
        <Row>
            <Col span="8">
                <Input type="text" v-model="settingUserinfo.first_name" placeholder="请输入..."></Input>
            </Col>
        </Row>
    </FormItem>
    <FormItem label="名字" :label-width="80">
        <Row>
            <Col span="8">
                <Input type="text" v-model="settingUserinfo.last_name" placeholder="请输入..."></Input>
            </Col>
        </Row>
    </FormItem>
    <FormItem label="性别" prop="gender" :label-width="80">
        <RadioGroup v-model="settingUserinfo.gender">
            <Radio :label="0">不告诉你</Radio>
            <Radio :label="1">女</Radio>
            <Radio :label="2">男</Radio>
        </RadioGroup>
    </FormItem>
    <FormItem label="个人描述" :label-width="80">
        <Row>
            <Col span="18">
                <Input type="textarea" v-model="settingUserinfo.description" placeholder="介绍一下自己吧"></Input>
            </Col>
        </Row>
    </FormItem>
    <FormItem :label-width="80">
        <Button type="primary" @click="save()">保存</Button>
        <Button type="ghost" @click="reset()" style="margin-left: 8px">重置</Button>
    </FormItem>
</Form>
</div>
</template>

<script>
import assign from 'object-assign'
import axios from 'axios'

export default {
    props: {
        userinfo: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            settingUserinfo: null,
            currentUserinfo: null
        }
    },

    created() {
        this.settingUserinfo = assign({}, this.userinfo)
        this.currentUserinfo = assign({}, this.userinfo)
    },

    methods: {
        reset() {
            this.settingUserinfo = assign({}, this.currentUserinfo)
        },

        save() {
            let { first_name, last_name, gender, description } = this.settingUserinfo
            this.settingUserinfo ={
                first_name: first_name.trim(),
                last_name: last_name.trim(),
                gender,
                description: description ? description.trim() : ''
            }

            if (this.settingUserinfo.first_name.length > 10) {
                this.$Message.warning('姓氏最长 10 个字符');
                return;
            }

            if (this.settingUserinfo.last_name.length > 10) {
                this.$Message.warning('名字最长 10 个字符');
                return;
            }

            if (this.settingUserinfo.description.length > 255) {
                this.$Message.warning('个人简介最长 255 个字符');
                return;
            }

            axios.post('/user/info', this.settingUserinfo)
            .then((res) => {
                this.$Message.info('已成功修改个人信息');
                this.currentUserinfo = assign({}, this.settingUserinfo)
            })
            .catch((err) => {
                this.$Message.warning('未能成功保存到服务器，请检查后重试');
            })
        }
    }
}
</script>

<style lang="less" scoped>
.user-settings {
    padding: 20px;
}
</style>
