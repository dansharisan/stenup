const AuthPlugin = {
    install(Vue, options) {
        Vue.hasPermission = function(user, reqPermission) {
            var hasPermission = false
            user.roles.forEach(function (role, index) {
                role.permissions.forEach(function (permission, index) {
                    if (permission.name == reqPermission) {
                        hasPermission = true
                        // If I can break here, that would save some resource on specific cases
                    }
                })
            });

            return hasPermission
        }
    }
};

export default AuthPlugin;
