import { defaultMutations } from 'vuex-easy-access'
import { APP_CONFIG } from '../../config.js'
import AuthAPI from '../../api/auth.js'

const state = {
    user: {},
    userLoadStatus: 0
}

// add generate mutation vuex easy access
// https://mesqueeb.github.io/vuex-easy-access/setup.html#setup
const mutations = { ...defaultMutations(state) }

const getters = {
    getUser: state => () => state.user,
    getUserLoadStatus: state => () => state.userLoadStatus
}

const actions = {
    getUser ({ commit }) {
        commit('userLoadStatus', 1)

        AuthAPI.getUser()
        .then((response) => {
            commit('userLoadStatus', 2)
            commit('user', response.data.user)
        })
        .catch( function( e ) {
            commit('userLoadStatus', 3)
            commit('user', {})
        })
    },

    logout ({ commit }) {
        return new Promise((resolve, reject) => {
            AuthAPI.logout()
            .then((response) => {
                commit('userLoadStatus', 0)
                commit('user', {})
                // Return successful response
                resolve(response)
            })
            .catch((error) => {
                // Return error
                reject(error)
            })
        })
    },

    login ({ commit }, credential) {
        commit('userLoadStatus', 1)

        return new Promise((resolve, reject) => {
            AuthAPI.getAccessToken(credential.email, credential.password)
            .then((response) => {
                commit('userLoadStatus', 2)
                commit('user', response.data.user)
                // Return successful response
                resolve(response)
            })
            .catch((error) => {
                commit('userLoadStatus', 3)
                commit('user', {})
                // Return error
                reject(error)
            })
        })
    }
}

export default {
    state,
    mutations,
    actions,
    getters
}
