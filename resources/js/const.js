/*
	Defines the API route we are using.
*/
var location = window.location;
var api_url = location.protocol + '//' + location.host + '/api';

export const APP_CONFIG = {
    API_URL: api_url
}

export const COMPONENT_NAME = {
    INDEX: "Index",
    HOME: "Home",
    USERS: "Users",
    DASHBOARD: "Dashboard",
    ROLES_PERMISSIONS: "Roles & Permissions",
    P404: "404",
    PAGE_404: "Error 404",
    PAGE_403: "Error 403",
    PAGE_500: "Error 500",
    LOGIN: "Login",
    REGISTER: "Register",
    FORGOT_PASSWORD: "Forgot Password",
    RESET_PASSWORD: "Reset Password",
    ACTIVATE_ACCOUNT: "Activate Account",
    USER_INFO: "User Info"
}

export const PERMISSION_NAME = {
    VIEW_USERS: 'view-users',
    CREATE_USERS: 'create-users',
    UPDATE_USERS: 'update-users',
    DELETE_USERS: 'delete-users',
    VIEW_DASHBOARD: 'view-dashboard',
    VIEW_ROLES_PERMISSIONS: 'view-roles-permissions',
    CREATE_ROLES: 'create-roles',
    UPDATE_ROLES: 'update-roles',
    DELETE_ROLES: 'delete-roles',
    UPDATE_PERMISSIONS: 'update-permissions',
}
