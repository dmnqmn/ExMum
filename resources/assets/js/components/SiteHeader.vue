<template>
<header class="site-header">
    <div class="header-item logo"><router-link to="/"><img src="../../image/simple-exmum.svg"></router-link></div>
    <div class="header-item search">
        <div class="search-icon"><Icon type="ios-search"></Icon></div>
        <input class="search-input" type="text" placeholder="" v-model="searchText">
    </div>
    <div class="header-item nav-item search-small" @click="toggleSearchPanel">
        <Icon class="nav-icon search-small-icon" type="ios-search"></Icon>
    </div>
    <div class="header-item nav-item">
        <router-link class="nav-link" to="/"><Icon class="nav-icon" type="ios-home-outline"></Icon><span class="nav-text">首页</span></router-link>
    </div>
    <div class="header-item nav-item">
        <router-link class="nav-link" to="/"><Icon class="nav-icon" type="compass"></Icon><span class="nav-text">热门</span></router-link>
    </div>
    <div class="header-item nav-item">
        <router-link class="nav-link" to="/"><Icon class="nav-icon" type="android-person"></Icon><span class="nav-text">我</span></router-link>
    </div>
    <div class="header-item message">
        <Icon class="message-icon" type="android-notifications-none"></Icon>
    </div>
    <div class="header-item search-panel" :class="{ 'search-panel-show': searchPanelShow }">
        <input class="search-input" type="text" placeholder="" v-model="searchText">
    </div>
</header>
</template>

<script>
export default {
    data() {
        return {
            searchPanelShow: false,
            searchText: ''
        }
    },

    methods: {
        toggleSearchPanel() {
            this.searchPanelShow = !this.searchPanelShow
        }
    }
}
</script>

<style lang="less" scoped>
@import '../../css/common/variables.less';
@header-height: 64px;
@header-content-height: 44px;
@header-item-padding-vertical: 10px;

.site-header {
    display: flex;
    position: relative;
    width: 100%;
    height: @header-height;
    padding: 0 20px;
    box-shadow: 0 0 1px 0 rgba(0, 0, 0, 0.3);

    .header-item {
        padding-top: @header-item-padding-vertical;
        padding-bottom: @header-item-padding-vertical;
    }

    .logo {
        img {
            width: @header-content-height;
            height: @header-content-height;
        }
    }

    .nav-item {
        padding-left: 50px;
        font-size: 22px;
        line-height: @header-content-height;

        .nav-icon {
            display: none;
            height: @header-content-height;
            line-height: @header-content-height;
            font-size: 26px;
        }

        .nav-link {
            color: #777;
        }
    }

    .search {
        flex: 1;
        position: relative;
        height: 100%;
        padding-left: 30px;
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
            color: #777;

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
