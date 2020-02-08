<template>
    <b-nav-item-dropdown ref="headerbtndropdown" right no-caret>
        <template slot="button-content" v-if="user">
            <abbr :title="user.email">
                <button type="button" class="btn btn-light">
                    <i class="fa fa-user" /> Account
                </button>
            </abbr>
        </template>
        <b-dropdown-header tag="div" class="text-center">
            <strong>{{ user.email }}</strong>
        </b-dropdown-header>
        <b-dropdown-item @click="logout()">
            <i class="fas fa-sign-out-alt" /> Logout
        </b-dropdown-item>
    </b-nav-item-dropdown>
</template>

<script>
import AuthAPI from '../../../../api/auth.js'

export default {
    name: 'HeaderDropdown',
    computed: {
        user(){
            return this.$store.get('auth/user');
        },
    },
    methods: {
        logout () {
            var vm = this
            // Hide the dropdown
            vm.$refs.headerbtndropdown.hide()
            // Do the logout
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
}
</script>
