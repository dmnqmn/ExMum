<template>
<div class="user-settings">
    <Form :model="settingUserInfo">
        <FormItem label="头像" :label-width="80">
            <div class="avatar">
                <img :src="user.avatar">
                <div class="avatar-upload" @click="chooseAvatarImage"><Icon type="images" class="avatar-upload-icon"></Icon></div>
                <input type="file" ref="avatarUploadInput" accept="image/png,image/jpg" @change="editChosenImage">
            </div>
            <Modal v-model="avatarEditorShow" title="编辑头像" @on-ok="changeAvatar">
                <div class="avatar-editor">
                    <img :src="chosenImage" ref="editingImage">
                </div>
            </Modal>
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
import { User } from '@js/models/User'
import Cropper from 'cropperjs'

import 'cropperjs/dist/cropper.css'

export default {
    data() {
        return {
            settingUserInfo: null,
            currentUserInfo: null,
            chosenImage: null,
            avatarEditorShow: false,
            cropper: null
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
        ...mapActions(['changeUserInfo', 'setAvatar']),

        reset() {
            this.settingUserInfo = new User(this.currentUserInfo)
        },

        async save() {
            let { username, gender, description } = this.settingUserInfo
            assign(this.settingUserInfo, {
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
                await this.changeUserInfo(new User(this.settingUserInfo))
            } catch (error) {
                this.$Message.warning('未能成功保存到服务器，请检查后重试')
                return
            }

            this.$Message.info('已成功修改个人信息')
            this.currentUserInfo = assign({}, this.settingUserInfo)
        },

        chooseAvatarImage() {
            this.chosenImage = null
            this.$refs.avatarUploadInput.value = ''
            this.$refs.avatarUploadInput.click()
        },

        editChosenImage() {
            if (this.cropper) {
                this.cropper.destroy()
            }

            let image = this.$refs.avatarUploadInput.files[0]
            let reader = new FileReader()
            reader.readAsDataURL(image)
            reader.onload = () => {
                this.chosenImage = reader.result
                this.avatarEditorShow = true
                this.$nextTick(() => {
                    this.cropper = new Cropper(this.$refs.editingImage, {
                        viewMode: 2,
                        aspectRatio: 1,
                        dragMode: 'move'
                    })
                })
            }
            reader.onerror = () => {
                this.$Message.alert('图片读取失败，请重试')
            }
        },

        changeAvatar() {
            this.cropper.getCroppedCanvas().toBlob(async (blob) => {
                try {
                    await this.setAvatar(blob)
                } catch (reason) {
                    let error = reason.response.data.error
                    switch (error) {
                        case 'AVATAR_TOO_LARGE':
                            this.$Message.error('头像大小超出限制')
                            break
                        case 'AVATAR_FILE_NEEDED':
                        case 'AVATAR_NOT_A_FILE':
                            this.$Message.error('请上传头像文件')
                            break
                        default:
                            this.$Message.error('头像上传失败，请稍后重试')
                    }
                    return
                }
                this.$Message.info('已成功修改头像')
            })
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
        border-radius: 50%;
        overflow: hidden;

        img {
            width: 100%;
            height: 100%;
            transition: filter .1s ease-in;
        }

        .avatar-upload {
            position: absolute;
            top: 0;
            left: 0;
            width: 128px;
            height: 128px;
            border-radius: 50%;
            background-color: alpha(@primary, 1);
            opacity: 0;
            transition: opacity .1s ease-in, background-color .1s ease-in;

            .avatar-upload-icon {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 26px;
                color: #fff;
            }
        }

        input[type="file"] {
            display: none;
        }

        &:hover {
            img {
                filter: blur(3px);
            }

            .avatar-upload {
                background-color: fadein(@primary, 20%);
                opacity: 0.7;
            }
        }
    }
}

.avatar-editor {
    margin: 0 auto;
    width: 400px;
    height: 400px;

    img {
        max-width: 100%;
        max-height: 100%;
    }
}
</style>
