<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <b-row class="justify-content-center">
                <b-col md="4" class="mr-2 ml-2 pr-0 pl-0">
                    <loading :active="request.status == 1"></loading>
                    <b-card-group>
                        <b-card no-body class="mb-0">
                            <b-card-header><h2 class="m-0">Change password</h2></b-card-header>
                            <b-card-body>
                                <p class="text-muted">
                                    Change your password
                                </p>
                                <b-alert :variant="notification.type" :show="notification.message != null" v-html="notification.message">
                                </b-alert>
                                <template v-if="request.status != 2">
                                    <b-input-group class="mb-3">
                                        <b-input-group-prepend is-text class="item-header-text">
                                            <i class="fas fa-key"></i>
                                        </b-input-group-prepend>
                                        <b-input type="password" :class="{'border-danger' : (validation && validation.password)}" v-model="form.password" placeholder="Current password" v-on:keyup.enter="submit"/>
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
                                        <b-input type="password" :class="{'border-danger' : (validation && validation.new_password)}" v-model="form.new_password" placeholder="New password" v-on:keyup.enter="submit"/>
                                        <b-input-group-append is-text class="item-header-text cursor-pointer" @click="togglePasswordVisibility($event)">
                                            <i class="fa fa-eye-slash"></i>
                                        </b-input-group-append>
                                        <div class="invalid-feedback d-block" v-if="validation && validation.new_password">
                                            {{ validation.new_password[0] }}
                                        </div>
                                    </b-input-group>
                                    <b-input-group class="mb-3">
                                        <b-input-group-prepend is-text class="item-header-text">
                                            <i class="fas fa-key"></i>
                                        </b-input-group-prepend>
                                        <b-input type="password" :class="{'border-danger' : (validation && validation.new_password_confirmation)}" v-model="form.new_password_confirmation" placeholder="Confirm new password" v-on:keyup.enter="submit"/>
                                        <b-input-group-append is-text class="item-header-text cursor-pointer" @click="togglePasswordVisibility($event)">
                                            <i class="fa fa-eye-slash"></i>
                                        </b-input-group-append>
                                        <div class="invalid-feedback d-block" v-if="validation && validation.new_password_confirmation">
                                            {{ validation.new_password_confirmation[0] }}
                                        </div>
                                    </b-input-group>
                                </template>
                                <b-row>
                                    <b-col cols="6" class="text-left">
                                        <b-button variant="link" class="px-0" @click="$router.push({ name: 'Home' })">
                                            Back to Home
                                        </b-button>
                                    </b-col>
                                    <b-col cols="6" class="text-right" v-if="request.status != 2">
                                        <b-button variant="success" class="px-4" @click="submit">
                                            Change
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
import { DOMUtils } from '../../../mixins/dom-utils.js'
export default {
    name: 'PasswordChange',
    mixins:[
        DOMUtils,
    ],
    data () {
        return {
            form: {
                password: '',
                new_password: '',
                new_password_confirmation: '',
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
            this.changePassword(this.form.password, this.form.new_password, this.form.new_password_confirmation)
        },
        changePassword (password, new_password, new_password_confirmation) {
            var vm = this;
            // Mark request status as loading
            this.request.status = 1
            // Send API to change password
            AuthAPI.changePassword(password, new_password, new_password_confirmation)
            .then(response => {
                vm.notification.type = 'success'
                vm.notification.message = "Your password has been changed successfully."
                // Mark request status as loaded succesully
                vm.request.status = 2
                // Show success message
                vm.$snotify.success("Changed password successfully")
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
