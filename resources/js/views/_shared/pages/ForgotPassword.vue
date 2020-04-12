<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <b-row class="justify-content-center">
                <b-col md="4" class="mr-2 ml-2 pr-0 pl-0">
                    <loading :active="request.status == 1"></loading>
                    <b-card-group>
                        <b-card no-body class="mb-0">
                            <b-card-header><h2 class="m-0">Forgot password</h2></b-card-header>
                            <b-card-body>
                                <p class="text-muted">
                                    Request to reset password
                                </p>
                                <b-alert :variant="notification.type" :show="notification.message != null">
                                    {{ notification.message }}
                                </b-alert>
                                <b-input-group class="mb-3" v-if="request.status != 2">
                                    <b-input-group-prepend is-text class="item-header-text">
                                        <i class="fas fa-at"></i>
                                    </b-input-group-prepend>
                                    <b-input v-model="form.email" type="text" :class="{'border-danger' : (validation && validation.email)}" placeholder="Email" v-on:keyup.enter="submit"/>
                                    <div class="invalid-feedback d-block" v-if="validation && validation.email">
                                        {{ validation.email[0] }}
                                    </div>
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
                                    <b-col cols="6" class="text-right" v-if="request.status != 2">
                                        <b-button variant="success" class="px-4" @click="submit">
                                            Request
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
    name: 'ForgotPassword',
    data () {
        return {
            form: {
                email: '',
            },
            notification: {
                type: 'danger',
                message: null
            },
            validation: null,
            request: {
                status: 0
            },
        }
    },
    methods: {
        goToHome () {
            this.$router.push({ name: 'Home' })
        },
        submit () {
            this.requestPasswordReset(this.form.email)
        },

        requestPasswordReset (email) {
            var vm = this;
            // Mark request status as loading
            this.request.status = 1
            // Get the access token
            AuthAPI.createPasswordResetToken(email)
            .then(response => {
                vm.notification.type = 'info'
                vm.notification.message = "An email has been sent to " + vm.form.email + ". Please check that email for further instructions about resetting password."
                // Mark request status as loaded succesully
                vm.request.status = 2
                // Show success message
                vm.$snotify.success("Request password reset successfully")
            })
            .catch(error => {
                // Mark request status as failed to load
                vm.request.status = 3
                // Show error message
                if (error.response) {
                    vm.validation = error.response.data.validation
                    vm.$snotify.error(error.response.data.error ? error.response.data.error.message : error.response.data.message)
                } else {
                    vm.$snotify.error("Network error")
                }
            })
        }
    },
}
</script>
