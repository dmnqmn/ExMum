<template>
<div class="user-settings">
<Form :model="settingUserInfo">
    <FormItem label="名字" :label-width="80">
        <Row>
            <Col span="8">
                <Input type="text" v-model="settingUserInfo.username" placeholder="起名是最难的事情了"></Input>
            </Col>
        </Row>
    </FormItem>
    <FormItem label="性别" prop="gender" :label-width="80">
        <RadioGroup v-model="settingUserInfo.gender">
            <Radio :label="0">不告诉你</Radio>
            <Radio :label="1">女</Radio>
            <Radio :label="2">男</Radio>
        </RadioGroup>
    </FormItem>
    <FormItem label="个人描述" :label-width="80">
        <Row>
            <Col span="18">
                <Input type="textarea" v-model="settingUserInfo.description" placeholder="介绍一下自己吧"></Input>
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
import { mapGetters, mapActions } from 'vuex'
import { User } from '@js/models/account'

export default {
    data() {
        return {
            settingUserInfo: null,
            currentUserInfo: null
        }
    },

    computed: {
        ...mapGetters(['user'])
    },

    created() {
        this.settingUserInfo = new User(this.user)
        this.currentUserInfo = new User(this.user)
    },

    methods: {
        ...mapActions(['changeUserInfo']),

        reset() {
            this.settingUserInfo = new User(this.currentUserInfo)
        },

        async save() {
            let { username, gender, description } = this.settingUserInfo
            this.settingUserInfo = new User({
                id: this.settingUserInfo.id,
                url: this.settingUserInfo.url,
                username: username.trim(),
                gender,
                description: description ? description.trim() : ''
            })

            if (this.settingUserInfo.username.length > 20) {
                this.$Message.warning('名字最长 20 个字符')
                return
            }

            if (this.settingUserInfo.description.length > 255) {
                this.$Message.warning('个人简介最长 255 个字符')
                return
            }

            try {
                await this.changeUserInfo(this.settingUserInfo)
            } catch (error) {
                this.$Message.warning('未能成功保存到服务器，请检查后重试')
                return
            }

            this.$Message.info('已成功修改个人信息');
            this.currentUserInfo = assign({}, this.settingUserInfo)
        }
    }
}
</script>

<style lang="less" scoped>
.user-settings {
    padding: 20px;
}
</style>
