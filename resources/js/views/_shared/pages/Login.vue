<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <b-row class="justify-content-center">
                <b-col md="4" class="mr-2 ml-2 pr-0 pl-0">
                    <loading :active="request.status == 1"></loading>
                    <b-card-group>
                        <b-card no-body class="mb-0">
                            <b-card-header><h2 class="m-0">Login</h2></b-card-header>
                            <b-card-body>
                                <p class="text-muted">
                                    Sign In to your account
                                </p>
                                <div :class="'alert alert-' + this.notification.type" id="message" v-if="this.notification.message" role="alert">
                                    {{ this.notification.message }}
                                </div>
                                <b-input-group class="mb-3">
                                    <b-input-group-prepend is-text class="item-header-text">
                                        <span>Email</span>
                                    </b-input-group-prepend>
                                    <b-input v-model="form.email" v-on:input="$v.form.email.$touch()" :state="$v.form.email.$dirty ? !$v.form.email.$error : null" type="text" class="form-control" placeholder="youremail@something.com" v-on:keyup.enter="submit"/>
                                    <div class="invalid-feedback d-block" v-if="$v.form.email.$invalid && validation && validation.email">
                                        {{ validation.email[0] }}
                                    </div>
                                </b-input-group>
                                <b-input-group class="mb-3">
                                    <b-input-group-prepend is-text class="item-header-text">
                                        <span>Password</span>
                                    </b-input-group-prepend>
                                    <b-input v-model="form.password" v-on:input="$v.form.password.$touch()" :state="$v.form.password.$dirty ? !$v.form.password.$error : null" type="password" class="form-control" placeholder="********" v-on:keyup.enter="submit"/>
                                    <div class="invalid-feedback d-block" v-if="$v.form.password.$invalid && validation && validation.password">
                                        {{ validation.password[0] }}
                                    </div>
                                </b-input-group>
                                <b-row>
                                    <b-col cols="6" class="text-left">
                                        <b-button variant="link" class="px-0" @click="$router.push({ name: 'Register' })">
                                            Register
                                        </b-button>
                                        <b-button variant="link" class="px-0" @click="$router.push({ name: 'ForgotPassword' })">
                                            Forgot password
                                        </b-button>
                                        <button type="button" class="btn px-0 btn-link" @click="goToHome()">
                                            Back to Home
                                        </button>
                                    </b-col>
                                    <b-col cols="6" class="text-right">
                                        <b-button variant="primary" class="px-4" @click="submit">
                                            Login
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

export default {
    name: 'Login',
    data () {
        return {
            form: {
                email: '',
                password: '',
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
                email: { required, email },
                password: { required },
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
            this.login(this.form.email, this.form.password)
        },

        login (email, password) {
            var vm = this;
            // Mark request status as loading
            vm.request.status = 1
            // Do the login
            var credential = {}
            credential.email = email
            credential.password = password
            vm.$store.dispatch('auth/login', credential)
            .then(res => {
                // Mark request status as loaded succesully
                vm.request.status = 2
                // Move to UserInfo page
                vm.$router.push({ name: 'Home' })
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
