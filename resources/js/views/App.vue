<template>
    <div>
        <router-view />
        <div id="page-loading" class="middle-center" v-if="logoutLoadStatus == 1">
            <div>
                <loading :active="true" :is-full-page="true"></loading>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'App' ,
    computed: {
        logoutLoadStatus () {
            return this.$store.get('auth/logoutLoadStatus');
        },
    },
}
</script>
