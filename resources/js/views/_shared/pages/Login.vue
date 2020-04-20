<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <b-row class="justify-content-center">
                <b-col md="4" class="mr-2 ml-2 pr-0 pl-0">
                    <loading :active="request.status == 1"></loading>
                    <b-card-group>
                        <b-card no-body class="mb-0">
                            <b-card-header><h2 class="m-0">Log in</h2></b-card-header>
                            <b-card-body>
                                <p class="text-muted">
                                    Log in to your account
                                </p>
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
                                    <div class="invalid-feedback d-block" v-if="validation && validation.password">
                                        {{ validation.password[0] }}
                                    </div>
                                </b-input-group>
                                <b-row>
                                    <b-col cols="6" class="text-left">
                                        <b-button variant="link" class="px-0" @click="$router.push({ name: 'Registration' })">
                                            Register
                                        </b-button>
                                        <br />
                                        <b-button variant="link" class="px-0" @click="$router.push({ name: 'PasswordResetRequest' })">
                                            Forgot password
                                        </b-button>
                                        <br />
                                        <b-button variant="link" class="px-0" @click="$router.push({ name: 'AccountActivationRequest' })">
                                            Activate account
                                        </b-button>
                                        <br />
                                        <b-button variant="link" class="px-0" @click="$router.push({ name: 'Home' })">
                                            Back to Home
                                        </b-button>
                                    </b-col>
                                    <b-col cols="6" class="text-right">
                                        <b-button variant="primary" class="px-4" @click="submit">
                                            Log in
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
export default {
    name: 'Login',
    data () {
        return {
            form: {
                email: '',
                password: '',
            },
            validation: null,
            request: {
                status: 0
            },
        }
    },
    methods: {
        submit () {
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
                // Show success message
                vm.$snotify.success("Login successfully")
                // Move to UserInfo page
                vm.$router.push({ name: 'UserInfo' })
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
