import Vue from 'vue'
import Router from 'vue-router'

// Containers
import AdminContainer from './views/admin/Container'
import UserContainer from './views/user/Container'

// Views - Pages
import Page403 from './views/_shared/pages/Page403'
import Page404 from './views/_shared/pages/Page404'
import Page500 from './views/_shared/pages/Page500'
import Login from './views/_shared/pages/Login'
import Register from './views/_shared/pages/Register'
import ForgotPassword from './views/_shared/pages/ForgotPassword'
import ResetPassword from './views/_shared/pages/ResetPassword'
import UserInfo from './views/_shared/pages/UserInfo'
import ActivateAccount from './views/_shared/pages/ActivateAccount'

// User site
import Index from './views/user/pages/Index'

// Admin Tools
import Dashboard from './views/admin/pages/Dashboard'
import Users from './views/admin/pages/Users'
import RolesPermissions from './views/admin/pages/RolesPermissions'

import store from './store/index.js';
Vue.use(Router)

function requireNonAuth (to, from, next) {
    if (store.get('auth/user') && store.get('auth/user').id) {
        next('/home')
    } else {
        if (store.get('auth/userLoadStatus') == 3) {
            next()
        } else {
            store.dispatch('auth/getUser')
            store.watch(store.getters['auth/getUserLoadStatus'], n => {
                if (store.get('auth/userLoadStatus') == 2) {
                    next('/home')
                } else if (store.get('auth/userLoadStatus') == 3) {
                    next()
                }
            })
        }
    }
}

function requireAuth (to, from, next) {
    if (store.get('auth/user') && store.get('auth/user').id) {
        next()
    } else {
        if (store.get('auth/userLoadStatus') == 3) {
            next('/login')
        } else {
            store.dispatch('auth/getUser')
            store.watch(store.getters['auth/getUserLoadStatus'], n => {
                if (store.get('auth/userLoadStatus') == 2) {
                    next()
                } else if (store.get('auth/userLoadStatus') == 3) {
                   next('/login')
                }
            })
        }
    }
}

export default new Router({
    mode           : 'history',
    linkActiveClass: 'open active',
    scrollBehavior : () => ({ y: 0 }),
    routes         : [
        {
            path     : '/',
            redirect : '/index',
            name     : 'Home',
            component: UserContainer,
            children : [
                {
                    path     : 'index',
                    name     : 'Index',
                    component: Index,
                },
            ],
        },
        // From below are admin routes
        {
            path     : '/admin',
            redirect : '/admin/dashboard',
            name     : 'Panel',
            component: AdminContainer,
            beforeEnter: requireAuth,
            children : [
                {
                    path     : 'dashboard',
                    name     : 'Dashboard',
                    component: Dashboard,
                },
                {
                    path     : 'users',
                    name     : 'Users',
                    component: Users,
                },
                {
                    path     : 'roles-permissions',
                    name     : 'Roles & Permissions',
                    component: RolesPermissions,
                },
            ],
        },
        // From below are general pages
        {
            path     : '/404',
            name     : 'Page404',
            component: Page404,
        },
        {
            path     : '/403',
            name     : 'Page403',
            component: Page403,
        },
        {
            path     : '/500',
            name     : 'Page500',
            component: Page500,
        },
        {
            path     : '/login',
            name     : 'Login',
            component: Login,
            beforeEnter: requireNonAuth
        },
        {
            path     : '/register',
            name     : 'Register',
            component: Register,
            // beforeEnter: requireNonAuth
        },
        {
            path     : '/forgot-password',
            name     : 'ForgotPassword',
            component: ForgotPassword,
            // beforeEnter: requireNonAuth
        },
        {
            path     : '/reset-password/:token',
            name     : 'ResetPassword',
            component: ResetPassword
        },
        {
            path     : '/activate-account/:token',
            name     : 'ActivateAccount',
            component: ActivateAccount
        },
        {
            path     : '/userinfo',
            name     : 'UserInfo',
            component: UserInfo,
            beforeEnter: requireAuth
        },
        {
            path     : '*',
            name     : '404',
            component: Page404,
        },
    ],
})
