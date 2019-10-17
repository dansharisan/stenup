require('./bootstrap');

import Vue from 'vue'
import App from './views/App.vue'
import router from './router.js'
import store from './store/index.js'

// Vuelidate
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

// Bootstrap vue
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

// Fontawesome
import '@fortawesome/fontawesome-free/css/all.css'
import '@fortawesome/fontawesome-free/js/all.js'

// Spinner loading icon
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
Vue.component('loading', {
    extends: Loading,
    props  : {
        transition: {
            type: String,
            default: 'fade'
        },
        loader: {
            type: String,
            default: 'spinner' //spinner or dots or bars
        },
        opacity: {
            type   : Number,
            default: 0.8,
        },
        color: {
            type   : String,
            default: '#1B8EB7',
        },
        isFullPage: {
            type   : Boolean,
            default: false,
        },
        height: {
            type   : Number,
            default: 32,
        },
        width: {
            type   : Number,
            default: 32,
        },
        zIndex: {
            type   : Number,
            default: 100000,
        },
    }
})

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
    store     : store,
    components: { App },
    template  : '<App/>',
})
