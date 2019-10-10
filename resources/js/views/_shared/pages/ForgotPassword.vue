<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <b-row class="justify-content-center">
                <b-col md="4" class="mr-2 ml-2 pr-0 pl-0">
                    <loading :active="request.status == 1"></loading>
                    <b-card-group>
                        <b-card no-body class="p-4 mb-0">
                            <b-card-body>
                                <h2>Forgot password</h2>
                                <p class="text-muted">
                                    Request to reset password
                                </p>
                                <div :class="'alert alert-' + this.notification.type" id="message" v-if="this.notification.message" role="alert">
                                    {{ this.notification.message }}
                                </div>
                                <b-input-group class="mb-3">
                                    <b-input-group-prepend is-text class="item-header-text">
                                        <span>Email</span>
                                    </b-input-group-prepend>
                                    <b-input v-model="form.email" :state="$v.form.email | state" type="text" class="form-control" placeholder="youremail@something.com" v-on:keyup.enter="submit"/>
                                    <div class="invalid-feedback d-block" v-if="$v.form.email.$invalid && validation && validation.email">
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
                                    <b-col cols="6" class="text-right">
                                        <b-button variant="primary" class="px-4" @click="submit">
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
import { required, email } from 'vuelidate/lib/validators'
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
                message: ''
            },
            validation: null,
            request: {
                status: 0
            },
        }
    },
    validations () {
        return {
            form: {
                email: { required, email }
            },
        }
    },
    methods: {
        goToHome() {
            this.$router.push({ name: 'Home' })
        },
        submit () {
            // Validation
            this.$v.$touch()
            this.requestPasswordReset(this.form.email)
        },

        requestPasswordReset (email) {
            var vm = this;
            // Mark request status as loading
            this.request.status = 1
            // Get the access token
            AuthAPI.createPasswordResetToken(email)
            .then(response => {
                vm.notification.type = 'success'
                vm.notification.message = "An email has been sent to your email address. Please check for further instructions about resetting password."
                // Mark request status as loaded succesully
                vm.request.status = 2
            })
            .catch(error => {
                // Mark request status as failed to load
                vm.request.status = 3
                vm.notification.type = 'danger'
                if (error.response) {
                    // Show message error
                    vm.notification.message = error.response.data.error ? error.response.data.error.message : error.response.data.message
                    vm.validation = error.response.data.validation
                } else {
                    vm.notification.message = "Network error"
                }
            })
        }
    },
}
</script>
