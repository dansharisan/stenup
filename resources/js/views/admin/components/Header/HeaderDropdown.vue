<template>
    <b-nav-item-dropdown ref="headerbtndropdown" right no-caret>
        <template slot="button-content" v-if="user">
            <abbr :title="user.email">
                <button type="button" class="btn btn-light">
                    <i class="fa fa-user" /> Account
                </button>
            </abbr>
        </template>
        <b-dropdown-header class="text-center">
            <strong>{{ user.email }}</strong>
        </b-dropdown-header>
        <b-dropdown-item @click="doLogout()">
            <i class="fas fa-sign-out-alt" /> Logout
        </b-dropdown-item>
    </b-nav-item-dropdown>
</template>

<script>
import AuthAPI from '../../../../api/auth.js'
import { AuthUtils } from '../../../../mixins/auth-utils.js';

export default {
    name: 'HeaderDropdown',
    computed: {
        user () {
            return this.$store.get('auth/user');
        },
    },
    methods: {
        doLogout () {
            var vm = this
            // Hide the dropdown
            vm.$refs.headerbtndropdown.hide()
            // Call logout function from mixin
            vm.logout()
        },
    },
    mixins:[
        AuthUtils,
    ],
}
</script>
