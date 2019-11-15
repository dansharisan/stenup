<template>
    <b-nav-item-dropdown right no-caret>
        <template slot="button-content" v-if="user">
            <abbr :title="user.email">
                <button type="button" class="btn btn-light" v-if="logoutLoadStatus == 1">
                    <div class="text-center text-info">
                        <!-- <b-spinner small></b-spinner> -->
                        <loading :active="true"></loading>
                    </div>
                </button>
                <button type="button" class="btn btn-light" v-else><i class="fa fa-user" /> Account</button>
            </abbr>
        </template>
        <b-dropdown-header tag="div" class="text-center" v-if="user">
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
    data: () => {
        return {
            logoutLoadStatus: 0,
        }
    },
    computed: {
        user(){
            return this.$store.get('auth/user');
        },
    },
    methods: {
        logout () {
            var vm = this
            vm.logoutLoadStatus = 1
            vm.$store.dispatch('auth/logout')
            .then(response => {
                vm.logoutLoadStatus = 2
                vm.$router.push({ name: 'Home' })
            })
            .catch(function(error) {
                vm.logoutLoadStatus = 3
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
