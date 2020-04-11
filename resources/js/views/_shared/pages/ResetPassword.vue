<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <b-row class="justify-content-center">
                <b-col md="4" class="mr-2 ml-2 pr-0 pl-0">
                    <loading v-if="findTokenRequest.status == 2 && this.resetPasswordRequest.status != 2" :active="resetPasswordRequest.status == 1"></loading>
                    <b-card-group>
                        <b-card no-body class="mb-0">
                            <b-card-header><h2 class="m-0">Reset password</h2></b-card-header>
                            <b-card-body>
                                <p class="text-muted">
                                    Reset your password
                                </p>
                                <b-alert :variant="notification.type" :show="notification.message != null">
                                    {{ notification.message }}
                                </b-alert>
                                <template v-if="this.findTokenRequest.status == 2 && this.resetPasswordRequest.status != 2">
                                    <b-input-group class="mb-3">
                                        <b-input-group-prepend is-text class="item-header-text">
                                            <i class="fas fa-at"></i>
                                        </b-input-group-prepend>
                                        <b-input type="text" class="form-control" placeholder:="Email" :value="form.email" disabled/>
                                    </b-input-group>
                                    <b-input-group class="mb-3">
                                        <b-input-group-prepend is-text class="item-header-text">
                                            <i class="fas fa-key"></i>
                                        </b-input-group-prepend>
                                        <b-input type="password" class="form-control" v-model="form.password" placeholder="Password" v-on:keyup.enter="submit"/>
                                        <div class="invalid-feedback d-block" v-if="validation && validation.password">
                                            {{ validation.password[0] }}
                                        </div>
                                    </b-input-group>
                                    <b-input-group class="mb-3">
                                        <b-input-group-prepend is-text class="item-header-text">
                                            <i class="fas fa-key"></i>
                                        </b-input-group-prepend>
                                        <b-input type="password" class="form-control" v-model="form.password_confirmation" placeholder="Confirm password" v-on:keyup.enter="submit"/>
                                        <div class="invalid-feedback d-block" v-if="validation && validation.password_confirmation">
                                            {{ validation.password_confirmation[0] }}
                                        </div>
                                    </b-input-group>
                                </template>
                                <b-row>
                                    <b-col cols="6" class="text-left">
                                        <b-button variant="link" class="px-0" @click="$router.push({ name: 'Login' })">
                                            Go to Login
                                        </b-button>
                                        <button type="button" class="btn px-0 btn-link" @click="goToHome()">
                                            Back to Home
                                        </button>
                                    </b-col>
                                    <b-col cols="6" class="text-right" v-if="findTokenRequest.status==2 && this.resetPasswordRequest.status != 2">
                                        <b-button variant="success" class="px-4" @click="submit">
                                            Reset
                                        </b-button>
                                    </b-col>
                                    <b-col cols="6" class="text-right" v-else>
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
    name: 'ResetPassword',
    data () {
        return {
            form: {
                email: '',
                password: '',
                password_confirmation: ''
            },
            notification: {
                type: 'danger',
                message: null
            },
            validation: null,
            findTokenRequest: {
                status: 0
            },
            resetPasswordRequest: {
                status: 0
            },
            params:  {
                token: ''
            }
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
        this.checkToken(this.params.token);
    },
    methods: {
        goToHome () {
            this.$router.push({ name: 'Home' })
        },

        submit () {
            this.resetPassword(this.form.email, this.form.password, this.form.password_confirmation, this.params.token)
        },

        checkToken (token) {
            var vm = this;
            this.findTokenRequest.status = 1
            AuthAPI.findPasswordResetToken(token)
            .then(response => {
                vm.notification.type = 'success'
                // Mark request status as loaded succesully
                vm.findTokenRequest.status = 2
                vm.form.email = response.data.password_reset.email
            })
            .catch(error => {
                // Mark request status as failed to load
                vm.findTokenRequest.status = 3
                vm.notification.type = 'danger'
                if (error.response) {
                    // Show message error
                    vm.notification.message = error.response.data.error ? error.response.data.error.message : error.response.data.message
                    vm.validation = error.response.data.validation
                } else {
                    vm.notification.message = "Network error"
                }
            })
        },

        resetPassword (email, password, password_confirmation, token) {
            var vm = this;
            // Mark request status as loading
            this.resetPasswordRequest.status = 1
            // Get the access token
            AuthAPI.resetPassword(email, password, password_confirmation, token)
            .then(response => {
                vm.notification.type = 'success'
                // Mark request status as loaded succesully
                vm.resetPasswordRequest.status = 2
                vm.notification.message = "Your password has been reset successfully. Now you can login with your new password."
                // Show success message
                vm.$snotify.success("Password reset successfully")
            })
            .catch(error => {
                // Mark request status as failed to load
                vm.resetPasswordRequest.status = 3
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
