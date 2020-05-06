<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <b-row class="justify-content-center">
                <b-col md="4" class="mr-2 ml-2 pr-0 pl-0">
                    <loading :active="activateAccountRequest.status == 1"></loading>
                    <b-card-group>
                        <b-card no-body class="mb-0">
                            <b-card-header><h2 class="m-0">Activate account</h2></b-card-header>
                            <b-card-body>
                                <p class="text-muted">
                                    Activate your account
                                </p>
                                <div :class="'alert alert-' + notification.type" id="message" v-if="notification.message" v-html="notification.message" role="alert">
                                </div>
                                <b-row>
                                    <b-col cols="6" class="text-left">
                                        <b-button variant="link" class="px-0" @click="$router.push({ name: 'Login' })">
                                            Log in
                                        </b-button>
                                        <br />
                                        <b-button variant="link" class="px-0" @click="$router.push({ name: 'Home' })">
                                            Back to Home
                                        </b-button>
                                    </b-col>
                                </b-row>
                            </b-card-body>
                        </b-card>
                    </b-card-group>
                </b-col>
            </b-row>
        </div>
    </div>
</template>

<script>
import AuthAPI from '../../../api/auth.js'

export default {
    name: 'AccountActivation',
    data () {
        return {
            notification: {
                type: 'danger',
                message: ''
            },
            activateAccountRequest: {
                status: 0
            },
            params:  {
                token: ''
            },
        }
    },
    created () {
        // Get token param from URL
        this.params.token = this.$route.params.token
        if (!this.params.token) {
            this.notification.type = 'danger'
            this.notification.message = 'Token not found'
            return false
        }

        // Check if the token is valid
        this.activateAccount(this.params.token);
    },
    methods: {
        activateAccount (token) {
            var vm = this;
            this.activateAccountRequest.status = 1
            AuthAPI.activateAccount(token)
            .then(response => {
                vm.notification.type = 'success'
                vm.notification.message = "Your account has been activated successfully. You can now login."
                // Mark request status as loaded succesully
                vm.activateAccountRequest.status = 2
            })
            .catch(error => {
                // Mark request status as failed to load
                vm.notification.type = 'danger'
                vm.activateAccountRequest.status = 3
                if (error.response) {
                    // Show message error
                    vm.notification.message = error.response.data.error ? error.response.data.error.message : error.response.data.message
                } else {
                    vm.notification.message = "Network error"
                }
            })
        },
    },
}
</script>
