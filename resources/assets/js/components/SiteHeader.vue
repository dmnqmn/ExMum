<template>
<header class="site-header">
    <Row class="row">
        <Col class="col" span="22" push="1">
            <div class="logo"><router-link to="/"><img src="../../image/simple-exmum.svg"></router-link></div>
            <div class="header-item search">
                <div class="search-icon"><Icon type="ios-search"></Icon></div>
                <input class="search-input" type="text" placeholder="" v-model="searchText">
            </div>
            <div class="header-item nav-item search-small" @click="toggleSearchPanel">
                <Icon class="nav-icon search-small-icon" type="ios-search"></Icon>
            </div>
            <router-link class="nav-link" to="/">
                <div class="header-item nav-item">
                    <Icon class="nav-icon" type="ios-home-outline"></Icon><span class="nav-text">首页</span>
                </div>
            </router-link>
            <router-link class="nav-link" to="/">
                <div class="header-item nav-item">
                    <Icon class="nav-icon" type="compass"></Icon><span class="nav-text">热门</span>
                </div>
            </router-link>
            <div class="header-item nav-item user">
                <a v-if="!user" href="javascript:void(0);" @click="eventBus.$emit('sign')">
                    <Icon class="nav-icon user-icon" type="android-person"></Icon>
                    <span class="nav-text user-text user-text-sign">登录/注册</span>
                </a>
                <Dropdown v-else trigger="click">
                    <a href="javascript:void(0);">
                        <Icon class="nav-icon user-icon" type="android-person"></Icon>
                        <span class="nav-text user-text">{{ user.username }}</span>
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem class="user-dropdown-item">
                            <span class="user-dropdown-item-text">个人主页</span>
                        </DropdownItem>
                        <DropdownItem class="user-dropdown-item">
                            <router-link class="user-dropdown-item-text" to="/settings">设置</router-link>
                        </DropdownItem>
                        <DropdownItem class="user-dropdown-item">
                            <span class="user-dropdown-item-text" @click="logout">退出</span>
                        </DropdownItem>
                    </DropdownMenu>
                </Dropdown>
            </div>
            <div class="header-item message">
                <Icon class="message-icon" type="android-notifications-none"></Icon>
            </div>
            <div class="header-item search-panel" :class="{ 'search-panel-show': searchPanelShow }">
                <input class="search-input" type="text" placeholder="" v-model="searchText">
            </div>
        </Col>
    </Row>
</header>
</template>

<script>
import { logout } from '@js/common/account'
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            searchPanelShow: false,
            searchText: ''
        }
    },

    computed: {
        ...mapGetters(['user', 'eventBus'])
    },

    methods: {
        toggleSearchPanel() {
            this.searchPanelShow = !this.searchPanelShow
        },

        logout() {
            logout()
        }
    }
}
</script>

<style lang="less" scoped>
@import '../../css/common/variables.less';
@header-height: 64px;
@header-content-height: 44px;
@logo-size: 50px;
@header-item-padding-vertical: 10px;
@header-normal-text-color: #777;

.site-header {
    position: relative;
    width: 100%;
    height: @header-height;
    box-shadow: 0 0 1px 0 rgba(0, 0, 0, 0.3);
    z-index: 1000;

    .row {
        width: 100%;

        .col {
            display: flex;
            height: 100%;
        }
    }


    .header-item {
        padding-top: @header-item-padding-vertical;
        padding-bottom: @header-item-padding-vertical;
    }

    .logo {
        padding-top: (@header-height - @logo-size) / 2;

        img {
            width: @logo-size;
            height: @logo-size;
        }
    }

    .nav-item {
        padding-top: 0;
        padding-bottom: 0;
        padding-left: 40px;
        font-size: 20px;
        line-height: @header-height;

        .nav-icon {
            display: none;
            height: @header-height;
            line-height: @header-height;
            font-size: 26px;
        }
    }

    .nav-link {
        color: @header-normal-text-color;
    }

    .search {
        flex: 1;
        position: relative;
        height: @header-height;
        padding-left: 20px;
        border: 0;
    }

    .search-panel {
        display: none;
        position: absolute;
        top: @header-height;
        left: 0;
        width: 100%;
        height: 100%;
        padding-left: 20px;
        padding-right: 20px;
    }

    .user {
        .user-text,
        .user-icon {
            color: @header-normal-text-color;
        }

        .user-text-sign {
            color: #e59709;
        }

        .user-dropdown-item-text {
            font-size: 16px;
            color: @header-normal-text-color;
        }
    }

    .search,
    .search-panel {
        .search-icon {
            position: absolute;
            height: @header-content-height;
            line-height: @header-content-height;
            font-size: 28px;
            color: #333;
            right: 12px;
        }

        .search-input {
            width: 100%;
            height: 100%;
            padding: 3px 12px;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 3px;
            background-color: #eee;
            color: @header-normal-text-color;

            &:focus {
                outline: none;
            }
        }
    }

    .search-small {
        flex: 1;
        display: none;
        position: relative;

        .search-small-icon {
            position: absolute;
            right: 0;
        }
    }

    .message {
        padding-left: 50px;

        .message-icon {
            height: @header-content-height;
            line-height: @header-content-height;
            font-size: 26px;
        }
    }

    @media screen and (max-width: 596px) {
        .nav-item .nav-icon {
            display: block;
        }

        .nav-text {
            display: none;
        }

        .search {
            display: none;
        }

        .search-small {
            display: block;
        }

        .search-panel-show {
            display: block;
        }
    }
}
</style>
