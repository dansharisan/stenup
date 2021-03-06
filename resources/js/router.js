import Vue from 'vue'
import Router from 'vue-router'
import store from './store/index.js';
import { COMPONENT_NAME, PERMISSION_NAME } from './const.js';
Vue.use(Router)

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
                checkAccessPermission(PERMISSION_NAME.READ_GENERAL_STATS)
                break;
            case COMPONENT_NAME.USERS:
                checkAccessPermission(PERMISSION_NAME.READ_USERS)
                break;
            case COMPONENT_NAME.ROLES_PERMISSIONS:
                checkAccessPermission(PERMISSION_NAME.READ_ROLES_PERMISSIONS)
                break;
            default:
                next('/')
        }
    }

    // In case user info is already in the store
    if (store.get('auth/userLoadStatus') == 2) {
        checkRouteAccessLogic()
    } else if (store.get('auth/userLoadStatus') == 3) {
        next('/403')
    }
}

function requireNonAuth (to, from, next) {
    if (store.get('auth/userLoadStatus') == 3) {
        next()
    } else if (store.get('auth/userLoadStatus') == 2 && store.get('auth/logoutLoadStatus') != 1) {
        next('/userinfo')
    }
}

function requireAuth (to, from, next) {
    if (store.get('auth/userLoadStatus') == 2 && store.get('auth/logoutLoadStatus') != 1) {
        next()
    } else if (store.get('auth/userLoadStatus') == 3) {
        next('/login')
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
            component: () => import(/* webpackChunkName: 'user_container' */ './views/user/Container'),
            children : [
                {
                    path     : 'index',
                    name     : COMPONENT_NAME.INDEX,
                    component: () => import(/* webpackChunkName: 'user_index' */ './views/user/pages/Index'),
                },
            ],
        },
        // From below are admin panel routes
        {
            path     : '/admin',
            redirect : '/admin/dashboard',
            name     : 'Panel',
            component: () => import(/* webpackChunkName: 'admin_container' */ './views/admin/Container'),
            beforeEnter: requireAuth,
            children : [
                {
                    path     : 'dashboard',
                    name     : COMPONENT_NAME.DASHBOARD,
                    component: () => import(/* webpackChunkName: 'admin_dashboard' */ './views/admin/pages/Dashboard'),
                    beforeEnter: requireAccessPermission
                },
                {
                    path     : 'users',
                    name     : COMPONENT_NAME.USERS,
                    component: () => import(/* webpackChunkName: 'admin_users' */ './views/admin/pages/Users'),
                    beforeEnter: requireAccessPermission
                },
                {
                    path     : 'roles-permissions',
                    name     : COMPONENT_NAME.ROLES_PERMISSIONS,
                    component: () => import(/* webpackChunkName: 'admin_rolespermissions' */ './views/admin/pages/RolesPermissions'),
                    beforeEnter: requireAccessPermission
                },
            ],
        },
        // From below are general pages
        {
            path     : '/404',
            name     : COMPONENT_NAME.PAGE_404,
            component: () => import(/* webpackChunkName: 'page404' */ './views/_shared/pages/Page404'),
        },
        {
            path     : '/403',
            name     : COMPONENT_NAME.PAGE_403,
            component: () => import(/* webpackChunkName: 'page403' */ './views/_shared/pages/Page403'),
        },
        {
            path     : '/500',
            name     : COMPONENT_NAME.PAGE_500,
            component: () => import(/* webpackChunkName: 'page500' */ './views/_shared/pages/Page500'),
        },
        {
            path     : '/login',
            name     : COMPONENT_NAME.LOGIN,
            component: () => import(/* webpackChunkName: 'login' */ './views/_shared/pages/Login'),
            beforeEnter: requireNonAuth
        },
        {
            path     : '/register',
            name     : COMPONENT_NAME.REGISTRATION,
            component: () => import(/* webpackChunkName: 'registration' */ './views/_shared/pages/Registration'),
            beforeEnter: requireNonAuth
        },
        {
            path     : '/forgot-password',
            name     : COMPONENT_NAME.PASSWORD_RESET_REQUEST,
            component: () => import(/* webpackChunkName: 'passwordresetrequest' */ './views/_shared/pages/PasswordResetRequest'),
            beforeEnter: requireNonAuth
        },
        {
            path     : '/reset-password/:token',
            name     : COMPONENT_NAME.PASSWORD_RESET,
            component: () => import(/* webpackChunkName: 'passwordreset' */ './views/_shared/pages/PasswordReset')
        },
        {
            path     : '/activate-account',
            name     : COMPONENT_NAME.ACCOUNT_ACTIVATION_REQUEST,
            component: () => import(/* webpackChunkName: 'accountactivationrequest' */ './views/_shared/pages/AccountActivationRequest'),
        },
        {
            path     : '/activate-account/:token',
            name     : COMPONENT_NAME.ACCOUNT_ACTIVATION,
            component: () => import(/* webpackChunkName: 'accountactivation' */ './views/_shared/pages/AccountActivation'),
        },
        {
            path     : '/userinfo',
            name     : COMPONENT_NAME.USER_INFO,
            component: () => import(/* webpackChunkName: 'userinfo' */ './views/_shared/pages/UserInfo'),
            beforeEnter: requireAuth
        },
        {
            path     : '/change-password',
            name     : COMPONENT_NAME.PASSWORD_CHANGE,
            component: () => import(/* webpackChunkName: 'passwordchange' */ './views/_shared/pages/PasswordChange'),
            beforeEnter: requireAuth
        },
        {
            path     : '*',
            name     : COMPONENT_NAME.P404,
            component: () => import(/* webpackChunkName: 'page404' */ './views/_shared/pages/Page404'),
        },
    ],
})

router.beforeEach((to, from, next) => {
    if (store.get('auth/userLoadStatus') == 0) {
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
