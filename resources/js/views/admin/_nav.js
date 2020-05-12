import { PERMISSION_NAME } from '../../const.js'

export default {
    items: [
        {
            name : 'Dashboard',
            url  : '/admin/dashboard',
            icon : 'icon-speedometer',
            permission: PERMISSION_NAME.READ_GENERAL_STATS
        },
        {
            name   : 'Users',
            url  : '/admin/users',
            icon : 'icon-user',
            permission: PERMISSION_NAME.READ_USERS
        },
        {
            name   : 'Settings',
            icon : 'icon-settings',
            children: [
                {
                    name   : 'Roles & Permissions',
                    url  : '/admin/roles-permissions',
                    permission: PERMISSION_NAME.READ_ROLES_PERMISSIONS
                },
            ]
        },
    ],
}
