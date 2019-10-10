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

Vue.use(Router)

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
            // beforeEnter: requireAdmin,
            children : [
                {
                    path     : 'dashboard',
                    name     : 'Dashboard',
                    component: Dashboard,
                },
                // {
                //     path     : 'users',
                //     name     : 'Users',
                //     component: Users,
                // },
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
            // beforeEnter: requireNonAuth
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
            // beforeEnter: requireAuth
        },
        {
            path     : '*',
            name     : '404',
            component: Page404,
        },
    ],
})
