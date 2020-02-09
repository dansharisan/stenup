export const AuthUtils = {
    methods: {
        hasRole (userObject, roleName) {
            var roles = this.getRoles(userObject)

            return roles.includes(roleName);
        },
        getRoles (userObject) {
            if (!userObject.roles) {
                return []
            }
            var roles = []
            for (let roleObject of userObject.roles) {
                roles.push(roleObject.name)
            }

            return roles
        },
        logout () {
            var vm = this
            vm.$store.dispatch('auth/logout')
            .then(response => {
                // Only redirect if current page is not Index
                if (vm.$route.name != 'Index') {
                    vm.$router.push({ name: 'Index' })
                }
            })
            .catch(function(error) {
                if (error.response) {
                    // Show message error
                    vm.$snotify.error("Server error")
                } else {
                    vm.$snotify.error("Network error")
                }
            })
        },
    }
}
