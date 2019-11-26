<template>
    <div class="animated fadeIn">
        Users placeholder
        <!-- <bootstrap-table :columns="columns" :data="data" :options="options"></bootstrap-table> -->
    </div>
</template>

<script>
import UserAPI from '../../../api/user.js'
export default {
    name      : 'User',
    components: {
    },
    data: function () {
        return {
            getUsersRequest: {
                loadStatus: 0,
                data: {}
            },
        }
    },
    methods: {
        getUsers(page = 1, perPage = 15) {
            var vm = this
            vm.getUsersRequest.loadStatus = 1
            UserAPI.getUsers(page, perPage)
            .then(response => {
                vm.getUsersRequest.data = response.data.users
                vm.getUsersRequest.loadStatus = 2
            })
            .catch(error => {
                vm.getUsersRequest.loadStatus = 3
            })
        },
    },
    created () {
        var vm = this
        vm.getUsers()
    },
}
</script>
