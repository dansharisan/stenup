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
                                    Activating your account
                                </p>
                                <div :class="'alert alert-' + this.notification.type" id="message" v-if="this.notification.message" role="alert">
                                    {{ this.notification.message }}
                                </div>
                                <b-input-group class="mb-3" v-if="this.form.email">
                                    <b-input-group-prepend is-text class="item-header-text">
                                        <span>Email</span>
                                    </b-input-group-prepend>
                                    <b-input type="text" class="form-control" :value="form.email" disabled/>
                                </b-input-group>
                                <b-row>
                                    <b-col cols="6" class="text-left">
                                        <b-button variant="link" class="px-0" @click="$router.push({ name: 'Login' })">
                                            Go to Login
                                        </b-button>
                                        <button type="button" class="btn px-0 btn-link" @click="goToHome()">
                                            Back to Home
                                        </button>
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
    name: 'ActivateAccount',
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
            form: {
                email: null,
            },
        }
    },
    created () {
        // Get token param from URL
        this.params.token = this.$route.params.token
        if (!this.params.token) {
            message = 'Token not found'
            return false
        }

        // Check if the token is valid
        this.activateAccount(this.params.token);
    },
    methods: {
        goToHome () {
            this.$router.push({ name: 'Home' })
        },

        activateAccount (token) {
            var vm = this;
            this.activateAccountRequest.status = 1
            AuthAPI.activateAccount(token)
            .then(response => {
                vm.notification.type = 'success'
                vm.notification.message = "Your account has been activated successfully. You can now login."
                // Mark request status as loaded succesully
                vm.activateAccountRequest.status = 2
                vm.form.email = response.data.user.email
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
