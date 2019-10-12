<template>
    <div class="animated fadeIn">
        <b-row>
            <b-col sm="6" lg="3">
                <b-card class="bg-success">
                    <div class="text-center text-light stats-loading">
                        <b-spinner></b-spinner>
                    </div>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
// import UserAPI from '../../../../api/user.js'
// import CardBarChart from '../../components/CardBarChart'
export default {
    name      : 'Dashboard',
    components: {
        // CardBarChart,
    },
    data: function () {
        return {
            userStats: {
                loadStatus: 0,
                totalUser: 0,
                last7DayStats: []
            },
        }
    },
    methods: {
    },
    created () {
        var vm = this;
        vm.userStats.loadStatus = 1
        // Get user stats
        // UserAPI.getUserStats()
        // .then(response => {
        //     vm.userStats.totalUser = response.data.user_stats.total
        //     vm.userStats.last7DayStats = response.data.user_stats.last_7_day_stats
        //     vm.userStats.loadStatus = 2
        // })
        // .catch(error => {
        //     vm.userStats.loadStatus = 3
        // })
    },
}
</script>

<style>
.stats-loading {
    padding-top: 19px;
    min-height: 70px;
}
</style>
