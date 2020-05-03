import { defaultMutations } from 'vuex-easy-access'
import AuthAPI from '../../api/auth.js'

const state = {
    user: {},
    userLoadStatus: 0,
    logoutLoadStatus: 0,
    rolesAndPermissions: {roles: [], permissions: []},
    rolesAndPermissionsLoadStatus: 0
}

// add generate mutation vuex easy access
// https://mesqueeb.github.io/vuex-easy-access/setup.html#setup
const mutations = { ...defaultMutations(state) }

const getters = {
    getUser: state => () => state.user,
    getUserLoadStatus: state => () => state.userLoadStatus,
    getLogoutLoadStatus: state => () => state.logoutLoadStatus,
    getRolesAndPermissions: state => () => state.rolesAndPermissions,
    getRolesAndPermissionsLoadStatus: state => () => state.rolesAndPermissionsLoadStatus
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

    getRolesAndPermissions ({ commit }) {
        var vm = this._vm
        commit('rolesAndPermissionsLoadStatus', 1)

        AuthAPI.getRolesAndPermissions()
        .then((response) => {
            commit('rolesAndPermissionsLoadStatus', 2)
            commit('rolesAndPermissions', response.data)
        })
        .catch(function(e) {
            // Handle unauthorized error
            if (e.response && e.response.status == 401) {
                vm.handleInvalidAuthState(vm)
            } else {
                commit('rolesAndPermissionsLoadStatus', 3)
                commit('rolesAndPermissions', [])
            }
        })
    },

    logout ({ commit }) {
        commit('logoutLoadStatus', 1)

        return new Promise((resolve, reject) => {
            AuthAPI.logout()
            .then((response) => {
                commit('logoutLoadStatus', 2)
                commit('userLoadStatus', 0)
                commit('user', {})
                // Return successful response
                resolve(response)
            })
            .catch((error) => {
                commit('logoutLoadStatus', 3)
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
