<template>
<Form>
    <Form-item label="邮箱">
        <Input v-model="formItems.account" placeholder="请输入您的邮箱账号"></Input>
    </Form-item>
    <Form-item label="密码">
        <Input v-model="formItems.password" placeholder="请输入您的密码" type="password"></Input>
    </Form-item>
    <Form-item>
        <Button type="primary" @click="checkAndRegister">注册</Button>
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
            } catch (reason) {
                console.log(reason)
                let error = reason.response.data.error
                switch (error) {
                    case 'REGISTER_EMAIL_NEEDED':
                        return this.$Message.error('请输入邮箱账号')
                    case 'REGISTER_EMAIL_ILLEGAL':
                        return this.$Message.error('请输入合法的邮箱账号')
                    case 'REGISTER_EMAIL_EXISTED':
                        return this.$Message.error('该邮箱已经注册过')
                    case 'REGISTER_PASSWORD_NEEDED':
                        return this.$Message.error('请输入合适的密码')
                    default:
                        return this.$Message.error('注册失败，请稍候重试')
                }
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
            } catch(reason) {
                let error = reason.response.data.error
                switch (error) {
                    case 'LOGIN_EMAIL_NEEDED':
                        return this.$Message.error('请输入邮箱账号')
                    case 'LOGIN_EMAIL_ILLEGAL':
                        return this.$Message.error('请输入合法的邮箱账号')
                    case 'LOGIN_PASSWORD_NEEDED':
                        return this.$Message.error('请输入密码')
                    case 'LOGIN_USER_NOT_FOUND':
                        return this.$Message.error('该账号不存在')
                    case 'LOGIN_WRONG_PASSWORD':
                        return this.$Message.error('账号或密码错误')
                    default:
                        return this.$Message.error('登录失败，请稍候重试')
                }
            }
        }
    }
}
</script>
