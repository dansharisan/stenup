require('./bootstrap');

import Vue from 'vue'
import App from './views/App.vue'
import BootstrapVue from 'bootstrap-vue'
import router from './router.js'
// import store from './store'
import Snotify, { SnotifyPosition } from 'vue-snotify'

const snotifyOptions = {
    toast: {
        position: SnotifyPosition.rightBottom,
        timeout: 1000,
        showProgressBar: true,
        closeOnClick: true,
        pauseOnHover: true
    }
}

Vue.use(BootstrapVue)
Vue.use(Snotify, snotifyOptions)

export default new Vue({
    el        : '#app',
    router    : router,
    // store     : store,
    components: { App },
    template  : '<App/>',
})
