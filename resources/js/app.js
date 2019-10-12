require('./bootstrap');

import Vue from 'vue'
import App from './views/App.vue'
import router from './router.js'
// import store from './store'

// Bootstrap vue
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

// Fontawesome
import '@fortawesome/fontawesome-free/css/all.css'
import '@fortawesome/fontawesome-free/js/all.js'

// Snotify
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
Vue.use(Snotify, snotifyOptions)

export default new Vue({
    el        : '#app',
    router    : router,
    // store     : store,
    components: { App },
    template  : '<App/>',
})
