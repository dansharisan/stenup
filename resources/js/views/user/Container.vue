<template>
    <div class="app">
        <vue-snotify></vue-snotify>
        <br />
        <h1><center>User site container</center></h1>
        <br />
        <template v-if="user.id">
            <a href="#" @click="$router.push('/userinfo')"><h3><center>Go to UserInfo page</center></h3></a>
            <a href="#" @click="logout()"><h3><center>Logout</center></h3></a>
        </template>
        <a v-else href="#" @click="$router.push('/login')"><h3><center>Go to Login page</center></h3></a>
        <div class="container-fluid">
            <center><router-view /></center>
        </div>
    </div>
</template>

<script>
import UserInfo from '../_shared/pages/UserInfo.vue'
import { AuthUtils } from '../../mixins/auth-utils.js';

export default {
    components: {
        UserInfo
    },
    computed: {
        user () {
            return this.$store.get('auth/user');
        }
    },
    mixins:[
        AuthUtils,
    ],
}
</script>
