import Vue from 'vue'
import { createUserFromBackendUserInfo } from '@js/common/account.js'

export const state = {
    eventBus: new Vue(),
    user: window.user ? createUserFromBackendUserInfo(window.user) : null
}

export const getters = {
    eventBus: (state) => state.eventBus,
    user: (state) => state.user
}
