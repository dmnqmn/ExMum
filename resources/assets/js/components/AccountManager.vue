<template>
<Modal
    v-model="showModal"
    :footer-hide="true"
    :closable="false"
    title="注册/登录"
>
    <Form>
        <Form-item>
            <Input v-model="formItems.account" placeholder="请输入您的邮箱账号"></Input>
        </Form-item>
        <Form-item>
            <Input v-model="formItems.password" placeholder="请输入您的密码" type="password"></Input>
        </Form-item>
        <Form-item>
            <Button type="primary" @click="register">注册</Button>
            <Button type="info" @click="login">直接登录</Button>
        </Form-item>
    </Form>
</Modal>
</template>

<script>
import $ from 'jquery'
import axios from 'axios'
import crypto from 'crypto-js'

export default {
    data() {
        return {
            showModal: !window.user,
            formItems: {
                account: '',
                password: '',
                repeatPassword: ''
            }
        }
    },

    methods: {
        register() {
            if (this.formItems.account.trim().length < 0) {
                return this.$Message.error('请输入邮箱账号')
            }

            if (this.formItems.account.trim().length < 6) {
                return this.$Message.error('密码应大于 6 位且不包含空格')
            }

            axios.post('/register', {
                email: this.formItems.account,
                password: crypto.SHA256(this.formItems.password).toString(crypto.enc.Hex).slice(0, 50)
            })
            .then(() => {
                window.location.reload()
            })
            .catch((reason) => {
                let error = reason.response.data.error
                switch (error) {
                    case 'REGISTER_EMAIL_NEEDED':
                        return this.$Message.error('请输入邮箱账号')
                    case 'REGISTER_EMAIL_ILLEGAL':
                        return this.$Message.error('请输入合法的邮箱账号')
                    case 'REGISTER_EMAIL_NEEDED':
                        return this.$Message.error('请输入合适的密码');
                }
            })
        },

        login() {
            if (this.formItems.account.trim().length < 0) {
                return this.$Message.error('请输入邮箱账号')
            }

            if (this.formItems.account.trim().length < 0) {
                return this.$Message.error('请输入密码')
            }

            axios.post('/login', {
                email: this.formItems.account,
                password: crypto.SHA256(this.formItems.password).toString(crypto.enc.Hex).slice(0, 50)
            })
            .then(() => {
                window.location.reload()
            })
            .catch((reason) => {
                let error = reason.response.data.error
                switch (error) {
                    case 'LOGIN_EMAIL_NEEDED':
                        return this.$Message.error('请输入邮箱账号')
                    case 'LOGIN_EMAIL_ILLEGAL':
                        return this.$Message.error('请输入合法的邮箱账号')
                    case 'LOGIN_PASSWORD_NEEDED':
                        return this.$Message.error('请输入密码');
                    case 'LOGIN_USER_NOT_FOUND':
                        return this.$Message.error('该账号不存在');
                    case 'LOGIN_WRONG_PASSWORD':
                        return this.$Message.error('账号或密码错误');
                }
            })
        }
    }
}
</script>

<style scoped lang="less">

</style>
