<template>
    <div class="app">
        <vue-snotify></vue-snotify>
        <br />
        <h1><center>User site container</center></h1>
        <br />
        <a v-if="user.id" href="#" @click="$router.push('/userinfo')"><h3><center>Go to UserInfo page</center></h3></a>
        <a v-else href="#" @click="$router.push('/login')"><h3><center>Go to Login page</center></h3></a>
    </div>
</template>

<script>
import UserInfo from '../_shared/pages/UserInfo.vue'
export default {
    components: {
        UserInfo
    },
    computed: {
        user(){
            return this.$store.get('auth/user');
        },
    },
}
</script>
