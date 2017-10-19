<template>
<div class="panel panel-default">
    <div class="panel-body">
        <Tabs>
            <TabPane label="个人设置">
                <UserSettings :userinfo="userinfo"></UserSettings>
            </TabPane>
            <TabPane label="退出">
                <Button type="error" @click="logout">退出账号</Button>
            </TabPane>
        </Tabs>
    </div>
</div>
</template>

<script>
import UserSettings from './UserSettings'
import axios from 'axios'

export default {
    components: {
        UserSettings
    },

    props: {
        userinfo: {
            type: Object,
            required: true
        }
    },

    methods: {
        async logout() {
            try {
                await axios.post('/logout');
            } catch (error) {
                this.$Message.warning('请求失败，请手动清除 cookie');
                return;
            }
            window.location.href = '/';
        }
    }
}
</script>
