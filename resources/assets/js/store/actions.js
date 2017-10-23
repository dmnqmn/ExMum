import axios from 'axios'
import crypto from 'crypto-js'

export async function fetchIdentification({ commit }) {
    let response

    try {
        response = await axios.get('/user/info')
    } catch (error) {
        return null
    }

    if (response.status !== 200) {
        return null
    }

    let { success, data } = response.data

    if (!success) {
        return null
    }

    commit('SET_IDENTIFICATION', data)
    return data
}

export async function register({ state, commit }, { email, password }) {
    let identification = (await axios.post('/register', {
        email: email,
        password: crypto.SHA256(password).toString(crypto.enc.Hex).slice(0, 50)
    })).data

    commit('SET_IDENTIFICATION', identification)
}

export async function login({ state, commit }, { email, password }) {
    let identification = (await axios.post('/login', {
        email: email,
        password: crypto.SHA256(password).toString(crypto.enc.Hex).slice(0, 50)
    })).data

    commit('SET_IDENTIFICATION', identification)
}
