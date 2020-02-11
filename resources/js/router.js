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
    if (store.get('auth/user') && store.get('auth/user').id) {
        checkRouteAccessLogic()
    } else {
        // If unauthenticated, redirect to login page
        if (store.get('auth/userLoadStatus') == 3) {
            next('/login')
        } else {
            // Before request to auth, make sure it's not being requested
            if (store.get('auth/userLoadStatus') != 1) {
                store.dispatch('auth/getUser')
            }
            store.watch(store.getters['auth/getUserLoadStatus'], n => {
                if (store.get('auth/userLoadStatus') == 2) {
                    checkRouteAccessLogic()
                } else if (store.get('auth/userLoadStatus') == 3) {
                    next('/login')
                }
            })
        }
    }
}

function checkAuth (to, from, next) {
    if (store.get('auth/userLoadStatus') == 0 && store.get('auth/logoutLoadStatus') != 2) {
        store.dispatch('auth/getUser')
        var unwatch = store.watch(store.getters['auth/getUserLoadStatus'], n => {
            unwatch()
            next()
        })
    } else {
        next()
    }
}

function requireNonAuth (to, from, next) {
    if (store.get('auth/user') && store.get('auth/user').id) {
        next('/')
    } else {
        if (store.get('auth/userLoadStatus') == 3) {
            next()
        } else {
            // Before request to auth, make sure it's not being requested
            if (store.get('auth/userLoadStatus') != 1) {
                store.dispatch('auth/getUser')
            }
            store.watch(store.getters['auth/getUserLoadStatus'], n => {
                if (store.get('auth/userLoadStatus') == 2) {
                    next('/')
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
            // Before request to auth, make sure it's not being requested
            if (store.get('auth/userLoadStatus') != 1) {
                store.dispatch('auth/getUser')
            }
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
        // User site routes
        {
            path     : '/',
            redirect : '/index',
            name     : COMPONENT_NAME.HOME,
            component: UserContainer,
            beforeEnter: checkAuth,
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
            name     : COMPONENT_NAME.REGISTER,
            component: Register,
            beforeEnter: requireNonAuth
        },
        {
            path     : '/forgot-password',
            name     : COMPONENT_NAME.FORGOT_PASSWORD,
            component: ForgotPassword,
            beforeEnter: requireNonAuth
        },
        {
            path     : '/reset-password/:token',
            name     : COMPONENT_NAME.RESET_PASSWORD,
            component: ResetPassword
        },
        {
            path     : '/activate-account/:token',
            name     : COMPONENT_NAME.ACTIVATE_ACCOUNT,
            component: ActivateAccount
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
