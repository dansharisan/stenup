<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <b-row class="justify-content-center">
                <b-col md="4">
                    <b-card-group>
                        <b-card no-body class="mb-0">
                            <b-card-header><h2 class="m-0">User information</h2></b-card-header>
                            <b-card-body>
                                <p class="text-muted">
                                    Your profile is as below:
                                </p>
                                <b-input-group class="mb-3">
                                    <b-input-group-prepend is-text class="item-header-text">
                                        <i class="fas fa-at"></i>
                                    </b-input-group-prepend>
                                    <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Email"
                                    :value="user.email"
                                    disabled
                                    />
                                </b-input-group>
                                <b-input-group class="mb-3">
                                    <b-input-group-prepend is-text class="item-header-text">
                                        <i class="fas fa-user-circle"></i>
                                    </b-input-group-prepend>
                                    <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Role"
                                    :value="this.getRoles(this.user)"
                                    disabled
                                    />
                                </b-input-group>
                                <b-button
                                v-if="hasPermission(user, PERMISSION_NAME.VIEW_DASHBOARD)"
                                variant="link"
                                class="px-0"
                                @click="$router.push({ name: 'Dashboard' })"
                                >
                                    Go to Admin panel
                                </b-button>
                                <br v-if="hasPermission(user, PERMISSION_NAME.VIEW_DASHBOARD)"/>
                                <button type="button" class="btn px-0 btn-link" @click="goToHome()">
                                    Back to Home
                                </button>
                                <br />
                                <button type="button" class="btn px-0 btn-link" @click="logout()">
                                    Logout
                                </button>
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
import { AuthUtils } from '../../../mixins/auth-utils.js';
import { PERMISSION_NAME } from '../../../const.js'

export default {
    name: 'UserInfo',
    data () {
        return {
            PERMISSION_NAME: PERMISSION_NAME,
        }
    },
    computed: {
        user(){
            return this.$store.get('auth/user');
        },
    },
    methods: {
        goToHome() {
            this.$router.push({ name: 'Home' })
        },
        logout () {
            var vm = this
            vm.$store.dispatch('auth/logout')
            .then(response => {
                vm.$router.push({ name: 'Home' })
            })
            .catch(function(error) {
                if (error.response) {
                    // Show message error
                    vm.$snotify.error("Server error")
                } else {
                    vm.$snotify.error("Network error")
                }
            })
        },
    },
    mixins:[
        AuthUtils,
    ],
}
</script>
