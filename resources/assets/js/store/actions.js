import * as account from '@js/common/account'

export async function register({ commit }, { email, password }) {
    let user = await account.register({ email, password })
    commit('SET_USER', user)
}

export async function login({ commit }, { email, password }) {
    let user = await account.login({ email, password })
    commit('SET_USER', user)
}

export async function changeUserInfo({ commit }, user) {
    await account.changeUserInfo(user)
    commit('SET_USER', user)
}

export async function setAvatar({ commit }, avatar) {
    let avatarUrl = await account.setAvatar(avatar)
    commit('SET_USER_AVATAR', avatarUrl)
}
