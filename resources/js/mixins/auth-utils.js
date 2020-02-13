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
                vm.handleAuthError(error)
                vm.$snotify.error("Something is not right")
            })
        },
        handleAuthError (error) {
            var vm = this
            // Handle unauthorized error
            if (error.response && error.response.status == 401) {
                let timerInterval
                vm.$swal({
                    title: 'Unauthorized',
                    html: "You're not authorized to do this action. We will redirect you to Home page in <b></b> seconds.",
                    timer: 3000,
                    onBeforeOpen: () => {
                        vm.$swal.showLoading()
                        timerInterval = setInterval(() => {
                            const content = vm.$swal.getContent()
                            if (content) {
                                const b = content.querySelector('b')
                                if (b) {
                                    b.textContent = Math.ceil(parseInt(vm.$swal.getTimerLeft()) / 1000)
                                }
                            }
                        }, 100)
                    },
                    onClose: () => {
                        clearInterval(timerInterval)
                    }
                })
                .then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === vm.$swal.DismissReason.timer) {
                        // Only redirect if current page is not Index
                        if (vm.$route.name != 'Index') {
                            vm.$router.push({ name: 'Index' })
                        } else {
                            // Retrieve the current auth status
                            vm.$store.dispatch('auth/getUser')
                        }
                    }
                })
        }
    }
    }
}
