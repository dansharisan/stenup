import { APP_CONFIG } from '../const.js';

export default {
    /*
    GET /api/users
    To get list of users information
    */
    getUsers: function(page = 1, perPage = 25) {
        return axios.get(APP_CONFIG.API_URL + '/users?page=' + page + '&per_page=' + perPage);
    },

    /*
    GET /api/users/{id}
    Get a specific user information
    */
    readUser: function(userId) {
        return axios.get(APP_CONFIG.API_URL + '/users/' + userId);
    },

    /*
    PATCH /api/users/{id}/ban
    To ban a user
    */
    banUser: function(userId) {
        return axios.patch(APP_CONFIG.API_URL + '/users/' + userId +'/ban');
    },

    /*
    PATCH /api/users/{id}/unban
    To unban a user
    */
    unbanUser: function(userId) {
        return axios.patch(APP_CONFIG.API_URL + '/users/' + userId + '/unban');
    },

    /*
    DELETE /api/users/{id}
    To delete n user
    */
    deleteUser: function(userId) {
        return axios.delete(APP_CONFIG.API_URL + '/users/' + userId);
    },

    /*
    POST /api/users/collection:batchDelete
    To delete a selected collection of users
    */
    deleteUsers: function(userIdsSeq) {
        return axios.post(APP_CONFIG.API_URL + '/users/collection:batchDelete',
        {
            ids: userIdsSeq,
        });
    },

    /*
    PATCH /api/users/{id}/
    To update a user
    */
    updateUser: function(id, email, email_verified_at, role_ids) {
        return axios.patch(APP_CONFIG.API_URL + '/users/' + id,
        {
            email: email,
            email_verified_at: email_verified_at,
            role_ids: role_ids
        });
    },

    /*
    POST /api/users/
    To create a new user
    */
    createUser: function(email, email_verified_at, password, password_confirmation, role_ids) {
        return axios.post(APP_CONFIG.API_URL + '/users',
        {
            email: email,
            password: password,
            password_confirmation: password_confirmation,
            role_ids: role_ids,
            email_verified_at: email_verified_at
        });
    },

    /*
    Get /api/users/registered_user_stats
    To get user stats
    */
    getUserStats: function() {
        return axios.get(APP_CONFIG.API_URL + '/users/registered_user_stats');
    },
}
