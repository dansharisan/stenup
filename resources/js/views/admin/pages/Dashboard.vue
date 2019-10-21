<template>
    <div class="animated fadeIn">
        <b-row>

            <b-col sm="6" lg="3">
                <b-card no-body class="bg-success">
                    <template v-if="getUserStatsRequest.loadStatus == 1">
                        <b-card-body class="p-0 text-center">
                            <div class="text-center text-light stats-loading">
                                <b-spinner></b-spinner>
                            </div>
                        </b-card-body>
                    </template>
                    <template v-else-if="getUserStatsRequest.loadStatus == 2">
                        <b-card-body class="pb-0">
                            <h4 class="mb-0">{{ getUserStatsRequest.totalUser }}</h4>
                            <p>Total registered users</p>
                        </b-card-body>
                        <card-bar-chart :data="getUserStatsRequest.last7DayStats" label="New user(s)" backgroundColor="rgba(255,255,255,.3)" chartId="card-chart-01" class="chart-wrapper px-3" style="height:70px;" height="70"/>
                    </template>
                    <template v-else-if="getUserStatsRequest.loadStatus == 3">
                        <b-card-body class="pb-0 text-center" style="margin-top: 25px">
                            <p>Data load error</p>
                        </b-card-body>
                        <div style="height: 70px;">&nbsp;</div>
                    </template>
                </b-card>
            </b-col>

        </b-row>
    </div>
</template>

<script>
import UserAPI from '../../../api/user.js'
import CardBarChart from '../components/CardBarChart'
export default {
    name      : 'Dashboard',
    components: {
        CardBarChart,
    },
    data: function () {
        return {
            getUserStatsRequest: {
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
        vm.getUserStatsRequest.loadStatus = 1
        // Get user stats
        UserAPI.getUserStats()
        .then(response => {
            vm.getUserStatsRequest.totalUser = response.data.user_stats.total
            vm.getUserStatsRequest.last7DayStats = response.data.user_stats.last_7_day_stats
            vm.getUserStatsRequest.loadStatus = 2
        })
        .catch(error => {
            vm.getUserStatsRequest.loadStatus = 3
        })
    },
}
</script>

<style>
.stats-loading {
    height: 152px;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
