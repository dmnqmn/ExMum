import Vue from 'vue'
import { User } from '@js/models/User.js'

export const state = {
    eventBus: new Vue(),
    user: window.user ? new User(window.user) : null
}

export const getters = {
    eventBus: (state) => state.eventBus,
    user: (state) => state.user
}
