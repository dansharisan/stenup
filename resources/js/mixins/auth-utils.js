export const AuthUtils = {
    methods: {
        hasRole(userObject, roleName) {
            var roles = this.getRoles(userObject)

            return roles.includes(roleName);
        },
        getRoles(userObject) {
            if (!userObject.roles) {
                return []
            }
            var roles = []
            for (let roleObject of userObject.roles) {
                roles.push(roleObject.name)
            }

            return roles
        }
    }
}
