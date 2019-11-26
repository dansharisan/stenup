const AuthPlugin = {
    install(Vue, options) {
        Vue.prototype.hasPermission = function(user, reqPermission) {
            var hasPermission = false
            if (user.associated_permissions) {
                user.associated_permissions.forEach(function (permission, index) {
                    if (permission.name == reqPermission) {
                        hasPermission = true
                        // If I can break here, that would save some resource on specific cases
                    }
                })
            }

            return hasPermission
        }
    }
};

export default AuthPlugin;
