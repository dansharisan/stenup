import Vue from 'vue'
import Vuex from 'vuex'
import EasyAccess from 'vuex-easy-access'

import state from './state'
import mutations from './mutations'
import modules from './modules'

import AuthPlugin from '../plugins/auth.js'

Vue.use(Vuex)
Vue.use(AuthPlugin)

export default new Vuex.Store({
    state,
    mutations,
    modules,
    plugins: [EasyAccess()],
})
