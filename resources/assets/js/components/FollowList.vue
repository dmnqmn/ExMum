<template>
<div class="follow-list">
    <Card v-for="(user, index) in userList" :key="index">
        <a :href="user.url">{{ user.user_name }}</a>
        <Button :type="user.relationship.followedByMe ? 'ghost' : 'primary'" class="follow-btn">
            <Icon :type="user.relationship.followedByMe ? 'minus' : 'plus'"></Icon>
            {{ user.relationship.followedByMe ? '取消关注' : '关注' }}
        </Button>
    </Card>
    <div class="pager-wrapper">
        <Page :current="page" :total="total" :page-size="pageSize" simple @on-change="getUserList(page)"></Page>
    </div>
</div>
</template>

<script>
import axios from 'axios'

export default {
    props: {
        userListUrl: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            userList: [],
            total: 0,
            page: 1,
            pageSize: 10
        }
    },

    methods: {
        async getUserList(page) {
            let { data } = await axios.get(this.userListUrl, {
                params: {
                    results_per_page: this.pageSize,
                    page: this.page
                }
            })
            this.total = data.total
            this.userList = data.data
        }
    },

    mounted() {
        this.getUserList(1)
    }
}
</script>

<style lang="less">
.follow-list {
    .follow-btn {
        float: right;
        position: relative;
        top: -4px;
    }

    .pager-wrapper {
        margin-top: 12px;
        text-align: center;
    }
}
</style>
