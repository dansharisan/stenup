import { APP_CONFIG } from '../const.js';

export default {
    /*
    POST /api/user
    Get access token
    */
    getAccessToken: function(email, password) {
        return axios.post(APP_CONFIG.API_URL + '/auth/login',
        {
            email: email,
            password: password
        });
    },

    /*
    GET /api/auth/getUser
    To get user information
    */
    getUser: function() {
        return axios.get(APP_CONFIG.API_URL + '/auth/getUser');
    },

    /*
    GET /api/auth/password/token/create
    Generate password reset token and send that token to user through mail
    */
    createPasswordResetToken: function(email) {
        return axios.post(APP_CONFIG.API_URL + '/auth/password/token/create',
        {
            email: email,
        });
    },

    /*
    GET /api/auth/password/token/find/:token
    Find the reset password token
    */
    findPasswordResetToken: function(token) {
        return axios.get(APP_CONFIG.API_URL + '/auth/password/token/find/' + token);
    },

    /*
    GET /api/auth/register/activate/:token
    Activate account
    */
    activateAccount: function(token) {
        return axios.get(APP_CONFIG.API_URL + '/auth/register/activate/' + token);
    },

    /*
    POST /api/auth/register/resend_activation_email
    Resend activation email
    */
    resendActivationEmail: function(email) {
        return axios.post(APP_CONFIG.API_URL + '/auth/register/resend_activation_email',
        {
            email: email
        });
    },

    /*
    PATCH /api/auth/password/reset
    Reset password
    */
    resetPassword: function(email, password, password_confirmation, token) {
        return axios.patch(APP_CONFIG.API_URL + '/auth/password/reset',
        {
            email: email,
            password: password,
            password_confirmation: password_confirmation,
            token: token
        });
    },

    /*
    PATCH /api/auth/password/reset
    Reset password
    */
    changePassword: function(password, new_password, new_password_confirmation) {
        return axios.patch(APP_CONFIG.API_URL + '/auth/password/change',
        {
            password: password,
            new_password: new_password,
            new_password_confirmation: new_password_confirmation
        });
    },

    /*
    GET /api/auth/logout
    Logout
    */
    logout: function() {
        return axios.get( APP_CONFIG.API_URL + '/auth/logout' );
    },

    /*
    POST /api/auth/register
    Register
    */
    register: function(email, password, password_confirmation) {
        return axios.post(APP_CONFIG.API_URL + '/auth/register',
        {
            email: email,
            password: password,
            password_confirmation: password_confirmation
        });
    },

    /*
    GET /api/auth/roles_permissions
    Get all roles and permissions
    */
    getRolesAndPermissions: function() {
        return axios.get( APP_CONFIG.API_URL + '/auth/roles_permissions' );
    },

    /*
    GET /api/auth/roles_w_permissions
    Get all roles with associated permissions
    */
    getRolesWithPermissions: function() {
        return axios.get( APP_CONFIG.API_URL + '/auth/roles_w_permissions' );
    },

    /*
    POST /api/auth/roles
    Create a new role
    */
    createRole: function(role_name) {
        return axios.post(APP_CONFIG.API_URL + '/auth/roles',
        {
            role_name: role_name,
        });
    },

    /*
    DELETE /api/auth/roles/{id}
    Delete a specified role
    */
    deleteRole: function(id) {
        return axios.delete(APP_CONFIG.API_URL + '/auth/roles/' + id);
    },

    /*
    PUT /api/auth/update_roles_permissions_matrix
    Update roles and permissions
    */
    updateRolesPermissionsMatrix: function(matrix) {
        return axios.put(APP_CONFIG.API_URL + '/auth/update_roles_permissions_matrix',
        {
            matrix: JSON.stringify(matrix),
        });
    }
}
