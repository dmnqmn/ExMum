import axios from 'axios'
import crypto from 'crypto-js'
import { User } from '@js/models/account'

export function createUserFromBackendUserInfo(data) {
    return new User({
        id: data.id,
        username: data.user_name,
        url: data.url,
        gender: data.gender,
        description: data.description
    })
}

export async function getUserInfo() {
    let response = await axios.get('/user/info')
    if (response.status !== 200) {
        return null
    }

    let { success, data } = response.data

    if (!success) {
        return null
    }

    return createUserFromBackendUserInfo(data)
}

export async function register({ email, password }) {
    let data = (await axios.post('/register', {
        email: email,
        password: crypto.SHA256(password).toString(crypto.enc.Hex).slice(0, 50)
    })).data

    return createUserFromBackendUserInfo(data)
}

export async function login({ email, password }) {
    let data = (await axios.post('/login', {
        email: email,
        password: crypto.SHA256(password).toString(crypto.enc.Hex).slice(0, 50)
    })).data

    return createUserFromBackendUserInfo(data)
}

export async function logout() {
    await axios.post('/logout')
    window.location.href = '/'
}

export async function changeUserInfo(user) {
    return await axios.post('/user/info', {
        user_name: user.username,
        gender: user.gender,
        description: user.description
    })
}
