import { PERMISSION_NAME } from '../../const.js'

export default {
    items: [
        {
            name : 'Dashboard',
            url  : '/admin/dashboard',
            icon : 'icon-speedometer',
            class: 'font-sm',
            permission: PERMISSION_NAME.VIEW_DASHBOARD
        },
        {
            name   : 'Users',
            url  : '/admin/users',
            icon : 'icon-user',
            class: 'font-sm',
            permission: PERMISSION_NAME.VIEW_USERS
        },
        {
            name   : 'Roles & Permissions',
            url  : '/admin/roles-permissions',
            icon : 'icon-organization',
            class: 'font-sm',
            permission: PERMISSION_NAME.VIEW_ROLES_PERMISSIONS
        },
    ],
}
