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
                // Show success message
                vm.$snotify.success("Logged out successfully")
                // Only redirect if current page is not Index
                if (vm.$route.name != 'Index') {
                    vm.$router.push({ name: 'Index' })
                }
            })
            .catch(function(error) {
                // Handle unauthorized error
                if (error.response && error.response.status) {
                    vm.handleInvalidAuthState(error.response.status)
                } else {
                    vm.$snotify.error("Failed to log out")
                }
            })
        },
    }
}
