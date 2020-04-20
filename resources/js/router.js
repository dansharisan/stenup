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
import Registration from './views/_shared/pages/Registration'
import PasswordResetRequest from './views/_shared/pages/PasswordResetRequest'
import PasswordReset from './views/_shared/pages/PasswordReset'
import UserInfo from './views/_shared/pages/UserInfo'
import AccountActivation from './views/_shared/pages/AccountActivation'
import AccountActivationRequest from './views/_shared/pages/AccountActivationRequest'

// User site
import Index from './views/user/pages/Index'

// Admin Tools
import Dashboard from './views/admin/pages/Dashboard'
import Users from './views/admin/pages/Users'
import RolesPermissions from './views/admin/pages/RolesPermissions'

import store from './store/index.js';
import AuthPlugin from './plugins/auth.js'
import { COMPONENT_NAME, PERMISSION_NAME } from './const.js';
Vue.use(Router)
Vue.use(AuthPlugin)

function requireAccessPermission(to, from, next) {
    // Check if current has the permission to access the component
    function checkAccessPermission(permission) {
        if (Vue.prototype.hasPermission(store.get('auth/user'), permission)) {
            next()
        } else {
            next('/403')
        };
    }

    function checkRouteAccessLogic() {
        switch (to.name) {
            case COMPONENT_NAME.DASHBOARD:
                checkAccessPermission(PERMISSION_NAME.VIEW_DASHBOARD)
                break;
            case COMPONENT_NAME.USERS:
                checkAccessPermission(PERMISSION_NAME.VIEW_USERS)
                break;
            case COMPONENT_NAME.ROLES_PERMISSIONS:
                checkAccessPermission(PERMISSION_NAME.VIEW_ROLES_PERMISSIONS)
                break;
            default:
                next('/')
        }
    }

    // In case user info is already in the store
    if (store.get('auth/userLoadStatus') == 2) {
        checkRouteAccessLogic()
    } else {
        // Not authorized or auth state has changed
        if (from.name) {
            Vue.prototype.handleInvalidAuthState(router.app)
        } else {
            next('/403')
        }
    }
}

function requireNonAuth (to, from, next) {
    if (store.get('auth/userLoadStatus') != 1 && store.get('auth/logoutLoadStatus') != 1) {
        if (store.get('auth/userLoadStatus') != 2) {
            next()
        } else {
            if (from.name) {
                Vue.prototype.handleInvalidAuthState(router.app)
            } else {
                next('/userinfo')
            }
        }
    }
}

function requireAuth (to, from, next) {
    if (store.get('auth/userLoadStatus') != 1 && store.get('auth/logoutLoadStatus') != 1) {
        if (store.get('auth/userLoadStatus') == 2) {
            next()
        } else {
            if (from.name) {
                Vue.prototype.handleInvalidAuthState(router.app)
            } else {
                next('/login')
            }
        }
    }
}

const router = new Router({
    mode           : 'history',
    linkActiveClass: 'open active',
    scrollBehavior : () => ({ y: 0 }),
    routes         : [
        // User site routes
        {
            path     : '/',
            redirect : '/index',
            name     : COMPONENT_NAME.HOME,
            component: UserContainer,
            children : [
                {
                    path     : 'index',
                    name     : COMPONENT_NAME.INDEX,
                    component: Index,
                },
            ],
        },
        // From below are admin panel routes
        {
            path     : '/admin',
            redirect : '/admin/dashboard',
            name     : 'Panel',
            component: AdminContainer,
            beforeEnter: requireAuth,
            children : [
                {
                    path     : 'dashboard',
                    name     : COMPONENT_NAME.DASHBOARD,
                    component: Dashboard,
                    beforeEnter: requireAccessPermission
                },
                {
                    path     : 'users',
                    name     : COMPONENT_NAME.USERS,
                    component: Users,
                    beforeEnter: requireAccessPermission
                },
                {
                    path     : 'roles-permissions',
                    name     : COMPONENT_NAME.ROLES_PERMISSIONS,
                    component: RolesPermissions,
                    beforeEnter: requireAccessPermission
                },
            ],
        },
        // From below are general pages
        {
            path     : '/404',
            name     : COMPONENT_NAME.PAGE_404,
            component: Page404,
        },
        {
            path     : '/403',
            name     : COMPONENT_NAME.PAGE_403,
            component: Page403,
        },
        {
            path     : '/500',
            name     : COMPONENT_NAME.PAGE_500,
            component: Page500,
        },
        {
            path     : '/login',
            name     : COMPONENT_NAME.LOGIN,
            component: Login,
            beforeEnter: requireNonAuth
        },
        {
            path     : '/register',
            name     : COMPONENT_NAME.REGISTRATION,
            component: Registration,
            beforeEnter: requireNonAuth
        },
        {
            path     : '/forgot-password',
            name     : COMPONENT_NAME.PASSWORD_RESET_REQUEST,
            component: PasswordResetRequest,
            beforeEnter: requireNonAuth
        },
        {
            path     : '/reset-password/:token',
            name     : COMPONENT_NAME.PASSWORD_RESET,
            component: PasswordReset
        },
        {
            path     : '/activate-account',
            name     : COMPONENT_NAME.ACCOUNT_ACTIVATION_REQUEST,
            component: AccountActivationRequest
        },
        {
            path     : '/activate-account/:token',
            name     : COMPONENT_NAME.ACCOUNT_ACTIVATION,
            component: AccountActivation
        },
        {
            path     : '/userinfo',
            name     : COMPONENT_NAME.USER_INFO,
            component: UserInfo,
            beforeEnter: requireAuth
        },
        {
            path     : '*',
            name     : COMPONENT_NAME.P404,
            component: Page404,
        },
    ],
})

router.beforeEach((to, from, next) => {
    if (store.get('auth/userLoadStatus') != 1 && store.get('auth/logoutLoadStatus') != 1) {
        store.dispatch('auth/getUser')
        var unwatch = store.watch(store.getters['auth/getUserLoadStatus'], n => {
            unwatch()
            next()
        })
    } else {
        next()
    }
})

export default router
