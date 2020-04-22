const AuthPlugin = {
    install(Vue, options) {
        Vue.prototype.hasPermission = function (user, reqPermission) {
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

        Vue.prototype.handleInvalidAuthState = function (vm) {
            let timerInterval
            vm.$swal({
                title: 'Unauthorized',
                html: "Something went wrong. Either you're not authorized to do this action or your authentication state has changed. We will redirect you to Home page in <b></b> seconds.",
                timer: 3000,
                icon: 'warning',
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
                window.location.replace('/');
            })
        }
    }
};

export default AuthPlugin;
