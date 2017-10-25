<template>
<div class="user-settings">
<Form :model="settingUserInfo">
    <FormItem label="头像" :label-width="80">
        <div class="avatar">
            <img :src="user.avatar || defaultAvatar">
            <div class="avatar-upload"><Icon type="images" class="avatar-upload-icon"></Icon></div>
        </div>
    </FormItem>
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
import defaultAvatar from '@/image/exmum.svg'

export default {
    data() {
        return {
            settingUserInfo: null,
            currentUserInfo: null,
            defaultAvatar: defaultAvatar
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
@import '../../../css/common/variables.less';

.user-settings {
    padding: 20px;

    .avatar {
        position: relative;
        width: 128px;
        height: 128px;
        border-radius: 100%;

        img {
            width: 100%;
            height: 100%;
            transition: filter .2s ease-in;
        }

        .avatar-upload {
            position: absolute;
            top: 0;
            left: 0;
            width: 128px;
            height: 128px;
            border-radius: 100%;
            background-color: @primary;
            opacity: 0;
            transition: opacity .2s ease-in;

            .avatar-upload-icon {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 26px;
            }
        }

        &:hover {
            img {
                filter: blur(3px);
            }

            .avatar-upload {
                opacity: 0.7;
            }
        }
    }
}
</style>
