<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <b-row class="justify-content-center">
                <b-col md="4" class="mr-2 ml-2 pr-0 pl-0">
                    <loading :active="request.status == 1"></loading>
                    <b-card-group>
                        <b-card no-body class="mb-0">
                            <b-card-header><h2 class="m-0">Register</h2></b-card-header>
                            <b-card-body>
                                <p class="text-muted">
                                    Create your account
                                </p>
                                <b-alert :variant="notification.type" :show="notification.message != null" v-html="notification.message">
                                </b-alert>
                                <template v-if="request.status != 2">
                                    <b-input-group class="mb-3">
                                        <b-input-group-prepend is-text class="item-header-text">
                                            <i class="fas fa-at"></i>
                                        </b-input-group-prepend>
                                        <b-input v-model="form.email" type="text" :class="{'border-danger' : (validation && validation.email)}" placeholder="Email" v-on:keyup.enter="submit"/>
                                        <div class="invalid-feedback d-block" v-if="validation && validation.email">
                                            {{ validation.email[0] }}
                                        </div>
                                    </b-input-group>

                                    <b-input-group class="mb-3">
                                        <b-input-group-prepend is-text class="item-header-text">
                                            <i class="fas fa-key"></i>
                                        </b-input-group-prepend>
                                        <b-input v-model="form.password" type="password" :class="{'border-danger' : (validation && validation.password)}" placeholder="Password" v-on:keyup.enter="submit"/>
                                        <b-input-group-append is-text class="item-header-text cursor-pointer" @click="togglePasswordVisibility($event)">
                                            <i class="fa fa-eye-slash"></i>
                                        </b-input-group-append>
                                        <div class="invalid-feedback d-block" v-if="validation && validation.password">
                                            {{ validation.password[0] }}
                                        </div>
                                    </b-input-group>

                                    <b-input-group class="mb-3">
                                        <b-input-group-prepend is-text class="item-header-text">
                                            <i class="fas fa-key"></i>
                                        </b-input-group-prepend>
                                        <b-input v-model="form.password_confirmation" type="password" :class="{'border-danger' : (validation && validation.password_confirmation)}" placeholder="Confirm password" v-on:keyup.enter="submit"/>
                                        <b-input-group-append is-text class="item-header-text cursor-pointer" @click="togglePasswordVisibility($event)">
                                            <i class="fa fa-eye-slash"></i>
                                        </b-input-group-append>
                                        <div class="invalid-feedback d-block" v-if="validation && validation.password_confirmation">
                                            {{ validation.password_confirmation[0] }}
                                        </div>
                                    </b-input-group>
                                </template>
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
                                    <b-col cols="6" class="text-right" v-if="request.status != 2">
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
import AuthAPI from '../../../api/auth.js'
import { DOMUtils } from '../../../mixins/dom-utils.js'
export default {
    name: 'Registration',
    mixins:[
        DOMUtils,
    ],
    data () {
        return {
            form: {
                email: '',
                password: '',
                password_confirmation: '',
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
        submit () {
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
                vm.$snotify.success("Register successfully")
                vm.notification.type = 'info'
                vm.notification.message = "We have sent an email to <strong>" + vm.form.email + "</strong>. Please follow the instruction to activate your account before you can login."
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
    }
}
</script>
