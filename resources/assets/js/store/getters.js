import Vue from 'vue'

export const state = {
    eventBus: new Vue(),
    identification: null
}

export const getters = {
    eventBus: (state) => state.eventBus,
    identification: (state) => state.identification
}
