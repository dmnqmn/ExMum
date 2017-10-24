<template>
<Form :label-width="40">
    <Form-item label="邮箱">
        <Input v-model="formItems.account" placeholder="请输入您的邮箱账号" @on-enter="checkAndLogin"></Input>
    </Form-item>
    <Form-item label="密码">
        <Input v-model="formItems.password" placeholder="请输入您的密码" type="password" @on-enter="checkAndLogin"></Input>
    </Form-item>
    <Form-item>
        <Button type="primary" @click="checkAndRegister">注册账号</Button>
        <Button type="info" @click="checkAndLogin">直接登录</Button>
    </Form-item>
</Form>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    data() {
        return {
            formItems: {
                account: '',
                password: ''
            }
        }
    },

    methods: {
        ...mapActions(['register', 'login']),

        check() {
            if (this.formItems.account.trim().length < 0) {
                this.$Message.error('请输入邮箱账号')
                return
            }

            if (this.formItems.password.length < 6) {
                this.$Message.error('密码应大于 6 位')
                return
            }

            if (/\s/.test(this.formItems.password)) {
                this.$Message.error('密码不能包含空格')
                return
            }

            return true
        },

        async checkAndRegister() {
            if (!this.check()) {
                return
            }

            let email = this.formItems.account.trim()
            let password = this.formItems.password

            try {
                await this.register({ email, password })
                this.$emit('register', true)
            } catch (reason) {
                let error = reason.response.data.error
                switch (error) {
                    case 'REGISTER_EMAIL_NEEDED':
                        this.$Message.error('请输入邮箱账号')
                        break
                    case 'REGISTER_EMAIL_ILLEGAL':
                        this.$Message.error('请输入合法的邮箱账号')
                        break
                    case 'REGISTER_EMAIL_EXISTED':
                        this.$Message.error('该邮箱已经注册过')
                        break
                    case 'REGISTER_PASSWORD_NEEDED':
                        this.$Message.error('请输入合适的密码')
                        break
                    default:
                        this.$Message.error('注册失败，请稍候重试')
                }
                this.$emit('register', false)
                return
            }
        },

        async checkAndLogin() {
            if (!this.check()) {
                return
            }

            let email = this.formItems.account.trim()
            let password = this.formItems.password

            try {
                await this.login({ email, password })
                this.$emit('login', true)
            } catch(reason) {
                let error = reason.response.data.error
                switch (error) {
                    case 'LOGIN_EMAIL_NEEDED':
                        this.$Message.error('请输入邮箱账号')
                        break
                    case 'LOGIN_EMAIL_ILLEGAL':
                        this.$Message.error('请输入合法的邮箱账号')
                        break
                    case 'LOGIN_PASSWORD_NEEDED':
                        this.$Message.error('请输入密码')
                        break
                    case 'LOGIN_USER_NOT_FOUND':
                        this.$Message.error('该账号不存在')
                        break
                    case 'LOGIN_WRONG_PASSWORD':
                        this.$Message.error('账号或密码错误')
                        break
                    default:
                        this.$Message.error('登录失败，请稍候重试')
                }
                this.$emit('login', false)
                return
            }
        }
    }
}
</script>
