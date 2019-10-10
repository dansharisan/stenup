<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <b-row class="justify-content-center">
                <b-col md="4" class="mr-2 ml-2 pr-0 pl-0">
                    <loading :active="request.status == 1"></loading>
                    <b-card-group>
                        <b-card no-body class="p-4 mb-0">
                            <b-card-body>
                                <h2>Register</h2>
                                <p class="text-muted">
                                    Create your account
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

                                <b-input-group class="mb-4">
                                    <b-input-group-prepend is-text class="item-header-text">
                                        <span>Re-pass</span>
                                    </b-input-group-prepend>
                                    <b-input v-model="form.password_confirmation" v-on:input="$v.form.password_confirmation.$touch()" :state="$v.form.password_confirmation.$dirty ? !$v.form.password_confirmation.$error : null" type="password" class="form-control" placeholder="********" v-on:keyup.enter="submit"/>
                                    <div class="invalid-feedback d-block" v-if="$v.form.password_confirmation.$invalid && validation && validation.password_confirmation">
                                        {{ validation.password_confirmation[0] }}
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
                                        <b-button variant="success" class="px-4" @click="submit">
                                            Register
                                        </b-button>
                                    </b-col>
                                </b-row>
                            </b-card-body>
                            <!-- <b-card-footer class="p-4">
                                <b-row>
                                    <b-col cols="6">
                                        <b-button block class="btn btn-facebook">
                                            <span class="ml-4">facebook</span>
                                        </b-button>
                                    </b-col>
                                    <b-col cols="6">
                                        <b-button block class="btn btn-twitter" type="button">
                                            <span class="ml-4">twitter</span>
                                        </b-button>
                                    </b-col>
                                </b-row>
                            </b-card-footer> -->
                        </b-card>
                    </b-card-group>
                </b-col>
            </b-row>
        </div>
    </div>
</template>

<script>
import { required, email, sameAs } from 'vuelidate/lib/validators'
import AuthAPI from '../../../api/auth.js'

export default {
    name: 'Register',
    data () {
        return {
            form: {
                email: '',
                password: '',
                password_confirmation: '',
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
                password_confirmation: {
                    required,
                    sameAsPassword: sameAs('password')
                },
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
            this.register(this.form.email, this.form.password, this.form.password_confirmation)
        },

        register (email, password, passwordConfirmation) {
            var vm = this;
            // Mark request status as loading
            vm.request.status = 1
            // Do the login
            var credential = {}
            credential.email = email
            credential.password = password
            credential.password_confirmation = passwordConfirmation

            AuthAPI.register(email, password, passwordConfirmation)
            .then(response => {
                // Mark request status as loaded succesully
                vm.request.status = 2
                // Show success message
                vm.notification.type = 'success'
                vm.notification.message = "We have sent an email to your email addres. Please follow the instruction to activate your account before you can login."
            })
            .catch(error => {
                // Mark request status as failed to load
                vm.request.status = 3
                vm.notification.type = 'danger'
                if (error.response) {
                    // Show error message
                    if (error.response.data && error.response.data.validation && !vm.$v.$anyError) {
                        vm.notification.message = error.response.data.validation[Object.keys(error.response.data.validation)[0]][0]
                    } else {
                        vm.notification.message = error.response.data.error ? error.response.data.error.message : error.response.data.message
                    }
                    vm.validation = error.response.data.validation
                } else {
                    vm.notification.message = "Network error"
                }
            })
        }
    }
}
</script>
